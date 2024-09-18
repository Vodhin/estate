<?php
define('e_TOKEN_DISABLE',true);
require_once('../../../../class2.php');
if(!defined("USERID") || USERID == 0){exit;}
/*
if (!getperms('P')) {exit;}
if(!defined("EST_USERPERM")){echo "No Perms"; exit;}
if(intval(EST_USERPERM) < 1){exit;}
define('ADMIN_AREA', true);
*/
e107::lan('estate',true,true);
e107::includeLan(e_PLUGIN.'estate/languages/'.e_LANGUAGE.'/'.e_LANGUAGE.'_msg.php');

$EMQRY = array();
if(e_QUERY && e_QUERY !== ''){$EMQRY = explode('||',e_QUERY);}
$DSPL = ($_GET['rt'] ? $_GET['rt'] : ($_POST['rt'] ? $_POST['rt'] : 'js'));
$FETCH = intval($_GET['fetch'] ? $_GET['fetch'] : ($_POST['fetch'] ? $_POST['fetch'] : intval($EMQRY[0])));

if(!in_array($DSPL,array('js','html','txt','pop'))){
  echo 'Fail Disp ['.$DSPL.'] ['.$FETCH.'] ';
  die;
  }


//require_once(e_PLUGIN.'estate/ui/uipre.php');

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
require_once(e_PLUGIN.'estate/ui/uipost.php');

$estateCore = new estateCore;
$EST_AGENT = $estateCore->estGetUserById(USERID);
$EST_AGENT['perm'] = intval(EST_USERPERM);
define("EST_AGENTID",intval($EST_AGENT['agent_idx']));
define("EST_AGENCYID",intval($EST_AGENT['agent_agcy']));



$sql = e107::getDB();
//$frm = e107::getForm();
$tp = e107::getParser();
$ns = e107::getRender();


$PROPIDREQ = array('estate_grouplist','estate_spaces'); //,'estate_featurelist'

$RES = array();

//preps edit and create - get tables, keys, and fields
$RES['fetch'] = $FETCH;
if($FETCH == 1 || $FETCH == 2){
  $PROPID = intval($_GET['propid']);
  if($PROPID > 0 || $FETCH == 2){
    $RES = estGetAllDta($PROPID);
    }
  else{
    $i=0;
    $RES['thms'] = array();
    $sql->gen("SELECT * FROM #estate_media WHERE media_galord = 1 ORDER BY media_propidx ASC, media_galord ASC");
    while($rows = $sql->fetch()){$RES['thms'][$i] = $rows;$i++;}
    }
  
  $RES['prefs'] = e107::pref('estate');
  $RES['keys'] = estJSkeys();
  $RES['txt'] = estJStext();
  $RES['propid'] = $PROPID;
  $RES['dir'] = estDirList();
  $RES['tbls']['estate_listypes']['dta'] = $sql->retrieve('estate_listypes', '*', '',true);
  $RES['classes'] = $GLOBALS['EST_CLASSES'];
  $pref = e107::pref();
  $RES['weblogo'] = $tp->thumbUrl($pref['sitelogo'],false,false,true);
  $RES['user'] = estCurUser();
  $RES['userlevs'] = EST_USRLEVELS;
  }

else if($FETCH == 3){
  $TDTA = $_POST['tdta'];
  $PROPID = intval($_POST['propid']);
  if($PROPID == 0 && in_array($TDTA[0]['tbl'],$PROPIDREQ)){$RES = array('error'=>EST_ERR_PROPIDZERO,'tdta'=>$TDTA);}
  else{
    $RES = estSaveDB($PROPID,$TDTA);
    $RES['posted'] = $TDTA;
    }
  }


