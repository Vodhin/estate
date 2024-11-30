<?php
if(!defined('e107_INIT')){require_once(__DIR__ . '/../../class2.php');}

e107::lan('estate',true,true);
$ns = e107::getRender();
$tp = e107::getParser();

$EST_PREF = e107::pref('estate');

e107::css('url',e_PLUGIN.'estate/css/listings.css');
e107::css('url',e_PLUGIN.'estate/css/viewtop.css');
e107::css('url',e_PLUGIN.'estate/css/spaces.css');



$e107 = e107::getInstance();
if(!$e107->isInstalled('estate')){
  require_once(HEADERF);
  $ns->tablerender(EST_GEN_LISTINGS,'<div class="WD100 TAC FWB">'.(ADMIN ? EST_GEN_PLUGNOTINST : EST_GEN_PLUGUNAVAIL).'</div>', 'estate-listings');
	require_once(FOOTERF);
	exit;
  }


if(intval($EST_PREF['adminonly']) == 1){
  if(ADMIN){$estText = '<div class="WD100 TAC FWB">'.EST_GEN_PLUGADMINONLY.'</div>';}
  else{
    require_once(HEADERF);
    $ns->tablerender(EST_GEN_LISTINGS,'<div class="WD100 TAC FWB">'.EST_GEN_PLUGUNAVAIL.'</div>', 'estate-listings');
  	require_once(FOOTERF);
    exit;
    }
  }

$sql = e107::getDb();

if(e_QUERY){$qs = explode(".", e_QUERY);}
else{$qs = array('list',0);}

$PROPID = intval($qs[1]);



e107::css('url',e_PLUGIN.'estate/js/leaflet/leaflet.css');
e107::css('url',e_PLUGIN.'estate/js/Leaflet.markercluster/dist/MarkerCluster.css');
e107::css('url',e_PLUGIN.'estate/js/Leaflet.markercluster/dist/MarkerCluster.Default.css');

if(intval($EST_PREF['map_jssrc']) == 1 || trim($EST_PREF['map_key']) == '' || trim($EST_PREF['map_url']) == ''){
  e107::js('estate','js/leaflet/leaflet.js', 'jquery',2);
  }
else{
  if(trim($EST_PREF['map_key']) !== '' && trim($EST_PREF['map_url']) !== ''){
    e107::js('url',$tp->toHTML($EST_PREF['map_url']).'" integrity="'.$tp->toHTML($EST_PREF['map_key']).'" crossorigin="', 'jquery',2);
    }
  else{
    e107::js('estate','js/leaflet/leaflet.js', 'jquery',2);
    }
  }

    
e107::meta('mobile-web-app-capable','yes'); // example
e107::js('estate','js/Leaflet.markercluster/dist/leaflet.markercluster.js');


e107::js('estate','js/listing.js', 'jquery');
require_once('estate_defs.php');

$EST_ZONING = array();
$EST_PROPTYPES = array();
if($Z1 = $sql->retrieve("SELECT * FROM #estate_zoning",true)){
  foreach($Z1 as $row){$EST_ZONING[$row['zoning_idx']] = $row['zoning_name'];}
  }
if($Z2 = $sql->retrieve("SELECT * FROM #estate_listypes",true)){
  foreach($Z2 as $row){$EST_PROPTYPES[$row['listype_idx']] = $row['listype_name'];}
  }



if($qs[0] == 'edit' || $qs[0] == 'new'){
  require_once(e_PLUGIN.'estate/ui/oa.php');
  exit;
  }


if(isset($_POST['fltrs'])){
  if(count($_POST['fltrs']) > 0){
    foreach($_POST['fltrs'] as $fk=>$fv){
      if(trim($fv) !== ''){$PSTFLTR .= " AND ".$fk."='".$tp->toDB($fv)."' ";}
      }
    }
  }


$orderBy = (isset($_POST['sort']) ? $_POST['sort'][0] : "prop_dateupdated");
$order = (isset($_POST['sort']) ? $_POST['sort'][1] : "DESC");
$from = (isset($_POST['from']) ? intval($_POST['from']) : 0);
$records = (isset($_POST['to']) ? intval($_POST['to']) : 25);

