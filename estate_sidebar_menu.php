<?php
/* Estate Sidebar Menu
 * Will NOT render on View Page in a theme template that is Full Width (looks for the word 'full')
 * because this information usually renders in the Summary Section of listings.php?view
 *
 *
 */


if (!defined('e107_INIT')) { exit; }
if (!e107::isInstalled('estate')) { return; }

if(defined("EST_RENDERED_SIDEBARMENU")){return;}

$EST_PREF = e107::pref('estate');
$tp = e107::getParser();
$ns = e107::getRender();
$ns->setStyle('menu');
$tmpl = e107::getTemplate('estate');
$sc = e107::getScBatch('estate',true);

$tkey = (trim($EST_PREF['template_menu']) !=='' ? $EST_PREF['template_menu'] : 'default');
$TEMPLATE = $tmpl['menu'][$tkey];


if(strpos(strtolower(THEME_LAYOUT),'full') !== false){return '';}

if(e_QUERY){$qs = explode(".", e_QUERY);}
else{$qs = array('list',0);}


if(EST_USERPERM == 4){echo '<form name="estMenuTemplateForm" method="POST" action="'.e_SELF.'?'.e_QUERY.'" >';}
echo '<div id="estSidebarMenuCont" class="WD100">';



if(e_PAGE == 'listings.php'){
  
  if($qs[0] == 'view'){
    
    $tmplct = count($TEMPLATE['txt']);
    
    if(is_array($TEMPLATE['txt'])){
      $PREFTMP = $EST_PREF['template_menu_ord'][$tkey];
      if($TEMPLATE['ord'] && count($PREFTMP) > 0){
        if(EST_USERPERM == 4){
          $NEWK = array();
          $ALLK = $TEMPLATE['ord'];
          echo $tp->parseTemplate('{ADMIN_REORDER_MENU:area=menu&tkey='.$tkey.'&ct='.$tmplct.'}', false, $sc);
          foreach($PREFTMP as $sk=>$sv){$NEWK[$sk] = $TEMPLATE['txt'][$sk];}
          foreach($ALLK as $nk=>$nv){if(!$NEWK[$nv]){$NEWK[$nv] = '';}}
          unset($ALLK,$sk,$sv,$nk,$nv,$NEWK['dummy']);
          foreach($NEWK as $ok=>$dta){
            echo $tp->parseTemplate('{ADMIN_REORDER:area=menu&templ='.$tkey.'&ok='.$ok.'&ov='.intval($PREFTMP[$ok]).'}', false, $sc);
            echo $tp->parseTemplate($dta, false, $sc).'</div></div>';
            }
          unset($NEWK,$ok,$dta);
          }
        else{
          foreach($PREFTMP as $ok=>$ov){
            if(isset($TEMPLATE['txt'][$ok]) && $ov == 1){
              echo $tp->parseTemplate($TEMPLATE['txt'][$ok], false, $sc);
              }
            }
          }
        }
      else{
        ksort($TEMPLATE['txt']);
        if(EST_USERPERM == 4){
          echo $tp->parseTemplate('{ADMIN_REORDER_MENU:area=menu&tkey='.$tkey.'&ct='.$tmplct.'}', false, $sc);
          }
        foreach($TEMPLATE['txt'] as $ok=>$ov){
          echo $tp->parseTemplate($ov, false, $sc);
          }
        }
      }
    else{
      if(EST_USERPERM == 4){
        echo $tp->parseTemplate('{ADMIN_REORDER_MENU:area=menu&tkey='.$tkey.'&ct='.$tmplct.'}', false, $sc);
        }
      echo $tp->parseTemplate($TEMPLATE['txt'], false, $sc);
      }
    }
  else{
    //echo $tp->parseTemplate('{EST_NAV_MENU:for=menu}', false, $sc);
    }
  }
else{
  //echo $ns->tablerender('Estate Menu', 'not on the listings page', 'estSideMenu1',true);
  }

echo "</div>";
if(EST_USERPERM == 4){echo '</form>';}
define("EST_RENDERED_SIDEBARMENU",1);
unset($PREFTMP,$TEMPLATE,$tkey,$ns,$tp);
?>