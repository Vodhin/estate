<?php
if(!defined('e107_INIT')){exit;}

//if(!ADMIN){exit;}
//if(!getperms('P')){exit;}


class estateCore{
  
	private $permList = array();
	private $estData = array();
	public $modArray,$prefs;
  
  
  public function __construct($update= false){
    $this->e107 = e107::getInstance();
    $sql = e107::getDB();
		$tp = e107::getParser();
    $msg = e107::getMessage();
    
		$this->prefs = e107::getPlugConfig('estate');
    $setup = $this->prefs->get('firsttime');
    
    if($setup == 2){
      $ESTATEUCLASSES = array(
        'ESTATE_ADMIN' => "userclass_description='".EST_PLUGNAME." ".EST_GEN_ADMINISTRATOR."', userclass_editclass='250', userclass_parent='254', userclass_visibility='250', userclass_icon='fas-building-user.glyph' WHERE userclass_id='".intval(ESTATE_ADMIN)."' LIMIT 1",
        'ESTATE_MANAGER' => "userclass_description='".EST_PLUGNAME." ".EST_GEN_MANAGER."', userclass_editclass='250', userclass_parent='254', userclass_visibility='250', userclass_icon='fas-users-between-lines.glyph' WHERE userclass_id='".intval(ESTATE_MANAGER)."' LIMIT 1",
        'ESTATE_AGENT' => "userclass_description='".EST_PLUGNAME." ".EST_GEN_AGENT."', userclass_editclass='250', userclass_parent='254', userclass_visibility='250', userclass_icon='fas-house-chimney-user.glyph' WHERE userclass_id='".intval(ESTATE_AGENT)."' LIMIT 1"
        );
      
      
      foreach($ESTATEUCLASSES as $eck=>$ecv){
        if($sql->update("userclass_classes",$ecv)){$ucsuc .= '<div>'.EST_GEN_UPDATED.' '.EST_GEN_USERCLASS.' '.$eck.' </div>';}
        else{
          $dberr = $sql->getLastErrorText();
          if($dberr){$ucerr .= '<p>'.EST_GEN_UPDATE.' '.EST_GEN_USERCLASS.' '.$eck.' '.EST_GEN_FAILED.': '.$ecv.' <div>'.$dberr.'</div></p>';}
          else{$dbinf .= '<div>'.EST_GEN_UPDATE.' '.EST_GEN_USERCLASS.' '.$eck.' - '.EST_GEN_UPTODATE.'</div>';}
          unset($dberr);
          }
        }
      $msg->addWarning(EST_INSTSETUCLEVOK);
      
      if($ucsuc){$msg->addSuccess($ucsuc);}
      if($dbinf){$msg->addInfo($dbinf);}
      if($ucerr){$msg->addWarning(EST_INSTSETUCLEVNOK1.': '.$ucerr.' '.EST_INSTSETUCLEVNOK2);}
      if(!$ucerr){
        $this->prefs->set('firsttime',1);
        $this->prefs->save();
        }
      unset($ucsuc,$dbinf,$ucerr);
      }
    
    if(!empty($_POST['estFirsttimeDone'])){
		  $this->prefs->removePref('firsttime');
      $this->prefs->save();
      
      $FUSERS = array();
      $sql->gen("SELECT user_id,user_name,user_class FROM #user WHERE user_admin='1' AND user_perms='0'");
      while($row = $sql->fetch()){array_push($FUSERS,$row);}
      
      if(count($FUSERS)){
        foreach($FUSERS as $k=>$v){
          $user_class = explode(',',$v['user_class']);
          if(!in_array(ESTATE_ADMIN,$user_class)){array_push($user_class,ESTATE_ADMIN);}
          if($sql->update("user","user_class='".implode(',',array_map("trim",array_filter($user_class)))."' WHERE user_id='".intval($v['user_id'])."' LIMIT 1")){
            $msg->addSuccess($v['user_name'].' '.EST_INSTADDEDMAINADMIN);
            }
          }
        }
      }
    
    if(!defined("EST_AGENTID")){
      $EST_AGENT = $this->estGetUserById(USERID);
      $EST_AGENT['perm'] = intval(EST_USERPERM);
      define("EST_AGENTID",intval($EST_AGENT['agent_idx']));
      define("EST_AGENCYID",intval($EST_AGENT['agent_agcy']));
      define("EST_SELLERUID",intval($EST_AGENT['agent_uid']));
      $msg->addInfo('Defined in Core Init: '.$EST_AGENT['agent_name'].' ');
      }
    }
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  private function estqry(){
    //$setup = $this->prefs->get('setup');
    $URK = array();
    if(e_QUERY){
      $QRYX = str_replace('amp;', '', explode('&',e_QUERY));
      foreach($QRYX as $x=>$y){
        $z = explode('=',$y);
        $URK[strtolower($z[0])] = $z[1];
        }
      unset($x,$y,$z);
      if(!isset($URK['id'])){$URK['id'] = 0;}
      if(!isset($URK['uid'])){$URK['uid'] = USERID;}
      }
    return $URK;
    }
  
  
  
  public function estGetUserById($UID=0){
    $sql = e107::getDB();
    if(intval($UID) > 0){
      $tp = e107::getParser();
      
      $ret1 = array();
      $ret2 = array();
      $sql->gen("SELECT user_id,user_name,user_loginname,user_email,user_class,user_admin,user_perms,user_signature,user_image FROM #user WHERE user_id = '".intval($UID)."'");
      $ret1 = $sql->fetch();
      $ret1['user_profimg'] = $tp->toAvatar($ret1,array('type'=>'url'));
      
      $ret2 = $this->getAgentData($UID);
      
      if(intval($ret2['agent_idx']) == 0){
        $ret2['agent_uid'] = intval($UID);
        if(trim($ret2['agent_name']) == ''){$ret2['agent_name'] = (trim($ret1['user_login']) !== '' ? $ret1['user_login'] : $ret1['user_name']);}
        $agent['agent_txt1'] = $agent['user_signature'];
        }
      return array_merge($ret1, $ret2);
      }
    }
  
  public function estGetUsers($mode=0){
    $sql = e107::getDB();
    $tp = e107::getParser();
    $ret1 = array();
    
    $usrClasses = $this->getUsrClassIds(1);
    
    $sql->gen("SELECT user_id,user_name,user_loginname,user_email,user_admin,user_perms,user_class,user_image,user_signature FROM #user WHERE user_admin='1'".($mode == 1 ? "" : " AND user_class IN (".$usrClasses.")").((EST_USERPERM === 4 || USERID === 1) ? "" : " AND NOT user_perms='0'").(USERID === 1 ? "" : " AND NOT user_id='1'")." ORDER BY user_name ASC");
    
    while($rows = $sql->fetch()){
      $UID = intval($rows['user_id']);
      $rows['user_profimg'] = $tp->toAvatar($rows,array('type'=>'url'));
      if($rows['user_perms'] === '0'){
        if(USERID === $UID || USERID === 1){
          $ret1[$UID] = $rows;
          if($AGNT[$UID]){array_merge($ret1[$UID], $AGNT[$UID]);}
          }
        }
      else{
        $UPERMS = explode('.',$rows['user_perms']);
        if(in_array(EST_PLUGID,$UPERMS)){
          $ret1[$UID] = $rows;
          if($AGNT[$UID]){array_merge($ret1[$UID], $AGNT[$UID]);}
          }
        }
      }
    //unset($AGNT);
    return $ret1;
    }
  
  public function estGetAllUsers(){ 
    $sql = e107::getDB();
    $tp = e107::getParser();
    $ret = array();
    
    
    if($sql->select('estate_agents', '*', '')){
      while($row = $sql->fetch()){$AGNT[$row['agent_uid']] = $row;}
      }
    
    $ui=0;
    $usrClasses = $this->getUsrClassIds(1);
    $sql->gen("SELECT user_id,user_name,user_loginname,user_email,user_admin,user_perms,user_class,user_signature,user_image FROM #user WHERE user_class IN (".$usrClasses.") ORDER BY user_name ASC");
    while($rows = $sql->fetch()){
      $rows['user_profimg'] = $tp->toAvatar($rows,array('type'=>'url'));
      $rows['dta'] = $AGNT[$rows['user_id']];
      $ret[$ui] = $rows;
      $ui++;
      }
    
    unset($usrClasses,$AGNT,$ui);
    return $ret;
    }
  
  
  
  
  public function estGetAgencyById($id){
    if(EST_USERPERM < 3 && EST_AGENCYID !== intval($id)){
      return;
      }
    $sql = e107::getDB();
    $sql->gen("SELECT * FROM #estate_agencies WHERE agency_idx='".intval($id)."' LIMIT 1");
    return $sql->fetch();
    }
  
  
  
  public function estGetAgencyFull(){
    $sql = e107::getDB();
    $tp = e107::getParser();
    $ret = array();
    
    $agents = array();
    
    $sql->gen("SELECT #estate_agents.*,user_id,user_name,user_loginname,user_email,user_admin,user_perms,user_class,user_signature,user_image FROM #estate_agents JOIN #user ON user_id = agent_uid ORDER BY agent_name ASC");
    while($rows = $sql->fetch()){
      $agents[$rows['agent_agcy']][$rows['agent_idx']] = $this->estUserAgentData($rows);
      }
    
    $ui = 0;
    $sql->gen("SELECT * FROM #estate_agencies ORDER BY agency_name ASC");
    while($rows = $sql->fetch()){
      $rows['logo'] = $this->estAgecnyThmUrl($rows);
      $rows['agents'] = array();
      if(count($agents[$rows['agency_idx']])){
        $ai = 0;
        foreach($agents[$rows['agency_idx']] as $k=>$v){
          $rows['agents'][$ai] = $v;
          $ai++;
          }
        }
      $ret[$ui] = $rows;
      $ui++;
      }
    
    return $ret;
    }
  
  
  public function estGetAllAgencies($mode=0){
    $sql = e107::getDB();
    $tp = e107::getParser();
    $ret = array();
    
    
    if($mode == 1){
      $sql->gen("SELECT agent_agcy, COUNT('agent_idx') AS agency_agtct FROM #estate_agents GROUP BY agent_agcy");
      while($rows = $sql->fetch()){$AGT[$rows['agent_agcy']] = $rows['agency_agtct'];}
      $sql->gen("SELECT prop_agency, COUNT('prop_idx') AS agency_lstct FROM #estate_properties GROUP BY prop_agency");
      while($rows = $sql->fetch()){$LST[$rows['prop_agency']] = $rows['agency_lstct'];}
      }
    
    $QRY = "SELECT * FROM #estate_agencies";
    if(EST_USERPERM < 3){
      if(intval(EST_AGENCYID) > 0){
        $QRY .= " WHERE agency_idx='".intval(EST_AGENCYID)."'";
        }
      }
    
    $sql->gen($QRY." ORDER BY agency_name ASC");
    
    $dberr = $sql->getLastErrorText($dberr);
    if($dberr){e107::getMessage()->addError($dberr);}
    //$ret['error'] = $dberr;
    
    $ui=0;
    while($rows = $sql->fetch()){
      $rows['agency_altimg'] = $this->estAgecnyThmUrl($rows);
      $rows['agency_agtct'] = $AGT[$rows['agency_idx']];
      $rows['agency_listct'] = $LST[$rows['agency_idx']];
      
      $ret[$ui] = $rows;
      $ui++;
      }
    unset($ui);
    return $ret;
    }
  
  
  
  
  public function estGetAllAgents($MODE=0){
    $EST_PREF = e107::pref('estate');
    $sql = e107::getDB();
    $tp = e107::getParser();
    $ret = array();
    $ui=0;
    
    $sql->gen("SELECT user_id,user_name,user_loginname,user_email,user_admin,user_perms,user_class,user_signature,user_image, #estate_agents.* FROM #estate_agents LEFT JOIN #user ON user_id = agent_uid".(USERID !== 1 ? " WHERE NOT user_id='1'" : ""));
    
    while($rows = $sql->fetch()){
      
      $AGTCLSES = explode(",",$rows['user_class']);
      if(in_array(ESTATE_ADMIN,$AGTCLSES)){
        $rows['user_role'] = EST_GEN_MAIN." ".EST_GEN_ADMIN;
        $rows['user_mgr'] = 4;
        }
      elseif(in_array(ESTATE_MANAGER,$AGTCLSES)){
        $rows['user_role'] = EST_GEN_MANAGER;
        $rows['user_mgr'] = 3;
        }
      elseif(in_array(ESTATE_AGENT,$AGTCLSES)){
        $rows['user_role'] = EST_GEN_AGENT;
        $rows['user_mgr'] = 2;
        }
      else{
        $rows['user_role'] = EST_GEN_MEMBER;
        $rows['user_mgr'] = intval($EST_PREF['public_mod']);
        }
      
      if($rows['user_mgr'] <= EST_USERPERM || intval($rows['user_id']) == intval(USERID)){
        
        $rows['user_profimg'] = $tp->toAvatar($rows,array('type'=>'url'));
        $rows['agent_profimg'] = $rows['user_profimg'];
        if(intval($rows['agent_imgsrc']) == 1 && trim($rows['agent_image']) !== ""){
          $rows['agent_profimg'] = EST_PTHABS_AGENT.$rows['agent_image'];
          }
        
        $ret[$ui] = $rows;
        $ui++;
        }
      }
    unset($ui);
    return $ret;
    }
  
  
  public function estGetAgentById($AGTID,$UID=USERID){
    $sql = e107::getDB();
    $tp = e107::getParser();
    $sql->gen("SELECT user_id,user_name,user_loginname,user_email,user_admin,user_perms,user_class,user_signature,user_image, #estate_agents.*, #estate_agencies.* FROM #estate_agents LEFT JOIN #estate_agencies ON agent_agcy=agency_idx LEFT JOIN #user ON user_id=agent_uid WHERE ".(intval($AGTID) > 0 ? "agent_idx='".intval($AGTID)."'" : "agent_uid='".intval($UID)."'")." LIMIT 1");
    $rows = $sql->fetch();
    
    if(intval($rows['agent_imgsrc']) == 1 && trim($rows['agent_image']) !== ""){$rows['agent_profimg'] = EST_PTHABS_AGENT.$tp->toHTML($rows['agent_image']);}
    else{$rows['agent_profimg'] = $tp->toAvatar($rows,array('type'=>'url'));}
    
    $rows['agent_propct'] = intval(0);
    if(intval($rows['agent_idx']) > 0){
      if($sql->gen("SELECT COUNT('prop_idx') AS agent_propct FROM #estate_properties WHERE prop_agent ='".intval($rows['agent_idx'])."' GROUP BY prop_agent")){
        $apc = $sql->fetch();
        $rows['agent_propct'] = $apc['agent_propct'];
        }
      }
    
    return $rows;
    }
  
  
	public function getAgentData($UID){
    $sql = e107::getDB();
    $ret = array();
    $TQRY = "
    SELECT #estate_agents.*, #estate_agencies.*
    FROM #estate_agents 
    LEFT JOIN #estate_agencies
    ON agent_agcy = agency_idx
    WHERE agent_uid = '".$UID."'
    LIMIT 1";
    if($sql->gen($TQRY)){
      $ret = $sql->fetch();
      }
    return $ret;
    }
  
  //public function estGetCompDta($id=0){}
  
  public function estAgecnyThmUrl($v){
    $tp = e107::getParser();
    $pref = e107::pref();
    if(intval($v['agency_imgsrc']) == 1 && trim($v['agency_image']) !== ''){return EST_PTHABS_AGENCY.$tp->toHTML($v['agency_image']);}
    else{return $tp->thumbUrl($pref['sitelogo'],false,false,true);}
    }
  
  public function estCompThmUrl($company_imgsrc,$company_image,$v){
    $tp = e107::getParser();
    $pref = e107::pref();
    if(intval($v['agency_imgsrc']) == 1 && trim($v['agency_image']) !== ''){return EST_PTHABS_AGENCY.$tp->toHTML($v['agency_image']);}
    else{return $tp->thumbUrl($pref['sitelogo'],false,false,true);}
    }
  
  
  
  public function estGetCompLocs($disp){
    $ret = array();
    $sql = e107::getDB();
    $tp = e107::getParser();
    
    if($disp == 1){
      if($sql->select('estate_agencies', '*', '')){
  			while($row = $sql->fetch()){
          $ret[$row['agency_idx']] = $row;
          }
        
        if($sql->select('estate_agents', '*', '')){
          while($row = $sql->fetch()){$ret[$row['agent_agcy']]['agents'][$row['agent_idx']] = $row;}
          }
        
        if($sql->select('estate_properties', '*', 'prop_idx > "0" ORDER BY prop_name ASC')){
          while($row = $sql->fetch()){
            $ret[$row['prop_agency']]['prop'][$row['prop_idx']] = $row;
            $ret[$row['prop_agency']]['aprop'][$row['prop_agent']][$row['prop_zoning']][$row['prop_status']][$row['prop_idx']] = $row;
            //prop_listype 
            }
          }
        }
      }
    else{
      if($sql->select('estate_agencies', '*', '')){
  			while($row = $sql->fetch()){
          $ret[$row['agency_idx']] = $row;
          }
        }
      }
    return $ret;
    }
  
  public function estSectLevel(){
    return array('Subdivision','Property','Spaces');
    }
  
  
  public function estGetZoning(){
    $ret = array();
    $sql = e107::getDB();
    if($sql->select('estate_zoning', '*', '')){
			while($row = $sql->fetch()){$ret[$row['zoning_idx']] = $row['zoning_name'];}
      }
      
    return $ret;
    }
  
  
  public function estGetListings($mode,$cid=0,$agy=0,$agt=0){
    $ret = array();
    if($cid > 0){
      $sql = e107::getDB();
      if($mode == 1){
        if($sql->select('estate_properties', 'prop_idx,prop_name,prop_status,prop_zoning', 'prop_idx > "0" ORDER BY prop_zoning ASC, prop_idx ASC')){
    			while($row = $sql->fetch()){$ret[$row['prop_zoning']][$row['prop_idx']] = $row;} //[$row['prop_status']]
          }
        }
      else{}
      }
    return $ret;
    }
  
  
  
  public function estGetAgents($mode,$cid=0,$agy=0,$agt=0){
  //may be dead.
    $ret = array();
    if($cid > 0){
      $sql = e107::getDB();
      if($sql->select('estate_agents', 'agent_idx,agent_name,agent_uid', '')){
  			while($row = $sql->fetch()){$ret['agts'][$row['agent_idx']] = $row;}
        }
      }
    return $ret;
    }
  
  
  
  
  
  
  
  public function estAgencyPHPPopoverform($dta){
    $tp = e107::getParser();
    $dtaStr = $this->estDataStr($dta);
    extract($dta);
    
    $text = '
      <div id="estAgenciesAvail" class="estPopBoxTab"'.$dtaStr.'>
        <button id="estAgencyRemUsr" class="btn btn-primary btn-sm" data-agyid="0" style="display:none;" title="'.EST_GEN_REMOVEAGT.'" data-conf="'.EST_GEN_REMOVEAGTC1.'"=>'.EST_GEN_REMOVEAGTLOC.'</button>';
    unset($dtaStr);
    
    $locs = $this->estGetCompLocs(0);
    if(count($locs)){
      foreach($locs as $k=>$v){
        $dtaStr = $this->estDataStr($v);
        $image = $this->estCompThmUrl($company_imgsrc,$company_image,$v);
        $text .= '
        <button class="btn btn-primary btn-sm estAgntUserBtn" style="background-image:url(\''.$image.'\')"'.$dtaStr.'">
          <div class="estAgntUserDta">
            <h4>'.$tp->toHTML($v['agency_name']).'</h4>
            <div class="caddr">'.$tp->toHTML($v['agency_addr']).'</div>
          </div>
        </button>';
        unset($dtaStr);
        }
      }
    $text .= '
      </div>';
    return $text;
    }
  
  
  
  
  public function est_PropNameAddr($dta){
    $tp = e107::getParser();
    $addr2 = $dta->get('prop_addr2');
    $addr = $dta->get('prop_addr1').($addr2 !== '' ? '<br />'.$addr2 : '').'<br />'.$dta->get('prop_city').', '.$dta->get('prop_state').' '.$dta->get('prop_zip');
    
    return '<div>'.$tp->toHTML($dta->get('prop_name')).'</div><div>'.$tp->toHTML($addr).'</div>';
    }
  
  
  
