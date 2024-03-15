<?php
if (!defined('e107_INIT')) { exit; }

//Check e107 User Data and Estate Prefs/Classes
if(intval(USERID) == 0){$err = "Not Allowed";}
if(intval(EST_USERPERM) == 0 && (intval($EST_PREF['public_act']) == 0 || intval($EST_PREF['public_act']) == 255)){$err = "Not Allowed";}
else{
  if(intval(EST_USERPERM) > 0 || (USERID > 0 && check_class($EST_PREF['public_act']))){
    if($qs[0] == 'edit' && intval($qs[1]) == 0){e107::redirect(e_SELF."?new.0.0");}
    }
  else{$err = "Not Allowed";}
  }

if($err){
  e107::getMessage()->addError($err);
  require_once(HEADERF);
  require_once(FOOTERF);
  exit;
  }


$USRSEL = "user_id,user_name,user_loginname,user_email,user_class,user_admin,user_perms,user_signature,user_image ";

if($AGENT = $sql->retrieve("estate_agents","*","agent_uid = '".USERID."' LIMIT 1",true)){
  define("EST_AGENTID",intval($AGENT[0]['agent_idx']));
  define("AGENT_NAME",$AGENT[0]['agent_name']);
  define("EST_AGENCYID",intval($AGENT[0]['agent_agcy']));
  if($AGENCY = $sql->retrieve("estate_agencies","agency_name","agency_idx = '".intval($AGENT[0]['agent_agcy'])."' LIMIT 1",true)){
    define("AGENCY_NAME",$AGENCY[0]['agency_name']);
    }
  else{define("AGENCY_NAME","(".EST_ERR_AGYNOTFOUND.")");}
  }
else{
  define("EST_AGENTID",0);
  define("AGENT_NAME","(".EST_ERR_NOTAGENT.")");
  define("EST_AGENCYID",0);
  define("AGENCY_NAME","");
  }


//$tp = e107::getParser();
//$ret1['user_profimg'] = $tp->toAvatar($ret1,array('type'=>'url'));
    
// Load Listing by ID if any





$DTA = array();

if(!$err && intval($qs[1]) > 0){
  if($prop = $sql->retrieve("estate_properties","*","prop_idx='".intval($qs[1])."' LIMIT 1",true)){
    $DTA['prop'] = $prop[0];
    if(intval($DTA['prop']['prop_agent']) > 0){
      if($agt = $sql->retrieve("estate_agents","*","agent_idx='".intval($DTA['prop']['prop_agent'])."' LIMIT 1",true)){
        $DTA['agent'] = $agt[0];
        //extract($agt[0]);
        if($agy = $sql->retrieve("estate_agencies","*","agency_idx='".intval($DTA['prop']['prop_agency'])."' LIMIT 1",true)){
          $DTA['agency'] = $agy[0];
          //extract($agy[0]);
          }
        if($usr = $sql->retrieve("user",$USRSEL,"user_id='".intval($DTA['agent']['agent_uid'])."' LIMIT 1",true)){
          $DTA['user'] = $usr[0];
          //extract($usr[0]);
          }
        }
      }
    else{
      if($usr = $sql->retrieve("user",$USRSEL,"user_id='".intval($DTA['prop']['prop_uidcreate'])."' LIMIT 1",true)){
        $DTA['user'] = $usr[0];
        //extract($usr[0]);
        }
      }
    }
  }



