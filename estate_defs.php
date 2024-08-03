<?php
if(!defined('e107_INIT')){exit;}

$STRTIMENOW = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
$STRDATETODAY = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
$STRIN30DAYS = mktime(date("H"), date("i"), date("s"), date("m"), date("d")+30, date("Y"));
$EST_LISTTYPE1 = array(EST_GEN_FORRENT,EST_GEN_FORSALE,EST_GEN_FORSALEAUCT,EST_GEN_FORSALESHORT);
$EST_LEASEFREQ = array(EST_GEN_WEEKLY,EST_GEN_MONTHLY,EST_GEN_QUARTERLY);
$EST_HOAFREQ = array(0=>LAN_NONE,1=>EST_GEN_MONTHLY,2=>EST_GEN_QUARTERLY,3=>EST_GEN_SEMIANNUAL,4=>EST_GEN_ANNUAL);
$EST_HOAREQD = array(0=>EST_GEN_VOLANTARY,1=>EST_GEN_MANDATORY);
$EST_HOALAND = array(0=>array('',''),1=>array(EST_GEN_INCINHOAFEE,EST_GEN_INCLANDLEASE));
$EST_DIMUNITS = array(EST_SQFOOT,EST_SQMTR);
$EST_DIM1UNITS = array(explode(",",EST_SQFOOT),explode(",",EST_SQMTR));
$EST_DIM2UNITS = array(EST_GEN_ACRES,EST_GEN_SQRMI,$EST_DIM1UNITS[0][0],EST_GEN_SQRKM,$EST_DIM1UNITS[1][0]);

$EST_FOLDERS = array(0=>'prop',1=>'subdiv',2=>'agency',3=>'agency',4=>'agent');
$EST_SUBFLDR = array(0=>array('full','vid'));
$EST_CURSYMB = array('$','€','£','¥','฿','₡','¢','₴','₽','₱','CHF','Gs','kn','Kč','kr','MT','₪','Q','R','Rp','RM','₨','L','lei','Ft','₹','₺','₦','₭','₩','zł','₫','؋','៛','ƒ','лв','ден','₼','&nbsp;','');

define("EST_HOAFREQ", $EST_HOAFREQ);
define("EST_CURSYMB", $EST_CURSYMB);
define("EST_CONTKEYS",array(EST_GEN_MOBILE,EST_GEN_EMAIL,EST_GEN_OFFICE,EST_GEN_FAX,EST_GEN_WEBSITE,EST_GEN_LINKIN,EST_GEN_TWITER,EST_GEN_FACEBOOK));

define("EST_MSGTYPES",array('',EST_MSG_SHOWINGREQUESTS,EST_MSG_OFFERS,EST_MSG_QUOTEREQ,EST_MSG_OTHERQUESTIONS));

$EST_LEASEDUR = array(EST_GEN_NOLEASE);
for($i = 1; $i <= 6; $i++){array_push($EST_LEASEDUR,$i." ".EST_GEN_MONTH);}
for($i = 12; $i <= 24; $i = $i+6){array_push($EST_LEASEDUR,$i." ".EST_GEN_MONTH);}
for($i = 36; $i <= 96; $i = $i+12){array_push($EST_LEASEDUR,$i." ".EST_GEN_MONTH);}

$EST_EVENTARR = array(
  1=>array(
    0=>array(0,EST_GEN_OPENHOUSE),
    1=>array(1,EST_GEN_PRIVATEVIEWING),
    ),
  2=>array(
    0=>array(0,EST_GEN_PUBLICSHOWING),
    1=>array(1,EST_GEN_PRIVATEVIEWING),
    )
  );


$EST_EVENTKEYS = array(
  0=>array('l'=>EST_GEN_PRIVATEVIEWING,'t'=>'0:30','ms'=>1800),
  1=>array('l'=>EST_GEN_OPENHOUSE,'t'=>'4:00','ms'=>14400),
  2=>array('l'=>EST_GEN_INSPECTION,'t'=>'2:00','ms'=>7200),
  3=>array('l'=>EST_GEN_MEETING,'t'=>'1:30','ms'=>5400),
  4=>array('l'=>EST_GEN_CLOSING,'t'=>'2:00','ms'=>7200),
  );

