<?php
if (!defined('e107_INIT')) { exit; }

$EST_PROP = array();
$EST_PROPINTS = array('prop_idx','prop_status','prop_type','prop_zoning','prop_agent','prop_leasedur','prop_leasefreq','prop_landfee','prop_landfreq','prop_hoafee','prop_hoareq','prop_hoafrq','prop_yearbuilt','prop_intsize','prop_landsize','prop_bathhalf','prop_bathtot','prop_bedtot','prop_bathfull','prop_bathmain','prop_bedmain','prop_floorct','prop_floorno','prop_bldguc','prop_complxuc','prop_views');

foreach($estQdta as $row){
  foreach($row as $k=>$v){
    if(in_array($k,$EST_PROPINTS)){$row[$k] = intval($v);}
    //prop_appr
    }
  
  if($prop_idx !== intval($row['prop_idx'])){
    $prop_idx = intval($row['prop_idx']);
    $EST_PROP[$prop_idx] = $row;
    $FPROPS .= ($FPROPS ? "," : "").$prop_idx;
    }
  }



if($data2 = $sql->retrieve("SELECT #estate_subdiv.* FROM #estate_subdiv ORDER BY subd_idx ASC",true)){
  foreach($data2 as $row){
    $EST_SUBDIV[$row['subd_idx']]['subd_city'] = $row['subd_city'];
  	$EST_SUBDIV[$row['subd_idx']]['subd_name'] = $row['subd_name'];
  	$EST_SUBDIV[$row['subd_idx']]['subd_url'] = $row['subd_url'];
  	$EST_SUBDIV[$row['subd_idx']]['subd_hoaname'] = $row['subd_hoaname'];
  	$EST_SUBDIV[$row['subd_idx']]['subd_hoaweb'] = $row['subd_hoaweb'];
  	$EST_SUBDIV[$row['subd_idx']]['subd_hoareq'] = intval($row['subd_hoareq']);
  	$EST_SUBDIV[$row['subd_idx']]['subd_hoafee'] = intval($row['subd_hoafee']);
  	$EST_SUBDIV[$row['subd_idx']]['subd_hoafrq'] = intval($row['subd_hoafrq']);
    }
  }



if(count($EST_PROP) > 0){
  $EVTQRY = "SELECT #estate_events.* FROM #estate_events WHERE event_propidx";
  if($PROPID == 0){
    $EVTQRY .= " IN($FPROPS)";
    if($data2 = $sql->retrieve("SELECT #estate_media.* FROM #estate_media WHERE media_propidx IN($FPROPS) AND media_galord ='1'",true)){
      $i = 0;
      foreach($data2 as $row){$EST_PROP[$row['media_propidx']]['img'][$row['media_galord']]['t'] = $row['media_thm'];}
      }
    }
  else{$EVTQRY .= "='$PROPID'";}
  $EVTQRY .= " AND event_start >='".$STRDATETODAY."' AND event_type='1' ORDER BY event_start ASC"; //AND event_stat='1' 
  if($dataEvt = $sql->retrieve($EVTQRY,true)){
    foreach($dataEvt as $row){$EST_PROP[$row['event_propidx']]['evt'][$row['event_start']] = $row;}
    }
  }

unset($FPROPS,$EVTQRY,$MSGQRY,$data,$data2,$dataEvt,$dataMsg,$Z1,$Z2,$row);
?>