  public function estUserAgentTbl($dta){
    $tp = e107::getParser();
    $agency_idx = intval($dta['agency_idx']);
    //return $agency_idx;
    
    if($agency_idx == 0){
      return $this->estNoCompID(EST_GEN_ADDUSERS);
      }
    
    $cDtaStr = $this->estDataStr($dta);
    
    $locs = $this->estGetCompLocs(0);
    $agents = $this->getAllAgents();
    
    
    
    if(count($agents) == 0){
      return EST_ERR_NOUSERSINCLASS;
      }
    
    $OCCT = 0;
    if(count($agents)){
      foreach($agents as $k=>$v){
        $agtimg = $tp->toAvatar($v,array('type'=>'url'));
        $dtaStr = $this->estDataStr($v);
        if($v['agency_idx']){
          if($v['agent_imgsrc'] == 1 && trim($v['agent_image'] !== '')){$agtimg = EST_PTHABS_AGENT.$tp->toHTML($v['agent_image']);}
          
          if($v['agent_agcy'] == $agency_idx){
            $AGENTBTNS .= '
              <button class="btn btn-default btn-sm estAgntUserBtn" style="background-image:url(\''.$agtimg.'\')"'.$dtaStr.'>
                <div class="estAgntUserDta">
                  <h4 class="userName">'.$tp->toHTML($v['user_name']).'</h4>
                  <h4 class="agentName">'.$tp->toHTML($v['agent_name']).'</h4>
                  <div class="logon"><span class="userName">'.$tp->toHTML($v['user_name']).'</span> '.$tp->toHTML($v['user_email']).'</div>
                  <div>
                    <span class="agency">'.$tp->toHTML($v['agency_name']).'</span>
                  </div>
                </div>
              </button>';
            }
          else{
            $USERBTNS .= '
              <button class="btn btn-default btn-sm estAgntUserBtn" style="background-image:url(\''.$agtimg.'\')"'.$dtaStr.'>
                <div class="estAgntUserDta">
                  <h4 class="userName">'.$tp->toHTML($v['user_name']).'</h4>
                  <h4 class="agentName">'.$tp->toHTML($v['agent_name']).'</h4>
                  <div class="logon"><span class="userName">'.$tp->toHTML($v['user_name']).'</span> '.$tp->toHTML($v['user_email']).'</div>
                  <div>
                    <span class="agency">'.$tp->toHTML($v['agency_name']).'</span>
                  </div>
                </div>
              </button>';
            }
          }
        else{
          $USERBTNS .= '
              <button class="btn btn-default btn-sm estAgntUserBtn" style="background-image:url(\''.$agtimg.'\')"'.$dtaStr.'>
                <div class="estAgntUserDta">
                  <h4 class="userName">'.$tp->toHTML($v['user_name']).'</h4>
                  <h4 class="agentName"></h4>
                  <div class="logon"><span class="userName">'.$tp->toHTML($v['user_name']).'</span> '.$tp->toHTML($v['user_email']).'</div>
                  <div>
                    <span class="agency"></span>
                  </div>
                </div>
              </button>';
          }
        unset($dtaStr);
        }
      }
    
    $text .= '
    <table id="estUserAgentTab" class="table">
      <colgroup style="width:50%"></colgroup>
      <colgroup style="width:50%"></colgroup>
      <thead>
        <tr>
          <th class="noPADTB VAM"><div class="WD100 VAM">'.EST_GEN_USERSAVAIL.'</div></th>
          <th>'.EST_GEN_USERSASSIGNED.'</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="TAC noPAD">
            <div class="estAgntUserTarg userAvail"'.$cDtaStr.'>'.$USERBTNS.'</div>
          </td>
          <td class="TAC noPAD">
            <div class="estAgntUserTarg userAssigned"'.$cDtaStr.'>'.$AGENTBTNS.'</div>
          <td>
        </tr>
      </tbody>
    </table>';
    unset($cDtaStr);
    return $text;
    }
  
  
  
  
  private function estParseAgencyData($dta){
    $tp = e107::getParser();
    if(!$dta || !is_array($dta)){
      $dta = array();
      $sql = e107::getDB();
      $dta1 = $sql->db_FieldList('estate_agencies');
      foreach($dta1 as $k=>$v){$dta[$v] = '';}
      unset($dta1);
      }
    
    if(intval($dta['agency_idx']) === 0){
      $EST_PREF = e107::pref('estate');
      $dta['agency_idx'] = intval(0);
      $dta['agency_pub'] = 1;
      $dta['agency_image'] = '';
      $dta['agency_imgsrc'] = intval(0);
      $dta['agency_addr'] = $EST_PREF['pref_addr_lookup'];
      $dta['agency_lat'] = $EST_PREF['pref_lat'];
      $dta['agency_lon'] = $EST_PREF['pref_lon'];
      $dta['agency_geoarea'] = $EST_PREF['pref_zoom'];
      $dta['agency_zoom'] = $EST_PREF['pref_zoom'];
      }
    
    return $dta;
    }
  
  
  public function estAgencyForm($dta,$tab=-1){
    $pref = e107::pref();
    $tp = e107::getParser();
    $frm = e107::getForm(false, true);
    $timeZones = systemTimeZones();
    
    $dta = $this->estParseAgencyData($dta);
    extract($dta);
        
    if(trim($agency_timezone) == ''){$agency_timezone = vartrue($pref['timezone'], 'UTC');}
    if(intval($agency_idx) == 0){$agency_pub = 1;}
    
    $agency_altimg = $tp->thumbUrl($pref['sitelogo'],false,false,true);
    $LOGOIMG = $agency_altimg;
    if(intval($agency_imgsrc) == 1){
      if(trim($agency_image) !== ''){$LOGOIMG = EST_PTHABS_AGENCY.$tp->toHTML($agency_image);}
      else{$agency_imgsrc = 0;}
      }
    
    //if(intval($agency_imgsrc) == 1 && trim($agency_image) !== ''){$image = EST_PTHABS_AGENCY.$tp->toHTML($agency_image);}
    //else{$image = $tp->thumbUrl($pref['sitelogo'],false,false,true);}
    //estContactUDB
    
    $text .= '
      '.$frm->hidden('agency_idx',intval($agency_idx)).'
      '.$frm->hidden('agency_image',$tp->toFORM($agency_image)).'
      '.$frm->hidden('agency_imgsrc',intval($agency_imgsrc)).'
      '.$frm->hidden('agency_altimg',$agency_altimg).'
        <table id="estCompany1" class="table estFormSubTable">
        <colgroup style="width:20%"></colgroup>
        <colgroup style="width:60%"></colgroup>
        <colgroup style="width:20%"></colgroup>';
    if($tab == -1){
      $text .= '
          <thead>
            <tr>
              <th colspan="2">'.(intval($agency_idx) == 0 ? EST_GEN_NEW.' ' : '').EST_GEN_AGENCY.' '.EST_GEN_PROFILE.'</th>
              <th class="TAC">'.EST_GEN_AGENCY.' '.EST_GEN_LOGO.'</th>
            </tr>
          </thead>';
      }
          
    $text .= '
        <tbody>
          <tr>
            <td>'.EST_GEN_AGENCY.' '.EST_GEN_NAME.'*</td>
            <td>
            '.$frm->text('agency_name', $tp->toFORM($agency_name), 100, array('size' => 'xxlarge','placeholder'=>EST_GEN_AGENCY.' '.EST_GEN_NAME)).'
            '.$frm->hidden('agency_altimg',$agency_altimg).'
            </td>
            <td rowspan="4" class="TAC">
              <div id="estAgencyImg" class="estAgentAvatar" style="background-image:url(\''.$LOGOIMG.'?'.rand(99,99999).'\')">
                <img class="estSecretImg" src="'.$LOGOIMG.'?'.rand(99,99999).'" alt="" style="display:none;" />
                <input type="file" id="estAvatarUpload" name="estAvatarUpload" data-targ="agency_image" accept="image/jpeg, image/png, image/gif" class="ui-state-valid estInitFile" style="display:none;">
              </div>
            </td>
          </tr>
          
          <tr>
            <td>'.EST_GEN_PROFILEVISIB.'</td>
            <td>'.$frm->flipswitch('agency_pub', intval($agency_pub),array('off'=>EST_GEN_HIDDEN,'on'=>EST_GEN_PUBLIC)).'</td>
          </tr>
          
          <tr>
            <td>'.EST_GEN_TIMEZONE.'</td>
            <td>'.$frm->select('agency_timezone', $timeZones,$agency_timezone,'size=xlarge').'</td>
          </tr>
          <tr>
            <td>'.EST_GEN_ADDRESS.'</td>
            <td>'.$frm->textarea('agency_addr',$tp->toFORM($agency_addr),3,80,array('size' => 'xxlarge','placeholder'=>EST_PLCH96),true).'</td>
          </tr>
          <tr>
            <td>'.EST_GEN_INFO.'</td>
            <td colspan="2">'.$frm->textarea('agency_txt1',$tp->toFORM($agency_txt1),3,80,array('size' => 'xxlarge','placeholder'=>EST_PLCH85),true).'</td>
          </tr>';
          
          if($tab == -1){
            
            $text .='
        <tr>
          <td>'.EST_GEN_CONTACTS.'</td>
          <td class="posREL">
            <div id="estAgentPHPform2" class="">';
        $text .= $this->estContactForm(5,$agency_idx,2);
        $text .='
            </div>
          </td>
          <td>&nbsp;</td>
        </tr>';
            
            
            }
          $text .= $this->estMap('agency',$agency_addr,$agency_lat,$agency_lon,$agency_geoarea,$agency_zoom);
          $text .= '
        </tbody>
      </table>';
      
    return $text;
    }
  
  
  
  
  private function estAgentUIDSelect($agent_uid){
    $agent_uid = intval($agent_uid);
    $tp = e107::getParser();
    $frm = e107::getForm(false, true);
    $TRS = array();
    $TRS[0] = EST_GEN_AGENT.' '.EST_GEN_USERLOGIN;
    $TRS[1] = $frm->select_open('agent_uid',array('value'=>intval($agent_uid),'size'=>'xlarge estSelectDta'));
    
    //$sql = e107::getDB();
    //if($sql->select('estate_agents', '*', '')){while($row = $sql->fetch()){$AGNT[$row['agent_uid']] = $row;}}
    
    $AGNT = $this->getAllAgents();
    $users = $this->estGetUsers(1);
    
    foreach($users as $k=>$v){
      foreach($v as $dk=>$dv){$usrDtaStr .= ' data-'.$dk.'="'.$tp->toJS($dv).'"';}
      
      if(intval($v['user_id']) === $agent_uid){$selected = ' selected="selected"';}
      else{
        if(intval($v['user_id']) === 1 && $agent_uid !== 1){$disabled = ' disabled="disabled"';}
        if($AGNT[$v['user_id']] && $v['user_id'] !== $agent_uid ){
          $disabled = ' disabled="disabled"';
          foreach($AGNT[$v['user_id']] as $dk=>$dv){$usrDtaStr .= ' data-'.$dk.'="'.$tp->toJS($dv).'"';}
          }
        }
      
      $TRS[1] .= '<option value="'.intval($v['user_id']).'"'.$selected.$disabled.$usrDtaStr.'>'.$tp->toHTML(trim($v['user_login']) !== '' ? $v['user_login'] : $v['user_name']).' ('.$tp->toHTML($v['user_email']).')</option>';
      
      unset($usrDtaStr,$selected,$disabled);
      }
    $TRS[1] .= $frm->select_close();
    return $TRS;
    }
  
  private function estAgentCompSelect($company_idx){}
  
  public function estDataStr($dta){
    $tp = e107::getParser();
    $dtaStr = '';
    foreach($dta as $k=>$v){$dtaStr .= ' data-'.$k.'="'.$v.'"';}
    unset($dta,$k,$v);
    return $dtaStr;
    }
  
  private function estUserAgentData($dta){
    $tp = e107::getParser();
    $sql = e107::getDB();
    if(!$dta){$dta = array();}
    
    if($GLOBALS['PDTA']){
      foreach($GLOBALS['PDTA'] as $k=>$v){
        $dta[$k] = $v;
        }
      }
    
    if(!$dta['user_id']){
      $dta1 = $sql->db_FieldList('user');
      foreach($dta1 as $k=>$v){$dta[$v]='';}
      unset($dta1);
      }
    if(!$dta['agent_idx']){
      $dta1 = $sql->db_FieldList('estate_agents');
      foreach($dta1 as $k=>$v){$dta[$v]='';}
      unset($dta1);
      }
    
    if(intval($dta['user_id']) === 0){
      $dta['user_admin'] = 1;
      $dta['user_class'] = ESTATE_AGENT;
      $dta['user_perms'] = EST_PLUGID;
      $dta['user_visits'] = 0;
      }
    
    if(intval($dta['agent_idx']) === 0){
      $dta['agent_idx'] = intval(0);
      $dta['agent_name'] = $dta['user_name'];
      $dta['agent_agcy'] = (intval($dta['agent_agcy']) > 0 ? intval($dta['agent_agcy']) : intval(EST_AGENCYID));
      $dta['agent_lev'] = intval(1);
      $dta['agent_uid'] = intval($dta['user_id']);
      $dta['agent_img'] = '';
      $dta['agent_imgsrc'] = intval(0);
      $dta['agent_txt1'] = $dta['user_signature'];
      }
    //estDBUp
    
    $dta['user_profimg'] = $tp->toAvatar($dta,array('type'=>'url'));
    $dta['agent_altimg'] = '../..'.$dta['user_profimg'];
    $dta['user_image'] = $dta['user_profimg'];
    if($dta['agent_imgsrc'] == 1 && trim($dta['agent_image'] !== '')){
      $dta['agent_profimg'] = EST_PTHABS_AGENT.$tp->toHTML($dta['agent_image']);
      }
    else{$dta['agent_profimg'] = $dta['agent_altimg'];}
    return $dta;
    }
  
  
  
  
  
  
  
  
  
  public function estUserList($id=0){
    if(EST_USERPERM < 2){return;}
    $tp = e107::getParser();
    $sql = e107::getDB();
    
    if(isset($GLOBALS['EST_CLASSES']) && count($GLOBALS['EST_CLASSES']) > 0){
      //ADMINPERMS
      
      $TBLS = array(
        0=>array('capt'=>EST_GEN_YOURSELF,'body'=>''),
        1=>array('capt'=>EST_GEN_ADMINUSERS,'body'=>''),
        2=>array('capt'=>EST_GEN_NONADMINUSERS,'body'=>'')
        );
      
      if(intval(USERID) !== 1){$WHERE = "WHERE NOT user_id=1";}
      if(intval($id) > 0){
        $WHERE = ($WHERE ? $WHERE.' AND agent_agcy="'.intval($id).'"' : 'WHERE agent_agcy="'.intval($id).'"').' OR agent_agcy IS NULL';
        }
      
      $sql->gen("SELECT prop_agent, COUNT('prop_idx') AS agent_propct FROM #estate_properties WHERE prop_agent > 0 GROUP BY prop_agent");
      while($lai = $sql->fetch()){$AGL[$lai['prop_agent']] = $lai['agent_propct'];}
      unset($lai);
      
      $URES = $sql->gen("SELECT user_id,user_name,user_loginname,user_email,user_class,user_admin,user_perms,user_image, #estate_agents.*, #estate_agencies.* FROM #user LEFT OUTER JOIN #estate_agents ON agent_uid = user_id LEFT JOIN #estate_agencies ON agency_idx=agent_agcy $WHERE");
      
      $dberr = $sql->getLastErrorText();
      if($dberr){e107::getMessage()->addError($dberr);}
      if($URES){
        while($uv = $sql->fetch()){
          $UID = intval($uv['user_id']);
          $AGTID = intval($uv['agent_idx']);
          $XRP = explode('.',$uv['user_perms']);
          $XRC = explode(',',$uv['user_class']);
          $XGO = 0; // if > 0 then omit results
          $YGO = 0;
          if(intval($id) > 0 && intval($uv['agent_agcy']) == 0){
            $uv['agent_agcy'] = intval($id);
            }
          
          if(intval(USERID) !== 1 && in_array('0',$XRP) && USERID !== $UID){$XGO++;}
          
          if($AGTID > 0){
            if(EST_USERPERM == 2){
              if(intval($uv['agent_agcy']) > 0 && intval($uv['agent_agcy']) !== intval(EST_AGENCYID)){$XGO++;}
              }
            }
          
          if(EST_USERPERM == 3){
            if(in_array(ESTATE_ADMIN,$XRC) && USERID !== $UID){$XGO++;}
            }
          
          if(EST_USERPERM == 2){
            if(in_array(ESTATE_ADMIN,$XRC) || in_array(ESTATE_MANAGER,$XRC) && USERID !== $UID){$XGO++;}
            if(intval($uv['user_admin']) > 0){
              foreach($XRC as $tk=>$tv){
                if(!in_array($tv,EST_USERMANAGE)){$XGO++;}
                }
              }
            }
            
          
          $uv['agent_propct'] = intval($AGL[$AGTID]);
          
          $dtaStr = $this->estDataStr($uv);
          
          if($UID === intval(USERID)){$TKY = 0; $YGO = 1;}
          elseif($XGO == 0){
            $YGO = 1;
            if(intval($uv['user_admin']) > 0){$TKY = 1;}
            else{$TKY = 2;}
            }
          
          if($YGO == 1){
            $TBLS[$TKY]['body'] .= '
          <tr id="estUserTR-'.$UID.'" class="estUserData estUserTbl'.$TKY.'" '.$dtaStr.'>
            <td class="TAR">'.$tp->toAvatar($uv).'</td>
            <td>
              <div class="FWB">'.$tp->toHTML($uv['user_name']).'</div>
              <div class="smalltxt WSNWRP">'.$tp->toHTML($uv['user_loginname']).' ('.$tp->toHTML($uv['user_email']).')</div>
              <div class="smalltxt WSNWRP estUsrAdmStat"></div>
            </td>
            <td>
              <div class="WSNWRP"><input type="checkbox" id="estMainAdminCB-'.$UID.'" class="estMainAdminCB" value="'.EST_PLUGID.'" /> <label for="estMainAdminCB-'.$UID.'" title="'.EST_TT_ADMPERMIS1.'" >'.EST_GEN_ADMINAREA.' '.EST_GEN_ACCESS.'</label></div>
              <div class="WSNWRP"><input type="checkbox" id="estOwnerPostCB-'.$UID.'" class="estOwnerPostCB" value="'.EST_PLUGID.'" disabled="disabled" /> <label for="estOwnerPostCB-'.$UID.'" title="'.EST_TT_FRONTENDFORM.'" > '.EST_GEN_FRONTENDACCESS.'</label></div>
            </td>
            <td><select id="estUCls-'.$UID.'" class="estAllUserClass"></select></td>
            <td>
              <button class="btn btn-default btn-sm estUserAgentEdit">';
            
            if($AGTID > 0){
              $TBLS[$TKY]['body'] .= '<i class="S32 profImg" style="background-image:url('.($uv['agent_imgsrc'] == 1 ? EST_PTHABS_AGENT.$tp->toHTML($uv['agent_image']) : $tp->toAvatar($uv,array('type'=>'url'))).')"></i>';
              }
            else{
              $TBLS[$TKY]['body'] .= '<i class="S32 e-edit-32"></i>';
              }
            $TBLS[$TKY]['body'] .= '<div><span></span><span></span><span></span></div></button>
            </td>
          </tr>';
            }
          
          unset($UID,$dtaStr,$XRP,$XRC,$XGO,$YGO,$TKY);
          }
        }
      
      
      foreach($TBLS as $TK=>$TBL){
        if($TBL['body']){
          $text .= '
          <table id="estMainUserList'.$TK.'" class="table adminlist table-striped">
            <colgroup style="width:5%"></colgroup>
            <colgroup style="width:30%"></colgroup>
            <colgroup style="width:25%"></colgroup>
            <colgroup style="width:20%"></colgroup>
            <colgroup style="width:20%"></colgroup>
            '.$CG.'
            <thead>
              <tr>
                <th colspan="2">'.$TBL['capt'].'</th>
                <th>'.EST_GEN_PERMISSIONS.'</th>
                <th>'.EST_GEN_ADMINACCLEVEL.'</th>
                <th>'.EST_GEN_AGENT.' & '.EST_GEN_AGENCY.'</th>
              </tr>
            </thead>
            <tbody class="estUserTBody">
            '.$TBL['body'].'
            </tbody>
          </table>';
          }
        }
      unset($TBLS,$TK,$TBL);
      }
    return '<div id="estAgentUserTableDiv">'.$text.'</div>';
    }
  
  
  