// If Listing Loaded, Check current Estate User permissions against Property Listing Owner
if(intval($DTA['prop']['prop_idx']) > 0){
  if(EST_USERPERM == 0 && intval($DTA['prop']['prop_uidcreate']) !== USERID){
    $err[1] = EST_ERR_NOTYOURPROP;
    $err[2] = EST_ERR_NOTYOURPROP1.' '.$tp->toHTML(USERNAME).' ('.$tp->toHTML(USEREMAIL).')';
    }
  if(EST_USERPERM == 1 && intval($DTA['prop']['prop_agent']) !== EST_AGENTID){
    $err[1] = EST_ERR_NOTYOURPROP;
    $err[2] = EST_ERR_NOTYOURPROP1.' '.$tp->toHTML(AGENT_NAME).'  of '.$tp->toHTML(AGENCY_NAME).')';
    }
  if(EST_USERPERM == 2){
    if(intval($DTA['prop']['prop_agency']) == EST_AGENCYID){
      if(intval($DTA['prop']['prop_agent']) !== EST_AGENTID){
        e107::getMessage()->addInfo(EST_GEN_OTHERPROP1.' '.($DTA['prop']['prop_agent'] > 0 ? 'Agent '.$tp->toHTML($DTA['agent']['agent_name']) : 'User '.$tp->toHTML(USERNAME) ).'.');
        }
      }
    else{
      if(intval($DTA['prop']['prop_agency']) == 0 || $DTA['prop']['prop_agent'] == 0){
        $err[1] = EST_GEN_OTHERPROP2.' '.$tp->toHTML($DTA['user']['user_name']);
        $err[2] = EST_ERR_NOTYOURPROP2;
        }
      else{
        $err[1] = EST_ERR_NOTYOURPROP3;
        $err[2] = EST_ERR_NOTYOURPROP1.' '.$tp->toHTML(AGENCY_NAME).' ';
        }
      }
    } 
  
  if(EST_USERPERM > 2){
    
    }
  
  //If no Errors, Load Prop Subdata
  if(!$err){
    //$MEDIA = $sql->retrieve("estate_media","*","media_propidx='".intval($DTA['prop']['prop_idx)."'",true);

    $ESTDTA = estGetSpaces($DTA['prop']['prop_idx']);
    $MEDIA = $ESTDTA[0];
    $SPACES = $ESTDTA[1];
    
    //estate_featurelist
    }
  }
else{
  if($qs[0] !== 'new'){e107::redirect(e_SELF."?new.0.0");}
  $DTA['prop']['prop_idx'] = intval(0);
  $DTA['prop']['prop_agency'] = intval(EST_AGENCYID);
  $DTA['prop']['prop_agent'] = intval(EST_AGENTID);
  $DTA['prop']['prop_uidcreate'] = USERID;
  $DTA['prop']['prop_uidupdate'] = USERID;
  $DTA['prop']['prop_datecreated'] = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
  $DTA['prop']['prop_dateupdated'] = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
  
  if($DTA['prop']['prop_agent'] > 0){
    if($agt = $sql->retrieve("estate_agents","*","agent_idx='".intval($DTA['prop']['prop_agent'])."' LIMIT 1",true)){
      $DTA['agent'] = $agt[0];
      //extract($agt[0]);
      if($agy = $sql->retrieve("estate_agencies","*","agency_idx='".intval($DTA['prop']['prop_agency'])."' LIMIT 1",true)){
        $DTA['agency'] = $agy[0];
        //extract($agy[0]);
        }
      if($usr = $sql->retrieve("user",$USRSEL,"user_id='".intval($DTA['agent']['agent_uid'])."' LIMIT 1",true)){
        $DTA['user'] = $usr[0];
        //extract($usr[0]);
        }
      }
    }
  }







