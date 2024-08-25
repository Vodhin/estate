<?php
/** Estate Plugin Template File 
 *
 *  Templates must use $ESTATE_TEMPLATE['area']['layout_name']['txt'] format - see below
 * 
 * Template Array: 
 *    $ESTATE_TEMPLATE['area'] = ('view','list', or 'menu');
 *    
 *  Use 'name' to Identify Layout in Estate Prefs: 
 *    $ESTATE_TEMPLATE['view']['mylayout']['name'] = 'My Layout View Template'; 
 *    
 *  Use 'txt' for the actual template, can be Fixed or array();
 *    $ESTATE_TEMPLATE['view']['mylayout']['txt'] = '<div>{SHORTCODE1}</div><div>{SHORTCODE2}</div>';
 * 
 *  View and Menu areas can be Fixed or Array
 *    Fixed Layout: 
 *    $ESTATE_TEMPLATE['view']['mylayout']['txt'] = '<div>{SHORTCODE1}</div><div>{SHORTCODE2}</div>';
 *    $ESTATE_TEMPLATE['menu']['mylayout']['txt'] = '<div>{SHORTCODE3}</div><div>{SHORTCODE4}</div>';
 *    
 *    Hard Coded Array:
 *    $ESTATE_TEMPLATE['view']['mylayout']['txt'][0] = '<div>{SHORTCODE1}</div>';
 *    $ESTATE_TEMPLATE['view']['mylayout']['txt'][1] = '<div>{SHORTCODE2}</div>';
 *    
 *    $ESTATE_TEMPLATE['menu']['mylayout']['txt'][0] = '<div>{SHORTCODE3}</div>';
 *    $ESTATE_TEMPLATE['menu']['mylayout']['txt'][1] = '<div>{SHORTCODE4}</div>';
 *    
 *    Named Array (re-orderable): 
 *    $ESTATE_TEMPLATE['view']['mylayout']['ord'] = array('section_1_name','section_2_name');
 *    $ESTATE_TEMPLATE['view']['mylayout']['txt']['section_1_name'] = '<div>{SHORTCODE1}</div>';
 *    $ESTATE_TEMPLATE['view']['mylayout']['txt']['section_2_name'] = '<div>{SHORTCODE2}</div>';
 *    
 *    $ESTATE_TEMPLATE['menu']['mylayout']['ord'] = array('menu_1_name','menu_2_name');
 *    $ESTATE_TEMPLATE['menu']['mylayout']['txt']['menu_1_name'] = '<div>{SHORTCODE3}</div>';
 *    $ESTATE_TEMPLATE['menu']['mylayout']['txt']['menu_2_name'] = '<div>{SHORTCODE4}</div>';
 *    
 *    Estate Main Admin can change order of Named Array templates via Estate Prefs and through front end 
 *    Changes affect all Listings
 *    
 *  List Template does NOT support 'txt' as an Array
 *    List Template:
 *    $ESTATE_TEMPLATE['list']['mylayout']['name'] = 'My Layout List Template'; //used for Layout Select in Estate Preferences
 *    $ESTATE_TEMPLATE['list']['mylayout']['txt'] = '<div>{SHORTCODE}</div>';
 *    foreach($data as $k=>$v){
 *      $ESTATE_TEMPLATE['list']['mylayout']['txt'] .= '<div>{SHORTCODE}</div>';
 *      } 
 *    
 * 
 * 
 * 
 *
 **/


if (!defined('e107_INIT')) { exit; }
// Estate Template file:  default layout

global $sc,$EST_PREF,$EST_PROP,$EST_SPACES,$IDIV;
$ns = e107::getRender();

$estLoadSidebar = e107::getMenu()->isLoaded('estate_sidebar');
$estLoadInbox = e107::getMenu()->isLoaded('estate_inbox');
$ESTATE_TEMPLATE = array();




// THEMEABLE LIST TEMPLATES
if(!isset($EST_LIST_CAP)){
  $EST_LIST_CAP = '
  <div class="estCardTop">
    {PROP_LIST_BANNER}
    <div class="estCardTopIcons">{PROP_LIKE_ICON:for=card}{PROP_EDITICONS:for=card}</div>
    <a href="{PROP_LIST_LINKVIEW}">
      <div class="estCardTopName">{PROP_NAME}</div>
      <div>{PROP_CITYSTATE}</div>
      <div>{PROP_BULLETS1}</div>
      <div>{PROP_LIKESVIEWS}</div>
    </a>
  </div>';
  // or  {PROP_LIKES} {PROP_VIEWCOUNT}
  }
  
