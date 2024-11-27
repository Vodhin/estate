<?php
if (!defined('e107_INIT')) { exit; }
if(!isset($qs)){
  if(e_QUERY){$qs = explode(".", e_QUERY);}
  else{$qs = array('list',0);}
  $PROPID = intval($qs[1]);
  }

//Check e107 User Data and Estate Prefs/Classes
if(intval(USERID) == 0){$err = "Not Allowed";}
if(intval(EST_USERPERM) == 0 && (intval($EST_PREF['public_act']) == 0 || intval($EST_PREF['public_act']) == 255)){$err = EST_ERR_NOTALLOWED;}
else{
  if(intval(EST_USERPERM) > 0 || (USERID > 0 && check_class($EST_PREF['public_act']))){
    if($qs[0] == 'edit' && intval($qs[1]) == 0){e107::redirect(e_SELF."?new.0.0");}
    }
  else{$err = EST_ERR_NOTALLOWED;}
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


$DTA = array();
$DTA['prop'] = array();

if(!$err && intval($qs[1]) > 0){
  if($prop = $sql->retrieve("estate_properties","*","prop_idx='".intval($qs[1])."' LIMIT 1",true)){
    $DTA['prop'] = $prop[0];
    if(intval($DTA['prop']['prop_agent']) > 0){
      if($agt = $sql->retrieve("estate_agents","*","agent_idx='".intval($DTA['prop']['prop_agent'])."' LIMIT 1",true)){
        $DTA['agent'] = $agt[0];
        if(intval($DTA['agent']['agent_imgsrc']) == 1 && trim($DTA['agent']['agent_image']) !== ""){
          $DTA['imgurl'] = EST_PTHABS_AGENT.$tp->toHTML($DTA['agent']['agent_image']);
          }
        
        if($agy = $sql->retrieve("estate_agencies","*","agency_idx='".intval($DTA['prop']['prop_agency'])."' LIMIT 1",true)){
          $DTA['agency'] = $agy[0];
          }
        if($usr = $sql->retrieve("user",$USRSEL,"user_id='".intval($DTA['agent']['agent_uid'])."' LIMIT 1",true)){
          $DTA['user'] = $usr[0];
          $DTA['imgurl'] = $tp->toAvatar($DTA['user'],array('type'=>'url'));
          if(!$DTA['imgurl']){$DTA['imgurl'] = $DTA['user']['user_profimg'];}
          }
        }
      }
    else{
      if($usr = $sql->retrieve("user",$USRSEL,"user_id='".intval($DTA['prop']['prop_uidcreate'])."' LIMIT 1",true)){
        $DTA['imgurl'] = $tp->toAvatar($usr[0],array('type'=>'url'));
        $DTA['user'] = $usr[0];
        
        if($agt = $sql->retrieve("estate_agents","*","agent_uid='".intval($DTA['user']['user_id'])."' LIMIT 1",true)){
          $DTA['agent'] = $agt[0];
          $DTA['prop']['prop_agent'] = intval($DTA['agent']['agent_idx']);
          
          if(intval($DTA['agent']['agent_imgsrc']) == 1 && trim($DTA['agent']['agent_image']) !== ""){
            $DTA['imgurl'] = EST_PTHABS_AGENT.$tp->toHTML($DTA['agent']['agent_image']);
            }
          
          if($agy = $sql->retrieve("estate_agencies","*","agency_idx='".intval($DTA['agent']['agent_agcy'])."' LIMIT 1",true)){
            $DTA['agency'] = $agy[0];
            $DTA['prop']['prop_agency'] = intval($DTA['agency']['agency_idx']);
            }
          
          if(!isset($_POST['prop_name'])){
            if($sql->update("estate_properties","prop_agent='".intval($DTA['agent']['agent_idx'])."', prop_agency='".intval($DTA['agent']['agent_agcy'])."' WHERE prop_idx='".intval($DTA['prop']['prop_idx'])."' LIMIT 1")){
              e107::getMessage()->addInfo(EST_ERR_INFOCHANGED.' '.EST_ERR_UPDATEDONE);
              }
            else{e107::getMessage()->addWarning(EST_ERR_INFOCHANGED.' '.EST_ERR_UPDATENEEDED);}
            }
          }
        }
      }
    }
  }

require_once(e_HANDLER."form_handler.php");
require_once(e_PLUGIN.'estate/ui/tabstruct.php');
require_once(e_PLUGIN.'estate/ui/core.php');
$estateCore = new estateCore;

// If Listing Loaded, Check current Estate User permissions against Property Listing Owner
if(intval($DTA['prop']['prop_idx']) > 0){
  if(EST_USERPERM == 0){
    if(intval($DTA['prop']['prop_uidcreate']) !== USERID){
      $err[1] = EST_ERR_NOTYOURPROP;
      $err[2] = EST_ERR_NOTYOURPROP1.' '.$tp->toHTML(USERNAME).' ('.$tp->toHTML(USEREMAIL).')';
      }
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
      if(EST_USERPERM >= 3 || EST_USERPERM >= intval($EST_PREF['public_mod'])){
        
        }
      else{
        if(intval($DTA['prop']['prop_agency']) == 0 || $DTA['prop']['prop_agent'] == 0){
          if(intval($DTA['prop']['prop_uidcreate']) == USERID){
            if(intval($DTA['agent']['agent_uid']) == USERID){
              $DTA['prop']['prop_agency'] = intval($DTA['agent']['agent_agcy']);
              $DTA['prop']['prop_agent'] = intval($DTA['agent']['agent_idx']);
              e107::getMessage()->addWarning(EST_ERR_UPDATENEEDED);
              }
            }
          else{
            $err[1] = EST_GEN_OTHERPROP2.' '.$tp->toHTML($DTA['user']['user_name']);
            $err[2] = EST_ERR_NOTYOURPROP2;
            }
          }
        else{
          $err[1] = EST_ERR_NOTYOURPROP3;
          $err[2] = EST_ERR_NOTYOURPROP1.' '.$tp->toHTML(AGENCY_NAME).' ';
          }
        }
      }
    }
  //If no Errors, Load Prop Subdata
  if(!$err){
    $ESTDTA = estGetSpaces($DTA['prop']);
    $MEDIA = $ESTDTA[0];
    $SPACES = $ESTDTA[1];
    //estate_featurelist
    }
  }