$EST_PROPSTATUS = array(
  0=>array('opt'=>EST_GEN_OFFMARKET,'tit'=>''),
  1=>array('opt'=>EST_GEN_COMINGSOON,'tit'=>''),
  2=>array('opt'=>EST_GEN_ACTIVE1,'tit'=>''),
  3=>array('opt'=>EST_GEN_ACTIVE2,'tit'=>''),
  4=>array('opt'=>EST_GEN_PENDING,'tit'=>''),
  5=>array('opt'=>EST_GEN_SOLD,'tit'=>''),
  );

define("EST_IMGTYPES",array(".jpg",".jpeg",".gif",".png"));
define("EST_PTH_ADMIN", e_PLUGIN."estate/admin_config.php");
define("EST_PTH_LISTINGS", e_PLUGIN."estate/listings.php");
define("EST_PTH_AVATAR", e_MEDIA."avatars/upload/");
define("EST_PTHABS_AVATAR", SITEURLBASE.e_MEDIA_ABS."avatars/upload/");
define("EST_PATHABS", SITEURLBASE.e_PLUGIN_ABS."estate/");
define("EST_PATHABS_LISTINGS", EST_PATHABS."listings.php");

define("EST_PATHABS_MEDIA", EST_PATHABS."media/");
define("EST_PATHABS_IMAGES", EST_PATHABS."images/");
define("EST_PTHABS_AGENCY", EST_PATHABS_MEDIA."agency/");
define("EST_PTHABS_AGENT", EST_PATHABS_MEDIA."agent/");
define("EST_PTHABS_PROPTHM", EST_PATHABS_MEDIA."prop/thm/");
define("EST_PTHABS_PROPFULL", EST_PATHABS_MEDIA."prop/full/");
define("EST_PTHABS_PROPVID", EST_PATHABS_MEDIA."prop/vid/");
define("EST_PTHABS_SUBDTHM", EST_PATHABS_MEDIA."subdiv/thm/");
define("EST_PTHABS_SUBDFULL", EST_PATHABS_MEDIA."subdiv/full/");
define("EST_PTHABS_SUBDVID", EST_PATHABS_MEDIA."subdiv/vid/");

function spgrpsort($a, $b){
  return strnatcmp($a['ord'], $b['ord']);
  }


