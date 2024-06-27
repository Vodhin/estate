<?php
if(!defined('e107_INIT')){exit;}
if(!ADMIN){exit;}
if(!getperms('P')){exit;}





class estate_adminMain extends e_admin_dispatcher{
	protected $menuTitle = EST_PLUGNAME;
  //public $hlpactn = $this->getAction();
	
  protected $modes = array(
		'estate_properties'	=> array(
			'controller' 	=> 'estate_listing_ui',
			'path' 			=> null,
			'ui' 			=> 'estate_listing_form_ui',
			'uipath' 		=> null
		  ),
    'estate_agencies'	=> array(
			'controller' 	=> 'estate_agencies_ui',
			'path' 			=> null,
			'ui' 			=> 'estate_agencies_form_ui',
			'uipath' 		=> null
		  ),
    'estate_presets'	=> array(
			'controller' 	=> 'estate_presets_ui',
			'path' 			=> null,
			'ui' 			=> '',
			'uipath' 		=> null
		  ),
    'estate_instruct'	=> array(
			'controller' 	=> 'estate_instruct_ui',
			'path' 			=> null,
			'ui' 			=> '',
			'uipath' 		=> null
		  )
	);
	
  
  //'<i class=\"fa fa-envelope\">'.EST_MGS_NEWMSGS.'</i> '.
  //'estate_agencies/inbox' => array('caption'=> EST_GEN_AGENT.' '.EST_MSG_INBOX.': '.EST_MGS_NEWMSGS, 'perm' => 'P'),
  
  protected $adminMenu = array(
    'estate_properties/list' => array('caption'=> EST_AMENU_PROPLIST.(EST_NEW_PROPSUBMITTED > 0 ? ' ('.EST_NEW_PROPSUBMITTED.')' : ''), 'perm' => 'P'),
    'estate_agencies/profile' => array('caption'=> EST_MYAGENTPROFILE.' ('.EST_MGS_NEWMSGS.')', 'perm' => 'P','icon'=>'<i class=\"fa fa-envelope\"></i>'),
    'estate_agencies/list' => array('caption'=> EST_GEN_AGENTSAGENCIES, 'perm' => 'P'),
    'estate_presets/presets' => array('caption'=> EST_GEN_DATAPRESETS, 'perm' => 'P'),
    'estate_properties/prefs' => array('caption'=> LAN_PREFS, 'perm' => 'P'),
    'estate_instruct/help' => array('caption'=> EST_GEN_HOWTO, 'perm' => 'P')
    );
  
  protected $adminMenuAliases = array(
		'estate_properties/edit'	=> 'estate_properties/list',
		'estate_properties/create'	=> 'estate_properties/list',
		'estate_agencies/edit'	=> 'estate_agencies/list',
		'estate_agencies/agent'	=> 'estate_agencies/list',
		'estate_agencies/create'	=> 'estate_agencies/list'
    );
  }






class estate_adminManager extends e_admin_dispatcher{
	protected $menuTitle = EST_PLUGNAME;
	
  protected $modes = array(
		'estate_properties'	=> array(
			'controller' 	=> 'estate_listing_ui',
			'path' 			=> null,
			'ui' 			=> 'estate_listing_form_ui',
			'uipath' 		=> null
		  ),
    'estate_agencies'	=> array(
			'controller' 	=> 'estate_agencies_ui',
			'path' 			=> null,
			'ui' 			=> 'estate_agencies_form_ui',
			'uipath' 		=> null
		  ),
    
    'estate_presets'	=> array(
			'controller' 	=> 'estate_presets_ui',
			'path' 			=> null,
			'ui' 			=> '',
			'uipath' 		=> null
		  ),
    'estate_instruct'	=> array(
			'controller' 	=> 'estate_instruct_ui',
			'path' 			=> null,
			'ui' 			=> '',
			'uipath' 		=> null
		  )
	);
	
  
  
