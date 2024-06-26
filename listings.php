<?php

if(!defined('e107_INIT')){require_once(__DIR__ . '/../../class2.php');}

e107::lan('estate',true,true);
$ns = e107::getRender();
$tp = e107::getParser();

$EST_PREF = e107::pref('estate');


e107::css('url',e_PLUGIN.'estate/css/listings.css');
e107::css('url',e_PLUGIN.'estate/css/viewtop.css');
e107::css('url',e_PLUGIN.'estate/css/spaces.css');

//e107::css('url',e_PLUGIN.'estate/css/msg.css');


$e107 = e107::getInstance();
if(!$e107->isInstalled('estate')){
  require_once(HEADERF);
  $ns->tablerender(EST_GEN_LISTINGS,'<div class="WD100 TAC FWB">'.(ADMIN ? EST_GEN_PLUGNOTINST : EST_GEN_PLUGUNAVAIL).'</div>', 'estate-listings');
	require_once(FOOTERF);
	exit;
  }


$sql = e107::getDb();

if(intval($EST_PREF['adminonly']) == 1){
  if(ADMIN){$estText = '<div class="WD100 TAC FWB">'.EST_GEN_PLUGADMINONLY.'</div>';}
  else{
    require_once(HEADERF);
    $ns->tablerender(EST_GEN_LISTINGS,'<div class="WD100 TAC FWB">'.EST_GEN_PLUGUNAVAIL.'</div>', 'estate-listings');
  	require_once(FOOTERF);
    exit;
    }
  }


if(e_QUERY){$qs = explode(".", e_QUERY);}
else{$qs = array('list',0);}




if(EST_USERPERM == 4){
  e107::js('estate','js/Sortable/Sortable.js', 'jquery');
  $TFORM1 = '<form name="estViewTemplateForm" method="POST" action="'.e_SELF.'?'.e_QUERY.'" >';
  $TFORM2 = '</form>';
  }


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



$PROPID = intval($qs[1]);
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


$MQRY = "SELECT #estate_properties.*, city_name, city_url, city_timezone, state_name, state_init, state_url, cnty_name, cnty_url, user_id,user_name,user_loginname,user_email,user_admin,user_perms,user_class,user_signature,user_image, #estate_agents.*, #estate_agencies.* FROM #estate_properties LEFT JOIN #estate_city ON city_idx = prop_city LEFT JOIN #estate_county ON cnty_idx = prop_county LEFT JOIN #estate_states ON state_idx = prop_state LEFT JOIN #estate_agents ON agent_idx = prop_agent LEFT JOIN #user ON (prop_agent = 0 AND user_id = prop_uidcreate) OR (agent_uid > 0 AND user_id = agent_uid) LEFT JOIN #estate_agencies ON (agent_agcy > 0 AND agency_idx = agent_agcy) OR (agent_agcy = 0 AND agency_idx = prop_agency)";
  
  
  
  
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