  private function estPropListFilterLI($mode,$DTA){
    $tp = e107::getParser();
    if(!$DTA || (is_array($DTA) && count($DTA) == 0)){return;}
    $KEYS = array(
      //0=>array('USERS',EST_GEN_LISTAGENT.' '.EST_GEN_MEMBER,'prop_agent','agents','seller_name','seller_aid'),
      0=>array('AGENTFLTR',EST_GEN_LISTAGENT,'prop_agent','agents','seller_name','seller_aid'),
      1=>array('AGENTFLTR',EST_GEN_LISTAGENT,'prop_agent','agents','seller_name','seller_aid'),
      2=>array('USERS',EST_GEN_LISTING.' '.EST_GEN_MEMBER,'prop_uidcreate',null,'seller_name','seller_aid'),
      3=>array('EST_PROPSTATUS',EST_GEN_STATUS,'prop_status',null,'opt',null),
      4=>array('ZONES',EST_GEN_CATEGORY,'prop_type','types',null,null),
      );
    
    //estPropertyListTable
    
    if(isset($DTA[$KEYS[$mode][0]]) && is_array($DTA[$KEYS[$mode][0]]) && count($DTA[$KEYS[$mode][0]]) > 0){
      $text = '
      <div class="col-selection dropdown e-tip pull-right float-right estFltrDiv WD100" data-placement="left">
        <a class="dropdown-toggle" title="'.EST_GEN_FILTERBY.' '.$KEYS[$mode][1].'" data-toggle="dropdown" data-bs-toggle="dropdown" data-before="0" href="#">'.$KEYS[$mode][1].' <b class="caret"></b></a>
        <ul class="list-group dropdown-menu col-selection estPropListFltrSet e-noclick" data-fld="'.$KEYS[$mode][2].'" role="menu" aria-labelledby="dLabel">';
      
      if($KEYS[$mode][3] !== null){
        foreach($DTA[$KEYS[$mode][0]] as $k=>$v){
          if(isset($v[$KEYS[$mode][3]]) && is_array($v[$KEYS[$mode][3]]) && count($v[$KEYS[$mode][3]]) > 0){
            $agtCt = (is_array($v['agents']) ? count($v['agents']) : 0);
            $text .= '
          <li class="list-group-item col-selection-list">
            <ul class="nav scroll-menu estPropListFltrUL">
              <li class="estFltrItm estFltrHead" role="menuitem">
                <a href="#" title="">
                <label class="checkbox form-check" data-value="'.$k.'">
                  <input type="checkbox" name="set-'.$KEYS[$mode][2].'-'.$mode.'['.$k.']" value="'.$k.'" class="form-check-input">
                  '.($mode < 2 ? '<span class="estPropListCt">'.intval($agtCt).'</span>' : '').'
                  <span>'.$tp->toHTML($v['name']).'</span>
                </label>
                </a>
              </li>';
            foreach($v[$KEYS[$mode][3]] as $sk=>$sv){
              $elval = '';
              if(isset($sv['seller_aid'])){$elval = $sv['seller_aid'];}
                $text .= '
              <li class="estFltrItm estFltrInd" role="menuitem">
                <a href="#" title="">
                  <label class="checkbox form-check" data-agency="'.$k.'" data-value="'.($KEYS[$mode][5] == null ? $sk : $sv[$KEYS[$mode][5]]).'">
                    <input type="checkbox" name="set-'.$KEYS[$mode][2].'-'.$mode.'['.$elval.']" value="'.$elval.'" class="form-check-input">
                    '.($mode < 2 ? '<span class="estPropListCt">'.intval($DTA['AGENTS'][$elval]['seller_count']).'</span>' : '').'
                    <span>'.$tp->toHTML($KEYS[$mode][4] == null ? $sv : $sv[$KEYS[$mode][4]]).'</span>
                  </label>
                </a>
              </li>';
              }
            $text .= '
            </ul>
          </li>';
            }
          }
        }
      else{
        $text .= '
          <li class="list-group-item col-selection-list">
            <ul class="nav scroll-menu estPropListFltrUL">';
        foreach($DTA[$KEYS[$mode][0]] as $k=>$v){
          $text .= '
              <li class="estFltrItm" role="menuitem">
                <a href="#" title="">
                  <label class="checkbox form-check" data-value="'.($KEYS[$mode][5] == null ? $k : $v[$KEYS[$mode][5]]).'">
                    <input type="checkbox" name="set-'.$KEYS[$mode][2].'-'.$mode.'['.$sv['seller_aid'].']" value="'.$v['seller_aid'].'" class="form-check-input">
                    '.($mode < 2 ? '<span class="estPropListCt">'.intval($DTA['AGENTS'][$v['seller_aid']]['seller_count']).'</span>' : '').'
                    <span>'.$tp->toHTML($KEYS[$mode][4] == null ? $v : $v[$KEYS[$mode][4]]).'</span>
                  </label>
                </a>
              </li>';
          }
            $text .= '
            </ul>
          </li>';
        }
    
      
    $text .= '
          <li class="list-group-item">
    			  <div id="column_options-button" class="right">
              <button data-loading-icon="fa-spinner" type="button" id="propFltrClrBtn-'.$mode.'-'.$KEYS[$mode][2].'" class="btn btn-primary btn-small propFltrClr" value="propFltrClr-'.$KEYS[$mode][2].'"><span>'.EST_GEN_CLEARFILTER.'</span></button>
              <button data-loading-icon="fa-spinner" type="button" id="propFltrSetBtn-'.$mode.'-'.$KEYS[$mode][2].'" class="btn btn-primary btn-small propFltrSet" value="propFltr-'.$KEYS[$mode][2].'"><span>'.EST_GEN_APPLYFILTER.'</span></button>
    			  </div>
          </li>
        </ul>
      </div>';
      
      }
    else{
      $text = $KEYS[$mode][1];
      }
    
    return $text;
    }
  
  
  
  
  
  
  private function estPropertyListTableSF($mode,$DTA){
    $tp = e107::getParser();
    $CSPN = 7;
    $DTA['colsp'] = 7;
    $DTA['EST_PROPSTATUS'] = $GLOBALS['EST_PROPSTATUS'];
    
    $dtatrct = (is_array($DTA['TR']) ? $DTA['TR'] : 0);
    // prop_uidcreate prop_uidupdate prop_agent
    
    
    $text = '
        <table id="plugin-estate-list-table-'.$mode.'" class="table adminlist table-striped estPropListTABx estCustomTable1" data-colspan="'.$DTA['colsp'].'" data-mode="'.$mode.'" data-order="'.$DTA['FLTR']['ORDER'][0].' '.$DTA['FLTR']['ORDER'][1].'" data-limit="['.$DTA['FLTR']['LIMIT'][0].','.$DTA['FLTR']['LIMIT'][1].']">
          <colgroup>
    				<col class="left" style="width:110px">
    				<col class="left" style="width:auto">
    				<col class="left" style="width:auto">
    				<col class="left" style="width:auto">
    				<col class="left" style="width:auto">
    				<col class="right" style="width:auto">
    				<col class="center last" style="width:auto">
    			</colgroup>
          <thead id="estPropListTH-'.$mode.'" class="estPropListTH">
            <tr class="even first">
              <th id="e-column-prop-thmb-'.$mode.'" class="left">'.EST_GEN_THUMBNAIL.'</th>
              <th id="e-column-prop-name-'.$mode.'" class="left">
                '.EST_GEN_NAME.' & '.EST_GEN_ADDRESS.'
              </th>
              <th id="e-column-prop-agent-'.$mode.'" class="left">';
              $text .= $this->estPropListFilterLI($mode,$DTA);
              $text .= '
              </th>
              <th id="e-column-prop-status-'.$mode.'" class="left">';
              $text .= $this->estPropListFilterLI(3,$DTA);
              $text .= '
              </th>
              
              <th id="e-column-prop-zoning-'.$mode.'" class="left">';
              $text .= $this->estPropListFilterLI(4,$DTA);
              $text .= '
              </th>
              <th id="e-column-prop-listprice-'.$mode.'" class="right">
                '.EST_PROP_LISTPRICE.'
              </th>
              <th id="e-column-options-'.$mode.'" class="center last VAM noPAD">
                <div class="btn-group estPropListDBLimit" data-count="'.intval($dtatrct).'" data-from="'.$DTA['FLTR']['LIMIT'][0].'" data-limit="'.$DTA['FLTR']['LIMIT'][1].'">
                  <button type="button" id="estPropDBPrev-'.$mode.'" class="btn btn-default estPropDBPrev estNoRBord" title="'.LAN_PREVIOUS.'"><i class="fa-solid fa-chevron-left"></i></button>
                  <button type="button" id="estPropDBLimit-'.$mode.'" class="btn btn-default estNoLRBord" style="min-width:50px" title="'.EST_GEN_NUMBERRESULTS.'">'.$DTA['FLTR']['LIMIT'][1].'</button>
                  <button type="button" id="estPropDBNext-'.$mode.'" class="btn btn-default estPropDBNext estNoLBord" title="'.LAN_NEXT.'"><i class="fa-solid fa-chevron-right"></i></button>
                  
                  
                  
                </div>
              </th>
            </tr>
          </thead>
          <tbody id="estPropListTB-'.$mode.'" class="estPropListTB">';
          
          //<i class="fa-solid fa-chevron-left"></i>
          //<i class="fa-solid fa-angles-left"></i>
          //<i class="fa-solid fa-chevron-right"></i>
          //<i class="fa-solid fa-angles-right"></i>
          //<i class="fa-solid fa-9"></i>
          
          
          $text .= $this->PropertyListTableTR($DTA);
          
          $text .= '
          </tbody>
          <tfoot id="estPropListTF-'.$mode.'">
          </tfoot>
        </table>';
    /*<span id="admin-ui-list-search" class="form-group has-feedback has-feedback-left FL"><input type="text" name="searchquery" value="" maxlength="50" id="searchquery" class="tbox input-text filter input-xlarge form-control ui-state-valid" size="20" data-original-title="" title=""></span>
                <div class="e-autocomplete"></div>
                <span class="indicator" style="display: none;"><i class="fa fa-spin fa-spinner fa-fw"></i></span>
    
    */
    return $text;
    }
  
  
  
  
  public function PropertyListTableTR($DTA){
    $EST_PREF = e107::pref('estate');
    $tp = e107::getParser();
    if(!is_array($DTA['TR'])){return;}
    
    if($DTA['ERR']){
      $text = '
      <tr>
        <td class="left" colspan="'.$DTA['colsp'].'">
          <div class="s-message alert alert-block warning alert-warning" style="display: block;">
          <h4>'.LAN_ERROR.':</h4>
          <p>'.$DTA['ERR'].'</p>
          </div>
          <div class="s-message alert alert-block warning alert-warning" style="display: block;">
          <h4>QUERY:</h4>
          <p>'.$DTA['QRY'].'</p>
          </div>
        </td>
      </tr>';
      }
    elseif(count($DTA['TR']) == 0){
      $text = '
      <tr>
        <td class="left" colspan="'.$DTA['colsp'].'">
          <div class="s-message alert alert-block warning alert-warning" style="display: block;">'.EST_GEN_NODBRESULTS.'</div>
        </td>
      </tr>';
      }
    else{
      foreach($DTA['TR'] as $k=>$v){
        $dtaStr = $this->estDataStr($v);
        
        $SELLER = '
        <div class="estPropListAgentCont" '.$dtaStr.'>
          <div>'.$tp->toHTML($v['seller_agency']).'</div>
          <div>
            <div style="background-image:url(\''.$v['seller_profimg'].'\')"></div>
            <p>';
        //seller_agency
        if(intval($v['prop_agent']) > 0){
          $SELLER .= '
                <a href="'.e_SELF.'?mode=estate_agencies&action=agent&id='.intval($v['seller_uid']).'.'.intval($v['prop_agent']).'" title="'.EST_GEN_EDIT.' '.EST_GEN_AGENT.'">'.$tp->toHTML($v['seller_name']).'</a>';
          }
        else{
          $SELLER .= $tp->toHTML($v['seller_name']);
          }
        
        $SELLER .= '
              <i>'.$tp->toHTML($v['seller_email']).'</i>
              <i>'.$tp->toHTML($v['seller_role']).'</i>
            </p>
          </div>
        </div>';
        
        foreach($GLOBALS['EST_PROPSTATUS'] as $sk=>$sv){
          $STATOPTS .= ($STATOPTS ? "," : "").$sv['opt'];
          }
        
        $text .= '
        <tr id="row-'.intval($k).'" '.$dtaStr.'>
          <td class="left noPAD">
            <div class="estPropThumb" title="'.EST_PROP_RESETHM.'"></div>
          </td>
          <td class="left">
              <div class="FWB">'.$tp->toHTML($v['prop_name']).'</div>
            '.$tp->toHTML((trim(strtolower($v['prop_name'])) !== trim(strtolower($v['prop_addr1'])) ? $v['prop_addr1'] : '').($v['prop_addr2'] !== '' ? '<div>'.$v['prop_addr2'].'</div>' : '').'<div>'.$v['county'].'</div><div>'.$v['city'].', '.$v['ST'].' '.$v['prop_zip'].' '.strtoupper($v['nat']).'</div>').'
          </td>
          <td class="left noPAD">'.$SELLER.'</td>
          <td class="left">
            <div class="estPropListStat"><a class="estPropListILEdit" data-fld="prop_status" data-type="select" data-opts="'.$STATOPTS.'" data-key="i" data-pid="'.intval($v['prop_idx']).'" data-cval="'.$v['prop_status'].'">'.$GLOBALS['EST_PROPSTATUS'][$v['prop_status']]['opt'].'</a></div>
            <div class="estPropListStat"><a class="estPropListILEdit" data-fld="prop_appr" data-type="select" data-opts="'.EST_GEN_NOT.' '.EST_GEN_APPROVED.','.EST_GEN_APPROVED.'" data-key="i" data-pid="'.intval($v['prop_idx']).'" data-cval="'.intval($v['prop_appr']).'">'.(intval($v['prop_appr']) > 0 ? EST_GEN_APPROVED : EST_GEN_NOT.' '.EST_GEN_APPROVED).'</a></div>
          </td>
          <td class="left">
            <div title="'.EST_PROP_LISTZONE.'">'.$v['prop_zname'].'</div>
            <div title="'.EST_PROP_LISTYPE.'">'.$v['prop_ztype'].'</div>
          </td>
          <td class="right">
            <div title="'.EST_PROP_LISTYPE.'">'.$GLOBALS['EST_LISTTYPE1'][$v['prop_listype']].'</div>
            <div title="'.EST_PROP_ORIGPRICE.'"><i>'.$GLOBALS['EST_CURSYMB'][$v['prop_currency']].' '.$v['prop_origprice'].'</i></div>
            <div class="estPropListStat" title="'.EST_PROP_LISTPRICE.'">'.$GLOBALS['EST_CURSYMB'][$v['prop_currency']].' <a class="estPropListILEdit" data-fld="prop_listprice" data-type="number" data-pid="'.intval($v['prop_idx']).'" data-cval="'.$v['prop_listprice'].'">'.$v['prop_listprice'].'</a></div>
          </td>
          <td class="center last">
            <div class="btn-group WSNWRP">
              <a href="'.e_SELF.'?mode=estate_properties&amp;action=edit&amp;id='.intval($v['prop_idx']).'" class="btn btn-default btn-secondary" data-modal-caption="" title="'.EST_GEN_EDIT.'" data-placement="left"><i class="S32 e-edit-32"></i></a>
              <button type="submit" name="etrigger_delete['.intval($v['prop_idx']).']" data-placement="left" value="'.intval($v['prop_idx']).'" id="etrigger-delete-'.intval($v['prop_idx']).'-'.intval($v['prop_idx']).'" class="action delete btn btn-default" title="Delete [ ID: '.intval($v['prop_idx']).' ]" data-confirm="Are you sure?"><i class="S32 e-delete-32"></i></button>
            </div>
        
          </td>
        </tr>';
        }
      }
    
    $RSTART = intval($DTA['FLTR']['LIMIT'][0]);
    $RLIMIT = intval($DTA['FLTR']['LIMIT'][1]);
    $RTRWCT = count($DTA['TR']);
    $COUNTED = intval($DTA['COUNTED']);
    
    $REND = $RTRWCT + $RSTART;
    $RSTART++;
    
    $text .= '
      <tr data-counted="'.$DTA['COUNTED'].'">
        <td colspan="'.$DTA['colsp'].'">'.($RTRWCT < $RLIMIT ? EST_GEN_ENDOF : $RSTART.' '.EST_GEN_TO.' '.$REND.' '.EST_GEN_OF).' '.$COUNTED.' Total Records
        <div>'.$DTA['REMOVED'].' Removed Results</div>
        </td>
      </tr>';
    return $text;
    }
  
  
  
  public function estUserAgents(){
    $EST_PREF = e107::pref('estate');
    $sql = e107::getDB();
    $tp = e107::getParser();
    $DTA = array();
    
    $DTA['USERS'] = array();
    
    $sql->gen("SELECT user_id,user_name,user_loginname,user_email,user_admin,user_perms,user_class,user_signature,user_image FROM #user ".(USERID !== 1 ? " WHERE NOT user_id='1'" : "")." ORDER BY user_name ASC");
    while($rows = $sql->fetch()){
      $uid = intval($rows['user_id']);
      
      $AGTPERMS = explode(".",$rows['user_perms']);
      $AGTCLSES = explode(",",$rows['user_class']);
      if(in_array('0',$AGTPERMS)){
        $DTA['USERS'][$uid]['seller_role'] = EST_GEN_MAIN." ".EST_GEN_WEBSITE." "." ".EST_GEN_ADMIN;
        $DTA['USERS'][$uid]['seller_mgr'] = 4;
        }
      elseif(in_array(ESTATE_ADMIN,$AGTCLSES)){
        $DTA['USERS'][$uid]['seller_role'] = EST_PLUGNAME." ".EST_GEN_ADMIN;
        $DTA['USERS'][$uid]['seller_mgr'] = 4;
        }
      elseif(in_array(ESTATE_MANAGER,$AGTCLSES)){
        $DTA['USERS'][$uid]['seller_role'] = EST_GEN_MANAGER;
        $DTA['USERS'][$uid]['seller_mgr'] = 3;
        }
      elseif(in_array(ESTATE_AGENT,$AGTCLSES)){
        $DTA['USERS'][$uid]['seller_role'] = EST_GEN_AGENT;
        $DTA['USERS'][$uid]['seller_mgr'] = 2;
        }
      else{
        $DTA['USERS'][$uid]['seller_role'] = EST_GEN_MEMBER;
        $DTA['USERS'][$uid]['seller_mgr'] = intval($EST_PREF['public_mod']);
        }
      
      $DTA['USERS'][$uid]['seller_uid'] = $uid;
      $DTA['USERS'][$uid]['seller_aid'] = intval(0);
      $DTA['USERS'][$uid]['seller_name'] = (trim($rows['user_name']) !== '' ? $rows['user_name'] : $rows['user_loginname']);
      $DTA['USERS'][$uid]['seller_profimg'] = $tp->toAvatar($rows,array('type'=>'url'));
      $DTA['USERS'][$uid]['seller_agency'] = EST_GEN_PRIVATE.' '.EST_GEN_SELLER;
      $DTA['USERS'][$uid]['seller_email'] = $rows['user_email'];  
      $DTA['USERS'][$uid]['seller_count'] = 0;
      
      if(USERID !== 1){
        if(intval($DTA['USERS'][$uid]['seller_mgr']) > EST_USERPERM && $uid !== intval(USERID)){
          unset($DTA['USERS'][$uid]);
          }
        }
      }
    
    
    $DTA['AGENTS'] = array();
    $DTA['AGENTFLTR'] = array();
    $DTA['AGTIDS'] = array();
    
    if(EST_USERPERM < 3){
      if(EST_USERPERM == 2){$AQRY = " agency_idx='".EST_AGENCYID."' ";}
      else{$AQRY = " agent_uid='".USERID."' ";}
      }
    
    if(USERID !== 1){$AQRY .= ($AQRY ? " AND " : "")." NOT agent_uid='1'";}
    
    $sql->gen("SELECT #estate_agents.*, agency_idx, agency_name FROM #estate_agents LEFT JOIN #estate_agencies ON agency_idx=agent_agcy ".($AQRY ? " WHERE ".$AQRY : "")." ORDER BY agency_name ASC, agent_name ASC");
    
    
    while($rows = $sql->fetch()){
      $aid = intval($rows['agent_idx']);
      $uid = intval($rows['agent_uid']);
      
      if($DTA['USERS'][$uid]){
        if(intval($DTA['USERS'][$uid]['seller_mgr']) <= EST_USERPERM || $uid == intval(USERID)){
          if(!in_array($aid,$DTA['AGTIDS'])){array_push($DTA['AGTIDS'],$aid);}
            
          $DTA['USERS'][$uid]['seller_aid'] = $aid;
          $DTA['AGENTS'][$aid]['seller_aid'] = $aid;
          $DTA['AGENTS'][$aid]['seller_uid'] = $DTA['USERS'][$uid]['seller_uid'];
          $DTA['AGENTS'][$aid]['seller_name'] = (trim($rows['agent_name']) !== '' ? $rows['agent_name'] : $DTA['USERS'][$uid]['seller_name']);
          
          if(intval($rows['agent_imgsrc']) == 1 && trim($rows['agent_image']) !== ""){
            $DTA['AGENTS'][$aid]['seller_profimg'] = EST_PTHABS_AGENT.$rows['agent_image'];
            }
          else{
            $DTA['AGENTS'][$aid]['seller_profimg'] = $DTA['USERS'][$uid]['seller_profimg'];
            }
          
          $DTA['AGENTS'][$aid]['seller_agency']['id'] = $rows['agency_idx'];
          $DTA['AGENTS'][$aid]['seller_agency']['name'] = $rows['agency_name'];
          $DTA['AGENTS'][$aid]['seller_role'] = $DTA['USERS'][$uid]['seller_role'];
          $DTA['AGENTS'][$aid]['seller_mgr'] = $DTA['USERS'][$uid]['seller_mgr'];
          $DTA['AGENTS'][$aid]['seller_email'] = $DTA['USERS'][$uid]['seller_email'];
          $DTA['AGENTS'][$aid]['seller_count'] = 0;
          
          $DTA['AGENTFLTR'][$rows['agency_idx']]['count'] = 0;
          $DTA['AGENTFLTR'][$rows['agency_idx']]['name'] = $rows['agency_name'];
          $DTA['AGENTFLTR'][$rows['agency_idx']]['agents'][$aid] = $DTA['AGENTS'][$aid];
          
            
          }
        else{
          unset($DTA['USERS'][$uid]);
          }
        }
      }
    
    unset($rows,$uid,$aid);
    
    foreach($DTA['USERS'] as $uid=>$v){
      $aid = intval($v['seller_aid']);
      if($aid > 0){
        $ax = intval($DTA['AGENTS'][$aid]['seller_agency']['id']);
        $prop_count = $sql->count('estate_properties', '(prop_idx)', 'WHERE prop_agent = "'.$aid.'"');
        $DTA['AGENTS'][$aid]['seller_count'] = $prop_count;
        $DTA['AGENTFLTR'][$ax]['count'] = $DTA['AGENTFLTR'][$ax]['count'] + $prop_count;
        unset($DTA['USERS'][$uid]);
        }
      else{
        $prop_count = $sql->count('estate_properties', '(prop_idx)', 'WHERE prop_uidcreate = "'.$uid.'"');
        if($prop_count > 0){$DTA['USERS'][$uid]['seller_count'] = $prop_count;}
        else{unset($DTA['USERS'][$uid]);}
        }
      }
    
    
    return $DTA;
    }
  
  
  
  
  
  
  
  
  public function estPropertyListQry($MODE=0,$FLTR=array()){
    $EST_PREF = e107::pref('estate');
    $sql = e107::getDB();
    $tp = e107::getParser();
    
    $DTA = $this->estUserAgents();
    //estGetAllAgents
    
    
    $DTA['TR'] = array();
    
    
    $sql->gen("SELECT #estate_zoning.*, #estate_listypes.* FROM #estate_zoning LEFT JOIN #estate_listypes ON listype_zone = zoning_idx");
    while($rows = $sql->fetch()){
      $zid = intval($rows['zoning_idx']);
      $lid = intval($rows['listype_idx']);
      $DTA['ZONES'][$zid]['name'] = $rows['zoning_name'];
      if($lid > 0){$DTA['ZONES'][$zid]['types'][$lid] = $rows['listype_name'];}
      }
    unset($rows,$zid,$lid);
    
    $FLDS = array("prop_idx","prop_name","prop_agency","prop_agent","prop_addr1","prop_addr2","prop_country","prop_state","prop_county","prop_city","prop_zip","prop_subdiv","prop_datecreated","prop_dateupdated","prop_uidcreate","prop_status","prop_listype","prop_zoning","prop_type","prop_currency","prop_listprice","prop_origprice","prop_thmb","prop_appr","prop_views","prop_saves","city_name AS city","cnty_name AS county","state_name AS state","state_init AS ST","state_country AS nat");
    
    
    $QRY = "
    SELECT ".implode(", ",$FLDS)."
    FROM `#estate_properties` 
    LEFT JOIN `#estate_city`
    ON prop_city = city_idx
    LEFT JOIN `#estate_county`
    ON city_county = cnty_idx
    LEFT JOIN `#estate_states`
    ON state_idx = cnty_state";
    
    
    
    if(intval($EST_PREF['public_act']) === 255 || EST_USERPERM < intval($EST_PREF['public_mod'])){
      $MODE = 0;
      }
        
    
    
    if(isset($FLTR['WHERE']['prop_uidupdate'])){
      $FLTR['WHERE']['prop_agent'] = "prop_uidupdate='".intval($FLTR['WHERE']['prop_uidupdate'])."'";
      }
    
    elseif(isset($FLTR['WHERE']['prop_uidcreate'])){
      $FLTR['WHERE']['prop_agent'] = "prop_uidcreate='".intval($FLTR['WHERE']['prop_uidcreate'])."'";
      }
    elseif(isset($FLTR['WHERE']['prop_agent'])){
      if(is_array($FLTR['WHERE']['prop_agent'])){
        if(count($FLTR['WHERE']['prop_agent']) == 1){$FLTR['WHERE']['prop_agent'] = " prop_agent = ".$FLTR['WHERE']['prop_agent'][0]." ";}
        else{$FLTR['WHERE']['prop_agent'] = " prop_agent IN(".implode(",",$FLTR['WHERE']['prop_agent']).") ";}
        }
      else{$FLTR['WHERE']['prop_agent'] = " prop_agent = ".$FLTR['WHERE']['prop_agent']." ";}
      }
    elseif(isset($DTA['AGTIDS']) && count($DTA['AGTIDS']) > 0){
      if(count($DTA['AGTIDS']) == 1){$AGTIDS = " prop_agent ='".$DTA['AGTIDS'][0]."'";}
      else{$AGTIDS = " prop_agent IN(".implode(",",$DTA['AGTIDS']).")";}
      }
    
    // SPLIT TABLE DISPLAY
    if($MODE == 1){
      // TABLE #1, AGENT LISTINGS
      if(!$FLTR['WHERE']['prop_agent']){
        if(EST_USERPERM >= 3){
          $FLTR['WHERE']['prop_agent'] = " NOT prop_agent='0'";
          //if(intval(USERID) === 1){$FLTR['WHERE']['prop_agent'] = " NOT prop_agent='0'";}
          //else{$FLTR['WHERE']['prop_agent'] = ($AGTIDS ? $AGTIDS." AND " : "")." NOT prop_agent='0'";}
          }
        else{
          if(EST_USERPERM == 2){
            if($AGTIDS){$FLTR['WHERE']['prop_agent'] = ($AGTIDS ? $AGTIDS." AND " : "")." NOT prop_agent='0'";}
            else{$FLTR['WHERE']['prop_agent'] = " prop_agent='".EST_AGENTID."' OR (prop_agency = '".EST_AGENCYID."' AND NOT prop_agent='0')";}
            }
          else{$FLTR['WHERE']['prop_agent'] = " prop_agent='".EST_AGENTID."' ";}
          }
        }
      }
    else if($MODE == 2){
      // TABLE #2, USER LISTINGS
      if(EST_USERPERM >= 3 || EST_USERPERM >= intval($EST_PREF['public_mod'])){
        if(!$FLTR['WHERE']['prop_agent']){
          $FLTR['WHERE']['prop_agent'] = "prop_agent='0'";
          }
        }
      else{
        return $DTA;
        }
      }
    else{
      // SINGLE TABLE WITH EVERYTHING
      if(!$FLTR['WHERE']['prop_agent']){
        if(ADMINPERMS === '0'){
          if(intval(USERID) !== 1){$FLTR['WHERE']['prop_agent'] =  ($AGTIDS ? $AGTIDS." OR " : "")."  prop_agent='0'";} 
          }
        else{
          if(EST_USERPERM == 2){
            if($AGTIDS){$FLTR['WHERE']['prop_agent'] = $AGTIDS;}
            else{$FLTR['WHERE']['prop_agent'] = " (prop_agency = '".EST_AGENCYID."' OR prop_agent='".EST_AGENTID."')";}
            }
          else{$FLTR['WHERE']['prop_agent'] = " prop_agent='".EST_AGENTID."' ";}
          
          if(EST_USERPERM < intval($EST_PREF['public_mod'])){
            $FLTR['WHERE']['prop_agent'] .= ($FLTR['WHERE']['prop_agent'] ? " AND " : "")." NOT prop_agent='0'";
            }
          }
        }
      }
    
    
    
    if($FLTR['WHERE']){
      if($FLTR['WHERE']['prop_agent']){
        $QRYX .= ($QRYX ? " AND " : "")." ".$FLTR['WHERE']['prop_agent']." ";
        }
      
      if(is_array($FLTR['WHERE']['prop_status']) && $FLTR['WHERE']['prop_status']){
        if(count($FLTR['WHERE']['prop_status']) == 1){$QRYX .= ($QRYX ? " AND " : "")." prop_status=".$FLTR['WHERE']['prop_status'][0]." ";}
        else{$QRYX .= ($QRYX ? " AND " : "")." prop_status IN(".implode(",",$FLTR['WHERE']['prop_status']).") ";}
        
        }
        
      if(is_array($FLTR['WHERE']['prop_zoning']) && $FLTR['WHERE']['prop_zoning']){
        if(count($FLTR['WHERE']['prop_zoning']) == 1){$QRYX .= ($QRYX ? " AND " : "")." prop_zoning=".$FLTR['WHERE']['prop_zoning'][0]." ";}
        else{$QRYX .= ($QRYX ? " AND " : "")." prop_zoning IN(".implode(",",$FLTR['WHERE']['prop_zoning']).") ";}
        }
      
      if(is_array($FLTR['WHERE']['prop_type']) && $FLTR['WHERE']['prop_type']){
        if(count($FLTR['WHERE']['prop_type']) == 1){$QRYX .= ($QRYX ? " AND " : "")." prop_type=".$FLTR['WHERE']['prop_type'][0]." ";}
        else{$QRYX .= ($QRYX ? " AND " : "")." prop_type IN(".implode(",",$FLTR['WHERE']['prop_type']).") ";}
        }
      }
    
    if($QRYX){$QRYX = "WHERE ".$QRYX;}
    
    
    if(!$FLTR['ORDER'][0]){$FLTR['ORDER'][0] = "prop_datecreated";}
    if(!$FLTR['ORDER'][1]){$FLTR['ORDER'][1] = "DESC";}
    
    if(!$FLTR['LIMIT'][0]){$FLTR['LIMIT'][0] = intval(0);}
    if(!$FLTR['LIMIT'][1]){$FLTR['LIMIT'][1] = 25;}
    
    $DTA['FLTR'] = $FLTR;
    //." LIMIT ".implode(",",$FLTR['LIMIT'])
    $QRYZ = "SELECT COUNT('prop_idx') AS counted FROM `#estate_properties` ".$QRYX;
    
    
    $sql->gen($QRYZ);
    $rows = $sql->fetch();
    $DTA['COUNTED'] = $rows['counted'];
    $DTA['REMOVED'] = 0;
    $DTA['QRYZ'] = '['.$MODE.']'.$QRYZ;
    
    $dberr = $sql->getLastErrorText();
    if($dberr){$DTA['ERR'] = $dberr;}
    unset($dberr); 
    
    
    $QRY .= " ".$QRYX." ORDER BY ".implode(" ",$FLTR['ORDER'])." LIMIT ".implode(",",$FLTR['LIMIT']);
    $DTA['QRY'] = $QRY;
    
    $sql->gen($DTA['QRY']);
    
    $dberr = $sql->getLastErrorText();
    if($dberr){$DTA['ERR'] = $dberr;}
    unset($dberr); 
    
    while($rows = $sql->fetch()){
      $rows['prop_zname'] = $DTA['ZONES'][$rows['prop_zoning']]['name'];
      $rows['prop_ztype'] = $DTA['ZONES'][$rows['prop_zoning']]['types'][$rows['prop_type']];
      
      // intval($EST_PREF['public_mod']) 
      // intval($EST_PREF['public_apr'])
      // estOAFormTable
      
      
      
      if(intval($rows['prop_agent']) > 0 && $DTA['AGENTS'][$rows['prop_agent']]){
        $rows['seller_agency'] = $DTA['AGENTS'][$rows['prop_agent']]['seller_agency']['name'];
        $rows['seller_profimg'] = $DTA['AGENTS'][$rows['prop_agent']]['seller_profimg'];
        $rows['seller_name'] = $DTA['AGENTS'][$rows['prop_agent']]['seller_name'];
        $rows['seller_email'] = $DTA['AGENTS'][$rows['prop_agent']]['seller_email'];
        $rows['seller_role'] = $DTA['AGENTS'][$rows['prop_agent']]['seller_role'];
        $rows['seller_uid'] = $DTA['AGENTS'][$rows['prop_agent']]['seller_uid'];
        $DTA['TR'][$rows['prop_idx']] = $rows;
        }
      elseif($DTA['USERS'][$rows['prop_uidcreate']]){
        $rows['seller_agency'] = $DTA['USERS'][$rows['prop_uidcreate']]['seller_agency'];
        $rows['seller_profimg'] = $DTA['USERS'][$rows['prop_uidcreate']]['seller_profimg'];
        $rows['seller_name'] = $DTA['USERS'][$rows['prop_uidcreate']]['seller_name'];
        $rows['seller_email'] = $DTA['USERS'][$rows['prop_uidcreate']]['seller_email'];
        $rows['seller_role'] = $DTA['USERS'][$rows['prop_uidcreate']]['seller_role'];
        $rows['seller_uid'] = $rows['prop_uidcreate'];
        $DTA['TR'][$rows['prop_idx']] = $rows;
        }
      else{
        $DTA['COUNTED'] = $DTA['COUNTED'] - 1;
        $DTA['REMOVED'] ++;
        }
      }
    unset($QRY,$QRYX,$QRYZ);
    
    return $DTA;
    }
  
  
  
  
  
  
  public function estModList($curVal,$fldname='public_notify'){
    $EST_PREF = e107::pref('estate');
    $sql = e107::getDB();
    $tp = e107::getParser();
    
    $MODCLASSES = array(
      4=>array(
        'name'=>EST_GEN_ESTATE.' '.EST_GEN_MAINADMIN.' '.EST_GEN_ONLY,
        'users'=>array()
        ),
      3=>array(
        'name'=>EST_GEN_ESTATE.' '.EST_GEN_ADMIN.' & '.EST_GEN_MAINADMIN,
        'users'=>array()
        ),
      2=>array(
        'name'=>EST_GEN_ESTATE.' '.EST_GEN_MANAGER.', '.EST_GEN_ADMIN.', & '.EST_GEN_MAINADMIN,
        'users'=>array()
        ),
      );
    
    
    $sql->gen("SELECT user_id,user_name,user_loginname,user_email,user_admin,user_perms,user_class, #estate_agents.* FROM #estate_agents LEFT JOIN #user ON user_id = agent_uid");
     while($rows = $sql->fetch()){
      
      $AGTCLSES = explode(",",$rows['user_class']);
      $UPERMS = explode('.',$rows['user_perms']);
      $UDTA = array('id'=>$rows['user_id'],'name'=>$rows['agent_name'],'email'=>$rows['user_email']);
      if(in_array('0',$UPERMS)){
        array_push($MODCLASSES[4]['users'],$UDTA);
        }
      elseif(in_array(ESTATE_ADMIN,$AGTCLSES)){
        array_push($MODCLASSES[3]['users'],$UDTA);
        }
      elseif(in_array(ESTATE_MANAGER,$AGTCLSES)){
        array_push($MODCLASSES[2]['users'],$UDTA);
        }
      }
    
    //public_mod
    
    
    
    
    if($fldname == 'public_mod'){
      $txt = '<select id="public-mod" name="public_mod" class="form-control input-xlarge" value="'.$curVal.'" >';
      foreach($MODCLASSES as $k=>$v){
        $txt .= '<option value="'.$k.'"'.($k == $curVal ? ' selected="selected"' : '').'>'.$tp->toHTML($v['name']).'</option>';
        }
      $txt .= '</select>';
      unset($MODCLASSES);
      return $txt;
      }
    
    
    $curVal = (is_array($curVal) ? $curVal : explode(",",$curVal));
    
    /*
    <input type="hidden" name="'.$fldname.'" value="'.$curVal.'"/>
    <div class="estPubNotifyCont" data-fld="'.$fldname.'">
      <div class="btn btn-sm btn-default estPubNotifySectBtn">
        <input type="checkbox" id="estNoModNotify" value="0" '.(count($DTA) == 0 ? ' checked="checked"' : '').' title="" />'.$tp->toHTML(EST_GEN_NOBODY).'
      </div>
    </div>
    
    */
    $txt = '
    <div id="estPubNotifyCont" class="estPubNotifyCont">';
    
    
    
    foreach($MODCLASSES as $mk=>$mv){
      if(count($mv['users']) > 0){
        $txt .= '
        <div class="estPubNotifySect" data-lev="'.$mk.'">';
        foreach($mv['users'] as $uk=>$uv){
          $uid = intval($uv['id']);
          $txt .= '
          <div class="btn btn-sm btn-default estPubNotifySectBtn" data-lev="'.$mk.'">
            <input type="checkbox" id="estNAModId'.$mk.'-'.$uk.'-'.$uid.'" name="public_notify[]" value="'.$uid.'"'.(in_array($uid,$curVal) ? ' checked="checked" data-chk="1"' : 'data-chk="0"').' />'.$tp->toHTML($uv['name'].' ('.$uv['email'].')').'
          </div>';
          }
        $txt .= '
        </div>';
        }
      }
    
    
    
    $txt .= '</div>';
    unset($MODCLASSES);
    return $txt;
    }
  
  
  
