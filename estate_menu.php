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
  
  if(e_QUERY){$qs = explode(".", e_QUERY);}
  else{$qs = array('list',0);}
  
  $tmpl = e107::getTemplate('estate');
  $sc = e107::getScBatch('estate',true);
  
  
  if($qs[0] == 'list'){
    $txt = $tp->parseTemplate($tmpl['menu']['saved'], false, $sc);
    $ns->tablerender(EST_GEN_SAVEDLISTINGS, $txt, 'estSideMenu0');
    unset($thd,$txt);
    }
  elseif($qs[0] == 'view'){
    $thd = $tp->parseTemplate($tmpl['menu']['head'], false, $sc);
    $txt = $tp->parseTemplate($tmpl['menu']['seller'], false, $sc);
    $ns->tablerender($thd, $txt, 'estSideMenu0');
    }
  else{
    
    }
  unset($thd,$txt);
  }

  
?>