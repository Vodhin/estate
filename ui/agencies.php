<?php
if(!defined('e107_INIT')){exit;}

e107::coreLan('user');
e107::coreLan('users', true);
e107::coreLan('date');


class estate_agencies_ui extends e_admin_ui{
	
  protected $pluginTitle		= EST_PLUGNAME;
	protected $pluginName		= 'estate';
  protected $table			= 'estate_agencies';
	protected $pid				= 'agency_idx';
	protected $perPage			= 30; 
	protected $batchDelete		= true;
	protected $batchExport     = true;
	protected $batchCopy		= true;
  protected $listQry      = '';
  protected $listOrder		= 'agency_name ASC';
  
  protected $tabs				= array('<span id="estProfTab">'.EST_GEN_AGENCY.'</span> '.EST_GEN_PROFILE,EST_GEN_USERLIST,EST_GEN_LISTINGS); 
  
	protected $fields = array (
    'agency_idx' => array (
      'tab'=>0,
      'type' => 'hidden',
      'data' => 'int',
      'nolist'=>true,
      'width' => 'auto',
      'readParms' =>  array (),
      'writeParms' =>  array (),
      'class' => 'left',
      'thclass' => 'left',
      ),
    'agency_thmb' => array (
      'tab'=>0,
      'type' => 'method',
      'title' => EST_GEN_LOGO,
      'data' => false,
      'width' => '110px',
      'readParms' => array(),
      'writeParms' => array('tdClassLeft'=>'noDISP','tdClassRight'=>'noPAD'), //'tdClassLeft'=>'noDISP'
      'class' => 'left',
      'thclass' => 'left',
      ),
		'agency_pub' => array (
      'tab'=>0,
      'data' => 'int',
      'title' => false,
      'nolist'=>true,
      'noedit'=>true,
      ),
		'agency_name' => array (
      'title' => LAN_NAME,
      'type' => 'method',
      'data' => 'safestr',
      'width' => '90%',
      'noedit'=>true,
      'class' => 'left VAT',
      ),
    
		'agency_image' => array (
      'tab'=>0,
      'type' => 'hidden',
      'data' => 'safestr',
      'width' => 'auto',
      'nolist'=>true,
      'readParms' =>  array (),
      'writeParms' =>  array (),
      'class' => 'left',
      'thclass' => 'left',
      ),
		'agency_imgsrc' => array (
      'tab'=>0,
      'type' => 'hidden',
      'data' => 'int',
      'width' => 'auto',
      'nolist'=>true,
      'readParms' =>  array (),
      'writeParms' =>  array (),
      'class' => 'left',
      'thclass' => 'left',
      ),
    'agency_addr' => array (
      'data' => 'safestr',
      'nolist'=>true,
      'noedit'=>true,
      ),
		'agency_lat' => array (
      'tab'=>0,
      'type' => 'hidden',
      'data' => 'safestr',
      'width' => 'auto',
      'nolist'=>true,
      'readParms' =>  array (),
      'writeParms' =>  array (),
      'class' => 'left',
      'thclass' => 'left',
      ),
		'agency_lon' => array (
      'tab'=>0,
      'type' => 'hidden',
      'data' => 'safestr',
      'width' => 'auto',
      'nolist'=>true,
      'readParms' =>  array (),
      'writeParms' =>  array (),
      'class' => 'left',
      'thclass' => 'left',
      ),
		'agency_geoarea' => array (
      'tab'=>0,
      'type' => 'hidden',
      'data' => 'safestr',
      'width' => 'auto',
      'nolist'=>true,
      'readParms' =>  array (),
      'writeParms' =>  array (),
      'class' => 'left',
      'thclass' => 'left',
      ),
		'agency_zoom' => array (
      'tab'=>0,
      'type' => 'hidden',
      'data' => 'int',
      'width' => 'auto',
      'nolist'=>true,
      'readParms' =>  array (),
      'writeParms' =>  array (),
      'class' => 'left',
      'thclass' => 'left',
      ),
		'agency_timezone' => array (
      'data' => 'safestr',
      'nolist'=>true,
      'noedit'=>true,
      ),
		'agency_txt1' => array (
      'data' => 'safestr',
      'nolist'=>true,
      'noedit'=>true,
      ),
    'agency_agents' => array (
      'tab'=>1,
      'type' => 'method',
      'title' => EST_GEN_AGENTS,
      'data' => false,
      'readParms' => array(),
      'writeParms' => array('tdClassLeft'=>'noDISP','tdClassRight'=>'noPAD'),
      'class' => 'left posREL',
      'thclass' => 'left',
      'tdClassLeft'=>'noPAD',
      'tdClassRight'=>'noPAD',
      ),
    'agency_listings' => array (
      'tab'=>2,
      'type' => 'method',
      'title' => EST_GEN_LISTINGS,
      'data' => false,
      'readParms' => array(),
      'noedit'=>true,
      'class' => 'left posREL',
      'thclass' => 'left',
      'tdClassRight'=>'noPAD',
      ),
		'options' => array (
      'title' => LAN_OPTIONS,
      'type' => null,
      'data' => null,
      'width' => '10%',
      'thclass' => 'center last',
      'class' => 'center last',
      'forced' => 'value',
      'readParms' =>  array (),
      'writeParms' =>  array (),
      )
    );
  
  
  
  
  public function inboxPage(){
    $frm = e107::getForm(false, true);
    $tp = e107::getParser();
    $estateCore = new estateCore;
    $TBS = $estateCore->estAgentInbox();
    return '<div id="estAdmMsgBoxes">'.$frm->tabs($TBS, array('active' => intval($this->getID()),'fade' => 0,'class' => 'estOATabs')).'</div>';
    }
  
  
  public function listPage(){
    $frm = e107::getForm(false, true);
    $estateCore = new estateCore;
    
    $TBS = array();
    $TBS[0]['caption'] = EST_GEN_AGENCIES;
    $TBS[0]['text'] = $estateCore->estAgencyList(1);
    $TBS[1]['caption'] = EST_GEN_USERLIST;
    $TBS[1]['text'] = $estateCore->estUserList();
    
    $ANU = intval(e107::pref('estate','addnewuser'));
    if($ANU > 1 && EST_USERPERM >= $ANU){
      $TBS[2]['caption'] = EST_GEN_ADDNEWUSER;
      $TBS[2]['text'] = '<form method="post" action="'.e_SELF.'?mode=estate_agencies&action=list" id="estNewUserPage" enctype="multipart/form-data" '.$dataStr.'>';
      $TBS[2]['text'] .= $estateCore->estAgentForm('anu');
      $TBS[2]['text'] .= '<div class="buttons-bar center">'.$frm->admin_button('estProfileSubmit',LAN_CREATE, 'submit').'</div></form>';
      }
    
    return $frm->tabs($TBS, array('active' => intval($this->getID()),'fade' => 0,'class' => 'estOATabs'));
    }
  
  
  public function agentPage(){
    $estateCore = new estateCore;
    $frm = e107::getForm(false, true);
    $XID = $this->getID();
    $AID = explode('.',$XID);
    $USRID = intval($AID[0]);
    $AGTID = intval($AID[1]);
    $LOCID = intval($AID[2]);
    
    if($USRID == USERID){
      e107::redirect(SITEURLBASE.e_PLUGIN_ABS."estate/admin_config.php?mode=estate_agencies&action=profile");
      }
    else{
      if(intval($GLOBALS['ESTDB']['agent_idx']) > 0 ){
        if($AGTID !== intval($GLOBALS['ESTDB']['agent_idx'])){
          e107::redirect(SITEURLBASE.e_PLUGIN_ABS."estate/admin_config.php?mode=estate_agencies&action=agent&id=".intval($GLOBALS['ESTDB']['agent_uid']).".".intval($GLOBALS['ESTDB']['agent_idx']).".".intval($GLOBALS['ESTDB']['agent_agcy']));
          }
        }
      
      if($AGTID == 0 && $USRID > 0){
        $formDta = $estateCore->estGetUserById($USRID);
        if($LOCID > 0){$formDta['agent_agcy'] = $LOCID;}
        else{$formDta['agent_agcy'] = EST_AGENCYID;}
        }
      else{$formDta = $estateCore->estGetAgentById($AGTID);}
      
      
      $dataStr = $estateCore->estDataStr($formDta);
      $text = '
      <form method="post" action="'.e_SELF.'?mode=estate_agencies&action=agent&id='.intval($AID[0]).'" id="estAgentProfilePage" enctype="multipart/form-data" '.$dataStr.'>';
        $text .= $estateCore->estAgentForm($formDta);
        $text .= '
  			<div class="buttons-bar center">
          <input type="hidden" name="estProfileKey" value="6" />
        '.$frm->admin_button('estProfileSubmit',(intval($formDta['agent_idx']) > 0 ? LAN_UPDATE : LAN_SAVE), 'submit').'
  			</div>
      </form>';
      return $text;
      }
    }
  
  
  public function profilePage(){
    $frm = e107::getForm(false, true);  //enable inner tabindex counter
    $estateCore = new estateCore;
    $formDta = $estateCore->estGetUserById(USERID);
    $dataStr = $estateCore->estDataStr($formDta);
    $text .= '
    <form method="post" action="'.e_SELF.'?mode=estate_agencies&action=profile" id="estAgentProfilePage" enctype="multipart/form-data" '.$dataStr.'>';
    $text .= $estateCore->estAgentForm($formDta);
    $text .= '
			<div class="buttons-bar center">
        <input type="hidden" name="estProfileKey" value="6" />
      '.$frm->admin_button('estProfileSubmit',(intval($formDta['agent_idx']) > 0 ? LAN_UPDATE : LAN_SAVE), 'submit').'
			</div>
    </form>';
    return $text;
    }
  
  
  public function editPage(){
    $frm = e107::getForm(false, true);
    $estateCore = new estateCore;
    $id = (intval($GLOBALS['ESTDB']['agency_idx']) > 0 ? intval($GLOBALS['ESTDB']['agency_idx']) : intval($this->getID()));
    
    if(intval($id) === 0){
      $text = '
      <form method="post" action="'.e_SELF.'?mode=estate_agencies&action=edit" id="estAgencyCreatePage" enctype="multipart/form-data">';
      $text .= $estateCore->estAgencyForm($formDta);
      $text .= '
  			<div class="buttons-bar center">
          <input type="hidden" name="estProfileKey" value="5" />
          '.$frm->admin_button('estProfileSubmit',LAN_SAVE, 'submit').'
  			</div>
      </form>';
      return $text;
      }
    
    
    $formDta = $estateCore->estGetAgencyById($id);
    if(intval($formDta['agency_idx']) === 0){
      e107::getMessage()->addWarning('<h4>'.EST_GEN_NOAGENCYFOUND0.'</h4>'.EST_GEN_AGENCY.' ID# '.intval($id).' '.EST_GEN_NOAGENCYFOUND1);
      return;
      }
     
    
    $TBS = array();
    $TBS[0]['caption'] = '<span id="estProfTab">'.EST_GEN_AGENCY.'</span> '.EST_GEN_PROFILE;
    $TBS[0]['text'] = '<form method="post" action="'.e_SELF.'?mode=estate_agencies&action=edit&id='.$id.'" id="estAgencyEditPage" enctype="multipart/form-data" >';
    $TBS[0]['text'] .= $estateCore->estAgencyForm($formDta);
    $TBS[0]['text'] .= '<div class="buttons-bar center"><input type="hidden" name="estProfileKey" value="5" />'.$frm->admin_button('estProfileSubmit',LAN_UPDATE, 'submit').'</div></form>';
    
    
    $TBS[1]['caption'] = EST_GEN_AGENT.' '.EST_GEN_LIST;
    $TBS[1]['text'] = $estateCore->estUserList($id);    
    
    $ANU = intval(e107::pref('estate','addnewuser'));
    if($ANU > 1 && EST_USERPERM >= $ANU){
      $TBS[2]['caption'] = EST_GEN_ADDNEWUSER;
      $TBS[2]['text'] = '<form method="post" action="'.e_SELF.'?mode=estate_agencies&action=list" id="estNewUserPage" enctype="multipart/form-data" '.$dataStr.'>';
      $TBS[2]['text'] .= $estateCore->estAgentForm('anu');
      $TBS[2]['text'] .= '<div class="buttons-bar center">'.$frm->admin_button('estProfileSubmit',(intval($formDta['user_id']) > 0 ? LAN_UPDATE : LAN_SAVE), 'submit').'</div></form>';
      }
    
    return $frm->tabs($TBS, array('active' => 0,'fade' => 0,'class' => 'estOATabs'));
    }
  
  
  