  public function estPropertyListTable(){
    $EST_PREF = e107::pref('estate');
    $sql = e107::getDB();
    $tp = e107::getParser();
    
    /*
        <fieldset id="admin-ui-list-filter" class="e-filter" style="margin:4px;">
          <div class="left form-inline span8 col-md-8">
            <div class="row-fluid">
              <div class="btn-group">
                <span id="admin-ui-list-search" class="form-group has-feedback has-feedback-left FL"><input type="text" name="searchquery" value="" maxlength="50" id="searchquery" class="tbox input-text filter input-xlarge form-control ui-state-valid" size="20" data-original-title="" title=""></span>
                <div class="e-autocomplete"></div>
                <span class="indicator" style="display: none;"><i class="fa fa-spin fa-spinner fa-fw"></i></span>
                <button type="button" id="estPropCreate" class="btn btn-default ILBLK" title="'.EST_GEN_CREATE.'"><i class="fa fa-plus"></i></button>
              </div>
            </div>
          </div>
        </fieldset>
    
    */
    
    $text = '
    <form method="post" action="'.e_SELF.'?mode=estate_properties&amp;action=list" id="plugin-estate-list-form" data-h5-instanceid="0" novalidate="novalidate">
			<input type="hidden" name="e-token" value="'.e_TOKEN.'" data-original-title="" title="">
      <div class="admin-main-content">';
    
    $FLTR = array();
    
    if(EST_USERPERM >= intval($EST_PREF['public_mod']) && intval($EST_PREF['public_act']) !== 255){
      $DTA = $this->estPropertyListQry(1,$FLTR);
      $TBS[0]['caption'] = EST_GEN_AGENT.' '.EST_GEN_LISTINGS;
      $TBS[0]['text'] = $this->estPropertyListTableSF(1,$DTA);
      
      $FLTR = array();
      $DTA = $this->estPropertyListQry(2,$FLTR);
      $TBS[1]['caption'] = EST_GEN_NONAGENTLISTINGS;
      $TBS[1]['text'] = $this->estPropertyListTableSF(2,$DTA);
      }
    else{
      $DTA = $this->estPropertyListQry(0,$FLTR);
      $TBS[0]['caption'] = EST_GEN_ALL.' '.EST_GEN_LISTINGS;
      $TBS[0]['text'] = $this->estPropertyListTableSF(1,$DTA);
      }
    
    $NDTA = array('e-token'=>e_TOKEN,'prop_listype'=>1,'prop_status'=>3,'prop_currency'=>intval($EST_PREF['currency']),'prop_leasedur'=>0,'prop_leasefreq'=>1,'prop_lat'=>$EST_PREF['pref_lat'],'prop_lon'=>$EST_PREF['pref_lon'],'seller_namex'=>EST_AGENTNAME,'prop_appr'=>USERID);
    
    $TBS[2]['caption'] = EST_GEN_NEW.' '.EST_GEN_LISTING;
    $TBS[2]['text'] = '<form method="post" action="'.e_SELF.'?'.e_QUERY.'" id="plugin-estate-form" autocomplete="off" data-h5-instanceid="0" novalidate="novalidate">';
    $TBS[2]['text'] .= $this->estOAHidden('e-token',$NDTA);
    $TBS[2]['text'] .= $this->estOAHidden('prop_currency',$NDTA);
    $TBS[2]['text'] .= $this->estOAHidden('prop_leasefreq',$NDTA);
    $TBS[2]['text'] .= $this->estOAHidden('prop_lat',$NDTA);
    $TBS[2]['text'] .= $this->estOAHidden('prop_lon',$NDTA);
    $TBS[2]['text'] .= $this->estOAHidden('prop_appr',$NDTA);
    //estate_listypes
    
    $TBS[2]['text'] .= '
      <table class="table adminform" style="width:100%">
        <colgroup style="width:25%"></colgroup>
        <colgroup style="width:75%"></colgroup>
        <tbody>';
    $TBS[2]['text'] .= $this->estOAFormTR('text','prop_name',$NDTA);
    $TBS[2]['text'] .= $this->estOAFormTR('nofld','seller_namex',$NDTA);
    $TBS[2]['text'] .= $this->estOAFormTR('select','prop_status',$NDTA);
    $TBS[2]['text'] .= $this->estOAFormTR('select','prop_zoning',$NDTA);
    $TBS[2]['text'] .= $this->estOAFormTR('select','prop_type',$NDTA);
    
    $TBS[2]['text'] .= $this->estOAFormTR('select','prop_listype',$NDTA);
    $TBS[2]['text'] .= $this->estOAFormTR('text','prop_origprice',$NDTA);
    $TBS[2]['text'] .= $this->estOAFormTR('select','prop_leasedur',$NDTA);
    $TBS[2]['text'] .= '
        </tbody>
        <tfoot>
          <tr>
            <td colspan="2">
              <div class="buttons-bar center">
                <input type="submit" name="estAdminNewListing" class="btn btn-primary" value="'.EST_GEN_SAVECONT.'" />
              </div>
            </td>
          </tr>
        </tfoot>
      </table>
    </form>';
    
    
    
    $text .= e107::getForm(false,true)->tabs($TBS,array('active'=>0,'fade'=>0,'class'=>'estOATabs'));
    unset($NDTA);
    /*
    $text .= '
        <div id="admin-ui-list-batch" class="navbar navbar-inner left">
    			<div class="span6 col-md-6">
            <div class="form-inline input-inline">
    					<img src="/e107_images/generic/branchbottom.gif" class="treeprefix level-x icon" alt="">
    	        <div class="input-group input-append">
    						<select name="etrigger_batch" id="etrigger-batch" class="tbox form-control input-large select batch e-autosubmit reset" data-original-title="" title="">
      						<option value="">With selected...</option>
                  <option value="copy" class="ui-batch-option class" style="padding-left: 15px">Copy</option>
                  <option value="delete" class="ui-batch-option class" style="padding-left: 15px">Delete</option>
                  <option value="export" class="ui-batch-option class" style="padding-left: 15px">Export</option>
                  <optgroup class="optgroup " label="Modify&nbsp;Friendly URL">
                    <option value="sefgen__prop_sef__prop_name">Generate</option>
                  </optgroup>
                </select>
                <div class="input-group-btn input-append">
    				      <button data-loading-icon="fa-spinner" type="submit" name="e__execute_batch" value="e__execute_batch" id="e--execute-batch" class="btn batch e-hide-if-js btn-primary"><span>Go</span></button>
                </div>
              </div>
            </div>
          </div>
    			<div id="admin-ui-list-total-records" class="span6 col-md-6 right"><span>Total Records: 12</span></div>
    		</div>';
    */
    $text .= '
      </div>
    </form>';
    return $text;
    }
  
  
  
  public function estAgencyList($fltr=0){
    $tp = e107::getParser();
    $sql = e107::getDB();
    $agencies = $this->estGetAllAgencies(1);
    
    if(count($agencies) == 0){
      return '
      <div class="s-message alert alert-block alert-dismissible fade in show info  alert-info" style="width: 96%; margin: 16px auto 0px auto;">
        <h4 class="s-message-title">'.EST_ERR_NOAGENCIES0.'</h4>
        <div class="s-message-body">
          <div class="s-message-item">'.EST_ERR_NOAGENCIES1.' '.EST_ERR_NOAGENCIES2.'</div>
          <div class="s-message-item"><button id="estNewAgencyBtn" type="button" class="btn btn-default" title="'.LAN_CREATE.'"><i class="S16 e-add-16"></i></button></div>
        </div>
      </div>';
      }
    else{
      
      /*
      <button type="button" name="estFilterAgencies" value="1" id="estFilterAgencies" class="btn btn-default" title="Filter"><span><i class="fa fa-filter"></i></span></button>
      */
      
      if($fltr == 1){
        $text .= '
        <fieldset id="admin-ui-list-filter" class="e-filter" style="margin:4px;">
          <div class="row-fluid">
            <div class="left form-inline span8 col-md-8">
							<span id="" class="form-group has-feedback has-feedback-left">
                <select name="estLocFltr1" id="estLocFltr1" class="form-control e-tip tbox select input-xlarge filter ui-state-valid" title="" data-original-title="Filter">
                  <option value="">All Locations</option>
                  <option value="none">- '.EST_GEN_MISSING.' '.EST_GEN_ADDRESS.' -</option>
                </select>
                <button id="estNewAgencyBtn" type="button" class="btn btn-default" title="'.LAN_CREATE.'"><i class="S16 e-add-16"></i></button>
							</span>
            </div>
            <div class="span4 col-md-4 text-right"></div>
          </div>
        </fieldset>';
        }
      
            //<th>'.EST_GEN_PROFILEVISIB.'</th>
      $text .= '
      <table class="table adminlist table-striped estCustomTable1">
        <colgroup style="width:5%"></colgroup>
        <colgroup style="width:20%"></colgroup>
        <colgroup style="width:35%"></colgroup>
        <colgroup style="width:20%"></colgroup>
        <colgroup style="width:20%"></colgroup>
        <thead>
          <tr>
            <th>'.EST_GEN_LOGO.'</th>
            <th>'.EST_GEN_NAME.'</th>
            <th colspan="2">'.EST_GEN_DETAILS.'</th>
            <th class="TAC">'.LAN_OPTIONS.'</th>
          </tr>
        </thead>
        <tbody id="estAgyListTB">';
        $LOCADDR = array();
        foreach($agencies as $ak=>$av){
          extract($av);
          
          if(trim($agency_addr) !== ''){
            $adr1 = explode("\n",$agency_addr);
            $adr2 = explode(" ",str_replace(",","",$adr1[(count($adr1) - 1)]));
            $eky = $adr2[(count($adr2) - 2)];
            foreach($adr2 as $k=>$v){if($k < (count($adr2) - 1)){$addrKey .= ($addrKey ? ' ' : '').$v;}}
            if(!$LOCADDR[$eky]){$LOCADDR[$eky] = array($addrKey);}
            elseif(!in_array($addrKey,$LOCADDR[$eky])){array_push($LOCADDR[$eky],$addrKey);}
            }
          
          $LOGOIMG = $agency_altimg;
          if(intval($agency_imgsrc) == 1){
            if(trim($agency_image) !== ''){$LOGOIMG = EST_PTHABS_AGENCY.$tp->toHTML($agency_image);}
            else{$agency_imgsrc = 0;}
            }
          
          
          $EDTBTN = '';

          $DELBTN = '';
          
          $text .= '
          <tr class="estAgyLocTr" data-addr="'.($addrKey ? $tp->toJS($addrKey) : 'none').'">
            <td><img class="img-rounded rounded user-avatar" alt="'.$LOGOIMG.'" src="'.$LOGOIMG.'" loading="lazy"></td>
            <td>
              <h4>'.$agency_name.'</h4>
              <p>'.$tp->toHTML($agency_addr).'</p>
            </td>
            <td>
              <div>'.intval($agency_agtct).' '.(intval($agency_agtct) == 1 ? EST_GEN_AGENT : EST_GEN_AGENTS).'</div>
              <div>'.intval($agency_listct).' '.(intval($agency_listct) == 1 ? EST_GEN_LISTING : EST_GEN_LISTINGS).'</div>
            </td>
            <td>
              &nbsp;
            </td>
            <td class="center last options">
    					<div class="btn-group FLEXREV">
                <button type="submit" name="etrigger_delete['.intval($agency_idx).']" data-placement="left" value="'.intval($agency_idx).'" id="etrigger-delete-1-'.intval($agency_idx).'" class="action delete btn btn-default" '.(count($agencies) < 2 ? 'title="'.EST_GEN_REMAGENCYX.'" disabled="disabled"' : ' title="'.EST_GEN_DELETE.' '.$tp->toJS($agency_name).'"data-confirm="'.EST_GEN_REMAGENCY1.'"').'><i class="S32 e-delete-32"></i></button>
                <a href="'.e_SELF.'?mode=estate_agencies&action=edit&id='.intval($agency_idx).'" class="btn btn-default btn-secondary" data-modal-caption="" title="'.EST_GEN_EDIT.' '.$tp->toJS($agency_name).'" data-placement="left"><i class="S32 e-edit-32"></i></a><button type="button" name="" data-placement="left" value="'.intval($agency_pub).'" id="" class="btn btn-default estAgyVisibBtn" data-idx="'.intval($agency_idx).'"><i class="far '.(intval($agency_pub) ? 'fa-eye' : 'fa-eye-slash').' WD32px"></i></button>
              </div>
    				</td>
          </tr>';
          unset($addrKey);
          }
        $text .= '
        </tbody>
      </table>
      <div id="estAddrOpts" style="display:none;">';
      //sort($LOCADDR);
      
      foreach($LOCADDR as $k=>$v){
        $text .= '<optgroup label="'.$tp->toJS($k).'">'.$tp->toHTML($k).'</optgroup>';
        foreach($v as $sk=>$sv){
          $text .= '<option value="'.$tp->toJS($sv).'">'.$tp->toHTML($sv).'</option>';
          }
        }
      $text .= '
      </div>';
      return $text;
      }
    }
  
