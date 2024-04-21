<?php

// estate Shortcodes file

if (!defined('e107_INIT')) { exit; }


class estate_shortcodes extends e_shortcode{
  
  function admLinks(){
    
    }
  
  
  function sc_prop_newlnk($parm){
		$tp = e107::getParser();
    $lnk = '';
    if(EST_USERPERM > 0){
      $lnk = '
      <a title="'.EST_GEN_NEW.'"><i class="fa fa-plus"></i></a>
        <p>
          <a class="btn btn-primary noMobile" href="'.EST_PTH_ADMIN.'?action=create" title="'.EST_GEN_FULLADDLIST.'"><i class="fa fa-plus"></i> '.EST_GEN_FULLADDLIST.'</a>
          <a class="btn btn-primary" href="'.EST_PTH_LISTINGS.'?new.0.0" title="'.EST_GEN_QUICKADDLIST.'"><i class="fa fa-plus"></i> '.EST_GEN_QUICKADDLIST.'</a>
        </p>';
      }
    elseif(intval($GLOBALS['EST_PREF']['public_act']) !== 0){
      if(USERID > 0 && check_class($GLOBALS['EST_PREF']['public_act'])){
        $lnk = '<a class="FR" href="'.EST_PTH_LISTINGS.'?new.0.0" title="'.EST_GEN_NEW.'"><i class="fa fa-plus"></i></a>';
        }
      }
    return $lnk;
    }
  
  
  
  function sc_prop_list_editlnk($parm){
    $AGENT = $this->estGetAgent();
    $lnk = '';
    $lnkgo = 0;
    
    if(EST_USERPERM > 0){
      $AGENT = $this->estGetAgent();
      $url1 = EST_PTH_ADMIN.'?action=edit&id='.intval($this->var['prop_idx']);
      $url2 = EST_PTH_LISTINGS.'?edit.'.intval($this->var['prop_idx']);
      if(EST_USERPERM > 2){$lnkgo++;}
      elseif(EST_USERPERM == 2){
        if(intval($this->var['prop_agent']) > 0 && intval($this->var['prop_agency']) == intval($AGENT['agent_agcy'])){$lnkgo++;}
        }
      elseif(EST_USERPERM == 1){
        if(intval($this->var['prop_uidcreate']) == USERID || intval($AGENT['agent_uid']) == USERID){$lnkgo++;}
        }
      unset($url1,$url2);
      }
    else{
      if(intval($this->var['prop_agent']) === 0 && intval($this->var['prop_uidcreate']) == USERID){$lnkgo++;}
      }
    if($lnkgo > 0){
      $lnk = '<button class="estPropListEdtBtn" data-url="'.EST_PTH_LISTINGS.'?edit.'.intval($this->var['prop_idx']).'"  title="'.EST_GEN_EDIT.'"><i class="fa fa-pencil-square-o"></i></button>';
      }
    return $lnk;
    }
  
  function sc_prop_editlnk($parm){
		$tp = e107::getParser();
    $lnk = '';
    if(EST_USERPERM > 0){
      $AGENT = $this->estGetAgent();
      $url1 = EST_PTH_ADMIN.'?action=edit&id='.intval($this->var['prop_idx']);
      $url2 = EST_PTH_LISTINGS.'?edit.'.intval($this->var['prop_idx']);
      if(EST_USERPERM > 2){
        $lnk = '<a title="'.EST_GEN_EDIT.'"><i class="fa fa-pencil-square-o"></i></a><p>
      <a class="btn btn-primary noMobile" href="'.$url1.'" title="'.EST_GEN_FULLEDIT.'"><i class="fa fa-pencil-square-o"></i> '.EST_GEN_FULLEDIT.'</a>
      <a class="btn btn-primary" href="'.$url2.'.0" title="'.EST_GEN_QUICKEDIT.'"><i class="fa fa-pencil-square-o"></i> '.EST_GEN_QUICKEDIT.'</a></p>';
        }
      elseif(EST_USERPERM == 2){
        if(intval($this->var['prop_agent']) > 0 && intval($this->var['prop_agency']) == intval($AGENT['agent_agcy'])){
          $lnk = '<a title="'.EST_GEN_EDIT.'"><i class="fa fa-pencil-square-o"></i></a><p>
      <a class="btn btn-primary noMobile" href="'.$url1.'" title="'.EST_GEN_FULLEDIT.'"><i class="fa fa-pencil-square-o"></i> '.EST_GEN_FULLEDIT.'</a>
      <a class="btn btn-primary" href="'.$url2.'.0" title="'.EST_GEN_QUICKEDIT.'"><i class="fa fa-pencil-square-o"></i> '.EST_GEN_QUICKEDIT.'</a></p>';
          }
        }
      elseif(EST_USERPERM == 1){
        if(intval($this->var['prop_uidcreate']) == USERID || intval($AGENT['agent_uid']) == USERID){
          $lnk = '<a title="'.EST_GEN_EDIT.'"><i class="fa fa-pencil-square-o"></i></a><p>
      <a class="btn btn-primary noMobile" href="'.$url1.'" title="'.EST_GEN_FULLEDIT.'"><i class="fa fa-pencil-square-o"></i> '.EST_GEN_FULLEDIT.'</a>
      <a class="btn btn-primary" href="'.$url2.'.0" title="'.EST_GEN_QUICKEDIT.'"><i class="fa fa-pencil-square-o"></i> '.EST_GEN_QUICKEDIT.'</a></p>';
          }
        }
      unset($url1,$url2);
      }
    else{
      if(intval($this->var['prop_agent']) === 0 && intval($this->var['prop_uidcreate']) == USERID){
        $lnk = '<a class="FR" href="'.EST_PTH_LISTINGS.'?edit.'.intval($this->var['prop_idx']).'" title="'.EST_GEN_EDIT.'"><i class="fa fa-pencil-square-o"></i></a>';
        }
      }
    return $lnk;
    }
  
  
  
  
  
