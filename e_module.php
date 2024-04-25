<?php
if(!defined('e107_INIT')){exit;}
if(e107::isInstalled('estate')){
  // get plugin_id
  $ESTPLUG = e107::getDB()->retrieve('plugin', '*','plugin_name="EST_PLUGNAME"',true);
  define("EST_PLUGID",'P'.$ESTPLUG[0]['plugin_id']);
  define("EST_USRLEVELS",array("",EST_ULVAGENT,EST_ULVMANAGER,EST_ULVADMIN,EST_ULVMAINADMIN));
  
  
  if(USERID > 0){
    $tp = e107::getParser();
    $sql = e107::getDB();
    $sql->gen("SELECT user_id,user_name,user_loginname,user_email,user_class,user_admin,user_perms,user_signature,user_image FROM #user WHERE user_id = '".USERID."'");
    if($ret1 = $sql->fetch()){
      $ret1['user_profimg'] = $tp->toAvatar($ret1,array('type'=>'url'));
      
      if($sql->gen("SELECT #estate_agents.*, #estate_agencies.* FROM #estate_agents  LEFT JOIN #estate_agencies ON agent_agcy = agency_idx WHERE agent_uid = '".USERID."' LIMIT 1")){
        $ret2 = $sql->fetch();
        define("EST_AGENTID",intval($ret2['agent_idx']));
        define("EST_AGENCYID",intval($ret2['agent_agcy']));
        define("EST_SELLERUID",intval($ret2['agent_uid']));
        //e107::getMessage()->addInfo('Defined in e_module: '.$ret2['agent_name'].' ');
        }
      }
    }
  
  if(!defined("EST_SELLERUID")){define("EST_SELLERUID",intval(USERID));}
  //e107::getMessage()->addInfo('SELLERUID: '.EST_SELLERUID.' ');
  
  
  // get classes for plugin and define them
  $EST_CLASSES = array('ESTATE ADMIN'=>false,'ESTATE MANAGER'=>false,'ESTATE AGENT'=>false);
  foreach($EST_CLASSES as $k=>$v){
    $cid = e107::getUserClass()->ucGetClassIDFromName($k);
    define(str_replace(' ','_',strtoupper($k)),$cid);
    $EST_CLASSES[$k] = $cid;
    }
  unset($ESTPLUG,$k,$v);
  
  // define current user's premissions
  if(ADMINPERMS === '0'){
    define("EST_USERPERM", 4);
    define("EST_USERROLE", EST_GEN_MAIN." ".EST_GEN_ADMIN);
    define("EST_USERMANAGE", array(ESTATE_ADMIN,ESTATE_MANAGER,ESTATE_AGENT));
    }
  else{
    if(check_class(ESTATE_ADMIN)){
      define("EST_USERPERM", 3);
      define("EST_USERROLE", EST_GEN_ADMIN);
      define("EST_USERMANAGE", array(ESTATE_MANAGER,ESTATE_AGENT));
      }
    elseif(check_class(ESTATE_MANAGER)){
      define("EST_USERPERM", 2);
      define("EST_USERROLE", EST_GEN_MANAGER);
      define("EST_USERMANAGE", array(ESTATE_AGENT));
      }
    elseif(check_class(ESTATE_AGENT)){
      define("EST_USERPERM", 1);
      define("EST_USERROLE", EST_GEN_AGENT);
      define("EST_USERMANAGE", array());
      }
    else{
      define("EST_USERPERM", intval(0));
      }
    // NOT A MAIN ADMIN, redirect user to estate admin area if only access to estate plugin is allowed
    if(e_ADMIN_AREA === true && EST_USERPERM > 0){
      $CHKESTPERMS = explode('.',ADMINPERMS);
      if(count($CHKESTPERMS) == 1 && in_array(EST_PLUGID,$CHKESTPERMS)){
        if(e_SELF !== SITEURLBASE.e_ADMIN_ABS."updateadmin.php" 
        && e_SELF !== SITEURLBASE.e_ADMIN_ABS."credits.php" 
        && e_SELF !== SITEURLBASE.e_ADMIN_ABS."docs.php"){
          if(e_SELF !== SITEURLBASE.e_PLUGIN_ABS."estate/admin_config.php"){
            e107::redirect(SITEURLBASE.e_PLUGIN_ABS."estate/admin_config.php");
            }
          }
        }
      unset($CHKESTPERMS);
      }
    }
  
  //
  if(check_class(e107::pref('estate','listing_save'))){
    }
  
  if(check_class(e107::pref('estate','contact_class'))){
    require_once(e_PLUGIN.'estate/ui/msg.php');
    }
  }
?>