  public function estAgentPHPform($dta){}
  
  
  public function estAgentForm($dta){
    $tp = e107::getParser();
    $frm = e107::getForm(false, true);
		$pref = e107::getPref();
    
    if($dta === 'anu'){
      $ANU = true;
      $dta = array();
      //$UserHandler = new UserHandler;
      }
    //estGetAgentById
    $dta = $this->estUserAgentData($dta);
    
    
    
    $dtaStr = $this->estDataStr($dta);
    extract($dta);
    $UID = intval($user_id);
    
    $XRP = explode('.',$user_perms);
    $XRC = explode(',',$user_class);
    $XGO = 0; // if > 0 then omit results
    $agent_agcy = intval($agent_agcy);
    
    if(in_array('0',$XRP) && USERID !== $UID){$XGO++;}
    
    if(EST_USERPERM == 3){
      if(in_array(ESTATE_ADMIN,$XRC) && USERID !== $UID){$XGO++;}
      }
    
    if(EST_USERPERM == 2 && intval($agent_idx) !== 0 && USERID !== $UID){
      foreach($XRC as $tk=>$tv){
        if(!in_array($tv,EST_USERMANAGE)){$XGO++;}
        }
      }
    
    if(USERID !== 1 && $XGO > 0){
      return '<div class="estStopForm">You do not have permissions to edit this Agent</div>';
      }
    
    
    if($agent_imgsrc == 1){$AGENTIMAGE = EST_PTHABS_AGENT.$tp->toHTML($agent_image);}
    else{$AGENTIMAGE = (trim($agent_altimg) !== '' ? $agent_altimg : $user_profimg);}
    
    if(USERID === $UID){
      $agent_lev = (intval($agent_lev) > 0 ? intval($agent_lev) : (intval(EST_USERPERM) > 0 ? intval(EST_USERPERM) : 1));
      }
    
    
    
    if(in_array('0',$XRP)){$agent_lev = 4;}
    elseif(in_array(ESTATE_ADMIN,$XRC)){$agent_lev = 3;}
    elseif(in_array(ESTATE_MANAGER,$XRC)){$agent_lev = 2;}
    else{$agent_lev = 1;}
    
    
    $TRS = array();
    switch(EST_USERPERM){
      case 4 : // Main Admin
      case 3 : //Estate Admin
        if(!isset($ANU)){$TRS[0] = $this->estAgentUIDSelect($agent_uid);}
        $HELES .= $frm->hidden('agent_lev',intval($agent_lev));
        break;
      case 2 : //Manager
        if(!isset($ANU)){$HELES .= $frm->hidden('agent_uid',intval($agent_uid));}
        $HELES .= $frm->hidden('agent_lev',intval($agent_lev));
        break;
      default : //Agent
        $HELES .= $frm->hidden('agent_uid',intval($agent_uid));
        $HELES .= $frm->hidden('agent_lev',intval($agent_lev));
        break;
      }
    
    $HELES .= $frm->hidden('estNewUserPost',intval($GLOBALS['ESTNEWUSRPOST']));
    
    if(isset($ANU)){
      $HELES .= $frm->hidden('estNewUserKey',1);
      $HELES .= $frm->hidden('estProfileKey',6);
      //estNewUserPost
      $TRS[0][0] = LAN_USER_02.'*'; //user_loginname
      $TRS[0][1] = $frm->text('user_loginname', $user_loginname, varset($pref['loginname_maxlength'], 30), array('size'=>'xlarge', 'required'=>1));
      $TRS[1][0] = LAN_USER_03; //user_login
      $TRS[1][1] = $frm->text('user_login',$user_login, 30, array('size'=>'xlarge'));
      $TRS[2][0] = LAN_PASSWORD.'*';
      $TRS[2][1] = $frm->password('password', '', 128, array('size' => 'xlarge', 'class' => 'tbox e-password', 'generate' => 1, 'strength' => 1, 'autocomplete' => 'new-password'));
      $TRS[3][0] = LAN_EMAIL.'*';
      $TRS[3][1] = $frm->text('user_email',$user_email, 100, array('size'=>'xlarge'));
      //$TRS[4][0] = LAN_USER_10;
      //$TRS[4][1] = 'user_hideemail';
      }
    
    $dataStr = $this->estDataStr($dta);
    
    $text = '
    '.$frm->hidden('agent_idx',intval($agent_idx)).'
    '.$HELES.'
    '.$frm->hidden('agent_image',$tp->toFORM($agent_image)).'
    '.$frm->hidden('agent_imgsrc',intval($agent_imgsrc)).'
    '.$frm->hidden('agent_altimg',$agent_altimg).'
    <table id="'.($UID > 0 ? 'estAgentFormTable' : 'estNewUserFormTable').'" class="table estFormSubTable adminform" '.$dataStr.'>
      <colgroup style="width:20%"></colgroup>
      <colgroup style="width:60%"></colgroup>
      <colgroup style="width:20%"></colgroup>
      <thead>
        <tr>
          <th>'.(intval($agent_idx) == 0 ? EST_GEN_NEW.' ' : '').EST_GEN_AGENT.' '.EST_GEN_PROFILE.': </th>
          <th>'.(isset($ANU) ? EST_GEN_NEW.' '.LAN_USER : $user_login.' ['.$user_name.'] ('.$tp->toHTML($user_email).')').'</th>
          <th class="TAC">'.EST_GEN_PROFILE.' '.EST_GEN_IMAGE.'</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>'.(isset($ANU) ? LAN_USER.' '.LAN_USER_01.'<br />' : '').EST_GEN_AGENT.' '.EST_GEN_NAME.'*</td>
          <td>'.$frm->text('agent_name', $tp->toTEXT($agent_name), 100, array('size' => 'xxlarge','placeholder'=>EST_GEN_AGENT.' '.EST_GEN_NAME)).'</td>
          <td rowspan="'.(count($TRS) > 1 ? 5 : 4).'" class="TAC">
            <div id="agtAvatar" class="estAgentAvatar'.(isset($ANU) ? ' estNUAvatar' : '').'" style="background-image:url(\''.$AGENTIMAGE.'?'.rand(99,99999).'\')">
              <img class="estSecretImg" src="'.$AGENTIMAGE.'?'.rand(99,99999).'" alt="" style="display:none;" />
              <input type="file" id="'.(isset($ANU) ? 'file_userfile[avatar]" name="file_userfile[avatar]' : 'estAvatarUpload" name="estAvatarUpload').'" data-targ="agent_image" accept="image/jpeg, image/png, image/gif" class="ui-state-valid estInitFile noDISP">
            </div>
          </td>
        </tr>
        <tr>
          <td>'.EST_GEN_ADMINACCLEVEL.'</td>
          <td>'.EST_USRLEVELS[$agent_lev].'</td>
        </tr>
        <tr>
          <td>'.EST_GEN_LISTINGS.'</td>
          <td>'.intval($agent_propct).' '.(intval($agent_propct) == 1  ? EST_GEN_LISTING : EST_GEN_LISTINGS).'</td>
        </tr>
        <tr>
          <td>'.EST_GEN_AGENCY.'</td>
          <td>';
    
    
      $agencies = $this->estGetAllAgencies();
      
      if(count($agencies) == 0){$text .= EST_ERR_NOAGENCIES1;}
      elseif(count($agencies) === 1){
        $text .= $frm->hidden('agent_agcy',intval($agencies[0]['agency_idx']));
        $text .= $tp->toHTML($agencies[0]['agency_name']);
        if(intval($agencies[0]['agency_idx']) !== intval($agent_agcy)){
          $text .= ' <span class="smalltxt estTxtErr">'.EST_GEN_UPDATEREQ.'<span>';
          }
        }
      else{
        foreach($agencies as $k=>$v){
          foreach($v as $dk=>$dv){$dtaStr .= ' data-'.$dk.'="'.$tp->toJS($dv).'"';}
          $AGYOPTS .= '<option value="'.intval($v['agency_idx']).'"'.$dtaStr.' '.(intval($v['agency_idx']) == $agent_agcy ? 'selected="selected"' : '').'>'.$tp->toHTML($v['agency_name']).'</option>';
          unset($dtaStr);
          }
        
        if($agent_agcy == 0){
          if(intval(EST_USERPERM) === 2 && USERID !== $UID){$text .= $frm->hidden('agent_agcy',$agent_agcy);}
          }
        
        if(intval(EST_USERPERM) > 1 || $agent_agcy == 0){
          $text .= $frm->select_open('agent_agcy',array('value'=>$agent_agcy,'size'=>'xxlarge')); //estSelectDta
          $text .= $AGYOPTS;
          $text .= $frm->select_close();
          }
        else{
          $text .= $frm->hidden('agent_agcy',$agent_agcy);
          if($agent_agcy > 0){
            $agyr = e107::getDB()->retrieve('estate_agencies', '*','agency_idx="'.$agent_agcy.'" LIMIT 1',true);
            }
          $text .= (trim($agyr[0]['agency_name']) !== '' ? $tp->toHTML($agyr[0]['agency_name']) : EST_GEN_NOTASSIGNEDAGENCY);
          }
        unset($AGYOPTS);
        }
        
      $text .= '
          </td>
        </tr>';
      
      
      if(count($TRS)){
        foreach($TRS as $k=>$v){
          $text .= '
            <tr>
              <td>'.$v[0].'</td>
              <td'.($k > (count($TRS) - 3) ? ' colspan="2"' : '').'>'.$v[1].'</td>
            </tr>';
          }
        }
      
      
      $text .= '
        <tr>
          <td>'.(isset($ANU) ? LAN_USER.' '.LAN_USER_09.'<br />' : '').EST_GEN_AGENT.' '.EST_GEN_INFO.'</td>
          <td>'.$frm->textarea('agent_txt1',$tp->toFORM($agent_txt1),3,80,array('size' => 'xxlarge','placeholder'=>EST_PLCH75),true).'</td>
        </tr>';
    
    if(!isset($ANU)){
      $text.= '
        <tr>
          <td>'.EST_GEN_CONTACTS.'</td>
          <td class="posREL">
            <div id="estAgentPHPform2" class="">';
        $text .= $this->estContactForm(6,$agent_idx,2);
        $text .= '
            </div>
          </td>
          <td>&nbsp;</td>
        </tr>';
      }
      
      
        
      $text .= '
      </tbody>
		</table>';
    return $text;
    }
  
  
  
  
  
  
  public function estGetCompFeatures($zoning_idx=0){
    $ret = array();
    if($zoning_idx > 0){
      $sql = e107::getDB();
      $TQRY = "
        SELECT #estate_zoning.*, #estate_featcats.*, #estate_features.*, #estate_listypes.*, #estate_group.*
        FROM #estate_zoning 
        LEFT JOIN #estate_featcats
        ON featcat_zone = zoning_idx
        LEFT JOIN #estate_features
        ON feature_cat = featcat_idx
        LEFT JOIN #estate_listypes
        ON listype_zone = zoning_idx
        LEFT JOIN #estate_group
        ON group_zone = zoning_idx
        WHERE zoning_idx = '".$zoning_idx."'
        ORDER BY featcat_lev ASC, featcat_name ASC, feature_name ASC";
      
      if($sql->gen($TQRY)){
        $lev = $this->estSectLevel();
  			while($row = $sql->fetch()){
          $ret['name'] = $row['zoning_name'];
          $ret['ltype'][$row['listype_idx']] = $row['listype_name'];
          $ret['grps'][$row['group_lev']][$row['group_idx']]['lev'] = $lev[$row['group_lev']];
          $ret['grps'][$row['group_lev']][$row['group_idx']]['name'] = $row['group_name'];
          $ret['feat'][$row['featcat_lev']][$row['featcat_idx']]['name'] = $row['featcat_name'];
          $ret['feat'][$row['featcat_lev']][$row['featcat_idx']]['dta'][$row['feature_idx']]['name'] = $row['feature_name'];
          $ret['feat'][$row['featcat_lev']][$row['featcat_idx']]['dta'][$row['feature_idx']]['ele'] = intval($row['feature_ele']);
          $ret['feat'][$row['featcat_lev']][$row['featcat_idx']]['dta'][$row['feature_idx']]['opts'] = $row['feature_opts'];
          }
        }
      }
    return $ret;
    }
  
  
  
  
  
  
  
  
	public function estGetCompContacts($id=0){
    $sql = e107::getDB();
    $RES = array();
    
    if($sql->select('estate_contacts', '*','')){
      $ci = 0;
			while($row = $sql->fetch()){
        $row['contact_key'] = str_replace(" ","_",strtoupper($row['contact_key']));
        $RES[$ci] = $row;
        $ci++;
        }
      }
    return $RES;
    }
  
  
  
	public function estGetContDta($key,$id){
    $sql = e107::getDB();
    $tp = e107::getParser();
		$key = (int)$key;
		$id = (int)$id;
    $ret = array();
    
    $CONTKEYS = $this->estGetContKeys();
		
    if($sql->select('estate_contacts', '*', 'contact_tabkey="'.$key.'" AND contact_tabidx="'.$id.'" ORDER BY contact_key ASC')){
			while($row = $sql->fetch()){
        $row['contact_key'] = str_replace(" ","_",strtoupper($row['contact_key']));
        $ret[$row['contact_key']][$row['contact_idx']] = $row;
        }
      }
    if(intval($id) == 0){
      if(intval($key) == 6){
        if(!$ret[$CONTKEYS[0]]){$ret[$CONTKEYS[0]][0]['contact_data'] = '';}
        if(!$ret[$CONTKEYS[1]]){$ret[$CONTKEYS[1]][0]['contact_data'] = USEREMAIL;}
        }
      if(intval($key) == 5){
        if(!$ret[$CONTKEYS[2]]){$ret[$CONTKEYS[2]][0]['contact_data'] = '';}
        if(!$ret[$CONTKEYS[3]]){$ret[$CONTKEYS[3]][0]['contact_data'] = '';}
        }
      }
      
    $dberr = $sql->getLastErrorText();
    if($dberr){e107::getMessage()->addError($dberr);}
    unset($dberr);
		
    return $ret;
    }
  
  
  
  public function estGetContKeys(){
    $sql = e107::getDB();
    $tp = e107::getParser();
    $CONTKEYS = EST_CONTKEYS;//array(EST_GEN_MOBILE,EST_GEN_EMAIL,EST_GEN_OFFICE,EST_GEN_FAX,EST_GEN_WEBSITE,EST_GEN_LINKIN,EST_GEN_TWITER,EST_GEN_FACEBOOK);
    foreach($CONTKEYS as $CK=>$CV){
      $CK = str_replace(" ","_",$CK);
      $CV = str_replace(" ","_",$CV);
      $CONTKEYS[$CK] = strtoupper($CV);
      }
    
    $sql->gen("SELECT contact_key FROM #estate_contacts GROUP BY contact_key");
    while($rows = $sql->fetch()){
      $QKEY = strtoupper(str_replace(" ","_",$rows['contact_key']));
      if(!in_array($QKEY,$CONTKEYS)){array_push($CONTKEYS,$QKEY);}
      }
    return $CONTKEYS;
    }
  
  public function estContactFldNames($CV){
    return 'contact_'.preg_replace("/[^a-z0-9_-]/", "", str_replace(" ", "_", strtolower($CV)));
    }
  
  
  
  public function estMap($section,$addr,$lat,$lon,$geoarea,$zoom=14){
    $tp = e107::getParser();
    $frm = e107::getForm(false, true);
    
    return '
          <tr class="noMobile">
            <td class="VAT">
              <div id="est_'.$section.'_SrchRes" class="estMapBtnCont"></div>
            </td>
            <td class="VAT">
              '.$frm->hidden($section.'_lat',$tp->toFORM($lat)).'
              '.$frm->hidden($section.'_lon',$tp->toFORM($lon)).'
              '.$frm->hidden($section.'_geoarea',$tp->toFORM($geoarea)).'
              '.$frm->hidden($section.'_zoom',intval($zoom)).'
              <div id="est_'.$section.'_SrchForm" class="estInptCont form-group has-feedback-left estMapSearchCont">
                <input type="text" id="'.$section.'_addr_lookup" name="'.$section.'_addr_lookup" class="tbox form-control input-xxlarge estMapLookupAddr" value="'.$tp->toFORM($addr).'" placeholder="'.EST_PLCH15.'"/>
                <button id="est_'.$section.'_SrchBtn" class="btn btn-default estMapSearchBtn"><i class="fa fa-search"></i></button>
              </div>
              <div id="est_'.$section.'_MapCont" class="estMapCont"><div id="est_'.$section.'_Map" class="estMap"></div></div>
            </td>
            <td class="VAT">
              <div id="est_'.$section.'_MapHlpTD">
                <p>'.EST_GEN_MAPHLP1.'</p>
                <p>'.EST_GEN_MAPHLP2.'</p>
                <p>'.EST_GEN_MAPHLP3.'</p>
                <p>'.EST_GEN_MAPHLP4.'</p>
                <p>'.EST_GEN_MAPHLP5.'</p>
              </div>
            </td>
          </tr>
          <tr class="noDesktop">
            <td colspan="3" class="VAT">
              <div id="est_'.$section.'_MapCont_targ" class="WD100"></div>
              <div id="est_'.$section.'_SrchForm_targ" class="WD100"></div>
              <div id="est_'.$section.'_SrchRes_targ" class="WD100"></div>
              <div id="est_'.$section.'_MapHlpTD_targ" class="WD100"></div>
            </td>
          </tr>';
    }
  
  
  
  public function estNoCompID($txt){
    return '
    <div class="s-message alert alert-block alert-dismissible fade in show info  alert-info" style="width: 96%; margin: 16px auto 0px auto;">
      <h4 class="s-message-title">'.EST_ERR_COMPIDZERO.'</h4>
		  <div class="s-message-body">
			 <div class="s-message-item">'.EST_ERR_COMPIDZERO1.' '.$txt.'</div>
      </div>
    </div>';
    }
  
  
  
  public function estGetPresetsForm($zi,$newZone=''){
    $tp = e107::getParser();
    $frm = e107::getForm(false, true);
    
    $dtaset = $this->estGetCompFeatures($zi);
    $zoning = $this->estGetZoning();
    
    $lev = $this->estSectLevel();
    krsort($lev);
    
    $mct = 8;
    if(trim($newZone) !== ''){$NEWZPT = '<option value="'.$zi.'" class="estNewZoneopt">'.$tp->toHTML($newZone).'</option>';}
    
    $mct = 6;
    $text = '
        <table id="estPresetsTable-'.$zi.'" class="table estFormSubTable estPresetsTable">
          <tbody>
            <tr>
              <td>
                <div class="estHlpFLeft"><i class="admin-ui-help-tip fa fa-question-circle" data-original-title="" title="" ></i><div class="field-help TAL" data-placement="left" style="display:none"><p><b>'.EST_PROP_LISTYPE.'</b><br />'.EST_HLP_LISTTYPE0.'</p></div></div>'.$zoning[$zi].' '.EST_GEN_LISTYPES.' <button class="btn btn-primary btn-sm estPresetsNewListTypeBtn" title="'.EST_GEN_ADDNEW.' '.EST_PROP_LISTYPE.'" data-zi="'.$zi.'"  data-mx="'.$mct.'">'.EST_GEN_NEW.' '.EST_PROP_LISTYPE.'</button>
              </td>
            </tr>
            <tr>
              <td>
                <div id="estPresetsListTypeCont-'.$zi.'" class="estPresetsListCont">
                  <div class="estPresetsListDiv">
                    <ul class="estPresetsListUL" data-nkey="['.$zi.']">';

    $lict = 0;
    $dCt = 0;
    $dMx = (is_array($dtaset['ltype']) ? count($dtaset['ltype']) : 0);
    if($dMx > 0){
      foreach($dtaset['ltype'] as $lk=>$lv){
        if($lk > 0){
          $dCt++;
          $text .= '
                      <li class="estPresetDataLI1">
                        <input type="checkbox" name="listype_keep['.$zi.']['.$lk.']" value="1" checked="checked" />
                        <input type="hidden" name="listype_name['.$zi.']['.$lk.']" value="'.$tp->toFORM($lv).'" />
                        <a data-inpt="listype_name['.$zi.']['.$lk.']" contenteditable="true">'.$tp->toHTML($lv).'</a>
                      </li>';
          $lict++;
          if($lict == $mct){
            $lict = 0;
            $text .= '
                  </ul>
                </div>';
            if($dCt < $dMx){
              $text .='
                <div class="estPresetsListDiv">
                  <ul class="estPresetsListUL">';
              }
            }
          }
        }
      }
    if($lict !== 0){
      $text .= '
                    </ul>
                  </div>';
          }
      $text .= '
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <div class="estHlpFLeft"><i class="admin-ui-help-tip fa fa-question-circle" data-original-title="" title=""></i><div class="field-help TAL" data-placement="left" style="display:none"><p><b>'.EST_GEN_SPACES.' '.EST_GEN_GROUP.'</b><br />'.EST_HLP_SPACESGRP0.'</p></div></div>'.EST_GEN_SPACES.' '.EST_GEN_GROUP.'<button class="btn btn-primary btn-sm estPresetsNewGroupBtn" title="'.EST_GEN_ADDNEW.' '.EST_GEN_GROUP.'" data-zi="'.$zi.'" data-lk="2" data-mx="'.$mct.'">'.EST_GEN_NEW.' '.EST_GEN_GROUP.'</button>
              </td>
            </tr>
            <tr>
              <td>
                <div id="estPresetsGroupCont-'.$zi.'-2" class="estPresetsListCont">
                  <div class="estPresetsListDiv">
                    <ul class="estPresetsListUL">';
    
    $lict = 0;
    $dCt = 0;
    $dMx = (is_array($dtaset['grps'][2]) ? count($dtaset['grps'][2]) : 0);
      if($dMx > 0){
          foreach($dtaset['grps'][2] as $gk=>$gv){
            if($gk > 0){
              $text .= '
                      <li class="estPresetDataLI1">
                        <input type="checkbox" name="group_name_keep['.$zi.'][2]['.$gk.']" value="1" checked="checked" />
                        <input type="hidden" name="group_name['.$zi.'][2]['.$gk.']" value="'.$tp->toFORM($gv['name']).'" />
                        <a data-inpt="group_name['.$zi.'][2]['.$gk.']" contenteditable="true">'.$tp->toHTML($gv['name']).'</a>
                      </li>';
              $lict++;
              if($lict == $mct){
                $lict = 0;
                $text .= '
                    </ul>
                  </div>';
                if($dCt < $dMx){
                  $text .='
                  <div class="estPresetsListDiv">
                    <ul class="estPresetsListUL">';
                  }
                }
              }
            }
          }
      
        if($lict !== 0){
          $text .= '
                      </ul>
                    </div>';
              }
        $text .= '
                </div>
              </td>
            </tr>';
        
    foreach($lev as $lk=>$lv){
      $text .= '
            <tr>
              <td>
                <div class="estHlpFLeft"><i class="admin-ui-help-tip fa fa-question-circle" data-original-title="" title=""></i><div class="field-help TAL" data-placement="left" style="display:none"><p>'.EST_HLP_FEATURES0.'</p><p>'.EST_HLP_FEATURES1.'</p><p>'.EST_HLP_FEATURES2.'</p></div></div>'.EST_GEN_FEATURESFOR.' '.$lv.' <button class="btn btn-primary btn-sm estPresetsNewBtn" title="'.EST_GEN_ADDNEW.' '.$lv.' '.EST_GEN_FEATURE.' '.EST_GEN_CATEGORY.'" data-zi="'.$zi.'" data-lk="'.$lk.'" data-mx="'.$mct.'">'.EST_GEN_NEW.'</button>
              </td>
            </tr>
            <tr>
              <td>
                <div id="estPresetsListCont-'.$zi.'-'.$lk.'" class="estPresetsListCont">
                  <div class="estPresetsListDiv">
                    <ul class="estPresetsListUL" data-nkey="['.$zi.']['.$lk.']">';
      
      $lict = 0;
      $dMx = (is_array($dtaset['feat'][$lk]) ? count($dtaset['feat'][$lk]) : 0);
      if($dMx > 0){
        $dCt = 0;
        foreach($dtaset['feat'][$lk] as $fk=>$fv){
          $dCt++;
          $text .= '
                      <li class="estPresetDataLI1 subListUL">
                        <input type="checkbox" name="featcat_keep['.$zi.']['.$lk.']['.$fk.']" value="1" checked="checked" />
                        <input type="hidden" name="featcat_name['.$zi.']['.$lk.']['.$fk.']" value="'.$tp->toFORM($fv['name']).'" />
                        <a data-inpt="featcat_name['.$zi.']['.$lk.']['.$fk.']" contenteditable="true">'.$tp->toHTML($fv['name']).'</a>
                        <ul class="estPresetListLI1ul" data-nkey="['.$zi.']['.$lk.']['.$fk.']">';
          
            if(is_array($fv['dta']) && count($fv['dta']) > 0){
              foreach($fv['dta'] as $dk=>$dv){
                if($dk > 0){
                  if(trim($dv['opts']) == ''){$dv['ele'] = intval(0);}
                  else{
                    $oi = 0;
                    $opts = explode(',',$dv['opts']);
                    if(count($opts)){
                      foreach($opts as $ok=>$ov){
                        if(trim($ov) !==''){
                          $OPTLI .= '
                              <li class="estPresetDataLI3"><input type="checkbox" checked="checked" /><a contenteditable="true">'.$ov.'</a></li>';
                          $oi++;
                          }
                        }
                      }
                    if($oi > 0){$dv['ele'] = 1;}
                    else{
                      $dv['ele'] = 0;
                      $dv['opts'] = '';
                      }
                    }
                  $text .= '
                          <li class="estPresetDataLI2">
                            <input type="checkbox" name="feature_keep['.$zi.']['.$lk.']['.$fk.']['.$dk.']" value="1" checked="checked" />
                            <input type="hidden" name="feature_name['.$zi.']['.$lk.']['.$fk.']['.$dk.']" value="'.$tp->toFORM($dv['name']).'" />
                            <input type="hidden" name="feature_ele['.$zi.']['.$lk.']['.$fk.']['.$dk.']" value="'.intval($dv['ele']).'" />
                            <input type="hidden" name="feature_opts['.$zi.']['.$lk.']['.$fk.']['.$dk.']" value="'.$tp->toFORM($dv['opts']).'" />
                            <a data-inpt="feature_name['.$zi.']['.$lk.']['.$fk.']['.$dk.']" contenteditable="true">'.$dv['name'].'</a>
                            <div class="estPresetsDataSw '.($dv['ele'] == 1 ? 'actv' : 'inact').'" title="'.EST_GEN_ENABLE.' '.$tp->toHTML($dv['name']).' '.EST_GEN_OPTIONS.'"><div></div></div>
                            <ul class="estPresetListLI2ul '.($dv['ele'] == 1 ? 'actv' : 'inact').'" data-nkey="['.$zi.']['.$lk.']['.$fk.']">'.$OPTLI.'
                              <button class="btn btn-primary btn-sm estNopt2"'.($dv['ele'] == 0 ? ' style="display:none;"' : '').'> + '.EST_OPTION.'</button>
                            </ul>
                          </li>';
                  }
                unset($OPTLI);
                }
              }
            
            $text .= '
                        <button class="btn btn-primary btn-sm estNopt1"> + '.$tp->toHTML($fv['name']).' '.EST_GEN_ITEM.'</button>
                      </ul>
                    </li>';
          
            $lict++;
            if($lict == $mct){
              $lict = 0;
              $text .= '
                    </ul>
                  </div>';
              if($dCt < $dMx){
                $text .='
                  <div class="estPresetsListDiv">
                    <ul class="estPresetsListUL">';
                }
              }
            }
          }
      
      if($lict !== 0){
            $text .= '
                    </ul>
                  </div>';
        }
      
      $text .= '
                </div>
              </td>
            </tr>';
      }
    
    $text .= '
          </tbody>
        </table>'.$NEWZPT;
    return $text;
    unset($ltd1,$text,$lk,$lv,$fk,$fv,$dk,$dv,$ok,$ov,$NEWZPT);
      
    }
  
  
  
  
  