$pretext = '
  <h2>THIS FORM IS IN DEVELOPMENT</h2>
  <div>EST_USERPERM: '.EST_USERPERM.'</div>
  <div>public_act: '.intval($EST_PREF['public_act']).'</div>
  <div>You Are: '.$tp->toHTML(USERNAME).' ('.$tp->toHTML(USEREMAIL).') ['.$tp->toHTML(AGENT_NAME).' of '.EST_AGENCYID.' '.$tp->toHTML(AGENCY_NAME).']</div>
  <div>Property #'.$DTA['prop']['prop_idx'].': '.$tp->toHTML($DTA['prop']['prop_name']).'</div>
  <div>Agent & Agency: '.$DTA['prop']['prop_agent'].' '.$tp->toHTML($DTA['agent']['agent_name']).' of '.$tp->toHTML($DTA['agency']['agency_name']).' (#'.$DTA['prop']['prop_agency'].')</div>
  <div>Created By UID: '.$DTA['prop']['prop_uidcreate'].' (Updated By UID '.$DTA['prop']['prop_uidupdate'].')</div>
  <div>Street: '.$DTA['prop']['prop_addr1'].'</div>';


if($err){
  foreach($err as $k=>$v){e107::getMessage()->addError($v);}
  require_once(HEADERF);
  echo $pretext;
  require_once(FOOTERF);
  exit;
  }



if($_POST){
  $PROP_FLDS = $sql->db_FieldList('estate_properties');
  $PROP_FIXD = array('prop_idx','prop_agency','prop_agent','prop_uidcreate','prop_datecreated','prop_views');
  
  $_POST['prop_idx'] = intval($DTA['prop']['prop_idx']);
  $_POST['prop_agency'] = intval($DTA['prop']['prop_agency']);
  $_POST['prop_agent'] = intval($DTA['prop']['prop_agent']);
  
  $_POST['prop_datecreated'] = $DTA['prop']['prop_datecreated'];
  $_POST['prop_uidcreate'] = intval($DTA['prop']['prop_uidcreate']);
  
  $_POST['prop_uidupdate'] = intval(USERID);
  $_POST['prop_dateupdated'] = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));;
  
  $_POST['prop_hours'] = e107::serialize($_POST['prop_hours']);
  $_POST['prop_views'] = intval($DTA['prop']['prop_views']);
  
  
  
  
  if(intval($DTA['prop']['prop_idx']) > 0){
    $PROPIDX = intval($DTA['prop']['prop_idx']);
    foreach($PROP_FLDS as $fld){
      if(!in_array($fld,$PROP_FIXD)){
        $QRY .= ($QRY ? ", " : "").$fld."='".$tp->toDB($_POST[$fld] ? $_POST[$fld] : $DTA['prop'][$fld])."'";
        $postext .= '<div>['.$fld.'] '.$_POST[$fld].'</div>';
        }
      }
    $QRY .= "WHERE prop_idx='".$PROPIDX."' LIMIT 1";
    
    $postext = '<h2>DB Update: #'.$PROPIDX.'</h2><div>'.$QRY.'</div><p>'.$postext.'</p>';
    }
  else{
    foreach($PROP_FLDS as $fld){
      $QRY .= ($QRY ? ", " : "").$fld."='".$tp->toDB($_POST[$fld])."'";
      $postext .= '<div>['.$fld.'] '.$_POST[$fld].'</div>';
      }
    
    $postext = '<h2>DB INSERT</h2><div>'.$QRY.'</div><p>'.$postext.'</p>';
    
    $PROPIDX = 0; //insert new record
    }
  
  
  $dberr = $sql->getLastErrorText();
  
  if(intval($PROPIDX) > 0){
    if($qs[0] == 'new' && intval($DTA['prop']['prop_idx']) == 0){
      //e107::redirect(e_SELF."?edit.".intval($PROPIDX).".0");
      }
    
    }
  else{
    
    }
  }


e107::css('url',e_PLUGIN.'estate/css/admin.css');
e107::css('url',e_PLUGIN.'estate/css/oa.css');
e107::css('url',e_PLUGIN.'estate/js/cropperjs/dist/cropper.css');
e107::js('estate','js/Sortable/Sortable.js', 'jquery');
e107::js('estate','js/oa.js', 'jquery');

require_once(e_HANDLER."form_handler.php");
require_once(e_PLUGIN.'estate/ui/tabstruct.php');
require_once(e_PLUGIN.'estate/ui/core.php');

include_once('qry.php');




if(intval($DTA['prop']['prop_idx']) > 0){
  $nsHead = '<div id="estMiniNav"><a href="'.e_SELF.'?view.'.$DTA['prop']['prop_idx'].'" title="'.EST_GEN_VIEWLISTING.'"><i class="fa fa-eye"></i></a>';
  if(getperms('P')){
    $nsHead .= '<a class="noMobile" href="'.EST_PTH_ADMIN.'?action=edit&id='.intval($DTA['prop']['prop_idx']).'" title="'.EST_GEN_FULLEDIT.'"><i class="fa fa-pencil-square-o"></i></a>';
    }
  $nsHead .= '</div>'.EST_GEN_EDIT.': '.$tp->toHTML($DTA['prop']['prop_name']);
  }