$WHERE = "";
if(!ADMIN){
  if(intval(USERID) > 0 && intval(EST_USERPERM) == 0){
    if(intval($EST_PREF['public_act']) !== 0 && intval($EST_PREF['public_act']) !== 255 && check_class($EST_PREF['public_act'])){
      $WHERE = "WHERE prop_uidcreate='".USERID."' ".($PROPID > 0 ? "" : " OR (prop_status > 0 AND (prop_datepull > ".$STRTIMENOW." OR prop_datepull = 0))")." ";
      }
    }
  if($WHERE == ""){$WHERE = "WHERE prop_status > 0 AND (prop_datepull > ".$STRTIMENOW." OR prop_datepull = 0) ";}
  }


$MQRY = "SELECT #estate_properties.*, #estate_subdiv.subd_name, city_name, city_url, city_timezone, city_description, state_name, state_init, state_url, cnty_name, cnty_url, user_id,user_name,user_loginname,user_email,user_admin,user_perms,user_class,user_signature,user_image, #estate_agents.*, #estate_agencies.* FROM #estate_properties LEFT JOIN #estate_subdiv ON subd_idx = prop_subdiv LEFT JOIN #estate_city ON city_idx = prop_city LEFT JOIN #estate_county ON cnty_idx = prop_county LEFT JOIN #estate_states ON state_idx = prop_state LEFT JOIN #estate_agents ON agent_idx = prop_agent LEFT JOIN #user ON (prop_agent = 0 AND user_id = prop_uidcreate) OR (agent_uid > 0 AND user_id = agent_uid) LEFT JOIN #estate_agencies ON (agent_agcy > 0 AND agency_idx = agent_agcy) OR (agent_agcy = 0 AND agency_idx = prop_agency)";
  

  
  
if($qs[0] == 'agent'){
  $AGENTID = intval($qs[1]);
  $PROPID = 0;
  $query = $MQRY.$WHERE.(trim($WHERE) == "" ? "WHERE prop_agent=" : " AND prop_agent=").$AGENTID." ORDER BY prop_status ASC, ".$orderBy." ".$order." LIMIT ".intval($from).",".intval($records);
  }

elseif($qs[0] == 'listby'){
  $AGENTID = intval($qs[1]);
  $PROPID = 0;
  $query = $MQRY.$WHERE.(trim($WHERE) == "" ? "WHERE prop_uidcreate=" : " AND prop_uidcreate=").$AGENTID." ORDER BY prop_status ASC, ".$orderBy." ".$order." LIMIT ".intval($from).",".intval($records);
  }


elseif($PROPID > 0){
  $query = $MQRY.$WHERE.(trim($WHERE) == "" ? "WHERE prop_idx=" : " AND prop_idx=").$PROPID." LIMIT 1";
  //$NPQRY = $MQRY.$WHERE.(trim($WHERE) == "" ? "WHERE NOT prop_idx=" : " AND NOT prop_idx=").$PROPID." ORDER BY ".$orderBy." ".$order." LIMIT ".intval($from).",".intval($records);
  }
else{
  $query = $MQRY.$WHERE.$PSTFLTR." ORDER BY prop_status ASC, ".$orderBy." ".$order." LIMIT ".intval($from).",".intval($records);
  }


$dberr = $sql->getLastErrorText();
if($dberr){e107::getMessage()->addError($dberr);}
unset($dberr);


if(!$estQdta = $sql->retrieve($query,true)){
  if($PROPID > 0){
    define("EST_MENU_RENDERED",true);
    require_once(HEADERF);
    $ns->tablerender(EST_GEN_PROPERTY.' '.EST_GEN_VIEW,'<div id="estateCont"><h3>'.EST_GEN_PROPERTY.' #'.$PROPID.' '.EST_GEN_NOTFOUND.'</h3><div>'.EST_ERR_HLP1a.' '.$PROPID.' '.EST_ERR_HLP1b.' <a href="'.e_SELF.'"><b>'.EST_GEN_VIEWLISTINGS.'</b></a></div></div>','estate-invalid');
    unset($query,$PROPID,$MQRY,$WHERE);
    require_once(FOOTERF);
    exit;
    }
  else{
    e107::getMessage()->addInfo('<div id="estateCont"><h3 class="WD100">'.EST_GEN_NOLISTINGS.'</h3>'.(isset($PSTFLTR) ? EST_GEN_BADFILTER1.'['.$PSTFLTR.']' : EST_GEN_CHECKLATER).'</div>');
    }
  }