else if($FETCH == 4){ //upload file
  $uploaddir = '../../temp';
  if(is_dir($uploaddir) && is_writable($uploaddir)){
    $PROPID = intval($_POST['propid']);
    $desttarg = intval($_POST['desttarg']);
    
    $RES['desttarg'] = $desttarg;
    $RES['post'] = $_POST;
    
    $THMBFILEDIR = "../../media/prop/thm";
    $FULLFILEDIR = "../../media/prop/full";
    $VIDFILEDIR = "../../media/prop/vid";
    
    $media_idx = intval($_POST['media_idx']);
    $media_lev = intval($_POST['media_lev']); // 0=subdiv, 1=property, 2=spaces, 3=city space, 4=subdiv space
    $media_propidx = ($media_lev == 0 ? 0 : intval($_POST['media_propidx'])); //0 prevents batch delete of media used for other properties
    $media_levidx = intval($_POST['media_levidx']);
    $media_levord = intval($_POST['media_levord']);
    $media_galord = intval($_POST['media_galord']);
    $media_asp = $tp->toDB($_POST['media_asp']);
    $media_type = intval($_POST['media_type']);
    $media_thm = $tp->toDB($_POST['media_thm']);
    $media_full = $tp->toDB($_POST['media_full']);
    $media_name = $tp->toDB($_POST['media_name']);
    
    switch($desttarg){
      case 6 :
        $THMBFILEDIR = "../../media/agent";
        $agent_idx = intval($_POST['agent_idx']);
        $GENFILENAME = ($_POST['genfilename'] ? $tp->toDB($_POST['genfilename']) : 'agent-'.$agent_idx);
        $media_full = "";
        break;
      
      case 5 :
        $THMBFILEDIR = "../../media/agency";
        $agency_idx = intval($_POST['agency_idx']);
        $GENFILENAME = ($_POST['genfilename'] ? $tp->toDB($_POST['genfilename']) : 'agency-'.$agency_idx);
        $media_full = "";
        break;
      
      
      case 1 :
        $THMBFILEDIR = "../../media/subdiv/thm";
        $FULLFILEDIR = "../../media/subdiv/full";
        $VIDFILEDIR = "../../media/subdiv/vid";
        $media_levidx = (isset($_POST['subd_idx']) ? intval($_POST['subd_idx']) : intval($_POST['media_levidx']));
        $media_lev = 0;
        $media_propidx = 0;
        break;
      
      case 0 :
        break;
      default :
        
        break;
      }
    
    
    foreach($_FILES as $filek=>$file){
      $UP_FILE = $file["tmp_name"]; //the file
      $UP_NAME = strtolower($file["name"]); // name.ext
      $UP_EXT = array_pop(explode(".",$UP_NAME)); // ext
      $UP_TYPE = $file["type"]; // mime type
      $UP_SIZE = $file["size"]; // size
      
      
      //  READ FIRST BIT OF FILE TO SEE WHAT'S IN IT
      $tpos = strrchr($UP_NAME, '.');
      if($tpos !== FALSE){
        $res = fopen($UP_FILE, 'rb');
        $tstr = fread($res,100);
        fclose($res);
        if($tstr === FALSE){$RES['error'][$filek]['file'] = "[$filek] ".EST_ERR_UPLNOREAD;}
        if(stristr($tstr,'<?php') !== FALSE){$RES['error'][$filek]['file'] = "[$filek] ".EST_ERR_UPLNOPHP;}
        }
      if($tpos === FALSE){$RES['error'][$filek]['file'] = "[$filek] ".EST_ERR_FILENOTALLOWED.": ".$UP_TYPE;}
      
      $UP_NAME = preg_replace("/[^a-z0-9._-]/", "", str_replace(" ", "_", str_replace("%20", "_", $UP_NAME)));
      
      if(trim($UP_EXT) == ""){$RES['error'][$filek]['file'] = "[$filek] ".EST_ERR_UPLNOEXT;}
      if(trim($UP_NAME) == ""){$UP_NAME = "unknown_filename_".rand(99,99999).".".$UP_EXT;}
      
      
      if(isset($RES['error'][$filek])){
        if(file_exists($UP_FILE) && is_file($UP_FILE)){
          if(@unlink($UP_FILE)){$RES['error'][$filek]['file'] .= " ".EST_ERR_UPFILEREM.": ".$UP_FILE;}
          else{$RES['error'][$filek]['file'] .= " ".EST_ERR_UPFILEREMFAIL.": ".$UP_FILE;}
          }
        else{$RES['error'][$filek]['file'] .= " ".EST_ERR_UPFILEREMFAIL.": ".$UP_FILE." ".EST_ERR_FILENOTFOUND;}
        clearstatcache();
        }
      else{
        
        if($desttarg > 4){
          if(isset($GENFILENAME)){$UP_NAME = $GENFILENAME.".".$UP_EXT;}
          else{
            if($desttarg == 6){$UP_NAME = "agent-".$agent_idx.".".$UP_EXT;}
            elseif($desttarg == 5){$UP_NAME = "agency-".$agency_idx.".".$UP_EXT;}
            }
          }
        else{
          if($media_idx == 0){
            $media_idx = $sql->insert("estate_media","'0','$media_propidx','$media_lev','$media_levidx','$media_levord','$media_galord','$media_asp','$media_type','$media_thm','$media_full','$media_name'");
            }
          //$UP_NAME = preg_replace("/[^a-z0-9._-]/", "", str_replace(" ", "_", str_replace("%20", "_", $UP_NAME)));
          $UP_NAME = intval($media_propidx)."-".intval($media_lev)."-".intval($media_levidx)."-".intval($media_idx).".".$UP_EXT;
          $newmedia_full = "";
          }
          
        
        if($UP_TYPE =='image/jpeg' || $UP_TYPE =='image/png' || $UP_TYPE =='image/gif'){
          $media_type = 1;
          if($desttarg < 3){
            $OLDFULLFILE = $FULLFILEDIR."/".$UP_NAME;
            if(file_exists($OLDFULLFILE) && is_file($OLDFULLFILE)){
              if(@unlink($OLDFULLFILE)){$RES['upl'][$filek]['removed'] = EST_UPL_FILEFULLREM.": ".$OLDFULLFILE;}
              else{$RES['upl'][$filek]['removed'] = EST_UPL_FILEFULLNOTREM.": ".$OLDFULLFILE;}
              }
            clearstatcache();
            }
            
          
          $exif = exif_read_data($UP_FILE);
          $RES['upl'][$filek]['exif'] = $exif;
          $ROTATE = 0;
          if(!empty($exif['Orientation'])){
            switch($exif['Orientation']) {
              case 8: $ROTATE = 90; break;
              //case 3: $ROTATE = 180; break;
              case 6: $ROTATE = -90; break;
              }
            }
          //if(!empty($exif['THUMBNAIL'])){}
          
          
          $UP_DEST = realpath($THMBFILEDIR)."/".$UP_NAME;
          if(@move_uploaded_file($UP_FILE, $UP_DEST)){
            @chmod($UP_DEST, 0644);
            
            list($AW,$AH,$AT) = getimagesize($UP_DEST);
            clearstatcache();
            //if($sql->delete("estate_media", 'media_idx='.intval($media_idx))){}
            
            $RESIZE = false;
            $MAXSZ = 2048;
            
            if($ROTATE !== 0 || $AW > $MAXSZ || $AH > $MAXSZ){
              if($AW > $MAXSZ || $AH > $MAXSZ){
                $RESIZE = true;
                if($desttarg < 4){$newmedia_full = $UP_NAME;}
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
                
                $DESTIMG = imagecreatetruecolor($RW,$RH); //
                imagecopyresampled($DESTIMG,$SRCIMG,0,0,0,0,$RW,$RH,$AW,$AH);
                //imagecopyresampled($DESTIMG,$SRCIMG,$DST_X,$DST_Y,$SRC_X,$SRC_Y,$DST_WIDTH,$DST_HEIGHT,$SRC_WIDTH,$SRC_HEIGHT);
                
                
                switch($AT){
                  case 1 : imagegif($DESTIMG,$UP_DEST,80); break;
                  case 2 : imagejpeg($DESTIMG,$UP_DEST,80); break;
                  case 3 : imagepng($DESTIMG,$UP_DEST,1); break;
                  }
                
                if($desttarg < 4){
                  if($RESIZE == true){
                    $UP_DEST2 = realpath($FULLFILEDIR)."/".$UP_NAME;
                    $DESTIMG2 = imagecreatetruecolor($AW,$AH);
                    imagecopyresampled($DESTIMG2,$SRCIMG,0,0,0,0,$AW,$AH,$AW,$AH);
                    
                    switch($AT){
                      case 1 : imagegif($DESTIMG2,$UP_DEST2,80); $RESIZE = "GIF Resize "; break;
                      case 2 : imagejpeg($DESTIMG2,$UP_DEST2,80); $RESIZE = "JPEG Resize "; break;
                      case 3 : imagepng($DESTIMG2,$UP_DEST2,1); $RESIZE = "PNG Resize "; break;
                      }
                    $RESIZE .= "$AW x $AH > $RW x $RH";
                    imagedestroy($DESTIMG2);
                    }
                  }
                
                imagedestroy($SRCIMG);
                imagedestroy($DESTIMG);
                clearstatcache();
                }
              }
            
            $RES['upl'][$filek]['resize'] = $RESIZE;
            $RES['upl'][$filek]['base'] = str_replace(".$UP_EXT","",$UP_NAME);
            $RES['upl'][$filek]['ext'] = $UP_EXT;
            $RES['upl'][$filek]['thmb'] = $THMBFILEDIR;
            $RES['upl'][$filek]['name'] = $UP_NAME;
            $RES['upl'][$filek]['type'] = $UP_TYPE;
            }
          else{
            $RES['error'][$filek]['file'] .= " ".EST_ERR_FILENOTSAVED." $UP_FILE to $FULLFILEDIR as $UP_NAME ($UP_TYPE) ";
            if(intval($media_idx) > 0){
              if($sql->delete("estate_media", 'media_idx='.intval($media_idx))){
                $RES['error'][$filek]['db'] = EST_ERR_DBMEDIAREMOVED;
                $media_idx = 0;
                }
              }
            }
          
          switch($desttarg){
            case 6 :
              $sql->update("estate_agents","agent_image='$UP_NAME', agent_imgsrc='1' WHERE agent_idx='$agent_idx' LIMIT 1");
              $RES['upl'][$filek]['dberr'] = $sql->getLastErrorText();
              $RES['upl'][$filek]['fdta']['agent_idx'] = intval($agent_idx);
              $RES['upl'][$filek]['fdta']['agent_image'] = $UP_NAME;
              $RES['upl'][$filek]['fdta']['agent_imgsrc'] = 1;
              $ORPH = estChkOrphanFiles('../../media/agent',$UP_NAME,$UP_NAME);
              foreach($ORPH as $mk=>$mv){$RES['upl'][$filek]['orphanchk'][$mk] = $mv;}
              break;
            
            case 5 :
              $sql->update("estate_agencies","agency_image='$UP_NAME', agency_imgsrc='1' WHERE agency_idx='$agency_idx' LIMIT 1");
              $RES['upl'][$filek]['dberr'] = $sql->getLastErrorText();
              $RES['upl'][$filek]['fdta']['agency_idx'] = intval($agency_idx);
              $RES['upl'][$filek]['fdta']['agency_image'] = $UP_NAME;
              $RES['upl'][$filek]['fdta']['agency_imgsrc'] = 1;
              $ORPH = estChkOrphanFiles('../../media/agency',$UP_NAME,$UP_NAME);
              foreach($ORPH as $mk=>$mv){$RES['upl'][$filek]['orphanchk'][$mk] = $mv;}
              break;
            
            default :
              $RES['upl'][$filek] = estUDMediaDb($media_idx,$media_propidx,$media_lev,$media_levidx,$media_levord,$media_galord,$media_asp,$media_type,$tp->toDB($UP_NAME),$tp->toDB($newmedia_full),$media_name);
              break;
            }
          }
        else{
          // the file is not an image - need to do something with it...
          $UP_DEST = realpath($THMBFILEDIR)."/".$UP_NAME;
          if(@move_uploaded_file($UP_FILE, $UP_DEST)){
            @chmod($UP_DEST, 0644);
            $RES['upl'][$filek] = estUDMediaDb($media_idx,$media_propidx,$media_lev,$media_levidx,$media_levord,$media_galord,$media_asp,2,$tp->toDB($UP_NAME),$tp->toDB($newmedia_full),$media_name);
            }
          else{
            $RES['error'][$filek]['file'] .= " ".EST_ERR_FILENOTSAVED." $UP_FILE to $FULLFILEDIR as $UP_NAME ($UP_TYPE) ";if(intval($media_idx) > 0){
              if($sql->delete("estate_media", 'media_idx='.intval($media_idx))){
                $RES['error'][$filek]['db'] = EST_ERR_DBMEDIAREMOVED;
                $media_idx = 0;
                }
              }
            }
          }
        }
      }
    $RES['alldta'] = estGetAllDta($PROPID);
    }
  else{
    $UPDIREX = is_dir($uploaddir);
    $UPDIRWR = is_writable($uploaddir);
    $RES['error'][$filek]['pth'] = EST_GEN_UPFOLDER." [$uploaddir] ".($UPDIREX ? EST_GEN_DOESEXIST : EST_GEN_DOESNOTEXIST)." ".($UPDIRWR ? EST_GEN_ANDIS : EST_GEN_ANDISNOT)." ".EST_GEN_WRITABLE;
    }
  $RES['prefs'] = e107::pref('estate');
  $RES['userlevs'] = EST_USRLEVELS;
  echo json_encode($RES);
  exit;
  }









