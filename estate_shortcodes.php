<?php

// estate Shortcodes file

if (!defined('e107_INIT')) { exit; }


if(defined('THEME_LAYOUT')){}

class estate_shortcodes extends e_shortcode{
  
  function sc_agent_roll($parm){
    $AGENT = $this->estGetSeller();
    $MSGICON = $this->estGetMsgIcon();
    return $AGENT['agent_roll'].$MSGICON;
    }
  
  
  function sc_prop_editicons($parm){
    $AGENT = $this->estGetSeller();
    if($parm['for'] == 'card'){return $AGENT['eicon'];}
    if($parm['for'] == 'list'){return $AGENT['lstedit'];}
    if($parm['for'] == 'view'){return $AGENT['edit'];}
    }
  
  function sc_prop_agentmini($parm){
    $AGENT = $this->estGetSeller();
    if($parm['for'] == 'card'){return $AGENT['lstagent'];}
    }
  
  
  function sc_prop_admlnk($parm){
    return '';
    }
  
  
  function sc_est_nav_menu($parm){
    if(defined("EST_RENDER_NAVMENU")){return;}
    
    $tp = e107::getParser();
    $frm = e107::getForm(false, true);
    $FLTRS = $_POST['fltrs'];
    
    
    if(isset($GLOBALS['EST_ZONING']) && count($GLOBALS['EST_ZONING']) > 0){
      $ZONING = '<select id="fltrs[prop_zoning]" name="fltrs[prop_zoning]" class="tbox" value="'.$FLTRS['prop_zoning'].'" ><option value="">All Zoning</option>';
      foreach($GLOBALS['EST_ZONING'] as $k=>$v){
        $ZONING .= '<option value="'.$k.'"'.($FLTRS['prop_zoning'] == $k ? ' selected="selected"': '').'>'.$tp->toHTML($v).'</option>';
        } 
      $ZONING .= '</select>';
      }
    
    
    
    switch($parm['for']){
      case 'menu' :
        $cls = 'estNavMenu';
        $text .= $ZONING;
        $text .= '<div class="btnlst"><input type="submit" id="estSetFilters" name="estSetFilters" class="" value="Apply Filters"/></div>';
        $txt = e107::getRender()->tablerender('Filter Menu', $text, 'estSideMenu-Filter',true);
        unset($text);
        break;
      
      default :
        $cls = 'estNavBar';
        $txt = '<input type="submit" id="estSetFilters" name="estSetFilters" class="ILBLK FR" value="GO"/>';
        $txt .= $ZONING;
        break;
      }
    define("EST_RENDER_NAVMENU",true);
    return '<div id="estNavMenu" class="'.$cls.'"><form name="estListingNav" method="post" action="'.e_SELF.'?list.0">'.$txt.'</form></div>';
    }
  
  
  
  function sc_prop_viewcount($parm){
    return intval($this->var['prop_views']).' '.(intval($this->var['prop_views']) == 1 ? EST_GEN_VIEW : EST_GEN_VIEWS);;
    }
  
  function sc_prop_likes($parm){
    $lkds = intval($this->var['likes']);
    if($lkds > 0){
      $txt = '<span id="estPropLikeCt-'.$this->var['prop_idx'].'" title="'.$lkds.' '.EST_GEN_SAVEINQ.'" data-t="'.EST_GEN_SAVEINQ.'" data-ct="'.$lkds.'">'.$lkds.'</span> '.EST_GEN_SAVES;
      if($parm['bullet'] = 'right'){$txt = $txt.' • ';}
      elseif($parm['bullet'] = 'left'){$txt = ' • '.$txt;}
      return '<span'.($lkds > 0 ? '' : ' style="display:none;"').'>'.$txt.'</span>';
      }
    }
  
  
  function sc_prop_likesviews($parm){
    $txt = intval($this->var['prop_views']).' '.(intval($this->var['prop_views']) == 1 ? EST_GEN_VIEW : EST_GEN_VIEWS);
    $lkds = intval($this->var['likes']);
    $txt .= ' <span'.($lkds > 0 ? '' : ' style="display:none;"').'> • <span id="estPropLikeCt-'.$this->var['prop_idx'].'" title="'.$lkds.' '.EST_GEN_SAVEINQ.'" data-t="'.EST_GEN_SAVEINQ.'" data-ct="'.$lkds.'">'.$lkds.'</span> '.EST_GEN_SAVEINQ.'</span>';
    return $txt;
    }
    
  function sc_prop_list_linkview($parm){
		//return e107::getUrl()->create('estate/listings.php?view.'.intval($this->var['prop_idx']).'.0', $this->var);
    return EST_PATHABS_LISTINGS.'?view.'.intval($this->var['prop_idx']).'.0';
    }
  
  function sc_prop_name($parm){
    return e107::getParser()->toHTML($this->var['prop_name']);
    }
  
  function sc_prop_summary($parm){
    return e107::getParser()->toHTML($this->var['prop_summary'],true);
    }
  
  function sc_prop_description($parm){
    return e107::getParser()->toHTML($this->var['prop_description'],true);
    }
  
  
  
  function sc_est_view_gallery($parm){
    return '<div id="estGalCont">'.$GLOBALS['IDIV'].'</div>';
    }
  
  
  function sc_est_leaflet_map($parm){
    return '<div id="estMapCont"><div id="estMap" style="width: 100%;"></div></div>';
    }
  
  
  function sc_prop_map_agydta($parm){
    
    $sql = e107::getDb();
		$tp = e107::getParser();
    
    if($AGY = $sql->retrieve("SELECT #estate_agencies.* FROM #estate_agencies",true)){
      foreach($AGY as $k=>$v){
        //if($v['agency_lat'] !== '' && $v['agency_lon'] !== ''){}
        if(intval($v['agency_imgsrc']) == 1){$imgpth = EST_PTHABS_AGENCY.$tp->toHTML($v['agency_image']);}
        else{$imgpth = $tp->thumbUrl($pref['sitelogo'],false,false,true);}
        
        $AGYDTA .= ($AGYDTA ? ';' : '').intval($v['agency_idx']).'||'.$tp->toHTML($v['agency_name']).'|'.$tp->toHTML($v['agency_lat']).'|'.$tp->toHTML($v['agency_lon']).'|'.$imgpth.'';
        }
      return 'data-agcy="'.$AGYDTA.'"';
      }
    else{return '';}
    }
  
  //PROP_THMSTY
  function sc_prop_subdivname($parm){
    if(trim($this->var['subd_name']) !== ""){
      return e107::getParser()->toHTML(''.$this->var['subd_name'].'');
      }
    }
    
  function sc_prop_modelname($parm){
    if(trim($this->var['prop_modelname']) !== ""){return '"'.e107::getParser()->toHTML($this->var['prop_modelname']).'"';}
    }
  
  
  function sc_prop_events($parm){
    if($this->var['evt']){
      $ns = e107::getRender();
      $tp = e107::getParser();
      $STRDATETOM = mktime(23, 59, 59, date("m"), date("d")+1, date("Y"));
      foreach($this->var['evt'] as $ok=>$ov){
        $CAPT = $tp->toHTML((strtotime(date('m/d/Y',$ov['event_start'])) <= $GLOBALS['STRDATETODAY'] ? EST_GEN_TODAY : (strtotime(date('m/d/Y',$ov['event_start'])) <= $STRDATETOM ? EST_GEN_TOMORROW : date('D F j, Y',$ov['event_start']))));
        $TXT = $tp->toHTML('<h5>'.$ov['event_name'].'</h5><p>'.($ov['event_start'] <= $GLOBALS['STRTIMENOW'] ? EST_GEN_NOW : date('g:i a',$ov['event_start'])).' - '.date('g:i a',$ov['event_end']).'<br />'.$ov['event_text'].'</p>');
        $LITM .= '<li class="estOHItem"><h4>'.$CAPT.'</h4>'.$TXT.'</li>';
        $OPH .= $ns->tablerender(EST_GEN_EVENT.': '.$CAPT, $TXT, 'estSideMenuEvents-'.$ok,true);
        }
      
      
      }
    else{$LITM = '<li class="estOHItem">'.EST_GEN_NOEVENTS.'</li>';}
    
    if($parm['as'] == 'ul' && $LITM){
      unset($OPH);
      return '<ul id="estOHCont">'.$LITM.'</ul>';
      }
    elseif($OPH){
      unset($LITM);
      return $OPH;
      }
    }
  
