<?php
/* Estate Sidebar Menu
 * Will NOT render in theme template that is Full Width (looks for the word 'full')
 * because this information should renter in the Summary Section of listings.php?view
 *
 *
 */


if (!defined('e107_INIT')) { exit; }
if (!e107::isInstalled('estate')) { return; }

if(e_QUERY){$qs = explode(".", e_QUERY);}
else{$qs = array('list',0);}

$EST_PREF = e107::pref('estate');
$tp = e107::getParser();
$ns = e107::getRender();

$tmpl = e107::getTemplate('estate');
$sc = e107::getScBatch('estate',true);

if(e_PAGE == 'listings.php'){
  if($qs[0] == 'view'){
    if(strpos(strtolower(THEME_LAYOUT),'full') !== false){return '';}
    
    $CAPT = $tp->parseTemplate($tmpl['menu']['title']['cap'], false, $sc);
    $TXT = $tp->parseTemplate($tmpl['menu']['title']['txt'], false, $sc);
    $ns->tablerender($CAPT, $TXT, 'estSideMenuTitle',true);
    
    
    $CAPT = $tp->parseTemplate($tmpl['menu']['seller']['cap'], false, $sc);
    $TXT = $tp->parseTemplate($tmpl['menu']['seller']['txt'], false, $sc);
    $ns->tablerender($CAPT, $TXT,'estSideMenuSeller',true);
    
    
    $TXT = $tp->parseTemplate($tmpl['menu']['events']['txt'], false, $sc);
    $ns->tablerender(EST_GEN_EVENTS, $TXT, 'estSideMenuEvents',true);
    define("ESTAGENTRENDERED",1);
    
    $TXT = $tp->parseTemplate($tmpl['menu']['saved']['txt'], false, $sc);
    $ns->tablerender(EST_GEN_SAVEDLISTINGS, $TXT, 'estSideMenuSaved',true);
    
    $TXT = $tp->parseTemplate($tmpl['menu']['spaces']['txt'], false, $sc);
    $ns->tablerender(EST_GEN_SPACES, $TXT, 'estSideMenuSpaces',true);
    
    }
  else{
    $TXT = $tp->parseTemplate($tmpl['menu']['saved']['txt'], false, $sc);
    $ns->tablerender(EST_GEN_SAVEDLISTINGS, $TXT, 'menu',true);
    }
  }
else{
  $ns->tablerender('Estate MEenu', 'not on the listings page', 'estSideMenu1',true);
  }
unset($CAPT,$TXT,$ns,$tp);
?>