else if($FETCH == 5){ //delete file
  $RES['sent'] = $_POST['mediadta'];
  $PROPID = intval($_POST['propid']);
  if(intval($_POST['mediadta']['media_idx']) > 0){
    $MDTA = $_POST['mediadta'];
    $THMBFILEDIR = "../../media/prop/thm"; //EST_PTHABS_PROPTHM;//
    $FULLFILEDIR = "../../media/prop/full"; //EST_PTHABS_PROPFULL;//
    $VIDFILEDIR = "../../media/prop/vid"; //EST_PTHABS_PROPVID;//
    $MRES = estMediaRemove($MDTA,$THMBFILEDIR,$FULLFILEDIR,$VIDFILEDIR,1);
    if($MRES['error']){$RES['error'] = $MRES['error'];}
    else{
      if(intval($MRES['vid']) !== -1){$RES['removed']['vid'] = $MRES['vid'];}
      if(intval($MRES['full']) !== -1){$RES['removed']['full'] = $MRES['full'];}
      if(intval($MRES['thm']) !== -1){$RES['removed']['thumb'] = $MRES['thm'];}
      $RES['db'] = $MRES['db'];
      $RES['alldta'] = estGetAllDta($PROPID);
      }
    }
  else{
    $RES['error'] = EST_GEN_MISSING." ".EST_MEDIA." ".EST_INDEX;
    }
  echo $tp->toJSON($RES);
  exit;
  }

else if($FETCH == 6){
  $PROPID = intval($_POST['propid']);
  $TDTA = $_POST['tdta'];
  $RES['sent'] = $TDTA;
  
  $MAINTBL = $_POST['tdta']['maintbl'];
  $MAINKEY = $_POST['tdta']['mainkey'];
  $MAINIDX = ($_POST['tdta']['mainkx'] == 'int' ? intval($_POST['tdta']['mainidx']) : $tp->toDB($_POST['tdta']['mainidx']));
  $MAINFLD = $_POST['tdta']['mainfld'];
  
  $NEWVAL = $tp->toDB($_POST['tdta']['nval']);
  
  $TBLIST = $sql->tables();
  if(!in_array($MAINTBL,$TBLIST)){$RES[0] = array('error'=>EST_ERR_TABLE1);}
  else{
    $FLDLST = $sql->db_FieldList($MAINTBL);
    if(!in_array($MAINKEY,$FLDLST)){$RES[0] = array('error'=>EST_ERR_KEYFIELD1,'tbl'=>$MAINTBL,'tblkey'=>$MAINKEY,'tblfld'=>$FLDLST);}
    else{
      if($MAINIDX > 0){
        if($sql->update($MAINTBL, $MAINFLD."='".$NEWVAL."' WHERE ".$MAINKEY."='".$MAINIDX."'")){
          $RES['alldta'] = estGetAllDta($PROPID);
          }
        }
      }
    }
  echo $tp->toJSON($RES);
  exit;
  }


else if($FETCH == 14){
  $RES = array();
  $RES['agtdel'] = 0;
  if($sql->update("user","user_admin='".intval($_POST['user_admin'])."', user_class='".$tp->toDB($_POST['user_class'])."', user_perms='".$tp->toDB($_POST['user_perms'])."' WHERE user_id='".intval($_POST['user_id'])."' LIMIT 1")){
    $RES['updb'] = 1;
    if(intval($_POST['agtdel']) > 0){
      if($sql->select('estate_agents', '*', 'agent_idx = '.intval($_POST['agtdel']).'')){
        $row = $sql->fetch();
        if(intval($row['agent_idx']) > 0 && intval($row['agent_idx']) == intval($_POST['agtdel'])){
          if($sql->delete('estate_agents', 'agent_idx = '.intval($_POST['agtdel']).' LIMIT 1')){
            $RES['agtdel'] = 1;
            $ORPH = estChkOrphanFiles('../../media/agent','agent-'.intval($row['agent_idx']),'');
            foreach($ORPH as $mk=>$mv){$RES['orphanchk'][$mk] = $mv;}
            }
          }
        }
        
      }
    }
  else{
    $dberr = $sql->getLastErrorText();
    if($dberr){$RES['error'] = $dberr;}
    $RES['updb'] = 0;
    }
  echo $tp->toJSON($RES);
  exit;
  }



else if($FETCH == 20){
  $usr = $_POST['usr'];
  $agency = $_POST['agency'];
  $formDta = array_merge($usr,$agency);
  $text = $estateCore->estAgencyPHPPopoverform($formDta);
  echo $text;
  exit;
  }

else if($FETCH == 21){
  $usr = $_POST['usr'];
  $agy = $_POST['agy'];
  $formDta = array_merge($usr,$agy);
  $text = 'NOT USED';//$estateCore->estAgentForm($formDta);
  echo $text;
  exit;
  }


else if($FETCH == 35){
  $PROPID = intval($_POST['propid']);
  $MNTH = intval($_POST['mnth']);
  echo $estateCore->buildEventCal($PROPID,$MNTH);
  return;
  }




else if($FETCH == 50){
  $RES = array();
  $media_propidx = intval($_POST['mediadta']['media_propidx']);
  $desttarg = intval($_POST['desttarg']);
  
  $FILENAME = $tp->toHTML($_POST['mediadta']['media_thm']);
  
  switch($desttarg){
    case 6 : 
      $DIRF = EST_PTHABS_AGENT;
      $DIRR = "../../media/agent";
      break;
    case 5 : 
      $DIRF = EST_PTHABS_AGENCY;
      $DIRR = "../../media/agency"; 
      break;
    case 1 : 
      //$sql = e107::getDB();
      $DIRF = EST_PTHABS_SUBDTHM;
      $DIRR = "../../media/subdiv/thm"; 
      break;
    default : 
      //$sql = e107::getDB();
      
      $DIRF = EST_PTHABS_PROPTHM;
      $DIRR = "../../media/prop/thm";
      
      //$UP_NAME = intval($media_propidx)."-".intval($media_lev)."-".intval($media_levidx)."-".intval($media_idx).".".$UP_EXT;
      break;
    }
  //EST_PTHABS_PROPTHM
  //realpath($THMBFILEDIR)
  $FTOGET = $DIRR."/".$FILENAME;
  
  $RES['desttarg'] = $desttarg;
  $RES['dir']['abs'] = $DIRF;
  $RES['dir']['rel'] = $DIRR;
  $RES['fname'] = $FILENAME;
  $RES['propid'] = $media_propidx;
  
  if(file_exists($FTOGET) && is_file($FTOGET)){
    $exif = exif_read_data($FTOGET);
    list($AW,$AH,$AT) = getimagesize($FTOGET);
    $RES['w'] = $AW;
    $RES['h'] = $AH;
    $RES['t'] = $AT;
    $RES['exif'] = $exif;
    }
  clearstatcache();
  
  if($AT < 1 || $AT > 3){
    $RES['typerr'] = EST_GEN_FILE.' '.EST_ERR_NOTANIMG;
    }
  
  echo $tp->toJSON($RES);
  exit;
  }

else if($FETCH == 51){
  $RES = $estateCore->estCropImg($_POST['dir']['rel'],$_POST['fname'],0,0,intval($_POST['x1']),intval($_POST['y1']),intval($_POST['x2']),intval($_POST['y2']),intval($_POST['rot']),intval($_POST['sx']),intval($_POST['sy']));
  echo $tp->toJSON($RES);
  exit;
  }



