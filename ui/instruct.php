<?php
if(!defined('e107_INIT')){exit;}
if(!ADMIN){exit;}
if(!getperms('P')){exit;}




class estate_instruct_ui extends e_admin_ui{
  protected $pluginTitle		= EST_PLUGNAME;
	protected $pluginName		= 'estate';
  
  //public function __construct($update= false){
  
  function donePage(){
    $tp = e107::getParser();
    $frm = e107::getForm(false, true);
    return '
      <div>'.EST_INSTRNEXT1.'</div>
      <form method="post" action="'.e_SELF.'?mode=estate_agencies&action=profile" id="estFirstTimeForm">
        <div>'.$frm->admin_button('estFirsttimeDone',EST_INSTRDONE1, 'submit').'</div>
      </form>';
    }
  
  
  function listPage(){e107::redirect(e_SELF."?mode=estate_instruct&action=help");}
  function editPage(){e107::redirect(e_SELF."?mode=estate_instruct&action=help");}
  function createPage(){e107::redirect(e_SELF."?mode=estate_instruct&action=help");}
  
  function helpPage(){
    $tp = e107::getParser();
    
    $text = '
    <div id="estMainHelpPage">
      <div class="tab-content estMargB300">';
    $TBS = estHelpTabs();
    $text .= e107::getForm(false,true)->tabs($TBS,array('active'=>0,'fade'=>0,'class'=>'estHelpSysTabs'));
    
    $text .= '
      </div>
    </div>';
    
    if(isset($GLOBALS['EST_PREF']['firsttime'])){
      $frm = e107::getForm(false, true);
      $text .= '
      <form method="post" action="'.e_SELF.'?mode=estate_agencies&action=profile" id="estFirstTimeForm">
        <div>'.$tp->toHTML(EST_INSTRNEXT1).'</div>
        <div>'.$frm->admin_button('estFirsttimeDone',EST_INSTRDONE1, 'submit').'</div>
      </form>';
      }
    
    $text .= '</div>';
    return $text;
    }
  
	public function init() {
    $tp = e107::getParser();
    $mes = e107::getMessage();
	  }
  
	public function renderHelp(){
		$tp = e107::getParser();
    $hlpmode = $this->getMode();
    $hlpactn = $this->getAction();
    
	  }
  }

