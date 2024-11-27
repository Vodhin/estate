<?php
if(!empty($_POST) && !isset($_POST['e-token'])){
	$_POST['e-token'] = ''; // make sure e-token hasn't been deliberately removed.
  }

if(!defined('e107_INIT')){exit;}
if(!defined("USERID") || USERID == 0){exit;}


if(EST_USERPERM > 0){
  
  $msg = e107::getMessage();
  
  
    //$EXPR = mktime(0,0,0, date("m"), date("d"), date("Y"));
    //$sql = e107::getDB();
    //$sql->delete("estate_likes", "like_exp <= '".$EXPR."'");
    //$sql->delete("estate_msg", "msg_exp <= '".$EXPR."'");
    //unset($EXPR);
    
  
  
  
  if(isset($_POST['estAdminNewListing'])){
    $sql = e107::getDB();
		$tp = e107::getParser();
    $EST_PREF = e107::pref('estate');
    if(!is_array($EST_PREF['locale'])){$EST_PREF['locale'] = [3,1,'en_US','USD'];}
        
    $NEWPROP = array();
    $TNOW = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
    $_POST['prop_idx'] = intval(0);
    $_POST['prop_agency'] = EST_AGENCYID;
    $_POST['prop_agent'] = EST_AGENTID;
    $_POST['prop_datecreated'] = $TNOW;
    $_POST['prop_dateupdated'] = $TNOW;
    $_POST['prop_uidcreate'] = USERID;
    $_POST['prop_uidupdate'] = USERID;
    $_POST['prop_listprice'] = intval($_POST['prop_origprice']);
    if(isset($_POST['locale'])){
      $_POST['prop_locale'] = (is_array($_POST['locale']) ? implode(",",$_POST['locale']) : $EST_PREF['locale']);
      }
    
    
    $FLDS = $sql->db_FieldList('estate_properties');
    foreach($FLDS as $k=>$v){$NEWPROP[$v] = ($_POST[$v] ? $tp->toDB($_POST[$v]) : '');}
    unset($FLDS);
    
    $newId = $sql->insert("estate_properties",$NEWPROP);
    $dberr = $sql->getLastErrorText();
    if($dberr){$msg->addError('<p>'.$dberr.'</p>');}
    else{
      if(intval($newId) > 0){
        e107::redirect(e_SELF."?mode=estate_properties&action=edit&id=".$newId);
        }
      }
    }
  
  
  if(isset($_POST['estProfileSubmit'])){
    $sql = e107::getDB();
		$tp = e107::getParser();
    $msg = e107::getMessage();
  	$PDTA = $_POST;
    
    $KEY = intval($PDTA['estProfileKey']);
    if($KEY < 5 || $KEY > 6){return;}
    
    $USRERR = array();
    
    $KYS = array(
      5=>array('agency_idx','agency_name','agency_image','agency_imgsrc',EST_GEN_AGENCY),
      6=>array('agent_idx','agent_name','agent_image','agent_imgsrc',EST_GEN_AGENT)
      );
    
    if($KEY == 6 && intval($PDTA['agent_idx']) == 0){
      if($sql->count('estate_agents', "(*)", "WHERE `agent_name`='".$tp->toDB($PDTA['agent_name'])."'")){
        $msg->addWarning(EST_ERR_DUPEAGENT.'<p>'.EST_ERR_DUPEAGENT1.'</p>');
        return;
        }
      }
		
    if($KEY == 5 && intval($PDTA['agency_idx']) == 0){
      if($sql->count('estate_agencies', "(*)", "WHERE `agency_name`='".$tp->toDB($PDTA['agency_name'])."'")){
        $msg->addWarning(EST_ERR_DUPEAGENCY.'<p>'.EST_ERR_DUPEAGENCY1.'</p>');
        return;
        }
      }
    
    if(isset($PDTA['estNewUserKey']) && intval($PDTA['estNewUserKey']) === 1){
      $ANU = intval(e107::pref('estate','addnewuser'));
      $ESTNEWUSRPOST = -1;
      if($ANU > 1 && EST_USERPERM >= $ANU){
  		  require_once (e_HANDLER.'resize_handler.php');
        require_once(e_HANDLER.'user_handler.php');
        $UserHandler = new UserHandler; //vG'e?2Qi4:9K
        //$userMethods = e107::getUserSession();
        
        $estNewUserId = intval(0);
        
        $USRPOST = array(
          'user_id'=>'0',
          'user_name'=>$tp->toDB($PDTA['agent_name']),
          'user_loginname'=>$tp->toDB($PDTA['user_loginname']), //loginname
          'user_login'=>$tp->toDB($PDTA['user_login']),//realname
          'user_email'=>$tp->toDB($PDTA['user_email']),
          'user_hideemail'=>'0',
          'user_password'=>$sql->escape($UserHandler->HashPassword($PDTA['password'],$tp->toDB($PDTA['loginname'])), false),
          'user_class'=>ESTATE_AGENT,
          'user_perms'=>EST_PLUGID,
          'user_admin'=>1,
          'user_signature'=>$tp->toDB($PDTA['agent_txt1']),
          );
        
        $USRDTA = array();
        $USRFLDS = $sql->db_FieldList('user');
        foreach($USRFLDS as $k=>$v){$USRDTA[$v] = (isset($USRPOST[$v]) ? $USRPOST[$v] : '');}
        unset($USRFLDS);
        
        $exUser = $sql->count('user', "(*)", "WHERE `user_name`='".$USRDTA['user_name']."' OR  `user_loginname`='".$USRDTA['user_loginname']."' OR  `user_login`='".$USRDTA['user_login']."' OR  `user_email`='".$USRDTA['user_email']."'");
				if($exUser > 0){
          $msg->addWarning(EST_ERR_DUPEUSER.'<p>'.EST_ERR_DUPEUSER1.'</p>');
          return;
          }
        
        $estNewUserId = $sql->insert("user",$USRDTA);
        $dberr = $sql->getLastErrorText();
        if($dberr){
          $msg->addWarning('<p>'.$dberr.'</p>');
          unset($dberr);
          return;
          }
          
  				
        if(intval($estNewUserId) > 0){
          $PDTA['agent_uid'] = intval($estNewUserId);
          $ESTURSID = $PDTA['agent_uid'];
          $ESTNEWUSRPOST = $PDTA['agent_uid'];
          
          if(intval($_FILES['file_userfile']['error']['avatar']) == 0 && trim($_FILES['file_userfile']['name']['avatar']) !== ''){
            $ESTNEWUSERIMG = true;
  				  $opts = array('overwrite' => TRUE, 'file_mask'=>'jpg,png,gif,jpeg', 'max_file_count' => 2);
            
            if($uploaded = e107::getFile()->getUploaded(e_AVATAR_UPLOAD, 'prefix+ap_'.$tp->leadingZeros($estNewUserId,7).'_', $opts)){
              if(isset($uploaded[0]['error']) && isset($uploaded[0]['message'])){
                //$msg->addDebug("Uploaded: ".print_a($uploaded,true));
    						$msg->addWarning('<div>'.$uploaded[0]['error'].'<p>'.$uploaded[0]['message'].'</p></div>');
                unset($dberr);
                }
              else{
                $PDTA['user_image'] = '-upload-'.$uploaded[0]['name'];
                $sql->update("user","user_image='".$tp->toDB($PDTA['user_image'])."' WHERE user_id='".intval($estNewUserId)."' LIMIT 1");
                $dberr = $sql->getLastErrorText();
                if($dberr){
                  $msg->addWarning('Avatar NOT Saved: '.$PDTA['user_image'].'<p>'.$dberr.'</p>');
                  unset($dberr);
                  }
                }
              }
            }
          }
        }
      else{
        $msg->addWarning(EST_GEN_ADDNEWUSER.'<p>'.EST_ERR_NOTAUTH1.'</p>');
        return;
        }
      unset($USRFLDS);
      }
    
    if(isset($ESTURSID)){$PDTA[$KYS[$KEY][3]] = 0;}
    
    if(intval($PDTA[$KYS[$KEY][3]]) == 1){
      //if(intval($_FILES['estAvatarUpload']['error']) !== 0){$PDTA[$KYS[$KEY][3]] = 0;}
      //if(trim($_FILES['estAvatarUpload']['name']) === '' && trim($PDTA[$KYS[$KEY][2]]) === ''){$PDTA[$KYS[$KEY][3]] = 0;}
      }
    
    
    
    $estateCore = new estateCore;
    $ESTDB = $estateCore->estDBUp($KEY,$PDTA);
    if(!$ESTDB['error']){
      $pidx = intval($ESTDB[$KYS[$KEY][0]]);
      if($pidx > 0){
        if($KEY == 6){
          $agent_idx = $pidx;
          if(!isset($ESTURSID)){$ESTURSID = intval($ESTDB['agent_uid']);}
          if(isset($ESTNEWUSRPOST) && intval($ESTNEWUSRPOST) > 0){
            $sql->insert("estate_contacts","'0','6','".$agent_idx."','".$tp->toDB(EST_CONTKEYS[1])."','1','".$tp->toDB($PDTA['user_email'])."'");
            e107::redirect(e_SELF."?mode=estate_agencies&action=agent&id=".$agent_idx);
            }
          }
        elseif($KEY == 5){
          $agency_idx = $pidx;
          }
        if(intval($ESTDB[$KYS[$KEY][3]]) == 1 && intval($_FILES['estAvatarUpload']['error']) == 0 && trim($_FILES['estAvatarUpload']['name']) !== ''){
          if(!isset($ESTNEWUSERIMG)){estInitUpl($_FILES['estAvatarUpload'],$KEY,intval($pidx));}
          }
        }
      else{$msg->addError('<div>'.EST_ERR_FAILADDNEW.' '.$KYS[$KEY][4].': '.$tp->toHTML($_POST[$KYS[$KEY][1]]).'</div>');}
      }
    }
  }




