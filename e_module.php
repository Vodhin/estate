<?php
if(!defined('e107_INIT')){exit;}
if(e107::isInstalled('estate')){
  // get plugin_id
  $ESTPLUG = e107::getDB()->retrieve('plugin', '*','plugin_name="EST_PLUGNAME"',true);
  define("EST_PLUGID",'P'.$ESTPLUG[0]['plugin_id']);
  define("EST_USRLEVELS",array("",EST_ULVAGENT,EST_ULVMANAGER,EST_ULVADMIN,EST_ULVMAINADMIN));
  
  
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
    //e107::getMessage()->addInfo(SITEURLBASE.e_ADMIN_ABS."updateadmin.php");
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
  }
?>