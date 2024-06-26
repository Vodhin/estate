<?php
if(!defined('e107_INIT')){exit;}
/*
 * Not Used. 
 */
if(isset($_POST['estPresetsSubmit'])){
  
  $sql = e107::getDB();
  $tp = e107::getParser();
  $msg = e107::getMessage();
  
  $estateCore = new estateCore;
  $zoning = $estateCore->estGetZoning();
  
  $cmsg = array();
  
  if(isset($_POST['listype_name']) && count($_POST['listype_name'])){
    foreach($_POST['listype_name'] as $k=>$v){
      foreach($v as $lk=>$lv){
        if($lk > 0){
          if(intval($_POST['listype_keep'][$k][$lk]) == 0){
            if($sql->delete("estate_listypes", "listype_idx='".$lk."'")){
              $cmsg[$k] .= '<li>'.EST_GEN_DELETED.' "'.$tp->toHTML($_POST['listype_name'][$k][$lk]).'"</li>';
              }
            }
          else{
            if($sql->update("estate_listypes","listype_name='".$tp->toDB($_POST['listype_name'][$k][$lk])."' WHERE listype_idx='".$lk."' LIMIT 1")){
              $cmsg[$k] .= '<li>'.EST_GEN_UPDATED.' "'.$tp->toHTML($_POST['listype_name'][$k][$lk]).'"</li>';
              }
            }
          }
        }
      }
    }
  
  if(isset($_POST['new_listype_name']) && count($_POST['new_listype_name'])){
    foreach($_POST['new_listype_name'] as $k=>$v){  
      foreach($v as $lk=>$lv){
        if($sql->insert("estate_listypes","'0','".$k."','".$tp->toDB($_POST['new_listype_name'][$k][$lk])."'")){
          $cmsg[$k] .= '<li>'.EST_GEN_ADDED.' "'.$tp->toHTML($_POST['new_listype_name'][$k][$lk]).'"</li>';
          }
        }
      }
    }
  
  if(count($cmsg) > 0){
    foreach($cmsg as $k=>$v){
      $msg->addSuccess('<div>'.$zoning[$k].' '.EST_GEN_LISTYPES.':<ul>'.$cmsg[$k].'</ul></div>');
      }
    }
  unset($cmsg);
  $cmsg = array();
  
  if(isset($_POST['group_name']) && count($_POST['group_name'])){
    foreach($_POST['group_name'] as $k=>$v){
      foreach($v as $lk=>$lv){
        foreach($lv as $gk=>$gv){
          if($gk > 0){
            if(intval($_POST['group_name_keep'][$k][$lk][$gk]) == 0){
              if($sql->delete("estate_group", "group_idx='".$gk."'")){
                $cmsg[$k] .= '<li>'.EST_GEN_DELETED.' "'.$tp->toHTML($_POST['group_name'][$k][$lk][$gk]).'"</li>';
                }
              }
            else{
              if($sql->update("estate_group","group_name='".$tp->toDB($_POST['group_name'][$k][$lk][$gk])."' WHERE group_idx='".$gk."' LIMIT 1")){
                $cmsg[$k] .= '<li>'.EST_GEN_UPDATED.' "'.$tp->toHTML($_POST['group_name'][$k][$lk][$gk]).'"</li>';
                }
              }
            }
          }
        }
      }
    }
  
  if(isset($_POST['new_group_name']) && count($_POST['new_group_name'])){
    foreach($_POST['new_group_name'] as $k=>$v){
      foreach($v as $lk=>$lv){
        foreach($lv as $gk=>$gv){
          if($sql->insert("estate_group","'0','".$k."',".$lk.",'".$tp->toDB($_POST['new_group_name'][$k][$lk][$gk])."'")){
            $cmsg[$k] .= '<li>'.EST_GEN_ADDED.' "'.$tp->toHTML($_POST['new_group_name'][$k][$lk][$gk]).'"</li>';
            }
          }
        }
      }
    }

  if(count($cmsg) > 0){
    foreach($cmsg as $k=>$v){
      $msg->addSuccess('<div>'.$zoning[$k].' '.EST_GEN_GROUP.':<ul>'.$cmsg[$k].'</ul></div>');
      }
    }
  unset($cmsg);

  if(isset($_POST['featcat_name']) && count($_POST['featcat_name']) > 0){
    foreach($_POST['featcat_name'] as $featcat_zone=>$zDta){
      foreach($zDta as $featcat_lev=>$lDta){
        foreach($lDta as $featcat_idx=>$featcat_Dta){
          $featcat_keep = intval($_POST['featcat_keep'][$featcat_zone][$featcat_lev][$featcat_idx]);
          $featcat_name = $_POST['featcat_name'][$featcat_zone][$featcat_lev][$featcat_idx];
          if(trim($featcat_name) == ''){$featcat_keep = 0;}
          
          if($featcat_keep === 0){
            if($sql->delete("estate_featcats", "featcat_idx='".$featcat_idx."'")){
              $FCATUDB = ' - '.EST_GEN_DELETED;
              if($sql->delete("estate_features", "feature_cat='".$featcat_idx."'")){
                $cmsg .= '<li>'.EST_GEN_DELETED.' '.EST_GEN_FEATURES.'</li>';
                unset($_POST['feature_keep'][$featcat_zone][$featcat_lev][$featcat_idx],$_POST['feature_ele'][$featcat_zone][$featcat_lev][$featcat_idx],$_POST['feature_name'][$featcat_zone][$featcat_lev][$featcat_idx],$_POST['feature_opts'][$featcat_zone][$featcat_lev][$featcat_idx],$_POST['add_feature_ele'][$featcat_zone][$featcat_lev][$featcat_idx],$_POST['add_feature_name'][$featcat_zone][$featcat_lev][$featcat_idx],$_POST['add_feature_opts'][$featcat_zone][$featcat_lev][$featcat_idx]);
                }
              }
            }
          else{
            if($sql->update("estate_featcats","featcat_name='".$tp->toDB($featcat_name)."' WHERE featcat_idx='".$featcat_idx."' LIMIT 1")){
              $FCATUDB = ' - '.EST_GEN_UPDATED;
              }
            }
          
          if(isset($_POST['feature_name'][$featcat_zone][$featcat_lev][$featcat_idx]) && count($_POST['feature_name'][$featcat_zone][$featcat_lev][$featcat_idx]) > 0){
            foreach($_POST['feature_name'][$featcat_zone][$featcat_lev][$featcat_idx] as $k=>$v){
              if($k > 0){
                if(intval($_POST['feature_keep'][$featcat_zone][$featcat_lev][$featcat_idx][$k]) === 0 || trim($_POST['feature_name'][$featcat_zone][$featcat_lev][$featcat_idx][$k]) == ''){
                  if($sql->delete("estate_features", "feature_idx='".$k."'")){
                    $cmsg .= '<li>'.EST_GEN_DELETED.' "'.$tp->toHTML($_POST['feature_name'][$featcat_zone][$featcat_lev][$featcat_idx][$k]).'"</li>';
                    }
                  }
                else{
                  if($sql->update("estate_features","feature_ele='".intval($_POST['feature_ele'][$featcat_zone][$featcat_lev][$featcat_idx][$k])."',feature_name='".$tp->toDB($_POST['feature_name'][$featcat_zone][$featcat_lev][$featcat_idx][$k])."',feature_opts='".$tp->toDB($_POST['feature_opts'][$featcat_zone][$featcat_lev][$featcat_idx][$k])."' WHERE feature_idx='".$k."' LIMIT 1")){
                    $cmsg .= '<li>Updated "'.$tp->toHTML($_POST['feature_name'][$featcat_zone][$featcat_lev][$featcat_idx][$k]).'"</li>';
                    }
                  }
                }
              }
            unset($_POST['feature_keep'][$featcat_zone][$featcat_lev][$featcat_idx],$_POST['feature_ele'][$featcat_zone][$featcat_lev][$featcat_idx],$_POST['feature_name'][$featcat_zone][$featcat_lev][$featcat_idx],$_POST['feature_opts'][$featcat_zone][$featcat_lev][$featcat_idx]);
            }
          
          if(isset($_POST['add_feature_name'][$featcat_zone][$featcat_lev][$featcat_idx]) && count($_POST['add_feature_name'][$featcat_zone][$featcat_lev][$featcat_idx]) > 0){
            foreach($_POST['add_feature_name'][$featcat_zone][$featcat_lev][$featcat_idx] as $k=>$v){
              if(trim($_POST['add_feature_name'][$featcat_zone][$featcat_lev][$featcat_idx][$k]) !== ''){
                $feature_idx = $sql->insert("estate_features","'0','".intval($_POST['add_feature_ele'][$featcat_zone][$featcat_lev][$featcat_idx][$k])."','".$featcat_idx."','".$tp->toDB($_POST['add_feature_name'][$featcat_zone][$featcat_lev][$featcat_idx][$k])."','".$tp->toDB($_POST['add_feature_opts'][$featcat_zone][$featcat_lev][$featcat_idx][$k])."'");
                if($feature_idx > 0){$cmsg .= '<li>Added "'.$tp->toHTML($_POST['add_feature_name'][$featcat_zone][$featcat_lev][$featcat_idx][$k]).'"</li>';}
                }
              }
            unset($_POST['add_feature_ele'][$featcat_zone][$featcat_lev][$featcat_idx],$_POST['add_feature_name'][$featcat_zone][$featcat_lev][$featcat_idx],$_POST['add_feature_opts'][$featcat_zone][$featcat_lev][$featcat_idx]);
            }
          
          if(isset($cmsg) || $FCATUDB){
            $msg->addSuccess($tp->toHTML('<div>'.$featcat_name.$FCATUDB.' <ul>'.$cmsg.'</ul></div>'));
            unset($cmsg,$FCATUDB);
            }
          }
        }
      }
    }
  
  
  if(isset($_POST['new_featcat_name']) && count($_POST['new_featcat_name']) > 0){
    foreach($_POST['new_featcat_name'] as $featcat_zone=>$zDta){
      foreach($zDta as $featcat_lev=>$lDta){
        foreach($lDta as $tempId=>$featcat_Dta){
          if(trim($_POST['new_featcat_name'][$featcat_zone][$featcat_lev][$tempId]) !== ''){
            $featcat_idx = $sql->insert("estate_featcats","'0','".$featcat_zone."','".$featcat_lev."','".$tp->toDB($_POST['new_featcat_name'][$featcat_zone][$featcat_lev][$tempId])."'");
            if($featcat_idx > 0){
              if(isset($_POST['new_feature_name'][$featcat_zone][$featcat_lev][$tempId]) && count($_POST['new_feature_name'][$featcat_zone][$featcat_lev][$tempId]) > 0){
                foreach($_POST['new_feature_name'][$featcat_zone][$featcat_lev][$tempId] as $k=>$v){
                  if(trim($_POST['new_feature_name'][$featcat_zone][$featcat_lev][$tempId][$k]) !== ''){
                    if($sql->insert("estate_features","'0','".intval($_POST['new_feature_ele'][$featcat_zone][$featcat_lev][$tempId][$k])."','".$featcat_idx."','".$tp->toDB($_POST['new_feature_name'][$featcat_zone][$featcat_lev][$tempId][$k])."','".$tp->toDB($_POST['new_feature_opts'][$featcat_zone][$featcat_lev][$tempId][$k])."'")){
                      $cmsg .= '<li>'.$tp->toHTML($_POST['new_feature_name'][$featcat_zone][$featcat_lev][$tempId][$k]).'</li>';
                      }
                    else{$cmsg .= '<li>NOT ADDED: '.$tp->toHTML($_POST['new_feature_name'][$featcat_zone][$featcat_lev][$tempId][$k]).'</li>';}
                    }
                  }
                unset($_POST['new_feature_ele'][$featcat_zone][$featcat_lev][$tempId],$_POST['new_feature_name'][$featcat_zone][$featcat_lev][$tempId],$_POST['new_feature_opts'][$featcat_zone][$featcat_lev][$tempId]);
                }
              $msg->addSuccess('<div>'.EST_GEN_ADDEDNEW.' '.EST_GEN_CATEGORY.': '.$tp->toHTML($_POST['new_featcat_name'][$featcat_zone][$featcat_lev][$tempId]).'<ul>'.$cmsg.'</ul></div>');
              unset($cmsg);
              }
            }
          unset($featcat_idx);
          }
        }
      }
    }
  }