else if($FETCH == 60){
  $RES = array();
  $sql = e107::getDB();
  $UPRES = array();
  if(count($_POST['newzones'])){
    foreach($_POST['newzones'] as $k=>$v){
      $zi = $sql->insert("estate_zoning","'0','".$tp->toDB($v)."'");
      if($zi > 0){$UPRES[$zi] = EST_GEN_ADDED.' '.$tp->toHTML($v);}
      }
    }
  
  if(count($_POST['curzones'])){
    foreach($_POST['curzones'] as $k=>$v){
      if($sql->update("estate_zoning", "zoning_name='".$tp->toDB($v['txt'])."' WHERE zoning_idx='".intval($v['idx'])."'")){
        $UPRES[$v['idx']] = EST_GEN_UPDATED.' '.$tp->toHTML($v['txt']);
        }
      }
    }
  
  if(count($_POST['delzones'])){
    $DELRES = array();
    foreach($_POST['delzones'] as $k=>$v){
      $zidx = intval($v['idx']);
      $DELRES[$zidx]['name'] = $tp->toHTML($v['txt']);
      
      $sql->gen("SELECT prop_idx, prop_name FROM #estate_properties WHERE prop_zoning='".$zidx."'");
      while($rows = $sql->fetch()){$DELRES[$zidx]['prop'][$rows['prop_idx']]['name'] = $rows['prop_name'];}
      
      if(count($DELRES[$zidx]['prop']) == 0){
        $sql->gen("SELECT listype_idx, listype_name FROM #estate_listypes WHERE listype_zone='".$zidx."'");
        while($rows = $sql->fetch()){$DELRES[$zidx]['lists'][$rows['listype_idx']]['name'] = $rows['listype_name'];}
        
        $sql->gen("SELECT group_idx, group_name FROM #estate_group WHERE group_zone='".$zidx."'");
        while($rows = $sql->fetch()){$DELRES[$zidx]['grps'][$rows['group_idx']]['name'] = $rows['group_name'];}
        
        $sql->gen("SELECT featcat_idx, featcat_name FROM #estate_featcats WHERE featcat_zone='".$zidx."'");
        while($rows = $sql->fetch()){$DELRES[$zidx]['cats'][$rows['featcat_idx']]['name'] = $rows['featcat_name'];}
        
        if($sql->delete("estate_zoning", "zoning_idx='".$zidx."'")){
          $DELRES[$zidx]['name'] = EST_GEN_DELETED.' '.EST_GEN_ZONING.' '.LAN_CATEGORY.': '.$DELRES[$zidx]['name'];
          
          if($sql->delete("estate_listypes", "listype_zone='".$zidx."'")){
            $DELRES[$zidx]['lists'] = EST_GEN_DELETED.' '.EST_GEN_LISTYPES;
            }
          
          if(count($DELRES[$zidx]['grps'])){
            foreach($DELRES[$zidx]['grps'] as $groupId=>$grpDta){
              if($sql->delete("estate_group", "group_idx='".$groupId."'")){
                $DELRES[$zidx]['grps'][$groupId]['name'] = ' - '.EST_GEN_DELETED;
                }
              }
            unset($groupId,$grpDta);
            }
          
          
          if(count($DELRES[$zidx]['cats'])){
            foreach($DELRES[$zidx]['cats'] as $catId=>$catDta){
              $sql->gen("SELECT feature_idx, feature_name FROM #estate_features WHERE feature_cat='".intval($catId)."'");
              while($rows = $sql->fetch()){$DELRES[$zidx]['cats'][$catId]['feat'][$rows['feature_idx']]['name'] = $rows['feature_name'];}
              
              if(count($DELRES[$zidx]['cats'][$catId]['feat'])){
                foreach($DELRES[$zidx]['cats'][$catId]['feat'] as $featId=>$fv){
                  if($sql->delete("estate_features", "feature_idx='".intval($featId)."'")){
                    $DELRES[$zidx]['cats'][$catId]['feat'][$featId]['name'] .= ' - '.EST_GEN_DELETED;
                    }
                  if($sql->delete("estate_featurelist", "featurelist_levidx='".intval($featId)."'")){
                    $DELRES[$zidx]['cats'][$catId]['feat'][$featId]['name'] .= ' & '.EST_GEN_DELETED.' '.EST_GEN_FEATURELISTDTA;
                    }
                  }
                unset($featId,$fv);
                }
              if($sql->delete("estate_featcats", "featcat_idx='".intval($catId)."'")){
                $DELRES[$zidx]['cats'][$catId]['name'] .= ' - '.EST_GEN_DELETED;
                }
              unset($catId,$catDta);
              }
            }
          }
        }
      }
    }
  
  
  $text = '';
  
  if(count($UPRES)){
    $text .= '<div class="estDelZoneDta s-message alert alert-block alert-dismissible fade in show info alert-success"><a class="close" data-dismiss="alert" aria-label="Close">×</a><i class="s-message-icon s-message-success"></i><h4 class="s-message-title">'.EST_GEN_UPDATED.' '.EST_GEN_ZONING.' '.EST_GEN_CATEGORIES.'</h4><div class="s-message-body"><ul>';
    foreach($UPRES as $zidx=>$zdta){
      $text .= '<li>'.$zdta.'</li>';
      }
    $text .= '</ul></div></div>';
    }
  
  if(count($DELRES)){
    foreach($DELRES as $zidx=>$zdta){
      $propCt = count($zdta['prop']);
      if($propCt > 0){
        $text .= '<div class="estDelZoneDta s-message alert alert-block alert-dismissible fade in show info alert-warning"><a class="close" data-dismiss="alert" aria-label="Close">×</a><i class="s-message-icon s-message-info"></i><h4 class="s-message-title">'.EST_GEN_CANTDELZONE.'</h4><div class="s-message-body"><div>'.$zdta['name'].' '.EST_GEN_ZONING.': '.$propCt.' '.($propCt == 1 ? EST_GEN_LISTING : EST_GEN_LISTINGS).' '.EST_GEN_FOUND.':<ul>';
        foreach($zdta['prop'] as $pId=>$pDta){$text .= '<li>ID: #'.$pId.' '.$pDta['name'].'</li>';}
        $text .= '</ul></div></div></div>';
        }
      else{
        $text .= '<div class="estDelZoneDta s-message alert alert-block alert-dismissible fade in show info alert-info"><a class="close" data-dismiss="alert" aria-label="Close">×</a><i class="s-message-icon s-message-info"></i><h4 class="s-message-title">'.EST_GEN_DELZONECAT.'</h4><div class="s-message-body"><div>'.$zdta['name'].'<ul>';
        
        if(count($zdta['lists'])){
          foreach($zdta['lists'] as $lId=>$lDta){$text .= '<li>'.$lDta['name'].'</li>';}
          }
        
        if(count($zdta['grps'])){
          foreach($zdta['grps'] as $grpId=>$grpDta){$text .= '<li>'.$grpDta['name'].'</li>';}
          }
        
        if(count($zdta['cats'])){
          foreach($zdta['cats'] as $catId=>$catDta){
            $text .= '<li>'.$catDta['name'];
            if(count($catDta['feat'])){
              $text .= '<ul>';
              foreach($catDta['feat'] as $featId=>$fv){
                $text .= '<li>'.$fv['name'].'</li>';
                }
              $text .= '</ul>';
              }
            $text .= '</li>';
            }
          }
        $text .= '</ul></div></div></div>';
        }
      }
    }
  
  
  $zoning = $estateCore->estGetZoning();
  if(count($zoning)){
    foreach($zoning as $k=>$v){
      $text .= $estateCore->estGetPresetsForm($k,$v);
      }
    }
  
  echo $text;
  unset($UPRES,$DELRES,$text);
  exit;
  }

else if($FETCH == 61){
  }



else if($FETCH == 76){
  $RES = array();
  $PROPID = intval($_GET['propid']);
  $i=0;
  $sql->gen("SELECT * FROM #estate_media WHERE media_propidx=".$PROPID." AND media_galord > '0' ORDER BY media_galord ASC"); 
  while($rows = $sql->fetch()){$RES[$i] = $rows; $i++;}
  echo $tp->toJSON($RES);
  exit;
  }


else if($FETCH == 81){
  $RES = estGetSubDivDta(intval($_GET['subd_idx']),intval($_GET['subd_city']));
  $RES['subd_description'] = $tp->toHTML($RES['subd_description'],true);
  echo e107::getParser()->toJSON($RES);
  /*
  if(EST_USERPERM > 0){
    echo $estateCore->estCommunitySpacesForm($subd_idx,$subd_city);
    }
  else{
    echo '<div class="WD100">'.EST_GEN_NOTAVAIL3.'</div>';
    }
  //echo estSubDivisionView($subd_idx,1);
  */
  exit;
  }


else if($FETCH == 97){
  //$sql = e107::getDB();
  if($_POST['tdta']){
    $FLTR = array();
    $FLTR['WHERE'] = $_POST['tdta']['fltr'];
    $FLTR['ORDER'] = explode(" ",$_POST['tdta']['order']);
    $FLTR['LIMIT'] = explode(",",$_POST['tdta']['limit']);
    $DTA = $estateCore->estPropertyListQry(intval($_POST['tdta']['mode']),$FLTR);
    $DTA['colsp'] = intval($_POST['tdta']['colsp']);
    
    $text = $estateCore->PropertyListTableTR($DTA);
    echo $text;
    }
  exit;
  }

