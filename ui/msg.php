<?php
if(!defined('e107_INIT')){
  if((isset($_POST['sndMsg']) || isset($_POST['sndLike']) || isset($_POST['msgRead'])) && isset($_POST['tdta'])){
    define('e_TOKEN_DISABLE',true);
    require_once('../../../class2.php');
    e107::includeLan(e_PLUGIN.'estate/languages/'.e_LANGUAGE.'/'.e_LANGUAGE.'_msg.php');
    require_once('../estate_defs.php');
    $sql = e107::getDB();
    $tp = e107::getParser();
    $EST_PREF = e107::pref('estate');
    //$RES['sent']['raw'] = $_POST['tdta'];
    }
  else{exit;}
  
  $RES = array();
  if(isset($_POST['sndMsg'])){
    //$sec_img = e107::getSecureImg();
    if(!check_class($EST_PREF['contact_class'])){exit;}
    
    $TOUID = intval($_POST['tdta']['msg_to_uid']);
    $ei = 0;
    
    if($TOUID == 0){$RES['error'][$ei] = EST_MSG_ERRNOUID; $ei++;}
    
    $MSG = est_msg_proc($_POST['tdta']);
    $RES['sent']['proc'] = $MSG;
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
    
    
    if(intval($MSG['msg_mode']) > 2){
      if(!$AGENT){$RES['error'][$ei] = EST_MSG_ERRNOTAGENT; $ei++;}
      else{$MSG['msg_propidx'] = intval(0);}
      }
    
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
    
		$siteadminemail = e107::getPref('siteadminemail');
		$siteadmin = e107::getPref('siteadmin');
		require_once(e_HANDLER.'mail_manager_class.php');
		$mailer = new e107MailManager;
    
		//$triggerData = $MSG;
	  //e107::getEvent()->trigger('estate_message', $triggerData);
	  //ob_start();
		//$mailer->trackEmail($MSG);
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
    
  

		$vars = array('x'=>$MSG['msg_to_name'], 'y'=>$MSG['msg_from_name'], 'z'=>$MSG['msg_top']);
    $message = "[html]".$tp->lanVars('<strong>'.EST_MSG_DONOTRPLY.'</strong> '.EST_MSG_TOPPER, $vars,true)." <br /><br /><blockquote>".$tp->toEmail($MSGTXT.' <br /><br />'.$MSGFOOT, false)."</blockquote>[/html]";
    
  
		// Create the mail body
		$mailData = array(
				'mail_total_count'      => count($recipients),
				'mail_content_status' 	=> MAIL_STATUS_TEMP,
				'mail_create_app' 		=> 'estate',
				'mail_title' 			=> 'ESTATE TRACKING',
				'mail_subject' 			=> $MSG['msg_top'],
				'mail_sender_email' 	=> e107::getPref('replyto_email',$siteadminemail),
				'mail_sender_name'		=> e107::getPref('replyto_name',$siteadmin),
				'mail_notify_complete' 	=> 0,
				'mail_body' 			=> $message,
				'template'				=> 'default',
				'mail_send_style'       => 'default',
        'overrides'=>array(
          'replyto_email'=>$MSG['msg_from_addr'],
          'replyto_name'=>$MSG['msg_from_name']
          )
		  );
    /*
    
    */
    
    if(ADMINPERMS === '0'){$RES['email']['data'] = $mailData;}
    
		$opts =  array(); // array('mail_force_queue'=>1); array(email_replyto)
    $emsent = $mailer->sendEmails('default', $mailData, $recipients, $opts);
    $MSG['msg_email'] = ($emsent == true ? 1 : 0);
    
    //	$thread_name = str_replace('&quot;', '"', $thread_name);// This not picked up by toText();
  
    
		$MSGID = $sql->insert('estate_msg', $MSG);
		$MSG['msg_idx'] = $MSGID;
  
  
    if(e107::isInstalled('pm')){
      if($MSG['msg_to_uid'] > 0){
        $pm_prefs = e107::getPlugPref('pm');
        //$pmClass = varset($pm_prefs['pm_class'], e_UC_NOBODY);
        //check_class($pmClass)
        
        //EST_USERPERM
        
  		  
      
        if($MSG['msg_from_uid'] == 0){
          // not a member, send as the seller
          $MSG['msg_from_uid'] = intval($MSG['msg_to_uid']);
          $MSG['msg_top'] = '('.EST_GEN_NONMEMBER.') '.$MSG['msg_top'];
          /*
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
          */
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
        $sql->update("estate_msg","msg_pm='".intval($MSG['msg_pm'])."' WHERE msg_idx='".$MSGID."' LIMIT 1");
        }
      }
    
    $RES['msg'] = $MSG;
    $RES['pvw'] = estPrevMsg($MSG);
    
    $dberr = $sql->getLastErrorText();
    if($dberr){
      $RES['error'][0] = $dberr;
      unset($dberr);
      }
    echo $tp->toJSON($RES);
    unset($RES,$TOUID,$MSG,$AGENT,$TOUSER,$EMAIL_TO_NAME,$EMAIL_TO_ADDR,$FROMUSER);
    exit;
    }
  
  elseif(isset($_POST['msgRead'])){
    //pmid
    $RES = array();
    $RES['dta'] = $_POST['tdta']['dta'];
    $IDX = intval($RES['dta']['idx']);
    if($IDX > 0){
      $PID = intval($RES['dta']['pid']);
      
      if(e107::isInstalled('pm')){$PMID = intval($RES['dta']['pmid']);}
      else{$PMID = intval(0);}
      
      if(intval($RES['dta']['del']) === $IDX){
        if($sql->delete("estate_msg", "msg_idx='".$IDX."' LIMIT 1")){
          $RES['dta']['del'] = 1;
          if($PMID > 0){$sql->delete("private_msg", "pm_id='".$PMID."' LIMIT 1");}
          }
        else{$RES['dta']['del'] = 0;}
        }
      else{
        $RES['dta']['del'] = intval(0);
        if(intval($RES['dta']['read']) == 0){$RES['dta']['read'] = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));}
        else{$RES['dta']['read'] = intval(0);}
        if($sql->update("estate_msg","msg_read='".intval($RES['dta']['read'])."' WHERE msg_idx='".$IDX."' LIMIT 1")){$RES['dta']['up'] = 1;}
        else{$RES['dta']['up'] = intval(0);}
        if($PMID > 0){$sql->update("private_msg","pm_read='".intval($RES['dta']['read'])."' WHERE pm_id='".$PMID."' LIMIT 1");}
        }
      }
    //estPrevMsgPvw
    echo $tp->toJSON($RES);
    unset($RES);
    exit;
    }
  
  elseif(isset($_POST['sndLike'])){
    $RES['like'] = 0;
    if(!check_class($EST_PREF['listing_save'])){exit;}
    $PID = intval($_POST['tdta']['pid']);
    if($PID > 0){
      $likedb = $sql->retrieve("SELECT * FROM #estate_likes WHERE like_pid='".$PID."' AND  like_uid='".USERID."' AND like_ip='".USERIP."'",true);
      if(count($likedb) > 0){
        foreach($likedb as $lk=>$lv){
          if($sql->delete("estate_likes", "like_idx = '".intval($lv['like_idx'])."' LIMIT 1")){
            $RES['rem'][$lk] = $lv['like_idx'];
            }
          }
        $RES['like'] = -1;
        }
      else{
        $RES['like'] = $sql->insert("estate_likes","'0','".$PID."','".intval(USERID)."','".USERIP."'");
        
        }
      }
    echo $tp->toJSON($RES);
    unset($RES);
    exit;
    }
  
  exit;
  }




  




	//$randnum = rand(1000, 9999);