if(!isset($EST_LIST_TXT)){
  $EST_LIST_TXT = '
  <div class="estCardMain">
    <a href="{PROP_LIST_LINKVIEW}">
      <div class="estCardImg" style="{PROP_THMSTY}"></div>
      <div class="estILBullets">
        <div class="FSITAL">{PROP_MODELNAME}</div>
        {PROP_FEATURES}
        {PROP_SUMMARY}
      </div>
    </a>
    {PROP_AGENTMINI:for=card}
  </div>';
  }




//THEMEABLE VIEW TEMPLATES
if(!isset($EST_VIEW_SUMMARY)){
  $EST_VIEW_SUMMARY = '
  <h4>{PROP_NAME}</h4>
  {PROP_FEATURES}
  <p>{PROP_SUMMARY}</p>
  <p class="DTH256">{PROP_DESCRIPTION}</p>';
  }

if(!isset($EST_VIEW_SUMMARY_SIDEBAR)){
  $EST_VIEW_SUMMARY_SIDEBAR = '
  <div class="WD100 noPADTB">
    <h3 class="sumCapt">{PROP_STATUS}</h3>
    <div><span class="FR">{PROP_VIEWCOUNT}</span>{PROP_PRICE}</div>
    <div>{PROP_LIKES:bullet=right}{PROP_MODELNAME}</div>
  </div>
  <div class="estFLEXCol WD100 noPADTB">{PROP_AGENTCARD:img=1}</div>
  <div class="WD100 noPADTB">{PROP_EVENTS}</div>';
  //{PROP_EVENTS:as=ul}
  }

if(!isset($EST_VIEW_FEATURES)){
  $EST_VIEW_FEATURES = '
  <div class="estFLEXCont">
    <div class="estInfoCard estFLEX45">
      {PROP_POINTS}
    </div>
    <div class="estInfoCard DTH256 estFLEX45">
      {PROP_FEATURE_EXTENDED}
    </div>
  </div>
  <div>
    <div class="estInfoCard">
    {PROP_HOA}
    </div>
  </div>';
  }




//NOT ready yet...
if(!isset($EST_VIEW_NXPR)){
  $EST_VIEW_NXPR = '
  <div class="WD100">
      {PROP_NPLISTING}
  </div>';
  }




// THEMEABLE MENU TEMPLATE (may be called by View, too)
if(!isset($EST_MENU_MAIN)){
  $EST_MENU_MAIN = '
  <div class="noPADTB">
    <h3 class="sumCapt">{PROP_STATUS}</h3>
    <div><span class="FR">{PROP_LIKES:bullet=right}{PROP_VIEWCOUNT}</span>{PROP_PRICE}</div>
    <div>{PROP_MODELNAME}</div>
  </div>';
  }

if(!isset($EST_MENU_BASIC)){
  $EST_MENU_BASIC = '
  <div class="WD100">
    <div class="noPADTB">
      <h3 class="sumCapt">{PROP_STATUS}</h3>
      <div><span class="FR">{PROP_VIEWCOUNT}</span>{PROP_PRICE}</div>
      <div>{PROP_MODELNAME}{PROP_LIKES}</div>
    </div>
  </div>
  <div class="WD100"><h4>{AGENT_ROLL}</h4>{PROP_AGENTCARD:img=1}</div>
  <div class="WD100">{PROP_EVENTS}</div> 
  <div class="WD100">{PROP_SAVED_LIST}</div>';
  }



/**
 * Below are the actual templates loaded by the listings.php and estate_menu.php pages
 **/
 
$ns->setStyle('menu'); 


//VIEW ALTERNATE 1
$ESTATE_TEMPLATE['view']['alternate1']['name'] = 'Alternate 1';
$ESTATE_TEMPLATE['view']['alternate1']['txt'] = "
<div class='WD100'>{EST_LEAFLET_MAP}</div>
<div class='WD100'>$EST_VIEW_SUMMARY</div>
<div class='WD100'>$EST_VIEW_FEATURES</div>
<div class='WD100'>{VIEW_SPACES}</div>
<div class='WD100'>{PROP_COMMUNITY}</div>
<div class='WD100'>{EST_VIEW_GALLERY}</div>";


