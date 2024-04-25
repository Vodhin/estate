<?php
// estate Template file

// IMPORTANT: if making or changing this template, there are certain IDs starting with "estViewBox" (eg estViewBoxTop, estViewBoxSummary, etc)
// that are used by the Layout Preferences for this plugin and must be retained for this template to work.

if (!defined('e107_INIT')) { exit; }

global $EST_PREF,$EST_SPACES,$EST_GALEXP,$IDIV;

if(defined('THEME_LAYOUT')){
  if($EST_PREF['layout_view_spaces'] !== 'dynamicf' && strpos(strtolower(THEME_LAYOUT),'full') === false){$EST_PREF['layout_view_spaces'] = 'default';}
  }

$ESTATE_TEMPLATE = array();

//CORE TEMPLATE
$ESTATE_TEMPLATE['noview'] = '
<div id="estateCont">
  <h3>'.EST_GEN_PROPERTY.' #'.$GLOBALS['PROPID'].' '.EST_GEN_NOTFOUND.'</h3>
  <div>'.EST_ERR_HLP1a.' '.$GLOBALS['PROPID'].' '.EST_ERR_HLP1b.' <a href="'.e_SELF.'"><b>'.EST_GEN_VIEWLISTINGS.'</b></a></div>
</div>';

$ESTATE_TEMPLATE['nolist'] = '
<div id="estateCont">
  <h3>'.EST_GEN_NOLISTINGS.'</h3><div>'.EST_GEN_CHECKLATER.'</div>
</div>';

$ESTATE_TEMPLATE['start'] = '
<div id="estateCont">';

$ESTATE_TEMPLATE['end'] = '
</div>';

$ESTATE_TEMPLATE['pins'] = '{PROP_MAP_PINS}';


//LIST OF LISTINGS
$ESTATE_TEMPLATE['list']['elnk'] = '<div id="estMiniSrc">{PROP_NEWLNK}</div>';

if(intval($EST_PREF['layout_list_map']) > 0){
  $ESTATE_TEMPLATE['list']['map']	= '
  <div id="estMapCont" class="'.$EST_PREF['layout_list_mapbg'].'">
    <div id="estMap" style="width: 100%;"></div>
  </div>';
  }
else{
  $ESTATE_TEMPLATE['list']['map'] = '';
  }



$ESTATE_TEMPLATE['list']['item'] 	= '
  <a class="estListBlockA" href="{PROP_LIST_LINKVIEW}">
    <div class="estListBlock '.$EST_PREF['layout_list'].'">
      <div class="estListPropHead">
        <div class="estListPropHeadD1">{PROP_NAME}</div>
        <div class="estListPropHeadD2">{PROP_BULLETS1} • {PROP_VIEWCOUNT}</div>
        <div class="estListPropHeadD3">{PROP_CITYSTATE}{PROP_SUBDIVNAME}</div>
        {PROP_LIST_EDITLNK}
      </div>
      <div class="estListPropImg" style="{PROP_THMSTY}">{PROP_LISTTHM_BANNER}</div>
      <div class="estListPropInfo">
        <div class="estILBullets">
          <div class="estModlNam">{PROP_MODELNAME}</div>
          {PROP_FEATURES}
        </div>
        <div class="estListSummary">
          {PROP_SUMMARY}
        </div>
      </div>
    </div>
  </a>';



$ESTATE_TEMPLATE['menu']['head'] = '<div id="estPlugMenu0Cap">{PROP_MENU_HEAD}</div>';

$ESTATE_TEMPLATE['menu']['saved'] = '
  <div id="estSavedModule" class="estViewSect noPADTB">
    <h3>Saved Listings</h3>
    {PROP_SAVED_LIST}
  </div>
  ';

$ESTATE_TEMPLATE['menu']['seller'] = '
    <div id="estViewBoxSummaryMenu">
      <div id="estInfoModule" class="estViewSect">
        <div class="estViewMod">
          <h3 id="estSidebar1Capt" class="sumCapt">{PROP_STATUS}</h3>
          <div><span class="FR">{PROP_VIEWCOUNT}</span>{PROP_PRICE}</div>
          <div>{PROP_MODELNAME}</div>
        </div>
      </div>
      
      <div id="estAgntModule" class="estViewSect noPADTB">
        {PROP_AGENTCARD:img=1}
          {PROP_MSG_CARD}
        </div>
      </div>
      
      <div id="estOpenHouseModule" class="estViewSect">
        <div class="estViewMod">
          <h3 class="caption">'.EST_GEN_EVENTS.'</h3>
          <div class="estDet2Cont">
            {PROP_VIEW_OPENHOUSE}
          </div>
        </div>
      </div>
    </div>
  ';


//VIEW LISTING TEMPLATE
$ESTATE_TEMPLATE['view']['elnk'] = '<div id="estMiniSrc">{PROP_NEWLNK}{PROP_EDITLNK}</div>';

$ESTATE_TEMPLATE['view']['top'] = '
  <div id="estViewBoxTop"'.($EST_PREF['slideshow_act'] == 1 ? ' class="estSlideshow" title="'.EST_GEN_PLAYPAUSESLIDE.'"' : '').'>
    <div class="estSSPlayPause"></div>
  </div>';




$ESTATE_TEMPLATE['view']['sum'] = '
  <div id="estViewBoxSummary" class="estViewBoxMid '.$EST_PREF['layout_view_agent'].' flexStretch '.$EST_PREF['layout_view_summbg'].'">';
  