  public function estContactTR($key,$idx,$CK,$CV,$contdta){
    $tp = e107::getParser();
    $dtaStr = $this->estDataStr($contdta);
    //$contfld = $this->estContactFldNames($CV);
    $text = '
            <tr class="estContList"'.$dtaStr.'>
              <td class="noPAD noBORD">
                <div class="contactD1"><input type="text" name="contact_key['.$key.']['.$idx.']"  value="'.$tp->toFORM($contdta['contact_key']).'" class="tbox form-control btn-primary contTypeTxt" max="25" style="display: none;" /><button class="btn btn-primary contType" title="'.EST_GEN_CLKCUSTOM.'">'.$tp->toHTML($contdta['contact_key']).'</button><input type="text" name="contact_data['.$key.']['.$idx.']" id="contact_data['.$key.']['.$idx.']'.'" value="'.$tp->toFORM($contdta['contact_data']).'" class="tbox form-control input-xlarge xlarge contData contact_data-'.$key.'-'.$CK.' ILBLK" max="100" placeholder="'.$tp->toHTML($contdta['contact_key']).'" /><button class="btn btn-default estContMove" title="'.EST_GEN_SORT.'"><i class="S16 e-sort-16"></i></button><button class="btn btn-default estContGo" title="'.EST_GEN_DELETE.'" style="color: rgb(204, 0, 0);"><i class="fa fa-close"></i></button></div>
              </td>
            </tr>';
    unset($dtaStr);
    return $text;
    }
  
  
  
  
  public function estContactForm($key,$id,$cust=0,$email=null){
    $sql = e107::getDB();
    $tp = e107::getParser();
    $frm = e107::getForm(false, true);
    
    if($id == 0){
      $MTX1 = array('','','','','',EST_ERR_AGYIDZERO,EST_ERR_AGENTIDZERO);
      $MTX2 = array('','','','','',EST_ERR_AGYIDZERO1,EST_ERR_AGENTIDZERO1);
      $errtxt[0] = $MTX1[$key];
      $errtxt[1] = $MTX2[$key].' '.EST_GEN_CONTACTS;
      unset($MTX1,$MTX2);
      }
    
    $CONTKEYS = $this->estGetContKeys();
    if(count($CONTKEYS) == 0){
      $errtxt[0] = EST_ERR_NOCONTKEYS;
      $errtxt[1] = EST_ERR_NOCONTKEYS1;
      }
    
    if($errtxt){
      return '
      <div class="s-message alert alert-block alert-dismissible fade in show info  alert-info" style="width: 96%; margin: 16px auto 0px auto;"><h4 class="s-message-title">'.$errtxt[0].'</h4><div class="s-message-body"><div class="s-message-item">'.$errtxt[1].'</div></div></div>';
      }
      
    
    
    $tblKeys = $this->estGetSectTlbKeys($key);
    $CONTARR = $this->estGetContDta($key,$id);
    
    $text = '
      <table id="est'.$tblKeys[3].'-contacts" class="table estContTabl estContInMain" data-idx="'.$id.'" data-key="'.$key.'">
        <tbody>';
    
    foreach($CONTKEYS as $CK=>$CV){
      foreach($CONTARR[$CV] as $idx=>$contdta){
        $text .= $this->estContactTR($key,$idx,$CK,$CV,$contdta);
        }
      }
    
    $text .= '
        </tbody>
        <tfoot>
          <tr class="estContList">
            <td class="noPADLR">
              <div class="contactD1"><input type="text" name="contact_key['.$key.'][0]" value="'.EST_GEN_NEW.'" class="tbox form-control btn-default contTypeTxt" style="display: none;" /><button class="btn btn-primary contType" title="'.EST_GEN_CLKCUSTOM.'">'.EST_GEN_NEW.'</button><input type="text" name="contact_data['.$key.'][0]" class="tbox form-control input-xlarge xlarge contData ILBLK" value="" /><button id="estNewContactBtn" class="btn btn-default estContGo" title="'.EST_GEN_SAVE.'" style="color: rgb(0, 204, 0);"><i class="fa fa-save"></i></button></div>
            </td>
          </tr>
        </tfoot>
			</table>';
    return $text;
      
    }
  
  
  
  
  public function estTemplateOrd($curVal){
    /*
    
    if(isset($GLOBALS['ESTATE_TEMPLATE']['view'][$tkey]['ord']) && count($GLOBALS['ESTATE_TEMPLATE']['view'][$tkey]['ord']) > 0){
      
      $sects = $GLOBALS['ESTATE_TEMPLATE']['view'][$tkey]['ord'];
      }
    else{
      $sects = array('slideshow'=>0,'summary'=>0,'spaces'=>0,'map'=>0,'comminuty'=>0,'next-prev'=>0,'gallery'=>0);
      }
    
    
    if(!$curVal || count($curVal) == 0){
      $curVal = array('slideshow'=>1,'summary'=>1,'spaces'=>1,'map'=>1,'comminuty'=>1,'next-prev'=>0,'gallery'=>1);
      }
    
    
    //sched_agt_times
        
    //$dtaStr = $this->estDataStr($contdta);
    $ret = '<div id="estViewOrderCont" class="estTemplateSectCont">';
    foreach($curVal as $k=>$v){
      if($k !== 'xx'){
        $ret .= '<div class="btn btn-default btn-sm TAL" value="'.$k.'" title="'.EST_GEN_REORDER.' '.EST_PREF_TEMPLATE_VIEWORD.'">
        <input type="checkbox" id="template-view-ord['.$k.']" name="template_view_ord['.$k.']" value="1" '.($v == 1 ? ' checked="checked"' : '').' title="'.EST_GEN_ENABLEDIS.' '.$k.'" />
        <label for="template-view-ord['.$k.']" title="'.EST_GEN_ENABLEDIS.' '.$k.'">'.$tp->toHTML($k).'*</label>
        </div>';
        }
      unset($sects[$k]);
      }
    $ret .= '</div>';
    
    //$curVal = e107::unserialize($curVal);
    $ret .= '<div>'.$curVal.'</div>';
    return $ret;
    */
    
    }
  
  private function getContSects(){
    
    }
  
  
  
  public function estGetSectTlbKeys($id=null){
    $tkeys = array(
      0=>array('estate_properties','prop_idx','prop_name',EST_GEN_PROPERTY),
      1=>array('estate_subdiv','subd_idx','subd_name',EST_GEN_SUBDIVISION),
      2=>array('estate_owners','owner_idx','owner_name',EST_GEN_SELLER),
      3=>array('estate_clients','client_idx','client_name',EST_GEN_CLIENT),
      4=>array('estate_clients','client_idx','client_name',EST_GEN_CLIENT),
      5=>array('estate_agencies','agency_idx','agency_name',EST_GEN_AGENCY),
      6=>array('estate_agents','agent_idx','agent_name',EST_GEN_AGENT)
      );
    if($id !== null){return $tkeys[$id];}
    else{return $tkeys;}
    }
  
  
  
  private function getUsrClassIds($mode=0){
    if($mode == 1){
      $RET = array();
      foreach($GLOBALS['EST_CLASSES'] as $k=>$v){array_push($RET,$v);}
      unset($k,$v);
      return implode(',',$RET);
      }
    else{
      $UCLS = $GLOBALS['EST_CLASSES'];
      foreach($UCLS as $k=>$v){$UCLS[$k] = e107::getUserClass()->ucGetClassIDFromName($k);}
      unset($k,$v);
      return $UCLS;
      }
    }
  
  
  
  
  public function getAllAgents(){
    $sql = e107::getDB();
    $ret = array();
    
    $ret = $this->estGetUsers();
    
    $TQRY = "
      SELECT #estate_agents.*, #estate_agencies.* 
      FROM #estate_agents
      LEFT JOIN #estate_agencies
      ON agent_agcy = agency_idx";
    
    if($sql->gen($TQRY)){
      while($rows = $sql->fetch()){
        $uid = intval($rows['agent_uid']);
        if($ret[$uid]){
          $ret[$uid]['agency_idx'] = intval($rows['agency_idx']);
          $ret[$uid]['agency_name'] = $rows['agency_name'];
          $ret[$uid]['agency_image'] = $rows['agency_image'];
          $ret[$uid]['agency_imgsrc'] = intval($rows['agency_imgsrc']);
          $ret[$uid]['agent_idx'] = intval($rows['agent_idx']);
          $ret[$uid]['agent_name'] = $rows['agent_name'];
          $ret[$uid]['agent_agcy'] = intval($rows['agent_agcy']);
          $ret[$uid]['agent_lev'] = intval($rows['agent_lev']);
          $ret[$uid]['agent_uid'] = $uid;
          $ret[$uid]['agent_image'] = $rows['agent_image'];
          $ret[$uid]['agent_imgsrc'] = intval($rows['agent_imgsrc']);
          $ret[$uid]['agent_txt1'] = $rows['agent_txt1'];
          }
        }
      }
    return $ret;
    }
  
  
  public function estAgentUserSelect($id=0){
    //defunct?
    return "DEFUNCT FUNCTION?";
    }
  
  
  
  private function estMsgTR($mv){
    $tp = e107::getParser();
    if(intval($mv['msg_read']) > 0){
      $Btn2ttl = 'title="'.EST_MSG_MARKUNREAD.'" data-ttl="'.EST_MSG_MARKREAD.'"';
      $BTN2Cls = 'fa fa-arrow-up';
      $BTNEnv = '<i class="fa fa-envelope-open"></i>';
      }
    else{
      $Btn2ttl = 'title="'.EST_MSG_MARKREAD.'" data-ttl="'.EST_MSG_MARKUNREAD.'"';
      $BTN2Cls = 'fa fa-check';
      $BTNEnv = '<i class="fa fa-envelope"></i>';
      }
      
    
    return '
      <tr class="estAdmMsgTr" data-idx="'.$mv['msg_idx'].'" data-pid="'.intval($mv['msg_propidx']).'" data-mode="'.intval($mv['msg_mode']).'" data-read="'.intval($mv['msg_read']).'" data-pmid="'.intval($mv['msg_pm']).'" data-del="0">
        <td class="left VAM">'.$tp->toDate($mv['msg_sent'],'long').'</td>
        <td class="left VAM">'.$tp->toHTML($mv['msg_from_name']).'</td>
        <td class="left VAM">
          <button class="btn btn-default estMsgBtnEmail" title="'.$tp->lanVars(EST_MSG_SENDEMAILTO, array('x'=>$mv['msg_from_name']),false).'" data-email="'.$tp->toEmail($mv['msg_from_addr']).'">'.$tp->toHTML($mv['msg_from_addr']).'</button>
        </td>
        <td class="left VAM estAdmMsgTr">
          <div class="estAdmMsgDiv" data-addcls="popover fade top in editable-container editable-popup estMsgAdmBlk">
            <div class="estMsgBtn" data-idx="'.intval($mv['msg_idx']).'" data-pid="'.intval($mv['msg_propidx']).'" data-mode="'.intval($mv['msg_mode']).'" data-read="'.intval($mv['msg_read']).'" data-pmid="'.intval($mv['msg_pm']).'" data-del="0">
              <button class="btn btn-default estMsgBtnBlock">
                <div class="estViewMsg TAL">'.$tp->toHTML($mv['msg_top']).'</div>
                <div class="estMarkMsg" '.$Btn2ttl.'><i class="'.$BTN2Cls.'"></i></div>
                <div class="estDelMsg" title="'.EST_MSG_DELETE.'" data-msg="'.EST_MSG_DELALERT.'"><i class="fa fa-trash-can"></i></div>
              </button>
              <div class="estMsgP">
                <h4>'.$tp->toHTML($mv['msg_top']).'</h4>
                <p>'.$tp->toHTML($mv['msg_text']).'</p>
                <div class="estInBoxHead">
                  <div>'.EST_MSG_RECD.': '.$tp->toDate($mv['msg_sent'],'long').'</div>
                  <div>'.EST_GEN_FROM.': '.$tp->toHTML($mv['msg_from_name']).' '.(intval($mv['msg_from_uid']) == 0 ? '('.EST_GEN_NONMEMBER.')' : '').' </div>
                  <div>'.EST_GEN_EMAIL.': '.$tp->toHTML($mv['msg_from_addr']).' </div>
                  <div>'.EST_GEN_PHONE.': '.$tp->toHTML($mv['msg_from_phone']).' </div>
                  <div>'.EST_MSG_PRIVATEMSG.': '.(intval($mv['msg_from_uid']) == 0 ? EST_GEN_NOT.' '.EST_GEN_AVAILABLE : (intval($mv['msg_pm']) == 1 ? EST_MSG_PMSENT : EST_MSG_PMNOTSENT)).' </div>
                </div>
              </div>
            </div>
          </div>
        </td>
      </tr>';
    unset($Btn2ttl,$BTN2Cls,$BTNEnv,$mv);
    }
  
  
  
  
  
  
  public function estAgentInbox(){
    $sql = e107::getDB();
    $tp = e107::getParser();
    //$EST_PREF = e107::pref('estate');
    $sql->gen("SELECT #estate_msg.* FROM #estate_msg WHERE msg_to_uid='".USERID."' ORDER BY msg_read ASC, msg_mode ASC, msg_sent DESC");
    
    $MSGS = array();
    $ix = array();
    while($rows = $sql->fetch()){
      $key = intval($rows['msg_mode']);
      $iu = (intval($rows['msg_read']) > 0 ? 2 : 1);
      $MSGS[$key][$iu][$ix[$key][$iu]] = $rows;
      $ix[$key][$iu]++;
      }
    
    $MSGTYPES = EST_MSGTYPES;
    $MSGTYPES = array_slice($MSGTYPES, 1, NULL, true);
    
    $TBS = array();
    $i = 0;
    foreach($MSGTYPES as $k=>$tabtxt){
      $mct = 0;
      
      $TBS[$i]['text'] = '
        <table id="estMsgTbl-new-'.$k.'" class="table adminlist table-striped estCustomTable1 WD100" style="margin-bottom:16px;">
          <colgroup></colgroup>
          <colgroup></colgroup>
          <colgroup></colgroup>
          <colgroup class="WD50"></colgroup>
          <thead>
            <tr>
              <th>'.EST_GEN_NEWMESSAGES.'</th>
              <th>'.EST_MSG_SENTBY.'</th>
              <th>'.EST_MSG_FROMEMAIL.'</th>
              <th>'.EST_MSG_SUBJECT.'</th>
            </tr>
          </thead>
          <tbody id="estMsgTB-new-'.$k.'">';
      
      //$tp->lanVars(EST_GEN_NOMESSAGESTXT1, array('x'=>$cv),false)
      
      if(isset($MSGS[$k][1])){
        $mct = count($MSGS[$k][1]);
        foreach($MSGS[$k][1] as $mk=>$mv){$TBS[$i]['text'] .= $this->estMsgTR($mv);}
        }
      
      $TBS[$i]['text'] .= '
          </tbody>
        </table>
        <table id="estMsgTbl-read-'.$k.'" class="table adminlist table-striped estCustomTable1 WD100">
          <colgroup></colgroup>
          <colgroup></colgroup>
          <colgroup></colgroup>
          <colgroup class="WD50"></colgroup>
          <thead>
            <tr>
              <th>'.EST_GEN_READMESSAGES.'</th>
              <th>'.EST_MSG_SENTBY.'</th>
              <th>'.EST_MSG_FROMEMAIL.'</th>
              <th>'.EST_MSG_SUBJECT.'</th>
            </tr>
          </thead>
          <tbody id="estMsgTB-read-'.$k.'">';
      
      if(isset($MSGS[$k][2])){
        foreach($MSGS[$k][2] as $mk=>$mv){$TBS[$i]['text'] .= $this->estMsgTR($mv);}
        }
        
      $TBS[$i]['text'] .= '
          </tbody>
        </table>';
      
      $TBS[$i]['caption'] = $tabtxt.' [<span class="estAdmMsgTabCts" data-targ="estMsgTB-new-'.$k.'">'.$mct.'</span>]';
      unset($mct);
      $i++;
      }
    
    return $TBS;
    }
  
  
  
  
  public function estContactUDB($tk,$DBIDX){
		if(intval($DBIDX) > 0){
      $sql = e107::getDB();
      $tp = e107::getParser();
      $msg = e107::getMessage();
      
      if($_POST['contact_key'][$tk]){
        $tx = $this->estGetSectTlbKeys($tk);
        $ti = 0;
        foreach($_POST['contact_key'][$tk] as $idx=>$contact_key){
          $ti++;
          $contact_key = strtoupper(trim($tp->toDB($contact_key)));
          $contact_data = trim($tp->toDB($_POST['contact_data'][$tk][$idx]));
          //$msg->addInfo('<div>'.$ti.'[IDX '.$idx.'] '.$contact_key.': '.$contact_data.'</div>');
          
          if($idx > 0){
            if($sql->update("estate_contacts","contact_key='$contact_key', contact_data='$contact_data' WHERE contact_idx='$idx' LIMIT 1")){
              $cmsg .= '<li>'.EST_GEN_UPDATED.' '.$tp->toHTML($contact_key).': '.$tp->toHTML($contact_data).'</li>';
              }
            }
          else{
            if($contact_data !== ''){
              if($sql->insert("estate_contacts","'0','".intval($tk)."','".intval($DBIDX)."','".$contact_key."','".$ti."','".$contact_data."'")){
                $cmsg .= '<li>'.EST_GEN_NEW.' '.$tp->toHTML($contact_key).': '.$tp->toHTML($contact_data).'</li>';
                $ti++;
                }
              else{
                $emsg .= '<li>'.EST_ERR_FAILADDNEW.' '.EST_GEN_CONTACT.': '.$tp->toHTML($contact_data).'</li>';
                }
              }
            }
          }
        
  
        $dberr = $sql->getLastErrorText();
        if($dberr){
          $msg->addError('<div>'.$dberr.'</div>');
          unset($dberr);
          }
        if($cmsg){
          $msg->addSuccess('<div>'.$tx[3].' '.EST_GEN_CONTACTS.'<ul>'.$cmsg.'</ul></div>');
          unset($cmsg);
          }
        
        if($emsg){
          $msg->addWarning('<div>'.$tx[3].' '.EST_GEN_CONTACTS.'<ul>'.$emsg.'</ul></div>');
          unset($emsg);
          }
        }
      }
    }
  
