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
if(!$err && intval($qs[1]) > 0){
  if($prop = $sql->retrieve("estate_properties","*","prop_idx='".intval($qs[1])."' LIMIT 1",true)){
    extract($prop[0]);
    if(intval($prop_agent) > 0){
      if($agt = $sql->retrieve("estate_agents","*","agent_idx='".intval($prop_agent)."' LIMIT 1",true)){
        extract($agt[0]);
        if($agy = $sql->retrieve("estate_agencies","*","agency_idx='".intval($prop_agency)."' LIMIT 1",true)){extract($agy[0]);}
        if($usr = $sql->retrieve("user",$USRSEL,"user_id='".intval($agent_uid)."' LIMIT 1",true)){extract($usr[0]);}
        }
      }
    else{
      if($usr = $sql->retrieve("user",$USRSEL,"user_id='".intval($prop_uidcreate)."' LIMIT 1",true)){extract($usr[0]);}
      }
    }
  }



// If Listing Loaded, Check current Estate User permissions against Property Listing Owner
if(intval($prop_idx) > 0){
  if(EST_USERPERM == 0 && intval($prop_uidcreate) !== USERID){
    $err[1] = EST_ERR_NOTYOURPROP;
    $err[2] = EST_ERR_NOTYOURPROP1.' '.$tp->toHTML(USERNAME).' ('.$tp->toHTML(USEREMAIL).')';
    }
  if(EST_USERPERM == 1 && intval($prop_agent) !== EST_AGENTID){
    $err[1] = EST_ERR_NOTYOURPROP;
    $err[2] = EST_ERR_NOTYOURPROP1.' '.$tp->toHTML(AGENT_NAME).'  of '.$tp->toHTML(AGENCY_NAME).')';
    }
  if(EST_USERPERM == 2){
    if(intval($prop_agency) == EST_AGENCYID){
      if(intval($prop_agent) !== EST_AGENTID){
        e107::getMessage()->addInfo(EST_GEN_OTHERPROP1.' '.($prop_agent > 0 ? 'Agent '.$tp->toHTML($agent_name) : 'User '.$tp->toHTML(USERNAME) ).'.');
        }
      }
    else{
      if(intval($prop_agency) == 0 || $prop_agent == 0){
        $err[1] = EST_GEN_OTHERPROP2.' '.$tp->toHTML($user_name);
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
    //$MEDIA = $sql->retrieve("estate_media","*","media_propidx='".intval($prop_idx)."'",true);

    $ESTDTA = estGetSpaces($prop_idx);
    $MEDIA = $ESTDTA[0];
    $SPACES = $ESTDTA[1];
    
    //estate_featurelist
    }
  }
else{
  if($qs[0] !== 'new'){e107::redirect(e_SELF."?new.0.0");}
  $prop_agency = intval(EST_AGENCYID);
  $prop_agent = intval(EST_AGENTID);
  $prop_uidcreate = USERID;
  $prop_uidupdate = USERID;
  if($prop_agent > 0){
    if($agt = $sql->retrieve("estate_agents","*","agent_idx='".intval($prop_agent)."' LIMIT 1",true)){
      extract($agt[0]);
      if($agy = $sql->retrieve("estate_agencies","*","agency_idx='".intval($prop_agency)."' LIMIT 1",true)){extract($agy[0]);}
      if($usr = $sql->retrieve("user",$USRSEL,"user_id='".intval($agent_uid)."' LIMIT 1",true)){extract($usr[0]);}
      }
    }
  }


$pretext = '
  <h2>THIS FORM IS IN DEVELOPMENT</h2>
  <div>EST_USERPERM: '.EST_USERPERM.'</div>
  <div>public_act: '.intval($EST_PREF['public_act']).'</div>
  <div>You Are: '.$tp->toHTML(USERNAME).' ('.$tp->toHTML(USEREMAIL).') ['.$tp->toHTML(AGENT_NAME).' of '.EST_AGENCYID.' '.$tp->toHTML(AGENCY_NAME).']</div>
  <div>Property #'.$prop_idx.': '.$tp->toHTML($prop_name).'</div>
  <div>Agent & Agency: '.$prop_agent.' '.$tp->toHTML($agent_name).' of '.$tp->toHTML($agency_name).' (#'.$prop_agency.')</div>
  <div>Created By UID: '.$prop_uidcreate.' (Updated By UID '.$prop_uidupdate.')</div>
  <div>Street: '.$prop_addr1.'</div>';


if($err){
  foreach($err as $k=>$v){e107::getMessage()->addError($v);}
  require_once(HEADERF);
  echo $pretext;
  require_once(FOOTERF);
  exit;
  }



define("EST_OAEDIT",true);

require_once(e_HANDLER."form_handler.php");
e107::js('estate','js/oa.js', 'jquery');
$OACLASS = ' class="estOA"';






include_once('qry.php');


if(intval($prop_idx) > 0){
  $nsHead = '<div id="estMiniNav"><a href="'.e_SELF.'?view.'.$prop_idx.'" title="'.EST_GEN_VIEWLISTING.'"><i class="fa fa-eye"></i></a>';
  if(getperms('P')){
    $nsHead .= '<a class="noMobile" href="'.EST_PTH_ADMIN.'?action=edit&id='.intval($prop_idx).'" title="'.EST_GEN_FULLEDIT.'"><i class="fa fa-pencil-square-o"></i></a>';
    }
  $nsHead .= '</div>'.EST_GEN_EDIT.': '.$tp->toHTML($prop_name);
  }
else{
  $nsHead = EST_GEN_NEW.' '.EST_GEN_LISTING;
  }





$tmpl = e107::getTemplate('estate');
$sc = e107::getScBatch('estate',true);


$sc->setVars($prop[0]);


e107::js('inline','var estMapPins = '.$tp->parseTemplate($tmpl['pins'], false, $sc).'; ', 'jquery',2);



require_once(HEADERF);

$estText = $pretext;

$estText .= $tp->parseTemplate($tmpl['view']['sum'], false, $sc);

$estText .= $tp->parseTemplate($tmpl['view']['map'], false, $sc);

      
$estText .= '
<div id="estJSpth"'.$OACLASS.' data-pth="'.EST_PATHABS.'"></div>
<div id="estMobTst"></div>
<div id="estMiniNav"></div>';



$ns->tablerender($nsHead,$estText,'estEditProp');
unset($nsHead,$estText);





if(count($SPACES) > 0){
  usort($SPACES, "spgrpsort");
  $text .= '
  <div class="estOAEditBlock">';
  foreach($SPACES as $k=>$v){
    $text .= '
    <div style="order:'.$v['ord'].'">
    <h4>'.$tp->toHTML($v['n']).'</h4>';
    foreach($v['sp'] as $sok=>$sov){
      ksort($sov);
      foreach($sov as $sk=>$sv){
        //$estkeyid = $this->spacesKeyId($v,$sok,$sk);
        //$SPACETXT = $this->spacesTxt($sv);
        $text .= '
        <div class="estViewSpaceBtn">
          <div class="estSpTtl">'.$tp->toHTML($sv['n']).'</div>
          <div class="estImgSlide'.(count($sv['m']) > 0 ? ' '.$estkeyid.'img' : '').'" data-ict="'.count($sv['m']).'"></div>
          <div class="estViewSpTxt">'.$sv['d'].'</div>
          <div class="">';
          foreach($sv['m'] as $mk=>$mv){
            $text .= '
            <div>'.$mv['t'].'
            </div>';
            }
          $text .= '
          </div>
        </div>';
        }
      }
    $text .= '
    </div>';
    }
  $text .= '
  </div>';
  $ns->tablerender(EST_GEN_SPACES,$text,'estEditSpaces');
  unset($text,$SPACES);
  }




unset($USRSEL);
require_once(FOOTERF);
exit;

?>