include_once('ui/qry.php'); // <-- generates clean $EST_PROP array used here and oa.php





$dberr = $sql->getLastErrorText();
if($dberr){e107::getMessage()->addError($dberr);}
unset($dberr);


//$EST_SAVED

//if(is_array($EST_PROP) && count($EST_PROP) > 0){
  
  
  if($qs[0] == 'pview'){
    //include_once('ui/preview.php');
    exit;
    }
  
  if(check_class($EST_PREF['contact_class'])){
    foreach($EST_PROP as $MPID=>$MDTA){
      if(intval($MDTA['prop_appr']) < 1){
        if(EST_USERPERM < intval($EST_PREF['public_mod']) && intval($MDTA['prop_uidcreate']) !== USERID){
          unset($EST_PROP[$MPID]);
          }
        }
      else{
        $EST_PROP[$MPID]['msgd'] = estGetPrevMsgs($MPID);
        }
      }
    }
  
  $EST_SAVED = array();
  if(check_class($EST_PREF['listing_save'])){
    if($inqDta = $sql->retrieve("SELECT * FROM #estate_msg WHERE ".($PROPID > 0 ? "msg_propidx='".$PROPID."' AND " : "")." msg_mode < '3'",true)){
      foreach($inqDta as $k=>$v){
        $MPID = intval($v['msg_propidx']);
        if(isset($EST_PROP[$MPID])){
          $EST_PROP[$MPID]['likes'] = intval($EST_PROP[$MPID]['likes']) + 1;
          }
        }
      }
    
    if($likeDta = $sql->retrieve("SELECT * FROM #estate_likes".($PROPID > 0 ? " WHERE like_pid='".$PROPID."'" : "")."",true)){
      foreach($likeDta as $k=>$v){
        $MPID = intval($v['like_pid']);
        if(isset($EST_PROP[$MPID])){
          $EST_PROP[$MPID]['likes'] = intval($EST_PROP[$MPID]['likes']) + 1;
          if(intval($v['like_uid']) == USERID && $v['like_ip'] == USERIP){
            $EST_PROP[$MPID]['saved'] = ' actv';
            $EST_SAVED[$MPID]['name'] = $EST_PROP[$MPID]['prop_name'];
            $EST_SAVED[$MPID]['thm'] = $EST_PROP[$MPID]['img'][1]['t'];
            }
          }
        }
      }
    
    unset($inqDta,$likeMsg,$likeDta,$k,$v,$MPID);
    }
  
  
  
  if($PROPID == 0 && $qs[0] == 'view'){$qs[0] = 'list';}
  if($PROPID > 0 && !isset($EST_PROP[$PROPID])){$qs[0] = 'list';}
  
  if($qs[0] == 'view'){
    
    if($PROPID > 0){
      if($EST_PROP[$PROPID]['prop_appr'] == 0){
        e107::getMessage()->addInfo(EST_PROP_APPROVE00);
        }
      
      if(intval($EST_PROP[$PROPID]['prop_status']) == 3 || intval($EST_PROP[$PROPID]['prop_status']) == 4){
        $ESTDTA = estGetSpaces($EST_PROP[$PROPID]);
        }
      else{$ESTDTA = estGetSpaces($EST_PROP[$PROPID],1);}
      
      if(isset($ESTDTA[2])){
        $EST_PROP[$PROPID]['subdiv'] = $ESTDTA[2];
        }
      
      $EST_PROP[$PROPID]['history'] = estGetPropHist($PROPID,$EST_PROP[$PROPID]['prop_dateupdated'],$EST_PROP[$PROPID]['prop_listprice'],$EST_PROP[$PROPID]['prop_status']);
      
      
      $IDIV = estViewCSS($ESTDTA);
      $EST_SPACES = $ESTDTA[1];
      $PROPDTA[0] = $EST_PROP[$PROPID];
      
      
      $TMPLREORDOK = 0;
      if(EST_USERPERM > 2){$TMPLREORDOK++;}
      elseif(EST_USERPERM == 2 && intval($PROPDTA[0]['prop_agency']) == EST_AGENCYID){$TMPLREORDOK++;}
      elseif(EST_USERPERM == 1 && intval($PROPDTA[0]['prop_agent']) == EST_AGENTID){$TMPLREORDOK++;}
      elseif(intval($PROPDTA[0]['prop_uidcreate']) == EST_SELLERUID){$TMPLREORDOK++;}
      
      if($TMPLREORDOK > 0){
        e107::js('estate','js/Sortable/Sortable.js', 'jquery');
        $TFORM1 = '<form name="estViewTemplateForm" method="POST" action="'.e_SELF.'?'.e_QUERY.'" >';
        $TFORM2 = '</form>';
        }
      
      
      if(intval($PROPDTA[0]['prop_uidcreate']) !== USERID){
        $PROPDTA[0]['prop_views'] = $PROPDTA[0]['prop_views'] + 1;
        $sql->update("estate_properties","prop_views='".$PROPDTA[0]['prop_views']."' WHERE prop_idx='".$PROPID."' LIMIT 1");
        }
      
      if(trim($PROPDTA[0]['prop_name']) == ''){
        if(trim($PROPDTA[0]['prop_addr1']) == ''){$PROPDTA[0]['prop_name'] = $PROPDTA[0]['prop_addr1'];}
        else{$PROPDTA[0]['prop_name'] = EST_GEN_UNNAMEDPROPERTY;}
        }
      
      $PINS = est_map_pins();
      e107::js('inline','var estMapPins = '.$PINS.'; ', 'jquery',2);
      
      $estHead = $tp->toHTML($PROPDTA[0]['prop_name'].'<div class="FS6EM">'.$PROPDTA[0]['city_name'].', '.$PROPDTA[0]['state_name'].'</div>');
      $estPT = $tp->toHTML($PROPDTA[0]['prop_name'].' | '.$PROPDTA[0]['city_name'].' | '.$PROPDTA[0]['state_name']);
      define('e_PAGETITLE',$estPT);
      define('PAGE_NAME', $estPT);
      
      estGetMeta($PROPDTA,$ESTDTA,1);
      
      //e107::meta('mobile-web-app-capable','yes'); // example
      
      $sc = e107::getScBatch('estate',true);
      $sc->setVars($PROPDTA[0]);
      
      require_once(HEADERF);
      $tmpl = e107::getTemplate('estate');
      $tkey = (trim($PROPDTA[0]['prop_template_view']) !== '' ?  $PROPDTA[0]['prop_template_view'] : (trim($EST_PREF['template_view']) !== '' ? $EST_PREF['template_view'] : 'default'));
      
      $TEMPLATE = $tmpl['view'][$tkey];
      $tmplct = 0;
      if(isset($PROPDTA[0]['prop_template_view_ord'])){
        if(is_array($PROPDTA[0]['prop_template_view_ord'])){$VIEWTMPL = $PROPDTA[0]['prop_template_view_ord'];}
        elseif(trim($PROPDTA[0]['prop_template_view_ord']) !== ''){$VIEWTMPL = e107::unserialize($PROPDTA[0]['prop_template_view_ord']);}
        }
      
      if(!is_array($VIEWTMPL) && isset($EST_PREF['template_view_ord'])){
        if(is_array($EST_PREF['template_view_ord'])){$VIEWTMPL = $EST_PREF['template_view_ord'];}
        elseif(trim($EST_PREF['template_view_ord']) !== ''){$VIEWTMPL = e107::unserialize($EST_PREF['template_view_ord']);}
        }
      
      
      if(is_array($TEMPLATE['txt'])){
        $tmplct = count($TEMPLATE['txt']);
        if(isset($TEMPLATE['ord']) && isset($VIEWTMPL[$tkey]) && count($VIEWTMPL[$tkey]) > 0){
          if($TMPLREORDOK > 0){
            $estText .= $tp->parseTemplate('{ADMIN_REORDER_MENU:area=view&tkey='.$tkey.'&ct='.$tmplct.'}', false, $sc);
            $NEWK = array();
            $ALLK = $TEMPLATE['ord'];
            foreach($VIEWTMPL[$tkey] as $sk=>$sv){$NEWK[$sk] = $TEMPLATE['txt'][$sk];}
            foreach($ALLK as $nk=>$nv){if(!$NEWK[$nv]){$NEWK[$nv] = '';}}
            unset($ALLK,$sk,$sv,$nk,$nv,$NEWK['dummy']);
            foreach($NEWK as $ok=>$dta){
              $estText .= $tp->parseTemplate('{ADMIN_REORDER:area=view&templ='.$tkey.'&ok='.$ok.'&ov='.intval($VIEWTMPL[$tkey][$ok]).'}', false, $sc);
              $estText .= $tp->parseTemplate($dta, false, $sc).'</div></div>';
              }
            unset($NEWK,$ok,$dta);
            }
          else{
            foreach($VIEWTMPL[$tkey] as $ok=>$ov){
              if(isset($TEMPLATE['txt'][$ok]) && $ov == 1){
                $estText .= $tp->parseTemplate($TEMPLATE['txt'][$ok], false, $sc);
                }
              }
            }
          }
        else{
          ksort($TEMPLATE['txt']);
          if($TMPLREORDOK > 0){
            $estText .= $tp->parseTemplate('{ADMIN_REORDER_MENU:area=view&tkey='.$tkey.'&ct='.$tmplct.'}', false, $sc);
            }
          foreach($TEMPLATE['txt'] as $k=>$tmpv){
            $estText .= $tp->parseTemplate($tmpv, false, $sc);
            }
          }
        }
      else{
        if($TMPLREORDOK > 0){
          $estText .= $tp->parseTemplate('{ADMIN_REORDER_MENU:area=view&tkey='.$tkey.'&ct='.$tmplct.'}', false, $sc);
          }
        $estText .= $tp->parseTemplate($TEMPLATE['txt'], false, $sc);
        }
      
      $ns->setStyle('main');
      $estText .= $tp->parseTemplate('<div>{PROP_HOADISCLAIMERS}</div>', false, $sc);
      $estText .= $tp->parseTemplate('<div id="estMiniSrc">{SOCIAL_LINKS}{PROP_NEWICON}{PROP_EDITICONS:for=view}</div>', false, $sc);
      
      $ns->tablerender('<span id="estMiniNav"></span>'.$estHead,$TFORM1.'<div id="estateCont" data-pid="'.$PROPID.'">'.$estText.'</div>'.$TFORM2,'estate-view');
      
      
      unset($estHead,$estPT,$estText,$EST_PROP,$VIEWTMPL,$TEMPLATE);
      }
    
    if(USERID == 1){
      //echo propArrayTest($PROPDTA[0]);
      }
    
    }
  
  else{
    $PINS = est_map_pins();
    e107::js('inline','var estMapPins = '.$PINS.'; ', 'jquery',2);
    
    require_once(HEADERF);
    $tmpl = e107::getTemplate('estate');
    $tkey = (trim($EST_PREF['template_list']) !== '' ? $EST_PREF['template_list'] : 'default');
    $sc = e107::getScBatch('estate',true);
    if(is_array($tmpl['list'][$tkey]['txt'])){
      ksort($tmpl['list'][$tkey]['txt']);
      foreach($tmpl['list'][$tkey]['txt'] as $k=>$tmpv){
        $estText .= $tp->parseTemplate($tmpv, false, $sc);
        }
      }
    else{
      $estText = $tp->parseTemplate($tmpl['list'][$tkey]['txt'], false, $sc);
      }
    
    $estText .= $tp->parseTemplate('<div id="estMiniSrc">{PROP_NEWICON}</div>', false, $sc);
    
    $ns->setStyle('main');
    $ns->tablerender('<span id="estMiniNav"></span>'.EST_GEN_LISTINGS,'<div id="estateCont">'.$estText.'</div>','estate-list');
    unset($estText,$EST_PROP);
    }
  //}
  