function estInitUpl($file,$LOC,$DBIDX,$MDB=null){
	$sql = e107::getDB();
  $tp = e107::getParser();
  $msg = e107::getMessage();
  $estateCore = new estateCore;
  
  $RES = array();
  
  $uploaddir = 'temp';
  if(is_dir($uploaddir) && is_writable($uploaddir)){
    $UP_FILE = $file["tmp_name"]; //the file
    $UP_NAME = strtolower($file["name"]); // name.ext
    $UP_EXT = array_pop(explode(".",$UP_NAME)); // ext
    $UP_TYPE = $file["type"]; // mime type
    $UP_SIZE = $file["size"]; // size
    
    $tpos = strrchr($UP_NAME, '.');
    if($tpos !== FALSE){
      $res = fopen($UP_FILE, 'rb');
      $tstr = fread($res,100);
      fclose($res);
      if($tstr === FALSE){$UPERR = '<li>'.EST_ERR_UPLNOREAD.'</li>';}
      if(stristr($tstr,'<?php') !== FALSE){$UPERR .= '<li>'.EST_ERR_UPLNOPHP.'</li>';}
      }
    if($tpos === FALSE){$UPERR .= '<li>'.EST_ERR_FILENOTALLOWED.": ".$UP_TYPE.'</li>';}
    
    
    $UP_NAME = preg_replace("/[^a-z0-9._-]/", "", str_replace(" ", "_", str_replace("%20", "_", $UP_NAME)));
    if(trim($UP_NAME) == ""){$UP_NAME = "unknown_filename_".rand(99,99999).".".$UP_EXT;}
    
    if(trim($UP_EXT) == ""){$UPERR .= '<li>'.EST_ERR_UPLNOEXT.'</li>';}
    
    if($UPERR){
      if(file_exists($UP_FILE) && is_file($UP_FILE)){
        if(@unlink($UP_FILE)){$UPERR .= '<li>'.EST_ERR_UPFILEREM.': '.$UP_FILE.'</li>';}
        else{$UPERR .= '<li>'.EST_ERR_UPFILEREMFAIL.': '.$UP_FILE.'</li>';}
        }
      
      $msg->addError('<div>'.EST_GEN_UPLOADING.' '.$UP_NAME.'<ul>'.$UPERR.'</ul></div>');
      clearstatcache();
      return;
      }
    else{
      if($UP_TYPE =='image/jpeg' || $UP_TYPE =='image/png' || $UP_TYPE =='image/gif'){
        
        switch($LOC){
          case 6 :
            $THMBFILEDIR = 'media/agent';
            $UP_NAME = 'agent-'.intval($DBIDX).".".$UP_EXT;
            $ORPH = estChkOrphanFiles($THMBFILEDIR,$UP_NAME,$UP_NAME);
            foreach($ORPH as $mk=>$mv){$msg->addInfo($mv);}
            break;
          
          case 5 : 
            $THMBFILEDIR = 'media/agency';
            $UP_NAME = 'agency-'.intval($DBIDX).".".$UP_EXT;
            $ORPH = estChkOrphanFiles($THMBFILEDIR,$UP_NAME,$UP_NAME);
            foreach($ORPH as $mk=>$mv){$msg->addInfo($mv);}
            break;
          
          case 4 : 
            return; //not used 
            break;
          
          case 1 :
            $THMBFILEDIR = "media/subdiv/thm";
            $FULLFILEDIR = "media/subdiv/full";
            $VIDFILEDIR = "media/subdiv/vid";
            //$subd_idx = intval($_POST['subd_idx']);
            $media_lev = 0;
            $media_propidx = 0;
            break;
          case 0 :
            $THMBFILEDIR = "media/subdiv/thm";
            $FULLFILEDIR = "media/subdiv/full";
            $VIDFILEDIR = "media/subdiv/vid";
            $media_lev = 0;
            $media_propidx = 0;
            break;
          }
        
        
        $exif = exif_read_data($UP_FILE);
        $RES['exif'] = $exif;
        $ROTATE = 0;
        if(!empty($exif['Orientation'])){
          switch($exif['Orientation']) {
            case 8: $ROTATE = 90; break;
            //case 3: $ROTATE = 180; break;
            case 6: $ROTATE = -90; break;
            }
          }
        
        $UP_DEST = realpath($THMBFILEDIR)."/".$UP_NAME;
        if(@move_uploaded_file($UP_FILE, $UP_DEST)){
          @chmod($UP_DEST, 0644);
          
          list($AW,$AH,$AT) = getimagesize($UP_DEST);
          clearstatcache();
          
          $RESIZE = false;
          $MAXSZ = 2048;
          
          if($ROTATE !== 0 || $AW > $MAXSZ || $AH > $MAXSZ){
            if($AW > $MAXSZ || $AH > $MAXSZ){
              $RESIZE = true;
              if($AW > $AH){$RW = $MAXSZ; $RH = round(($AH / $AW) * $MAXSZ);}
              else{$RW = round(($AW / $AH) * $MAXSZ); $RH = $MAXSZ;}
              }
            else{$RW = $AW; $RH = $AH;}
            
            if($ROTATE !== 0){
              $XRW = $RH;
              $RH = $RW;
              $RW = $XRW;
              $XAW = $AH;
              $AH = $AW;
              $AW = $XAW;
              unset($XRW,$XAW);
              }
            
            if($RW > 0 && $RH > 0){
              switch($AT){
                case 1 : $SRCIMG = imagecreatefromgif($UP_DEST); break;
                case 2 : $SRCIMG = imagecreatefromjpeg($UP_DEST); break;
                case 3 : $SRCIMG = imagecreatefrompng($UP_DEST); break;
                }
              
              if($ROTATE !== 0){$SRCIMG = imagerotate($SRCIMG,$ROTATE,0);}
              
              $DESTIMG = imagecreatetruecolor($RW,$RH);
              imagecopyresampled($DESTIMG,$SRCIMG,0,0,0,0,$RW,$RH,$AW,$AH);
              
              switch($AT){
                case 1 : imagegif($DESTIMG,$UP_DEST,80); break;
                case 2 : imagejpeg($DESTIMG,$UP_DEST,80); break;
                case 3 : imagepng($DESTIMG,$UP_DEST,1); break;
                }
              
              imagedestroy($SRCIMG);
              imagedestroy($DESTIMG);
              clearstatcache();
              }
            }
          
          //$msg->addInfo('<div>'.EST_GEN_UPLOADING.' '.$UP_NAME.'<ul><li>'..'</li></ul></div>');
          if(intval($DBIDX) > 0){
            switch($LOC){
              case 6 : 
                if($sql->update("estate_agents","agent_image='".$tp->toDB($UP_NAME)."', agent_imgsrc='1' WHERE agent_idx='$DBIDX' LIMIT 1")){
                  $msg->addInfo('<div>'.EST_GEN_UPLOADING.' '.$UP_NAME.'<ul><li>'.EST_GEN_FILE.' '.$UP_NAME.' '.EST_GEN_ADDEDTO.' '.EST_GEN_AGENT.' '.EST_GEN_PROFILE.'</li></ul></div>');
                  }
                else{
                  $msg->addInfo('<div>'.EST_GEN_UPLOADING.' '.$UP_NAME.'<ul><li>'.EST_GEN_AGENT.' '.EST_GEN_PROFILE.' '.EST_GEN_UPDATEDWITH.' '.EST_GEN_FILE.' '.$UP_NAME.'</li></ul></div>');
                  }
                break;
              case 5 : 
                if($sql->update("estate_agencies","agency_image='".$tp->toDB($UP_NAME)."', agency_imgsrc='1' WHERE agency_idx='$DBIDX' LIMIT 1")){
                  $msg->addInfo('<div>'.EST_GEN_UPLOADING.' '.$UP_NAME.'<ul><li>'.EST_GEN_FILE.' '.$UP_NAME.' '.EST_GEN_ADDEDAS.' '.EST_GEN_AGENCY.' '.EST_GEN_LOGO.'</li></ul></div>');
                  }
                else{
                  $msg->addInfo('<div>'.EST_GEN_UPLOADING.' '.$UP_NAME.'<ul><li>'.EST_GEN_AGENCY.' '.EST_GEN_LOGO.' '.EST_GEN_UPDATEDWITH.' '.EST_GEN_FILE.' '.$UP_NAME.'</li></ul></div>');
                  }
                break;
              case 4 : 
                //old Company logo upload
                break;
              
              default :
                if($MDB !== null){
                  extract($MDB);
                  
                  if($media_idx == 0){
                    //$media_idx = estUDMediaDb($media_idx,$media_propidx,$media_lev,$media_levidx,$media_levord,$media_galord,$media_asp,$media_type,$tp->toDB($UP_NAME),$tp->toDB($newmedia_full),$media_name);
                    }
                  else{
                    
                    }
                  
                  }
                break;
              }
            }
          else{
            $msg->addWarning('<div>'.EST_GEN_UPLOADING.' '.$UP_NAME.'<ul><li>Database Index ('.$DBIDX.') missing.</li></ul></div>');
            }
          
          
          if($RESIZE !== false){$RES['upl']['resize'] = $RESIZE;}
          $RES['upl']['base'] = str_replace(".$UP_EXT","",$UP_NAME);
          $RES['upl']['name'] = $UP_NAME;
          $RES['upl']['dir'] = $THMBFILEDIR;
          $RES['upl']['type'] = $UP_TYPE;
          }
        else{
          $msg->addError('<div>'.EST_GEN_UPLOADING.' '.$UP_NAME.'<ul><li>'.EST_ERR_FILENOTSAVED.': '.$UP_FILE.' to '.$THMBFILEDIR.'</li></ul></div>');
          }
        }
      else{
        $msg->addError('<div>'.EST_GEN_UPLOADING.' '.$UP_NAME.'<ul><li>'.EST_ERR_FILENOTALLOWED.": ".$UP_FILE.".".$UP_TYPE.'</li></ul></div>');
        }
      }
    }
  else{
    $UPDIREX = is_dir($uploaddir);
    $UPDIRWR = is_writable($uploaddir);
    $msg->addError('<div>'.EST_GEN_UPLOADING.' '.$UP_NAME.'<ul><li>'.EST_GEN_UPFOLDER." [$uploaddir] ".($UPDIREX ? EST_GEN_DOESEXIST : EST_GEN_DOESNOTEXIST)." ".($UPDIRWR ? EST_GEN_ANDIS : EST_GEN_ANDISNOT)." ".EST_GEN_WRITABLE.'</li></ul></div>');
    }
  
  return $RES;
  }