//VIEW BASIC
$ESTATE_TEMPLATE['view']['basic']['name'] = 'Basic';
$ESTATE_TEMPLATE['view']['basic']['txt'] = "
<div class='WD100'>{EST_SLIDESHOW_TOP}</div>
<div class='WD100'>$EST_VIEW_SUMMARY</div>
<div class='WD100'>$EST_VIEW_FEATURES</div>
<div class='WD100'>{VIEW_SPACES}</div>";


//VIEW DEFAULT with re-orderable sections and Tile Spaces layout
$ESTATE_TEMPLATE['view']['default']['name'] = 'Default View Layout';
$ESTATE_TEMPLATE['view']['default']['ord'] = array('slideshow','summary','spaces','map','comminuty','nearby','gallery');
$ESTATE_TEMPLATE['view']['default']['txt']['slideshow'] = '{EST_SLIDESHOW_TOP}';
$ESTATE_TEMPLATE['view']['default']['txt']['summary'] = '<div class="estFLEXCont flexRev">';
if($estLoadSidebar == 0 || strpos(strtolower(THEME_LAYOUT),'full') !== false){
  $ESTATE_TEMPLATE['view']['default']['txt']['summary'] .= '<div class="estSummaryMenu">'.$ns->tablerender('{PROP_NAME}{PROP_LIKE_ICON}',$EST_VIEW_SUMMARY_SIDEBAR,'summary-table',true).'</div>';
  define("ESTAGENTRENDERED",1);
  }
$ESTATE_TEMPLATE['view']['default']['txt']['summary'] .= '<div class="estSummaryMain"><div class="WD100">';
//$ESTATE_TEMPLATE['view']['default']['txt']['summary'] .= $ns->tablerender(EST_GEN_OVERVIEW,$EST_VIEW_SUMMARY,'overview-table',true);
$ESTATE_TEMPLATE['view']['default']['txt']['summary'] .= $ns->tablerender(EST_GEN_OVERVIEW,$EST_VIEW_SUMMARY,'overview-sect',true);
$ESTATE_TEMPLATE['view']['default']['txt']['summary'] .= $ns->tablerender(EST_GEN_FEATURES,$EST_VIEW_FEATURES,'features-sect',true);
$ESTATE_TEMPLATE['view']['default']['txt']['summary'] .= '</div></div></div>';

$ESTATE_TEMPLATE['view']['default']['txt']['spaces'] = '{VIEW_SPACES}';
$ESTATE_TEMPLATE['view']['default']['txt']['map'] = '<div class="WD100">'.$ns->tablerender(EST_GEN_MAP,'{EST_LEAFLET_MAP}','map-section',true).'</div>';
$ESTATE_TEMPLATE['view']['default']['txt']['comminuty'] = '{PROP_COMMUNITY_SECT}';

$ESTATE_TEMPLATE['view']['default']['txt']['nearby'] = $EST_VIEW_NXPR;
$ESTATE_TEMPLATE['view']['default']['txt']['gallery'] = '<div class="WD100">'.$ns->tablerender(EST_GEN_IMAGE.' '.EST_GEN_GALLERY,'{EST_VIEW_GALLERY}','image-gallery',true).'</div>';




//VIEW DYNAMIC with re-orderable sections and Dynamic Spaces layout
$ESTATE_TEMPLATE['view']['dynamic']['name'] = 'Default with Dynamic Spaces';
$ESTATE_TEMPLATE['view']['dynamic']['ord'] = array('slideshow','summary','spaces','map','comminuty','nearby','gallery');
$ESTATE_TEMPLATE['view']['dynamic']['txt']['slideshow'] = '{EST_SLIDESHOW_TOP}';
$ESTATE_TEMPLATE['view']['dynamic']['txt']['summary'] = '<div class="estFLEXCont flexRev">';
if($estLoadSidebar == 0 || strpos(strtolower(THEME_LAYOUT),'full') !== false){
  $ESTATE_TEMPLATE['view']['dynamic']['txt']['summary'] .= '<div class="estSummaryMenu">'.$ns->tablerender('{PROP_NAME}{PROP_LIKE_ICON}',$EST_VIEW_SUMMARY_SIDEBAR,'summary-table',true).'</div>';
  define("ESTAGENTRENDERED",1);
  }