else{
  if($qs[0] !== 'new'){e107::redirect(e_SELF."?new.0.0");}
  $DTA['prop'] = $estateCore->estGetNewProp();
  
  if($DTA['prop']['prop_agent'] > 0){
    if($agt = $sql->retrieve("estate_agents","*","agent_idx='".$DTA['prop']['prop_agent']."' LIMIT 1",true)){
      $DTA['agent'] = $agt[0];
      
      if($agy = $sql->retrieve("estate_agencies","*","agency_idx='".$DTA['prop']['prop_agency']."' LIMIT 1",true)){
        $DTA['agency'] = $agy[0];
        }
      if($usr = $sql->retrieve("user",$USRSEL,"user_id='".$DTA['agent']['agent_uid']."' LIMIT 1",true)){
        $DTA['user'] = $usr[0];
        }
      
      if(intval($DTA['agent']['agent_imgsrc']) == 1 && trim($DTA['agent']['agent_image']) !== ""){
        $DTA['imgurl'] = EST_PTHABS_AGENT.$DTA['agent']['agent_image'];
        }
      else{$DTA['imgurl'] = $tp->toAvatar($usr[0],array('type'=>'url'));}
      }
    }
  else{
    if($usr = $sql->retrieve("user",$USRSEL,"user_id='".USERID."' LIMIT 1",true)){
        $DTA['imgurl'] = $tp->toAvatar($usr[0],array('type'=>'url'));
        $DTA['user'] = $usr[0];
        }
    }
  }





$pretext = '
  <h2>'.(intval($DTA['prop']['prop_idx']) > 0 ? '' : EST_GEN_NEW.' ').($DTA['prop']['prop_agent'] > 0 ? EST_GEN_AGENT : EST_GEN_PRIVATE).' '.EST_GEN_LISTING.'</h2>
  <div id="estAgCard" class="estAgCard">
    <div class="estAgCardInner">
      <div class="estAgtAvatar" style="background-image:url(\''.$DTA['imgurl'].'\')"></div>
      <div class="estAgtInfo1">';