function estChkOrphanFiles($PTH,$BASENAME,$FMATCH=''){
  $RES = array();
  if(strrchr($BASENAME, '.')){
    $BASENAME = explode('.',$BASENAME)[0];
    }
  if(is_dir($PTH) && is_writable($PTH)){
    $i = 0;
    foreach(EST_IMGTYPES as $fk=>$FEXT){
      $THUMBFILE = $PTH.'/'.$BASENAME.$FEXT;
      if(file_exists($THUMBFILE) && is_file($THUMBFILE)){
        if($FMATCH !== '' && $FMATCH == $BASENAME.$FEXT){}
        else{
          if(@unlink($THUMBFILE)){$RES[$i] = EST_GEN_DELETED." ".$THUMBFILE; $i++;}
          else{$RES[$i] = EST_ERR_FAILDELETE." ".$THUMBFILE; $i++;}
          }
        }
      clearstatcache();
      }
    }
  else{$RES[0] = EST_ERR_FILEDIR1.' '.$PTH.' '.(!is_dir($PTH) ? EST_ERR_ISNOTDIR : EST_ERR_ISNOTWRITE);}
  return $RES;
  }






function estMediaRemove($MDTA,$THMBFILEDIR,$FULLFILEDIR,$VIDFILEDIR,$DBDO=0){
  $RES = array();
  $EST_PREF = e107::pref('estate');
  if(EST_USERPERM > 0 || (intval($EST_PREF['public_act']) !== 0 && USERID > 0 && check_class($EST_PREF['public_act']))){
    
    //need to add check to see if user owns image...
    if(!is_writable($THMBFILEDIR)){
      $RES['error'] .= EST_GEN_DIRECTORY.': '.$THMBFILEDIR.' '.EST_GEN_ISNOTACCESS;
      }
    if(!is_writable($FULLFILEDIR)){
      $RES['error'] .= ($RES['error'] ? '<br />' : '').EST_GEN_DIRECTORY.': '.$FULLFILEDIR.' '.EST_GEN_ISNOTACCESS;
      }
    if(!is_writable($VIDFILEDIR)){
      $RES['error'] .= ($RES['error'] ? '<br />' : '').EST_GEN_DIRECTORY.': '.$VIDFILEDIR.' '.EST_GEN_ISNOTACCESS;
      }
    
    if($RES['error']){
      return $RES;
      }
    
    $RES['vid'] = -1;
    $RES['full'] = -1;
    $RES['thm'] = -1;
    $RES['db'] = -1;
    
    if(intval($MDTA['media_idx']) > 0){
      if(trim($MDTA['media_vid']) !== ""){
        $VIDFILE = $VIDFILEDIR."/".$MDTA['media_vid'];
        if(file_exists($VIDFILE) && is_file($VIDFILE)){
          if(@unlink($VIDFILE)){$RES['vid'] = EST_UPL_FILEVIDREM.": ".$VIDFILE;}
          else{$RES['vid'] = EST_UPL_FILEVIDNOTREM.": ".$VIDFILE;}
          }
        //else{$RES['vid'] = EST_GEN_FILENOTFOUND.": ".$VIDFILE;}
        clearstatcache();
        }
      
      if(trim($MDTA['media_full']) !== ""){
        $FULLFILE = $FULLFILEDIR."/".$MDTA['media_thm'];
        if(file_exists($FULLFILE) && is_file($FULLFILE)){
          if(@unlink($FULLFILE)){$RES['full'] = EST_UPL_FILEFULLREM.": ".$FULLFILE;}
          else{$RES['full'] = EST_UPL_FILEFULLNOTREM.": ".$FULLFILE;}
          }
        //else{$RES['full'] = EST_GEN_FILENOTFOUND.": ".$FULLFILE;}
        clearstatcache();
        }
      
      if(trim($MDTA['media_thm']) !== ""){
        $THUMBFILE = $THMBFILEDIR."/".$MDTA['media_thm'];
        if(file_exists($THUMBFILE) && is_file($THUMBFILE)){
          if(@unlink($THUMBFILE)){$RES['thm'] = EST_UPL_FILETHUMBREM.": ".$THUMBFILE;}
          else{$RES['thm'] = EST_UPL_FILETHUMBNOTREM.": ".$THUMBFILE;}
          }
        else{$RES['thm'] = EST_GEN_FILENOTFOUND.": ".$THUMBFILE;}
        clearstatcache();
        }
      
      if($DBDO == 1){
        $sql = e107::getDB();
        if($sql->delete("estate_media", 'media_idx = '.intval($MDTA['media_idx']).' LIMIT 1')){
          $RES['db'] = EST_GEN_DBUPDATED.' '.intval($MDTA['media_idx']);
          }
        else{$RES['db'] = EST_GEN_DBNOTUPDATED.' '.intval($MDTA['media_idx']);}
        }
      }
    }
  else{$RES['error'] = 'No User Permissions';}
  return $RES;
  unset($RES,$VIDFILE,$FULLFILE,$THUMBFILE);
  }