function estHelpTabs(){
    $tp = e107::getParser();
    $public_act = intval($GLOBALS['EST_PREF']['public_act']);
    
    
    $TBS[0]['caption'] = EST_INSTR000;
    $TBS[0]['text'] = '
          <div>
            <h3>'.EST_INSTR000.'</h3>
            <p>'.EST_INSTR001.'</p>
            <p>'.EST_INSTR001a.'</p>
          </div>
          <div>
            <h3>'.EST_INSTR002.'</h3>
            <p>'.EST_INSTR002a.'</p>
            <p>'.EST_INSTR002b.':</p>
            <table id="estInstrMenuTBL" class="estInstrMenuTBL">
              <tbody>
                <tr>
                  <td><b>'.EST_GEN_ESTADMINS.'</b></td>
                  <td><b>'.EST_GEN_ESTMANAGERS.'</b></td>
                  <td><b>'.EST_GEN_ESTAGENTS.'</b></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td class="VAT"><img src="'.EST_PATHABS_IMAGES.'instr_admmenu3.png" /><p class="FSITAL">'.EST_GEN_ESTADMINS.' '.EST_INSTR002c.'</p></td>
                  <td class="VAT"><img src="'.EST_PATHABS_IMAGES.'instr_admmenu2.png" /><p class="FSITAL">'.EST_GEN_ESTMANAGERS.' '.EST_INSTR002d.'</p></td>
                  <td class="VAT"><img src="'.EST_PATHABS_IMAGES.'instr_admmenu1.png" /><p class="FSITAL">'.EST_GEN_ESTAGENTS.' '.EST_INSTR002e.'</p></td>
                  <td>&nbsp;</td>
                </tr>
              </tbody>
            </table>
            <p>'.EST_INSTR003.' ('.(EST_USERPERM >= 2 ? EST_INSTR003a : EST_INSTR003b).').</p>
            <p>'.EST_INSTR003c.'</p>
          </div>
          <div>
            <h3>'.EST_GEN_NONAGENTLISTINGS.' </h3>
            <p>['.EST_GEN_CURRENTLY.' '.($public_act == 255 ? LAN_DISABLED : LAN_ENABLED).'] '.EST_INSTR005a.'</p>
            <p>'.EST_INSTR005b.'</p>
          </div>';
    
    
    
    //<b>'.EST_GEN_AGENT.' '.EST_GEN_LISTINGS.'</b>
    $TBS[1]['caption'] = EST_GEN_AGENTS;
    $TBS[1]['text'] = '
          <div class="WD100">
            <h3>'.EST_INSTR004.'</h3>
            <p>'.EST_INSTR004a.'</p>
            <hr />
            <h3>'.EST_GEN_ESTADMINS.'</h3>
            <p>'.EST_INSTR004b.'</p>
            <h4>'.EST_INSTRHEAD11a.'</h4>
            <p><img src="'.EST_PATHABS_IMAGES.'instr_agtagencylist.png" /></p>
            <p>'.EST_INSTR004b1.'</p>
            <p>'.EST_INSTR004b2.'</p>
            <h4>'.EST_INSTRHEAD12a.'</h4>
            <p><img src="'.EST_PATHABS_IMAGES.'instr_agtuserlist.png" /></p>
            <p>'.EST_INSTR004c.' <i>'.EST_INSTR004d.'</i></p>
            <p>'.EST_INSTR004e.'</p>
            <p>'.EST_INSTR004f.'</p>
            <h4>'.EST_INSTRHEAD13a.'</h4>
            <p><img src="'.EST_PATHABS_IMAGES.'instr_agtadduser.png" /></p>
            <p>'.EST_INSTR004g.'</p>
            <hr />
            <h3>'.EST_GEN_ESTMANAGERS.'</h3>
            <p>'.EST_INSTR004g.'</p>
            <h4>'.EST_INSTRHEAD11b.'</h4>
            <p><img src="'.EST_PATHABS_IMAGES.'instr_agtagency.png" /></p>
            <p>'.EST_INSTR004h.'</p>
            <p>'.EST_INSTR004i.'</p>
            <h4>'.EST_INSTRHEAD12b.'</h4>
            <p><img src="'.EST_PATHABS_IMAGES.'instr_agtagentlist.png" /></p>
            <p>'.EST_INSTR004j.'</p>
          </div>';
    
    
    $TBS[2]['caption'] = EST_INSTR008;
    $TBS[2]['text'] = '
          <div class="WD100">
            <h3>'.EST_INSTR008a.'</h3>
            <p>'.EST_INSTR008b.'</p>
            <p>'.EST_INSTR008c.'</p>
          </div>
          <div class="WD100">
            <h3>'.EST_GEN_ESTADMINS.'</h3>
            <p><img src="'.EST_PATHABS_IMAGES.'instr000.png" /></p>
            <p>'.EST_INSTR008d.'</p>
          </div>
          <div class="WD100">
            <h3>'.EST_GEN_ESTMANAGERS.'</h3>
            <p><img src="'.EST_PATHABS_IMAGES.'instr012a.png" /></p>
            <p>'.EST_INSTR008e.'</p>
          </div>
          
          <div class="WD100">
            <h3>'.EST_GEN_ESTAGENTS.'</h3>
            <p><img src="'.EST_PATHABS_IMAGES.'instr013a.png" /></p>
            </p>'.EST_INSTR008f.'</p>
          </div>
          
          <div class="WD100">
            <h3>'.EST_GEN_NONAGENTLISTINGS.'</h3>
            <p><img src="'.EST_PATHABS_IMAGES.'instr011b.png" /></p>
            <p>'.EST_INSTR008g.'</p>
          </div>
          <div class="WD100">
            <h3>'.EST_GEN_NEW.' '.EST_GEN_LISTING.'</h3>
            <p><img src="'.EST_PATHABS_IMAGES.'instr013b.png" /></p>
            <p>'.EST_INSTR008h.'</p>
          </div>';
    
    
    $TBS[3]['caption'] = EST_INSTR009;
    $TBS[3]['text'] = 'Coming Soon';
    
    
    $TBS[4]['caption'] = EST_INSTR010;
    $TBS[4]['text'] = '
          <div class="WD100">
            <h3>'.EST_INSTR010.'</h3>
            <p>'.EST_INSTR011.'</p>
            <img src="'.EST_PATHABS_IMAGES.'instr002.png" class="estInstImg" />
            <p>'.EST_INSTR012.'</p>
            <img src="'.EST_PATHABS_IMAGES.'instr003.png" class="estInstImg" />
            <p>'.EST_INSTR013.'</p>
          </div>
          <div class="WD100">
            <h3>'.EST_INSTR020.'</h3>
            <p>'.EST_INSTR021.'</p>
            <img src="'.EST_PATHABS_IMAGES.'instr004.png" class="estInstImg" />
          </div>
          <div class="WD100">
            <p>'.EST_INSTR022.'</p>
          </div>';
    
    
    
    $TBS[5]['caption'] = EST_INSTR030;
    $TBS[5]['text'] = '<div>
            <h3>'.EST_INSTR030.'</h3>
            <p>'.EST_INSTR031.'</p>
            <div class="WD100 TAC">
              <img src="'.EST_PATHABS_IMAGES.'instr005.png" class="estInstImg" style="width:96%; margin:16px auto;" />
            </div>
            <p>'.EST_INSTR032.'</p>
            <p>'.EST_INSTR033.'</p>
            <p>'.EST_INSTR033a.'</p>
            
            <table class="WD100">
              <tr>
                <td colspan="2">
                  <h4>'.EST_INSTRHEAD61.'</h4>
                </td>
              </tr>
              <tr>
                <td class="VAT"><img src="'.EST_PATHABS_IMAGES.'instr006.png" class="estInstImg" style="width:480px;" /></td>
                <td class="VAT">
                  <p>'.EST_INSTR034.'</p>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <p>'.EST_INSTR034a.'</p>
                  <p>'.EST_INSTR034b.'</p>
                  <p>'.EST_INSTR034c.'</p>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <h4>'.EST_INSTRHEAD62.'</h4>
                </td>
              </tr>
              <tr>
                <td class="VAT"><img src="'.EST_PATHABS_IMAGES.'instr007.png" class="estInstImg" style="width:480px;" /></td>
                <td class="VAT">
                  <p>'.EST_INSTR035.'</p>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <p>'.EST_INSTR035a.'</p>
                  <p>'.EST_INSTR035b.'</p>
                </td>
              </tr>
              <tr>
                <td class="VAT"><img src="'.EST_PATHABS_IMAGES.'instr007a.png" class="estInstImg" style="width:480px;" /></td>
                <td class="VAT">
                  <p>'.EST_INSTR036.'</p>
                  <p>'.EST_INSTR036a.'</p>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <p>'.EST_INSTR036b.'</p>
                  <p>'.EST_INSTR036c.'</p>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <h4>'.EST_INSTRHEAD63.'</h4>
                </td>
              </tr>
              <tr>
                <td class="VAT"><img src="'.EST_PATHABS_IMAGES.'instr008.png" class="estInstImg" style="width:480px;" /></td>
                <td class="VAT">
                  <p>'.EST_INSTR037.'</p>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <p>'.EST_INSTR037a.' "[b]Bold[/b]" '.EST_INSTR037b.' "[i]Italic[/i]" '.EST_INSTR037c.' "[b][i]Bold Italic[/i][/b]" '.EST_INSTR037d.' </p>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <h4>'.EST_INSTRHEAD64.'</h4>
                </td>
              </tr>
              <tr>
                <td class="VAT"><img src="'.EST_PATHABS_IMAGES.'instr009.png" class="estInstImg" style="width:480px;" /></td>
                <td class="VAT">
                  <p>'.EST_INSTR038.'</p>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <p>'.EST_INSTR038a.'</p>
                  <p>'.EST_INSTR038b.'<ul><li>'.EST_INSTR038b1.'</li><li>'.EST_INSTR038b2.'</li><li>'.EST_INSTR038b3.'</li></ul></p>
                  <p>'.EST_INSTR038c.'</p>
                  <p>'.EST_INSTR038d.'</p>
                  <p>'.EST_INSTR038e.'</p>
                </td>
              </tr>
            </table>
          </div>';
    
  return $TBS;
  }