class estate_presets_ui extends e_admin_ui{
	
  protected $pluginTitle		= EST_PLUGNAME;
	protected $pluginName		= 'estate';
  
  function presetsPage(){
    $pref = e107::pref();
		$sql = e107::getDB(); 
		$tp = e107::getParser();
    $frm = e107::getForm(false, true); 
    $estateCore = new estateCore;
    
    //$dta = $this->getController()->getModel()->getData();
    
    $zoning = $estateCore->estGetZoning();
        
    $text = '
    <form method="post" action="'.e_SELF.'?mode=estate_presets&action=presets" id="estPresetDataForm">
      <div id="estPresetsZoningPopover" class="popover fade top in editable-container editable-popup">
        <h3 class="popover-title">
          <button id="estPresetsAddZone" type="button" class="btn btn-primary btn-sm FR" title="'.EST_GEN_NEW.'">'.EST_GEN_NEW.'</button>
          <button id="estPresetsCanZone" type="button" class="btn btn-primary btn-sm FR" title="'.LAN_CANCEL.'">'.LAN_CANCEL.'</button>
          <p>'.EST_PRESETS_SELZONKEEP.'</p>
        </h3>
        <div id="estPresetsCurZoningCont">';
    if(count($zoning)){
      foreach($zoning as $k=>$v){
        $text .= '
          <div class="estPresetsCurZoneDivCont">
            <input type="checkbox" name="cur_zoning_keep['.$k.']" class="estCurZoneKeepCB" value="'.$k.'" checked="checked"/>
            <input type="text" name="zoning_name['.$k.']" data-idx="'.$k.'" class="tbox form-control input-xlarge ILBLK ui-state-valid" value="'.$tp->toForm($v).'" />
          </div>';
        }
      }
    
    $text .= '
        </div>
        <div class="TAC">
          <button id="estPresetsSaveZones" type="button" class="btn btn-primary btn-sm" title="'.EST_PRESETS_SELZONINGSAVEHINT.'">'.LAN_SAVE.'</button>
        </div>
      </div>
      
  
      <table id="estPresetsDataTbl" class="table estFormSubTable">
        <colgroup style="width:20%"></colgroup>
        <colgroup style="width:80%"></colgroup>
        <thead>
          <tr id="estPresetsSelZoningTR">
            <th class="VAT"><span>'.EST_GEN_ZONING.' '.EST_GEN_FILTER.'</span><i class="admin-ui-help-tip far fa-question-circle" data-original-title="" title="" aria-describedby="tooltip874908"><!-- --></i><div class="field-help" data-placement="left" style="display:none">'.EST_HLPMNU_PRESETZONESEL.'</div></th>
            <th colspan="2" style="padding-top: 4px; padding-bottom: 6px;">
              <div class="estInptCont">
                '.$frm->select('preset_zoneSelect', $zoning,0,'size=xlarge ILBLK').'
                <button id="estPresetsZoneDtaEdit" type="button" class="btn btn-default selEditBtn1" title="'.EST_PRESETS_SELZONEDITTTL.'"><i class="fa fa fa-pencil-square-o"></i></button>
              </div>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr class="noDISP"><td colspan="2"></td></tr>
          <tr>
            <td colspan="2" class="noPAD">
              <div id="estPresetsDataCont">';
    if(count($zoning)){
      foreach($zoning as $zi=>$zv){
        $text .= $estateCore->estGetPresetsForm(intval($zi));
        }
      }
    
    $text .= '
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      
			<div class="buttons-bar center">
      '.$frm->admin_button('estPresetsSubmit',LAN_SAVE, 'submit').'
			</div>
    </form>';
    return $text;
    
    }
  
    
  
