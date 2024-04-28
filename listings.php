<?php

if(!defined('e107_INIT')){require_once(__DIR__ . '/../../class2.php');}

e107::lan('estate',true,true);

$ns = e107::getRender();
$tp = e107::getParser();

$phpver = phpversion();
$phpinf = explode('.',$phpver);
if($phpinf[0] > 7 || $phpinf[0] < 5){
  require_once(HEADERF);
  $ns->tablerender('PHP Version Error', EST_PHPVERR1.' '.$phpver.' '.EST_PHPVERR2,'estate-phperror');
  unset($phpver,$phpinf,$ns);
  require_once(FOOTERF);
  exit;
  }





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


$sql = e107::getDb();
$EST_PREF = e107::pref('estate');

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


if(intval($EST_PREF['layout_list_map']) > 0 || intval($EST_PREF['map_active2']) > 0){
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
  e107::js('estate','js/Leaflet.markercluster/dist/leaflet.markercluster.js');
  }

e107::js('estate','js/listing.js', 'jquery');


require_once('estate_defs.php');

$PROPID = intval($qs[1]);
if($qs[0] == 'edit' || $qs[0] == 'new'){
  require_once(e_PLUGIN.'estate/ui/oa.php');
  }










$order = "DESC";
$orderBy = "prop_dateupdated";
$from = 0;
$records = 150;


$WHERE = "";
if(!ADMIN){$WHERE = "WHERE prop_status > 0 AND (prop_datepull > ".$STRTIMENOW." OR prop_datepull = 0) ";} //


$MQRY = "
  SELECT #estate_properties.*, city_name, city_url, city_timezone, state_init, state_url, cnty_name, cnty_url, user_id,user_name,user_loginname,user_email,user_admin,user_perms,user_class,user_signature,user_image, #estate_agents.*, #estate_agencies.*
  FROM #estate_properties
  LEFT JOIN #estate_city
  ON city_idx = prop_city
  LEFT JOIN #estate_county
  ON cnty_idx = prop_county
  LEFT JOIN #estate_states
  ON state_idx = prop_state
  LEFT JOIN #estate_agents
  ON agent_idx = prop_agent
  LEFT JOIN #user
  ON (prop_agent = 0 AND user_id = prop_uidcreate) OR (agent_uid > 0 AND user_id = agent_uid)
  LEFT JOIN #estate_agencies 
  ON (agent_agcy > 0 AND agency_idx = agent_agcy) OR (agent_agcy = 0 AND agency_idx = prop_agency)
  ";
  
  
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
    $NPQRY = $MQRY.$WHERE.(trim($WHERE) == "" ? "WHERE NOT prop_idx=" : " AND NOT prop_idx=").$PROPID."
    ORDER BY ".$orderBy." ".$order." LIMIT ".intval($from).",".intval($records);
    }
  else{
    $query = $MQRY.$WHERE." ORDER BY prop_status ASC, ".$orderBy." ".$order." LIMIT ".intval($from).",".intval($records);
    }

if(!$estQdta = $sql->retrieve($query,true)){
  require_once(HEADERF);
  $tmpl = e107::getTemplate('estate');
  if($PROPID > 0){
    $thead = EST_GEN_PROPERTY.' '.EST_GEN_VIEW;
    $text = $tp->parseTemplate($tmpl['noview'], false);
    }
  else{
    $thead = EST_GEN_LISTINGS;
    $text = $tp->parseTemplate($tmpl['nolist'], false);
    }
  $ns->tablerender($thead, $text,'estate-invalid');
  unset($query,$PROPID,$WHERE,$EST_PROP,$thead,$text);
  require_once(FOOTERF);
  exit;
  }


$dberr = $sql->getLastErrorText();
if($dberr){e107::getMessage()->addError($dberr);}



include_once('ui/qry.php');