//

function estGetSpaces($DTA,$PSTAT=0){
  $sql = e107::getDb();
  $RET = array();
  $PROPID = intval($DTA['prop_idx']);
  $SUBDIVID = intval($DTA['prop_subdiv']);
  
  $MQRY = "SELECT #estate_media.* FROM #estate_media WHERE media_propidx=".$PROPID." ";
  if($PSTAT == 1){$MQRY .= "AND media_galord='1' LIMIT 1";}
  else{$MQRY .= "AND media_type='1' ORDER BY media_galord ASC";}
  
  if($data2 = $sql->retrieve($MQRY,true)){
    $i = 0;
    foreach($data2 as $row){
      //if(intval($row['media_type']) == 1){}
      $MEDIA = array(
        'a'=>$row['media_asp'],
        'f'=>$row['media_full'],
        'i'=>$row['media_idx'],
        'l'=>$row['media_levidx'],
        'n'=>$row['media_name'],
        'o'=>$row['media_levord'],
        't'=>$row['media_thm'],
        'y'=>$row['media_type']
        );
      if(intval($row['media_galord']) > 0){
        $RET[0][$row['media_galord']] = $MEDIA;
        }
      $SPMEDIA[$row['media_levidx']][$row['media_levord']] = $MEDIA;
      }
    }
  
  
  if($data4 = $sql->retrieve("SELECT * FROM #estate_group",true)){
    foreach($data4 as $row){$SPGRPS[$row['group_idx']] = $row['group_name'];}
    }
  
  $query3 = "
  SELECT #estate_spaces.*, #estate_grouplist.*, #estate_featurelist.*, #estate_features.feature_name
  FROM #estate_spaces
  LEFT JOIN #estate_featurelist
  ON featurelist_levidx = space_idx
  LEFT JOIN #estate_features
  ON feature_idx = featurelist_key
  LEFT JOIN #estate_grouplist
  ON grouplist_groupidx = space_grpid
  WHERE space_propidx=".$PROPID."
  ORDER BY grouplist_ord ASC, space_ord ASC";
  
  if($data3 = $sql->retrieve($query3,true)){
    foreach($data3 as $row){
      $RET[1][$row['space_grpid']]['ord'] = $row['grouplist_ord'];
      $RET[1][$row['space_grpid']]['n'] = $SPGRPS[$row['space_grpid']];
      $RET[1][$row['space_grpid']]['sp'][$row['space_ord']][$row['space_idx']]['n'] = $row['space_name'];
      $RET[1][$row['space_grpid']]['sp'][$row['space_ord']][$row['space_idx']]['d'] = $row['space_description'];
      $RET[1][$row['space_grpid']]['sp'][$row['space_ord']][$row['space_idx']]['l'] = $row['space_loc'];
      $RET[1][$row['space_grpid']]['sp'][$row['space_ord']][$row['space_idx']]['f'][$row['featurelist_idx']]['n'] = $row['feature_name'];
      $RET[1][$row['space_grpid']]['sp'][$row['space_ord']][$row['space_idx']]['f'][$row['featurelist_idx']]['d'] = $row['featurelist_dta'];
      $RET[1][$row['space_grpid']]['sp'][$row['space_ord']][$row['space_idx']]['m'] = $SPMEDIA[$row['space_idx']];
      if(intval($row['space_dimxy']) !== 0){
        $RET[1][$row['space_grpid']]['sp'][$row['space_ord']][$row['space_idx']]['xy'] = $row['space_dimxy']." ".$GLOBALS['EST_DIM1UNITS'][$row['space_dimu']][0];
        }
      }
    }
  
  if($SUBDIVID > 0){
    $SUBDIVDTA = estGetSubDivDta($SUBDIVID);
    if(isset($SUBDIVDTA)){$RET[2] = $SUBDIVDTA;}
    }
  
  return $RET;
  unset($RET,$MEDIA,$SPMEDIA,$SPGRPS,$SUBDIVDTA);
  }


