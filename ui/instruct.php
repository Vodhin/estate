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
    $tab = intval($this->getID());
    $public_act = intval($GLOBALS['EST_PREF']['public_act']);
    
    $text = '
    <div id="estMainHelpPage">
      <ul id="estAgencyListTabs" class="nav nav-tabs">
        <li class="nav-item'.($tab == 0 ? ' active' : '').'">
          <a class="nav-link active" href="#tab-0" data-toggle="tab">'.EST_INSTR000.'</a>
        </li>
        <li class="nav-item'.($tab == 1 ? ' active' : '').'">
          <a class="nav-link" href="#tab-1" data-toggle="tab">'.EST_INSTR010.'</a>
        </li>
        <li class="nav-item'.($tab == 2 ? ' active' : '').'">
          <a class="nav-link" href="#tab-2" data-toggle="tab">'.EST_INSTR030.'</a>
        </li>
      </ul>
      <div class="tab-content estMargB300">
        <div class="tab-pane'.($tab == 0 ? ' active' : '').'" id="tab-0" role="tabpanel">
          <div>
            <h3>'.EST_INSTR000.'</h3>
            <p>'.EST_INSTR001.'</p>
            <p>'.EST_INSTR001a.'</p>
          </div>
          <div>
            <h3>'.EST_INSTR002.'</h3>
            <p>'.EST_INSTR002a.':</p>
            <table id="estInstrMenuTBL">
              <tbody>
                <tr>
                  <td><b>'.EST_GEN_ESTADMINS.'</b></td>
                  <td><b>'.EST_GEN_ESTMANAGERS.'</b></td>
                  <td><b>'.EST_GEN_ESTAGENTS.'</b></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td class="VAT"><img src="'.EST_PATHABS_IMAGES.'instr_admmenu3.png" /><p>'.EST_GEN_ESTADMINS.' '.EST_INSTR002b.'</p></td>
                  <td class="VAT"><img src="'.EST_PATHABS_IMAGES.'instr_admmenu2.png" /><p>'.EST_GEN_ESTMANAGERS.' '.EST_INSTR002c.'</p></td>
                  <td class="VAT"><img src="'.EST_PATHABS_IMAGES.'instr_admmenu1.png" /><p>'.EST_GEN_ESTAGENTS.' '.EST_INSTR002d.'</p></td>
                  <td>&nbsp;</td>
                </tr>
              </tbody>
            </table>
            <p>'.EST_INSTR003.' ('.(EST_USERPERM >= 2 ? EST_INSTR003a : EST_INSTR003b).').</p>
            <p>'.EST_INSTR003c.'</p>
          </div>
          <div>
            <h3>'.EST_GEN_NONAGENTLISTINGS.' </h3>
            <p>['.EST_GEN_CURRENTLY.' '.($public_act == 255 ? LAN_DISABLED : LAN_ENABLED).'] '.EST_INSTR005a.'</p>
            <p>'.EST_INSTR005b.'</p>
          </div>
          <div>
            <h3>'.EST_INSTR004.'</h3>
            <p>'.EST_INSTR004a.'</p>
            <hr />
            <h3>'.EST_GEN_ESTADMINS.'</h3>
            <p>'.EST_INSTR004b.'</p>
            <p><img src="'.EST_PATHABS_IMAGES.'instr_agtagencylist.png" /></p>
            <p>'.EST_INSTR004c.' <i>'.EST_INSTR004d.'</i></p>
            <p><img src="'.EST_PATHABS_IMAGES.'instr_agtuserlist.png" /></p>
            <p>'.EST_INSTR004e.'</p>
            <p>'.EST_INSTR004f.'</p>
            <p><img src="'.EST_PATHABS_IMAGES.'instr_agtadduser.png" /></p>
            <p>'.EST_INSTR004g.'</p>
            <hr />
            <h3>'.EST_GEN_ESTMANAGERS.'</h3>
            <p>'.EST_INSTR004g.'</p>
            <p><img src="'.EST_PATHABS_IMAGES.'instr_agtagency.png" /></p>
            <p>'.EST_INSTR004h.'</p>
            <p>'.EST_INSTR004i.'</p>
            <p><img src="'.EST_PATHABS_IMAGES.'instr_agtagentlist.png" /></p>
            <p>'.EST_INSTR004j.'</p>
          </div>
        </div>
        
        <div class="tab-pane'.($tab == 1 ? ' active' : '').'" id="tab-1" role="tabpanel">
          <div>
            <h3>'.EST_INSTR010.'</h3>
            <p>'.EST_INSTR011.'</p>
            <img src="'.EST_PATHABS_IMAGES.'instr002.png" class="estInstImg" />
            <p>'.EST_INSTR012.'</p>
            <img src="'.EST_PATHABS_IMAGES.'instr003.png" class="estInstImg" />
            <p>'.EST_INSTR013.'</p>
          </div>
          <div>
            <h3>'.EST_INSTR020.'</h3>
            <p>'.EST_INSTR021.'</p>
            <img src="'.EST_PATHABS_IMAGES.'instr004.png" class="estInstImg" />
          </div>
          <div>
            <p>'.EST_INSTR022.'</p>
          </div>
        </div>
        
        <div class="tab-pane'.($tab == 2 ? ' active' : '').'" id="tab-2" role="tabpanel">
          <div>
            <h3>'.EST_INSTR030.'</h3>
            <p>'.EST_INSTR031.'</p>
            <img src="'.EST_PATHABS_IMAGES.'instr005.png" class="estInstImg" style="width:75%;" />
            <p>'.EST_INSTR032.'</p>
            <p>'.EST_INSTR033.'</p>
            <p>'.EST_INSTR033a.'</p>
            
            <table>
              <tr>
                <td><img src="'.EST_PATHABS_IMAGES.'instr006.png" class="estInstImg" /></td>
                <td class="VAT">
                  <p>'.EST_INSTR034.'</p>
                  <p>'.EST_INSTR034a.'</p>
                  <p>'.EST_INSTR034b.'</p>
                  <p>'.EST_INSTR034c.'</p>
                </td>
              </tr>
              <tr>
                <td><img src="'.EST_PATHABS_IMAGES.'instr007.png" class="estInstImg" /></td>
                <td class="VAT">
                  <p>'.EST_INSTR035.'</p>
                  <p>'.EST_INSTR035a.'</p>
                  <p>'.EST_INSTR035b.'</p>
                </td>
              </tr>
              <tr>
                <td><img src="'.EST_PATHABS_IMAGES.'instr007a.png" class="estInstImg" /></td>
                <td class="VAT">
                  <p>'.EST_INSTR036.'</p>
                </td>
              </tr>
              <tr>
                <td><img src="'.EST_PATHABS_IMAGES.'instr008.png" class="estInstImg" /></td>
                <td class="VAT">
                  <p>'.EST_INSTR038.'</p>
                </td>
              </tr>
              <tr>
                <td><img src="'.EST_PATHABS_IMAGES.'instr009.png" class="estInstImg" /></td>
                <td class="VAT">
                  <p>'.EST_INSTR039.'</p>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>';
    
    
    if(isset($GLOBALS['EST_PREF']['firsttime'])){
      $frm = e107::getForm(false, true);
      $text .= '
      <form method="post" action="'.e_SELF.'?mode=estate_agencies&action=profile" id="estFirstTimeForm">
        <div>'.EST_INSTRNEXT1.'</div>
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

