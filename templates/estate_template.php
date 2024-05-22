<?php
/** Estate Plugin Template File 
 * 
 * Template Array: 
 * Pages: 'view','list','menu' (each Page has its own Pref setting)
 * 
 * View Template:
 * Sections: 'slideshow','summary','spaces','map','comminuty','gallery'
 * $ESTATE_TEMPLATE['view']['mylayout']['name'] = 'My Layout View Template'; [used for Layout Select in Estate Preferences]
 * $ESTATE_TEMPLATE['view']['mylayout']['ord'] = array('slideshow','summary','spaces','map','comminuty','gallery'); [Optional default order, can be changed in Prefs]
 * $ESTATE_TEMPLATE['view']['mylayout']['txt']['summary'] = '<div class="mySpecialClass">{SHORTCODE}</div>';
 * NOTE: 
 *    Can be Fixed Layout with Order: $ESTATE_TEMPLATE['view']['mylayout']['txt'] .= '<div>{SHORTCODE}</div>';
 *    or sectional Order *$ESTATE_TEMPLATE['view']['mylayout']['ord'] If present, Estate Preferences allows admin to change section order for selected template 
 * 
 * List Template:
 * $ESTATE_TEMPLATE['list']['mylayout']['name'] = 'My Layout List Template'; //used for Layout Select in Estate Preferences
 * $ESTATE_TEMPLATE['list']['mylayout']['txt'] = '<div>{SHORTCODE}</div>';
 * 
 * $ESTATE_TEMPLATE['menu']['mylayout']['name'] = 'My Layout Menu Template'; //used for Layout Select in Estate Preferences
 * $ESTATE_TEMPLATE['menu']['default']['txt'][0]
 * 
 * 
 *
 **/


if (!defined('e107_INIT')) { exit; }
// Estate Template file:  default layout

global $sc,$EST_PREF,$EST_PROP,$EST_SPACES,$IDIV;
$ns = e107::getRender();
$tp = e107::getParser();

$EST_MENU1 = e107::getMenu()->isLoaded('estate');


$ESTATE_TEMPLATE = array();

//$EST_VIEW_ORDER = array('slideshow','summary','spaces','map','comminuty','nearby','gallery');