  public function createPage(){
    e107::redirect(e_SELF."?mode=estate_agencies&action=edit&id=0");
    return;
    }
  
  
  
  
	public function init() {
    $tp = e107::getParser();
    $mes = e107::getMessage();
    $actn = $this->getAction();
    $id = $this->getId();
    
    if(EST_USERPERM == 1 && $actn !== 'profile' && $actn == 'inbox'){
      e107::redirect(e_SELF."?mode=estate_agencies&action=profile");
      }
    elseif(EST_USERPERM == 2){
      if($actn !== 'profile'){
        if($actn == 'edit' || $actn == 'create' || $actn == 'agency'){
          if(intval($id) !== EST_AGENCYID){
            e107::redirect(e_SELF."?mode=estate_agencies&action=edit&id=".EST_AGENCYID);
            }
          }
        elseif($actn == 'agent'){}
        elseif($actn == 'inbox'){}
        else{
          e107::redirect(e_SELF."?mode=estate_agencies&action=profile");
          }
        }
      }
	  }


	// ------- Customize Create --------
	
	public function beforeCreate($new_data,$old_data){
    return $new_data;
	  }

	public function afterCreate($new_data, $old_data, $id){
    //$estateCore = new estateCore;
    //$estateCore->estContactUDB(5,$id);
    
    if(intval($new_data['agency_imgsrc']) == 1){
      //estInitUpl($_FILES['compLogoUpload'],5,intval($id));
      }
	  }

