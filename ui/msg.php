<?php
if(!defined('e107_INIT')){exit;}


if(e107::isInstalled('pm')){
  //e107::getMessage()->addInfo('PM Installed');
  }

e107::includeLan(e_PLUGIN.'estate/languages/'.e_LANGUAGE.'/'.e_LANGUAGE.'_msg.php'); //???



//EST_SELLERUID

  //e107::getMessage()->addInfo('Contact System Enabled');

//USERIP

if(EST_SELLERUID > 0){
  //e107::getMessage()->addInfo('Test: '.USERIP);
  
  if(EST_AGENTID > 0){
    
    }
  
  
  
  }




function est_msg_proc($DTA){
  $tp = e107::getParser();
  if(trim($DTA['msg_text']) == ''){
    $DTA['msg_text'] = EST_MSG_DEF1A.(trim($DTA['prop_name']) !== '' ? $DTA['prop_name'] : EST_MSG_APROP).'. '.EST_MSG_DEF1B;
    }
  
  $MSG = array(
    'msg_idx'=>(intval($DTA['msg_idx']) > 0 ? intval($DTA['msg_idx']) : intval(0)),
    'msg_sent'=>(intval($DTA['msg_sent']) > 0 ? intval($DTA['msg_sent']) : mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"))),
    'msg_read'=>(intval($DTA['msg_read']) > 0 ? intval($DTA['msg_read']) : intval(0)),
    'msg_uid_to'=>(intval($DTA['msg_uid_to']) > 0 ? intval($DTA['msg_uid_to']) : intval(0)),
    'msg_mode'=>(intval($DTA['msg_mode']) > 0 ? intval($DTA['msg_mode']) : intval(0)),
    'msg_propidx'=>(intval($DTA['msg_propidx']) > 0 ? intval($DTA['msg_propidx']) : (intval($DTA['prop_idx']) > 0 ? intval($DTA['prop_idx']) : intval(0))),
    'msg_from_uid'=>intval($DTA['msg_from_uid'] ? $DTA['msg_from_uid'] : USERID),
    'msg_from_ip'=>$tp->toTEXT($DTA['msg_from_ip'] ? $DTA['msg_from_ip'] : USERIP),
    'msg_from_name'=>$tp->toTEXT(trim($DTA['msg_from_name']) !== '' ? $DTA['msg_from_name'] : (defined("USERNAME") ? USERNAME : '')),
    'msg_from_email'=>$tp->toTEXT(trim($DTA['msg_from_email']) ? $DTA['msg_from_email'] : USEREMAIL),
    'msg_from_phone'=>$tp->toTEXT($DTA['msg_from_phone']),
    'msg_text'=>$tp->toTEXT($DTA['msg_text']),
    );
  
  return $MSG;
  
  }




function est_msg_form($DTA=null){
	if(!$DTA['prop_seller']){
    return EST_GEN_UNK.' '.EST_GEN_SELLER;
    }
  
  $tp = e107::getParser();
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
  if(intval($DTA['agent_idx']) > 0){$EST_MSG_MODES[1] = EST_MSG_IWANTVIEW;}
  if(intval($DTA['agent_idx']) > 0){$EST_MSG_MODES[2] = EST_MSG_IWANTSELL;}
  
  ksort($EST_MSG_MODES);
  // e_TBQS
  
  $ret = '
  <div class="WD100">
    <table id="estMsgFormTabl" class="table WD100 TAL">
      <thead>
        <tr style="display:none;">
          <td>
            <input type="text" name="mail_to" class="tbox form-control estChkMsgRem" value="istatrap@icloud.com" />
            <input type="text" name="mail_from" class="tbox form-control estChkMsgRem" value="istatrap@icloud.com" />
            <input type="text" name="mail_subject" class="tbox form-control" value="it is a trap" />
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
          </td>
        </tr>
      </thead>
      <tbody id="estMsgFormTB"'.($msg_idx > 0 ? '' : ' style="display:none;"').'>
        <tr>
          <td>
            <input type="text" name="msg_from_name" class="tbox form-control estChkMsg" data-len="6" value="'.$tp->toTEXT($msg_from_name).'" placeholder="'.EST_MSG_YOURNAME.' ('.EST_GEN_REQUIURED.')" />
          </td>
        </tr>
        <tr>
          <td>
            <input type="text" name="msg_from_email" class="tbox form-control estChkMsg" data-req="@" data-len="6" value="'.$tp->toTEXT($msg_from_email).'" placeholder="'.EST_MSG_YOUREMAIL.' ('.EST_GEN_REQUIURED.')" />
          </td>
        </tr>
        <tr>
          <td>
            <input type="text" name="msg_from_phone" '.($EST_PREF['contact_phone'] == 1 ? 'class="tbox form-control estChkMsg"  placeholder="'.EST_MSG_YOUREPHONE.' ('.EST_GEN_REQUIURED.')"': 'class="tbox form-control"  placeholder="'.EST_MSG_YOUREPHONE.'"').' data-len="8" value="'.$tp->toTEXT($msg_from_phone).'" />
          </td>
        </tr>
        <tr>
          <td>
            <textarea name="msg_text" class="tbox form-control estChkMsg" cols="40" rows="6" data-len="18" placeholder="'.EST_MSG_MSGTXTPL.' ('.EST_GEN_REQUIURED.')">'.$tp->toTEXT($DTA['msg_text']).'</textarea>
            <div id="estMsgDef1" style="display:none;">'.$msg_text.'</div>
            <div id="estMsgDef2" style="display:none;">'.EST_MSG_DEF2.'</div>
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
            <input type="hidden" name="msg_idx" value="'.$msg_idx.'"/>
            <input type="hidden" name="msg_sent" value="'.$msg_sent.'"/>
            <input type="hidden" name="msg_read" value="'.$msg_read.'"/>
            <input type="hidden" name="msg_uid_to" value="'.$msg_uid_to.'"/>
            <input type="hidden" name="msg_propidx" value="'.$msg_propidx.'"/>
            <input type="hidden" name="msg_from_uid" value="'.$msg_from_uid.'"/>
            <input type="hidden" name="msg_from_ip" value="'.$msg_from_ip.'"/>
          </td>
        </tr>
      </tfoot>
    </table>
  </div>';
  
  return $ret;
  }



  /*
  
  EST_MSG_YOURNAME
  
  
  
  
  
  if('inbox' == $which)
		{
			$qry = "SELECT count(pm.pm_id) AS total, SUM(pm.pm_size)/1024 size, SUM(pm.pm_read = 0) as unread FROM `#private_msg` as pm WHERE pm.pm_to = ".USERID." AND pm.pm_read_del = 0";
		}
		else
		{
			$qry = "SELECT count(pm.pm_from) AS total, SUM(pm.pm_size)/1024 size, SUM(pm.pm_read = 0) as unread FROM `#private_msg` as pm WHERE pm.pm_from = ".USERID." AND pm.pm_sent_del = 0";
		}
    
    
    
	function pm_get($pmid)
	{
		$qry = "
		SELECT pm.*, ut.user_image AS sent_image, ut.user_name AS sent_name, uf.user_image AS from_image, uf.user_name AS from_name, uf.user_email as from_email, ut.user_email as to_email  FROM #private_msg AS pm
		LEFT JOIN #user AS ut ON ut.user_id = pm.pm_to
		LEFT JOIN #user AS uf ON uf.user_id = pm.pm_from
		WHERE pm.pm_id='".intval($pmid)."'
		";
		if (e107::getDb()->gen($qry))
		{
			$row = e107::getDb()->fetch();
			return $row;
		}
		return FALSE;
	}
    
  
	function add($vars)
	{

		$tp = e107::getParser();
		$sql = e107::getDb();
		$pmsize = 0;
		$attachlist = '';
		$pm_options = '';
		$ret = '';
		$addOutbox = TRUE;
		$timestamp = time();
		$a_list = array();

		$maxSendNow = varset($this->pmPrefs['pm_max_send'],100);	// Maximum number of PMs to send without queueing them
		if (isset($vars['pm_from']))
		{	// Doing bulk send off cron task
			$info = array();
			foreach ($vars as $k => $v)
			{
				if (strpos($k, 'pm_') === 0)
				{
					$info[$k] = $v;
					unset($vars[$k]);
				}
			}
			$addOutbox = FALSE;			// Don't add to outbox - was done earlier
		}
		else
		{	// Send triggered by user - may be immediate or bulk dependent on number of recipients
			$vars['options'] = '';
			if(isset($vars['receipt']) && $vars['receipt']) {$pm_options .= '+rr+';	}

			if(isset($vars['uploaded']))
			{
				foreach($vars['uploaded'] as $u)
				{
					if (!isset($u['error']) || !$u['error'])
					{
						$pmsize += $u['size'];
						$a_list[] = $u['name'];
					}
				}
				$attachlist = implode(chr(0), $a_list);
			}
			$pmsize += strlen($vars['pm_message']);

			$pm_subject = trim($tp->toDB($vars['pm_subject']));
			$pm_message = trim($tp->toDB($vars['pm_message']));
			
			if (!$pm_subject && !$pm_message && !$attachlist)
			{  // Error - no subject, no message body and no uploaded files
				return LAN_PM_65;
			}
			
			// Most of the pm info is fixed - just need to set the 'to' user on each send
			$info = array(
				'pm_from' => $vars['from_id'],
				'pm_sent' => $timestamp,				
				'pm_read' => 0,						
				'pm_subject' => $pm_subject,
				'pm_text' => $pm_message,
				'pm_sent_del' => 0,						
				'pm_read_del' => 0,						
				'pm_attachments' => $attachlist,
				'pm_option' => $pm_options,	
				'pm_size' => $pmsize
				);

		//	print_a($info);
		//	print_a($vars);
		}

		if(!empty($vars['pm_userclass']) || isset($vars['to_array']))
		{
			if(!empty($vars['pm_userclass']))
			{
				$toclass = e107::getUserClass()->getName($vars['pm_userclass']);
				$tolist = $this->get_users_inclass($vars['pm_userclass']);
				$ret .= LAN_PM_38.": {$toclass}<br />";
				$class = TRUE;
				$info['pm_sent_del'] = 1; // keep the outbox clean and limited to 1 entry when sending to an entire class.
			}
			else
			{
				$tolist = $vars['to_array'];
				$class = FALSE;
			}
			// Sending multiple PMs here. If more than some number ($maxSendNow), need to split into blocks.
			if (count($tolist) > $maxSendNow)
			{
				$totalSend = count($tolist);
				$targets = array_chunk($tolist, $maxSendNow);		// Split into a number of lists, each with the maximum number of elements (apart from the last block, of course)
				unset($tolist);

				$pmInfo = $info;
				$genInfo = array(
					'gen_type' => 'pm_bulk',
					'gen_datestamp' => time(),
					'gen_user_id' => USERID,
					'gen_ip' => ''
					);
				for ($i = 0; $i < count($targets) - 1; $i++)
				{	// Save the list in the 'generic' table
					$pmInfo['to_array'] = $targets[$i];			// Should be in exactly the right format
					$genInfo['gen_intdata'] = count($targets[$i]);
					$genInfo['gen_chardata'] = e107::serialize($pmInfo,TRUE);
					$sql->insert('generic', array('data' => $genInfo, '_FIELD_TYPES' => array('gen_chardata' => 'string')));	// Don't want any of the clever sanitising now
				}
				$toclass .= ' ['.$totalSend.']';
				$tolist = $targets[count($targets) - 1];		// Send the residue now (means user probably isn't kept hanging around too long if sending lots)
				unset($targets);
			}
			foreach($tolist as $u)
			{
				set_time_limit(30);
				$info['pm_to'] = intval($u['user_id']);		// Sending to a single user now

				if($pmid = $sql->insert('private_msg', $info))
				{
					$info['pm_id'] = $pmid;
					e107::getEvent()->trigger('user_pm_sent', $info);

					unset($info['pm_id']); // prevent it from being used on the next record.

					if($class == FALSE)
					{
						$toclass .= $u['user_name'].', ';
					}
					if(check_class($this->pmPrefs['notify_class'], null, $u['user_id']))
					{
						$vars['to_info'] = $u;
						$vars['pm_sent'] = $timestamp;
						$this->pm_send_notify($u['user_id'], $vars, $pmid, count($a_list));
					}
				}
				else
				{
					$ret .= LAN_PM_39.": {$u['user_name']} <br />";
					e107::getMessage()->addDebug($sql->getLastErrorText());
				}
			}
			if ($addOutbox)
			{
				$info['pm_to'] = $toclass;		// Class info to put into outbox
				$info['pm_sent_del'] = 0;
				$info['pm_read_del'] = 1;
				if(!$pmid = $sql->insert('private_msg', $info))
				{
					$ret .= LAN_PM_41.'<br />';
				}
			}
			
		}
		else
		{	// Sending to a single person
			$info['pm_to'] = intval($vars['to_info']['user_id']);		// Sending to a single user now




			if($pmid = $sql->insert('private_msg', $info))
			{
				$info['pm_id'] = $pmid;
				$info['pm_sent'] = $timestamp;
				e107::getEvent()->trigger('user_pm_sent', $info);


				if(check_class($this->pmPrefs['notify_class'], null, $vars['to_info']['user_id']))
				{
					set_time_limit(20);
					$vars['pm_sent'] = $timestamp;
					$this->pm_send_notify($vars['to_info']['user_id'], $vars, $pmid, count($a_list));
				}
				$ret .= LAN_PM_40.": {$vars['to_info']['user_name']}<br />";
			}
		}
		return $ret;
	}
    
    
  */


?>