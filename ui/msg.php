<?php
if(!defined('e107_INIT')){
  if(isset($_POST['sndMsg']) && isset($_POST['tdta'])){
    define('e_TOKEN_DISABLE',true);
    require_once('../../../class2.php');
    e107::includeLan(e_PLUGIN.'estate/languages/'.e_LANGUAGE.'/'.e_LANGUAGE.'_msg.php');
    
    $sql = e107::getDB();
    $tp = e107::getParser();
    $EST_PREF = e107::pref('estate');
    $RES = array();
    //$RES['sent']['raw'] = $_POST['tdta'];
    $TOUID = intval($_POST['tdta']['msg_to_uid']);
    $ei = 0;
    
    if($TOUID == 0){$RES['error'][$ei] = EST_MSG_ERRNOUID; $ei++;}
    
    $MSG = est_msg_proc($_POST['tdta']);
    //$RES['sent']['proc'] = $MSG;
    if(intval($TOUID) !== intval($MSG['msg_to_uid'])){$RES['error'][$ei] = EST_MSG_ERRUIDNOMATCH; $ei++;}
    
    $MSG['msg_from_ip'] = USERIP;
    
    if($sql->select("estate_agents", "*", "agent_uid = '".$TOUID."'")){
      $AGENT = $sql->fetch();
      $AGTID = intval($AGENT['agent_idx']);
      if($AGTID > 0){
        $EMAIL_TO_NAME = $AGENT['agent_name'];
        if($CONT = $sql->retrieve("SELECT * FROM #estate_contacts WHERE contact_tabidx=".$AGTID." ORDER BY contact_ord ASC ",true)){
          foreach($CONT as $k=>$v){
            if(strtolower($v['contact_key']) == strtolower(EST_GEN_EMAIL)){$EMAIL_TO_ADDR = $v['contact_data'];}
            $AGENT['contacts'][$k] = array($v['contact_key'],$v['contact_data']);
            }
          }
        $RES['found']['agent'] = $AGENT;
        }
      }
    
    
    if(!$AGENT && intval($MSG['msg_mode']) == 2){$RES['error'][$ei] = EST_MSG_ERRNOTAGENT; $ei++;}
    
    $sql->gen("SELECT user_id,user_name,user_loginname,user_login,user_email,user_lastvisit FROM #user WHERE user_id = '".$TOUID."'");
    $TOUSER = $sql->fetch();
    if(intval($TOUSER['user_id']) == 0){$RES['error'][$ei] = EST_MSG_ERRTOUSRNOTFOUND; $ei++;}
    
    $MSG['msg_to_uid'] = intval($TOUSER['user_id']);
    if(!$EMAIL_TO_NAME){$EMAIL_TO_NAME = (trim($TOUSER['user_name']) !== '' ? $TOUSER['user_name'] : $TOUSER['user_loginname']);}
    if(!$EMAIL_TO_ADDR){$EMAIL_TO_ADDR = $TOUSER['user_email'];}
    
    if(!$EMAIL_TO_ADDR){$RES['error'][$ei] = EST_MSG_ERRTOUSRNOEMAIL; $ei++;}
    if($EST_PREF['contact_phone'] == 1 && trim($MSG['msg_from_phone']) == ''){$RES['error'][$ei] = EST_MSG_ERRFROMNOPHONE; $ei++;}
    
    
    if(USERID > 0){
      $sql->gen("SELECT user_id,user_name,user_loginname,user_login,user_email,user_lastvisit FROM #user WHERE user_id = '".USERID."'");
      $FROMUSER = $sql->fetch();
      }
    
    if(trim($MSG['msg_from_addr']) == ''){$RES['error'][$ei] = EST_MSG_ERRFROMUSRNOEMAIL; $ei++;}
    
    
    $MSGTXT = $MSG['msg_text'];
    $MSGFOOT = EST_MSG_MYCONTINFO.': <br />'.$MSG['msg_from_name'].' <br />'.$MSG['msg_from_addr'].' <br />'.$MSG['msg_from_phone'];
    
    $MSG['msg_sent'] = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
    $MSG['msg_to_addr'] = $tp->toDB($EMAIL_TO_ADDR);
		$MSG['msg_to_name'] = $tp->toDB($EMAIL_TO_NAME);
		$MSG['msg_from_addr'] = $tp->toDB($MSG['msg_from_addr']);
		$MSG['msg_from_name'] = $tp->toDB($MSG['msg_from_name']);
		$MSG['msg_top'] = $tp->toDB($MSG['msg_top']);
		$MSG['msg_text'] = $tp->toDB($MSGTXT.' <br /><br />'.$MSGFOOT);
    
    
    if($RES['error']){
      $RES['msg'] = $MSG;
      echo $tp->toJSON($RES);
      exit;
      }
    
    
		//$triggerData = $MSG;
	  //e107::getEvent()->trigger('estate_message', $triggerData);
	  //ob_start();
		//$this->trackEmail($MSG);
		//ob_end_clean();
    
    
		$recipients = array();
    // send to
		$recipients[0] = array(
			'mail_recipient_id'=>intval($MSG['msg_to_uid']),
			'mail_recipient_name'=>$MSG['msg_to_name'],
			'mail_recipient_email'=>$MSG['msg_to_addr'],
      'mail_copy_to'=>$MSG['msg_from_addr'],
			'mail_target_info'=>array(
				'USERID'=>intval($MSG['msg_to_uid']),
				'DISPLAYNAME'=>(trim($TOUSER['user_name']) !== '' ? $TOUSER['user_name'] : $TOUSER['user_loginname']),
				'SUBJECT'=>$MSG['msg_top'],
				'USERNAME'=>$TOUSER['user_loginname'],
				'USERLASTVISIT'=>$TOUSER['user_lastvisit'],
				'DATE_SHORT'=>$tp->toDate(time(),'short'),
				'DATE_LONG'=>$tp->toDate(time(),'long'),
				)
			);
    
    //$SITE_PREF['contact_emailcopy']
    $COPY_PREF = e107::pref('contact_emailcopy');
    
    if($COPY_PREF == 1 && check_class($EST_PREF['contact_cc']) && intval($MSG['msg_from_cc']) == 1){
      $recipients[1] = array(
  			'mail_recipient_id'=> intval($MSG['msg_from_uid']),
  			'mail_recipient_name'=> $MSG['msg_from_name'],
  			'mail_recipient_email'=> $MSG['msg_from_addr'],
  			'mail_target_info'=> array(
  				'USERID'=> intval($MSG['msg_from_uid']),
  				'DISPLAYNAME'=>(trim($FROMUSER['user_login']) !== '' ? $FROMUSER['user_login'] : ($FROMUSER['user_name'] ? $FROMUSER['user_name'] : EST_MSG_NOTMEMBER)),
  				'SUBJECT'=> $MSG['msg_top'],
  				'USERNAME'=> ($FROMUSER['user_name'] ? $FROMUSER['user_name'] : EST_MSG_NOTMEMBER),
  				'USERLASTVISIT'=> ($FROMUSER['user_lastvisit'] ? $FROMUSER['user_lastvisit'] : intval($MSG['msg_sent'])),
  				'DATE_SHORT'=> $tp->toDate(time(),'short'),
  				'DATE_LONG'=> $tp->toDate(time(),'long'),
  				)
  			);
      }
    
    if(ADMINPERMS === '0'){$RES['email']['to'] = $recipients;}
    
      
    //<em>$text</em> print tags
    
  
		require_once(e_HANDLER.'mail_manager_class.php');
		$mailer = new e107MailManager;

		$vars = array('x'=>$MSG['msg_from_name'], 'y'=>$MSG['msg_to_name'], 'z'=>$MSG['msg_top']);
    $message = "[html]".$tp->lanVars('<strong>'.EST_MSG_DONOTRPLY.'</strong> '.EST_MSG_TOPPER, $vars,true)." <br /><br /><blockquote>".$tp->toEmail($MSGTXT.' <br /><br />'.$MSGFOOT, false)."</blockquote>[/html]";
    
  
		// Create the mail body
		$mailData = array(
				'mail_total_count'      => count($recipients),
				'mail_content_status' 	=> MAIL_STATUS_TEMP,
				'mail_create_app' 		=> 'estate',
				'mail_title' 			=> 'ESTATE TRACKING',
				'mail_subject' 			=> $MSG['msg_top'],
				'mail_sender_email' 	=> e107::getPref('replyto_email',SITEADMINEMAIL),
				'mail_sender_name'		=> $MSG['msg_from_name'].' via '.SITENAME.'',
				'mail_notify_complete' 	=> 0,
				'mail_body' 			=> $message,
				'template'				=> 'default',
				'mail_send_style'       => 'default',
        'overrides'=>array(
          'replyto_email'=>$MSG['msg_from_addr'],
          'replyto_name'=>$MSG['msg_from_name']
          )
		  );
  
    
    if(ADMINPERMS === '0'){$RES['email']['data'] = $mailData;}
    
		$opts =  array(); // array('mail_force_queue'=>1); array(email_replyto)
    $emsent = $mailer->sendEmails('default', $mailData, $recipients, $opts);
    $MSG['msg_email'] = ($emsent == true ? 1 : 0);
    
    //	$thread_name = str_replace('&quot;', '"', $thread_name);// This not picked up by toText();
  
    
		$MSGID = $sql->insert('estate_msg', $MSG);
		$MSG['msg_idx'] = $MSGID;
  
  
    if(e107::isInstalled('pm')){
      $PM_TO_UID = intval();
      if($MSG['msg_to_uid'] > 0){
        $pm_prefs = e107::getPlugPref('pm');
        //$pmClass = varset($pm_prefs['pm_class'], e_UC_NOBODY);
        //check_class($pmClass)
        
        //EST_USERPERM
        
  		  
      
        if($MSG['msg_from_uid'] == 0){
          // not a member, send as if an admin
          if($AGENT){
            if($sql->gen("SELECT user_id,user_name, #estate_agents.* FROM #estate_agents LEFT JOIN #user ON user_id = agent_uid WHERE user_perms='0' AND agent_agcy='".intval($AGENT['agent_agcy'])."' AND NOT agent_uid='".$MSG['msg_to_uid']."' LIMIT 1")){
              $mgr = $sql->fetch();
              $MSG['msg_from_uid'] = intval($mgr['user_id']);
              }
            else{
              $sql->gen("SELECT user_id,user_name FROM #user WHERE user_perms='0' LIMIT 1");
              $mgr = $sql->fetch();
              $MSG['msg_from_uid'] = intval($mgr['user_id']);
              }
            $RES['mgr'] = $mgr;
            }
          
          if($MSG['msg_from_uid'] == 0){$MSG['msg_from_uid'] = 1;}
          }
        
        
        $pm = array();
        $pmf = $sql->db_FieldList('private_msg');
        foreach($pmf as $k=>$v){$pm[$v]='';}
        $pm['pm_id'] = intval(0);
        $pm['pm_from'] = $MSG['msg_from_uid'];
        $pm['pm_to'] = $MSG['msg_to_uid']; //$MSG['msg_to_name'];
        $pm['pm_sent'] = intval($MSG['msg_sent']);
        $pm['pm_read'] = intval(0);//date
        $pm['pm_subject'] = $MSG['msg_top'];
        $pm['pm_text'] = $MSG['msg_text'];
        $pm['pm_sent_del'] = intval(0);
        $pm['pm_read_del'] = intval(0);
        $pm['pm_size'] = strlen($MSG['msg_text']);
        $MSG['msg_pm'] = $sql->insert('private_msg', $pm);
        }
      }
    
    
    $RES['msg'] = $MSG;
    $RES['pvw'][0] = estPrevMsgPvw($MSG);
    $RES['pvw'][1] = estPrevMsgPvw($MSG,1);
    
    $dberr = $sql->getLastErrorText();
    if($dberr){
      $RES['error'][0] = $dberr;
      unset($dberr);
      }
    echo $tp->toJSON($RES);
    unset($RES,$TOUID,$MSG,$AGENT,$TOUSER,$EMAIL_TO_NAME,$EMAIL_TO_ADDR,$FROMUSER);
    }
  exit;
  }


	//$randnum = rand(1000, 9999);

