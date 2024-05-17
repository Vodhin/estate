<?php
/* Estate Plugin Template File 
 * 
 * To Modify: 
 * $ESTATE_TEMPLATE['view']['mylayout']['tempname'] = 'My Layout View Template'; //used for Layout Select in Estate Preferences
 * $ESTATE_TEMPLATE['view']['mylayout']['txt'][0] = '<div class="mySpecialClass">{SHORTCODE}</div>'; //['txt'][0] ordering is implemented in listing.php 
 * 
 * 
 */
if (!defined('e107_INIT')) { exit; }
// Estate Template file:  default layout

global $sc,$EST_PREF,$EST_PROP,$EST_SPACES,$IDIV;
$ns = e107::getRender();
$tp = e107::getParser();

$EST_MENU1 = e107::getMenu()->isLoaded('estate');


$ESTATE_TEMPLATE = array();



//VIEW TEMPLATE
if(!isset($EST_VIEW_SUMMARY)){
  $EST_VIEW_SUMMARY = '
      <div class="estViewSect">
        <h4>{PROP_NAME}</h4>
        {PROP_FEATURES}
        <p>{PROP_SUMMARY}</p>
        <p>{PROP_DESCRIPTION}</p>
      </div>';
  }



if(!isset($EST_VIEW_FEATURES)){
  $EST_VIEW_FEATURES = '
      <div class="estViewSect">
        <div id="estInfoModule1">
          <div class="estInfoCard">
            {PROP_POINTS}
          </div>
          <div class="estInfoCard">
            {PROP_FEATURE_EXTENDED}
          </div>
        </div>
      </div>';
  }


  

if(!isset($EST_VIEW_SPACES_DYNAMIC)){
  $EST_VIEW_SPACES_DYNAMIC = '
        <div id="estViewBoxSpacesCont" class=" estSpaceDynamic'.($EST_PREF['slideshow_act'] == 1 ? ' estSlideshow' : '').'">
          <div id="estViewSpacePvwCont">
            {PROP_SPACEPVW}
          </div>
          <div id="estViewSpaceBtnCont">
            <div id="estViewSpaceBtns" class="estViewSect">
              {PROP_VIEWSPACEBTNS}
            </div>
          </div>
        </div>';
  }

if(!isset($EST_VIEW_SPACES_TILES)){
  $EST_VIEW_SPACES_TILES = '
          <div id="estViewBoxSpacesCont" class=" '.($EST_PREF['slideshow_act'] == 1 ? ' estSlideshow' : '').' WD100">
              <div id="estViewSpaceBtnCont">
                {VIEW_SPACESTABLE}
              </div>
          </div>';
  }


  
  
  

  

if(!isset($EST_VIEW_NXPR)){
  $EST_VIEW_NXPR = '
        <div id="estViewNxtPre" class="estViewBoxBot WD100">
          <div class="estViewSect">
            {PROP_NPLISTING}
          </div>
        </div>';
  }





/*
 * SIDE BAR ELEMENTS:
 * used in estate_menu.php (if NOT full width) OR listings.php?view (if full width)
 * desgned to be parsed and rentered via $ns->tablerender
 *
 */

$ESTATE_TEMPLATE['menu']['title']['cap'] = '<div id="estMenuPropTop">{PROP_NAME}{PROP_MENU_HEAD}</div>';
$ESTATE_TEMPLATE['menu']['title']['txt'] = '
        <div class="estViewSect noPADTB">
          <div class="estViewMod">
            <h3 class="sumCapt">{PROP_STATUS}</h3>
            <div><span class="FR">{PROP_VIEWCOUNT}</span>{PROP_PRICE}</div>
            <div>{PROP_MODELNAME}</div>
          </div>
        </div>';


$ESTATE_TEMPLATE['menu']['seller']['cap'] = '<div>{AGENT_ROLL}</div>';
$ESTATE_TEMPLATE['menu']['seller']['txt'] = '<div class="estAgntModule noPADTB">{PROP_AGENTCARD:img=1}</div>';

$ESTATE_TEMPLATE['menu']['events']['txt'] = '<div id="estEventList">{PROP_VIEW_OPENHOUSE}</div>';

$ESTATE_TEMPLATE['menu']['spaces']['cap'] = '<div>'.EST_GEN_SPACES.'</div>';
$ESTATE_TEMPLATE['menu']['spaces']['txt'] = '<div id="estSpacesMenu">{SPACES_MENU}</div>';


$ESTATE_TEMPLATE['menu']['saved']['txt'] = '<div id="estSavedPropList">{PROP_SAVED_LIST}</div>';






$ns->setStyle('menu');
$ESTATE_TEMPLATE['view']['default']['tempname'] = 'Default View Template'; //used for Layout Select in Estate Preferences

//SlideShow Section
$ESTATE_TEMPLATE['view']['default']['txt'][0] = '
  <div id="estSlideShow" class="'.($EST_PREF['slideshow_act'] == 1 ? ' estSlideshow" title="'.EST_GEN_PLAYPAUSESLIDE.'"' : '"').'>
    <div class="estSSPlayPause"></div>
  </div>';