else if($FETCH == 98){
  $RES = array();//estGetAllDta(0);
  $RES['dir'] = estDirList();
  $RES['classes'] = $GLOBALS['EST_CLASSES'];
  $RES['keys'] = estJSkeys();
  $RES['prefs'] = e107::pref('estate');
  
  $RES['tbls']['estate_user']['dta'] = $estateCore->estGetAllUsers();
  $RES['tbls']['estate_agents']['dta'] = $estateCore->estGetAllAgents();
  
  $TBLS = estTablStruct();
  $RES['tbls']['estate_contacts']['dta'] = ($sql->count('estate_contacts') > 0 ? $sql->retrieve('estate_contacts', '*','',true) : array());
  $RES['tbls']['estate_contacts']['flds'] = $sql->db_FieldList('estate_contacts');
  $RES['tbls']['estate_contacts']['form'] = estTablForm($TBLS['estate_contacts'],$RES['tbls']['estate_contacts']['flds']);
  
  $RES['txt'] = estJStext();
  $RES['user'] = estCurUser();
  $RES['userlevs'] = EST_USRLEVELS;
  
  echo $tp->toJSON($RES);
  exit;
  }

else if($FETCH == 99){
  $PROPID = intval($_POST['propid']);
  $DBEGIN = intval($_POST['tdta']['dtB']);
  $DPRVW = intval($_POST['tdta']['dtP']);
  $DLIVE = intval($_POST['tdta']['dtL']);
  $DEND = intval($_POST['tdta']['dtE']);
  
  $DTXTS = '<div>START: '.date("m/d/Y",$DBEGIN).' - '.$DBEGIN.' ['.date("m/d/Y",$prop_datecreated).' - '.$prop_datecreated.']</div>';
  if($DPRVW > 0){$DTXTS .= '<div>PREV: '.date("m/d/Y",$DPRVW).'</div>';}
  if($DLIVE > 0){$DTXTS .= '<div>LIVE: '.date("m/d/Y",$DLIVE).'</div>';}
  if($DEND > 0){$DTXTS .= '<div>END: '.date("m/d/Y",$DEND).'</div>';}
  
  $calDays = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
  
  foreach($calDays as $k=>$v){
    $TBLC .= '<colgroup></colgroup>';
    $TBLH .= '<th>'.$v.'</th>';
    }
  
  echo '<table id="estEvtCal" class="estCalTbl">'.$TBLC.'<thead id="estEvtCalth"><tr>'.$TBLH.'</tr></thead><tbody id="estEvtCaltb"></tbody></table><div style="text-align:left">'.$DTXTS.'<div>'.date("m/d/Y",$STRPRE30DAYS).'</div></div>';
  exit;
  }


else if($FETCH == 100){
  $tp = e107::getParser();
  //estTablStruct
  echo e107::getForm()->flipswitch($_POST['elename'], intval($_POST['eleval']), array($tp->toHTML($_POST['off']),$tp->toHTML($_POST['on'])));
  }

else{
  $RES['txt'] = estJStext();
  $RES['nothing'] = 'Nothing to do';
  }


if($DSPL == 'js'){
  $RES['userlevs'] = EST_USRLEVELS;
  $RES['prefs'] = e107::pref('estate');
  include_once(e_PLUGIN.'estate/templates/estate_template.php');
  
  $RES['prefs']['template_list'] = (isset($RES['prefs']['template_list']) ? $RES['prefs']['template_list'] : 'default');
  $RES['prefs']['template_menu'] = (isset($RES['prefs']['template_menu']) ? $RES['prefs']['template_menu'] : 'default');
  $RES['prefs']['template_view'] = (isset($RES['prefs']['template_view']) ? $RES['prefs']['template_view'] : 'default');
  
  foreach($ESTATE_TEMPLATE as $sk=>$sv){foreach($sv as $tkey=>$tele){unset($ESTATE_TEMPLATE[$sk][$tkey]['txt']);}}
  $RES['prefs']['templates'] = $ESTATE_TEMPLATE;
  
  if(!isset($RES['prefs']['template-view-ord']['default'])){
    //$RES['prefs']['template-view-ord']['default'] = $ESTATE_TEMPLATE['default']['ord'];
    }
  
  /*
  $ti = 0;
  foreach (new DirectoryIterator('../../templates') as $file) {
    if($file->isDot()) continue;
    $fname = $file->getFilename();
    if(strpos($fname, 'template') !== false){
      $RES['prefs']['templateids'][$ti]['id'] = str_replace("_template.php", "",$fname);
      $RES['prefs']['templateids'][$ti]['name'] = $fname;
      $ti++;
      }
    }
  */
  echo $tp->toJSON($RES);
  }
else{echo $RES;}













  //$text = $estateCore->estAgencyPHPPopoverform($formDta);




function estTablForm($TBL,$FLDS){
  $RES = array();
  $DTACHK = array('attr','chks','chng','cls','cspan','fltrs','fnct','hint','hlpm','html','inf','labl','par','plch','rows','src','str','tab','type'); //,'fetch'
  foreach($FLDS as $sk=>$sv){
    $RES[$sv]['ord'] = $sk;
    if($TBL[$sv]){
      $FDTA = $TBL[$sv];
      foreach($DTACHK as $k=>$v){
        if($v == 'str'){$RES[$sv]['str'] = ($FDTA['str'] ? $FDTA['str'] : 0);}
        if($v == 'tab'){$RES[$sv]['tab'] = intval($FDTA['tab']);}
        else{$RES[$sv][$v] = $FDTA[$v];}
        }
      }
    }
  return $RES;
  }


function estDirList(){
  return array(
    'avatar'=>EST_PTHABS_AVATAR,
    'agency'=>EST_PTHABS_AGENCY,
    'agent'=>EST_PTHABS_AGENT,
    'prop'=>array('full'=>EST_PTHABS_PROPFULL,'thm'=>EST_PTHABS_PROPTHM,'vid'=>EST_PTHABS_PROPVID),
    'subdiv'=>array('full'=>EST_PTHABS_SUBDFULL,'thm'=>EST_PTHABS_SUBDTHM,'vid'=>EST_PTHABS_SUBDVID)
    );
  }


function estCurUser(){
  $tp = e107::getParser();
  $RES = $GLOBALS['EST_AGENT'];
  $RES['clist'] = explode(",",USERCLASS_LIST);
  $RES['id'] = intval(USERID);
  $RES['name'] = $tp->toHTML(USERNAME);
  $RES['role'] = EST_USERROLE;
  $RES['perm'] = EST_USERPERM;
  $RES['xro'] = ADMINPERMS;
  $RES['xrm'] = EST_USERMANAGE;
  return $RES;
  }



function estBatchDelSpaces($PROPID,$LEVIDX){
  $UPRES = array();
  $sql = e107::getDB();
  
  $i=0;
  $MROWS = array();
  $sql->gen('SELECT * FROM #estate_media WHERE media_propidx = '.intval($PROPID).' AND media_lev = 2 AND media_levidx = '.intval($LEVIDX));
  while($rows = $sql->fetch()){$MROWS[$i] = $rows; $i++;}
  
  if(count($MROWS) > 0){
    $THMBFILEDIR = "../../media/prop/thm"; //EST_PTHABS_PROPTHM;//
    $FULLFILEDIR = "../../media/prop/full"; //EST_PTHABS_PROPFULL;//
    $VIDFILEDIR = "../../media/prop/vid"; //EST_PTHABS_PROPVID;//
    foreach($MROWS as $k=>$v){
      $MRES = estMediaRemove($rows,$THMBFILEDIR,$FULLFILEDIR,$VIDFILEDIR,1);
      if($MRES['error']){$UPRES['files'][$k]['error'] = $MRES['error'];}
      else{
        if(intval($MRES['vid']) !== -1){$UPRES['files'][$k]['vid'] = $MRES['vid'];}
        if(intval($MRES['full']) !== -1){$UPRES['files'][$k]['full'] = $MRES['full'];}
        if(intval($MRES['thm']) !== -1){$UPRES['files'][$k]['thm'] = $MRES['thm'];}
        $UPRES['files'][$k]['db'] = $MRES['db'];
        $RES['db'] = $MRES['db'];
        }
      }
    $RES['alldta'] = estGetAllDta($PROPID);
    }
  else{$UPRES['files'] = 'No Media Found';}
  
  if($sql->delete("estate_featurelist", 'featurelist_propidx='.intval($PROPID).' AND featurelist_lev=2 AND featurelist_levidx='.intval($LEVIDX))){
    $UPRES['featurelist'] = EST_GEN_DBUPDATED;
    }
  else{$UPRES['featurelist'] = EST_GEN_DBNOTUPDATED;}
  return $UPRES;
  }