if($DTA['agent']){
  $pretext .= '
        <h3>'.$tp->toHTML($DTA['agent']['agent_name']).'</h3>
        <h4>'.$tp->toHTML($DTA['agency']['agency_name']).'</h4>
        <p class="FSITAL">'.$tp->toHTML($DTA['agent']['agent_txt1']).'</p>
        <div class="estAgContact">';
  if($AGENT['contacts'][6]){
    foreach($AGENT['contacts'][6] as $ck=>$cv){
      $pretext .= '
          <div>'.$tp->toHTML($cv[0]).' '.$tp->toHTML($cv[1]).'</div>';
      }
    }
  else{
    $pretext .= '
          <div>'.EST_GEN_EMAIL.' '.$tp->toHTML($DTA['user']['user_email']).'</div>';
    }
  
    
  $pretext .= '
        </div>';
  }
else{
  $pretext .= '
        <h3>'.$tp->toHTML($DTA['user']['user_name']).'</h3>
        <h4>'.EST_GEN_PRIVATESELLER.'</h4>
        <div class="estAgContact">
          <div>'.$tp->toHTML($DTA['user']['user_email']).'</div>
        </div>';
  }

  $pretext .= '
      </div>
      <div>';
  if(ADMIN){
    $pretext .= '
        <div>'.EST_GEN_CREATEDBY.' '.EST_GEN_AGENT.': '.$DTA['prop']['prop_agent'].', UID: '.$DTA['prop']['prop_uidcreate'].'</div>
        <div>'.EST_PROP_UPDTEUID.' UID '.$DTA['prop']['prop_uidupdate'].'</div>';
    }
  $pretext .= '
      </div>
    </div>
  </div>';