$ESTATE_TEMPLATE['view']['dynamic']['txt']['summary'] .= '<div class="estSummaryMain"><div class="WD100">';
$ESTATE_TEMPLATE['view']['dynamic']['txt']['summary'] .= $ns->tablerender(EST_GEN_OVERVIEW,$EST_VIEW_SUMMARY,'overview-sect',true);
$ESTATE_TEMPLATE['view']['dynamic']['txt']['summary'] .= $ns->tablerender(EST_GEN_FEATURES,$EST_VIEW_FEATURES,'features-sect',true);
$ESTATE_TEMPLATE['view']['dynamic']['txt']['summary'] .= '</div></div></div>';
$ESTATE_TEMPLATE['view']['dynamic']['txt']['spaces'] = '{VIEW_SPACES:dynamic=1}';
$ESTATE_TEMPLATE['view']['dynamic']['txt']['map'] = '<div class="WD100">'.$ns->tablerender(EST_GEN_MAP,'{EST_LEAFLET_MAP}','map-section',true).'</div>';
$ESTATE_TEMPLATE['view']['dynamic']['txt']['comminuty'] = '{PROP_COMMUNITY_SECT}';
$ESTATE_TEMPLATE['view']['dynamic']['txt']['nearby'] = $EST_VIEW_NXPR;
$ESTATE_TEMPLATE['view']['dynamic']['txt']['gallery'] = '<div class="WD100">'.$ns->tablerender(EST_GEN_IMAGE.' '.EST_GEN_GALLERY,'{EST_VIEW_GALLERY}','image-gallery',true).'</div>';





//LIST LAYOUTS

//LIST DEFAULT LAYOUT
$ESTATE_TEMPLATE['list']['default']['name'] = 'Default List Layout';
if($estLoadSidebar == 0 || strpos(strtolower(THEME_LAYOUT),'full') !== false){
  $ESTATE_TEMPLATE['list']['default']['txt'] = '';
  }
$ESTATE_TEMPLATE['list']['default']['txt'] .= '{EST_LEAFLET_MAP}';
$ESTATE_TEMPLATE['list']['default']['txt'] .= '<div class="estCardCont">';


if($EST_PROP && count($EST_PROP) > 0){
  $tp = e107::getParser();
  $slc = e107::getScBatch('estate',true);
  foreach($EST_PROP as $k=>$v){
    $slc->setVars($v);
    $capt = $tp->parseTemplate($EST_LIST_CAP, false, $slc);
    $text = $tp->parseTemplate($EST_LIST_TXT, false, $slc);
    $ESTATE_TEMPLATE['list']['default']['txt'] .= '<div class="card estCard">'.$ns->tablerender($capt,$text,'list-card-'.$k,true).'</div>';
    unset($capt,$text);
    }
  }
$ESTATE_TEMPLATE['list']['default']['txt'] .= '</div>';


//MENU LAYOUTS

//MENU DEFAULT LAYOUT
$ESTATE_TEMPLATE['menu']['default']['name'] = 'Default Sidebar Layout';
$ESTATE_TEMPLATE['menu']['default']['ord'] = array('top','agent','saved','events','spaces');
$ESTATE_TEMPLATE['menu']['default']['txt']['top'] = $ns->tablerender('{PROP_NAME}{PROP_LIKE_ICON}', $EST_MENU_MAIN, 'estSideMenuTitle',true);
$ESTATE_TEMPLATE['menu']['default']['txt']['agent'] =  $ns->tablerender('{AGENT_ROLL}', '{PROP_AGENTCARD:img=1}','estSideMenuSeller',true);
$ESTATE_TEMPLATE['menu']['default']['txt']['events'] = '{PROP_EVENTS}';
$ESTATE_TEMPLATE['menu']['default']['txt']['saved'] = $ns->tablerender(EST_GEN_SAVEDLISTINGS, '{PROP_SAVED_LIST}', 'estSideMenuSaved',true);
$ESTATE_TEMPLATE['menu']['default']['txt']['spaces'] = '{SPACES_MENU}';


//MENU BASIC LAYOUT
$ESTATE_TEMPLATE['menu']['basic']['name'] = 'Basic';
$ESTATE_TEMPLATE['menu']['basic']['txt'] = $ns->tablerender('{PROP_NAME}{PROP_LIKE_ICON}', $EST_MENU_BASIC, 'estSideMenuBasic',true);
unset($TXT);



unset($EST_LIST_CAP,$EST_LIST_TXT,$EST_VIEW_SUMMARY,$EST_VIEW_SUMMARY_SIDEBAR,$EST_VIEW_FEATURES,$EST_VIEW_NXPR,$EST_MENU_MAIN,$EST_MENU_BASIC);