function estSaveDB($PROPID,$TDTA){
  $sql = e107::getDB();
  $tp = e107::getParser();
  
  $DBRES = array();
  if(!$TDTA || count($TDTA) == 0){$DBRES[0] = array('error'=>EST_ERR_DATANOTARRAY);}
  else{
    $TBLIST = $sql->tables();
    $CTWORK = count($TDTA);
    foreach($TDTA as $TK=>$TBLDTA){
      $TBL = trim($TBLDTA['tbl']);
      $FLDLST = $sql->db_FieldList($TBL);
      $KEYF = trim($TBLDTA['key']);
      $FDTA = $TBLDTA['fdta'];
      $DEL = intval($TBLDTA['del']);
      
      $DBRES['sendnew'] = array('tbl'=>$TBL,'key'=>$KEYF,'fdta'=>$FDTA,'flds'=>$FLDLST);
        
      $CHKMEDIA = 0;
      if(!in_array($TBL,$TBLIST)){$DBRES[$TK] = array('error'=>EST_ERR_TABLE1);}
      else{
        if(!in_array($KEYF,$FLDLST)){
          $DBRES[$TK] = array('error'=>EST_ERR_KEYFIELD1,'tbl'=>$TBL,'tblkey'=>$KEYF,'tblfld'=>$FLDLST);
          }
        else{
          if(is_array($FDTA)){
            //NEED TO ACCOUNT FOR MULTIPLE ROWS?
            if(count($FDTA) > 0 && count($FDTA) == count($FLDLST)){
              $kf=[];
              $kv=[];
              $DBIDX = 'x!x';
              $DBTRY = $KEYF.':';
              foreach($FDTA as $fk=>$fv){
                if(is_array($fv)){
                  $kf[$fk] = array_keys($fv)[0];
                  $kv[$fk] = $tp->toDB(array_values($fv)[0]);
                  if($kf[$fk] === $KEYF){$DBIDX = $kv[$fk];}
                  $DBTRY .= ' ['.$kf[$fk].'='.$kv[$fk].']';
                  }
                else{
                  $kf[$fk] = $fk;
                  $kv[$fk] = $tp->toDB($fv);
                  if($fk === $KEYF){$DBIDX = intval($fv);}
                  $DBTRY .= ' '.$fk.'='.$fv.',';
                  }
                }
              
              if($DBIDX == 'x!x'){
                $DBRES[$TK] = array('error'=>EST_ERR_KEYFIELD2,'try'=>$DBTRY,'tbl'=>$TBL,'tblkey'=>$KEYF,'tblfld'=>$FLDLST,'fdta'=>$FDTA,'kf'=>$kf,'kv'=>$kv);
                }
              else{
                $QRY2 = array();
                $NEWID = 0;
                if($DBIDX === '' || intval($DBIDX) === 0){
                  $DBMDE = 'new';
                  foreach($kf as $dx=>$dk){$QRY2[$dk] = ($dk == $KEYF ? 0 : $kv[$dx]);}
    		          $NEWID = $sql->insert($TBL,$QRY2);
                  if($NEWID){
                    $DBIDX = $NEWID;
                    $kv[0] = $NEWID;
                    }
                  }
                else if($DBIDX > 0){
                  $EXTRAWRK = array();
                  if(intval($DEL) == -1){
                    $DBMDE = 'Delete from '.$TBL.' IDX #'.$DBIDX;
                    if($sql->delete($TBL, $KEYF.' = '.$DBIDX.' LIMIT 1')){
                      $UPRES .= ' ...'.EST_GEN_DELETED.' ';
                      if($TBL == "estate_spaces"){$EXTRAWRK['spaces'] = estBatchDelSpaces($PROPID,$DBIDX);}
                      }
                    else{$UPRES = ' ...'.EST_GEN_NOT.' '.EST_GEN_DELETED;}
                    }
                  else{
                    $DBMDE = 'update';
                    foreach($kf as $dx=>$dk){
                      if($dk !== $KEYF){$QRY2[$dk] = $kv[$dx];}
                      }
                    $QRY2['WHERE'] = $KEYF.'="'.$DBIDX.'" LIMIT 1';
                    
                    if($TBL == "estate_agents"){
                      $ORPH = estChkOrphanFiles('../../media/agent','agent-'.intval($DBIDX),(intval($QRY2['agent_imgsrc']) == 1 ? $QRY2['agent_image'] : ''));
                      foreach($ORPH as $mk=>$mv){$EXTRAWRK['orphanchk'][$mk] = $mv;}
                      }
                    if($TBL == "estate_agencies"){
                      $ORPH = estChkOrphanFiles('../../media/agency','agency-'.intval($DBIDX),(intval($QRY2['agency_imgsrc']) == 1 ? $QRY2['agency_image'] : ''));
                      foreach($ORPH as $mk=>$mv){$EXTRAWRK['orphanchk'][$mk] = $mv;}
                      }
                    
                    if($UPRES = $sql->update($TBL,$QRY2)){
                      if($TBL == "estate_spaces"){
                        $mik = array_search('space_name',$kf);
                        if($mik !== false){
                          if($sql->update("estate_media","media_name='".$kv[$mik]."' WHERE media_lev='2' AND media_levidx='".intval($DBIDX)."'")){
                            $CHKMEDIA = array("media_name"=>$kv[$mik],"lev"=>2,"key"=>intval($DBIDX));
                            }
                          }
                        }
                      }
                    }
                  }
                
                $DBRES[$TK] = array('dbmde'=>$DBMDE,'qry2'=>$QRY2,'dbidx'=>$DBIDX,'upres'=>$UPRES,'del'=>$DEL,'newid'=>$NEWID,'fdta'=>$FDTA,'kf'=>$kf,'kv'=>$kv,'chkmedia'=>$CHKMEDIA,'extrawork'=>$EXTRAWRK,'try'=>$DBTRY);
                $DBERR[$TK] = $sql->getLastErrorText();
                if($DBERR[$TK]){$DBRES[$TK]['error'] = $DBERR[$TK];}
                else{
                  if($TK + 1 == $CTWORK){
                    $DBRES[$TK]['alldta'] = estGetAllDta($PROPID);
                    }
                  }
                }
              }
            else{$DBRES[$TK] = array('error'=>EST_ERR_TABLE2,'line'=>'1063','fdta'=>$FDTA);}
            }
          else{$DBRES[$TK] = array('error'=>EST_ERR_DATANOTARRAY,'line'=>'1065','fdta'=>$FDTA);}
          }
        }
      }
    }
  return $DBRES;
  }


//select($table, $fields = '*', $arg = '', $noWhere = false, $debug = false, $log_type = '', $log_remark = '');


function estUDMediaDb($media_idx,$media_propidx,$media_lev,$media_levidx,$media_levord,$media_galord,$media_asp,$media_type,$media_thm,$media_full,$media_name){
  $sql = e107::getDB();
  $tp = e107::getParser();
  $RES = array();
  $sql->update("estate_media","media_asp='$media_asp', media_type='$media_type', media_thm='$media_thm', media_full='$media_full', media_name='$media_name' WHERE media_idx='$media_idx' LIMIT 1");
  $RES['dberr'] = $sql->getLastErrorText();
  $RES['fdta']['media_idx'] = intval($media_idx);
  $RES['fdta']['media_propidx'] = intval($media_propidx);
  $RES['fdta']['media_lev'] = intval($media_lev);
  $RES['fdta']['media_levidx'] = intval($media_levidx);
  $RES['fdta']['media_levord'] = intval($media_levord);
  $RES['fdta']['media_galord'] = intval($media_galord);
  $RES['fdta']['media_asp'] = $tp->toFORM($media_asp);
  $RES['fdta']['media_type'] = intval($media_type);
  $RES['fdta']['media_thm'] = $tp->toFORM($media_thm);
  $RES['fdta']['media_full'] = $tp->toFORM($media_full);
  $RES['fdta']['media_name'] = $tp->toHTML($media_name);
  return $RES;
  unset($RES);
  }