e107::css('url',e_PLUGIN.'estate/css/msg.css');
e107::includeLan(e_PLUGIN.'estate/languages/'.e_LANGUAGE.'/'.e_LANGUAGE.'_msg.php');
e107::js('estate','js/msg.js', 'jquery');

define("EST_MSGTYPES",array('',EST_MSG_SHOWINGREQUESTS,EST_MSG_OFFERS,EST_MSG_QUOTEREQ,EST_MSG_OTHERQUESTIONS));


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
    'msg_mode'=>(intval($DTA['msg_mode']) > 1 ? intval($DTA['msg_mode']) : 1),
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



function estPrevMsgPvw($mv,$mode=0){
  $tp = e107::getParser();
  
  if(intval($mv['msg_read']) > 0){
    $Btn2ttl = 'title="'.EST_MSG_MARKUNREAD.'" data-ttl="'.EST_MSG_MARKREAD.'"';
    $BTN2Cls = 'fa fa-arrow-up';
    $BTNEnv = '<i class="fa fa-envelope-open"></i>';
    }
  else{
    $Btn2ttl = 'title="'.EST_MSG_MARKREAD.'" data-ttl="'.EST_MSG_MARKUNREAD.'"';
    $BTN2Cls = 'fa fa-check';
    $BTNEnv = '<i class="fa fa-envelope"></i>';
    }
  
  switch($mode){
    case 2 :
      //<i class=""></i>
      //<i class="fa fa-solid fa-trash-arrow-up"></i>
      
      
      return '
          <div class="estMsgBtn" data-idx="'.intval($mv['msg_idx']).'" data-pid="'.intval($mv['msg_propidx']).'" data-mode="'.intval($mv['msg_mode']).'" data-read="'.intval($mv['msg_read']).'" data-pmid="'.intval($mv['msg_pm']).'" data-del="0">
            <button class="btn dtn-default estMsgBtnBlock">
              <div class="estViewMsg">'.$tp->toHTML($mv['msg_top']).'</div>
              <div class="estMarkMsg" '.$Btn2ttl.'><i class="'.$BTN2Cls.'"></i></div>
              <div class="estDelMsg" title="'.EST_MSG_DELETE.'" data-msg="'.EST_MSG_DELALERT.'"><i class="fa fa-trash-can"></i></div>
            </button>
            <div class="estMsgP">
              <h4>'.$tp->toHTML($mv['msg_top']).'</h4>
              <p>'.$tp->toHTML($mv['msg_text']).'</p>
              <div class="estInBoxHead">
                <div>'.EST_MSG_RECD.': '.$tp->toDate($mv['msg_sent'],'long').'</div>
                <div>'.EST_GEN_FROM.': '.$tp->toHTML($mv['msg_from_name']).' '.(intval($mv['msg_from_uid']) == 0 ? '('.EST_GEN_NONMEMBER.')' : '').' </div>
                <div>'.EST_GEN_EMAIL.': '.$tp->toHTML($mv['msg_from_addr']).' </div>
                <div>'.EST_GEN_PHONE.': '.$tp->toHTML($mv['msg_from_phone']).' </div>
                <div>'.EST_MSG_PRIVATEMSG.': '.(intval($mv['msg_from_uid']) == 0 ? EST_GEN_NOT.' '.EST_GEN_AVAILABLE : (intval($mv['msg_pm']) == 1 ? EST_MSG_PMSENT : EST_MSG_PMNOTSENT)).' </div>
              </div>
            </div>
          </div>';
      break;
      
    case 1 :
      return '
      <div class="estMsgP" style="display:block;">
        <h4>'.$BTNEnv.$tp->toHTML($mv['msg_top']).'</h4>
        <h5>'.$tp->toDate($mv['msg_sent'],'long').(intval($mv['msg_read']) > 0 ? ' <i>'.EST_MSG_RECD.' '.$tp->toDate($mv['msg_read'],'short').'</i>' : '').'</h5>
        <p>'.$tp->toHTML($mv['msg_text']).'</p>
      </div>';
      break;
    
    default :
      return '
      <div class="estMsgBtn">
        <button class="btn btn-default estViewMsg">'.$BTNEnv.$tp->toHTML($mv['msg_top']).'</button>
        <div class="estMsgP">
        <h4>'.$tp->toHTML($mv['msg_top']).'</h4>
        <h5>'.$tp->toDate($mv['msg_sent'],'long').(intval($mv['msg_read']) > 0 ? ' <i>'.EST_MSG_RECD.' '.$tp->toDate($mv['msg_read'],'short').'</i>' : '').'</h5>
          <p>'.$tp->toHTML($mv['msg_text']).'</p>
        </div>
      </div>';
    }
  }




