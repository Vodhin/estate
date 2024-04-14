<?php
if (!defined('e107_INIT')) { exit; }
if (!e107::isInstalled('estate')) { return ''; }


//e107::getScParser();
//require_once(e_PLUGIN.'estate/estate_shortcodes.php');

//$template = e107::getTemplate('estate/templates', 'estate_menu');

if(strpos(strtolower(THEME_LAYOUT),'full') === false){
  $tp = e107::getParser();
  $ns = e107::getRender();
  
  $EST_PREF = e107::pref('estate');
  //'.EST_GEN_LISTINGS.' '.LAN_OPTIONS.'
  
  $txt = '<div id="estPlugMenu1" class="estSideMenu"></div><div id="estSideMenuFeat" class="estSideMenu"></div><div id="estSideMenuSpaces" class="estSideMenu"></div>';
  $ns->tablerender('<div id="estPlugMenu1Cap"></div>', $txt, 'estSideMenu1');
  unset($txt);
  /*
  $txt = '<div id="estSideMenu2" class="estSideMenu"></div>';
  $ns->tablerender('<div id="estSideMenu2Cap"></div>', $txt, 'estSideMenu2');
  unset($txt);
  
  $txt = '<div id="estSideMenu3" class="estSideMenu"></div>';
  $ns->tablerender('<h3 id="estSideMenu3Cap"></h3>', $txt, 'estSideMenu3');
  unset($txt);
  */
  }

  
?>