if(strpos(strtolower(THEME_LAYOUT),'full') === true){
  $ESTATE_TEMPLATE['view']['sum'] .= '
    <div id="estViewBoxSummarySB" class="'.$EST_PREF['layout_view_agntbg'].'">
      <div class="estViewBoxCont">
        <div id="estInfoModule" class="estViewSect">
          <div class="estViewMod">
            <h3 id="estSidebar1Capt" class="sumCapt">{PROP_STATUS}</h3>
            <div><span class="FR">{PROP_VIEWCOUNT}</span>{PROP_PRICE}</div>
            <div>{PROP_MODELNAME}</div>
          </div>
        </div>
        <div id="estAgntModule" class="estViewSect noPADTB">
          {PROP_AGENTCARD:img=1}
        </div>
        <div id="estOpenHouseModule" class="estViewSect">
          <div class="estViewMod">
            <h3 class="caption">'.EST_GEN_EVENTS.'</h3>
            <div class="estDet2Cont">
              {PROP_VIEW_OPENHOUSE}
            </div>
          </div>
        </div>
      </div>
    </div>';
  }

$ESTATE_TEMPLATE['view']['sum'] .= '
    <div id="estViewBoxSummaryOverview">
      <div class="estViewBoxCont">
        <div class="estViewSect">
          <h3 class="caption">'.EST_GEN_OVERVIEW.'</h3>
          <h4>{PROP_NAME}</h4>
          {PROP_FEATURES}
          <p>{PROP_SUMMARY}</p>
          <p>{PROP_DESCRIPTION}</p>
        </div>
        <div id="estFeaturesModule" class="estViewSect">
          <h3 id="estFeaturesCaption" class="caption">'.EST_GEN_FEATURES.'</h3>
          <div id="estInfoModule1">
            <div class="estInfoCard">
              {PROP_POINTS}
            </div>
            <div class="estInfoCard">
              {PROP_FEATURE_EXTENDED}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>';
  

if(count($GLOBALS['EST_SPACES'])){
  $ESTATE_TEMPLATE['view']['spaces'] = '
  <div id="estViewBoxSpacesCont" class="'.$EST_PREF['layout_view_spacesbg'].($EST_PREF['layout_view_spaces'] == 'dynamic' ? ' estSpaceDynamic' : '').($EST_PREF['layout_view_spaces_ss'] == 1 ? ' estSlideshow' : '').'">';
  
  if($EST_PREF['layout_view_spaces'] == 'dynamic'){
    $ESTATE_TEMPLATE['view']['spaces'] .= '<div class="estViewBoxMid">
      <div id="estViewSpacePvwCont">
        <div class="estViewBoxCont">
          <h3 id="estViewBoxSpacesCapt" class="caption">'.EST_GEN_SPACES.'</h3>
          <div id="estViewSpaceImgPvwCont" class="estBGGrad1">
            <div id="estViewSpaceImgPvwSlider" class="estViewSect">
              <div id="estArrBordR"></div>
              <div id="estViewSpaceImgCont">{PROP_VIEWSPACEIMGS}</div>
            </div>
          </div>
        </div>
      </div>
      <div id="estViewSpaceBtnCont">
        <div class="estViewBoxCont">
          <div id="estViewSpaceBtns" class="estViewSect">{PROP_VIEWSPACEBTNS}</div>
        </div>
      </div>
    </div>';
    }
  else{
    $ESTATE_TEMPLATE['view']['spaces'] .= '<div class="estViewBoxMid">
      <div id="estViewSpaceBtnCont">
        <div class="estViewBoxCont">
          <div id="estViewSpaceBtns" class="estViewSect">{PROP_VIEWSPACEBTNS}</div>
        </div>
      </div>
    </div>';
    }
  $ESTATE_TEMPLATE['view']['spaces'] .= '
  </div>';
  }
else{
  $ESTATE_TEMPLATE['view']['spaces'] = '';
  }



if(intval($EST_PREF['layout_view_map']) > 0){
  $ESTATE_TEMPLATE['view']['map'] = '
  <div id="estViewBoxMap" class="estViewBoxBot '.$EST_PREF['layout_view_mapbg'].'">
    <div class="estViewBoxCont">
      <div class="estViewSect">
        <h3 class="caption">'.EST_GEN_MAP.'</h3>
        <div id="estMapCont" data-zm="'.$EST_PREF['map_zoom_def'].'">
          <div id="estMap"></div>
        </div>
      </div>
    </div>
  </div>';
  }
else{
  $ESTATE_TEMPLATE['view']['map'] = '';
  }


$ESTATE_TEMPLATE['view']['nplisting']	= '
  <div id="estViewNxtPre" class="estViewBoxBot '.$EST_PREF['layout_view_gallbg'].'">
    <div class="estViewBoxCont">
      <div class="estViewSect">
        {PROP_NPLISTING}
      </div>
    </div>
  </div>
  ';



$ESTATE_TEMPLATE['view']['gal'] = '
  <div id="estViewBoxGallery" class="estViewBoxBot '.$EST_PREF['layout_view_gallbg'].'">
    <div class="estViewBoxCont">
      <div id="estGalSect" class="estViewSect">
        <h3 id="estGalContH2" class="caption">'.EST_GEN_IMAGE.' '.EST_GEN_GALLERY.'</h3>
        <div id="estGalCont" {PROP_VIEWGALSTYLE}>'.$IDIV.'</div>
      </div>
    </div>
  </div>';

 //{SETIMAGE: w=400&h=300} {PROP_OPENHOUSELI}

