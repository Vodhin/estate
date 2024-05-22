<?php
/* Estate Sidebar Menu
 * Will NOT render in theme template that is Full Width (looks for the word 'full')
 * because this information should renter in the Summary Section of listings.php?view
 *
 *
 */


if (!defined('e107_INIT')) { exit; }
if (!e107::isInstalled('estate')) { return; }

if(defined("EST_MENU_RENDERED")){return;}

if(e_QUERY){$qs = explode(".", e_QUERY);}
else{$qs = array('list',0);}


$EST_PREF = e107::pref('estate');
$tp = e107::getParser();
$ns = e107::getRender();
$ns->setStyle('menu');


$tmpl = e107::getTemplate('estate');
$sc = e107::getScBatch('estate',true);

if(e_PAGE == 'listings.php'){
  if($qs[0] == 'view'){
    if(strpos(strtolower(THEME_LAYOUT),'full') !== false){return '';}
    $EST_PREF['template_menu'] = 'default';
    
    if(is_array($tmpl['menu'][$EST_PREF['template_menu']]['txt'])){
      ksort($tmpl['menu'][$EST_PREF['template_menu']]['txt']);
      foreach($tmpl['menu'][$EST_PREF['template_menu']]['txt'] as $k=>$tmpv){
        echo $tp->parseTemplate($tmpv, false, $sc);
        }
      }
    else{
      echo $tp->parseTemplate($tmpl['menu'][$EST_PREF['template_menu']]['txt'], false, $sc);
      }
    }
  else{
    $TXT = $tp->parseTemplate($tmpl['menu']['saved']['txt'], false, $sc);
    $ns->tablerender(EST_GEN_SAVEDLISTINGS, $TXT, 'menu');
    }
  }
else{
  $ns->tablerender('Estate MEenu', 'not on the listings page', 'estSideMenu1',true);
  }
define("EST_MENU_RENDERED",true);
unset($CAPT,$TXT,$ns,$tp);
?>