e107::css('url',e_PLUGIN.'estate/css/msg.css');
e107::includeLan(e_PLUGIN.'estate/languages/'.e_LANGUAGE.'/'.e_LANGUAGE.'_msg.php');


function est_msg_proc($DTA){
  $tp = e107::getParser();
  //if(trim($DTA['msg_text']) == ''){$DTA['msg_text'] = EST_MSG_TXT1A;}
  if(USERID > 0 && trim($DTA['msg_from_name']) == ''){
    $sql = e107::getDB();
    $sql->gen("SELECT user_id,user_name,user_loginname,user_login,user_email FROM #user WHERE user_id = '".USERID."'");
    $udta = $sql->fetch();
    $DTA['msg_from_addr'] = $udta['user_email'];
    if(trim($udta['user_login']) !== ''){
      $DTA['msg_from_name'] = $udta['user_login'].(trim($udta['user_name']) !== '' ? ' ('.$udta['user_name'].')' : '');
      }
    else{
      $DTA['msg_from_name'] = ($udta['user_name'] ? $udta['user_name'] : '');
      }
    
    unset($udta);
    }
  
  $MSG = array(
    'msg_idx'=>(intval($DTA['msg_idx']) > 0 ? intval($DTA['msg_idx']) : intval(0)),
    'msg_sent'=>(intval($DTA['msg_sent']) > 0 ? intval($DTA['msg_sent']) : mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"))),
    'msg_read'=>(intval($DTA['msg_read']) > 0 ? intval($DTA['msg_read']) : intval(0)),
    'msg_to_uid'=>(intval($DTA['msg_to_uid']) > 0 ? intval($DTA['msg_to_uid']) : intval(0)),
    'msg_to_addr'=>$tp->toTEXT(trim($DTA['msg_to_addr']) ? $DTA['msg_to_addr'] : ''),
    'msg_to_name'=>$tp->toTEXT(trim($DTA['msg_to_name']) ? $DTA['msg_to_name'] : ''),
    'msg_mode'=>(intval($DTA['msg_mode']) > 0 ? intval($DTA['msg_mode']) : intval(0)),
    'msg_propidx'=>(intval($DTA['msg_propidx']) > 0 ? intval($DTA['msg_propidx']) : (intval($DTA['prop_idx']) > 0 ? intval($DTA['prop_idx']) : intval(0))),
    'msg_from_cc'=>(intval($DTA['msg_from_cc']) > 0 ? intval($DTA['msg_from_cc']) : intval(0)),
    'msg_from_uid'=>intval($DTA['msg_from_uid'] ? $DTA['msg_from_uid'] : USERID),
    'msg_from_ip'=>$tp->toTEXT($DTA['msg_from_ip'] ? $DTA['msg_from_ip'] : USERIP),
    'msg_from_name'=>$tp->toTEXT(trim($DTA['msg_from_name']) !== '' ? $DTA['msg_from_name'] : ''), //(defined("USERNAME") ? USERNAME : '')
    'msg_from_addr'=>$tp->toTEXT(trim($DTA['msg_from_addr']) ? $DTA['msg_from_addr'] : ''), //(defined("USEREMAIL") ? USEREMAIL : '')
    'msg_from_phone'=>$tp->toTEXT($DTA['msg_from_phone']),
    'msg_top'=>$tp->toTEXT($DTA['msg_top']),
    'msg_text'=>$tp->toTEXT($DTA['msg_text']),
    );
  
  
  return $MSG;
  }



function estPrevMsgPvw($mv,$mde=0){
  $tp = e107::getParser();
  if($mde == 1){
    return '
      <div class="estMsgP" style="display:block;">
        <h4>'.$tp->toHTML($mv['msg_top']).'</h4>
        <h5>'.$tp->toDate($mv['msg_sent'],'long').(intval($mv['msg_read']) > 0 ? ' <i>'.EST_MSG_RECD.' '.$tp->toDate($mv['msg_read'],'short').'</i>' : '').'</h5>
        <p>'.$tp->toHTML($mv['msg_text']).'</p>
      </div>';
    }
  else{
    return '
      <div class="estMsgBtn">
        <button class="btn btn-default">'.$tp->toHTML($mv['msg_top']).'</button>
        <div class="estMsgP">
        <h4>'.$tp->toHTML($mv['msg_top']).'</h4>
        <h5>'.$tp->toDate($mv['msg_sent'],'long').(intval($mv['msg_read']) > 0 ? ' <i>'.EST_MSG_RECD.' '.$tp->toDate($mv['msg_read'],'short').'</i>' : '').'</h5>
          <p>'.$tp->toHTML($mv['msg_text']).'</p>
        </div>
      </div>';
    }
  }





function est_msg_form($DTA=null){
	if(!$DTA['prop_seller']){
    return (ADMIN ? EST_GEN_UNK.' '.EST_GEN_SELLER : '');
    }
  
  $sql = e107::getDB();
  $tp = e107::getParser();
  $COPY_PREF = e107::pref('contact_emailcopy');
  
  $EST_PREF = e107::pref('estate');
  
  /*
    0=>array('unsaved_icon','Un Saved'),
    1=>array('saved_icon','Saved '),
  */
  
  
  $MSG = est_msg_proc($DTA);
  extract($MSG);
  
  $EST_MSG_MODES = array(
    0=>EST_GEN_CONTACT.' '.$DTA['prop_seller'].' ('.EST_GEN_SELECTONE.')',
    3=>EST_MSG_IWANTOTHER
    );
  
  $EST_COOKIE = $_COOKIE['estate-'.USERID.'-msgs']; //.intval($MSG['msg_propidx'])]
  
  $SUBTXT = EST_MSG_SUB1A;
  $TXTTXT = EST_MSG_TXT1A;
  
  switch(intval($DTA['prop_status'])){
    case 4 :  //Pending
      $EST_MSG_MODES[1] = EST_MSG_IWANTOFFER;
      $SUBTXT = EST_MSG_SUB1B;
      $TXTTXT = EST_MSG_TXT1B;
      break;
    case 3 : 
      $EST_MSG_MODES[1] = EST_MSG_IWANTVIEW;
      break;
       
    case 2 : 
      if(intval($DTA['prop_datelive']) > 0 && intval($DTA['prop_datelive']) <= $MSG['msg_sent']){
        $EST_MSG_MODES[1] = EST_MSG_IWANTVIEW;
        }
      elseif(USERID > 0 && (intval($DTA['prop_dateprevw']) > 0 && intval($DTA['prop_dateprevw']) <= $MSG['msg_sent'])){
        $EST_MSG_MODES[1] = EST_MSG_IWANTVIEW;
        }
      else{
        
        }
      break;
    }
  
  
  
  
  if(intval($DTA['agent_idx']) > 0){$EST_MSG_MODES[2] = EST_MSG_IWANTSELL;}
  
  
  //e107::getDate()->convert_date($post_list[0]['post_datestamp'], "forum")
  
  
  
  ksort($EST_MSG_MODES);
  
  $PREVMSG = array();
  $AGO = mktime(0,0,0, date("m"), date("d") - intval($EST_PREF['contact_life']), date("Y"));
  
  $MQRY = "SELECT #estate_msg.* FROM #estate_msg WHERE ".(USERID > 0 ? " msg_from_uid='".USERID."'" : " msg_from_ip=".USERIP."' AND NOT msg_from_uid > '0'")." AND msg_sent >= '".$AGO."'";
  
  if(intval($EST_PREF['contact_maxto']) > 1){$MQRY .= " AND msg_read = '0'";}
  
  if(intval($MSG['msg_propidx']) > 0){
    if(intval($EST_PREF['contact_maxto']) == 1 || intval($EST_PREF['contact_maxto']) == 3){
      $MQRY .= " AND msg_propidx='".$MSG['msg_propidx']."'";
      }
    }
  else{
    if(intval($EST_PREF['contact_maxto']) == 1 || intval($EST_PREF['contact_maxto']) == 3){
      $MQRY .= " AND NOT msg_propidx > '0'";
      }
    }
  
  
  $MQRY .= " ORDER BY msg_sent DESC LIMIT ".intval($EST_PREF['contact_max']);
  
  $mi = 0;
  $sql->gen($MQRY);
  while($rows = $sql->fetch()){
    $PREVMSG[$mi] = $rows;
    $mi++;
    }
  
  $PMSGCT = count($PREVMSG);
  $PREVDIV = '
  <div id="estMsgPrevDiv" class="WD100">
    <h4 id="estPrevMsgBtn" class="btn btn-primary"'.($PMSGCT > 0 ? '' : ' style="display:none;"').'>'.EST_MSG_PREVMSG.' ('.$PMSGCT.')</h4>';
  
  if($PMSGCT > 0){
    if($PMSGCT == 1){
      $PREVDIV .= estPrevMsgPvw($PREVMSG[0],1);
      }
    else{
      $PREVDIV .= '
    <div id="estMsgPrevBelt" style="display:none;">';
      foreach($PREVMSG as $mk=>$mv){$PREVDIV .= estPrevMsgPvw($mv);}
      $PREVDIV .= '
    </div>';
      }
    }
  $PREVDIV .= '
  </div>';
  
  
  //<div>['.$tp->toDate($AGO,'short').']<p>'.$MQRY.'</p></div>
  
  $ret = '
  <div id="estMsgFormDiv" class="WD100">';
  if($PMSGCT >= intval($EST_PREF['contact_max'])){
    $ret .= '<div id="estMsgWarn">'.EST_MSG_REACHMAX1.' '.$tp->toHTML($DTA['prop_seller']).' 
    '.(intval($MSG['msg_propidx']) > 0 && (intval($EST_PREF['contact_maxto']) == 1 || intval($EST_PREF['contact_maxto']) == 3) ? EST_MSG_REACHMAX2 : '').'
    </div>';
    }
  else{
    $ret .= '
    <table id="estMsgFormTabl" class="table WD100 TAL">
      <thead>
        <tr style="display:none;">
          <td>
            <input type="text" name="mail_to" class="tbox form-control estChkMsgRem" data-domn="'.e_DOMAIN.'" value="itsatrap@'.e_DOMAIN.'" />
            <input type="text" name="mail_from" class="tbox form-control estChkMsgRem" data-domn="'.e_DOMAIN.'" value="itsatrap@'.e_DOMAIN.'" />
            <input type="text" name="mail_subject" class="tbox form-control" value="itsatrap" />
            <textarea name="mail_text" class="tbox form-control">Humans should not change this</textarea>
          </td>
        </tr>
        <tr>
          <td>
            <select name="msg_mode" class="tbox form-control" value="'.$msg_mode.'">';
    foreach($EST_MSG_MODES as $k=>$v){
      $ret .= '<option value="'.$k.'"'.($k == $msg_mode ? 'selected="selected"' : '').' >'.$tp->toHTML($v).'</option>';
      }

  
    $ret .= '
            </select>
            <div id="estMsgDefs" style="display:none;">
              <div id="estT1">'.$TXTTXT.' '.(trim($DTA['prop_name']) !== '' ? $DTA['prop_name'] : EST_MSG_APROP).'. '.EST_MSG_END.'</div>
              <div id="estT2">'.EST_MSG_TXT2.'</div>
              <div id="estS1">'.$SUBTXT.': '.(trim($DTA['prop_name']) !== '' ? $DTA['prop_name'] : EST_MSG_APROP).'</div>
              <div id="estS2">'.EST_MSG_SUB2.'</div>
              <div id="estMsgFNG">'.EST_MSG_FNG.'</div>
              <div id="estEmS1">'.EST_MSG_SEND.'<div>'.$tp->toHTML($DTA['prop_seller']).'</div></div>
              <div id="estMsgRes">'.EST_MSG_RESULTS.'</div>
              <div id="estEmSent0">'.EST_MSG_EMSENT0.'</div>
              <div id="estEmSent1">'.EST_MSG_EMSENT1.'</div>
              <div id="estEmSent4">'.EST_MSG_EMSENT4.'</div>
              <div id="estEmSent5">'.EST_MSG_EMSENT5.'</div>
              <div id="estPmSent">'.EST_MSG_PMSENT.'</div>
              <div id="estThks1">'.EST_MSG_THANKS1.'</div>
              <div id="estThks2">'.EST_MSG_THANKS2.'</div>
            </div>
          </td>
        </tr>
      </thead>
      <tbody id="estMsgFormTB"'.($msg_idx > 0 ? '' : ' style="display:none;"').'>
        <tr>
          <td>
            <fieldset><legend>'.EST_MSG_YOURNAME.':</legend>
            <input type="text" name="msg_from_name" class="tbox form-control estChkMsg" data-len="6" value="'.$tp->toTEXT($msg_from_name).'" placeholder="'.EST_MSG_YOURNAME.' ('.EST_GEN_REQUIURED.')" />
            <input type="hidden" name="msg_idx" value="'.$msg_idx.'"/>
            <input type="hidden" name="msg_sent" value="'.$msg_sent.'"/>
            <input type="hidden" name="msg_read" value="'.$msg_read.'"/>
            <input type="hidden" name="msg_to_uid" value="'.$msg_to_uid.'"/>
            <input type="hidden" name="msg_to_addr" value="'.$msg_to_addr.'"/>
            <input type="hidden" name="msg_propidx" value="'.$msg_propidx.'"/>
            <input type="hidden" name="msg_top" value="'.$msg_top.'"/>
            <input type="hidden" name="msg_from_uid" value="'.$msg_from_uid.'"/>
            <input type="hidden" name="msg_from_ip" value="'.$msg_from_ip.'"/>
            </fieldset>
          </td>
        </tr>
        <tr>
          <td>
            <fieldset><legend>'.EST_MSG_YOUREMAIL.':</legend>
            <input type="text" name="msg_from_addr" class="tbox form-control estChkMsg" data-req="@" data-len="6" value="'.$tp->toTEXT($msg_from_addr).'" placeholder="'.EST_MSG_YOUREMAIL.' ('.EST_GEN_REQUIURED.')" />';
            
          if($COPY_PREF == 1 && check_class($EST_PREF['contact_cc'])){
            $ret .= '
            <div id="estMsgCCdiv">
              <input type="checkbox" id="msg-from-cc" name="msg_from_cc" value="1" class="tbox INLBLK" /><label for="msg-from-cc">'.EST_MSG_CCME.'</label>
            </div>';
            }
          $ret .= '
            </fieldset>
          </td>
        </tr>
        <tr>
          <td>
            <fieldset><legend>'.EST_MSG_YOUREPHONE.':</legend>
            <input type="text" name="msg_from_phone" '.($EST_PREF['contact_phone'] == 1 ? 'class="tbox form-control estChkMsg"  placeholder="'.EST_MSG_YOUREPHONE.' ('.EST_GEN_REQUIURED.')"': 'class="tbox form-control"  placeholder="'.EST_MSG_YOUREPHONE.'"').' data-len="8" value="'.$tp->toTEXT($msg_from_phone).'" />
            </fieldset>
          </td>
        </tr>
        <tr id="msgTopTR">
          <td>
            <fieldset><legend>'.EST_MSG_SUBJECT.':</legend>
            <input type="text" name="msg_top" class="tbox form-control estChkMsg" value="'.$msg_top.'" data-len="6" placeholder="'.EST_MSG_SUBJECT.'"/>
            </fieldset>
          </td>
        </tr>
        <tr>
          <td>
            <fieldset><legend>'.EST_MSG_YOURMSG.':</legend>
            <textarea name="msg_text" class="tbox form-control estChkMsg" cols="40" rows="6" data-len="18" placeholder="'.EST_MSG_MSGTXTPL.' ('.EST_GEN_REQUIURED.')">'.$tp->toTEXT($DTA['msg_text']).'</textarea>
            </fieldset>
          </td>
        </tr>
        <tr>
          <td class="TAC">
            <button id="estMsgSend0" class="btn btn-primary">'.EST_MSG_VIEWAGREE.'</button>
          </td>
        </tr>
      </tbody>
      <tfoot id="estMsgFormTF"'.($msg_idx > 0 ? '' : ' style="display:none;"').'>
        <tr>
          <td id="estMsgBtnTD" class="TAC">
            <div id="estMsgTerms">
              '.$tp->toHTML(trim($EST_PREF['contact_terms']) !== '' ? $EST_PREF['contact_terms'] : EST_MSG_CONSTXT1.'<br /><br />'.EST_MSG_CONSTXT2).'
            </div>
            <button id="estMsgSend1" class="btn btn-primary" data-t1="'.EST_MSG_SEND.'" data-t2="'.$tp->toHTML($DTA['prop_seller']).'" disabled="disabled" >'.EST_MSG_CONSBTN.'</button>
          </td>
        </tr>
      </tfoot>
    </table>';
    }
    
  $ret .= '
  </div>';
  
  $ret .= $PREVDIV;
  
  unset($PREVDIV);
  return $ret;
  }

?>