else{
  $nsHead = EST_GEN_NEW.' '.EST_GEN_LISTING;
  }





$tmpl = e107::getTemplate('estate');
$sc = e107::getScBatch('estate',true);
$sc->setVars($prop[0]);


e107::js('inline','var estMapPins = '.$tp->parseTemplate($tmpl['pins'], false, $sc).'; ', 'jquery',2);

$frm = e107::getForm(false, true);
$timeZones = systemTimeZones();




if($_POST){
  $DTA['prop']['prop_hours'] = $_POST['prop_hours'];
  }

$estateCore = new estateCore;




if($DTA['prop']['prop_idx']){$UpBtnTxt = EST_GEN_UPDATE;}
else{$UpBtnTxt = EST_GEN_SAVE;}





$OATXT = $postext.$pretext;




/*
  e_TOKEN
  <input type="hidden" name="" value="'.$tp->toForm($DTA['prop']['']).'" />

*/


$XTRAFRMT = array(
  'estate_properties' => array(
    'prop_status'=>array('type'=>'select','labl'=>EST_GEN_STATUS,'hlp'=>EST_PROP_STATUSHLP),
    'prop_name'=>array('type'=>'text','labl'=>EST_GEN_NAME,'hlp'=>EST_PROP_NAMEHLP),
    'prop_zoning'=>array('labl'=>EST_PROP_LISTZONE,'hlp'=>EST_PROP_ZONEHLP),
    'prop_type'=>array('labl'=>EST_PROP_TYPE,'hlp'=>EST_PROP_TYPEHLP),
    'prop_listype'=>array('labl'=>EST_PROP_LISTYPE,'hlp'=>EST_PROP_LISTYPE),
    'prop_mlsno'=>array('type'=>'text','labl'=>EST_PROP_MLSNO,'hlp'=>EST_PROP_MLSNOHLP),
    'prop_parcelid'=>array('type'=>'text','labl'=>EST_PROP_PARCELID,'hlp'=>EST_PROP_PARCELIDHLP),
    'prop_lotid'=>array('type'=>'text','labl'=>EST_PROP_LOTID,'hlp'=>EST_PROP_LOTIDHLP),
    //'prop_leasefreq'=>array(),
    //'prop_currency'=>array(),
    
    'prop_subdiv'=>array('labl'=>EST_GEN_SUBDIVISION,'hlp'=>EST_PROP_CITYHLP),
    'prop_landfee'=>array('labl'=>EST_PROP_LANDLEASE,'hlp'=>EST_PROP_LANDLEASEHLP),
    'prop_landfreq'=>array('labl'=>EST_PROP_HOAFRQ,'hlp'=>EST_PROP_HOAFRQHLP),
    
    'prop_hours'=>array('labl'=>EST_PROP_HRS,'hlp'=>EST_PROP_HRSHLP),
    )
  
  );


$TABLSTRUCT = estTablStruct();
$FORMELES = array_merge_recursive($TABLSTRUCT,$XTRAFRMT);
unset($XTRAFRMT,$TABLSTRUCT);




$OATXT .= '
<form method="post" action="'.e_SELF.'?'.e_QUERY.'" id="plugin-estate-OAform" enctype="multipart/form-data" autocomplete="off" data-propid="'.intval($DTA['prop']['prop_idx']).'" data-h5-instanceid="0" novalidate="novalidate">';



$TBS = array(
  0 => array('caption' => EST_GEN_LISTING),
  1 => array('caption' => EST_GEN_ADDRESS),
  2 => array('caption' => EST_GEN_COMMUNITY),
  3 => array('caption' => EST_GEN_SPACES),
  4 => array('caption' => EST_GEN_DETAILS),
  5 => array('caption' => EST_GEN_GALLERY),
  6 => array('caption' => EST_GEN_SCHEDULING)
  );