  public function estDBUp($tk,$PDTA){
    $sql = e107::getDB();
    $tp = e107::getParser();
    $msg = e107::getMessage();
    $tx = $this->estGetSectTlbKeys($tk);
    $DBIDX = 0;
    $TBLS = estTablStruct();
    $FLDS = array();
    $RES = array();
    
    foreach($TBLS[$tx[0]] as $fn=>$fld){
      if($fn == $tx[1]){
        $DBIDX = intval($PDTA[$fn]);
        $FLDS[$fn] = $DBIDX;
        }
      else{
        if($fld['str'] == 'int'){$FLDS[$fn] = intval($PDTA[$fn]);}
        else{$FLDS[$fn] = $tp->toDB($PDTA[$fn]);}
        }
      }
    
    if($DBIDX > 0){
      $FLDS['WHERE'] = $tx[1].'='.$DBIDX.' LIMIT 1';
      if($tk == 6){
        if(trim($FLDS['agent_image']) !== ''){
          $ORPH = estChkOrphanFiles('media/agent','agent-'.intval($DBIDX),(intval($FLDS['agent_imgsrc']) == 1 ? $FLDS['agent_image'] : ''));
          foreach($ORPH as $mk=>$mv){$msg->addInfo($mv);}
          }
        }
      elseif($tk == 5){
        if(trim($FLDS['agency_image']) !== ''){
          $ORPH = estChkOrphanFiles('media/agency','agency-'.intval($DBIDX),(intval($FLDS['agency_imgsrc']) == 1 ? $FLDS['agency_image'] : ''));
          foreach($ORPH as $mk=>$mv){$msg->addInfo($mv);}
          }
        }
      
      if($sql->update($tx[0], $FLDS)){$msg->addInfo('<div>'.EST_UPDATED.' '.$tx[3].' '.$tp->toHTML($FLDS[$tx[2]]).'</div>');}
      $FLDS[$tx[1]] = intval($DBIDX);
      unset($FLDS['WHERE']);
      }
    else{
      $DBIDX = $sql->insert($tx[0], $FLDS);
      if($DBIDX > 0){
        $FLDS[$tx[1]] = intval($DBIDX);
        $msg->addInfo('<div>'.EST_GEN_ADDEDNEW.' '.$tx[3].': '.$tp->toHTML($FLDS[$tx[2]]).'</div>');
        }
      }
    
    $RES = $FLDS;
    
    $dberr = $sql->getLastErrorText();
    if($dberr){
      $msg->addError('<div>'.$dberr.'</div>');
      $RES['error'] = true;
      unset($dberr);
      }
    else{
      $this->estContactUDB($tk,$DBIDX);
      if($DBIDX == 0){
        $msg->addError('<div>NO DB INDEX</div>');
        $RES['error'] = true;
        }
      }
    return $RES;
    }
  
  
  public function buildEventCal($PROPID,$CALSTART=null){
    $tp = e107::getParser();
    $sql = e107::getDB();
    $sql->gen('SELECT prop_agent,prop_datecreated,prop_dateprevw,prop_datelive,prop_datepull FROM #estate_properties WHERE prop_idx = '.intval($PROPID));
    $row = $sql->fetch();
    extract($row);
    
    $calDays = $this->getCalDays();
    
    $PROPSTART = mktime(0,0,0,date("m",$prop_datecreated),date("d",$prop_datecreated),date("Y",$prop_datecreated));
    
    $ESTTODAY = mktime(0,0,0, date("m"), date("d"), date("Y"));
    
    if($CALSTART === null){$CALSTART  = mktime(0, 0, 0, date("m"),1, date("Y"));}
    $PREVMONTH  = mktime(0, 0, 0, date("m",$CALSTART)-1,1, date("Y",$CALSTART));
    $NEXTMONTH  = mktime(0, 0, 0, date("m",$CALSTART)+1,1, date("Y",$CALSTART));
    $CALEND = mktime(0,0,0, date("m",$CALSTART), date("d",$CALSTART)+date('t',$CALSTART), date("Y",$CALSTART));
    
    $THISMONTH = $CALSTART;
    
    $MSDOW = date('w',$CALSTART);
    if($MSDOW > 0){$CALSTART = strtotime(date('Y-m-d', strtotime('-'.$MSDOW.' day', $CALSTART)));}
    
    $MEDOW = date('w',$CALEND);
    if($MEDOW !== 6){$CALEND = strtotime(date('Y-m-d', strtotime('+'.(7-$MEDOW).' day', $CALEND)));}
    
    $NEXTDAY = new DateTime(date("Y-m-d H:i:s",$CALSTART));
    
    $ESTDAYCT = intval(abs($CALSTART - $CALEND) / 86400);
    
    $text = $this->getCalTbl('start');
    $text .= $this->getCalTbl('head',array('curm'=>$THISMONTH,'nextm'=>$NEXTMONTH,'prevm'=>$PREVMONTH));
    $dta = array('id'=>'estEvtCaltb','class'=>'estCheckered','data-calstart'=>$CALSTART);
    $text .= $this->getCalTbl('body',$dta);
    
    
    $GRSQ = 1;
    for($i=0; $i < (ceil($ESTDAYCT / 7)); $i++){
      $CALACTD = 0;
      if(count($calDays) > 0){
        foreach($calDays as $k=>$v){
          $TSTDAYX = date_timestamp_get($NEXTDAY);
          if($GRSQ > date('w',$CALSTART) && $TSTDAYX < $CALEND){
            $CALTDCSS = 'estCalDay ';
            
            if($NEXTDAY->format('m') !== $MNTH){
              $MNTH = $NEXTDAY->format('m');
              }
            
            $MDAYN = $NEXTDAY->format('m/d');
            $YMD = $NEXTDAY->format('Ymd');
            
            
            if($TSTDAYX < $PROPSTART){
              $CALTDCSS .= 'estDead';
              }
            else{
              $CALTDCSS .= 'estLive'.($TSTDAYX == $ESTTODAY ? ' estToday' : '');
              $CALACTD++;
              
              }
            //$YMD
            $CALTD .= '
              <td id="estCalTD-'.$TSTDAYX.'" class="'.$CALTDCSS.'">
                <div class="estCalDayBox">
                  <div class="estCalDayNo">'.$MDAYN.'</div>
                  <div id="estCalDta-'.$TSTDAYX.'" class="estCalDtaCont" data-dno="'.$k.'" data-ud="'.$TSTDAYX.'"></div>
                </div>
              </td>';
            unset($CALDAYSTY,$MDAYN);
            $NEXTDAY->modify('+1 day');
            }
          else{
            $CALTD .= '<td class="estCalDay" data-grdsq="'.$GRSQ.'">&nbsp;</td>';
            unset($CALTDCSS,$MDAYN);
            }
          $GRSQ++;
          }
        }
      if($CALACTD > 0){
        $text .= '<tr data-trno="'.$i.'">'.$CALTD.'</tr>'; 
        }
      unset($CALTD,$CALACTD);
      }
    
    $text .= $this->getCalTbl('end',array('tag'=>'tbody'));
    unset($CALSTART,$CALEND,$THISMONTH,$PREVMONTH,$NEXTMONTH,$MSDOW,$MEDOW,$NEXTDAY,$ESTDAYCT);
    
    return $text;
    }
  
  
  
  
  function getCalDays(){
    return array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
    }
  
  function getCalFldTime($nam,$val,$toggl,$hint){
    return '<input type="time" name="'.$nam.'" class="estPrefInptTime" value="'.$val.'"'.($hint ? ' title="'.$hint.'"' : '' ).($toggl == 1? '' : ' disabled="disabled"').'/>';
    }
  
  function getCalFldTog($nam,$labl,$val){
    return '<input type="hidden" name="'.$nam.'" id="'.$nam.'" value="'.$val.'" /><button class="estPrefCalActDay btn '.($val == 1 ? 'btn-primary' : 'btn-default').' btn-sm"'.($hint ? ' title="'.$hint.'"' : '' ).' data-for="'.$nam.'">'.$labl.'</button>';
    }
  
  
  
  function getCalTbl($sect,$dta=null){
    $calDays = $this->getCalDays();
    
    if($sect == 'start'){
      $TBL = '<table class="estCalTbl">';
      if(count($calDays) > 0){foreach($calDays as $k=>$v){$TBL .= '<colgroup></colgroup>';}}
      return $TBL;
      }
    elseif($sect == 'head'){
      if(count($calDays) > 0){
        if($dta !== null){
          if($dta['prevm']){
            $CALNAV .= '<button class="btn btn-default estNoRBord" data-month="'.$dta['prevm'].'">'.date('F',$dta['prevm']).'</button>';
            }
          
          $CALNAV .= '<button class="btn btn-default estNoLRBord" data-month="'.$dta['curm'].'">'.date('F Y',$dta['curm']).'</button>';
          
          if($dta['nextm']){
            $CALNAV .= '<button class="btn btn-default estNoLBord" data-month="'.$dta['nextm'].'">'.date('F',$dta['nextm']).'</button>';
            }
          $CALNAV .= 
          $txt .= '<tr><th colspan="7"><div id="estEvtNavBar">'.$CALNAV.'</div></th></tr>';
          }
        $txt .= '<tr>';
        foreach($calDays as $k=>$v){$txt .= '<th>'.$v.'</th>';}
        $txt .= '</tr>';
        }
      return '<thead>'.$txt.'</thead>';
      }
    elseif($sect == 'body'){
      if($dta !== null && is_array($dta) && count($dta) > 0){foreach($dta as $k=>$v){$tb .= ' '.$k.'="'.$v.'"';}}
      return '<tbody'.$tb.'>';
      }
    elseif($sect == 'end'){
      if($dta['tag']){return '</'.$dta['tag'].'></table>';}
      else{return '</table>';}
      }
    elseif($sect == 'tr'){
      if(count($calDays) > 0){
        foreach($calDays as $k=>$v){
          $TBL .= '<td class="estCalDay">';
          if($dta['deftime']){
            $tm = $dta['deftime'];
            $TBL .= $this->getCalFldTog($tm['n'].'['.$k.'][0]',$tm['l'],intval($tm['v'][$k][0]));
            $TBL .= $this->getCalFldTime($tm['n'].'['.$k.'][1]',($tm['v'][$k][1] ? $tm['v'][$k][1] : '09:00'),intval($tm['v'][$k][0]),$tm['h'][0]);
            $TBL .= $this->getCalFldTime($tm['n'].'['.$k.'][2]',($tm['v'][$k][2] ? $tm['v'][$k][2] : '17:00'),intval($tm['v'][$k][0]),$tm['h'][1]);
            }
          $TBL .= '</td>';
          }
        }
      return '<tr>'.$TBL.'</tr>';
      }
    }
  
  
  
  
  
  
  public function estPropFormEle($name,$atr,$value,$DTA){}
  
  private function estOAHidden($FN,$DTA){
    $FID = str_replace("_","-",$FN);
    return '<input type="hidden" id="'.$FID.'" name="'.$FN.'" value="'.$DTA[$FN].'" />';
    }
  
  
  
  public function estOAFormTabs(){
    return array(
      0 => array('caption' => EST_GEN_LISTING,'text'=>'','cg'=>2),
      1 => array('caption' => EST_GEN_ADDRESS,'text'=>'','cg'=>3),
      2 => array('caption' => EST_GEN_COMMUNITY,'text'=>'','cg'=>2),
      3 => array('caption' => EST_GEN_SPACES,'text'=>'','cg'=>2),
      4 => array('caption' => EST_GEN_DETAILS,'text'=>'','cg'=>2),
      5 => array('caption' => EST_GEN_GALLERY,'text'=>'','cg'=>2),
      6 => array('caption' => EST_GEN_SCHEDULING,'text'=>'','cg'=>2)
      );
    
    }
  
  
  public function estOAFormTable($SN,$DTA,$CG=2){
    $tp = e107::getParser();
    $EST_PREF = e107::pref('estate');
    switch($SN){
      case 6 :
        $text = $this->estOAFormTableStart($SN);
        $text .= $this->estOAFormTR('prop_timezone','prop_timezone',$DTA);
        $text .= $this->estOAFormTR('datetime','prop_dateprevw',$DTA);
        $text .= $this->estOAFormTR('datetime','prop_datetease',$DTA);
        $text .= $this->estOAFormTR('datetime','prop_datelive',$DTA);
        $text .= $this->estOAFormTR('datetime','prop_datepull',$DTA);
        $text .= $this->estOAFormTR('prop_hours','prop_hours',$DTA);
        $text .= $this->estOAFormTR('div','estEventsCont',$DTA);
        $text .= $this->estOAFormTableEnd($SN,$DTA);
        break;
        
      case 5 :
        return $this->estGalleryForm();
        break;
        
      case 4 :
        $text = $this->estOAFormTableStart($SN);
        $text .= $this->estOAFormTR('text','prop_flag',$DTA);
        $text .= $this->estOAFormTR('text','prop_summary',$DTA);
        $text .= $this->estOAFormTR('textarea','prop_description',$DTA);
        $text .= $this->estOAFormTR('number','prop_bedtot',$DTA);
        $text .= $this->estOAFormTR('number','prop_bathtot',$DTA);
        $text .= $this->estOAFormTR('txtcntr','prop_features',$DTA);
        $text .= $this->estOAFormTR('text','prop_modelname',$DTA);
        $text .= $this->estOAFormTR('text','prop_condit',$DTA);
        $text .= $this->estOAFormTR('number','prop_yearbuilt',$DTA);
        $text .= $this->estOAFormTR('number','prop_floorct',$DTA);
        $text .= $this->estOAFormTR('prop_floorno','prop_floorno',$DTA);
        $text .= $this->estOAFormTR('number','prop_intsize',$DTA);
        $text .= $this->estOAFormTR('number','prop_roofsize',$DTA);
        $text .= $this->estOAFormTR('text','prop_landsize',$DTA);
        $text .= $this->estOAFormTableEnd($SN,$DTA);
        $text .= $this->estOAHidden('prop_dimu1',$DTA);
        $text .= $this->estOAHidden('prop_dimu2',$DTA);
        break;
        
      case 3 :
        /*
            <table class="estOATable1" style="width:100%">
              <colgroup></colgroup>
              <colgroup></colgroup>
              <tbody>';
        $text .= '
              </tbody>
            </table>
        */
        $text = '
        <div class="estOABlock">
          <h3><div>'.$tp->toHTML(EST_GEN_SPACES).'</div></h3>
          <div class="estOATabCont">
            <div id="estSpaceGrpDiv" class="estSpaceGrpDiv"></div>
          </div>
        </div>';
        return $text;
        break;
        
      case 2 :
        $text = $this->estOAFormTableStart($SN);
        $text .= $this->estOAFormTR('select','prop_subdiv',$DTA);
        $text .= $this->estOAFormTR('text','prop_hoafee',$DTA);
        $text .= $this->estOAFormTR('switch','prop_hoaland',$DTA);
        $text .= $this->estOAFormTR('text','prop_landfee',$DTA);
        //$text .= $this->estOAFormTR('div','estCommunityPreviewCont',$DTA);
        $text .= $this->estOAFormTableEnd($SN,$DTA);
        $text .= $this->estOAHidden('prop_hoareq',$DTA);
        $text .= $this->estOAHidden('prop_hoafrq',$DTA);
        $text .= $this->estOAHidden('prop_hoaappr',$DTA);
        $text .= '<div class="WD100"><h4>'.EST_GEN_COMMUNITYPREVIEW.'</h4></div><div id="estCommunityPreviewCont"></div>';
        break;
        
      case 1 :
        $text = $this->estOAFormTableStart($SN);
        $text .= $this->estOAFormTR('text','prop_addr1',$DTA);
        $text .= $this->estOAFormTR('text','prop_addr2',$DTA);
        $text .= $this->estOAFormTR('select','prop_country',$DTA);
        $text .= $this->estOAFormTR('select','prop_state',$DTA);
        $text .= $this->estOAFormTR('select','prop_county',$DTA);
        $text .= $this->estOAFormTR('select','prop_city',$DTA);
        $text .= $this->estOAFormTR('select','prop_zip',$DTA);
        $text .= $this->estMap('prop',$DTA['prop_addr_lookup'],$DTA['prop_lat'],$DTA['prop_lon'],$DTA['prop_geoarea'],$DTA['prop_zoom']);
        $text .= $this->estOAFormTableEnd($SN,$DTA);
        break;
        
      case 0 :
        $text = $this->estOAFormTableStart($SN);
        $text .= $this->estOAFormTR('prop_appr','prop_appr',$DTA);
        $text .= $this->estOAFormTR('text','prop_name',$DTA);//$ATTR,$SRC);
        $text .= $this->estOAFormTR('select','prop_status',$DTA);
        $text .= $this->estOAFormTR('select','prop_listype',$DTA);
        $text .= $this->estOAFormTR('text','prop_origprice',$DTA);
        $text .= $this->estOAFormTR('text','prop_listprice',$DTA);
        $text .= $this->estOAFormTR('select','prop_leasedur',$DTA);
        $text .= $this->estOAFormTR('select','prop_zoning',$DTA);
        $text .= $this->estOAFormTR('select','prop_type',$DTA);
        $text .= $this->estOAFormTR('text','prop_mlsno',$DTA);
        $text .= $this->estOAFormTR('text','prop_parcelid',$DTA);
        $text .= $this->estOAFormTR('text','prop_lotid',$DTA);
        $text .= $this->estOAFormTableEnd($SN,$DTA);
        $text .= $this->estOAHidden('prop_currency',$DTA);
        $text .= $this->estOAHidden('prop_leasefreq',$DTA);
        $text .= $this->estOAHidden('prop_landfreq',$DTA);
        //prop_uidcreate
        
        break;
      }
    
    return $text;
    
    }
  
  
  private function estOALabels($FLD){
    $TXT = array(
      'seller_namex'=>array('labl'=>EST_GEN_AGENT.' '.EST_GEN_NAME,'cls'=>'WD95'),
      'prop_name'=>array('labl'=>EST_GEN_PROPERTY.' '.EST_GEN_NAME,'cls'=>'WD95','hlp'=>EST_PROP_NAMEHLP),
      'prop_status'=>array('labl'=>EST_GEN_STATUS,'cls'=>'WD45','hlp'=>EST_PROP_STATUSHLP),
      'prop_zoning'=>array('labl'=>EST_PROP_LISTZONE,'cls'=>'WD45','hlp'=>EST_PROP_ZONEHLP),
      'prop_type'=>array('labl'=>EST_PROP_TYPE,'cls'=>'WD45','hlp'=>EST_PROP_TYPEHLP),
      'prop_listype'=>array('labl'=>EST_PROP_LISTYPE,'cls'=>'WD45','hlp'=>EST_PROP_LISTYPE),
      'prop_origprice'=>array('labl'=>EST_PROP_ORIGPRICE,'cls'=>'WD144px estNoLeftBord','hlp'=>EST_PROP_ORIGPRICEHLP),
      'prop_listprice'=>array('labl'=>EST_PROP_LISTPRICE,'cls'=>'WD144px estNoLeftBord','hlp'=>EST_PROP_LISTPRICEHLP),
      'prop_leasedur'=>array('labl'=>EST_PROP_LEASEDUR,'cls'=>'WD45'),
      'prop_mlsno'=>array('labl'=>EST_PROP_MLSNO,'hlp'=>EST_PROP_MLSNOHLP),
      'prop_parcelid'=>array('labl'=>EST_PROP_PARCELID,'hlp'=>EST_PROP_PARCELIDHLP),
      'prop_lotid'=>array('labl'=>EST_PROP_LOTID,'hlp'=>EST_PROP_LOTIDHLP),
      'prop_addr1'=>array('labl'=>EST_PROP_ADDR1,'cs'=>2,'cls'=>'estPropAddr','plch'=>EST_PLCH96),
      'prop_addr2'=>array('labl'=>EST_PROP_ADDR2,'cs'=>2,'cls'=>'estPropAddr','plch'=>EST_PLCH96A),
      'prop_country'=>array('labl'=>EST_PROP_COUNTRY,'cs'=>2,'cls'=>'estPropAddr WD45','hlp'=>EST_PROP_COUNTRYHLP),
      'prop_state'=>array('labl'=>EST_PROP_STATE,'cs'=>2,'cls'=>'estPropAddr WD45','hlp'=>EST_PROP_STATEHLP),
      'prop_county'=>array('labl'=>EST_PROP_COUNTY,'cs'=>2,'cls'=>'estPropAddr WD45','hlp'=>EST_PROP_COUNTYHLP),
      'prop_city'=>array('labl'=>EST_PROP_CITY,'cs'=>2,'cls'=>'estPropAddr WD45','hlp'=>EST_PROP_CITYHLP),
      'prop_zip'=>array('labl'=>EST_PROP_POSTCODE,'cs'=>2,'cls'=>'estPropAddr WD144px','hlp'=>EST_PROP_POSTCODEHLP),
      'prop_timezone'=>array('labl'=>EST_GEN_TIMEZONE,'hlp'=>EST_PROP_TIMEZONEHLP),
      'prop_subdiv'=>array('labl'=>EST_GEN_SUBDIVISION,'cls'=>'WD45','hlp'=>EST_PROP_SUBDIVHLP),
      'prop_hoafee'=>array('labl'=>EST_PROP_HOAFEES,'cls'=>'FL estNoRightBord WD144px','hlp'=>EST_PROP_HOAFEESHLP),
      'prop_hoaland'=>array('labl'=>EST_PROP_HOALAND,'hlp'=>EST_PROP_HOALANDHLP),
      'prop_landfee'=>array('labl'=>EST_PROP_LANDLEASE,'cls'=>'FL estNoRightBord WD144px','hlp'=>EST_PROP_LANDLEASEHLP),
      //'prop_landfreq'=>array('labl'=>EST_PROP_HOAFRQ,'hlp'=>EST_PROP_HOAFRQHLP),
      'prop_flag'=>array('labl'=>EST_PROP_FLAG,'plch'=>EST_PROP_FLAGPLCHLDR,'hlp'=>EST_PROP_FLAGHLP),
      'prop_summary'=>array('labl'=>LAN_SUMMARY,'hlp'=>EST_PROP_SUMMARYHLP),
      'prop_description'=>array('labl'=>LAN_DESCRIPTION,'hlp'=>EST_PROP_DESCRIPTIONHLP),
      'prop_modelname'=>array('labl'=>EST_GEN_MODELNAME,'hlp'=>EST_PROP_MODELNAMEHLP),
      'prop_features'=>array('labl'=>EST_GEN_FEATURES,'cls'=>'estJSmaxchar','plch'=>EST_PROP_FEATURESPLCHLDR,'hlp'=>EST_PROP_FEATURESHLP),
      'prop_condit'=>array('labl'=>EST_GEN_CONDITION),
      'prop_yearbuilt'=>array('labl'=>EST_PROP_YEARBUILT,'cls'=>'WD144px'),
      'prop_floorct'=>array('labl'=>EST_GEN_FLOORCT,'cls'=>'WD144px'),
      'prop_floorno'=>array('labl'=>EST_GEN_COMPLEX,'cls'=>'WD144px'),
      'prop_intsize'=>array('labl'=>EST_PROP_INTSIZE,'cls'=>'WD144px FL','hlp'=>EST_PROP_INTSIZEHLP),
      'prop_roofsize'=>array('labl'=>EST_PROP_ROOFSIZE,'cls'=>'WD144px FL','hlp'=>EST_PROP_ROOFSIZEHLP),
      'prop_landsize'=>array('labl'=>EST_PROP_LANDSIZE,'cls'=>'WD144px FL','hlp'=>EST_PROP_LANDSIZEHLP),
      'prop_hours'=>array('labl'=>EST_PROP_HRS,'hlp'=>EST_PROP_HRSHLP),
      'prop_datetease'=>array('labl'=>EST_PROP_DATETEASE,'hlp'=>EST_PROP_DATETEASEHLP),
      'prop_dateprevw'=>array('labl'=>EST_PROP_DATEPREVW,'hlp'=>EST_PROP_DATEPREVWHLP),
      'prop_datelive'=>array('labl'=>EST_PROP_DATELIVE,'hlp'=>EST_PROP_DATELIVEHLP),
      'prop_datepull'=>array('labl'=>EST_PROP_DATEPULL,'hlp'=>EST_PROP_DATEPULLHLP),
      'estEventsCont'=>array('cs'=>2),
      'estCommunityPreviewCont'=>array('labl'=>EST_GEN_COMMUNITYPREVIEW,'td1c'=>'VAT'),
      );
    return $TXT[$FLD];
    }
  
  
  public function estOAFormTR($TYPE,$FLD,$DTA,$options=null,$OPTARR=array()){
    $pref = e107::pref();//'estate'
    $EST_PREF = e107::pref('estate');
    $tp = e107::getParser();
    $frm = e107::getForm(false, true);
    $sql = e107::getDB();
    
    //`prop_landfee` decimal(10,2) unsigned NOT NULL,
    $SERL = array('prop_hours');
    $INTS = array('prop_listype','prop_state','prop_county','prop_city','prop_subdiv','prop_status','prop_zoom','prop_yearbuilt','prop_dimu1','prop_intsize','prop_roofsize','prop_dimu2','prop_zoning','prop_type','prop_listprice','prop_origprice','prop_leasefreq','prop_leasedur','prop_currency','prop_hoafee','prop_hoaland','prop_hoaappr','prop_hoareq','prop_hoafrq','prop_bathtot','prop_bathmain','prop_bathhalf','prop_bathfull','prop_bedtot','prop_bedmain','prop_floorct','prop_floorno','prop_bldguc','prop_complxuc');
    
    $LABS = $this->estOALabels($FLD);
    $CLS1 = (isset($LABS['td1c']) ? ' class="'.$LABS['td1c'].'"' : '');
    
    if($LABS['hlp']){
      $INFICO = $frm->help($LABS['hlp']);//$tp->toHTML()
      }
    
    $text = '<tr><td'.$CLS1.'>'.$INFICO.$tp->toHTML($LABS['labl']).'</td><td'.($LABS['cs'] ? ' colspan="'.$LABS['cs'].'"': '').'>';
    
    if(in_array($FLD,$SERL)){$FVALUE = e107::unserialize($DTA[$FLD]);}
    elseif(in_array($FLD,$INTS)){$FVALUE = intval($DTA[$FLD]);}
    else{$FVALUE = $DTA[$FLD];}
    
    $OPTARR = array();
    
    
    switch($FLD){
      case 'prop_bedtot' :
        $options = array('size'=>'small','min'=>'0','step'=>'1');
        return '
        <tr>
          <td>'.EST_GEN_BEDROOMS.'</td>
          <td>
            <div class="ILMINI">'.EST_GEN_TOTAL.$frm->number('prop_bedtot', intval($DTA['prop_bedtot']), 0, $options).'</div>
            <div class="ILMINI">'.EST_GEN_MAINLEV.$frm->number('prop_bedmain', intval($DTA['prop_bedmain']), 0, $options).'</div>
          </td>
        </tr>';
        break;
      
      case 'prop_bathtot' :
        $options = array('size'=>'small','min'=>'0','step'=>'1');
        return '
        <tr>
          <td>'.EST_GEN_BATHROOMS.'</td>
          <td>
            <div class="ILMINI">'.EST_GEN_FULL.$frm->number('prop_bathfull', intval($DTA['prop_bathfull']), 0, $options).'</div>
            <div class="ILMINI">'.EST_GEN_HALF.$frm->number('prop_bathhalf', intval($DTA['prop_bathhalf']), 0, $options).'</div>
            <div class="ILMINI">'.EST_GEN_TOTAL.$frm->number('prop_bathtot', intval($DTA['prop_bathtot']), 0, $options).'</div>
            <div class="ILMINI">'.EST_GEN_MAINLEV.$frm->number('prop_bathmain', intval($DTA['prop_bathmain']), 0, $options).'</div>
          </td>
        </tr>';
        break;
      
      
      case 'prop_status' :
        foreach($GLOBALS['EST_PROPSTATUS'] as $k=>$v){$OPTARR[$k] = $v['opt'];}
        break;
        
      case 'prop_leasedur' :
        $OPTARR = $GLOBALS['EST_LEASEDUR'];
        //foreach($GLOBALS['EST_LEASEDUR'] as $k=>$v){$OPTARR[$k] = $v['opt'];}
        break;
        
      case 'prop_country' :
        $OPTARR = $frm->getCountry();
        break;
        
      case 'prop_listype' :
        $OPTARR = $GLOBALS['EST_LISTTYPE1'];
        break;
        
      case 'prop_type' :
        $dbRow = $sql->retrieve('estate_listypes', '*', '',true);
        if(count($dbRow)){foreach($dbRow as $k=>$v){$OPTARR[$v['listype_idx']] = $v['listype_name'];}}
        unset($dbRow);
        break;
      
      case 'prop_zoning' ;
        $dbRow = $sql->retrieve('estate_zoning', '*', '',true);
        if(count($dbRow)){foreach($dbRow as $k=>$v){$OPTARR[$v['zoning_idx']] = $v['zoning_name'];}}
        unset($dbRow);
        break;
      
      case 'prop_state' :
        $dbRow = $sql->retrieve('estate_states', '*', 'state_country="'.$DTA['prop_country'].'"',true);
        if(count($dbRow)){foreach($dbRow as $k=>$v){$OPTARR[$v['state_idx']] = $v['state_name'];}}
        unset($dbRow);
        break;
      
      case 'prop_county' :
        $dbRow = $sql->retrieve('estate_county', '*', 'cnty_state="'.$DTA['prop_state'].'"',true);
        if(count($dbRow)){foreach($dbRow as $k=>$v){$OPTARR[$v['cnty_idx']] = $v['cnty_name'];}}
        unset($dbRow);
        break;
      
      case 'prop_city' :
        $dbRow = $sql->retrieve('estate_city', '*', 'city_county="'.$DTA['prop_county'].'"',true);
        if(count($dbRow)){foreach($dbRow as $k=>$v){$OPTARR[$v['city_idx']] = $v['city_name'];}}
        unset($dbRow);
        break;
      
      
      case 'prop_zip' :
        if(intval($DTA[$FLD]) !== 0){
          if($sql->gen('SELECT city_zip FROM #estate_city WHERE FIND_IN_SET('.intval($DTA[$FLD]).',city_zip) > 0 LIMIT 1')){
            $OPTARR = explode(',',$sql->fetch()['city_zip']);
            }
          }
        break;
      }
    
    
    
    if($LABS['cls']){
      $options['class'] .= (trim($options['class']) == '' ? 'tbox form-control' : '').' '.$LABS['cls'];
      }
    if($LABS['plch']){
      $options['placeholder'] = $LABS['plch'];
      }
    
    
    switch($TYPE){
      
      case 'prop_appr' :
        
        $styl = '';
        
        if(intval($DTA['prop_uidcreate']) !== USERID && EST_USERPERM >= intval($EST_PREF['public_mod'])){
          $txt1 = '<select name="prop_appr" value="'.intval($FVALUE).'">';
          $txt1 .= '<option value="0"'.(intval($FVALUE) == 0 ? ' selected="selected"' : '').'>'.EST_GEN_NOT.' '.EST_GEN_APPROVED.'</option>';
          $txt1 .= '<option value="'.USERID.'"'.(intval($FVALUE) > 0 ? ' selected="selected"' : '').'>'.EST_GEN_APPROVED.'</option>';
          $txt1 .= '</select><input type="hidden" id="estEmailNoSend" name="estEmailNoSend" value="1" />';
          }
        else{
          if(EST_USERPERM > 0 || check_class($EST_PREF['public_apr'])){
            $styl = ' style="display:none;"';
            $txt1 = '<input type="hidden" id="estEmailNoSend" name="estEmailNoSend" value="1" />';
            }
          elseif(intval($FVALUE) > 0){
            $txt1 = EST_GEN_APPROVED;
            }
          else{
            $txt1 = EST_GEN_NOT.' '.EST_GEN_APPROVED;
            if(isset($_POST['prop_appr'])){$txt1 .= '<input type="hidden" id="estEmailNoSend" name="estEmailNoSend" value="1" />';}
            }
          }
        
        return '<tr'.$styl.'><td class="VAM">'.$tp->toHTML(EST_GEN_LISTING.' '.EST_GEN_APPROVAL).'</td><td>'.$txt1.'</td></tr>';
        break;
        
      case 'nofld' :
        return '<tr><td class="VAM">'.$tp->toHTML($LABS['labl']).'</td><td><div style="margin:8px auto; font-weight:bold;">'.$tp->toHTML($FVALUE).'</div></td><td>';
        break;
      case 'div' :
        if($LABS['cs']){
          return '<tr><td colspan="'.$LABS['cs'].'"><div id="'.$FLD.'"></div></td></tr>';
          }
        else{$text .= '<div id="'.$FLD.'"></div>';}
        break;
      
      case 'prop_timezone' :
        $timeZones = systemTimeZones();
        $text .= $frm->select('prop_timezone', $timeZones, vartrue($tp->toFORM($FVALUE), $pref['timezone']),'size=xlarge');
        unset($timeZones);
        break;
      
      case 'prop_floorno' :
        $text .= '
        <div id="propUnitCont" class="estInptCont">
          <div class="ILMINI">'.EST_GEN_FLOORNO.'
            <input type="number" name="prop_floorno" value="'.intval($DTA['prop_floorno']).'" min="0" step="1" id="prop-floorno" class="tbox number e-spinner input-small form-control ui-state-valid WD144px" pattern="^[0-9]*" data-original-title="" title="">
          </div>
          <div class="ILMINI">'.EST_GEN_UNITSBLDG.'
            <input type="number" name="prop_bldguc" value="'.intval($DTA['prop_bldguc']).'" min="0" step="1" id="prop-bldguc" class="tbox number e-spinner input-small form-control ui-state-valid WD144px" pattern="^[0-9]*" data-original-title="" title="">
          </div>
          <div class="ILMINI">'.EST_GEN_UNITSCOMPLX.'
            <input type="number" name="prop_complxuc" value="'.intval($DTA['prop_complxuc']).'" min="0" step="1" id="prop-complxuc" class="tbox number e-spinner input-small form-control ui-state-valid WD144px" pattern="^[0-9]*" data-original-title="" title="">
          </div>
        </div>';
        
        break;
      
      
      case 'prop_hours' :
        $text = '<tr><td class="VAT">'.$INFICO.$tp->toHTML($LABS['labl']).'</td><td>';
        $text .= $this->estPropHoursForm($FVALUE);
        break;
      
      case 'txtcntr' :
        $text = '<tr><td class="VAT">'.$INFICO.$tp->toHTML($LABS['labl']).'</td><td'.($LABS['cs'] ? ' colspan="'.$LABS['cs'].'"': '').'>';
        $text .= $frm->textarea($FLD,$tp->toFORM($FVALUE),1,40,$options,1);
        break;
      
      
      case 'switch' :
        //$options
        $labels = array('on'=>LAN_YES,'off'=>LAN_NO);
        $text .= $frm->flipswitch($FLD,$tp->toFORM($FVALUE),$labels,$options);
        break;
      
      case 'select' :
        if($LABS['wrap']){$text .= '<div class="estInptCont">';}
        $text .= $frm->select($FLD,$OPTARR,$FVALUE,$options);
        if($LABS['wrap']){$text .= '</div>';}
        break;
      
      case 'datetime' :
        $text .= $frm->datepicker($FLD,$tp->toFORM($FVALUE),array('size'=>'small WD256px','mode'=>'datetime'));
        break;
        
      case 'number' :
        //$options = array('size'=>'small');
        $maxlength = 200;
        if($LABS['wrap']){$text .= '<div class="estInptCont">';}
        $text .= $frm->number($FLD, intval($FVALUE), $maxlength, $options);
        if($LABS['wrap']){$text .= '</div>';}
        break;
        
      case 'textarea' :
        $text = '<tr><td class="VAT">'.$INFICO.$tp->toHTML($LABS['labl']).'</td><td'.($LABS['cs'] ? ' colspan="'.$LABS['cs'].'"': '').'>';
        $text .= $frm->textarea($FLD,$tp->toFORM($FVALUE),4,40,$options); //,$counter = true: add character counter
        break;
        
      default :
        if($LABS['wrap']){$text .= '<div class="estInptCont">';}
        $text .= $frm->text($FLD,$tp->toTEXT($FVALUE), varset($ATTR['f']['max'],255),$options);
        if($LABS['wrap']){$text .= '</div>';}
        //'required'=>varset($ATTR['f']['req'],0)
        break;
      }
    
    unset($INFICO,$OPTARR,$LABS,$options);
    return $text.'</td></tr>';
    }
  