  function sc_prop_admlnk($parm){
		$tp = e107::getParser();
    $lnk = '';
    if(ADMIN){
      $AGENT = $this->estGetAgent();
      if(EST_USERPERM > 2){
        $lnk = '<a class="FR" href="'.EST_PTH_ADMIN.'?action=edit&id='.intval($this->var['prop_idx']).'">EDIT</a>';
        }
      elseif(EST_USERPERM == 2){
        if(intval($this->var['prop_agency']) == intval($AGENT['agent_agcy'])){
          $lnk = '<a class="FR" href="'.EST_PTH_ADMIN.'?action=edit&id='.intval($this->var['prop_idx']).'">EDIT</a>';
          }
        }
      elseif(EST_USERPERM == 1){
        if(intval($this->var['prop_uidcreate']) == USERID || intval($AGENT['agent_uid']) == USERID){
          $lnk = '<a class="FR" href="'.EST_PTH_ADMIN.'?action=edit&id='.intval($this->var['prop_idx']).'">EDIT</a>';
          }
        }
      }
    elseif(intval($this->var['prop_agent']) === 0 && intval($this->var['prop_uidcreate']) == USERID){
      $lnk = '<a class="FR" href="'.e_SELF.'?edit.'.intval($this->var['prop_idx']).'">EDIT</a>';
      }
    return $lnk;
    }
  
  function sc_prop_viewcount($parm){
    return intval($this->var['prop_views']).' '.(intval($this->var['prop_views']) == 1 ? EST_GEN_VIEW : EST_GEN_VIEWS);
    }
  