  function sc_prop_list_banner($parm){
    if(intval($this->var['prop_appr']) < 1){
      return '<div class="estCardTopTab btn-warning">'.e107::getParser()->toHTML('<h5>'.EST_GEN_WAITINGAPPROVAL.'</h5></div>');
      }
    else{
      if(intval($this->var['prop_status']) == 4){
        $CAPT = EST_GEN_PENDING;
        }
      elseif(intval($this->var['prop_status']) == 5){
        $CAPT = (intval($this->var['prop_listype']) == 0 ? EST_GEN_OFFMARKET : EST_GEN_SOLD);
        }
      
      
      if($this->var['evt']){
        $i=0;
        $STRDATETOM = mktime(23, 59, 59, date("m"), date("d")+1, date("Y"));
        foreach($this->var['evt'] as $ok=>$ov){
          if($i == 0){
            $CAPT .= ($CAPT ? ' • ' : '').$ov['event_name'];
            $TXT = '<p>'.(strtotime(date('m/d/Y',$ov['event_start'])) <= $GLOBALS['STRDATETODAY'] ? EST_GEN_TODAY : (strtotime(date('m/d/Y',$ov['event_start'])) <= $STRDATETOM ? EST_GEN_TOMORROW : date('D F j',$ov['event_start']))).($ov['event_start'] <= $GLOBALS['STRTIMENOW'] ? ': '.EST_GEN_NOW : ' '.date('g:i a',$ov['event_start'])).' - '.date('g:i a',$ov['event_end']).'</p>';
            }
          $i++;
          }
        unset($i);
        }
      }
      
    
    
    if($CAPT){
      return '<div class="estCardTopTab">'.e107::getParser()->toHTML('<h5>'.$CAPT.'</h5>'.$TXT.'</div>');
      }
    else if(trim($this->var['prop_flag']) !== ''){
      return '<div class="estCardTopTab estCardTopFlag">'.e107::getParser()->toHTML('<h5>'.$this->var['prop_flag'].'</h5></div>');
      }
    }
  
  
  function sc_prop_features($parm){
    if($parm['as'] == 'ul'){
      $lia = explode(',',$this->var['prop_features']);
      if(trim($this->var['prop_flag']) !== ''){array_push($lia,$this->var['prop_flag']);}
      if(count($lia) > 0){
        $ret = '<ul>';
        foreach($lia as $k=>$v)$ret .= '<li>'.$v.'</li>';
        $ret .= '</ul>';
        }
      }
    else{
      $ret = str_replace(","," • ",$this->var['prop_features']).(trim($this->var['prop_flag']) !== '' ? ' • '.$this->var['prop_flag'] : '');
      }
    return e107::getParser()->toHTML($ret);
    }
  
  function sc_prop_citystate($parm){
    return e107::getParser()->toHTML($this->var['city_name']).(trim($this->var['state_init']) ? ', '.$this->var['state_init'] : '');
    }
  
  function sc_city_desc($parm){
    return e107::getParser()->toHTML($this->var['city_description'],true);
    }
  
  function sc_city_features($parm){
    $txt = '<div>Feature list here</div>';
    if(isset($this->var['city_description'])){
      //estCommSpaces
      //COMMUNITY_SPACES
      $tp= e107::getParser();
      
      }
    //$tp->toHTML($this->var['city_description'],true);
    return $txt;
    }
  
  
  function sc_prop_thmsty($parm){
    if(trim($this->var['img'][1]['t']) !== ''){
      return 'background-image:url(\''.e107::getParser()->toHTML(EST_PTHABS_PROPTHM.$this->var['img'][1]['t']).'\')';
      }
    else{return 'background-image:url(\''.EST_PATHABS_IMAGES.'imgnotavail.png\')';}
    }
  
  
  
  function sc_prop_points($parm){
    $ret = '';
    if($this->var['prop_type'] !== 0){
      $ret .= '<li>'.($this->var['prop_floorct'] !== 0 ? $this->var['prop_floorct'].' '.EST_GEN_STORY.' ' : '').$GLOBALS['EST_PROPTYPES'][$this->var['prop_type']].'</li>';
      }
    
    //$prop_condit
      
    if($this->var['prop_zoning'] !== 0){
      $ret .= '<li>'.$GLOBALS['EST_ZONING'][$this->var['prop_zoning']].'</li>';
      }
    if($this->var['prop_yearbuilt'] !== 0){
      $ret .= '<li>'.EST_PROP_YEARBUILT.': '.$this->var['prop_yearbuilt'].'</li>';
      }
    if($this->var['prop_intsize'] !== 0){
      $ret .= '<li>'.EST_PROP_INTSIZE.': '.$this->var['prop_intsize'].' '.$GLOBALS['EST_DIM1UNITS'][$this->var['prop_dimu1']][0].'</li>';
      }
    if($this->var['prop_landsize'] !== 0){
      $ret .= '<li>'.EST_PROP_LANDSIZE.': '.$this->var['prop_landsize'].' '.$GLOBALS['EST_DIM2UNITS'][$this->var['prop_dimu2']].'</li>';
      }
    
    if($this->var['prop_floorno'] !== 0){
      $ret .= '<li>'.EST_GEN_FLOORNO.': '.$this->var['prop_floorno'].'</li>';
      }
    if($this->var['prop_bldguc'] !== 0){
      $ret .= '<li>'.EST_GEN_UNITSBLDG.': '.$this->var['prop_bldguc'].'</li>';
      }
    if($this->var['prop_complxuc'] !== 0){
      $ret .= '<li>'.EST_GEN_UNITSCOMPLX.': '.$this->var['prop_complxuc'].'</li>';
      }
      
    if(trim($ret) !== ''){
      return e107::getParser()->toHTML('<b>'.EST_GEN_GENERAL.'</b><ul class="estDet2Cont">'.$ret.'</ul>');
      }
    }
  
  
  function sc_prop_feature_extended($parm){
    $ret = '';
    if($this->var['prop_bedtot'] !== 0){
      $ret .= '<li>'.EST_GEN_BEDTOT.': '.$this->var['prop_bedtot'].'</li>';
      }
    if($this->var['prop_bathtot'] !== 0){
      $ret .= '<li>'.EST_GEN_BATHROOMS.': '.$this->var['$prop_bathtot'].' ('.$this->var['prop_bathfull'].' '.EST_GEN_FULL.', '.$this->var['prop_bathhalf'].' '.EST_GEN_HALF.')</li>';
      }
    
    if($this->var['prop_bedmain'] !== 0){
      $ret .= '<li>'.EST_GEN_BEDMAIN.': '.$this->var['prop_bedmain'].'</li>';
      }
    if($this->var['prop_bathmain'] !== 0){
      $ret .= '<li>'.EST_GEN_BATHMAIN.': '.$this->var['prop_bathmain'].'</li>';
      }
    
    
    if(trim($ret) !== ''){
      return e107::getParser()->toHTML('<b>'.EST_GEN_BEDANDBATH.'</b><ul class="estDet2Cont">'.$ret.'</ul>');
      }
    }
  
  
  function sc_prop_featurelist($parm){
    //PROP_POINTS PROP_FEATURE_EXTENDED
    if(is_array($this->var['prop_fearurelist']) && count($this->var['prop_fearurelist']) > 0){
      foreach($this->var['prop_fearurelist'] as $fk=>$fv){
        $txt .= '<div class="estInfoCard estFLEX45"><b>'.$fv['cat'].'</b>';
        if(is_array($fv['dta'])){
          $txt .= '<ul class="estDet2Cont">';
          foreach($fv['dta'] as $sk=>$sv){
            $txt .= '<li>'.$sv['key'].(trim($sv['val']) !== '' ? ': '.$sv['val'] : '').'</li>';
            }
          $txt .= '</ul>';
          }
        $txt .= '</div>';
        }
      }
    return $txt;
    unset($txt);
    }
  
  
  
  
  function sc_prop_nplisting($parm){
    //$ret = '['.$this->var['prop_prevIdx'].']';
    
    return $ret;
    }
  
      
  function spacesKeyId($v,$sok,$sk){
    return 'SPACE'.$v['ord'].'x'.$sok.'x'.$sk;
    }
  