function estGetSubDivDta($subd_idx){
  $sql = e107::getDb();
  $tp = e107::getParser();
  $RET = array();
  if($dbRow1 = $sql->retrieve('estate_subdiv', '*', 'subd_idx="'.$subd_idx.'"',true)){
    $RET = $dbRow1[0];
    if($dbRow2 = $sql->retrieve('estate_media', '*', 'media_levidx="'.$subd_idx.'" AND media_lev="0"',true)){
      foreach($dbRow2 as $k=>$v){
        $RET['media'][$k] = array(
          'a'=>$v['media_asp'],
          'f'=>$v['media_full'],
          'g'=>$v['media_galord'],
          'i'=>$v['media_idx'],
          'l'=>$v['media_levidx'],
          'n'=>$v['media_name'],
          'o'=>$v['media_levord'],
          't'=>$v['media_thm'],
          'y'=>$v['media_type']
          );
        }
      }
    }
  return $RET;
  }



function estSubDivisionView($subd_idx){
  //estGetSpaces
  $sql = e107::getDb();
  $tp = e107::getParser();
  $dta = estGetSubDivDta($subd_idx);
  extract($dta);
  
  if(isset($media) && count($media) > 0){
    $CSSTOP = estViewImgCSS('#estSubDivSlideShow',$media,2);
    if(isset($CSSTOP[0])){
      $SLIDESHOW = '<style>'.$CSSTOP[0].'</style><div id="estSubDivSlideShow"></div>';
      }
    }
  
  if(trim($subd_url) !== ""){
    $SUBDWEB = '<h4 class="WD100">'.$tp->makeClickable($subd_url,'url',array('ext'=>1)).'</h4>';
    }
  
  if(trim($subd_hoaweb) !== ""){
    $HOAWEB = '<h4 class="WD100">'.$tp->makeClickable($subd_hoaweb,'url',array('ext'=>1)).'</h4>';
    }
  
  $txt = '
  <div id="estSubDivCont" data-id="'.intval($subd_idx).'" data-city="'.intval($subd_city).'" data-hoareq="'.intval($subd_hoareq).'" data-hoafee="'.intval($subd_hoafee).'" data-hoafrq="'.intval($subd_hoafrq).'" data-hoaappr="'.intval($subd_hoaappr).'">
    '.$SLIDESHOW.'
    <div id="estSubDivS1">
      <h3 class="WD100">'.$tp->toHTML($subd_name).'</h3>
      <h4 class="WD100">'.EST_GEN_SUBDIVTYPE[$subd_type].'</h4>
      '.$SUBDWEB.'
      '.(trim($subd_description) !== '' ? '<div class="WD100">'.$tp->toHTML($subd_description).'</div>' : '').'
    </div>';
  if($subd_hoaappr == 1 || $subd_hoareq == 1 || intval($subd_hoafee) > 0){
    
    $txt .= '
    <div id="estSubDivS2">
      '.$tp->toHTML(trim($subd_hoaname) !== '' ? '<h3>'.$subd_hoaname.' '.EST_GEN_HOMEOWNASS.'</h3>' : '<h4>'.EST_GEN_HOADEF1.'</h4>').'
      '.($HOAWEB ? $HOAWEB : ($SUBDWEB ? $SUBDWEB : '')).'
      <ul>
        <li>'.($subd_hoareq == 1 ? EST_GEN_HOAREQ1 : EST_GEN_HOAREQ2).'</li>
        <li>'.$subd_hoafee.' '.EST_HOAFREQ[$subd_hoafrq].'</li>
        '.($subd_hoaappr == 1 ? '<li>'.EST_GEN_HOAAPPR2.'</li>' : '').'
      </ul>
    </div>';
    }
    
    
    $txt .= '
    <div id="estSubDivS3">
    </div>';
    
    $txt .= '
  </div>';
  
  //$EST_HOAREQD[$subd_hoareq]
  //EST_HOAFREQ
  unset($SLIDESHOW,$SUBDWEB,$HOAWEB,$CSSTOP);
  return $txt;
  }