	public function renderHelp(){
		$tp = e107::getParser();
    $hlpmode = $this->getMode();
    $hlpactn = $this->getAction();
    
		$caption = '<span id="estHelpSpan">'.EST_GEN_PRESETS.' '.LAN_HELP.'</span>';
		$text = '
    <div id="estHelpBlock">
      <div id="estEditHelp-0" class="estEditHelpSect">
        <p><b>'.EST_GEN_DATAPRESETS.':</b><br />'.EST_HLPMNU_PRESETS30.'</p>
        <p><b>'.EST_GEN_ZONING.' '.EST_GEN_FILTER.':</b><br />'.EST_HLPMNU_PRESETZONESEL.'</p>
        <p><b>'.EST_GEN_LISTYPES.':</b><br />'.EST_HLPMNU_PRESETS33.'</p>
        <p><b>'.EST_GEN_SPACES.' '.EST_GEN_GROUP.'</b><br />'.EST_HLPMNU_PRESETS34.'</p>
        <p>¹<i>'.EST_HLPMNU_ABOUTSPACES0.'</i></p>
      </div>
    </div>';
    $text = $tp->toHTML($text,true);
    //$text = str_replace("[b]","<b>",str_replace("[/b]","</b>",$text));
		return array('caption'=>$caption,'text'=> $text);
	  }
  }



class estate_presets_form_ui extends e_admin_form_ui{
  
  }