  function sc_prop_list_linkview($parm){
		//return e107::getUrl()->create('estate/listings.php?view.'.intval($this->var['prop_idx']).'.0', $this->var);
    return EST_PTH_LISTINGS.'?view.'.intval($this->var['prop_idx']).'.0';
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
  
  
  
  
  function sc_prop_map_pins($parm){
    //$GLOBALS['EST_PREF']
    $sql = e107::getDb();
		$tp = e107::getParser();
    $pref = e107::pref();
    $ARR1 = array('agcy'=>array(),'prop'=>array());
    //intval($GLOBALS['PROPID']) == 0 && 
    if($GLOBALS['EST_PREF']['layout_list_mapagnt'] == 1){
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
          foreach($GLOBALS['EST_PROP'] as $k=>$v){
            if(intval($v['prop_status']) > 1 && intval($v['prop_status']) < 5 && trim($v['prop_lat']) !== '' && trim($v['prop_lon']) !== ''){
              $ARR1['prop'][$i] = array(
                'drop'=>$this->estPriceDrop($v,1),
                'feat'=>explode(',',$v['prop_features']),
                'idx'=>$v['prop_idx'],
                'lat'=>$v['prop_lat'],
                'lon'=>$v['prop_lon'],
                'lnk'=>EST_PTH_LISTINGS.'?view.'.intval($v['prop_idx']).'.0',
                'name1'=>$tp->toHTML($v['prop_name']),
                'prc'=>$this->estPropPrice($v,1),
                'sta'=>$tp->toHTML($this->estPropStat($v)),
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
  
  function sc_prop_map_agydta($parm){
    //$GLOBALS['EST_PREF']
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
  
  function sc_prop_view_openhouse($parm){
    if($this->var['evt']){
      $tp = e107::getParser();
      $OPH = '<ul id="estOHCont">';
      foreach($this->var['evt'] as $ok=>$ov){
        $OPH .= '
        <li class="estOHItem">
          <h4>'.date('D F j, Y',$ov['event_start']).'<br/>'.date('g:i a',$ov['event_start']).' - '.date('g:i a',$ov['event_end']).'</h4>
          <h5>'.$tp->toHTML($ov['event_name']).'</h5>
          <p>'.$tp->toHTML($ov['event_text']).'</p>
        </li>';
        }
      $OPH .= '</ul>';
      }
    else{$OPH .= EST_GEN_NOOPENHOUSE;}
    return $OPH;
    }
  
  function sc_prop_listthm_banner($parm){
    $i=0;
    if($this->var['evt']){
      foreach($this->var['evt'] as $ok=>$ov){
        if($i == 0){
          return '
          <div class="estListPropImgBanner1">
            <h5>'.EST_GEN_OPENHOUSE.'</h5>
            <p>'.date('D F j',$ov['event_start']).'<br>'.date('g:i a',$ov['event_start']).' - '.date('g:i a',$ov['event_end']).'</p>
          </div>';
          }
        $i++;
        }
      }
    else{
      if(intval($this->var['prop_status']) == 5){
        $BTXT = (intval($this->var['prop_listype']) == 0 ? EST_GEN_OFFMARKET : EST_GEN_SOLD);
        }
      elseif(intval($this->var['prop_status']) == 4){
        $BTXT = EST_GEN_PENDING;
        }
      if($BTXT){
        return '
        <div class="estListPropImgBanner1">
          <h5>'.$BTXT.'</h5>
        </div>';
        }
      }      
    }
  
  function sc_prop_openhouseli($parm){
    return '';
    //return e107::getParser()->toHTML(intval($this->var['prop_idx']) == 5 ? ' • OPEN HOUSE: Sunday February 26, 2023 10:30 am to 1:30 pm' : '');
    }
  
  function sc_prop_features($parm){
		$tp = e107::getParser();
    if($parm['as'] == 'ul'){
      $lia = explode(',',$this->var['prop_features']);
      if(count($lia) > 0){
        $ret = '<ul>';
        foreach($lia as $k=>$v)$ret .= '<li>'.$tp->toHTML($v).'</li>';
        $ret .= '</ul>';
        }
      }
    else{$ret = str_replace(","," • ",$tp->toHTML($this->var['prop_features']));}
    return $ret;
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
    $ret = '['.$this->var['prop_prevIdx'].']';
    
    return $ret;
    }
  
      
  function spacesKeyId($v,$sok,$sk){
    return 'SPACE'.$v['ord'].'x'.$sok.'x'.$sk;
    }
  
  function spacesTxt($sv){
		$tp = e107::getParser();
    $text = '<ul>';
    if($sv['xy']){
      $text .= '<li>'.$tp->toHTML($sv['xy']).'</li>';
      }
    if(trim($sv['l']) !== ''){
      $text .= '<li>'.EST_LOCATION.': '.$tp->toHTML($sv['l']).'</li>';
      }
    if($sv['f']){
      foreach($sv['f'] as $fk=>$fv){
        if(trim($fv['n']) !== ''){
          $text .= '<li>'.$tp->toHTML($fv['n']);
          if(trim($fv['d']) !== ''){$text .= ': '.str_replace(',',', ',$tp->toHTML($fv['d']));}
          $text .= '</li>';
          }
        }
      }
    $text .= '</ul>';
            
    if(trim($sv['d']) !== ''){
      $text .= '<p>'.$tp->toHTML($sv['d']).'</p>';
      }
    return $text;
    }
  
  function sc_prop_viewspaceimgs($parm){
		$tp = e107::getParser();
    $SPACES = $GLOBALS['EST_SPACES'];
    $text = '';
    if(count($SPACES) > 0){
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
  
  function sc_prop_viewspacebtns($parm){
		$tp = e107::getParser();
    $SPACES = $GLOBALS['EST_SPACES'];
    $text = '';
    if(count($SPACES) > 0){
      usort($SPACES, "spgrpsort");
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
              <div id="'.$estkeyid.'btn" class="estViewSpaceBtn '.$GLOBALS['EST_PREF']['layout_view_spacetilebg'].(count($sv['m']) > 0 ? '' : ' noPics').'" data-getimg="'.$estkeyid.'dynImg">
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
    unset($SPACETXT);
    return $text;
    }
  
  
  //PROP_FEATURES
  function sc_prop_latlng($parm){}
  
  
  
  function estGetAgency($AGYID){
    }
  
  
  function estGetAgent(){
    $sql = e107::getDb();
		$tp = e107::getParser();
    $ret = array();
    
    $PROPAGNT = intval($this->var['prop_agent']);
    $PROPUID = intval($this->var['prop_uidcreate']);
    if($PROPAGNT > 0){
      if($AGENT = $sql->retrieve("SELECT * FROM #estate_agents WHERE agent_idx=".$PROPAGNT." ",true)){
        if(intval($AGENT[0]['agent_idx']) > 0){
          $ret = $AGENT[0];
          
          if(intval($ret['agent_imgsrc']) == 1 && trim($ret['agent_image']) !== ""){$ret['imgurl'] = EST_PTHABS_AGENT.$ret['agent_image'];}
          else{
            if(intval($ret['agent_uid']) > 0){
              if($EUSR = $sql->retrieve("SELECT user_email,user_hideemail,user_image FROM #user WHERE user_id=".intval($ret['agent_uid'])." ",true)){
                $ret['imgurl'] = $tp->toAvatar($EUSR[0],array('type'=>'url'));
                }
              }
            }
          
          if($ACONT = $sql->retrieve("SELECT * FROM #estate_contacts WHERE contact_tabidx=".intval($ret['agent_idx'])." ORDER BY contact_ord ASC ",true)){
            foreach($ACONT as $k=>$v){
              $ret['contacts'][$v['contact_tabkey']][$k] = array($v['contact_key'],$v['contact_data']);
              /*
              if(strtoupper($v['contact_key']) == EST_GEN_EMAIL){
                $ret['contacts'][$v['contact_tabkey']][$k] = array(EST_GEN_EMAIL,(intval($EUSR[0]['user_hideemail']) !== 1 ? $v['contact_data'] : 'hidden'));
                }
              else{
                $ret['contacts'][$v['contact_tabkey']][$k] = array($v['contact_key'],$v['contact_data']);
                }
              */
              }
            }
          
          if($AGY = $sql->retrieve("SELECT * FROM #estate_agencies WHERE agency_idx=".intval($ret['agent_agcy'])." && agency_pub=1 ",true)){
            $ret['agency'] = $AGY[0];
            
            if(intval($ret['agency']['agency_imgsrc']) == 1 && trim($ret['agency']['agency_image']) !== ''){
              $ret['agency']['agylogo'] = EST_PTHABS_AGENCY.$tp->toHTML($ret['agency']['agency_image']);
              }
            else{
              $pref = e107::pref();
              $ret['agency']['agylogo'] = $tp->thumbUrl($pref['sitelogo'],false,false,true);
              }
            }
          }
        }
      }
    else{
      if($EUSR = $sql->retrieve("SELECT user_id,user_name,user_email,user_hideemail,user_image FROM #user WHERE user_id=".$PROPUID." ",true)){
        $ret['imgurl'] = $tp->toAvatar($EUSR[0],array('type'=>'url'));
        $ret['agent_name'] = $tp->toHTML($EUSR[0]['user_name']);
        //if(intval($EUSR[0]['user_hideemail']) !== 1){
          $ret['contacts'][6][0] = array($tp->toHTML(EST_GEN_EMAIL),$tp->toHTML($EUSR[0]['user_email']));
          //}
        if(check_class($GLOBALS['EST_PREF']['public_act']) && USERID > 0 && $PROPUID == USERID){
          $ret['oa'] = 1;
          }
        }
      }
      
    unset($AGENT,$ACONT,$AGY);
    return $ret;
    }
  
  
  
  function sc_prop_compcard($parm,$AGENT){}
  
  function sc_prop_agentcard($parm){
		$tp = e107::getParser();
    $AGENT = $this->estGetAgent();
    if(intval($AGENT['oa']) == 1){$dtastr = $this->estDataStr($AGENT);}
    
    $ret = '
      <div id="estAgCard" class="estAgCard'.(intval($AGENT['oa']) == 1 ? ' estOA' : '').'" '.$dtastr.'>
        <div class="estAgCardInner">';
    
    if(($parm['mode'] == 'full' || $parm['img'] > 0) && $AGENT['imgurl']){
      $ret .= '
          <div class="estAgtAvatar" style="background-image:url(\''.$AGENT['imgurl'].'\')"></div>';
      }
     
     $ret .= '
          <div class="estAgtInfo1">
            <h3>'.$tp->toHTML($AGENT['agent_name']).'</h3>';
    
    if(trim($AGENT['agent_txt1']) !== ''){
      $ret .= '
            <p class="FSITAL">'.$tp->toHTML($AGENT['agent_txt1']).'</p>';
      }
    
      
    if($AGENT['contacts'][6]){
      $ret .= '
            <div class="estAgContact">';
      foreach($AGENT['contacts'][6] as $ck=>$cv){
        $ret .= '
              <div>'.$tp->toHTML($cv[0]).' '.$tp->toHTML($cv[1]).'</div>';
        }
      $ret .= '
            </div>';
      }
    
    $ret .= '
          </div>
        </div>
      </div>';
    
    if($AGENT['agency']){
      
      }
    
    unset($AGENT);
    return $ret;
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
    
    /*
    $prefW = e107::getPlugPref('gallery', 'pop_w');
    */
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