function estViewImgCSS($cssName,$gal,$loc=1){
  $EST_PREF = e107::pref('estate');
  $actv = $EST_PREF['slideshow_act'];
  $stime = intval($EST_PREF['slideshow_time']) * $loc;
  $sdelay = $EST_PREF['slideshow_delay'];
  $CSS = array();
  $galCt = ($gal ? count($gal) : 0);
  if($galCt == 0){
    $CSS[0] = '#estSlideShow{background-image: url("'.EST_PATHABS_IMAGES.'imgnotavail.png");}';
    $CSS[1] .= '
        <img src="'.EST_PATHABS_IMAGES.'imgnotavail.png" />';
    }
  else{
    $sdelay = intval($sdelay);
    $iStep = round(99 / $galCt, 2);
    $imgpth = ($loc == 2 ? EST_PTHABS_SUBDTHM : EST_PTHABS_PROPTHM);
    $CSS[0] = '';
    $URLIST = 'url(\''.$imgpth.$gal[1]['t'].'\')';
    
    if(intval($actv) == 1 && $galCt > 1){
      $aniName = str_replace('.','',str_replace('#','',$cssName));
      $iPct = 0;
      $fi = 0;
      foreach($gal as $ik=>$idta){
        $fi++;
        if($fi > 1){$URLIST .= ',url(\''.$imgpth.$idta['t'].'\')';}
        $CSS[1] .= '
        <img src="'.$imgpth.$idta['t'].'" />';
        $CSS[2][$ik] = $idta['t'];
        $galCSS .= $iPct.'%'.($fi == 1 ? ', 100%' : '').' {background-image: url("'.$imgpth.$idta['t'].'");}
    ';
        $iPct = ($iStep + $iPct);
        }
      
      if($sdelay == 0){
        $sdelay = ($galCt > 7 ? ceil($galCt / 2) : 4);
        }
      
      $galBaseCSS = '
      animation: '.$aniName.' '.($galCt * intval($stime)).'s infinite;
      animation-delay: '.$sdelay.'s;
      visibility: visible !important;
      -webkit-animation-duration: '.($galCt * intval($stime)).'s;
      -webkit-animation-name: '.$aniName.';
      -webkit-animation-iteration-count: infinite;
      ';
      
      $CSS[0] .= '    @keyframes '.$aniName.'{
      '.$galCSS.'}';
        }
    $CSS[0] .= '
    '.$cssName.'{
      background-image:'.$URLIST.';'.$galBaseCSS.'
      }';
    
    }
  return $CSS;
  unset($galCSS,$galBaseCSS,$cssName,$aniName,$galBaseCSS);
  }
?>