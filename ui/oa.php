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
    //extract($prop[0]);
    
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
  $DTA['prop']['prop_agency'] = intval(EST_AGENCYID);
  $DTA['prop']['prop_agent'] = intval(EST_AGENTID);
  $DTA['prop']['prop_uidcreate'] = USERID;
  $DTA['prop']['prop_uidupdate'] = USERID;
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



e107::css('url',e_PLUGIN.'estate/css/oa.css');
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



require_once(HEADERF);

$OATXT = $pretext;

$TABLSTRUCT = estTablStruct();


$BLKS = array(
  array('h3'=>EST_GEN_LISTING),
  array('h3'=>EST_GEN_ADDRESS),
  array('h3'=>EST_GEN_COMMUNITY),
  array('h3'=>EST_GEN_SPACES),
  array('h3'=>EST_GEN_DETAILS),
  array('h3'=>EST_GEN_GALLERY),
  array('h3'=>EST_GEN_SCHEDULING)
  );


$tbls = array(); 
$tbls[0] = 'content 0';
$tbls[1] = $tp->parseTemplate($tmpl['edit']['map'], false, $sc);










foreach($TABLSTRUCT['estate_properties'] as $pk=>$pv){
  $TABKEY = 0;
  if($pk === 'prop_hours'){
    $TABKEY = 6;
    $estateCore = new estateCore;
    if(!$DTA['prop'][$pk] || count($DTA['prop'][$pk]) == 0){$DTA['prop'][$pk] = $GLOBALS['EST_PREF']['sched_pub_times'];}
    $dta = array('deftime'=>array('n'=>'prop_hours','v'=>$DTA['prop'][$pk],'l'=>EST_GEN_AVAILABLE));//,'h'=>array(EST_PREF_DEFHRSHINT0,EST_PREF_DEFHRSHINT1)
    /*
    $BLKS[6]['tabl']['a'] = ' id="propHrsTable"';
    $BLKS[6]['tabl']['h'][0]['a'] = ' id=nnnn';
    $BLKS[6]['tabl']['h'][0]['td'][0]['a'] = ' id="nva"'; 
    $BLKS[6]['tabl']['h'][0]['td'][0]['t'] = 'text'; 
    $BLKS[6]['tabl']['h'][0]['td'][0]['a'] = 'id="nvb"'; 
    $BLKS[6]['tabl']['h'][0]['td'][1]['t'] = 'input'; 
    */
    $BLKS[6]['tabl']['b'][0]['td'][0]['a'] = ''; 
    $BLKS[6]['tabl']['b'][0]['td'][0]['t'] = 'text'; 
    $BLKS[6]['tabl']['b'][0]['td'][1]['t'] = $estateCore->getCalTbl('start');
    $BLKS[6]['tabl']['b'][0]['td'][1]['t'] .= $estateCore->getCalTbl('head');
    $BLKS[6]['tabl']['b'][0]['td'][1]['t'] .= '<tbody>';
    $BLKS[6]['tabl']['b'][0]['td'][1]['t'] .= $estateCore->getCalTbl('tr',$dta);
    $BLKS[6]['tabl']['b'][0]['td'][1]['t'] .= '</tbody>';
    $BLKS[6]['tabl']['b'][0]['td'][1]['t'] .= $estateCore->getCalTbl('end');
    
    }
  else{
    if($pv['type'] == 'idx' || $pv['type'] == 'int' ||  $pv['str'] == 'int'){
      $FVALUE = intval($DTA['prop'][$pk]);
      }
    else{
      $FVALUE = $tp->toFORM($DTA['prop'][$pk]);
      }
    
    if(!$pv['type'] || $pv['type'] == 'int' || $pv['type'] == 'hidden'){
      $HIDDEN .= '<input type="hidden" name="'.$pk.'" value="'.$FVALUE.'" />';
      }
    else{
      
      }
    }
    
  
  
  }


$OATXT .= $HIDDEN;

foreach($BLKS as $BI=>$BLK){
  $OATXT .= '<div class="estOABlock">';
  $OATXT .= '<h3><div>'.$tp->toHTML($BLK['h3']).'</div></h3>';
  $OATXT .= '<div class="estOATabCont">';
  
  if($BLK['tabl']){
    $OATXT .= '<table'.$BLK['tabl']['a'].'>';
    foreach($BLK['tabl']['b'][0]['td'] as $cgi){
      $OATXT .= '<colgroup></colgroup>';
      }
    
    
    if($BLK['tabl']['h']){
      $OATXT .= '<thead>';
      foreach($BLK['tabl']['h'] as $ri=>$tr){
        $OATXT .= '<tr'.$tr['a'].'>';
        foreach($tr['td'] as $ci=>$td){
          $OATXT .= '<td'.$td['a'].'>';
          $OATXT .= $td['t'];
          $OATXT .= '</td>';
          }
        $OATXT .= '</tr>';
        }
      $OATXT .= '</thead>';
      }
    
    if($BLK['tabl']['b']){
      $OATXT .= '<tbody>';
      foreach($BLK['tabl']['b'] as $ri=>$tr){
        $OATXT .= '<tr'.$tr['a'].'>';
        foreach($tr['td'] as $ci=>$td){
          $OATXT .= '<td'.$td['a'].'>';
          $OATXT .= $td['t'];
          $OATXT .= '</td>';
          }
        $OATXT .= '</tr>';
        }
      $OATXT .= '</tbody>';
      }
    
    
    
    $OATXT .= '</table>';
    }
  else{
    //$OATXT .= $tbls[$BI];
    }
  $OATXT .= '</div></div>';
  }



//$OATXT .= $tp->parseTemplate($tmpl['view']['sum'], false, $sc);


      
$OATXT .= '
<div id="estJSpth" data-pth="'.EST_PATHABS.'"></div>
<div id="estMobTst"></div>
<div id="estMiniNav"></div>';



$ns->tablerender($nsHead,$OATXT,'estEditProp');
unset($nsHead,$OATXT);





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