function estGetPrevMsgs($PID=0,$QRY=0){
  $sql = e107::getDB();
  $tp = e107::getParser();
  $EST_PREF = e107::pref('estate');
  
  $PREVMSG = array();
  $AGO = mktime(0,0,0, date("m"), date("d") - intval($EST_PREF['contact_life']), date("Y"));
  $MQRY = "SELECT #estate_msg.* FROM #estate_msg WHERE ".(USERID > 0 ? "msg_from_uid='".USERID."'" : "msg_from_ip='".USERIP."' AND NOT msg_from_uid > '0'")." AND msg_sent >= '".$AGO."'";
  
  if(intval($EST_PREF['contact_maxto']) > 1){$MQRY .= " AND msg_read = '0'";}
  
  if(intval($PID) > 0){
    if(intval($EST_PREF['contact_maxto']) == 1 || intval($EST_PREF['contact_maxto']) == 3){
      $MQRY .= " AND msg_propidx='".$PID."'"; // OR (msg_propidx='0' AND msg_mode > '2')
      }
    }
  else{
    if(intval($EST_PREF['contact_maxto']) == 1 || intval($EST_PREF['contact_maxto']) == 3){
      $MQRY .= " AND NOT msg_propidx > '0'";
      }
    }
  $MQRY .= " ORDER BY msg_read ASC, msg_sent DESC"; // LIMIT ".intval($EST_PREF['contact_max']); 
  $mi = 0;
  $sql->gen($MQRY);
  while($rows = $sql->fetch()){
    $PREVMSG[$mi] = $rows;
    $mi++;
    }
  return $PREVMSG;
  }