function estGetAllDta($PROPID){
  global $estateCore;
  $sql = e107::getDB();
  $tp = e107::getParser();
  $PROPID = intval($PROPID);
  
  $TBLS = estTablStruct();
  
  //estGetContDta estate_contacts
  $ESTTBL = array();
  
  if($PROPID > 0){$ESTTBL['estate_properties']['dta'] = $sql->retrieve('estate_properties', '*','prop_idx="'.$PROPID.'"',true);}
  else{
    if($_GET['pragtid']){$ESTTBL['estate_properties']['dta'] = $sql->retrieve('estate_properties', '*','prop_agent="'.intval($_GET['pragtid']).'"',true);}
    elseif($_POST['pragtid']){$ESTTBL['estate_properties']['dta'] = $sql->retrieve('estate_properties', '*','prop_agent="'.intval($_POST['pragtid']).'"',true);}
    elseif($_GET['pragyid']){$ESTTBL['estate_properties']['dta'] = $sql->retrieve('estate_properties', '*','prop_agency="'.intval($_GET['pragyid']).'"',true);}
    elseif($_POST['pragyid']){$ESTTBL['estate_properties']['dta'] = $sql->retrieve('estate_properties', '*','prop_agency="'.intval($_POST['pragyid']).'"',true);}
    else{$ESTTBL['estate_properties']['dta'] = array();}
    }
  
  $ESTTBL['estate_user']['dta'] = $estateCore->estGetAllUsers();
  $ESTTBL['estate_agents']['dta'] = $estateCore->estGetAllAgents();
  $ESTTBL['estate_agencies']['dta'] = $estateCore->estGetAllAgencies();
  //$ESTTBL['estate_contacts']['dta'] = $estateCore->estGetCompContacts();
  
  //$ESTTBL['estate_agencyfull']['dta'] = $estateCore->estGetAgencyFull();
  
  
  //$tp->toVideo($file, $parm = array())
  
  //$STATEID = intval($ESTTBL['estate_properties']['dta'][0]['prop_state']);
  //$COUNTYID = intval($ESTTBL['estate_properties']['dta'][0]['prop_county']);
  //$CITYID = intval($ESTTBL['estate_properties']['dta'][0]['prop_city']);
  $SUBDID = intval($ESTTBL['estate_properties']['dta'][0]['prop_subdiv']);
  
   //media_lev = 0=subdiv, 1=property, 2=spaces, 3=city space, 4=subdiv space
  $ESTTABDTA = array(
    'estate_grouplist'=>'grouplist_propidx="'.$PROPID.'"',
    'estate_featurelist'=>'featurelist_propidx="'.$PROPID.'"',
    'estate_media'=>'media_propidx="'.$PROPID.'" OR (media_propidx="0" AND media_lev="0" AND media_levidx="'.$SUBDID.'")',
    'estate_spaces'=>'space_lev="1" AND space_levidx="'.$PROPID.'"',
    'estate_events'=>'event_idx>"0" ORDER BY event_start ASC' //event_propidx event_agt
    );
  
  $ESTTBL['estate_properties']['dta'][0]['prop_hours'] = e107::unserialize($ESTTBL['estate_properties']['dta'][0]['prop_hours']);
      
  if(intval($ESTTBL['estate_properties']['dta'][0]['prop_agency']) > 0){
    
    }
  if(intval($ESTTBL['estate_properties']['dta'][0]['prop_agent']) > 0){
    
    }
  
  //if($STATEID > 0){$ESTTABDTA['estate_county'] = 'cnty_state="'.$STATEID.'"';}
  //if($COUNTYID > 0){$ESTTABDTA['estate_city'] = 'city_county="'.$COUNTYID.'"';}
  //if($CITYID > 0){$ESTTABDTA['estate_subdiv'] = 'subd_city="'.$CITYID.'"';}
  
  foreach($TBLS as $tbl=>$v){
    $ESTTBL[$tbl]['flds'] = $sql->db_FieldList($tbl);
    if(!$ESTTBL[$tbl]['dta']){$ESTTBL[$tbl]['dta'] = array();}
    if(count($ESTTBL[$tbl]['dta']) == 0){
      if($ESTTABDTA[$tbl]){$ESTTBL[$tbl]['dta'] = ($sql->count($tbl) > 0 ? $sql->retrieve($tbl, '*',$ESTTABDTA[$tbl],true) : array());}
      else{$ESTTBL[$tbl]['dta'] = ($sql->count($tbl) > 0 ? $sql->retrieve($tbl, '*','',true) : array());}
      }
      
    
    $ESTTBL[$tbl]['form'] = estTablForm($TBLS[$tbl],$ESTTBL[$tbl]['flds']);
    }
  
  $i = 0;
  
  foreach($GLOBALS['EST_COUNTRIES'] as $ck=>$cv){
    $ESTTBL['estate_nations']['dta'][$i]['nation_idx'] = $ck;
    $ESTTBL['estate_nations']['dta'][$i]['nation_name'] = $cv;
    $i++;
    } 
  
  $i = 0;
  $timeZones = systemTimeZones();
  foreach($timeZones as $tzk=>$tzv){
    $ESTTBL['estate_timezone']['dta'][$i]['tz_idx'] = $tzk;
    $ESTTBL['estate_timezone']['dta'][$i]['tz_name'] = $tzv;
    $i++;
    }
    
  
  $ESTTBL['estate_sects'] = estSects();
  
  unset($PROPID,$ESTTABDTA,$TBLS,$FDTA,$COUNTRIES);
  return array('tbls'=>$ESTTBL);
  }