echo '
<div id="estJSpth"'.$OACLASS.' data-pth="'.EST_PATHABS.'"></div>
<div id="estMobTst"></div>
<div style="display:none;">Estate Plugin by Vodhin</div>';
require_once(FOOTERF);
exit;



/** 
* e107_class.php
* @param string $plug_name if null getCoreTemplate method will be called
* @param string $id - file prefix, e.g. calendar for calendar_template.php, or 'true' or 'null' for same as plugin name.
* @param string|null $key $YOURTEMPLATE_TEMPLATE[$key]
* @param boolean $override see {@link getThemeInfo()}
* @param boolean $merge merge theme with plugin templates, default is false
* @param boolean $info retrieve template info only
* 
* function getTemplate($plug_name, $id = null, $key = null, $override = true, $merge = false, $info = false){}
**/
    


function estGetMeta($PROPDTA,$ESTDTA,$MODE=0){
	$tp = e107::getParser();
  $RES = array();
  if($MODE == 1){ //view page
    //$PROPDTA[0]['prop_status']
    $type = $GLOBALS['EST_LISTTYPE1'][$PROPDTA[0]['prop_listype']];
    $zone = $GLOBALS['EST_ZONING'][$PROPDTA[0]['prop_zoning']];
    $propname = $tp->toHTML($type.': '.$PROPDTA[0]['prop_name'].' ('.$zone.')');
    $propsumm = $tp->toHTML(''.strtoupper($PROPDTA[0]['city_name'].', '.$PROPDTA[0]['state_name']).' '.$PROPDTA[0]['prop_summary']);
    $propdesc2 = $tp->toHTML($PROPDTA[0]['prop_description']);
    $kwarr = array(
      $zone,
      $type,
      strtoupper($PROPDTA[0]['prop_country']),
      $PROPDTA[0]['cnty_name'],
      $PROPDTA[0]['state_name'],
      $PROPDTA[0]['city_name'],
      $PROPDTA[0]['prop_zip'],
      $PROPDTA[0]['prop_addr1'].(trim($PROPDTA[0]['prop_addr2']) !== '' ? ','.$PROPDTA[0]['prop_addr2'] : ''),
      $PROPDTA[0]['agent_name'],
      $PROPDTA[0]['agency_name'],
      );
    
    if(intval($PROPDTA[0]['prop_bedtot']) > 0){array_push($kwarr,$PROPDTA[0]['prop_bedtot'].' '.EST_GEN_BED);}
    if(intval($PROPDTA[0]['prop_bathtot']) > 0){array_push($kwarr,$PROPDTA[0]['prop_bathtot'].' '.EST_GEN_BATH);}
    if(trim($PROPDTA[0]['prop_flag']) !== ''){array_push($kwarr,$PROPDTA[0]['prop_flag']);}
    
    //<!-- For Google -->
    e107::meta('description',$propsumm.' '.$propdesc2);
    e107::meta('keywords',$tp->toHTML(implode(",",$kwarr)));
    //<meta name="author" content="" />
    //<meta name="copyright" content="" />
    //<meta name="application-name" content="" />
    
    //<!-- For Facebook -->
    e107::meta('fb:app_id','1024154842269829');
    e107::meta('og:locale','en_us');
    e107::meta('og:title',$propname);
    e107::meta('og:type','website');
    e107::meta('og:url',$PROPDTA[0]['prop_link']);
    e107::meta('og:description',$propsumm.' '.$propdesc2);
    
    
  

  if(isset($ESTDTA[0])){
    e107::meta('og:image',EST_PTHABS_PROPTHM.$ESTDTA[0][1]['t']);
    e107::meta('twitter:image',EST_PTHABS_PROPTHM.$ESTDTA[0][1]['t']);
    //foreach($ESTDTA[0] as $mk=>$mv){
      //e107::meta('og:image',EST_PTHABS_PROPTHM.$mv['t']);
      //e107::meta('twitter:image',EST_PTHABS_PROPTHM.$mv['t']);
      //}
    }
  
    
    /*
    <meta property="og:image" content="https://example.com/rock.jpg" />
    <meta property="og:image:width" content="300" />
    <meta property="og:image:height" content="300" />
    <meta property="og:image" content="https://example.com/rock2.jpg" />
    <meta property="og:image" content="https://example.com/rock3.jpg" />
    <meta property="og:image:height" content="1000" />
    */
    
    //<!-- For Twitter -->
    e107::meta('twitter:card',$propsumm);
    e107::meta('twitter:title',$propname);
    e107::meta('twitter:description',$propsumm.' '.$propdesc2);
    
    
    //e107::meta($name, $content, $extended);
    unset($type,$zone,$propname,$propsumm,$propdesc2);
    }
  else{
    
    }
  
  return $RES;
  }