function estMsgInbox(){
  $sql = e107::getDB();
  $ns = e107::getRender();
  $tp = e107::getParser();
  $EST_PREF = e107::pref('estate');
  $MSGS = array();
  $MQRY = "SELECT #estate_msg.* FROM #estate_msg WHERE msg_to_uid='".USERID."' ORDER BY msg_read ASC, msg_mode ASC, msg_sent DESC";
  $sql->gen($MQRY);
  while($rows = $sql->fetch()){
    $MSGS[$rows['msg_mode']][$rows['msg_idx']] = $rows;
    }
  
  if(EST_USERPERM > 0 || count($MSGS) > 0){
    $NMSGC = 0;
    $RMSG = array();
    $txt = '
    <div id="estInBoxCont" data-jspth="'.SITEURLBASE.e_PLUGIN_ABS.'estate/">';
    foreach(EST_MSGTYPES as $tk=>$tv){
      $SMSGC = 0;
      foreach($MSGS[$tk] as $mk=>$mv){
        if(intval($mv['msg_read']) > 0){$RMSG[$mk] = $mv;}
        else{
          $MSGTXT .= estPrevMsgPvw($mv,2);
          $NMSGC++; $SMSGC++;
          }
        }
      $txt .= '
      <div class="estInBoxSect estNewMsgs"'.($SMSGC > 0 ? '' : ' style="display:none;"').'>
        <button class="btn btn-primary estSectBtn">'.EST_GEN_NEW.' '.$tp->toHTML($tv).' (<span>'.$SMSGC.'</span>)</button>
        
        <div id="estInBoxBelt-'.$tk.'" class="estMsgBelt" data-tk="'.$tk.'" style="display:none;">'.$MSGTXT.'</div>
      </div>';
      unset($MSGTXT);
      }
    
    $txt .= '
      <div class="estInBoxSect"'.(count($RMSG) > 0 ? '' : ' style="display:none;"').'>
        <button class="btn btn-primary estSectBtn">'.EST_MSG_READ.' '.EST_GEN_MESSAGES.' (<span>'.count($RMSG).'</span>)</button>
        <div id="estInBoxBelt-read" class="estMsgBelt" data-tk="read" style="display:none;">';
      if(count($RMSG) > 0){
        foreach($RMSG as $mk=>$mv){
          $txt .= estPrevMsgPvw($mv,2);
          }
        }
      $txt .= '
        </div>
      </div>
      <p id="estMsgPlsMrk" class="estNoteTxt" '.($NMSGC > 0 ? '' : ' style="display:none;"').'>'.EST_MSG_INBOXNOTE1.'</p>
    </div>';
    
    $capt = '<a id="estInBoxBtn">'.(EST_USERPERM > 0 ? EST_GEN_AGENT : EST_GEN_SELLER).' '.EST_MSG_INBOX.' (<span id="estMsgCtNew">'.intval($NMSGC).'</span> '.EST_GEN_NEW.')</a>';
    
    $retrn = $ns->tablerender($capt, $txt, 'estInboxMenu1',true);
    
    unset($MSGS,$RMSG,$NMSGC,$SMSGC,$capt,$txt);
    
    return $retrn;
    }
  }