if(count($EST_PROP) > 0){

  foreach($EST_PROP as $PK=>$PV){
    $DBTST .= '<p>';
    foreach($PV as $DK=>$DV){
      $DBTST .= '<div>['.$DK.']';
      if(is_array($DV)){
        foreach($DV as $CK=>$CV){
          $DBTST .= '<div>...['.$CK.'] '.$CV.'</div>';
          }
        }
      else{
        $DBTST .= ' '.$DV;
        }
      $DBTST .= '</div>';
      }
    $DBTST .= '</p><br />';
    }
  unset($DBTST);
  
  
  
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
      
      
      $tmpl = e107::getTemplate('estate');
      $sc = e107::getScBatch('estate',true);
      $sc->setVars($PROPDTA[0]);
      e107::js('inline','var estMapPins = '.$tp->parseTemplate($tmpl['pins'], false, $sc).'; ', 'jquery',2);
      
      $estHead = $tp->toHTML($PROPDTA[0]['prop_name']);
      define('e_PAGETITLE',$estHead);
      
      
      //define('PAGE_NAME', 'Members');
      
      //e107::meta($name, $content, $extended);
      //e107::meta('keywords','some words'); // example
      //e107::meta('apple-mobile-web-app-capable','yes'); // example
      
      require_once(HEADERF);
      if($DBTST){
        $ns->tablerender('DB TEST',$DBTST,'estate-test');
        unset($DBTST);
        }
        
      
      $estText .= $tp->parseTemplate($tmpl['start'], false);
      $estText .= $tp->parseTemplate($tmpl['view']['top'], false, $sc);
      $estText .= $tp->parseTemplate($tmpl['view']['sum'], false, $sc);
      $estText .= $tp->parseTemplate($tmpl['view']['spaces'], false, $sc);
      $estText .= $tp->parseTemplate($tmpl['view']['map'], false, $sc);
      //$estText .= $tp->parseTemplate($tmpl['view']['nplisting'], false, $sc);
      if($IDIV){$estText .= $tp->parseTemplate($tmpl['view']['gal'], false, $sc);}
      
      $estText .= $tp->parseTemplate($tmpl['end'], false);
      
      $estText .= $tp->parseTemplate($tmpl['view']['elnk'], false, $sc);
      
      $ns->tablerender('<span id="estMiniNav"></span>'.$estHead,$estText,'estate-view');
      unset($estHead,$estText,$EST_PROP);
      }
    }
  
  elseif($qs[0] == 'pview'){
    if(ADMIN){
      $e107_popup = 1;
            
      if(strpos($qs[3],'|') > -1){
        $NP = explode('|',$qs[3]);
        
        switch($qs[2]){
          case 'list' :
            $EST_PREF['layout_list'] = $tp->toTEXT($NP[0]);
            $EST_PREF['layout_list_map'] = intval($NP[1]);
            $EST_PREF['layout_list_mapagnt'] = intval($NP[2]);
            $EST_PREF['layout_list_agent'] = intval($NP[3]);
            break;
          
          case 'top' :
            $EST_PREF['slideshow_act'] = intval($NP[0]);
            $EST_PREF['slideshow_time'] = intval($NP[1]);
            $EST_PREF['slideshow_delay'] = intval($NP[2]);
            break;
          
          case 'sum' :
            $EST_PREF['layout_view_summbg'] = $tp->toTEXT($NP[0]);
            $EST_PREF['layout_view_agent'] = $tp->toTEXT($NP[1]);
            $EST_PREF['layout_view_agntbg'] = $tp->toTEXT($NP[2]);
            break;
          
          case 'spaces' :
            $EST_PREF['layout_view_spacesbg'] = $tp->toTEXT($NP[0]);
            $EST_PREF['layout_view_spaces_ss'] = intval($NP[1]);
            $EST_PREF['layout_view_spaces'] = $tp->toTEXT($NP[2]);
            $EST_PREF['layout_view_spacedynbg'] = $tp->toTEXT($NP[3]);
            $EST_PREF['layout_view_spacetilebg'] = $tp->toTEXT($NP[4]);
            break;
          
          case 'map' :
            $EST_PREF['layout_view_map'] = intval($NP[0]);
            $EST_PREF['layout_view_mapagnt'] = intval($NP[1]);
            $EST_PREF['layout_view_mapbg'] = $tp->toTEXT($NP[2]);
            break;
          
          case 'gal' :
            $EST_PREF['layout_view_gallbg'] = $tp->toTEXT($NP[0]);
            break;
          }
        }
      
      
      e107::css('inline', 'body{padding-top: 0px;}');
      if($PROPID > 0){
        $ESTDTA = estGetSpaces($PROPID);
        $IDIV = estViewCSS($ESTDTA);
        $EST_SPACES = $ESTDTA[1];
        $PROPDTA[0] = $EST_PROP[$PROPID];
        
        $tmpl = e107::getTemplate('estate');
        $sc = e107::getScBatch('estate',true);
        $sc->setVars($PROPDTA[0]);
        
        e107::js('inline','var estMapPins = '.$tp->parseTemplate($tmpl['pins'], false, $sc).'; ', 'jquery',2);
        require_once(HEADERF);
        $estText .= $tp->parseTemplate($tmpl['view'][$qs[2]], false, $sc);
        $ns->tablerender('','<div id="estDtaCont" class="estPrefScale75">'.$estText.'</div>','estate-view'); //
        }
      else{
        $tmpl = e107::getTemplate('estate');
        $sc = e107::getScBatch('estate',true);
        e107::js('inline','var estMapPins = '.$tp->parseTemplate($tmpl['pins'], false, $sc).'; ', 'jquery',2);
        
        if($EST_PREF['layout_list_map'] == 1){$estText .= $tp->parseTemplate($tmpl['list']['map'], false);}
        $estText .= '<div id="estateCont" class="'.$EST_PREF['layout_list'].'">';
        if(count($EST_PROP) > 0){
          foreach($EST_PROP as $k=>$v){
            $sc->setVars($v);
            $estText .= $tp->parseTemplate($tmpl['list']['item'], false, $sc);
            }
          }
        $estText .= '</div>';
        if($EST_PREF['layout_list_map'] == 2){$estText .= $tp->parseTemplate($tmpl['list']['map'], false);}
                
        $e107_popup = 1;
        require_once(HEADERF);
        $ns->tablerender('','<div id="estDtaCont" class="estPrefScale75">'.$estText.'</div>','estate-list');
        }
      
      }
    }
  
  
  else{
    
    $tmpl = e107::getTemplate('estate');
    $sc = e107::getScBatch('estate',true);
    e107::js('inline','var estMapPins = '.$tp->parseTemplate($tmpl['pins'], false, $sc).'; ', 'jquery',2);
    
    $estHead = EST_GEN_LISTINGS;
    
    require_once(HEADERF);
    
    
    //$estText .= $tp->parseTemplate($tmpl['start'], false);
    
    if($EST_PREF['layout_list_map'] == 1){$estText .= $tp->parseTemplate($tmpl['list']['map'], false, $sc);}
    $estText .= '<div id="estateCont" class="'.$EST_PREF['layout_list'].'">';
    if(count($EST_PROP) > 0){
      foreach($EST_PROP as $k=>$v){
        $sc->setVars($v);
        $estText .= $tp->parseTemplate($tmpl['list']['item'], false, $sc);
        }
      }
    $estText .= '</div>';
    if($EST_PREF['layout_list_map'] == 2){$estText .= $tp->parseTemplate($tmpl['list']['map'], false, $sc);}
    //$estText .= $tp->parseTemplate($tmpl['end'], false);
    
    $estText .= $tp->parseTemplate($tmpl['list']['elnk'], false, $sc);
    
    $ns->tablerender('<span id="estMiniNav"></span>'.$estHead,$estText,'estate-list');
    unset($estHead,$estText,$EST_PROP);
    }
  }
  
echo '
<div id="estJSpth"'.$OACLASS.' data-pth="'.EST_PATHABS.'"></div>
<div id="estMobTst"></div>';

require_once(FOOTERF);
exit;






function estViewImgCSS($cssName,$gal,$actv,$stime,$sdelay){
  global $EST_PREF;
  $CSS = array();
  $galCt = count($gal);
  if($galCt == 0){
    $CSS[0] = '#estViewBoxTop{background-image: url("'.EST_PATHABS_IMAGES.'imgnotavail.png");}';
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
  $CSSTOP = estViewImgCSS('#estViewBoxTop',$ESTDTA[0],$EST_PREF['slideshow_act'],$EST_PREF['slideshow_time'],$EST_PREF['slideshow_delay']);
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