//if(count($EST_PROP) > 0){
  
  
  if($qs[0] == 'pview'){
    //include_once('ui/preview.php');
    exit;
    }
  
  //$EST_PROP[$MPID]['msgd'] = array();
  if(check_class($EST_PREF['contact_class'])){
    foreach($EST_PROP as $MPID=>$MDTA){
      $EST_PROP[$MPID]['msgd'] = estGetPrevMsgs($MPID);
      }
    }
  
  $EST_SAVED = array();
  if(check_class($EST_PREF['listing_save'])){
    if($inqDta = $sql->retrieve("SELECT * FROM #estate_msg WHERE ".($PROPID > 0 ? "msg_propidx='".$PROPID."' AND " : "")." msg_mode < '3'",true)){
      foreach($inqDta as $k=>$v){
        $MPID = intval($v['msg_propidx']);
        if($EST_PROP[$MPID]){
          $EST_PROP[$MPID]['likes'] = intval($EST_PROP[$MPID]['likes']) + 1;
          }
        }
      }
    
    if($likeDta = $sql->retrieve("SELECT * FROM #estate_likes".($PROPID > 0 ? " WHERE like_pid='".$PROPID."'" : "")."",true)){
      foreach($likeDta as $k=>$v){
        $MPID = intval($v['like_pid']);
        if($EST_PROP[$MPID]){
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
  
  
  
  
  
  
  
  if($qs[0] == 'view'){
    
    if($PROPID > 0){
      if(intval($EST_PROP[$PROPID]['prop_status']) == 3 || intval($EST_PROP[$PROPID]['prop_status']) == 4){
        $ESTDTA = estGetSpaces($PROPID);
        }
      else{$ESTDTA = estGetSpaces($PROPID,1);}
      
      $IDIV = estViewCSS($ESTDTA);
      $EST_SPACES = $ESTDTA[1];
      $PROPDTA[0] = $EST_PROP[$PROPID];
      
      if(intval($PROPDTA[0]['prop_uidcreate']) !== USERID){
        $PROPDTA[0]['prop_views'] = $PROPDTA[0]['prop_views'] + 1;
        $sql->update("estate_properties","prop_views='".$PROPDTA[0]['prop_views']."' WHERE prop_idx='".$PROPID."' LIMIT 1");
        }
      
      $PINS = est_map_pins();
      e107::js('inline','var estMapPins = '.$PINS.'; ', 'jquery',2);
      
      $estHead = $tp->toHTML(trim($PROPDTA[0]['prop_name']) !== '' ? $PROPDTA[0]['prop_name'] : EST_GEN_UNNAMEDPROPERTY);
      define('e_PAGETITLE',$estHead);
      define('PAGE_NAME', $estHead);
      
      //e107::meta($name, $content, $extended);
      //e107::meta('keywords','some words'); // example
      //e107::meta('apple-mobile-web-app-capable','yes'); // example
      
      $sc = e107::getScBatch('estate',true);
      $sc->setVars($PROPDTA[0]);
      require_once(HEADERF);
      $tmpl = e107::getTemplate('estate');
      
      $tkey = (trim($EST_PREF['template_view']) !== '' ? $EST_PREF['template_view'] : 'default');
      $TEMPLATE = $tmpl['view'][$tkey];
      $tmplct = count($TEMPLATE['txt']);
      if(is_array($TEMPLATE['txt'])){
        $PREFTMP = $EST_PREF['template_view_ord'][$tkey];
        if($TEMPLATE['ord'] && count($PREFTMP) > 0){
          if(EST_USERPERM == 4){
            $estText .= $tp->parseTemplate('{ADMIN_REORDER_MENU:area=view&tkey='.$tkey.'&ct='.$tmplct.'}', false, $sc);
            $NEWK = array();
            $ALLK = $TEMPLATE['ord'];
            foreach($PREFTMP as $sk=>$sv){$NEWK[$sk] = $TEMPLATE['txt'][$sk];}
            foreach($ALLK as $nk=>$nv){if(!$NEWK[$nv]){$NEWK[$nv] = '';}}
            unset($ALLK,$sk,$sv,$nk,$nv,$NEWK['dummy']);
            foreach($NEWK as $ok=>$dta){
              $estText .= $tp->parseTemplate('{ADMIN_REORDER:area=view&templ='.$tkey.'&ok='.$ok.'&ov='.intval($PREFTMP[$ok]).'}', false, $sc);
              $estText .= $tp->parseTemplate($dta, false, $sc).'</div></div>';
              }
            unset($NEWK,$ok,$dta);
            }
          else{
            foreach($PREFTMP as $ok=>$ov){
              if(isset($TEMPLATE['txt'][$ok]) && $ov == 1){
                $estText .= $tp->parseTemplate($TEMPLATE['txt'][$ok], false, $sc);
                }
              }
            }
          }
        else{
          ksort($TEMPLATE['txt']);
          if(EST_USERPERM == 4){
            $estText .= $tp->parseTemplate('{ADMIN_REORDER_MENU:area=view&tkey='.$tkey.'&ct='.$tmplct.'}', false, $sc);
            }
          foreach($TEMPLATE['txt'] as $k=>$tmpv){
            $estText .= $tp->parseTemplate($tmpv, false, $sc);
            }
          }
        }
      else{
        if(EST_USERPERM == 4){
          $estText .= $tp->parseTemplate('{ADMIN_REORDER_MENU:area=view&tkey='.$tkey.'&ct='.$tmplct.'}', false, $sc);
          }
        $estText .= $tp->parseTemplate($TEMPLATE['txt'], false, $sc);
        }
      
      $ns->setStyle('main');
      $estText .= $tp->parseTemplate('<div id="estMiniSrc">{PROP_NEWICON}{PROP_EDITICONS:for=view}</div>', false, $sc);
      
      
      $ns->tablerender('<span id="estMiniNav"></span>'.$estHead,$TFORM1.'<div id="estateCont" data-pid="'.$PROPID.'">'.$estText.'</div>'.$TFORM2,'estate-view');
      unset($estHead,$estText,$EST_PROP,$PREFTMP,$TEMPLATE);
      }
    }
  
  else{
    $PINS = est_map_pins();
    e107::js('inline','var estMapPins = '.$PINS.'; ', 'jquery',2);
    
    require_once(HEADERF);
    $tmpl = e107::getTemplate('estate');
    $tkey = (trim($EST_PREF['template_list']) !=='' ? $EST_PREF['template_list'] : 'default');
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
<div id="estMobTst"></div>';
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
    





function estViewImgCSS($cssName,$gal,$actv,$stime,$sdelay){
  global $EST_PREF;
  $CSS = array();
  $galCt = ($gal ? count($gal) : 0);
  if($galCt == 0){
    $CSS[0] = '#estSlideShow{background-image: url("'.EST_PATHABS_IMAGES.'imgnotavail.png");}';
    $CSS[1] .= '
        <img src="'.EST_PATHABS_IMAGES.'imgnotavail.png" />';
    }
  else{
    $sdelay = intval($sdelay);
    $iStep = round(99 / $galCt, 2);
    $CSS[0] = '';
    $URLIST = 'url(\''.EST_PTHABS_PROPTHM.$gal[1]['t'].'\')';
    
    if(intval($actv) == 1 && $galCt > 1){
      $aniName = str_replace('.','',str_replace('#','',$cssName));
      $iPct = 0;
      $fi = 0;
      foreach($gal as $ik=>$idta){
        $fi++;
        if($fi > 1){$URLIST .= ',url(\''.EST_PTHABS_PROPTHM.$idta['t'].'\')';}
        $CSS[1] .= '
        <img src="'.EST_PTHABS_PROPTHM.$idta['t'].'" />';
        $CSS[2][$ik] = $idta['t'];
        $galCSS .= $iPct.'%'.($fi == 1 ? ', 100%' : '').' {background-image: url("'.EST_PTHABS_PROPTHM.$idta['t'].'");}
    ';
        $iPct = ($iStep + $iPct);
        }
      
      if($sdelay == 0){
        $sdelay = ($galCt > 7 ? ceil($galCt / 2) : 4);
        }
      
      $galBaseCSS = '
      animation: '.$aniName.' '.($galCt * intval($stime)).'s infinite;
      animation-delay: '.$sdelay.'s;
      visibility: visible !important;
      -webkit-animation-duration: '.($galCt * intval($stime)).'s;
      -webkit-animation-name: '.$aniName.';
      -webkit-animation-iteration-count: infinite;
      ';
      
      $CSS[0] .= '    @keyframes '.$aniName.'{
      '.$galCSS.'}';
        }
    $CSS[0] .= '
    '.$cssName.'{
      background-image:'.$URLIST.';'.$galBaseCSS.'
      }';
    
    }
  return $CSS;
  unset($galCSS,$galBaseCSS,$cssName,$aniName,$galBaseCSS);
  }



function estViewCSS($ESTDTA){
  global $EST_PREF;
  $CSSTOP = estViewImgCSS('#estSlideShow',$ESTDTA[0],$EST_PREF['slideshow_act'],$EST_PREF['slideshow_time'],$EST_PREF['slideshow_delay']);
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
  
  foreach($ESTDTA[1] as $k=>$v){
    foreach($v['sp'] as $sok=>$sov){
      foreach($sov as $sk=>$sv){
        if($sv['m']){
          $CSSTOP = estViewImgCSS('.SPACE'.$v['ord'].'x'.$sok.'x'.$sk.'img',$sv['m'],$GLOBALS['EST_PREF']['layout_view_spaces_ss'],4,0);
          if($CSSTOP[0]){e107::css('inline', $CSSTOP[0]);}
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
  
  if($pref['estate']['map_include_agency'] == 1){
    if($AGY = $sql->retrieve("SELECT #estate_agencies.* FROM #estate_agencies WHERE NOT agency_lat='' AND NOT agency_lon='' ",true)){
      $i = 0;
      foreach($AGY as $k=>$v){
        if(intval($v['agency_pub']) > 0){
          $ARR1['agcy'][$i] = array(
            'idx'=>$v['agency_idx'],
            'name1'=>$v['agency_name'],
            'name2'=>'',
            'addr'=>$v['agency_addr'],
            'lat'=>$v['agency_lat'],
            'lon'=>$v['agency_lon'],
            'thm'=>(intval($v['agency_imgsrc']) == 1 && trim($v['agency_image']) !== '' ? EST_PTHABS_AGENCY.$tp->toHTML($v['agency_image']) : $tp->thumbUrl($pref['sitelogo'],false,false,true)),
            'zoom'=>$pref['estate']['map_zoom_def']
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
        foreach($GLOBALS['EST_PROP'] as $k=>$v){
          if(intval($v['prop_status']) > 1 && intval($v['prop_status']) < 5 && trim($v['prop_lat']) !== '' && trim($v['prop_lon']) !== ''){
            $ARR1['prop'][$i] = array(
              'drop'=>est_PinsPriceDrop($v,1),
              'feat'=>explode(',',$v['prop_features']),
              'idx'=>$v['prop_idx'],
              'lat'=>$v['prop_lat'],
              'lon'=>$v['prop_lon'],
              'lnk'=>EST_PTH_LISTINGS.'?view.'.intval($v['prop_idx']).'.0',
              'name1'=>$tp->toHTML($v['prop_name']),
              'prc'=>estPinsPrice($v,1),
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
  return json_encode($ARR1);
  }




function estPinsPrice($DTA,$NOADV=0){
	$tp = e107::getParser();
  $nf = new NumberFormatter('en_US', \NumberFormatter::CURRENCY);
  $nf->setTextAttribute(NumberFormatter::CURRENCY_CODE, 'USD');
  $nf->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 0);
  
  $ListPrice = $nf->format($DTA['prop_listprice']).($DTA['prop_listype'] == 0 ? '/'.$GLOBALS['EST_LEASEFREQ'][$DTA['prop_leasefreq']] : '');
  $ListPrice .= est_PinsPriceDrop($DTA,0);
  
  if(ADMIN && (intval($DTA['prop_status']) < 2 || intval($DTA['prop_status']) > 4)){
    $ADMVIEW = '<span class="estAdmView" title="'.EST_GEN_ADMVIEW.'">'.$ListPrice.'</span>';
    }

  if(intval($DTA['prop_status']) == 5){
    if($ADMVIEW){$ret = $ADMVIEW;}
    }
  elseif(intval($DTA['prop_status']) == 4 || intval($DTA['prop_status']) == 3){
    $ret = $ListPrice;
    }
  elseif(intval($DTA['prop_status']) == 2){
    if(intval($DTA['prop_datelive']) > 0 && intval($DTA['prop_datelive']) <= $GLOBALS['STRTIMENOW']){
      $ret = $ListPrice;
      }
    elseif(USERID > 0 && (intval($DTA['prop_dateprevw']) > 0 && intval($DTA['prop_dateprevw']) <= $GLOBALS['STRTIMENOW'])){
      $ret = ($NOADV == 0 ? $GLOBALS['EST_LISTTYPE1'][$DTA['prop_listype']] : '').' '.$ListPrice;
      }
    elseif($ADMVIEW){$ret = ($NOADV == 0 ? $GLOBALS['EST_LISTTYPE1'][$DTA['prop_listype']] : '').' '.$ADMVIEW;}
    }
  elseif(intval($DTA['prop_status']) == 1){
    if($ADMVIEW){$ret = ($NOADV == 0 ? $GLOBALS['EST_LISTTYPE1'][$DTA['prop_listype']] : '').' '.$ADMVIEW;}
    }
  unset($nf,$ListPrice,$ADMVIEW);
  return $ret;
  }


function est_PinsPriceDrop($DTA,$MODE){
  if(intval($DTA['prop_listprice']) !== intval($DTA['prop_origprice'])){
    $OPLP = round((1 -(intval($DTA['prop_listprice']) / intval($DTA['prop_origprice']))) * 100, 1);
    if($MODE > 0){
      if($MODE == 1){return $OPLP;}
      }
    else{
      if($OPLP > 0){return '<span class="estPriceDrop">↓'.$OPLP.'%</span>';} // style="color:#009900"
      else{return'<span class="estPriceDrop">↑'.$OPLP.'%</span>';} // style="color:#990000"
      }
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