// Summary Section
$ESTATE_TEMPLATE['view']['default']['txt'][1] = '<div class="estFLEXCont flexRev flexStretch">';
// include Summary Menu if Theme layout is 'full' and no est_menu.php loaded
if($EST_MENU1 == 0 || strpos(strtolower(THEME_LAYOUT),'full') !== false){
  $CAPT = $tp->parseTemplate($ESTATE_TEMPLATE['menu']['title']['cap'], false, $sc);
  $TXT = $tp->parseTemplate($ESTATE_TEMPLATE['menu']['title']['txt'], false, $sc);
  $TXT .= $tp->parseTemplate($ESTATE_TEMPLATE['menu']['seller']['txt'], false, $sc);
  $TXT .= $tp->parseTemplate($ESTATE_TEMPLATE['menu']['events']['txt'], false, $sc);
  
  $ESTATE_TEMPLATE['view']['default']['txt'][1] .= '<div class="estSummaryMenu">'.$ns->tablerender($CAPT,$TXT,'menu',true).'</div>';
  unset($CAPT,$TXT);
  define("ESTAGENTRENDERED",1);
  }

$ESTATE_TEMPLATE['view']['default']['txt'][1] .= '<div class="estSummaryMain"><div class="WD100">';
$TXT = $tp->parseTemplate($EST_VIEW_SUMMARY, false, $sc);
$ESTATE_TEMPLATE['view']['default']['txt'][1] .= $ns->tablerender(EST_GEN_OVERVIEW,$TXT,'menu',true);
$TXT = $tp->parseTemplate($EST_VIEW_FEATURES, false, $sc);
$ESTATE_TEMPLATE['view']['default']['txt'][1] .= $ns->tablerender(EST_GEN_FEATURES,$TXT,'menu',true);
$ESTATE_TEMPLATE['view']['default']['txt'][1] .= '</div></div></div>';



if(strpos(strtolower(THEME_LAYOUT),'full') > -1){
  $TXT = $tp->parseTemplate($EST_VIEW_SPACES_DYNAMIC, false, $sc);
  //$ESTATE_TEMPLATE['view']['default'][0] .= $ns->tablerender(EST_GEN_SPACES,$TXT,'menu',true);
  $ESTATE_TEMPLATE['view']['default']['txt'][2] .= $tp->parseTemplate($EST_VIEW_SPACES_TILES, false, $sc);
  }
else{
  $ESTATE_TEMPLATE['view']['default']['txt'][2] .= $tp->parseTemplate($EST_VIEW_SPACES_TILES, false, $sc);
  }


$ESTATE_TEMPLATE['view']['default']['txt'][3] .= '<div class="WD100">'.$ns->tablerender(EST_GEN_MAP,'{EST_LEAFLET_MAP}','menu',true).'</div>';


//$ESTATE_TEMPLATE['view']['default'][0] .= $tp->parseTemplate($EST_VIEW_NXPR, false, $sc);

$ESTATE_TEMPLATE['view']['default']['txt'][4] .= '<div class="WD100">'.$ns->tablerender(EST_GEN_IMAGE.' '.EST_GEN_GALLERY,'{EST_VIEW_GALLERY:imgs='.$IDIV.'}','menu',true).'</div>';

$ESTATE_TEMPLATE['view']['default']['txt'][5] .= '<div id="estMiniSrc">{PROP_NEWICON}{PROP_EDITICONS:for=view}</div>';














$EST_LIST_CAP = '
<div class="estCardTop">
  {PROP_LIST_BANNER}
  {PROP_EDITICONS:for=card}
  <a href="{PROP_LIST_LINKVIEW}">
    <div class="estCardPropName">{PROP_NAME}</div>
    <div class="estCardPropCity">{PROP_CITYSTATE}</div>
    <div class="estCardPropBul1">{PROP_BULLETS1} • {PROP_VIEWCOUNT}</div>
  </a>
</div>';

$EST_LIST_TXT = '
<div class="estCardMain">
  <a href="{PROP_LIST_LINKVIEW}">
    <div class="estListCardImg" style="{PROP_THMSTY}"></div>
    <div class="estILBullets">
      <div class="estModlNam">{PROP_MODELNAME}</div>
      {PROP_FEATURES}
      {PROP_SUMMARY}
    </div>
  </a>
  {PROP_AGENTMINI:for=card}
</div>';




$ESTATE_TEMPLATE['list']['default']['tempname'] = 'Default List Template';
$ESTATE_TEMPLATE['list']['default']['txt'] = '{EST_LEAFLET_MAP}';

$ESTATE_TEMPLATE['list']['default']['txt'] .= '<div class="estFLEXCont">';

if(count($EST_PROP) > 0){
  $slc = e107::getScBatch('estate',true);
  foreach($EST_PROP as $k=>$v){
    $slc->setVars($v);
    $CAPT = $tp->parseTemplate($EST_LIST_CAP, false, $slc);
    $TXT = $tp->parseTemplate($EST_LIST_TXT, false, $slc);
    $ESTATE_TEMPLATE['list']['default']['txt'] .= '<div class="card estCard">'.$ns->tablerender($CAPT,$TXT,'menu',true).'</div>';
    unset($CAPT,$TXT);
    }
  }

$ESTATE_TEMPLATE['list']['default']['txt'] .= '</div>';
  
$ESTATE_TEMPLATE['list']['default']['txt'] .= '<div id="estMiniSrc">{PROP_NEWICON}</div>';



unset($EST_LIST_CAP,$EST_LIST_TXT);
