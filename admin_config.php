<?php
$eplug_admin = true;
require_once(__DIR__.'/../../class2.php');
if(!getperms('P')){
	e107::redirect('admin');
	exit;
  }


e107::lan('estate',true,true);

/*
$phpver = phpversion();
$phpinf = explode('.',$phpver);
if($phpinf[0] > 7 || $phpinf[0] < 5){
  require_once(e_ADMIN."header.php");
  e107::getRender()->tablerender(EST_PHPVERR0, EST_PHPVERR1.' '.$phpver.' '.EST_PHPVERR2,'estate-phperror');
  require_once(e_ADMIN."footer.php");
  exit;
  }
unset($phpver,$phpinf);
*/

if(ADMINPERMS === '0' || intval(EST_USERPERM) > 0){
  e107::css('url',e_PLUGIN.'estate/css/admin.css');
  e107::css('url',e_PLUGIN.'estate/js/leaflet/leaflet.css');
  e107::css('url',e_PLUGIN.'estate/js/cropperjs/dist/cropper.css');
  e107::js('estate','js/adm/core.js', 'jquery');
  e107::js('jquery', 5);
  e107::js('estate','js/Sortable/Sortable.js', 'jquery');
  
  //<script src="" type="text/javascript"></script>
	//<script src="https://ajax.googleapis.com/ajax/libs/scriptaculous/1.9.0/scriptaculous.js" type="text/javascript"></script>
  
  //e107::js('estate','https://ajax.googleapis.com/ajax/libs/prototype/1.7.0.0/prototype.js');
  //e107::js('estate','js/cropper/scriptaculous.js', 'jquery');
  e107::js('estate','js/cropperjs/dist/cropper.js', 'jquery');
  require_once(e_HANDLER.'form_handler.php');
  
  $EST_PREF = e107::pref('estate');
  
  if(e_QUERY){
    $QRYX = str_replace('amp;', '', explode('&',e_QUERY));
    foreach($QRYX as $x=>$y){
      $z = explode('=',$y);
      define('EST_'.strtoupper($z[0]), $z[1]);
      }
    unset($x,$y,$z);
    }
  if(!defined("EST_MODE")){define("EST_MODE","estate_properties");}
  if(!defined("EST_ACTION")){define("EST_ACTION","list");}
  if(!defined("EST_ID")){define("EST_ID",0);}
  
  //require_once(e_HANDLER.'validator_class.php');
  require_once(e_PLUGIN.'estate/estate_defs.php');
  require_once(e_PLUGIN.'estate/ui/tabstruct.php');
  require_once(e_PLUGIN.'estate/ui/core.php');
  
  $estateCore = new estateCore;
  $EST_AGENT = $estateCore->estGetUserById(USERID);
  $EST_AGENT['perm'] = intval(EST_USERPERM);
  define("EST_AGENTID",intval($EST_AGENT['agent_idx']));
  define("EST_AGENCYID",intval($EST_AGENT['agent_agcy']));
  
  if(!empty($_POST)){require_once(e_PLUGIN.'estate/ui/uipost.php');}
  

  
  
  require_once(e_PLUGIN.'estate/ui/menus.php');
  require_once(e_PLUGIN.'estate/ui/instruct.php');
  require_once(e_PLUGIN.'estate/ui/agencies.php');
  require_once(e_PLUGIN.'estate/ui/listing.php');
  require_once(e_PLUGIN.'estate/ui/presets.php');
  
  if(intval($EST_PREF['map_jssrc']) == 1 || trim($EST_PREF['map_key']) == '' || trim($EST_PREF['map_url']) == ''){
    e107::js('estate','js/leaflet/leaflet.js', 'jquery',2);
    }
  else{
    if(trim($EST_PREF['map_key']) !== '' && trim($EST_PREF['map_url']) !== ''){
      $tp = e107::getParser();
      e107::js('url',$tp->toHTML($EST_PREF['map_url']).'" integrity="'.$tp->toHTML($EST_PREF['map_key']).'" crossorigin="', 'jquery',2);
      // my url https://unpkg.com/leaflet@1.9.2/dist/leaflet.js
      // my key: sha256-o9N1jGDZrf5tS+Ft4gbIK7mYMipq9lqpVJ91xHSyKhg=
      }
    else{
      e107::js('estate','js/leaflet/leaflet.js', 'jquery',2);
      }
    }
  
  
  foreach($EST_CLASSES as $k=>$v){
    if($v === false){$clierr .= '<li>'.EST_GEN_MISSING.' '.LAN_USERCLASS.' <b>'.$k.'</b></li>';}
    }
  
  if($clierr){
    e107::getMessage()->addError('<div>'.EST_ERR_CLASSMIS1.':<ul>'.$clierr.'</ul>'.EST_ERR_CLASSMIS2.'</div>');
    }
  
  
  if(isset($EST_PREF['firsttime']) && empty($_POST['estFirsttimeDone'])){
    new estate_adminFirst();
    require_once(e_ADMIN."auth.php");
    e107::getAdminUI()->runPage();
    echo '<div id="estUIDdiv" data-euid="'.USERID.'"></div>';
    require_once(e_ADMIN."footer.php");
    exit;
    }
  
  /*
  $EST_ALLAGENTS = $estateCore->getAllAgents();
  if(count($EST_ALLAGENTS) == 0 && empty($_POST['estFirsttimeDone'])){e107::getMessage()->addError(EST_ERR_NOUSERSINCLASS.'<br >'.EST_ERR_NOUSERSINCLASS1);}
  
  if(count($EST_ALLAGENTS) == 0 && ADMINPERMS === '0' && empty($_POST['estFirsttimeDone'])){
    new estate_adminUser();
    require_once(e_ADMIN."auth.php");
    e107::getAdminUI()->runPage();
    echo '<div id="estUIDdiv" data-euid="'.USERID.'"></div>';
    require_once(e_ADMIN."footer.php");
    exit;
    }
  */
  
  
  if(ADMINPERMS === '0'){new estate_adminMain();}
  else{
    if(EST_AGENTID == 0){new estate_newAgent();}
    else{
      switch(EST_USERPERM){
        case 3 : new estate_adminMain(); break;
        case 2 : new estate_adminManager(); break;
        case 1 : new estate_adminAgent(); break;
        default : new estate_adminFail(); break;
        }
      }
    }
  }
else{
  require_once(e_PLUGIN.'estate/ui/instruct.php');
  require_once(e_PLUGIN.'estate/ui/menus.php');
  new estate_adminFail();
  }

require_once(e_ADMIN."auth.php");
e107::getAdminUI()->runPage();
echo '<div id="estUIDdiv" data-euid="'.USERID.'"></div>';
require_once(e_ADMIN."footer.php");
exit;