  protected $adminMenu = array(
    'estate_properties/list' => array('caption'=> EST_AMENU_PROPLIST.(EST_NEW_PROPSUBMITTED > 0 ? ' ('.EST_NEW_PROPSUBMITTED.')' : ''), 'perm' => 'P'),
    'estate_agencies/profile' => array('caption'=> EST_MYAGENTPROFILE, 'perm' => 'P'),
    'estate_agencies/edit' => array('caption'=> EST_GEN_AGENCY.' '.EST_GEN_PROFILE, 'perm' => 'P'),
    'estate_presets/presets' => array('caption'=> EST_GEN_DATAPRESETS, 'perm' => 'P'),
    'estate_instruct/help' => array('caption'=> EST_GEN_HOWTO, 'perm' => 'P')
    );
  
  protected $adminMenuAliases = array(
		'estate_properties/edit'	=> 'estate_properties/list',
		'estate_properties/create'	=> 'estate_properties/list',
		'estate_agencies/agent'	=> 'estate_agencies/edit',
		'estate_agencies/create'	=> 'estate_agencies/edit'
    );
  }






class estate_adminAgent extends e_admin_dispatcher{

	protected $menuTitle = EST_PLUGNAME;
	protected $modes = array(
		'estate_properties'	=> array(
			'controller' 	=> 'estate_listing_ui',
			'path' 			=> null,
			'ui' 			=> 'estate_listing_form_ui',
			'uipath' 		=> null
		  ),
    'estate_agencies'	=> array(
			'controller' 	=> 'estate_agencies_ui',
			'path' 			=> null,
			'ui' 			=> 'estate_agencies_form_ui',
			'uipath' 		=> null
		  ),
    'estate_instruct'	=> array(
			'controller' 	=> 'estate_instruct_ui',
			'path' 			=> null,
			'ui' 			=> '',
			'uipath' 		=> null
		  )
	);
	
  
  protected $adminMenu = array(
    'estate_properties/list' => array('caption'=> EST_AMENU_PROPLIST.(EST_NEW_PROPSUBMITTED > 0 ? ' ('.EST_NEW_PROPSUBMITTED.')' : ''), 'perm' => 'P'),
    'estate_agencies/profile' => array('caption'=> EST_MYAGENTPROFILE, 'perm' => 'P'),
    'estate_instruct/help' => array('caption'=> EST_GEN_HOWTO, 'perm' => 'P')
    );
  
	protected $adminMenuAliases = array(
		'estate_properties/edit'	=> 'estate_properties/list',
		'estate_properties/create'	=> 'estate_properties/list'
    );
  }



class estate_adminUser extends e_admin_dispatcher{

	protected $menuTitle = EST_PLUGNAME;
	protected $modes = array(
    'estate_agencies'	=> array(
			'controller' 	=> 'estate_agencies_ui',
			'path' 			=> null,
			'ui' 			=> 'estate_agencies_form_ui',
			'uipath' 		=> null
		  ),
    'estate_instruct'	=> array(
			'controller' 	=> 'estate_instruct_ui',
			'path' 			=> null,
			'ui' 			=> '',
			'uipath' 		=> null
		  )
	);
	
  
  protected $adminMenu = array(
    'estate_agencies/users' => array('caption'=> 'User List*', 'perm' => 'P'),
    'estate_instruct/help' => array('caption'=> EST_GEN_HOWTO, 'perm' => 'P')
    );
  
	protected $adminMenuAliases = array();
  }


class estate_newAgent extends e_admin_dispatcher{
	protected $menuTitle = EST_PLUGNAME;
	protected $modes = array(
    'estate_agencies'	=> array(
			'controller' 	=> 'estate_agencies_ui',
			'path' 			=> null,
			'ui' 			=> 'estate_agencies_form_ui',
			'uipath' 		=> null
		  ),
    'estate_instruct'	=> array(
			'controller' 	=> 'estate_instruct_ui',
			'path' 			=> null,
			'ui' 			=> '',
			'uipath' 		=> null
		  )
    );
	
  protected $adminMenu = array(
    'estate_agencies/profile' => array('caption'=> EST_MYAGENTPROFILE, 'perm' => 'P'),
    'estate_instruct/help' => array('caption'=> EST_GEN_HOWTO, 'perm' => 'P')
    );
  
	protected $adminMenuAliases = array();
  }