function estPrevMsg($MSG){
  //$sql = e107::getDB();
  $tp = e107::getParser();
  $EST_PREF = e107::pref('estate');
  $MPID = intval($MSG['msg_propidx']);
  $mctr = 0;
  $mctu = 0;
  
  $PREVMSG = estGetPrevMsgs($MPID);
  $PMSGCT = count($PREVMSG);
  
  if($PMSGCT > 0){
    foreach($PREVMSG as $mk=>$mv){
      if($mv['msg_read'] > 0){$mctr++;}
      else{$mctu++;}
      
      if($MPID > 0 && intval($mv['msg_propidx']) == $MPID){
        
        }
      
      }
    }
  
  $PREVDIV = '
    <h4 id="estPrevMsgBtn" class="btn btn-primary"'.($PMSGCT > 0 ? '' : ' style="display:none;"').'>'.EST_MSG_PREVMSG.' ('.$mctr.' '.EST_MSG_READ.', '.$mctu.' '.EST_MSG_UNREAD.')</h4>';
  
  if($PMSGCT > 0){
    $PREVDIV .= '
    <div id="estMsgPrevBelt" class="estMsgBelt" style="display:none;">';
    if($PMSGCT == 1){
      $PREVDIV .= estPrevMsgPvw($PREVMSG[0],1);
      }
    else{
      foreach($PREVMSG as $mk=>$mv){$PREVDIV .= estPrevMsgPvw($mv);}
      }
    $PREVDIV .= '
    </div>';
    }
  
  $PMSGWARN = '';
  if($PMSGCT >= intval($EST_PREF['contact_max'])){
    $PMSGWARN = '<div id="estMsgWarn">'.EST_MSG_REACHMAX1.' '.$tp->toHTML($MSG['msg_to_name']).' 
    '.(intval($MSG['msg_propidx']) > 0 && (intval($EST_PREF['contact_maxto']) == 1 || intval($EST_PREF['contact_maxto']) == 3) ? EST_MSG_REACHMAX2 : '').'
    </div>';
    }
  
  return array($PREVDIV,$PMSGWARN);
  }