// LIST TEMPLATES
if(!isset($EST_LIST_CAP)){
  $EST_LIST_CAP = '
  <div class="estCardTop">
    {PROP_LIST_BANNER}
    {PROP_EDITICONS:for=card}
    <a href="{PROP_LIST_LINKVIEW}">
      <div class="estCardTopName">{PROP_NAME}</div>
      <div>{PROP_CITYSTATE}</div>
      <div>{PROP_BULLETS1}</div>
      <div>{PROP_VIEWCOUNT}</div>
    </a>
  </div>';
  // • {PROP_VIEWCOUNT}
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




//VIEW TEMPLATEs

if(!isset($EST_VIEW_SLIDESHOW)){
  $EST_VIEW_SLIDESHOW = '
  <div id="estSlideShow" class="'.($EST_PREF['slideshow_act'] == 1 ? ' estSlideshow" title="'.EST_GEN_PLAYPAUSESLIDE.'"' : '"').'>
    <div class="estSSPlayPause"></div>
  </div>';
  }

if(!isset($EST_VIEW_MENU)){
  $EST_VIEW_MENU = '
        <div class="WD100 noPADTB">
          <h3 class="sumCapt">{PROP_STATUS}</h3>
          <div><span class="FR">{PROP_VIEWCOUNT}</span>{PROP_PRICE}</div>
          <div>{PROP_MODELNAME}</div>
        </div>
        <div class="estFLEXCol WD100 noPADTB">{PROP_AGENTCARD:img=1}</div>
        <div class="WD100 noPADTB">{PROP_EVENTS}</div>';
        //{PROP_EVENTS:as=ul}
  }


if(!isset($EST_VIEW_SUMMARY)){
  $EST_VIEW_SUMMARY = '
      <h4>{PROP_NAME}</h4>
      {PROP_FEATURES}
      <p>{PROP_SUMMARY}</p>
      <p>{PROP_DESCRIPTION}</p>';
  }



if(!isset($EST_VIEW_FEATURES)){
  $EST_VIEW_FEATURES = '
        <div id="estInfoModule1">
          <div class="estInfoCard">
            {PROP_POINTS}
          </div>
          <div class="estInfoCard">
            {PROP_FEATURE_EXTENDED}
          </div>
        </div>';
  }

if(!isset($EST_VIEW_OVERVIEW)){
  $EST_VIEW_OVERVIEW = $EST_VIEW_SUMMARY.'<div><h4>'.EST_GEN_FEATURES.'</h4>'.$EST_VIEW_FEATURES.'</div>';
  }
  

if(!isset($EST_VIEW_SPACES_DYNAMIC)){
  $EST_VIEW_SPACES_DYNAMIC = '
        <div id="estSpacesCont" class="estSpaceDynamic'.($EST_PREF['slideshow_act'] == 1 ? ' estSlideshow' : '').'">
          <div id="estViewSpacePvwCont">
            {PROP_SPACEPVW}
          </div>
          <div id="estViewSpaceBtnCont">
            <div id="estViewSpaceBtns">
              {PROP_VIEWSPACEBTNS}
            </div>
          </div>
        </div>';
  }

if(!isset($EST_VIEW_SPACES_TILES)){
  $EST_VIEW_SPACES_TILES = '
          <div id="estSpacesCont" class="'.($EST_PREF['slideshow_act'] == 1 ? ' estSlideshow' : '').' WD100">
              <div id="estViewSpaceBtnCont">
                {VIEW_SPACESTABLE}
              </div>
          </div>';
  }


if(!isset($EST_VIEW_NXPR)){
  $EST_VIEW_NXPR = '
        <div class="WD100">
            {PROP_NPLISTING}
        </div>';
  }



if(!isset($EST_MENU_MAIN)){
  $EST_MENU_MAIN = '
        <div class="noPADTB">
          <h3 class="sumCapt">{PROP_STATUS}</h3>
          <div><span class="FR">{PROP_VIEWCOUNT}</span>{PROP_PRICE}</div>
          <div>{PROP_MODELNAME}</div>
        </div>';
  }
  



/**
 * Below are the actual templates loaded by the listings.php and estate_menu.php pages
 * 
 *
 *
 **/
 
$ns->setStyle('menu'); 


//VIEW TEMPLATES

$ESTATE_TEMPLATE['view']['alternate1']['name'] = 'Alternate 1';
$ESTATE_TEMPLATE['view']['alternate1']['txt'] = "
<div class='WD100'>{EST_LEAFLET_MAP}</div>
<div class='WD100'>$EST_VIEW_SUMMARY</div>
<div class='WD100'>$EST_VIEW_FEATURES</div>
<div class='WD100'>$EST_VIEW_SPACES_TILES</div>
<div class='WD100'>{EST_VIEW_GALLERY}</div>";

$ESTATE_TEMPLATE['view']['basic']['name'] = 'Basic';
$ESTATE_TEMPLATE['view']['basic']['txt'] = "
<div class='WD100'>$EST_VIEW_SLIDESHOW</div>
<div class='WD100'>$EST_VIEW_SUMMARY</div>
<div class='WD100'>$EST_VIEW_SPACES_TILES</div>
<div class='WD100'>$EST_VIEW_FEATURES</div>";



$ESTATE_TEMPLATE['view']['default']['name'] = EST_PREF_TEMPLATESDEF[1];
$ESTATE_TEMPLATE['view']['default']['ord'] = array('slideshow','summary','spaces','map','comminuty','nearby','gallery');

//SlideShow Section
$ESTATE_TEMPLATE['view']['default']['txt']['slideshow'] = $EST_VIEW_SLIDESHOW;

// Summary Section
$ESTATE_TEMPLATE['view']['default']['txt']['summary'] = '<div class="estFLEXCont flexRev">';
// include Summary Menu if Theme layout is 'full' and no est_menu.php loaded
if($EST_MENU1 == 0 || strpos(strtolower(THEME_LAYOUT),'full') !== false){
  $CAPT = $tp->parseTemplate('{PROP_NAME}{PROP_MENU_HEAD}', false, $sc);
  $TXT = $tp->parseTemplate($EST_VIEW_MENU, false, $sc);
  $ESTATE_TEMPLATE['view']['default']['txt']['summary'] .= '<div class="estSummaryMenu">'.$ns->tablerender($CAPT,$TXT,'summary-table',true).'</div>';
  unset($CAPT,$TXT);
  define("ESTAGENTRENDERED",1);
  }

$ESTATE_TEMPLATE['view']['default']['txt']['summary'] .= '<div class="estSummaryMain"><div class="WD100">';
//$TXT = $tp->parseTemplate($EST_VIEW_OVERVIEW, false, $sc);
//$ESTATE_TEMPLATE['view']['default']['txt']['summary'] .= $ns->tablerender(EST_GEN_OVERVIEW,$TXT,'overview-table',true);
$TXT = $tp->parseTemplate($EST_VIEW_SUMMARY, false, $sc);
$ESTATE_TEMPLATE['view']['default']['txt']['summary'] .= $ns->tablerender(EST_GEN_OVERVIEW,$TXT,'menu',true);
$TXT = $tp->parseTemplate($EST_VIEW_FEATURES, false, $sc);
$ESTATE_TEMPLATE['view']['default']['txt']['summary'] .= $ns->tablerender(EST_GEN_FEATURES,$TXT,'menu',true);
$ESTATE_TEMPLATE['view']['default']['txt']['summary'] .= '</div></div></div>';



if(strpos(strtolower(THEME_LAYOUT),'full') > -1){
  $TXT = $tp->parseTemplate($EST_VIEW_SPACES_DYNAMIC, false, $sc);
  $ESTATE_TEMPLATE['view']['default']['txt']['spaces'] = '<div class="WD100">'.$ns->tablerender(EST_GEN_SPACES,$TXT,'dynamic-spaces',true).'</div>';
  }
else{
  $ESTATE_TEMPLATE['view']['default']['txt']['spaces'] = $tp->parseTemplate($EST_VIEW_SPACES_TILES, false, $sc);
  }


$ESTATE_TEMPLATE['view']['default']['txt']['map'] = '<div class="WD100">'.$ns->tablerender(EST_GEN_MAP,'{EST_LEAFLET_MAP}','map-section',true).'</div>';

$ESTATE_TEMPLATE['view']['default']['txt']['nearby'] = $tp->parseTemplate($EST_VIEW_NXPR, false, $sc);

$ESTATE_TEMPLATE['view']['default']['txt']['gallery'] = '<div class="WD100">'.$ns->tablerender(EST_GEN_IMAGE.' '.EST_GEN_GALLERY,'{EST_VIEW_GALLERY}','image-gallery',true).'</div>';











$ESTATE_TEMPLATE['list']['default']['name'] = EST_PREF_TEMPLATESDEF[0];
$ESTATE_TEMPLATE['list']['default']['txt'] = '{EST_LEAFLET_MAP}';

$ESTATE_TEMPLATE['list']['default']['txt'] .= '<div class="estCardCont">';

if(count($EST_PROP) > 0){
  $slc = e107::getScBatch('estate',true);
  foreach($EST_PROP as $k=>$v){
    $slc->setVars($v);
    $CAPT = $tp->parseTemplate($EST_LIST_CAP, false, $slc);
    $TXT = $tp->parseTemplate($EST_LIST_TXT, false, $slc);
    $ESTATE_TEMPLATE['list']['default']['txt'] .= '<div class="card estCard">'.$ns->tablerender($CAPT,$TXT,'list-card-'.$k,true).'</div>';
    unset($CAPT,$TXT);
    }
  }

$ESTATE_TEMPLATE['list']['default']['txt'] .= '</div>';
  
$ESTATE_TEMPLATE['list']['default']['txt'] .= '<div id="estMiniSrc">{PROP_NEWICON}</div>';



//MENU TEMPLATES
$ESTATE_TEMPLATE['menu']['default']['name'] = EST_PREF_TEMPLATESDEF[2];
$CAPT = $tp->parseTemplate('{PROP_NAME}{PROP_MENU_HEAD}', false, $sc);
$TXT = $tp->parseTemplate($EST_MENU_MAIN, false, $sc);
$ESTATE_TEMPLATE['menu']['default']['txt'][0] = $ns->tablerender($CAPT, $TXT, 'estSideMenuTitle',true);

$CAPT = $tp->parseTemplate('{AGENT_ROLL}', false, $sc);
$TXT = $tp->parseTemplate('{PROP_AGENTCARD:img=1}', false, $sc);
$ESTATE_TEMPLATE['menu']['default']['txt'][1] =  $ns->tablerender($CAPT, $TXT,'estSideMenuSeller',true);

//$TXT = $tp->parseTemplate('{PROP_EVENTS:as=ul}', false, $sc);
//$ESTATE_TEMPLATE['menu']['default']['txt'][2] = $ns->tablerender(EST_GEN_EVENTS, $TXT, 'estSideMenuEvents',true);
$ESTATE_TEMPLATE['menu']['default']['txt'][2] = $tp->parseTemplate('{PROP_EVENTS}', false, $sc);

$TXT = $tp->parseTemplate('{PROP_SAVED_LIST}', false, $sc);
$ESTATE_TEMPLATE['menu']['default']['txt'][3] = $ns->tablerender(EST_GEN_SAVEDLISTINGS, $TXT, 'estSideMenuSaved',true);

$TXT = $tp->parseTemplate('{SPACES_MENU}', false, $sc);
if(trim($TXT) !== ''){
  $ESTATE_TEMPLATE['menu']['default']['txt'][4] = $ns->tablerender(EST_GEN_SPACES, $TXT, 'estSideMenuSpaces',true);
  }

unset($CAPT,$TXT);
unset($EST_LIST_CAP,$EST_LIST_TXT);