function estJStext(){
  $DIMUOPT = array();
  foreach($GLOBALS['EST_DIMUNITS'] as $dk=>$dv){$DIMUOPT[$dk] = explode(',',$dv)[0];}
  $DIMU = explode(',',$GLOBALS['EST_DIMUNITS'][0]); // <- find way to set default prop measurement units - prefs?
  
  return array(
    'add1'=>EST_GEN_ADD1,
    'addevent'=>EST_PROP_MSG_ADDEVENTS,
    'addnewspace'=>EST_GEN_ADDNEWSPACE,
    'addrnotfound'=>EST_MSG_ADDRNOTFOUND,
    'addrtooshort'=>EST_MSG_ADDRTOOSHORT,
    'addspaces'=>EST_PROP_MSG_ADDSPACES,
    'addmedia'=>EST_PROP_MSG_ADDMEDIA,
    'admin'=>EST_GEN_ADMIN,
    'agency'=>EST_GEN_AGENCY,
    'agent'=>EST_GEN_AGENT,
    'agtpropic'=>EST_AGT_PROPIC,
    'agtupdta'=>EST_AGT_UPAGTDTA,
    'agtuspropic'=>EST_AGT_USEPROPIC,
    'agtclrdta'=>EST_AGT_CLEARDATA,
    'agentlogname'=>EST_GEN_AGENTLOGNAME,
    'all'=>EST_GEN_ALL,
    'alternate'=>EST_GEN_ALTERNATE,
    'append'=>EST_GEN_APPEND,
    'areyousure'=>LAN_JSCONFIRM,
    'assign'=>EST_GEN_ASSIGN,
    'bath'=>EST_GEN_BATH,
    'baths'=>EST_GEN_BATHS,
    'bed'=>EST_GEN_BED,
    'auto'=>EST_GEN_AUTO,
    'cancel'=>LAN_CANCEL,
    'cancelremove'=>EST_GEN_CANCELREM,
    'caption'=>EST_GEN_CAPTION,
    'category'=>LAN_CATEGORY,
    'categories'=>LAN_CATEGORIES,
    'changeimgsrc'=>EST_GEN_CHANGEIMGSRC,
    'choosethm'=>EST_GEN_CHOOSETHM,
    'city'=>EST_PROP_CITY,
    'clkcust'=>EST_GEN_CLKCUSTOM,
    'clksave'=>EST_GEN_CLKSAVEFOROPTS,
    'create'=>EST_GEN_CREATE,
    'createagnt'=>EST_GEN_CREATEAGENT,
    'community'=>EST_GEN_COMMUNITY,
    'contact'=>EST_GEN_CONTACT,
    'contacts'=>EST_GEN_CONTACTS,
    'crop'=>EST_GEN_CROP,
    'cropbtns'=>array(EST_IMG_MOVEL,EST_IMG_MOVER,EST_IMG_MOVEU,EST_IMG_MOVED,EST_IMG_ZOOMO,EST_IMG_ZOOMI,EST_IMG_ROTL,EST_IMG_ROTR,EST_IMG_FLIPH,EST_IMG_FLIPV,EST_IMG_RESETC,EST_IMG_CROP),
    'custom'=>EST_GEN_CUSTOM,
    'data'=>EST_GEN_DATA,
    'datasource'=>EST_GEN_DATASOURCE,
    'deletes'=>LAN_DELETE,
    'description'=>LAN_DESCRIPTION,
    'details'=>EST_GEN_DETAILS,
    'dimensions'=>EST_GEN_DIMENSIONS,
    'dimu0'=>$DIMU[0],
    'dimu1'=>$DIMU[1],
    'donotreload'=>EST_GEN_DONOTRELOAD,
    'drag2reord'=>EST_GEN_DRAGTOREORDER,
    'dragto'=>EST_GEN_DRAGTO,
    'edit'=>EST_GEN_EDIT,
    'enabdisab'=>EST_GEN_ENABLEDIS,
    'error1'=>LAN_ERROR,
    'error500'=>EST_ERR_500,
    'event'=>EST_GEN_EVENT,
    'events'=>EST_GEN_EVENTS,
    'feature'=>EST_GEN_FEATURE,
    'features'=>EST_GEN_FEATURES,
    'featurenobelong'=>EST_GEN_FEATURE.' '.EST_GEN_NOTBELONGTO.' '.LAN_CATEGORY,
    'field'=>EST_GEN_FIELD,
    'fieldrequired1'=>EST_GEN_FLDREQ1,
    'filesize'=>EST_GEN_FILE.' '.EST_GEN_SIZE,
    'first'=>EST_GEN_FIRST,
    'form'=>EST_GEN_FORM,
    'fracts'=>array('¼','½','¾'),
    'full'=>EST_GEN_FULL,
    'group1'=>EST_GEN_GROUP,
    'half'=>EST_GEN_HALF,
    'hoaappr1'=>EST_GEN_HOAAPPR1,
    'hoaupdta0'=>EST_PROP_HOAUPDTA0,
    'hoaupdta1'=>EST_PROP_HOAUPDTA1,
    'hoaupdta2'=>EST_PROP_HOAUPDTA2,
    'hoaupdta3'=>EST_PROP_HOAUPDTA3,
    'hoaupdta4'=>EST_PROP_HOAUPDTA4,
    'image'=>EST_GEN_IMAGE,
    'infochanged'=>EST_GEN_INFOCHANGED,
    'item'=>EST_GEN_ITEM,
    'javafail'=>EST_ERR_JAVAFAIL,
    'layout'=>EST_GEN_LAYOUT,
    'listing'=>EST_GEN_LISTING,
    'listings'=>EST_GEN_LISTINGS,
    'listype'=>EST_PROP_LISTYPE,
    'location'=>EST_LOCATION,
    'logo'=>EST_GEN_LOGO,
    'lostresult'=>EST_ERR_LOSTRESULT,
    'main'=>EST_GEN_MAIN,
    'makeadmin'=>EST_TT_ADMPERMIS2,
    'map'=>EST_GEN_MAP,
    'missing'=>EST_GEN_MISSING,
    'media'=>EST_MEDIA,
    'mediaavail'=>EST_MEDIAAVAILABLE,
    'mediainuse'=>EST_MEDIAINUSE,
    'mediareplace'=>EST_MEDIAREPLACE,
    'myagentprof'=>EST_MYAGENTPROFILE,
    'name'=>LAN_NAME,
    'nauntilspaces'=>EST_PROP_MSG_NAUNTILSPACES,
    'needsave'=>EST_PROP_MSG_NEEDSAVE,
    'new1'=>EST_NEW,
    'new2'=>EST_SAVEANDNEW,
    'newagt'=>EST_GEN_NEWAGENT,
    'nochanges2'=>EST_GEN_DBNOCHANGES,
    'nochangeadmin'=>EST_GEN_NOCHANGEADMIN,
    'none'=>EST_GEN_NONE,
    'nomap'=>EST_ERR_NOMAP,
    'nocontdta'=>EST_ERR_NOCONDATA,
    'nocontype'=>EST_ERR_NOCONTYPE,
    'notavail2'=>EST_GEN_NOTAVAIL2,
    'notdefined'=>EST_GEN_NOTDEFINED,
    'nothing2change'=>EST_GEN_NOTHING2CHANGE,
    'option'=>EST_OPTION,
    'options'=>EST_OPTIONS,
    'optionlist'=>EST_GEN_OPTLIST,
    'other'=>EST_GEN_OTHER,
    'please'=>EST_GEN_PLEASE,
    'posithnt'=>EST_GEN_POSITHNT,
    'position'=>EST_GEN_POSITION,
    'poweredby'=>EST_GEN_POWEREDBY,
    'property'=>EST_GEN_PROPERTY,
    'propid'=>$PROPID,
    'reorder'=>EST_GEN_REORDER,
    'reassign'=>EST_GEN_REASSIGN,
    'resetmap'=>EST_PROP_RESETMAP,
    'required'=>LAN_REQUIRED,
    'save'=>EST_SAVE,
    'saves'=>EST_SAVES,
    'save2'=>EST_SAVECLOSE,
    'savefirst'=>EST_GEN_SAVEFIRST,
    'section'=>EST_GEN_SECTION,
    'select1'=>EST_GEN_SELECT,
    'sendingemails'=>EST_GEN_SENDINGEMAILS,
    'sort'=>EST_GEN_SORT,
    'space'=>EST_GEN_SPACE,
    'spaces'=>EST_GEN_SPACES,
    'spacecatzero'=>EST_SPCAT_ZERO,
    'startcrop'=>EST_IMG_CROPSTART,
    'subdiv'=>EST_GEN_SUBDIVISION,
    'table'=>EST_PROP_MSG_TABLE,
    'templnoopt'=>EST_PREF_TEMPLATE_NOORD,
    'templvieword'=>EST_PREF_TEMPLATE_VIEWORD,
    'thisclrfrm'=>EST_PROP_THISCLRFRM,
    'tomods'=>EST_GEN_TOMODS,
    'unk'=>EST_GEN_UNK,
    'sqr'=>EST_GEN_SQR,
    'updated'=>EST_UPDATED,
    'updatethis'=>EST_GEN_UPDATETHIS,
    'upload'=>EST_UPLOAD,
    'view'=>EST_GEN_VIEW,
    'views'=>EST_GEN_VIEWS,
    'website'=>EST_GEN_WEBSITE,
    'xbyy'=>EST_XBYY,
    'zone'=>EST_GEN_ZONE,
    'zoning'=>EST_GEN_ZONING,
    'zoomcrop'=>EST_MEDIA_ZOOMCROP,
    );
  }



function estJSkeys(){
  global $estateCore;
  $DIMUOPT = array();
  foreach($GLOBALS['EST_DIMUNITS'] as $dk=>$dv){$DIMUOPT[$dk] = explode(',',$dv)[0];}
  $DIMU = explode(',',$GLOBALS['EST_DIMUNITS'][0]); // <- find way to set default prop measurement units - prefs?
  
  return array(
    'avatarlab'=>array(EST_AGT_PROPIC,EST_GEN_CUSTOM,EST_GEN_USER.' '.EST_AGT_PROPIC), //LAN_NONE,
    'contkeys'=>$estateCore->estGetContKeys(),
    'contabs'=>$GLOBALS['EST_CONTACTBLS'],
    'cursymb'=>EST_CURSYMB,
    'dim1u'=>$GLOBALS['EST_DIM1UNITS'],
    'dim2u'=>$GLOBALS['EST_DIM2UNITS'],
    'dimuopt'=>$DIMUOPT,
    'eventkeys'=>$GLOBALS['EST_EVENTKEYS'],
    'hoaland'=>$GLOBALS['EST_HOALAND'],
    'hoafrq'=>$GLOBALS['EST_HOAFREQ'],
    'hoareq'=>$GLOBALS['EST_HOAREQD'],
    'leasefrq'=>$GLOBALS['EST_LEASEFREQ'],
    'levmap'=>EST_LEVMAP,
    'plugid'=>EST_PLUGID,
    'popform'=>array(
      'estate_subdiv'=>array(
        'tabs'=>array(
          0=>array('li'=>EST_GEN_DETAILS),
          1=>array('li'=>EST_GEN_HOA),
          2=>array('li'=>EST_GEN_FEATURES),
          3=>array('li'=>EST_GEN_GALLERY)
          )
        )
      )
    );
  }

//'subdivtypes'=>EST_GEN_SUBDIVTYPE


class filsys{
  public static function deleteDir($dirPath) {
    if (!getperms('P')){$RES['error'] = "Not Allowed";}
    else{
      if(!is_dir($dirPath)) {throw new InvalidArgumentException("$dirPath must be a directory");}
      if(substr($dirPath, strlen($dirPath) - 1, 1) != '/') {$dirPath .= '/';}
      $files = glob($dirPath . '*', GLOB_MARK);
      foreach($files as $file){
        //if(is_dir($file)){self::deleteDir($file);}
        //else{unlink($file);}
        }
      //rmdir($dirPath);
      }
    }
  }
  

/*
$ret['status'] = e_QUERY;
//e107::getSession()->reset();
$ret['e_token'] = e107::getSession()->getFormToken();
if($DSPL == 'pop'){
  $e107_popup = 1;
  include_once(HEADERF);
  
  $text = e_QUERY;
  $ns->tablerender($FETCH,$text);
  include_once(FOOTERF);
  exit;
  }
*/
?>