  function spacesTxt($sv){
		$tp = e107::getParser();
    $text = '';
    if(trim($sv['d']) !== ''){
      $text .= '<p>'.$tp->toHTML($sv['d']).'</p>';
      }
    $text .= '<ul>';
    if(trim($sv['xy']) !== ''){
      $text .= '<li>'.$tp->toHTML($sv['xy']).'</li>';
      }
    if(trim($sv['l']) !== ''){
      $text .= '<li>'.EST_LOCATION.': '.$tp->toHTML($sv['l']).'</li>';
      }
    if(count($sv['f']) > 0){
      foreach($sv['f'] as $fk=>$fv){
        if(trim($fv['n']) !== ''){
          $text .= '<li>'.$tp->toHTML($fv['n']);
          if(trim($fv['d']) !== ''){$text .= ': '.str_replace(',',', ',$tp->toHTML($fv['d']));}
          $text .= '</li>';
          }
        }
      }
    $text .= '</ul>';
    return $text;
    }
  
  function sc_prop_viewspaceimgs($parm){
		$tp = e107::getParser();
    $SPACES = $GLOBALS['EST_SPACES'];
    $text = '';
    if($SPACES && count($SPACES) > 0){
      usort($SPACES, "spgrpsort");
      foreach($SPACES as $k=>$v){
        foreach($v['sp'] as $sok=>$sov){
          ksort($sov);
          foreach($sov as $sk=>$sv){
            $estkeyid = $this->spacesKeyId($v,$sok,$sk);
            $SPACETXT = $this->spacesTxt($sv);
            $text .= '
                <div id="'.$estkeyid.'dynImg" class="estViewSpaceImgBlock">
                  <div class="estImgSlide'.(count($sv['m']) > 0 ? ' '.$estkeyid.'img' : '').'" data-ict="'.count($sv['m']).'"></div>
                  <div class="estSpTtl">'.$tp->toHTML($sv['n']).'</div>
                  <div class="estViewSpTxt">'.$SPACETXT.'</div>
                </div>';
            }
          }
        }
      }
    unset($SPACETXT);
    return $text;
    }
  
  
  
  function sc_view_spacestable($parm){
    $SPACES = $GLOBALS['EST_SPACES'];
    $text = '';
    if(is_array($SPACES) && count($SPACES) > 0){
  		$tp = e107::getParser();
      $ns = e107::getRender();
      usort($SPACES, "spgrpsort");
      foreach($SPACES as $k=>$v){
        foreach($v['sp'] as $sok=>$sov){
          ksort($sov);
          foreach($sov as $sk=>$sv){
            $estkeyid = $this->spacesKeyId($v,$sok,$sk);
            $SPACETXT = $this->spacesTxt($sv);
            $TXT .= '
              <div id="'.$estkeyid.'btn" class="estViewSpaceBtn estTableGroupTile'.(count($sv['m']) > 0 ? '' : ' noPics').'" data-getimg="'.$estkeyid.'dynImg">
                <div class="estSpTtl">'.$tp->toHTML($sv['n']).'</div>
                <div class="estImgSlide'.(count($sv['m']) > 0 ? ' '.$estkeyid.'img' : '').'" data-ict="'.count($sv['m']).'"></div>
                <div class="estViewSpTxt">'.$SPACETXT.'</div>
              </div>';
            }
          }
        $text .= '<div class="estTableGroupShell" style="order:'.$v['ord'].'">'.$ns->tablerender($tp->toHTML($v['n']),'<div class="estTableGroupWrapper">'.$TXT.'</div>','menu',true).'</div>';
        unset($TXT,$SPACETXT);
        }
      }
    return '<div id="estViewSpaceBtns">'.$text.'</div>';
    }
  
  
  
  function sc_prop_viewspacebtns($parm){
		$tp = e107::getParser();
    $SPACES = $GLOBALS['EST_SPACES'];
    $text = '';
    if(is_array($SPACES) && count($SPACES) > 0){
      usort($SPACES, "spgrpsort");
      if($parm['as'] == 'table'){
        $ns = e107::getRender();
        foreach($SPACES as $k=>$v){
          foreach($v['sp'] as $sok=>$sov){
            ksort($sov);
            foreach($sov as $sk=>$sv){
              $estkeyid = $this->spacesKeyId($v,$sok,$sk);
              $SPACETXT = $this->spacesTxt($sv);
              $TXT .= '
                <div id="'.$estkeyid.'btn" class="estViewSpaceBtn'.(count($sv['m']) > 0 ? '' : ' noPics').'" data-getimg="'.$estkeyid.'dynImg">
                  <div class="estSpTtl">'.$tp->toHTML($sv['n']).'</div>
                  <div class="estImgSlide'.(count($sv['m']) > 0 ? ' '.$estkeyid.'img' : '').'" data-ict="'.count($sv['m']).'"></div>
                  <div class="estViewSpTxt">'.$SPACETXT.'</div>
                </div>';
              }
            }
          
          }
        }
      else{
        foreach($SPACES as $k=>$v){
          $text .= '
              <div class="estSpaceGroup" style="order:'.$v['ord'].'">
                <h3>'.$tp->toHTML($v['n']).'</h3>';
          foreach($v['sp'] as $sok=>$sov){
            ksort($sov);
            foreach($sov as $sk=>$sv){
              $estkeyid = $this->spacesKeyId($v,$sok,$sk);
              $SPACETXT = $this->spacesTxt($sv);
              $text .= '
                <div id="'.$estkeyid.'btn" class="estViewSpaceBtn'.(count($sv['m']) > 0 ? '' : ' noPics').'" data-getimg="'.$estkeyid.'dynImg">
                  <div class="estSpTtl">'.$tp->toHTML($sv['n']).'</div>
                  <div class="estImgSlide'.(count($sv['m']) > 0 ? ' '.$estkeyid.'img' : '').'" data-ict="'.count($sv['m']).'"></div>
                  <div class="estViewSpTxt">'.$SPACETXT.'</div>
                </div>';
              }
            }
          $text .= '
              </div>';
          }
        }
        
      }
    unset($SPACETXT);
    return $text;
    }
  
  
  function sc_prop_spacepvw($parm){
    $txt = '
          <div id="estViewSpaceImgPvwCont">
            <div id="estViewSpaceImgPvwSlider">
              <div id="estArrBordR"></div>
              <div id="estViewSpaceImgCont">';
    $txt .= $this->sc_prop_viewspaceimgs($parm);
    $txt .= '</div>
            </div>
          </div>';
    return $txt;
    }
  
  
  
  function sc_est_slideshow_top($parm){
    $actv = e107::pref('estate','slideshow_act');
    return '<div id="estSlideShow" class="'.($actv == 1 ? ' estSlideshow" title="'.EST_GEN_PLAYPAUSESLIDE.'"' : '"').'><div class="estSSPlayPause"></div></div>';
    }
  
  
  
  function sc_view_spaces($parm){
    $estpref = e107::pref('estate');
    $layout = strpos(strtolower(THEME_LAYOUT),'full');
    
    if($parm['dynamic'] == 1){
      $estpref['spaces_dynamic'] = 1;
      $layout = 1;
      }
    
    
    
    if($estpref['spaces_dynamic'] > 0 && $layout > -1){
      $txt = '
        <div id="estSpacesCont" class="estSpaceDynamic'.($estpref['slideshow_act'] == 1 ? ' estSlideshow' : '').' WD100">
          <div id="estViewSpacePvwCont">';
      $txt .= $this->sc_prop_spacepvw($parm);
      $txt .= '
          </div>
          <div id="estViewSpaceBtnCont">
            <div id="estViewSpaceBtns">';
      $txt .= $this->sc_prop_viewspacebtns($parm);
      $txt .= '
            </div>
          </div>
        </div>';
      return '<div class="WD100">'.e107::getRender()->tablerender(EST_GEN_SPACES,$txt,'dynamic-spaces',true).'</div>';
      }
    else{
      $txt = '
      <div id="estSpacesCont" class="'.($estpref['slideshow_act'] == 1 ? ' estSlideshow' : '').' WD100">
          <div id="estViewSpaceBtnCont">';
      $txt .= $this->sc_view_spacestable($parm);
      $txt .= '
          </div>
      </div>';
      return $txt;
      }
    }
  
  
  