if($err){
  foreach($err as $k=>$v){e107::getMessage()->addError($v);}
  require_once(HEADERF);
  echo $pretext.'
  <div>USER PERMISSIONS: '.EST_USERPERM.'</div>
  <div>You Are: '.$tp->toHTML(USERNAME).' ('.$tp->toHTML(USEREMAIL).') ['.$tp->toHTML(AGENT_NAME).' of '.EST_AGENCYID.' '.$tp->toHTML(AGENCY_NAME).']</div>
  <div>'.EST_GEN_AGENT.' & '.EST_GEN_AGENCY.': '.$DTA['prop']['prop_agent'].' '.$tp->toHTML($DTA['agent']['agent_name']).' of '.$tp->toHTML($DTA['agency']['agency_name']).' (#'.$DTA['prop']['prop_agency'].')</div>';
  unset($DTA,$err,$pretext);
  require_once(FOOTERF);
  exit;
  }


$msg = e107::getMessage();

e107::css('url',e_PLUGIN.'estate/js/cropperjs/dist/cropper.css');
e107::js('estate','js/cropperjs/dist/cropper.js', 'jquery');

if($_POST['estPTrig']){
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
  
  //check to see if Author has been upgraded to an Agent
  if(isset($DTA['agent']) && intval($_POST['prop_uidcreate']) == USERID && (intval($_POST['prop_agency']) == 0 || $_POST['prop_agent'] == 0)){
    if(intval($DTA['agent']['agent_uid']) == USERID){
      $_POST['prop_agency'] = intval($DTA['agent']['agent_agcy']);
      $_POST['prop_agent'] = intval($DTA['agent']['agent_idx']);
      $_POST['prop_appr'] = USERID;
      $msg->addInfo('Updated Seller Information');
      }
    }
  
  if(!isset($_POST['prop_appr'])){
    if($_POST['prop_uidcreate'] !== USERID){$_POST['prop_appr'] = intval($DTA['prop']['prop_appr']);}
    else{
      if(EST_USERPERM > 0  || check_class(e107::pref('estate','public_apr'))){$_POST['prop_appr'] = USERID;}
      else{
        $_POST['prop_appr'] = 0;
        if(intval($DTA['prop']['prop_appr']) > 0){$qs[3] = 2;}
        else{$qs[3] = 1;}
        }
      }
    }
  
  $DTA['prop']['prop_appr'] = intval($_POST['prop_appr']);
  
  if(intval($DTA['prop']['prop_idx']) > 0){
    $PROPIDX = intval($DTA['prop']['prop_idx']);
    foreach($PROP_FLDS as $fld){
      $DTA['prop'][$fld] = $_POST[$fld];
      $tst5 .= '<div>['.$fld.'] '.$_POST[$fld].'</div>';
      if(!in_array($fld,$PROP_FIXD)){
        $QRY .= ($QRY ? ", " : "").$fld."='".$tp->toDB($_POST[$fld] ? $_POST[$fld] : $DTA['prop'][$fld])."'";
        }
      }
    $QRY .= "WHERE prop_idx='".$PROPIDX."' LIMIT 1";
    
    //$msg->addInfo($tst5);
    
    if($sql->update("estate_properties",$QRY)){
      $msg->addSuccess(EST_UPDATED.' '.$tp->toHTML($_POST['prop_name']));
      
      if(isset($_POST['prop_status']) && isset($_POST['prop_listprice'])){
        $ndate = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        $msg->addInfo('['.$PROPIDX.'] '.$tp->toDate($ndate).' '.$_POST['prop_listprice'].' ('.$_POST['prop_status'].')');
        $chkHist = estChkPriceHist($PROPIDX,$ndate,$_POST['prop_listprice'],$_POST['prop_status']);
        if(count($chkHist) > 0){
          if($chkHist[0] !== 'noc'){
            switch($chkHist[0]){
              case 'err' : $mtx1 = EST_GEN_ERROR; break;
              case 'upd' : $mtx1 = EST_GEN_UPDATED; break;
              case 'add' : $mtx1 = EST_GEN_ADDED; break;
              }
            $msg->addInfo($mtx1.' '.EST_PROP_UPHIST1.' '.$chkHist[1].' [Prop ID#'.$PROPIDX.'] '.EST_GEN_AMOUNT.': '.$chkHist[2].' '.$GLOBALS['EST_PROPSTATUS'][$chkHist[3]]['opt'].' ('.$chkHist[3].')');
            }
            
          }
        }
      
      
      
      if(intval($DTA['prop_appr']) == 0){
        $qs[2] = USERID;
        }
      }
    }
  else{
    $NDB = array();
    foreach($PROP_FLDS as $fld){
      $NDB[$fld] = $_POST[$fld];
      $DTA['prop'][$fld] = $_POST[$fld];
      $ERDV .= '<div>'.$fld.' = "'.$tp->toDB($_POST[$fld]).'"</div>';
      }
    
    $NDB['prop_idx'] = intval(0);
    if($PROPIDX = $sql->insert("estate_properties",$NDB)){
      if(intval($PROPIDX) > 0){
        if(intval($DTA['prop_appr']) == 0){e107::redirect(e_SELF."?edit.".intval($PROPIDX).".".USERID.".2.0");}
        else{e107::redirect(e_SELF."?edit.".intval($PROPIDX).".0");}
        }
      };
    }
  
  
  $dberr = $sql->getLastErrorText();
  if($dberr){
    $msg->addError($dberr.'<p>'.($ERDV ? $ERDV : $QRY).'</p>');
    }
  unset($dberr,$ERDV,$QRY);
  }


e107::css('url',e_PLUGIN.'estate/css/admin.css');
e107::css('url',e_PLUGIN.'estate/css/oa.css');
e107::css('url',e_PLUGIN.'estate/js/cropperjs/dist/cropper.css');
e107::js('estate','js/Sortable/Sortable.js', 'jquery');
e107::js('estate','js/adm/shared.js', 'jquery');
e107::js('estate','js/oa.js', 'jquery');


include_once('qry.php');


if(intval($DTA['prop']['prop_idx']) > 0){
  $nsHead = '<div id="estMiniNav"><a href="'.e_SELF.'?view.'.$DTA['prop']['prop_idx'].'" title="'.EST_GEN_VIEWLISTING.'"><i class="fa fa-eye"></i></a>';
  if(getperms('P')){
    $nsHead .= '<a class="noMobile" href="'.EST_PTH_ADMIN.'?action=edit&id='.intval($DTA['prop']['prop_idx']).'" title="'.EST_GEN_FULLEDIT.'"><i class="fa fa-pencil-square-o"></i></a>';
    }
  $nsHead .= '</div>'.EST_GEN_EDIT.': '.$tp->toHTML($DTA['prop']['prop_name']);
  }
else{
  //$nsHead = EST_GEN_NEW.' '.EST_GEN_LISTING;
  }

$PINS = est_map_pins();
e107::js('inline','var estMapPins = '.$PINS.'; ', 'jquery',2);


$frm = e107::getForm(false, true);
$timeZones = systemTimeZones();


if(isset($_POST['prop_hours'])){
  $DTA['prop']['prop_hours'] = $_POST['prop_hours'];
  }

$OATXT = $pretext;


if(!isset($EST_PREF)){
  $EST_PREF = e107::pref('estate');
  }

//send email notifications for non agent postings
if(!isset($_POST['estEmailNoSend']) && EST_USERPERM == 0 && intval($DTA['prop_agent']) == 0 && isset($qs[2]) && intval($qs[2]) == USERID && isset($qs[3])){
  if(!isset($EST_PREF['public_notify'])){$msg->addError(EST_ERR_NOEMAILADMINS1);}
  else{
    
    if(is_array($EST_PREF['public_notify'])){$QRY = "user_id IN (".implode(',',$EST_PREF['public_notify']).")";}
    else{$QRY = "user_id='".intval($EST_PREF['public_notify'])."'";}
  
  
		$siteadminemail = e107::getPref('siteadminemail');
		$siteadmin = e107::getPref('siteadmin');
    
    //$fromUID = intval($DTA['prop_uidcreate']);
    $fromEmail = USEREMAIL;
    $fromName = USERNAME;
    $subject = $DTA['prop']['prop_name'];
    
    
    $i=0;
    $recipients = array();
    $sql->gen("SELECT user_id,user_name,user_loginname,user_email,user_class,user_admin,user_perms FROM #user WHERE ".$QRY);
    while($rows = $sql->fetch()){
      $toName = (trim($rows['user_name']) !== '' ? $rows['user_name'] : $rows['user_loginname']);
      $recipients[$i] = array(
  			'mail_recipient_id'=>intval($rows['user_id']),
  			'mail_recipient_name'=>$toName,
  			'mail_recipient_email'=>$rows['user_email'],
        //'mail_copy_to'=>$rows['msg_from_addr'],
  			'mail_target_info'=>array(
  				'USERID'=>intval($rows['msg_to_uid']),
  				'DISPLAYNAME'=>$toName,
  				'SUBJECT'=>$subject,
  				'USERNAME'=>$rows['user_loginname'],
  				'USERLASTVISIT'=>$rows['user_lastvisit'],
  				'DATE_SHORT'=>$tp->toDate(time(),'short'),
  				'DATE_LONG'=>$tp->toDate(time(),'long'),
  				)
  			);
      $i++;
      }
    
    $dberr = $sql->getLastErrorText();
    if($dberr){$msg->addError($dberr);}
    unset($dberr,$QRY);
    
    
    if(count($recipients) == 0){$msg->addError(EST_ERR_NOEMAILADMINS2.'<div>IDs Tried: '.implode(',',$EST_PREF['public_notify']).'</div>');}
    else{
      
  		require_once(e_HANDLER.'mail_manager_class.php');
  		$mailer = new e107MailManager;
      
  		$vars = array('x'=>SITENAME, 'y'=>$subject, 'z'=>$fromName);
      $message = "[html]".$tp->lanVars('<strong>'.EST_OAE_DONOTRPLY.'.</strong> '.EST_OAE_EMAILTOP, $vars,true)." <br /><br />".$tp->toEmail(EST_OAE_EMAILFOOT.' <a href="'.e_SELF.'?edit.'.intval($DTA['prop']['prop_idx']).'.0">'.EST_OAE_EMAILCLKHERE.' '.$tp->toHTML($DTA['prop']['prop_name']).'</a><br /><br />', false)."[/html]";
      
      
      
  		$mailData = array(
  			'mail_total_count'      => count($recipients),
  			'mail_content_status' 	=> MAIL_STATUS_TEMP,
  			'mail_create_app' 		=> 'estate',
  			'mail_title' 			=> 'ESTATE LISTING APPROVAL REQUEST',
  			'mail_subject' 			=> $subject,
  			'mail_sender_email' 	=> e107::getPref('replyto_email',$siteadminemail),
  			'mail_sender_name'		=> e107::getPref('replyto_name',$siteadmin),
  			'mail_notify_complete' 	=> 0,
  			'mail_body' 			=> $message,
  			'template'				=> 'default',
  			'mail_send_style'       => 'default'
  		  );
        
      
  		$opts =  array(); // array('mail_force_queue'=>1); array(email_replyto)
      $emsent = $mailer->sendEmails('default', $mailData, $recipients, $opts);
      if($emsent == true){
        foreach($recipients as $ek=>$ev){
          $mtxt .= '<li>'.$tp->toHTML($ev['mail_recipient_name']).'</li>'; // '.$tp->toHTML($ev['mail_recipient_email']).'
          }
        $msg->addSuccess('<p style="margin-top:1em;">'.EST_OAE_THANKYOU.'<ul>'.$mtxt.'</ul></p>');
        }
      else{
        $msg->addError(EST_ERR_NOEMAILSENT.' ['.$emsent.']');
        }
      }
    
    
    }
  }
  


if(EST_USERPERM == 0 && !check_class($EST_PREF['public_apr'])){
  if(intval($DTA['prop']['prop_idx']) == 0){
    e107::getMessage()->addInfo(EST_PROP_APPROVE01.' '.EST_PROP_APPROVE04);
    }
  else{
    if(isset($_POST['estEmailNoSend'])){
      e107::getMessage()->addSuccess(EST_PROP_APPROVE06);
      }
    elseif($DTA['prop']['prop_appr'] > 0){e107::getMessage()->addWarning(EST_PROP_APPROVE03.' '.EST_PROP_APPROVE04);}
    elseif(!isset($_POST['prop_status'])){
      e107::getMessage()->addWarning(EST_PROP_APPROVE02.' '.EST_PROP_APPROVE05);
      }
    }
  }

  



/*
  e_TOKEN
  <input type="hidden" name="" value="'.$tp->toForm($DTA['prop']['']).'" />

*/


$TBSOPTS = array('active' => 0,'fade' => 0,'class' => 'estOATabs');
$TBS = $estateCore->estOAFormTabs();


$OATXT .= '
<form method="post" action="'.e_SELF.'?'.$qs[0].'.'.intval($qs[1]).'.0" id="plugin-estate-OAform" enctype="multipart/form-data" autocomplete="off" data-propid="'.intval($DTA['prop']['prop_idx']).'" data-h5-instanceid="0" novalidate="novalidate">';

foreach($TBS as $k=>$v){$TBS[$k]['text'] = $estateCore->estOAFormTable($k,$DTA['prop']);}
  
$OATXT .= $frm->tabs($TBS, $TBSOPTS);

$OATXT .= '<input type="hidden" name="estPTrig" value="'.USERID.'" />
<input type="hidden" name="estDefCur" value="'.$EST_PREF['locale'][1].'" />
<input type="hidden" name="estDefDIMU1" value="'.$EST_PREF['dimu1'].'" />
<input type="hidden" name="estDefDIMU2" value="'.$EST_PREF['dimu2'].'" />
</form>
<div id="estJSpth" data-pth="'.EST_PATHABS.'"></div>
<div id="estMobTst"></div>
<div id="estMiniNav"></div>';

require_once(HEADERF);
$ns->tablerender($nsHead,$OATXT,'estEditProp');
unset($nsHead,$OATXT,$USRSEL,$TBS,$TBSOPTS);
require_once(FOOTERF);
exit;
?>