function estViewCSS($ESTDTA){
  $CSSTOP = estViewImgCSS('#estSlideShow',$ESTDTA[0]);
  if($CSSTOP[0]){
    $CSSTOP[0] = '
    
    /*Estate Plugin CSS*/
'.$CSSTOP[0];
    e107::css('inline', $CSSTOP[0]);
    }
  
  
  if($CSSTOP[1]){$IDIV = $CSSTOP[1];}
  if($CSSTOP[2]){
    $estPreJS = '
      pics = [];';
    $pli = 0;
    foreach($CSSTOP[2] as $plk=>$plv){
      $estPreJS .= '
      pics['.$pli.'] = new Image();
      pics['.$pli.'].src = "'.EST_PTHABS_PROPTHM.$plv.'";';
      $pli++;
      }
    e107::js('inline', $estPreJS);
    }
  
  
  
  if(isset($ESTDTA[2])){
    $CSSTOP = estViewImgCSS('#estSubDivSlideShow',$ESTDTA[2]['media'],2);
    if($CSSTOP[0]){e107::css('inline', $CSSTOP[0]);}
    if($CSSTOP[1]){$IDIV .= $CSSTOP[1];}
    }
  
  if(isset($ESTDTA[1])){
    foreach($ESTDTA[1] as $k=>$v){
      foreach($v['sp'] as $sok=>$sov){
        foreach($sov as $sk=>$sv){
          if($sv['m']){
            //e107::meta('og:image',EST_PTHABS_PROPTHM.$sv['t']);
            //e107::meta('twitter:image',EST_PTHABS_PROPTHM.$sv['t']);
            $CSSTOP = estViewImgCSS('.SPACE'.$v['ord'].'x'.$sok.'x'.$sk.'img',$sv['m']);
            if($CSSTOP[0]){e107::css('inline', $CSSTOP[0]);}
            }
          }
        }
      }
    }
  return $IDIV;
  }