  function sc_prop_hoadisclaimers($parm){
		$tp = e107::getParser();
    $subapr = intval($this->var['subdiv']['subd_hoaappr']);
    $subreq = intval($this->var['subdiv']['subd_hoareq']);
    $subfee = intval($this->var['subdiv']['subd_hoafee']);
    $subfrq = intval($this->var['subdiv']['subd_hoafrq']);
    $sublnd = intval($this->var['subdiv']['subd_hoaland']);
    
    $hoaapr = intval($this->var['prop_hoaappr']);
    $hoareq = intval($this->var['prop_hoareq']);
    $hoafee = intval($this->var['prop_hoafee']);
    $hoafrq = intval($this->var['prop_hoafrq']);
    $hoalnd = intval($this->var['prop_hoaland']);
    
    $liarr = array();
    if($hoaapr == 1 || $hoareq > 0|| $hoafee > 0 || $hoalnd > 0){array_push($liarr,'¹'.EST_PROP_HOADISCLAIMER);}
    if($subfee > 0 || $hoafee > 0){array_push($liarr,'²'.EST_PROP_HOADISCLAIMER1.' '.EST_PROP_HOADISCLAIMER0.' '.EST_PROP_HOADISCLAIMER2);}
    if($subapr == 1 || $hoaapr == 1){array_push($liarr,'³'.EST_PROP_HOADISCLAIMER3.' '.EST_PROP_HOADISCLAIMER0);}
    
    if(intval($this->var['prop_landfee']) > 0){
      array_push($liarr,'⁴'.EST_PROP_HOADISCLAIMER4);
      }
    
    if(count($liarr) > 0){
      $txt = '<div id="hoaDisclaimers" class="estDisclaimer">';
      foreach($liarr as $k=>$v){$txt .= '<p>'.$tp->toHTML($v).'</p>';}
      $txt .= '</div>';
      
      return $txt;
      }
    
    if($this->var['prop_hoareq'] == 1 ||  $this->var['subd_hoaappr'] == 1 || $this->var['subd_hoareq'] == 1){
    
      }
    unset($subapr,$subreq,$subfee,$subfrq,$sublnd,$hoaapr,$hoareq,$hoafee,$hoafrq,$hoalnd);
    }
  
  
  
  
  
  
  
  function sc_prop_hoa($parm){
		$tp = e107::getParser();
    
    $subapr = intval($this->var['subdiv']['subd_hoaappr']);
    $subreq = intval($this->var['subdiv']['subd_hoareq']);
    $subfee = intval($this->var['subdiv']['subd_hoafee']);
    $subfrq = intval($this->var['subdiv']['subd_hoafrq']);
    $sublnd = intval($this->var['subdiv']['subd_hoaland']);
    
    $hoaapr = intval($this->var['prop_hoaappr']);
    $hoareq = intval($this->var['prop_hoareq']);
    $hoafee = intval($this->var['prop_hoafee']);
    $hoafrq = intval($this->var['prop_hoafrq']);
    $hoalnd = intval($this->var['prop_hoaland']);
    
    $liarr = array();
    
    if($subapr !== 1 && $hoaapr == 1){
      $liarr[0] = '<a class="estSTlnk" href="#hoaDisclaimers">'.EST_GEN_HOAAPPR2.'³</a>';
      }
    
    
    if($hoareq !== $subreq){
      if($hoareq == 1){$liarr[1] = EST_GEN_HOAREQ1;}
      else{$liarr[1] = EST_GEN_HOAREQ2;}
      }
      
    
    if($hoafee !== $subfee){
      if($hoafee > 0){
        $liarr[2] = '<a class="estSTlnk" href="#hoaDisclaimers">'.EST_PROP_HOAFEES.': '.$hoafee.($hoafrq > 0 ? ' '.EST_HOAFREQ[$hoafrq] : '').($hoalnd == 1 ? ' '.$GLOBALS['EST_HOALAND'][1][$hoalnd] : '').'²</a>';
        }
      }
    
    $landfee = intval($this->var['prop_landfee']);
    $landfrq = intval($this->var['prop_landfreq']);
    if($landfee > 0){
      $liarr[3] = '<a class="estSTlnk" href="#hoaDisclaimers">'.EST_PROP_LANDLEASE.': '.$landfee.' '.($landfrq > 0 ? ' '.$GLOBALS['EST_HOAFREQ'][$landfrq] : '').'⁴</a>';
      }
    
    
    if(count($liarr) > 0){
      $txt = '
      <h4 class="TAL"><a class="estSTlnk" href="#hoaDisclaimers">'.EST_GEN_HOADEF2.'¹</a></h4>
      <ul class="WD100">';
      foreach($liarr as $k=>$v){$txt .= '<li>'.$tp->toHTML($v).'</li>';}
      $txt .= '</ul>';
      }
    
    unset($subapr,$subreq,$subfee,$subfrq,$sublnd,$hoaapr,$hoareq,$hoafee,$hoafrq,$hoalnd);
    return $txt;
    }
  
  
  
  
  function sc_prop_pricehistory($parm){
    $scns = e107::getRender();
    
    $txt = '<div class="estPropHistory">';
    if(is_array($this->var['history']['dta']) && count($this->var['history']['dta']) > 0){
      $tp = e107::getParser();
      
      $agentid = intval($this->var['prop_agent']);
      $agencyid = intval($this->var['prop_agency']);
      $prop_status = intval($this->var['prop_status']);
      $prop_listype = intval($this->var['prop_listype']);
      $prop_listprice = intval($this->var['prop_listprice']);
      $prop_dateupdated = intval($this->var['prop_dateupdated']);
      $prop_dateprevw = intval($this->var['prop_dateprevw']);
      $prop_datelive = intval($this->var['prop_datelive']);
      $propupd = 0;
      
      $opts = explode(",",$this->var['prop_locale']);
      
      foreach($this->var['history']['dta'] as $hk=>$hv){
        $prophist_date = intval($hv['prophist_date']);
        $prophist_status = intval($hv['prophist_status']);
        $prophist_price = estParseCurrency($hv['prophist_price'],$opts);
        $hstattxt = $GLOBALS['EST_PROPSTATUS'][$prophist_status]['alt'];
        if($prophist_status == 5 && $prop_listype == 0){$hstattxt = EST_GEN_OFFMARKET;}
        
        if($propupd == 0 && $prophist_date > $prop_dateupdated  && ($prophist_price !== $prop_listprice || $prophist_status !== $prop_status)){
          
          if(EST_USERPERM > 2  || (EST_USERPERM == 2 && EST_AGENCYID == $agencyid) || (EST_USERPERM == 1 && USERID == $agentid)){
            $txt2 = '
            <div class="estPropHistory">
              <div class="estViewHistDiv FSITAL" title="'.EST_GEN_PRICEHISTFUT4.'">
                <div>'.$tp->toDate($prophist_date).'</div>
                <div>'.$hstattxt.'</div>
                <div>'.$prophist_price.'</div>
              </div>
            </div>';
            }
          $propupd++;
          }
        
        if($prophist_status == 2){
          if($prop_datelive > 0 && $prop_datelive <= $prophist_date){
            $hstattxt = EST_GEN_COMINGSOON;
            $prophist_price = '- - -';
            }
          elseif($prop_dateprevw > 0 && $prop_dateprevw <= $prophist_date){
            if(USERID > 0){$hstattxt = EST_GEN_PREVIEW;}
            else{$prophist_price = '- - -';}
            }
          }
          
        if($prophist_date < intval($GLOBALS['STRTIMENOW'])){
          $txt .= '
          <div class="estViewHistDiv">
            <div>'.$tp->toDate($prophist_date).'</div>
            <div>'.$hstattxt.'</div>
            <div>'.$prophist_price.'</div>
          </div>';
          }
        }
      if(isset($this->var['history']['msg']) && EST_USERPERM > 2  || (EST_USERPERM == 2 && EST_AGENCYID == $agencyid) || (EST_USERPERM == 1 && USERID == $agentid)){
        e107::getMessage()->addInfo($this->var['history']['msg']);
        }
      unset($prop_listype,$prop_dateprevw,$prop_datelive,$prophist_date,$prophist_status,$hstattxt,$prophist_price);
      }
    $txt .= '</div>';
    
    if($parm['mode'] == 'menu'){ //$propupd > 0 && 
      $scns->setStyle('menu'); 
      if(isset($txt2)){
        $mtxt = $scns->tablerender(EST_GEN_PRICEHISTFUT3,$txt2,'estSideMenuPriceHist',true);
        }
      return $mtxt.$scns->tablerender(EST_GEN_PRICEHIST,$txt,'estSideMenuPriceHist',true);
      }
    
    return (isset($txt2) ? '<h4 class="WD100" title="'.EST_GEN_PRICEHISTFUT4.'">'.EST_GEN_PRICEHISTFUT3.'</h4>'.$txt2.'<h4 class="WD100">'.EST_GEN_PRICEHISTFUT5.'</h4>' : '').$txt;
    }
  
  
  