  //getCalTbl
  
  public function estPropHoursForm($DTA){
    if(!$DTA || count($DTA) == 0){$DTA = $GLOBALS['EST_PREF']['sched_pub_times'];}
    $text = $this->getCalTbl('start');
    $text .= $this->getCalTbl('head');
    $text .= '<tbody>';
    $text .= $this->getCalTbl('tr',array('deftime'=>array('n'=>'prop_hours','v'=>$DTA,'l'=>EST_GEN_AVAILABLE)));
    $text .= '</tbody>';
    $text .= $this->getCalTbl('end');
    return $text;
    }
  
  
  
  
  private function estOAFormTableStart($SN){
    $tp = e107::getParser();
    $TBS = $this->estOAFormTabs();
    for($i = 0;$i < $TBS[$SN]['cg']; $i++){$CGRP .= '<colgroup></colgroup>';}
    // table-striped
    return '
    <div class="estOABlock">
      <h3><div>'.$tp->toHTML($TBS[$SN]['caption']).'</div></h3>
      <div class="estOATabCont">
        <table class="estOATable1" style="width:100%">
          '.$CGRP.'
          <tbody>';
    }
  
  private function estOAFormTableEnd($SN,$DTA){
    $tp = e107::getParser();
    $TBS = $this->estOAFormTabs();
    return '
          </tbody>
          <tfoot>
            <tr>
              <td colspan="'.$TBS[$SN]['cg'].'">
                <div class="buttons-bar center">
                  <input type="submit" name="estSubmit-'.$SN.'" class="btn btn-primary estOASubmit" value="'.($DTA['prop_idx'] > 0 ? EST_GEN_UPDATE : EST_GEN_SAVE).'" />
                </div>
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>';
    }
  
  
  private function estGalleryForm(){
    $tp = e107::getParser();
    return '
    <div class="estOABlock">
      <h3><div>'.$tp->toHTML(EST_GEN_GALLERY).'</div></h3>
      <div class="estOATabCont">
        <div id="estNoGalWarn" class="s-message alert alert-block warning alert-warning">'.EST_PROP_MSG_NEEDSAVE.' '.EST_PROP_MSG_ADDMEDIA.'</div>
        <div class="WD100">
          <table id="estate-gallery-tabl" class="table-striped">
            <thead>
              <tr id="estGalleryH1">
                <th>
                  <div id="estGalFileSlipCont">
                    <label id="fileSlip" for="upFile">
                      <button id="fileSlipBtn" class="btn btn-primary btn-sm FR">'.EST_UPLOAD.' '.EST_MEDIA.'</button>
                    </label>
                  </div>
                '.EST_MEDIAAVAILABLE.'
                </th>
              </tr>
              <tr id="estGalleryH2"><td><div class="estBeltLoop"><div id="estGalleryBelt" class="estBelt estGalCont"></div></div></td></tr>
              <tr id="estGalleryH3"><th>'.EST_MEDIAINUSE.'</th></tr>
            </thead>
            <tbody>
              <tr id="estGalleryH4"><td><div id="estGalleryUsed" class="estGalCont"></div></td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>';
    }
  
  
  
  public function estCropImg($THMBFILEDIR,$FILENAME,$DST_X=0,$DST_Y=0,$SRC_X,$SRC_Y,$DST_WIDTH,$DST_HEIGHT,$ROTATE=0,$SCALE_X=1,$SCALE_Y=1){
  
    if(!is_writable($THMBFILEDIR)){
      $RES['error'] = EST_GEN_DIRECTORY.': '.$THMBFILEDIR.' '.EST_GEN_ISNOTACCESS;
      }
    
    if(!file_exists($THMBFILEDIR."/".$FILENAME)){
      $RES['error'] .= ' '.EST_GEN_FILENOTFOUND.': '.$THMBFILEDIR."/".$FILENAME;
      }
    if(!is_file($THMBFILEDIR."/".$FILENAME)){
      $RES['error'] .= ' '.EST_GEN_NOTAFILE.': '.$THMBFILEDIR."/".$FILENAME;
      }
    
    if($RES['error']){
      return $RES;
      }
    
    $SRC_PTH_FILE = realpath($THMBFILEDIR)."/".$FILENAME;
    
    list($SRC_WIDTH,$SRC_HEIGHT,$SRC_TYPE,$SRC_HTML_DIMS) = getimagesize($SRC_PTH_FILE);
    
    $DEST_TYPE = $SRC_TYPE;
    $SRC_RES = ($SRC_WIDTH * $SRC_HEIGHT);
    
    $RES['img']['actual'] = array($SRC_WIDTH,$SRC_HEIGHT,$SRC_TYPE,$SRC_HTML_DIMS,$SRC_RES);
    clearstatcache();
    
    
    //$BORDER_S = intval($_POST['bs']);
    
    
    
    /*
    $IMFORIENT = ($SRC_HEIGHT > $SRC_WIDTH ? "protrait" : "landscape");
    $Fsetting = array(0,800,600,0,90);
    $IMFSCALE = round(($SRC_RES / ($Fsetting[1] * $Fsetting[2])),2);
    
    $MIN_W = 512;
    $MIN_H = 512;
    $CONSTRAIN = 0;
    
    if($SRC_WIDTH < $MIN_W || $SRC_HEIGHT < $MIN_H){
      $IMDATA .= "<p>Image is smaller than the desired size. Cropping & upscaling will not work. New size will be $SRC_WIDTH x $SRC_HEIGHT</p>";
      $MIN_W = $SRC_WIDTH;
      $MIN_H = $SRC_HEIGHT;
      }
    
    //"Allow Stretch/Squeez","Standardize Aspect Ratio","Match Source Aspect Ratio, width","Match Source Aspect Ratio, height"
    // cropperjs:  "ratioDim: { x: $SRC_WIDTH, y: $SRC_HEIGHT },";
    switch($CONSTRAIN){
      case 0 :
        break;
      case 1 :
        break;
      case 2 :
        if($IMFORIENT == "protrait"){$MIN_W = ceil(($SRC_WIDTH/$SRC_HEIGHT) * $MIN_H);}
        else{$MIN_H = ceil(($SRC_HEIGHT/$SRC_WIDTH) * $MIN_W);}
        break;
      case 3 :
        if($IMFORIENT == "protrait"){$MIN_W = ceil(($SRC_WIDTH/$SRC_HEIGHT) * $MIN_H);}
        else{$MIN_H = ceil(($SRC_HEIGHT/$SRC_WIDTH) * $MIN_W);}
        break;
      }
    
    */
    
    
    $isimg = 0;
    switch($SRC_TYPE){
      case 1 : $SRCIMG = imagecreatefromgif($SRC_PTH_FILE); $isimg = 1; break;
      case 2 : $SRCIMG = imagecreatefromjpeg($SRC_PTH_FILE); $isimg = 2; break;
      case 3 : $SRCIMG = imagecreatefrompng($SRC_PTH_FILE); $isimg = 3; break;
      }
    
    if($isimg == 0){
      $RES['error'] = EST_GEN_FILE.' '.$FILENAME.' '.EST_ERR_NOTANIMG.' ('.EST_GEN_TYPE.': '.$SRC_TYPE.')';
      return $RES;
      }
    
    
    //cropbtns
    if($SCALE_X == -1 && $SCALE_Y == -1){imageflip($SRCIMG,IMG_FLIP_BOTH);}
    elseif($SCALE_X == -1){imageflip($SRCIMG,IMG_FLIP_HORIZONTAL);}
    elseif($SCALE_Y == -1){imageflip($SRCIMG,IMG_FLIP_VERTICAL);}
    
    if($ROTATE !== 0){
      $SRCIMG = imagerotate($SRCIMG,$ROTATE,0);
      }
      
      
    /*
    $BORDER = array('s'=>0,'r'=>255,'g'=>255,'b'=>255);
    if($BORDER['s'] > 0){
      $DST_X = $BORDER['s'];
      $DST_Y = $BORDER['s'];
      $DST_WIDTH = $DST_WIDTH + ($BORDER['s'] * 2);
      $DST_HEIGHT = $DST_HEIGHT + ($BORDER['s'] * 2);
      $DESTIMG = imagecreatetruecolor($DST_WIDTH,$DST_HEIGHT);
      $bgColor = imagecolorallocate($DESTIMG, $BORDER['r'],$BORDER['g'],$BORDER['b']);
      imagefilledrectangle($DESTIMG,0,0,$DST_WIDTH,$DST_HEIGHT,$bgColor);
      unset($bgColor);
      }
    else{
      $DESTIMG = imagecreatetruecolor($DST_WIDTH,$DST_HEIGHT);
      }
    */
    
    //$XFINAL = ($SRC_WIDTH - $SRC_X);
    //$YFINAL = ($SRC_HEIGHT - $SRC_Y);
    
    
    $XFINAL = ($DST_WIDTH - $SRC_X);
    $YFINAL = ($DST_HEIGHT - $SRC_Y);
    
    $RES['img']['crop'] = array(
      'dsth'=>$DST_HEIGHT,
      'dstw'=>$DST_WIDTH,
      'dstx'=>$DST_X,
      'dsty'=>$DST_Y,
      'rot'=>$ROTATE,
      'scalex'=>$SCALE_X,
      'scaley'=>$SCALE_Y,
      'srch'=>$YFINAL,
      'srcw'=>$XFINAL,
      'srcx'=>$SRC_X,
      'srcy'=>$SRC_Y
      );
    
    
      
    $DESTIMG = imagecreatetruecolor($DST_WIDTH,$DST_HEIGHT);
    //$DESTIMG = imagecreatetruecolor($XFINAL,$YFINAL);
      
    switch($DEST_TYPE){
      case 1 :
        imagecopyresampled($DESTIMG,$SRCIMG,$DST_X,$DST_Y,$SRC_X,$SRC_Y,$DST_WIDTH,$DST_HEIGHT,$XFINAL,$YFINAL);
        imagegif($DESTIMG,$SRC_PTH_FILE,80);
        break;
      case 2 :
        imagecopyresampled($DESTIMG,$SRCIMG,$DST_X,$DST_Y,$SRC_X,$SRC_Y,$DST_WIDTH,$DST_HEIGHT,$XFINAL,$YFINAL);
        imagejpeg($DESTIMG,$SRC_PTH_FILE,85);
        break;
      case 3 :
        $alpha_channel = imagecolorallocatealpha($DESTIMG, 0, 0, 0, 127);
        imagecolortransparent($DESTIMG, $alpha_channel); 
        imagefill($DESTIMG, 0, 0, $alpha_channel);
        imagecopy($DESTIMG,$SRCIMG,0,0,$SRC_X,$SRC_Y,$XFINAL,$YFINAL);
        imagesavealpha($DESTIMG,true);
        imagepng($DESTIMG,$SRC_PTH_FILE,9);
        break;
      }
    
    imagedestroy($SRCIMG);
    imagedestroy($DESTIMG);
    clearstatcache();
    return $RES;
    }
  
  public function estFileFetch($DIR){
    $res = array();
    $i = 0;
    if(is_dir($DIR)){
      if($DIR = opendir($DIR)){
        while(false !== (($FNAME = readdir($DIR)))){
          if($FNAME !=='.' && $FNAME !=='..' && strtolower($FNAME) !== 'index.html' && strtolower($FNAME) !== 'index.php'){
            $res[$i] = $FNAME;
            $i++;
            }
          }
        }
      }
    sort($res);
    return $res;
    }
  
  
  
  public function estHelpMenu($SECT,$hlpmode,$hlpactn){}
  
  
  }

?>