	public function onCreateError($new_data, $old_data){
		// do something
	  }
	
	// ------- Customize Delete --------
  /*
  public function beforeDelete($data, $id){
    // prevents actual delete if present - make a form to confirm delete?
    e107::getMessage()->addInfo("Before DELETE [".$id."]".$data['agency_name']);
    }
    */
  
  public function afterDelete($deleted_data, $id, $deleted_check){
    
    e107::getMessage()->addInfo("After DELETE [".$id."]".$data);
    }
  
	// ------- Customize Update --------
	public function beforeUpdate($new_data, $old_data, $id){
		return $new_data;
	  }

	public function afterUpdate($new_data, $old_data, $id){
    //$estateCore = new estateCore;
    //$estateCore->estContactUDB(4,$id);
    
    if(intval($new_data['agency_imgsrc']) == 1){
      //estInitUpl($_FILES['compLogoUpload'],5,intval($id));
      }
		return $new_data;
	  }
	
	public function onUpdateError($new_data, $old_data, $id){
		// do something	
	  }
	
  
	public function renderHelp(){
		$tp = e107::getParser();
    $hlpmode = $this->getMode();
    $hlpactn = $this->getAction();
    
		$caption = '<span id="estHelpSpan" class="'.($GLOBALS['EST_PREF']['helpinfull'] == 1 ? 'estHlpFull' : 'estHelpSAuto').'">'.LAN_HELP.'</span>';
		$text = '<div id="estHelpBlock" class="'.($GLOBALS['EST_PREF']['helpinfull'] == 1 ? '' : 'estHelpBAuto').'">';
    
    if($hlpactn == 'edit' || $hlpactn == 'create'){
      $OWNAGT = intval(e107::pref('estate','public_act'));
      $text .= '
      <div id="estEditHelp-0" class="estEditHelpSect">
        <b>'.EST_GEN_AGENCY.' '.EST_GEN_PROFILE.':</b>
        <p>'.EST_HLPMNU_AGCY00.'</p>
        <br />
        <b>'.EST_GEN_AGENCY.' '.EST_GEN_LOGO.':</b>
        <p>'.EST_HLPMNU_AGCY01.'</p>
        <br />
        <b>'.EST_GEN_PROFILEVISIB.':</b>
        <p>'.EST_HLPMNU_AGCY02.'</p>
        <br />
        <b>'.EST_GEN_CONTACTS.':</b>
        <p>'.EST_HLPMNU_CONTACTS01.'</p>
        <p>'.EST_HLPMNU_CONTACTS02.'</p>
      </div>
      <div id="estEditHelp-1" class="estEditHelpSect">
        <b>'.EST_GEN_AGENTS.' '.EST_GEN_LIST.'</b>
        <p>'.EST_HLPMNU_USERS07.'</p>
        <p>'.EST_HLPMNU_USERS08.'</p>
        <br />
        <b>'.EST_GEN_PERMISSIONS.'</b>
        <ul>
        <li><b>'.EST_GEN_ADMINAREA.' '.EST_GEN_ACCESS.'</b><br />'.EST_HLPMNU_USERS01.'¹</li>
        <li><b>'.EST_GEN_FRONTENDACCESS.'</b><br />'.EST_HLPMNU_USERS02.'<br /><br /><i>'.($OWNAGT !== 255 ? EST_HLPMNU_USERS03b : EST_HLPMNU_USERS03a).'</i></li>
        </ul>
        <br />
        <b>'.EST_GEN_ADMINACCLEVEL.'</b>
        <p>'.EST_HLPMNU_USERS04.'</p>
        <p>'.EST_HLPMNU_USERS05.'</p>
        <br />
        <p>¹<i>'.EST_HLPMNU_USERS11.'</i></p>
      </div>
      <div id="estEditHelp-2" class="estEditHelpSect">
        <p><b>'.EST_GEN_AGENCY.' '.EST_GEN_LISTINGS.':</b><br />'.EST_HLPMNU_AGCY10.'</p>
      </div>';
      }
    elseif($hlpactn == 'agent' || $hlpactn == 'profile'){
      $estateCore = new estateCore;
      $AID = explode('.',$this->getID());
      $USRID = intval($AID[0]);
      $AGTID = intval($AID[1]);
      $LOCID = intval($AID[2]);
      if($AGTID == 0 && $USRID > 0){$dta = $estateCore->estGetUserById($USRID);}
      else{$dta = $estateCore->estGetAgentById($AGTID);}
      
      $text .= '
      <div class="estEditHelpSect">
        <p>'.EST_HLPMNU_AGENTPROF01.'</p>
        <p>'.EST_HLPMNU_AGENTPROF02.'</p>
        <br />
        <b>'.EST_GEN_PROFILE.' '.EST_GEN_IMAGE.'</b>
        <p>'.EST_HLPMNU_AGENTPROF06.'</p>
        <p>'.EST_HLPMNU_AGENTPROF07.'</p>
        <br />
        <b>'.EST_GEN_AGENCY.'</b>';
          
      if(intval($dta['agent_idx']) == 0){
        $text .= '
        <p>'.EST_HLPMNU_AGENTPROF03.'</p>';
        }
      else if(EST_USERPERM > 2){
        $text .= '
        <p>'.EST_HLPMNU_AGENTPROF04.'</p>';
        }
      else{
        $text .= '
        <p>'.EST_HLPMNU_AGENTPROF05.'</p>';
        }
      $text .= '
        <br />
        <b>'.EST_GEN_CONTACTS.'</b>
        <p>'.EST_HLPMNU_AGENTPROF08.'</p>
        <p>'.EST_HLPMNU_AGENTPROF09.'</p>
        <p>'.EST_HLPMNU_AGENTPROF10.'</p>
      </div>';
      }
    elseif($hlpactn == 'inbox'){
      $vars = array('x'=>e107::pref('estate','contact_life'));
      $text .= '
      <div id="estEditHelp-0" class="estEditHelpSect">
        <p>'.EST_HLPMNU_INBOX00.'</p>
        <p>'.EST_HLPMNU_INBOX01.'</p>
        <p>'.EST_HLPMNU_INBOX02.'</p>
        <p>'.$tp->lanVars(EST_HLPMNU_INBOX03, $vars,true).'</p>
      </div>
      <div id="estEditHelp-1" class="estEditHelpSect">
        <p>'.$tp->lanVars(EST_HLPMNU_INBOX04, $vars,true).'</p>
        <p>'.EST_HLPMNU_INBOX05.'</p>
      </div>';
      }
    else{
      $OWNAGT = intval(e107::pref('estate','public_act'));
      $text .= '
      <div id="estEditHelp-0" class="estEditHelpSect">
        <b>'.EST_HLPMNU_AGCY20.'</b>
        <p>'.EST_HLPMNU_AGCY21.'</p>
        <p>'.EST_GEN_CLICKTHE.' <i class="S16 e-add-16"></i> '.EST_HLPMNU_AGCY22.'</p>
        <p>'.EST_GEN_CLICKTHE.' <i class="far fa-eye" style="width:16px;"></i> '.EST_HLPMNU_AGCY23.'</p>
      </div>
      <div id="estEditHelp-1" class="estEditHelpSect">
        <b>'.EST_GEN_USERLIST.'</b>
        <p>'.EST_HLPMNU_USERS00.'</p>
        <br />
        <b>'.EST_GEN_PERMISSIONS.'</b>
        <ul>
        <li><b>'.EST_GEN_ADMINAREA.' '.EST_GEN_ACCESS.'</b><br />'.EST_HLPMNU_USERS01.'¹</li>
        <li><b>'.EST_GEN_FRONTENDACCESS.'</b><br />'.EST_HLPMNU_USERS02.'<br /><br /><i>'.($OWNAGT !== 255 ? EST_HLPMNU_USERS03b : EST_HLPMNU_USERS03a).'</i></li>
        </ul>
        <br />
        <b>'.EST_GEN_ADMINACCLEVEL.'</b>
        <p>'.EST_HLPMNU_USERS04.'</p>
        <p>'.EST_HLPMNU_USERS05.'</p>
        <ul>
        </ul>
        <p>¹<i>'.EST_HLPMNU_USERS11.'</i></p>
      </div>
      <div id="estEditHelp-2" class="estEditHelpSect">
        <b>'.EST_GEN_NEWUSERAGENT.'</b>
        <p>'.EST_HLPMNU_USERS20.'</p>
        <p>'.EST_HLPMNU_USERS21.'</p>
        <p>'.EST_HLPMNU_USERS22.'</p>
        <p>'.EST_HLPMNU_USERS23.'</p>
        <p id="estHlp-proplist1">tab-3</p>
      </div>';
      }
    $text .= '</div>';
    $text = $tp->toHTML($text,true);
    //$text = str_replace("[b]","<b>",str_replace("[/b]","</b>",$text));
		return array('caption'=>$caption,'text'=> $text);
	  }
  }