  function sc_community_features($parm){
    if(intval($this->var['prop_subdiv']) > 0){
      $feats = $this->var['subdiv']['subd_features']['txt'];
      if(is_array($feats)){
        foreach($feats as $fk=>$fv){
          if(is_array($fv)){
            $txt .= '<b>'.$fk.'</b><ul class="estDet2Cont">';
            foreach($fv as $sk=>$sv){$txt .= '<li>'.$sk.': '.str_replace(",",", ",$sv).'</li>';}
            $txt .= '</ul>';
            }
          else{
            $txt .= '<li>'.$fk.': '.$fv.'</li>';
            }
          }
        }
      
      return $txt;
      }
    }
  
  function sc_community_slideshow($parm){
    if(is_array($this->var['subdiv']['media']) && count($this->var['subdiv']['media']) > 0){
      return '<div id="estSubDivSlideShow"></div>';
      }
    }
  
  function sc_comminuty_name($parm){
    return e107::getParser()->toHTML($this->var['subdiv']['subd_name'],true);
    }
  
  function sc_community_url($parm){
    if(trim($this->var['subdiv']['subd_url']) !== "" && $this->var['subdiv']['subd_url'] !== $this->var['subdiv']['subd_hoaweb']){
      return e107::getParser()->makeClickable($this->var['subdiv']['subd_url'],'url',array('ext'=>1));
      }
    }
  
  function sc_community_type($parm){
    if(intval($this->var['prop_subdiv']) > 0){
      return  e107::getParser()->toHTML(EST_GEN_SUBDIVTYPE[$this->var['subdiv']['subd_type']]);
      }
    }
  
  
  function sc_community_desc($parm){
    if(trim($this->var['subdiv']['subd_description']) !== ''){
      return e107::getParser()->toHTML($this->var['subdiv']['subd_description'],true);
      }
    }
  
  
  function sc_prop_hours($parm){
    if(trim($this->var['prop_hours']) !== ''){
      $tp = e107::getParser();
      $hoursAry = (is_array($this->var['prop_hours']) ? $this->var['prop_hours'] : e107::unserialize($this->var['prop_hours']));
      if(is_array($hoursAry)){
        $wkdays = estGetPHPCalDays($locale);
        if(is_array($wkdays)){
          foreach($wkdays as $wk=>$wv){
            $txt .= '
            <div class="estPropSchedDay">
              <div>'.$wv.'</div>';
            if($hoursAry[$wk][0] == 0){
              $txt .= '
              <div>'.EST_GEN_NOVIEWING.'</div>';
              }
            else{
              $txt .= '
              <div>'.date('g:i A',strtotime($hoursAry[$wk][1])).'</div>
              <div>'.date('g:i A',strtotime($hoursAry[$wk][2])).'</div>';
              }
            $txt .= '
            </div>';
            }
          }
        }
      
      $AGENT = $this->estGetSeller();
      $vars = array('x'=>$AGENT['agent_name']);
      $note = '<div class="estPropViewSchedNote1">'.$tp->lanVars(EST_PROP_HRS1,$vars,false).'</div>';
      
      if($parm['mode'] == 'menu'){
        $scns = e107::getRender();
        $scns->setStyle('menu'); 
        return $scns->tablerender(EST_PROP_HRS,$note.'<div class="estPropMenuSchedCont">'.$txt.'</div>','estSideMenuPropHours',true);
        }
      else{return $note.$txt;}
      }
    }
  
  
  
  function sc_community_hoa($parm){
    $tp = e107::getParser();
    
    $subapr = intval($this->var['subdiv']['subd_hoaappr']);
    $subreq = intval($this->var['subdiv']['subd_hoareq']);
    $subfee = intval($this->var['subdiv']['subd_hoafee']);
    $subfrq = intval($this->var['subdiv']['subd_hoafrq']);
    $sublnd = intval($this->var['subdiv']['subd_hoaland']);
    
    
    $liarr = array();
    if(trim($this->var['subdiv']['subd_hoaweb']) !== "" && $this->var['subdiv']['subd_hoaweb'] == $this->var['subdiv']['subd_url']){
      $liarr['txt'][0] = '<h4>'.$tp->makeClickable($this->var['subdiv']['subd_hoaweb'],'url',array('ext'=>1)).'</h4>';
      }
    
    if(trim($this->var['subdiv']['subd_hoaname']) !== '' && $this->var['subdiv']['subd_hoaname'] !== $this->var['subdiv']['subd_name']){
      $liarr['txt'][1] = '<h4>'.$tp->toHTML($this->var['subdiv']['subd_hoaname'],true).' '.EST_GEN_HOMEOWNASS.'</h4>';
      }
    
    if(trim($this->var['subdiv']['subd_url']) !== "" && $this->var['subdiv']['subd_url'] !== $this->var['subdiv']['subd_hoaweb']){
      $liarr['txt'][2] = '<h4>'.$tp->makeClickable($this->var['subdiv']['subd_hoaweb'],'url',array('ext'=>1)).'</h4>';
      }
    
    if($subapr == 1 || $subreq == 1 || $subfee > 0){
      $liarr['ul'][0] = ($subreq == 1 ? EST_GEN_HOAREQ1 : EST_GEN_HOAREQ2);
      if($subfee > 0){
        $liarr['ul'][1] = '<a class="estSTlnk" href="#hoaDisclaimers">'.EST_PROP_HOAFEES.': '.$subfee.($subfrq > 0 ? ' '.EST_HOAFREQ[$subfrq] : '').'²</a>';
        }
      if($subapr == 1){
        $liarr['ul'][2] = '<a class="estSTlnk" href="#hoaDisclaimers">'.EST_GEN_HOAAPPR2.'³</a>';
        }
      }
    
    if(count($liarr) > 0){
      $txt = '';//'<h4>'.EST_GEN_HOADEF1.'</h4>';
      if(isset($liarr['txt']) && count($liarr['txt']) > 0){
        foreach($liarr['txt'] as $k=>$v){$txt .= $v;}
        }
      
      if(isset($liarr['ul']) && count($liarr['ul']) > 0){
        $txt .= '<ul class="WD100">';
        foreach($liarr['ul'] as $k=>$v){$txt .= '<li>'.$v.'</li>';}
        $txt .= '</ul>';
        }
      $txt .= '';
      }
    return $txt;
    unset($subapr,$subreq,$subfee,$subfrq,$sublnd,$txt);
    }
  
  
  function sc_prop_community($parm){
    if(!isset($this->var['subdiv'])){return '';}
    if(!is_array($this->var['subdiv'])){return '';}
		
    $tp = e107::getParser();
    
    if($parm['get'] == 'capt'){
      if(intval($this->var['subdiv']['subd_idx']) == 0){return'';}
      return $tp->toHTML(EST_GEN_COMMUNITY.': '.$this->var['subdiv']['subd_name']);
      }
    
    
    //$EST_HOAREQD[$subd_hoareq]
    //EST_HOAFREQ
    return $txt;
    }
  
  function minifeatures($v){
    if(is_array($v['features']) && count($v['features']) > 0){
      foreach($v['features']['txt'] as $k=>$v){
        $txt .= '<div class="estViewSpTxt"><span class="FWB">'.$k.'</span>';
        if(is_array($v)){
          $txt .= '<ul class="estULOutside">';
          foreach($v as $sk=>$sv){
            if(trim($sv) !== ''){$txt .= '<li class="WSYWRP">'.$sk.': '.str_replace(',',', ',$sv).'</li>';} //· • 
            else{$txt .= '<li>'.$sk.'</li>';}
            }
          $txt .= '</ul>';
          }
        else{$txt .= ': '.$v;}
        $txt .= '</div>';
        }
      }
    return e107::getParser()->toHTML($txt);
    }
  