$TBS[0]['text'] = $estateCore->estOAFormTable(1,'start',EST_GEN_LISTING);
$TBS[0]['text'] .= $estateCore->estOAFormTR('text','prop_name',$DTA['prop'],EST_GEN_NAME,EST_PROP_NAMEHLP);//$ATTR,$SRC);
$TBS[0]['text'] .= $estateCore->estOAFormTable(1,'end',$UpBtnTxt,1);


$TBS[1]['text'] = $estateCore->estOAFormTable(2,'tbody',$DTA['prop']);
      



$TBS[2]['text'] = $estateCore->estOAFormTable(3,'start',EST_GEN_COMMUNITY);    
$TBS[2]['text'] .= $estateCore->estOAFormTable(3,'end',$UpBtnTxt,3);



$TBS[3]['text'] = '
<div class="estOABlock">
  <h3><div>'.$tp->toHTML(EST_GEN_SPACES).'</div></h3>
  <div class="estOATabCont">
    <div id="estSpaceGrpDiv" class="estSpaceGrpDiv"></div>
  </div>
</div>';



$TBS[4]['text'] = $estateCore->estOAFormTable(5,'start',EST_GEN_DETAILS);      
$TBS[4]['text'] .= $estateCore->estOAFormTable(5,'end',$UpBtnTxt,5);



$TBS[5]['text'] = '
<div class="estOABlock">
  <h3><div>'.$tp->toHTML(EST_GEN_GALLERY).'</div></h3>
  <div class="estOATabCont">
    <div id="estNoGalWarn" class="s-message alert alert-block warning alert-warning">'.EST_PROP_MSG_NEEDSAVE.' '.EST_PROP_MSG_ADDMEDIA.'</div>
    <div class="WD100">
      <table id="estate-gallery-tabl" class="table-striped">
        <thead>
          <tr id="estGalleryH1">
            <th>
              <div id="estGalFileSlipCont">
                <label id="fileSlip" for="upFile">
                  <button id="fileSlipBtn" class="btn btn-primary btn-sm FR">'.EST_UPLOAD.' '.EST_MEDIA.'</button>
                </label>
              </div>
            '.EST_MEDIAAVAILABLE.'
            </th>
          </tr>
          <tr id="estGalleryH2"><td><div class="estBeltLoop"><div id="estGalleryBelt" class="estBelt estGalCont"></div></div></td></tr>
          <tr id="estGalleryH3"><th>'.EST_MEDIAINUSE.'</th></tr>
        </thead>
        <tbody>
          <tr id="estGalleryH4"><td><div id="estGalleryUsed" class="estGalCont"></div></td></tr>
        </tbody>
      </table>
    </div>
    <div class="buttons-bar center"><input type="submit" name="estSubmit-6" class="btn btn-primary" value="'.$UpBtnTxt.'" /></div>
  </div>
</div>';



$TBS[6]['text'] = $estateCore->estOAFormTable(7,'start',EST_GEN_SCHEDULING);
$TBS[6]['text'] .= $estateCore->estOAFormTR('prop_timezone','prop_timezone',$DTA['prop'],EST_GEN_TIMEZONE,EST_PROP_TIMEZONEHLP);
$TBS[6]['text'] .= $estateCore->estOAFormTR('prop_hours','prop_hours',$DTA['prop'],EST_PROP_HRS);//,$INF,$ATTR,$SRC);
$TBS[6]['text'] .= $estateCore->estOAFormTable(7,'end',$UpBtnTxt,7);


$TBSOPTS = array('active' => 0,'fade' => 0,'class' => 'estOATabs');

$OATXT .= $frm->tabs($TBS, $TBSOPTS);

      
$OATXT .= '
</form>
<div id="estJSpth" data-pth="'.EST_PATHABS.'"></div>
<div id="estMobTst"></div>
<div id="estMiniNav"></div>';



require_once(HEADERF);
$ns->tablerender($nsHead,$OATXT,'estEditProp');
unset($nsHead,$OATXT,$USRSEL);
require_once(FOOTERF);
exit;


?>