function est_map_pins(){
  $pref = e107::pref();
  $sql = e107::getDb();
	$tp = e107::getParser();
  $ARR1 = array('agcy'=>array(),'prop'=>array());
  
  if(intval($GLOBALS['EST_PREF']['map_include_agency']) == 1){
  
    if($AGY = $sql->retrieve("SELECT #estate_agencies.* FROM #estate_agencies WHERE NOT agency_lat='' AND NOT agency_lon='' ",true)){
      $i = 0;
      foreach($AGY as $k=>$v){
        if(intval($v['agency_pub']) > 0){
          $ARR1['agcy'][$i] = array(
            'idx'=>$v['agency_idx'],
            'name1'=>$tp->toHTML($v['agency_name']),
            'name2'=>'',
            'addr'=>$v['agency_addr'],
            'lat'=>$v['agency_lat'],
            'lon'=>$v['agency_lon'],
            'thm'=>(intval($v['agency_imgsrc']) == 1 && trim($v['agency_image']) !== '' ? EST_PTHABS_AGENCY.$tp->toHTML($v['agency_image']) : $tp->thumbUrl($pref['sitelogo'],false,false,true)),
            'zoom'=>$GLOBALS['EST_PREF']['map_zoom_def']
            );
          $i++;
          }
        }
      }
    }
  
  if($GLOBALS['EST_PROP']){
    if(count($GLOBALS['EST_PROP']) > 0){
      if(intval($GLOBALS['PROPID']) > 0){
        foreach($GLOBALS['PROPDTA'] as $k=>$v){
          $ARR1['prop'][0] = array(
            'idx'=>$v['prop_idx'],
            'lat'=>$v['prop_lat'],
            'lon'=>$v['prop_lon'],
            'lnk'=>null,
            'name1'=>$tp->toHTML($v['prop_name']),
            'zoom'=>$v['prop_zoom']
            );
          }
        }
      else{
        $i = 0;
        $incs = (5 - intval($GLOBALS['EST_PREF']['map_include_sold']));
        foreach($GLOBALS['EST_PROP'] as $k=>$v){
          if(intval($v['prop_status']) > 0){
            if(intval($v['prop_status']) <= $incs){
              $ARR1['prop'][$i] = array(
                'drop'=>estPriceDrop($v,1),
                'hue'=>estPinColor($v),
                'feat'=>explode(',',$v['prop_features']),
                'idx'=>$v['prop_idx'],
                'lat'=>$v['prop_lat'],
                'lon'=>$v['prop_lon'],
                'lnk'=>EST_PTH_LISTINGS.'?view.'.intval($v['prop_idx']).'.0',
                'name1'=>$tp->toHTML($v['prop_name']),
                'prc'=>estGetListPrice($v,1),
                'sta'=>$tp->toHTML(estPinsPropStat($v)),
                'stat'=>intval($v['prop_status']),
                'thm'=>$v['img'][1]['t'],
                'type'=>$v['prop_listype'],
                'zoom'=>$v['prop_zoom']
                );
              $i++;
              }
            }
          }
        }
      }
    }
  return json_encode($ARR1);
  }


