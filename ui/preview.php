<?php
if(!defined('e107_INIT')) { exit; }
if(!ADMIN){exit;}
if(!$qs){exit;}

$e107_popup = 1;
e107::css('inline', 'body{padding-top: 0px;}');

if(strpos($qs[3],'|') > -1){
  $NP = explode('|',$qs[3]);
  
  }


if($PROPID > 0){
  $ESTDTA = estGetSpaces($PROPID);
  $IDIV = estViewCSS($ESTDTA);
  $EST_SPACES = $ESTDTA[1];
  $PROPDTA[0] = $EST_PROP[$PROPID];
  
  $PINS = est_map_pins();
  e107::js('inline','var estMapPins = '.$PINS.'; ', 'jquery',2);
  
  require_once(HEADERF);
  $tmpl = e107::getTemplate('estate');
  $sc = e107::getScBatch('estate',true);
  $sc->setVars($PROPDTA[0]);
  
  if(strpos(strtolower(THEME_LAYOUT),'full') === false){
    $EST_PREF['layout_view_spaces'] = 'tiles';
    $tmpl = e107::getTemplate('estate');
    $sc = e107::getScBatch('estate',true);
    }
  
  $estText .= $tp->parseTemplate($tmpl['view'][$qs[2]], false, $sc);
  $ns->tablerender('','<div id="estDtaCont" class="estPrefScale75">'.$estText.'</div>','estate-view'); //
  }
else{
  $PINS = est_map_pins();
  e107::js('inline','var estMapPins = '.$PINS.'; ', 'jquery',2);
  require_once(HEADERF);
  $tmpl = e107::getTemplate('estate');
  $sc = e107::getScBatch('estate',true);
  $sc->setVars($PROPDTA[0]);
  
  $estText = $tp->parseTemplate($tmpl['list']['top'], false, $sc);
  if(count($EST_PROP) > 0){
    foreach($EST_PROP as $k=>$v){
      $sc->setVars($v);
      $estText .= $tp->parseTemplate($tmpl['list']['item'], false, $sc);
      }
    }
  $estText .= $tp->parseTemplate($tmpl['list']['bot'], false, $sc);
  
  $ns->tablerender('','<div id="estDtaCont" class="estPrefScale75">'.$estText.'</div>','estate-list');
  unset($estText);
  }

echo '
<div id="estJSpth"'.$OACLASS.' data-pth="'.EST_PATHABS.'"></div>
<div id="estMobTst"></div>';
require_once(FOOTERF);

?>