class estate_adminFirst extends e_admin_dispatcher{
  protected $menuTitle = EST_PLUGNAME;
	protected $modes = array(
    'estate_instruct'	=> array(
			'controller' 	=> 'estate_instruct_ui',
			'path' 			=> null,
      'perm'=>'0',
			'ui' 			=> '',
			'uipath' 		=> null
		  )
	 );
  protected $adminMenu = array(
    'estate_instruct/help' => array('caption'=> EST_GEN_FIRSTTIME, 'perm' => 'P'),
    'estate_instruct/done' => array('caption'=> EST_INSTRDONE, 'perm' => 'P')
    );
  
  }






class estate_agentFail extends e_admin_dispatcher{
	protected $menuTitle = EST_PLUGNAME;
  protected $modes = array('estate_fail'	=> array(
			'controller' 	=> 'estate_fail_ui',
			'path' 			=> null,
			'ui' 			=> null, //'estate_fail_form_ui',
			'uipath' 		=> null
		  ),
    'estate_instruct'	=> array(
			'controller' 	=> 'estate_instruct_ui',
			'path' 			=> null,
      'perm'=>'0',
			'ui' 			=> '',
			'uipath' 		=> null
		  )
    );
  protected $adminMenu = array(
    'estate_fail/agent' => array('caption'=> 'Errors*', 'perm' => 'P'),
    'estate_instruct/help' => array('caption'=> EST_GEN_HOWTO, 'perm' => 'P')
    );
  }


class estate_adminFail extends e_admin_dispatcher{
	protected $menuTitle = EST_PLUGNAME;
  protected $modes = array(
    'estate_fail'	=> array(
			'controller' 	=> 'estate_fail_ui',
			'path' 			=> null,
			'ui' 			=> null, //'estate_fail_form_ui',
			'uipath' 		=> null
		  ),
    'estate_instruct'	=> array(
			'controller' 	=> 'estate_instruct_ui',
			'path' 			=> null,
      'perm'=>'0',
			'ui' 			=> '',
			'uipath' 		=> null
		  )
    );
  protected $adminMenu = array(
    'estate_fail/init' => array('caption'=> 'Errors', 'perm' => 'P'),
    'estate_instruct/help' => array('caption'=> EST_GEN_HOWTO, 'perm' => 'P')
    );
  }





class estate_fail_ui extends e_admin_ui{
	protected $pluginTitle		= EST_PLUGNAME;
	protected $pluginName		= 'estate';
  protected $fields = array ();
  
  function agentPage(){
    return e107::getParser()->toHTML('<p>no agent ID</p>',true);
    }
  
  
  function initPage(){
    $text = '<p>'.EST_INST_FIRSTRUN1.'</p>';
    if(USERID == 1){
      $text .= '
      <p>'.EST_INST_FIRSTRUN2.'</p>
      <ul>
        <li>'.EST_INST_CLASS1.'</li>
        <li>'.EST_INST_CLASS2.'</li>
        <li>'.EST_INST_CLASS3.'</li>
      </ul>
      <p>'.EST_INST_FIRSTRUN3.'</p>
      <a class="btn btn-primary" href="'.e_ADMIN.'users.php">'.EST_GEN_ASSUSRCLASSES.'</a>';
      }
    else{
      $text .= '
      <p>'.EST_INST_FIRSTRUN4.'</p>
      <ul>
        <li>'.EST_INST_CLASS2.'</li>
        <li>'.EST_INST_CLASS3.'</li>
      </ul>
      <p>'.EST_INST_FIRSTRUN5.'</p>';
      }
    return e107::getParser()->toHTML($text,true);
    }
  
  public function init() {
    if(EST_USERPERM == 0){e107::getMessage()->addError('<p class="estStopForm">'.EST_AWARN_006.'</p>');}
    else{
      if(EST_AGENTID == 0){e107::getMessage()->addWarning('<p class="estStopForm">'.EST_AWARN_007.'</p>');}
      //if(EST_AGENCYID == 0){e107::getMessage()->addWarning('<p class="estStopForm">'.EST_AWARN_008.'</p>');}
      }
		}
  
  public function renderHelp(){
    $text = e107::getParser()->toHTML('<p>'.EST_AWARN_000.' '.EST_AWARN_002.'</p>',true);
    //<p>'.EST_AWARN_004.'</p><p>'.EST_AWARN_005.'</p>
		return array('caption'=>LAN_HELP,'text'=> $text);
	  }
  }