function estPinColor($DTA){
  switch(intval($DTA['prop_status'])){
    case 5 :
      return 'estMarkGray';
      break;
    case 4 :
      return 'estMarkYellow';
      break;
    case 3 :
      return null;
      break;
    case 2 :
      return 'estMarkGreen';
      break;
    case 1 :
      return 'estMark';
      break;
    case 0 :
      return null;
      break;
    }
  }





function estPinsPropStat($DTA){
  if(intval($DTA['prop_status']) == 5){$ret = (intval($DTA['prop_listype']) == 0 ? EST_GEN_OFFMARKET : EST_GEN_SOLD);}
  elseif(intval($DTA['prop_status']) == 4){$ret = EST_GEN_PENDING; $parm = '';}
  elseif(intval($DTA['prop_status']) == 3){$ret = $GLOBALS['EST_LISTTYPE1'][$DTA['prop_listype']]; $parm = '';}
  elseif(intval($DTA['prop_status']) == 2){
    $parm = '';
    if(intval($DTA['prop_datelive']) > 0 && intval($DTA['prop_datelive']) <= $GLOBALS['STRTIMENOW']){
      $ret = $GLOBALS['EST_LISTTYPE1'][$DTA['prop_listype']];
      }
    elseif(USERID > 0 && (intval($DTA['prop_dateprevw']) > 0 && intval($DTA['prop_dateprevw']) <= $GLOBALS['STRTIMENOW'])){
      $ret = EST_GEN_PREVIEW;
      }
    else{$ret = EST_GEN_COMINGSOON;}
    }
  elseif(intval($DTA['prop_status']) == 1){
    $ret = EST_GEN_COMINGSOON;
    if(!ADMIN){$parm = '';}
    }
  else{$ret = EST_GEN_OFFMARKET;}
  if($ret){return $ret.($parm == 'bullet' ? ' • ' : '');}
  else{return '';}
  }