class estate_agencies_form_ui extends e_admin_form_ui{
  
  
  public function agency_thmb($curVal,$mode){
    $tp = e107::getParser();
    $sql = e107::getDB();
    $frm = e107::getForm();
    $pref = e107::pref();
    
    switch($mode){
			case 'read': 
    		$LMOD = $this->getController()->getListModel();
        $image 	= $LMOD->get('agency_image');
        if(intval($LMOD->get('agency_imgsrc')) == 1 && trim($image) !== ''){$image = EST_PTHABS_AGENCY.$tp->toHTML($image);}
        else{$image = $tp->thumbUrl($pref['sitelogo'],false,false,true);}
        return "<div class=\"estAgentListThumb\" style=\"background-image:url('".$image."')\"></div>";
				break;

			case 'write': 
        $estateCore = new estateCore;
        $dta = $this->getController()->getModel()->getData();
        return $estateCore->estAgencyForm($dta);
        
        break;
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  
  public function agency_name($curVal,$mode){
    switch($mode){
			case 'read': 
        $tp = e107::getParser();
    		$LMOD = $this->getController()->getListModel();
        return '
        <div class="estCompListTDDiv">
          <h3>'.$tp->toHTML($LMOD->get('agency_name')).'</h3>
          <p>'.$tp->toHTML($LMOD->get('agency_addr')).'</p>
        </div>';
        break;
      case 'write': 
        break;
      }
    }
  
  public function agency_listings($curVal,$mode){
    $tp = e107::getParser();
    switch($mode){
			case 'read': 
        $agency_idx = intval($this->getController()->getListModel()->get('agency_idx'));
        $estateCore = new estateCore;
        $zoning = $estateCore->estGetZoning();
        
        if(count($zoning)){
          $listings = $estateCore->estGetListings(1,$agency_idx);
          $zi = 0;
          foreach($zoning as $zk=>$zv){
            $ct = count($listings[$zk]);
            if($ct > 0){$text .= '<div class="WSNWRP">'.$ct.' '.$tp->toHTML($zv).' '.($ct == 1 ? EST_GEN_LISTING : EST_GEN_LISTINGS).'</div>'; $zi++;}
            }
          if($zi == 0){$text = '<div class="WSNWRP">0 '.EST_GEN_LISTINGS.'</div>';}
          }
        else{$text = EST_COMP_NOZONCATS;}
        return '<div class="estCompListTDDiv">'.$text.'</div>';
        break;
      case 'write': 
        break;
      }
    }
  
  
  public function agency_agents($curVal,$mode){
    $tp = e107::getParser();
    $estateCore = new estateCore;
    switch($mode){
			case 'read': 
        $agency_idx = intval($this->getController()->getListModel()->get('agency_idx'));
        $sql = e107::getDB();
        $agents = $sql->count('estate_agents', '(agent_idx)', 'WHERE agent_agcy = "'.$agency_idx.'"');
        return '<div class="estCompListTDDiv WSNWRP">'.$agents.' '.($agents == 1 ? EST_GEN_AGENT : EST_GEN_AGENTS).'</div>';
        break;
      
      case 'write': 
        $dta = $this->getController()->getModel()->getData();
        return $estateCore->estUserList($dta);
        //return $estateCore->estUserAgentTbl($dta);
        break;
      }
    }
  
  }