function estPropDelete($ID){
  if(EST_USERPERM > 0){
    $sql = e107::getDB();
    $tp = e107::getParser();
    
    if($sql->delete("estate_spaces", "space_lev='2' AND space_levidx='".$ID."'")){
      $RESLT .= '<div>'.EST_GEN_PROPERTY.' '.EST_GEN_SPACES.' '.EST_GEN_DBRECORDSREMOVED.'</div>';
      }
    if($sql->delete("estate_grouplist", "grouplist_propidx='".$ID."'")){
      $RESLT .= '<div>'.EST_GEN_PROPERTY.' '.EST_GEN_SPACE.' '.EST_GEN_GROUP.' '.EST_GEN_DBRECORDSREMOVED.'</div>';
      }
    if($sql->delete("estate_featurelist", "featurelist_propidx='".$ID."'")){
      $RESLT .= '<div>'.EST_GEN_PROPERTY.' '.EST_GEN_FEATURES.' '.EST_GEN_DBRECORDSREMOVED.'</div>';
      }
    if($sql->delete("estate_events", "event_propidx='".$ID."'")){
      $RESLT .= '<div>'.EST_GEN_PROPERTY.' '.EST_GEN_EVENTS.' '.EST_GEN_DBRECORDSREMOVED.'</div>';
      }
    if($sql->delete("estate_prophist", "prophist_propidx='".$ID."'")){
      $RESLT .= '<div>'.EST_GEN_PROPERTY.' '.EST_GEN_PRICEHIST.' '.EST_GEN_DBRECORDSREMOVED.'</div>';
      }
    if($sql->delete("estate_likes", "like_pid='".$ID."'")){
      $RESLT .= '<div>'.EST_GEN_PROPERTY.' '.EST_GEN_SAVES.' '.EST_GEN_DBRECORDSREMOVED.'</div>';
      }
    if($sql->delete("estate_msg", "msg_propidx='".$ID."'")){
      $RESLT .= '<div>'.EST_GEN_PROPERTY.' '.EST_GEN_MESSAGES.' '.EST_GEN_DBRECORDSREMOVED.'</div>';
      }
    
    $THMBFILEDIR = "media/prop/thm"; //EST_PTHABS_PROPTHM;//
    $FULLFILEDIR = "media/prop/full"; //EST_PTHABS_PROPFULL;//
    $VIDFILEDIR = "media/prop/vid"; //EST_PTHABS_PROPVID;//
    $sql->gen("SELECT * FROM #estate_media WHERE media_propidx = '$ID'");
    while($rows = $sql->fetch()){
      $MRES = estMediaRemove($rows,$THMBFILEDIR,$FULLFILEDIR,$VIDFILEDIR);
      if($MRES['error']){$MEDIA .= '<li>'.$MRES['error'].'</li>';}
      else{
        if(intval($MRES['vid']) !== -1){$MEDIA .= '<li>'.$MRES['vid'].'</li>';}
        if(intval($MRES['full']) !== -1){$MEDIA .= '<li>'.$MRES['full'].'</li>';}
        if(intval($MRES['thm']) !== -1){$MEDIA .= '<li>'.$MRES['thm'].'</li>';}
        }
      unset($MRES);
      }
    
      $RESLT .= '
      <div>'.EST_GEN_MEDIA.' '.EST_GEN_FILES.':</div>
      <ul id="estMediaDeleted">'.$MEDIA.'</ul>';
      
    
    if($sql->delete("estate_media", "media_propidx='".$ID."'")){ //
      $RESLT .= '<div>'.EST_GEN_PROPERTY.' '.EST_MEDIA.' '.EST_GEN_DBRECORDSREMOVED.'</div>';
      }
    return $RESLT;
    }
  }

?>