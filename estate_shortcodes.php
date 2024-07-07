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
		$tp = e107::getParser();
    return $tp->toHTML($this->var['city_name']).(trim($this->var['state_init']) ? ', '.$this->var['state_init'] : '');
    }
  
  function sc_prop_subdivname($parm){
    if(intval($this->var['prop_subdiv']) > 0){
      $ret = '<span>'.e107::getParser()->toHTML($GLOBALS['EST_SUBDIV'][$this->var['prop_subdiv']]['subd_name']).'</span>';
      }
    return $ret;
    }
  
  function sc_prop_thmsty($parm){
    if(trim($this->var['img'][1]['t']) !== ''){
      return 'background-image:url(\''.e107::getParser()->toHTML(EST_PTHABS_PROPTHM.$this->var['img'][1]['t']).'\')';
      }
    else{return 'background-image:url(\''.EST_PATHABS_IMAGES.'imgnotavail.png\')';}
    }
  
  
  
  function sc_prop_points($parm){
		$tp = e107::getParser();
    $ret = '<ul class="estDet2Cont">';
    if($this->var['prop_type'] !== 0){
      $ret .= '
              <li>'.($this->var['prop_floorct'] !== 0 ? $this->var['prop_floorct'].' '.EST_GEN_STORY.' ' : '').$GLOBALS['EST_PROPTYPES'][$this->var['prop_type']].'</li>';
      }
    
    //$prop_condit
      
    if($this->var['prop_zoning'] !== 0){
      $ret .= '
              <li>'.$GLOBALS['EST_ZONING'][$this->var['prop_zoning']].'</li>';
      }
    if($this->var['prop_yearbuilt'] !== 0){
      $ret .= '
              <li>'.EST_PROP_YEARBUILT.': '.$this->var['prop_yearbuilt'].'</li>';
      }
    if($this->var['prop_intsize'] !== 0){
      $ret .= '
              <li>'.EST_PROP_INTSIZE.': '.$this->var['prop_intsize'].' '.$GLOBALS['EST_DIM1UNITS'][$this->var['prop_dimu1']][0].'</li>';
      }
    if($this->var['prop_landsize'] !== 0){
      $ret .= '
              <li>'.EST_PROP_LANDSIZE.': '.$this->var['prop_landsize'].' '.$GLOBALS['EST_DIM2UNITS'][$this->var['prop_dimu2']].'</li>';
      }
    $ret .= '
            </ul>';
    return $ret;
    }
  
  
  function sc_prop_feature_extended($parm){
		$tp = e107::getParser();
    $ret = '<ul class="estDet2Cont">';
    if($this->var['prop_bedtot'] !== 0){
      $ret .= '
                <li>'.EST_GEN_BEDTOT.': '.$this->var['prop_bedtot'].'</li>';
      }
    if($this->var['prop_bathtot'] !== 0){
      $ret .= '
                <li>'.EST_GEN_BATHROOMS.': '.$this->var['$prop_bathtot'].' ('.$this->var['prop_bathfull'].' '.EST_GEN_FULL.', '.$this->var['prop_bathhalf'].' '.EST_GEN_HALF.')</li>';
      }
    
    if($this->var['prop_bedmain'] !== 0){
      $ret .= '
                <li>'.EST_GEN_BEDMAIN.': '.$this->var['prop_bedmain'].'</li>';
      }
    if($this->var['prop_bathmain'] !== 0){
      $ret .= '
                <li>'.EST_GEN_BATHMAIN.': '.$this->var['prop_bathmain'].'</li>';
      }
    
    if($this->var['prop_floorno'] !== 0){
      $ret .= '
                <li>'.EST_GEN_FLOORNO.': '.$this->var['prop_floorno'].'</li>';
      }
    if($this->var['prop_bldguc'] !== 0){
      $ret .= '
                <li>'.EST_GEN_UNITSBLDG.': '.$this->var['prop_bldguc'].'</li>';
      }
    if($this->var['prop_complxuc'] !== 0){
      $ret .= '
                <li>'.EST_GEN_UNITSCOMPLX.': '.$this->var['prop_complxuc'].'</li>';
      }
    
    $ret .= '
              </ul>';
    return $ret;
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
    if($SPACES && count($SPACES) > 0){
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
    if($SPACES && count($SPACES) > 0){
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
  
  
  
  
  
  
  
  
  
  
  
  function sc_prop_latlng($parm){}
  
  
  
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
    
    return $txt;
    }
  
  
  
  
  function sc_prop_newicon(){
		$tp = e107::getParser();
    if(EST_USERPERM > 0){
      return '<a title="'.EST_GEN_NEW.'"><i class="fa fa-plus"></i></a><p><a class="btn btn-primary noMobile" href="'.EST_PTH_ADMIN.'?action=create" title="'.EST_GEN_FULLADDLIST.'"><i class="fa fa-plus"></i> '.EST_GEN_FULLADDLIST.'</a><a class="btn btn-primary" href="'.EST_PATHABS_LISTINGS.'?new.0.0" title="'.EST_GEN_QUICKADDLIST.'"><i class="fa fa-plus"></i> '.EST_GEN_QUICKADDLIST.'</a></p>';
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
        $ret['edit'] = '<a title="'.EST_GEN_EDIT.'"><i class="fa fa-pencil-square-o"></i></a><p><a class="btn btn-primary noMobile" href="'.$url1.'" title="'.EST_GEN_FULLEDIT.'"><i class="fa fa-pencil-square-o"></i> '.EST_GEN_FULLEDIT.'</a><a class="btn btn-primary" href="'.$url2.'.0" title="'.EST_GEN_QUICKEDIT.'"><i class="fa fa-pencil-square-o"></i> '.EST_GEN_QUICKEDIT.'</a></p>';
      
        $ret['lstedit'] = '<button class="estCardTopBtn" data-eurl="'.EST_PATHABS_LISTINGS.'?edit.'.intval($this->var['prop_idx']).'"  title="'.EST_GEN_EDIT.'"><i class="fa fa-pencil-square-o"></i></button>';
        
        if(EST_USERPERM == 4){$ret['edit'] .= '<a id="estFEReorder" title="'.EST_GEN_REORDER.'"><i class="fa fa-navicon"></i></a>'; }
        }
      }
    elseif(intval($GLOBALS['EST_PREF']['public_act']) !== 0 && USERID > 0 && check_class($GLOBALS['EST_PREF']['public_act'])){
      if(intval($this->var['prop_agent']) === 0 && intval($this->var['prop_uidcreate']) == USERID){
        $ret['edit'] = '<a class="FR" href="'.EST_PATHABS_LISTINGS.'?edit.'.intval($this->var['prop_idx']).'" title="'.EST_GEN_EDIT.'"><i class="fa fa-pencil-square-o"></i></a>';
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
    //if(defined("ESTAGENTRENDERED")){return '<div id="estAgentCardDupe"></div>';}
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

    
    if(count($AGENT['contacts'][6]) > 0){
      $ret .= '<div class="estAgContact">';
      foreach($AGENT['contacts'][6] as $ck=>$cv){
        $CONTKEY = $tp->toHTML($cv[0]);
        if($HideEmail > 0 && strtoupper($CONTKEY) == strtoupper(EST_GEN_EMAIL)){}
        else{$ret .= '<div>'.$CONTKEY.' '.$tp->toHTML($cv[1]).'</div>';}
        }
      $ret .= '</div>';
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
    if($ESTATE_TEMPLATE[$area]){
      $txt = '<div class="estReordMenu">';
      $txt .= '<select id="template-'.$area.'" name="template_'.$area.'" class="tbox form-control estAdmTemplSel estTmplInpt" value="">';
      foreach($ESTATE_TEMPLATE[$area] as $sk=>$sv){
        $txtct = ($sv['txt'] && is_array($sv['txt']) ? count($sv['txt']) : 1);
        $txt .= '<option value="'.$sk.'"'.($sk == $EST_PREF['template_'.$area] ? ' selected="selected"' : '').' data-ct="'.$txtct.'">'.$sk.''.($txtct > 1 ? ' ('.EST_GEN_REORDERABLE.')' : '').'</option>';
        }
      $txt .= '</select><input type="submit" name="estSave'.$area.'Layout" class="btn btn-primary estAdmBtnSave" value="'.EST_GEN_SAVE.'" data-area="'.$area.'"data-pref1="template_'.$area.'" data-pref2="template_'.$area.'_ord" data-template="'.$parm['tkey'].'" />';
      
      $tmct2 = ($ESTATE_TEMPLATE[$area][$parm['tkey']]['txt'] ? count($ESTATE_TEMPLATE[$area][$parm['tkey']]['txt']) : 0);
      $txt .= '
      <div id="estTmplMenuMsg-'.$area.'-2" class="estTmplMenuMsg"'.($tmct2 > 1 ? '' : 'style="display:block;"').'>'.EST_PREF_TEMPLATE_NOORD.'</div>
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
      $msgd = (isset($this->var['msgd']) ? count($this->var['msgd']) : array()); //estGetPrevMsgs()
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
    
    if(check_class(e107::pref('estate','listing_save'))){
      return '<button id="estLikeIcon-'.intval($this->var['prop_idx']).'" class="estCardTopBtn'.$this->var['saved'].'" data-laid="'.intval($this->var['prop_agent']).'" data-lpid="'.intval($this->var['prop_idx']).'" title="'.EST_GEN_SAVE.'"><i class="fa fa-regular fa-heart"></i><i class="fa fa-solid fa-heart"></i></button>';
      }
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
  
  
  function estPriceDrop($DTA,$MODE){
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
  
  
  function estPropPrice($DTA,$NOADV=0){
		$tp = e107::getParser();
    $nf = new NumberFormatter('en_US', \NumberFormatter::CURRENCY);
    $nf->setTextAttribute(NumberFormatter::CURRENCY_CODE, 'USD');
    $nf->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 0);
    
    $ListPrice = $nf->format($DTA['prop_listprice']).($DTA['prop_listype'] == 0 ? '/'.$GLOBALS['EST_LEASEFREQ'][$DTA['prop_leasefreq']] : '');
    $ListPrice .= $this->estPriceDrop($this->var,0);
    
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
  
  
  
  function sc_prop_status($parm = ''){
    return $this->estPropStat($this->var);
    }
  
  
  function sc_prop_price($parm = ''){
    return $this->estPropPrice($this->var);
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
  
    
}