function est_msg_form($DTA=null){
	if(!$DTA['prop_seller']){
    return (ADMIN ? EST_GEN_UNK.' '.EST_GEN_SELLER : '');
    }
  
  //$sql = e107::getDB();
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
    0=>EST_GEN_CONTACT.' '.$DTA['prop_seller'].' ('.EST_GEN_SELECTONE.')'
    );
  
  
  //$EST_COOKIE = $_COOKIE['estate-'.USERID.'-msgs']; //.intval($MSG['msg_propidx'])]
  
  $SUBTXT1 = EST_MSG_SUB1A;
  $SUBTXT2 = EST_MSG_SUB2A;
  
  $TXTTXT1 = EST_MSG_TXT1A;
  $TXTTXT2 = EST_MSG_TXT2A;
  
  switch(intval($DTA['prop_status'])){
    case 4 :  //Pending
      $EST_MSG_MODES[2] = EST_MSG_IWANTOFFERB;
      $SUBTXT2 = EST_MSG_SUB2B;
      $TXTTXT2 = EST_MSG_TXT2B;
      break;
    case 3 : 
      $EST_MSG_MODES[1] = EST_MSG_IWANTVIEW;
      $EST_MSG_MODES[2] = EST_MSG_IWANTOFFERA;
      break;
       
    case 2 : 
      if(intval($DTA['prop_datelive']) > 0 && intval($DTA['prop_datelive']) <= $MSG['msg_sent']){
        $EST_MSG_MODES[1] = EST_MSG_IWANTVIEW;
        $EST_MSG_MODES[2] = EST_MSG_IWANTOFFERA;
        }
      elseif(USERID > 0 && (intval($DTA['prop_dateprevw']) > 0 && intval($DTA['prop_dateprevw']) <= $MSG['msg_sent'])){
        $EST_MSG_MODES[1] = EST_MSG_IWANTVIEW;
        $EST_MSG_MODES[2] = EST_MSG_IWANTOFFERA;
        }
      else{
        
        }
      break;
    }
  
  
  
  
  if(intval($DTA['agent_idx']) > 0){
    $EST_MSG_MODES[3] = EST_MSG_IWANTSELL;
    $EST_MSG_MODES[4] = EST_MSG_IWANTOTHER;
    }
  
  ksort($EST_MSG_MODES);
  $PREVDIV = estPrevMsg($MSG);
  
  
  $ret = '
  <div id="estMsgFormDiv" class="WD100">';
  if(trim($PREVDIV[1]) !== ''){
    $ret .= $PREVDIV[1];
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
      
      $ret .= '<option value="'.$k.'">'.$tp->toHTML($v).'</option>'; //'.($k == $msg_mode ? 'selected="selected"' : '').'
      }

  
    $ret .= '
            </select>
            <div id="estMsgDefs" style="display:none;">
              <div id="estS1">'.$SUBTXT1.': '.(trim($DTA['prop_name']) !== '' ? $DTA['prop_name'] : EST_MSG_APROP).'</div>
              <div id="estS2">'.$SUBTXT2.': '.(trim($DTA['prop_name']) !== '' ? $DTA['prop_name'] : EST_MSG_APROP).'</div>
              <div id="estS3">'.EST_MSG_SUB3.'</div>
              
              <div id="estT1">'.$TXTTXT1.' '.(trim($DTA['prop_name']) !== '' ? $DTA['prop_name'] : EST_MSG_APROP).'. '.EST_MSG_END.'</div>
              <div id="estT2">'.$TXTTXT2.' '.(trim($DTA['prop_name']) !== '' ? $DTA['prop_name'] : EST_MSG_APROP).'. '.EST_MSG_END.'</div>
              <div id="estT3">'.EST_MSG_TXT3.'</div>
              
              <div id="estMsgFNG">'.EST_MSG_FNG.'</div>
              <div id="estEmS1">'.EST_MSG_SEND.'<div>'.$tp->toHTML($DTA['prop_seller']).'</div></div>
              <div id="estMsgRes">'.EST_MSG_RESULTS.'</div>
              <div id="estEmSent0">'.EST_MSG_EMSENT0.'</div>
              <div id="estEmSent1">'.EST_MSG_EMSENT1.'</div>
              <div id="estEmSent4">'.EST_MSG_EMSENT4.'</div>
              <div id="estEmSent5">'.EST_MSG_EMSENT5.'</div>
              <div id="estPmSent">'.EST_MSG_PMALSOSENT.'</div>
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
  
  $ret .= '<div id="estMsgPrevDiv" class="WD100">'.$PREVDIV[0].'</div>';
  
  unset($PREVDIV);
  return $ret;
  }

?>