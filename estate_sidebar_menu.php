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


if(strpos(strtolower(THEME_LAYOUT),'full') !== false){return '';}

if(e_QUERY){$qs = explode(".", e_QUERY);}
else{$qs = array('list',0);}
$PROPID = intval($qs[1]);
$PROPDTA = array();
if(isset($GLOBALS['PROPDTA'])){$PROPDTA = $GLOBALS['PROPDTA'];}
elseif($PROPID > 0){$PROPDTA = e107::getDb()->retrieve("SELECT * FROM #estate_properties WHERE prop_idx='".$PROPID."'",true);}



$TMPLREORDOK = 0;
if(EST_USERPERM > 2){$TMPLREORDOK++;}
elseif(EST_USERPERM == 2 && intval($PROPDTA[0]['prop_agency']) == EST_AGENCYID){$TMPLREORDOK++;}
elseif(EST_USERPERM == 1 && intval($PROPDTA[0]['prop_agent']) == EST_AGENTID){$TMPLREORDOK++;}
elseif(intval($PROPDTA[0]['prop_uidcreate']) == EST_SELLERUID){$TMPLREORDOK++;}

if($TMPLREORDOK > 0){
  echo '<form name="estMenuTemplateForm" method="POST" action="'.e_SELF.'?'.e_QUERY.'" >';
  }

$tmpl = e107::getTemplate('estate');
$sc = e107::getScBatch('estate',true);

echo '<div id="estSidebarMenuCont" class="WD100">';



if(e_PAGE == 'listings.php'){
  
  if($qs[0] == 'view'){
    $tkey = (trim($PROPDTA[0]['prop_template_menu']) !== '' ?  $PROPDTA[0]['prop_template_menu'] : (trim($EST_PREF['template_menu']) !== '' ? $EST_PREF['template_menu'] : 'default'));
        
    $TEMPLATE = $tmpl['menu'][$tkey];
    $tmplct = 0;
    
    if(isset($PROPDTA[0]['prop_template_menu_ord'])){
      if(is_array($PROPDTA[0]['prop_template_menu_ord'])){$MENUTMPL = $PROPDTA[0]['prop_template_menu_ord'];}
      elseif(trim($PROPDTA[0]['prop_template_menu_ord']) !== ''){$MENUTMPL = e107::unserialize($PROPDTA[0]['prop_template_menu_ord']);}
      }
    
    if(!is_array($MENUTMPL) && isset($EST_PREF['template_menu_ord'])){
      if(is_array($EST_PREF['template_menu_ord'])){$MENUTMPL = $EST_PREF['template_menu_ord'];}
      elseif(trim($EST_PREF['template_menu_ord']) !== ''){$MENUTMPL = e107::unserialize($EST_PREF['template_menu_ord']);}
      }
    
    
    if(is_array($TEMPLATE['txt'])){
      $tmplct = count($TEMPLATE['txt']);
      
      if(isset($TEMPLATE['ord']) && isset($MENUTMPL[$tkey]) && count($MENUTMPL[$tkey]) > 0){
        if($TMPLREORDOK > 0){
          $NEWK = array();
          $ALLK = $TEMPLATE['ord'];
          echo $tp->parseTemplate('{ADMIN_REORDER_MENU:area=menu&tkey='.$tkey.'&ct='.$tmplct.'}', false, $sc);
          foreach($MENUTMPL[$tkey] as $sk=>$sv){$NEWK[$sk] = $TEMPLATE['txt'][$sk];}
          foreach($ALLK as $nk=>$nv){if(!$NEWK[$nv]){$NEWK[$nv] = '';}}
          unset($ALLK,$sk,$sv,$nk,$nv,$NEWK['dummy']);
          foreach($NEWK as $ok=>$dta){
            echo $tp->parseTemplate('{ADMIN_REORDER:area=menu&templ='.$tkey.'&ok='.$ok.'&ov='.intval($MENUTMPL[$tkey][$ok]).'}', false, $sc);
            echo $tp->parseTemplate($dta, false, $sc).'</div></div>';
            }
          unset($NEWK,$ok,$dta);
          }
        else{
          foreach($MENUTMPL[$tkey] as $ok=>$ov){
            if(isset($TEMPLATE['txt'][$ok]) && $ov == 1){
              echo $tp->parseTemplate($TEMPLATE['txt'][$ok], false, $sc);
              }
            }
          }
        }
      else{
        ksort($TEMPLATE['txt']);
        if($TMPLREORDOK > 0){
          echo $tp->parseTemplate('{ADMIN_REORDER_MENU:area=menu&tkey='.$tkey.'&ct='.$tmplct.'}', false, $sc);
          }
        foreach($TEMPLATE['txt'] as $ok=>$ov){
          echo $tp->parseTemplate($ov, false, $sc);
          }
        }
      }
    else{
      if($TMPLREORDOK > 0){
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
if($TMPLREORDOK > 0){echo '</form>';}
define("EST_RENDERED_SIDEBARMENU",1);
unset($MENUTMPL,$PROPID,$PROPDTA,$TEMPLATE,$TMPLREORDOK,$tkey,$ns,$tp);
?>