  function minithumb($v){
    if(is_array($v['media']) && count($v['media']) > 0){
      $g1 = (!isset($v['media'][1]) && isset($v['media'][0]) ? 0 : 1);
      $EST_PREF = e107::pref('estate');
      $galCt = count($v['media']);
      if(intval($EST_PREF['slideshow_act']) == 1 && $galCt > 1){
        $cssName = 'estMiniThumb-'.intval($v['media'][$g1]['p']).'-'.intval($v['media'][$g1]['v']).'-'.intval($v['media'][$g1]['l']).'-img'; 
        $stime = intval($EST_PREF['slideshow_time']);
        $sdelay = intval($EST_PREF['slideshow_delay']);
        if($sdelay == 0){$sdelay = ($galCt > 7 ? ceil($galCt / 2) : 4);}
        
        $iStep = round(99 / $galCt, 2);
        $iPct = 0;
        foreach($v['media'] as $mk=>$mv){
          $pth = estImgPaths($mv);
          if($mk == 0){
            $urlist = 'url(\''.$pth[0].'\')';
            $keyframes = '@keyframes '.$cssName.'{
                0%, 100% {background-image: url("'.$pth[0].'");}
                ';
            }
          else{
            $urlist .= ',url(\''.$pth[0].'\')';
            $keyframes .= $iPct.'% {background-image: url("'.$pth[0].'");}
                ';
            }
          
          $iPct = ($iStep + $iPct);
          unset($mk,$mv);
          }
        return '
            <style>
              '.$keyframes.'}
              #'.$cssName.'{
                background-image:'.$urlist.';
                animation: '.$cssName.' '.($galCt * intval($stime)).'s infinite;
                animation-delay: '.$sdelay.'s;
                visibility: visible !important;
                -webkit-animation-name: '.$cssName.';
                -webkit-animation-duration: '.($galCt * intval($stime)).'s;
                -webkit-animation-iteration-count: infinite;
                }
            </style>
            <div id="'.$cssName.'" class="estImgSlide"><div class="estSSict">'.$galCt.'</div></div>';
        unset($pth,$cssName,$urlist,$keyframes,$galCt,$sdelay,$stime);
        }
      else{
        $pth = estImgPaths($v['media'][0]);
        return '
            <div class="estImgSlide" style="background-image:url('.$pth[0].')"></div>';
        unset($pth,$galCt);
        }
      }
    else{
      return '
            <div class="estImgSlide"></div>';
      }
    }
  
  
  function sc_community_spaces($parm){
    $key = ($parm['for'] == 'city' ? 'city' : 'subd');
    if(is_array($this->var['subdiv']['spaces'][$key])){
      if(count($this->var['subdiv']['spaces'][$key]) > 0){
        $tp = e107::getParser();
        foreach($this->var['subdiv']['spaces'][$key] as $k=>$v){
          $txt .= '
          <div class="estViewSpaceBtn estTableGroupTile">
            <div class="estSpTtl">'.$tp->toHTML($v['space_name'],true).'</div>';
          $txt .= $this->minithumb($v);
          $txt .= $this->minifeatures($v);
          $txt .= '
            <p class="DTH128">'.$tp->toHTML($v['space_description'],true).'</p>';
          $txt .= '
          </div>';
          }
        return $txt;
        unset($txt,$k,$v);
        }
      }
    }
  
  function sc_community_props($parm){
    //NOT APPARENTLY USED
    $key = ($parm['for'] == 'city' ? 'city' : 'subd');
    if(is_array($this->var['subdiv']['props'][$key])){
      if(count($this->var['subdiv']['props'][$key]) > 0){
        $tp = e107::getParser();
        foreach($this->var['subdiv']['props'][$key] as $k=>$v){
          $txt .= '
          <div class="estViewSpaceBtn estTableGroupTile">
            <div class="estSpTtl">'.$tp->toHTML($v['prop_name'],true).'</div>';
          $txt .= $this->minithumb($v);
          $txt .= $this->minifeatures($v);
          $txt .= '
            <p class="DTH128">'.$tp->toHTML($v['prop_summary'],true).'</p>
          </div>';
          }
        return $txt;
        unset($txt,$k,$v);
        }
      }
    }
  
  
  
  function sc_prop_latlng($parm){}
  
  function sc_social_links($parm){
    // id="estFBshareBtn"
    $res = '<div class="fb-share-button btn ILBLK" data-href="'.$this->var['prop_link'].'" data-layout="button_count" data-size="large"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u='.$this->var['prop_link'].'&src=sdkpreparse" class="fb-xfbml-parse-ignore"><i class="fa fa-facebook" aria-hidden="true"></i></a></div>';
    //PROP_NEWICON
    return $res;
    }
  
  
  function sc_linkme($parm){
    if($parm['as']){
      switch($parm['as']){
        case 'url': 
          return $this->var['link_url'];
          break;
        case 'a': 
          return '<a href="'.$this->var['link_url'].'" class="'.($parm['class'] ? $parm['class'] : 'link').'">'.$this->var['prop_name'].'</a>';
          break;
        case 'button': 
          return '<button class="btn '.($parm['class'] ? $parm['class'] : 'btn-default').'" onclick="window.location.assign(\''.$this->var['link_url'].'\')">'.$this->var['prop_name'].'</button>';
          break;
        }
      }
    else{
      return '<a href="'.$this->var['link_url'].'"'.($parm['class'] ? ' class="'.$parm['class'].'"' : '').'>'.$this->var['prop_name'].'</a>';
      }
    }
  
  
  function sc_spaces_menu($parm){
		$tp = e107::getParser();
    
    $txt = '<div id="estSpacesMenu">Spaces Here</div>';
    if($txt == ''){return '';}
    return e107::getRender()->tablerender(EST_GEN_SPACES, $txt, 'estSideMenuSpaces',true);
    }
  
  
  
  function sc_prop_newicon(){
		$tp = e107::getParser();
    if(EST_USERPERM > 0){
      return '<a title="'.EST_GEN_NEW.'"><i class="fa fa-plus"></i></a><p><a class="btn btn-primary noMobile" href="'.EST_PTH_ADMIN.'?action=new" title="'.EST_GEN_FULLADDLIST.'"><i class="fa fa-plus"></i> '.EST_GEN_FULLADDLIST.'</a><a class="btn btn-primary" href="'.EST_PATHABS_LISTINGS.'?new.0.0" title="'.EST_GEN_QUICKADDLIST.'"><i class="fa fa-plus"></i> '.EST_GEN_QUICKADDLIST.'</a></p>';
      }
    if(intval($GLOBALS['EST_PREF']['public_act']) !== 0 && USERID > 0 && check_class($GLOBALS['EST_PREF']['public_act'])){
      return '<a class="FR" href="'.EST_PATHABS_LISTINGS.'?new.0.0" title="'.EST_GEN_NEW.'"><i class="fa fa-plus"></i></a>';
      }
    }
  
  
  function estGetSeller(){
		$tp = e107::getParser();
    $ret = array();
    
    $USRDTA = array(
      'user_id'=>$this->var['user_id'],
      'user_name'=>$this->var['user_name'],
      'user_loginname'=>$this->var['user_loginname'],
      'user_email'=>$this->var['user_email'],
      'user_admin'=>$this->var['user_admin'],
      'user_perms'=>$this->var['user_perms'],
      'user_class'=>$this->var['user_class'],
      'user_signature'=>$this->var['user_signature'],
      'user_image'=>$this->var['user_image']
      );
    
    $ret['imgurl'] = $tp->toAvatar($USRDTA,array('type'=>'url'));
    
    $ret['agylogo'] = $tp->thumbUrl(e107::pref('sitelogo'),false,false,true);
    $ret['agency_name'] = EST_GEN_PRIVATE.' '.EST_GEN_SELLER;
    
    unset($USRDTA);
    
    
    if(intval($this->var['agency_idx']) > 0){
      $ret['agency_idx'] = intval($this->var['agency_idx']);
      $ret['agency_name'] = $this->var['agency_name'];
      if(intval($this->var['agency_imgsrc']) == 1 && trim($this->var['agency_image']) !== ''){
        $ret['agylogo'] = EST_PTHABS_AGENCY.$tp->toHTML($this->var['agency_image']);
        }
      }
    
    
    if(intval($this->var['agent_idx']) > 0){
      $ret['agent_roll'] = EST_GEN_AGENT;
      $ret['agent_name'] = $this->var['agent_name'];
      $ret['agent_uid'] = intval($this->var['agent_uid']);
      if(intval($this->var['agent_imgsrc']) == 1 && trim($this->var['agent_image']) !== ""){
        $ret['imgurl'] = EST_PTHABS_AGENT.$this->var['agent_image'];
        }
      
      if($ACONT = e107::getDb()->retrieve("SELECT * FROM #estate_contacts WHERE contact_tabidx=".intval($this->var['agent_idx'])." ORDER BY contact_ord ASC ",true)){
        foreach($ACONT as $k=>$v){
          $ret['contacts'][$v['contact_tabkey']][$k] = array($v['contact_key'],$v['contact_data']);
          }
        }
      else{
        $ret['contacts'][6][0] = array($tp->toHTML(EST_GEN_EMAIL),$tp->toHTML($this->var['user_email']));
        }
      }
    else{
      $ret['agent_roll'] = EST_GEN_PRIVATESELLER;
      $ret['agent_name'] = $this->var['user_name'];
      $ret['agent_uid'] = intval($this->var['user_id']);
      $ret['agent_txt1'] = $this->var['user_signature'];
      //if(intval($this->var['user_hideemail']) !== 1){
        $ret['contacts'][6][0] = array($tp->toHTML(EST_GEN_EMAIL),$this->var['user_email']);
        //}
      
      if(check_class($GLOBALS['EST_PREF']['public_act']) && USERID > 0 && USERID == intval($this->var['prop_uidcreate'])){
        $ret['oa'] = 1;
        }
      }
    
    if(EST_USERPERM > 0){
      $url1 = EST_PTH_ADMIN.'?action=edit&id='.intval($this->var['prop_idx']);
      $url2 = EST_PATHABS_LISTINGS.'?edit.'.intval($this->var['prop_idx']);
      
      $XGO = 0; // if > 0 then no edit
      if(EST_USERPERM < 4){
        $XRP = explode('.',$this->var['user_perms']);
        $XRC = explode(',',$this->var['user_class']);
        //
        if(intval($this->var['prop_idx']) > 0){
          if(in_array('0',$XRP) && intval($this->var['agent_uid']) > 0 && intval($this->var['agent_uid']) !== USERID){$XGO++;}
          if(EST_USERPERM == 3 && USERID !== intval($this->var['agent_uid']) && in_array(ESTATE_ADMIN,$XRC) && intval($this->var['agent_uid']) > 0){$XGO++;}
          
          if(EST_USERPERM == 2 && USERID !== intval($this->var['agent_uid'])){
            if(intval($this->var['agent_uid']) == 0 && EST_USERPERM < intval(e107::pref('estate','public_mod'))){$XGO++;}
            if(intval($this->var['agent_agcy']) > 0 && intval($this->var['agent_agcy']) !== intval(EST_AGENCYID)){$XGO++;}
            if(in_array(ESTATE_ADMIN,$XRC) || in_array(ESTATE_MANAGER,$XRC) && USERID !== intval($this->var['agent_uid'])){$XGO++;}
            if(intval($this->var['user_admin']) > 0){
              foreach($XRC as $tk=>$tv){
                if(!in_array($tv,EST_USERMANAGE)){$XGO++;}
                }
              }
            }
          elseif(EST_USERPERM == 1){
            if(intval($this->var['prop_uidcreate']) !== USERID || intval($ret['agent_uid']) !== USERID){$XGO++;}
            }
          }
        }
      
      
      
      if($XGO == 0 || intval($this->var['prop_uidcreate']) == USERID){
        //PROP_EDITICONS
        $ret['edit'] = '<a title="'.EST_GEN_EDIT.'"><i class="fa fa-pencil-square-o"></i></a><p><a class="btn btn-primary noMobile" href="'.$url1.'" title="'.EST_GEN_FULLEDIT.'"><i class="fa fa-pencil-square-o"></i> '.EST_GEN_FULLEDIT.'</a><a class="btn btn-primary" href="'.$url2.'.0" title="'.EST_GEN_QUICKEDIT.'"><i class="fa fa-pencil-square-o"></i> '.EST_GEN_QUICKEDIT.'</a></p><a id="estFEReorder" title="'.EST_GEN_REORDER.'"><i class="fa fa-navicon"></i></a>';
      
        $ret['lstedit'] = '<button class="estCardTopBtn" data-eurl="'.EST_PATHABS_LISTINGS.'?edit.'.intval($this->var['prop_idx']).'"  title="'.EST_GEN_EDIT.'"><i class="fa fa-pencil-square-o"></i></button>';
        //estReordMenu
        //if(EST_USERPERM == 4){$ret['edit'] .= '<a id="estFEReorder" title="'.EST_GEN_REORDER.'"><i class="fa fa-navicon"></i></a>'; }
        }
      }
    elseif(intval($GLOBALS['EST_PREF']['public_act']) !== 0 && USERID > 0 && check_class($GLOBALS['EST_PREF']['public_act'])){
      if(intval($this->var['prop_agent']) === 0 && intval($this->var['prop_uidcreate']) == USERID){
        $ret['edit'] = '<a class="FR" href="'.EST_PATHABS_LISTINGS.'?edit.'.intval($this->var['prop_idx']).'" title="'.EST_GEN_EDIT.'"><i class="fa fa-pencil-square-o"></i></a><a id="estFEReorder" title="'.EST_GEN_REORDER.'"><i class="fa fa-navicon"></i></a>';
        $ret['lstedit'] = '<button class="estCardTopBtn" data-eurl="'.EST_PATHABS_LISTINGS.'?edit.'.intval($this->var['prop_idx']).'"  title="'.EST_GEN_EDIT.'"><i class="fa fa-pencil-square-o"></i></button>';
        }
      }
    $ret['eicon'] = $ret['lstedit'];
    
    $ret['lstagent'] = '<div class="estCardTopR"><div class="estCardTopRImg" style="background-image:url('.$ret['imgurl'].')"></div><div class="estCardTopRL1"><div>'.$ret['agent_roll'].'</div><div>'.$ret['agent_name'].'</div></div></div>';
    
    $ret['lstedit'] = '<div class="estCardTopR">'.$ret['lstedit'].'<div class="estCardTopRImg" style="background-image:url('.$ret['imgurl'].')"></div><div class="estCardTopRL1"><div>'.$ret['agent_roll'].'</div><div>'.$ret['agent_name'].'</div></div></div>';
      
    
    unset($ACONT,$XRP,$XRC,$XGO,$url1,$url2);
    return $ret;
    }
  
  
  
  
  function sc_prop_agentcard($parm){
		$tp = e107::getParser();
    $EST_PREF = e107::pref('estate');
    $AGENT = $this->estGetSeller();
    
    if($AGENT['error']){
      if(ADMIN){e107::getMessage()->addWarning($AGENT['error']);}
      return;
      }
    
    if(intval($AGENT['oa']) == 1){$dtastr = $this->estDataStr($AGENT);}
    
    $HideEmail = 0;
    $CFormOK = check_class($EST_PREF['contact_class']);
    if($CFormOK){
      //contact_mode 0 or 2 hides email if form enabled
      if($EST_PREF['contact_mode'] == 0 || $EST_PREF['contact_mode'] == 2){$HideEmail++;}
      }
    
    //'.$dtastr.'
    $ret = '
      <div id="estAgCard" class="estAgCard'.(intval($AGENT['oa']) == 1 ? ' estOA' : '').'" >
        <div class="estAgCardInner">';
    
    if(trim($AGENT['imgurl']) !== ''){
      $ret .= '<div class="estAgtAvatar" style="background-image:url(\''.$AGENT['imgurl'].'\')"></div>';
      }
    
    $ret .= '<div class="estAgtInfo1"><h3>'.$tp->toHTML($AGENT['agent_name']).'</h3>';
    
    //https://estate.vodhin.org/user.php?id.intval($AGENT['agent_uid'])
    
    
    $ret .= '<h4>'.$tp->toHTML($AGENT['agency_name']).'</h4>';
    $ret .= (trim($AGENT['agent_txt1']) !== '' ? '<p class="FSITAL">'.$tp->toHTML($AGENT['agent_txt1']).'</p>' : '');

    if(is_array($AGENT['contacts'][6])){
      if(count($AGENT['contacts'][6]) > 0){
        $ret .= '<div class="estAgContact">';
        foreach($AGENT['contacts'][6] as $ck=>$cv){
          $CONTKEY = $tp->toHTML($cv[0]);
          if($HideEmail > 0 && strtoupper($CONTKEY) == strtoupper(EST_GEN_EMAIL)){}
          else{$ret .= '<div>'.$CONTKEY.' '.$tp->toHTML($cv[1]).'</div>';}
          }
        $ret .= '</div>';
        }
      }
    
    $ret .= '
          </div>
        </div>';
    
    if($CFormOK){
      $this->var['msg_to_uid'] = $AGENT['agent_uid'];
      $this->var['msg_from_uid'] = USERID;
      $this->var['prop_seller'] = $AGENT['agent_name'];
      $ret .= '
      </div>
      <div id="estMsgModule" class="noPADTB">
        <div id="estMsgCard" class="estAgCard TAL">';
      $ret .= est_msg_form($this->var);
      $ret .= '
        </div>';
      }
    
    $ret .= '
      </div>
    ';
    
    
    if($AGENT['agency']){
      
      }
    
    unset($AGENT);
    return $ret;
    }
  
  
  
  function sc_admin_reorder($parm){
    return '
    <div class="estReordCont" data-sect="'.$parm['ok'].'">
      <div class="estReordHandle">
        <i class="fa fa-navicon"></i>
        <input type="checkbox" id="template-'.$parm['area'].'-ord['.$parm['templ'].']['.$parm['ok'].']" name="template_'.$parm['area'].'_ord['.$parm['templ'].']['.$parm['ok'].']" class="estTmplInpt" value="1"'.($parm['ov'] == 1 ? ' checked="checked"' :'').' title="'.EST_GEN_ENABLEDIS.' '.$parm['ok'].'" /><label for="template-'.$parm['area'].'-ord['.$parm['templ'].']['.$parm['ok'].']" title="'.EST_GEN_ENABLEDIS.' '.$parm['ok'].'">'.$parm['ok'].'</label>
      </div>
      <div class="estReordDiv'.($parm['ov'] == 1 ? '' : ' noDISP').'">';
    }
  
  function sc_admin_reorder_menu($parm){
  	$tp = e107::getParser();
    $EST_PREF = e107::pref('estate');
    include(e_PLUGIN.'estate/templates/estate_template.php');
    $area = strtolower($parm['area']);
    $tmplname = (trim($this->var['prop_template_'.$area]) !== '' ? $this->var['prop_template_'.$area] : $EST_PREF['template_'.$area]);
    
    if(is_array($ESTATE_TEMPLATE[$area])){
      $txt = '<div class="estReordMenu"><input type="hidden" name="old_template_'.$area.'" value="'.$tmplname.'" />
      <input type="hidden" name="template_idx_'.$area.'" value="'.intval($this->var['prop_idx']).'" />';
      $txt .= '<select id="template-'.$area.'" name="template_'.$area.'" class="tbox form-control estAdmTemplSel estTmplInpt" value="">';
      foreach($ESTATE_TEMPLATE[$area] as $sk=>$sv){
        $txtct = ($sv['txt'] && is_array($sv['txt']) ? count($sv['txt']) : 1);
        $txt .= '<option value="'.$sk.'"'.($sk == $tmplname ? ' selected="selected"' : '').' data-ct="'.$txtct.'">'.$sk.''.($txtct > 1 ? ' ('.EST_GEN_REORDERABLE.')' : '').'</option>';
        }
      $txt .= '</select><input type="submit" name="estSave'.$area.'Layout" class="btn btn-primary estAdmBtnSave" value="'.EST_GEN_SAVE.'" data-area="'.$area.'"data-pref1="template_'.$area.'" data-pref2="template_'.$area.'_ord" data-template="'.$parm['tkey'].'" />';
      
      $tmct2 = (is_array($ESTATE_TEMPLATE[$area][$parm['tkey']]['txt']) ? count($ESTATE_TEMPLATE[$area][$parm['tkey']]['txt']) : 0);
      $txt .= '
      <div id="estTmplMenuMsg-'.$area.'-2" class="estTmplMenuMsg"'.($tmct2 > 1 ? '' : ' style="display:block;"').'>'.EST_PREF_TEMPLATE_NOORD.'</div>
      <div id="estTmplMenuMsg-'.$area.'-1" class="estTmplMenuMsg">'.EST_PREF_CLICKSAVETEMPL.'</div>
      </div>';
      return $txt;
      }
    }
  
  //PROP_EDITICONS
  
  function estGetMsgIcon(){
    $EST_PREF = e107::pref('estate');
    //AGENT_ROLL
    if(check_class($EST_PREF['contact_class'])){
      $msgd = (is_array($this->var['msgd']) ? count($this->var['msgd']) : array()); //estGetPrevMsgs()
      if($msgd > 0){
        $mctr = 0;
        $mctu = 0;
        foreach($this->var['msgd'] as $mk=>$mv){
          if($mv['msg_read'] > 0){$mctr++;}
          else{$mctu++;}
          }
        return '<button id="estMsgdIcon-'.intval($this->var['prop_idx']).'" class="estCardTopBtn"  title="'.EST_MSG_YOUHAVESENT.' '.$msgd.' '.($msgd == 1 ? EST_GEN_MESSAGE : EST_GEN_MESSAGES).' '.EST_MSG_TOSELLER.' ('.$mctr.' '.EST_MSG_READ.', '.$mctu.' '.EST_MSG_UNREAD.')"><i class="fa fa-envelope'.($mctr > 0 ? '-open': '').'"></i></i></button>';
        }
      return false;
      }
    return false;
    }
  
  
  function sc_prop_like_icon($parm){
    if($parm['for'] == 'card'){
      $MSGICON = $this->estGetMsgIcon();
      if($MSGICON){return $MSGICON;}
      }
    
    $lbtns = '';
    
    if(check_class(e107::pref('estate','listing_save'))){
      $lbtns .= '<button id="estLikeIcon-'.intval($this->var['prop_idx']).'" class="estCardTopBtn'.$this->var['saved'].'" data-laid="'.intval($this->var['prop_agent']).'" data-lpid="'.intval($this->var['prop_idx']).'" title="'.EST_GEN_SAVE.'"><i class="fa fa-regular fa-heart"></i><i class="fa fa-solid fa-heart"></i></button>';
      }
    return $lbtns;
    }
  
  
  function sc_prop_saved_list($parm){
		$tp = e107::getParser();
    
    $txt = '
    <div id="estSavedModule" class="noPADTB">';
    
    $txt .= '';
    $txt .= '
    </div>';
    return $txt;
    
    }
  
  
  
  
  
  
  function estPropStat($DTA){
    if(intval($DTA['prop_status']) == 5){$ret = (intval($DTA['prop_listype']) == 0 ? EST_GEN_OFFMARKET : EST_GEN_SOLD);}//'';
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
  
  
  
  
  
  function sc_prop_status($parm = ''){
    return $this->estPropStat($this->var);
    }
  
  
  function sc_prop_price($parm = ''){
    return estGetListPrice($this->var);//$this->estPropPrice($this->var);
    }
  
  
  function sc_prop_bullets1($parm){
		$tp = e107::getParser();
    $ret = '';
    
    $stat4 = $this->sc_prop_status('bullet');
    $price = $this->sc_prop_price();
    if($stat4){$ret = $tp->toHTML($stat4);}
    if($price){$ret .= ($ret ? ' • ' : '').$tp->toHTML($price);}
    return $ret;
    }
    
  function estDataStr($dta){
    $tp = e107::getParser();
    $dtaStr = '';
    foreach($dta as $k=>$v){$dtaStr .= ' data-'.$k.'="'.$v.'"';}
    unset($dta,$k,$v);
    return $dtaStr;
    }
  
  // †¹ ² ³ ⁴ ⁵ ⁶ ⁷ ⁸ ⁹ ⁰
}