<?php
if (!defined('e107_INIT')) { exit; }


function estTablStruct(){
  $DIMUOPT = array();
  if(count($GLOBALS['EST_DIMUNITS']) > 0){foreach($GLOBALS['EST_DIMUNITS'] as $dk=>$dv){$DIMUOPT[$dk] = explode(',',$dv)[0];}}
  if(count($GLOBALS['EST_EVENTKEYS']) > 0){foreach($GLOBALS['EST_EVENTKEYS'] as $k=>$v){$EVENTTYPES[$k] = $v['l'];}}
  return array(
    'estate_agencies'=>array(
      'agency_idx'=>array('type'=>'idx','str'=>'int'),
      'agency_pub'=>array('type'=>'switch','str'=>'int','labl'=>EST_GEN_PROFILEVISIB,'tab'=>0,'src'=>array(EST_GEN_HIDDEN,EST_GEN_PUBLIC)),
      'agency_image'=>array('type'=>'hidden','tab'=>0,'str'=>'txt'),
      'agency_imgsrc'=>array('type'=>'hidden','str'=>'int'),
      'agency_name'=>array('str'=>'txt','labl'=>EST_LOCATION.' '.EST_GEN_NAME,'cls'=>'xlarge','chks'=>array('noblank')),
      'agency_addr'=>array('type'=>'textarea','str'=>'txt','labl'=>EST_GEN_ADDRESS,'tab'=>0,'cls'=>'xlarge','plch'=>EST_GEN_FULLADDR,'rows'=>2),
      'agency_timezone'=>array('type'=>'hidden','str'=>'txt','tab'=>2),
      'agency_lat'=>array('type'=>'hidden','str'=>'txt','tab'=>2,'cls'=>'xlarge'),
      'agency_lon'=>array('type'=>'hidden','str'=>'txt','tab'=>2,'cls'=>'xlarge'),
      'agency_geoarea'=>array('type'=>'hidden','str'=>'txt','tab'=>2,'cls'=>'xlarge'),
      'agency_zoom'=>array('type'=>'hidden','str'=>'int'),
      'agency_txt1'=>array('type'=>'textarea','str'=>'txt','labl'=>'Information','cls'=>'xxlarge WD100','plch'=>EST_GEN_ADDITIONAL,'rows'=>4),
      ),
    
    'estate_agents'=>array(
      'agent_idx'=>array('type'=>'idx','str'=>'int'),
      'agent_lev'=>array('type'=>'hidden','str'=>'int'),
      'agent_image'=>array('type'=>'hidden','str'=>'txt'),
      'agent_imgsrc'=>array('type'=>'hidden','str'=>'int'),
      'agent_uid'=>array(
        'type'=>'select',
        'str'=>'int',
        'cls'=>'xlarge',
        'labl'=>EST_GEN_AGENTLOGNAME,
        'src'=>array('tbl'=>'estate_user','idx'=>'user_id','map'=>array('user_id','user_loginname')),
        ),
      'agent_agcy'=>array(
        'type'=>'eselect',
        'str'=>'int',
        'cls'=>'xlarge',
        'labl'=>EST_GEN_AGENCY,
        'src'=>array('tbl'=>'estate_agencies','idx'=>'agency_idx','map'=>array('agency_idx','agency_name')),
        ),
      'agent_name'=>array('str'=>'txt','labl'=>EST_GEN_AGENT.' '.EST_GEN_NAME,'cls'=>'xlarge','chks'=>array('noblank')),
      'agent_txt1'=>array('type'=>'textarea','str'=>'txt','labl'=>'no-lab','cls'=>'xxlarge WD100','plch'=>EST_GEN_ADDITIONAL,'rows'=>3),
      ),
      
    'estate_user'=>array(
      'user_id'=>array('str'=>'int'),
      'user_image'=>array('type'=>'hidden','str'=>'text'),
      'user_email'=>array('type'=>'hidden','str'=>'text'),
      'user_imageurl'=>array('type'=>'hidden','str'=>'text'),
      'user_loginname'=>array('type'=>'hidden','str'=>'text'),
      'user_name'=>array('type'=>'hidden','str'=>'text'),
      'user_signature'=>array('type'=>'hidden','str'=>'text'),
      ),
    
    'estate_contacts'=>array(
      'contact_idx'=>array('type'=>'idx','str'=>'int'),
      'contact_tabkey'=>array('type'=>'hidden','str'=>'int'),
      'contact_tabidx'=>array('type'=>'hidden','str'=>'int'),
      'contact_key'=>array('str'=>'txt','labl'=>'eSelect','cls'=>'xlarge'),
      'contact_ord'=>array('type'=>'hidden','str'=>'int'),
      'contact_data'=>array('str'=>'txt','labl'=>'eNone','cls'=>'xlarge'),
      ),
    
    
    'estate_events'=>array(
      'event_idx'=>array('type'=>'idx','str'=>'int'),
      'event_propidx'=>array('type'=>'hidden','str'=>'int'),
      'event_name'=>array('type'=>'text','cls'=>'xlarge','chks'=>array('noblank'),'labl'=>EST_EVNT_NAME),
      'event_agt'=>array('type'=>'hidden','str'=>'int'),
      'event_type'=>array(
        'type'=>'select',
        'str'=>'int',
        'cls'=>'xlarge',
        'labl'=>EST_EVNT_TYPE,
        'src'=>array('tbl'=>'self','idx'=>'key','map'=>null,'opts'=>$EVENTTYPES)
        ),
      'event_stat'=>array(
        'type'=>'select',
        'str'=>'int',
        'cls'=>'xlarge',
        'labl'=>LAN_STATUS,
        'src'=>array('tbl'=>'self','idx'=>'key','map'=>null,'opts'=>array(EST_GEN_NOTCONFIRMED,EST_GEN_CONFIRMED))
        ),
      'event_class'=>array('type'=>'hidden','str'=>'int'),
      'event_start'=>array('type'=>'datetime','cls'=>'xlarge','chks'=>array('noblank'),'labl'=>EST_EVNT_START),
      'event_end'=>array('type'=>'datetime','cls'=>'xlarge','chks'=>array('noblank'),'labl'=>EST_EVNT_END),
      'event_timezone'=>array('type'=>'hidden','str'=>'int'),
      'event_text'=>array('type'=>'textarea','str'=>'txt','labl'=>EST_EVNT_TEXTLAB,'cls'=>'xxlarge WD100','plch'=>EST_EVNT_DETAILS,'rows'=>4),
      ),
    
    'estate_properties'=>array(
      'prop_idx'=>array('type'=>'idx','str'=>'int'),
      'prop_status'=>array('hlpm'=>'estHlp-list0'),
      'prop_name'=>array('hlpm'=>'estHlp-list0'),
      'prop_views'=>array('type'=>'hidden','str'=>'int'),
      'prop_agency'=>array('type'=>'hidden','str'=>'int'),
      'prop_agent'=>array(
        'type'=>'hidden',
        'str'=>'int',
        'cls'=>'xlarge',
        'hlpm'=>'estHlp-list0',
        'src'=>array('tbl'=>'estate_agents','idx'=>'agent_idx','map'=>array('agent_idx','agent_name')),
        ),
      'prop_addr_lookup'=>array('type'=>'hidden','str'=>'txt'),
      'prop_listype'=>array(
        'type'=>'select',
        'str'=>'int',
        'hlpm'=>'estHlp-list0',
        'src'=>array('tbl'=>'self','idx'=>'key','map'=>$GLOBALS['EST_LISTTYPE1']),
        ),
      'prop_zoning'=>array(
        'type'=>'eselect',
        'str'=>'int',
        'cls'=>'xlarge',
        'hlpm'=>'estHlp-list4',
        'src'=>array('tbl'=>'estate_zoning','idx'=>'zoning_idx','perm'=>array(1,2,3),'map'=>array('zoning_idx','zoning_name')),
        'fltrs'=>array('prop_type'=>array('map'=>array('zoning_idx','listype_zone')))
        ),
      'prop_type'=>array(
        'type'=>'eselect',
        'str'=>'int',
        'cls'=>'xlarge',
        'hlpm'=>'estHlp-list5',
        'src'=>array('tbl'=>'estate_listypes','idx'=>'listype_idx','perm'=>array(1,2,3),'map'=>array('listype_idx','listype_name'),'req'=>array('prop_zoning','listype_zone',EST_PROP_MSG_ZONE1)),
        ),
      'prop_timezone'=>array('hlpm'=>'estHlp-sched0'),
      'prop_datetease'=>array('hlpm'=>'estHlp-sched0'),
      'prop_dateprevw'=>array('hlpm'=>'estHlp-sched0'),
      'prop_datelive'=>array('hlpm'=>'estHlp-sched0'),
      'prop_datepull'=>array('hlpm'=>'estHlp-sched0'),
      'prop_hours'=>array('hlpm'=>'estHlp-sched0'),
      'prop_addr1'=>array('hlpm'=>'estHlp-addr0'),
      'prop_addr2'=>array('hlpm'=>'estHlp-addr0'),
      'prop_country'=>array(
        'type'=>'select',
        'str'=>'txt',
        'cls'=>'xlarge',
        'hlpm'=>'estHlp-addr2',
        'src'=>array('tbl'=>'estate_nations','idx'=>'nation_idx','map'=>array('nation_idx','nation_name')),
        'fltrs'=>array(
          'prop_state'=>array('map'=>array('nation_idx','state_country'),'fetch'=>array('prop_state','prop_county','prop_city','prop_zip','prop_subdiv'))
          )
        ),
      'prop_state'=>array(
        'type'=>'eselect',
        'str'=>'int',
        'cls'=>'xlarge',
        'hlpm'=>'estHlp-addr2',
        'src'=>array('perm'=>array(4,4),'tbl'=>'estate_states','idx'=>'state_idx','map'=>array('state_idx','state_name'),'req'=>array('prop_country','state_country',EST_PROP_MSG_NAT)),
        'fltrs'=>array(
          'prop_county'=>array('map'=>array('state_idx','cnty_state'),'fetch'=>array('prop_county','prop_city','prop_zip','prop_subdiv'))
          )
        ),
      'prop_county'=>array(
        'type'=>'eselect',
        'str'=>'int',
        'cls'=>'xlarge',
        'hlpm'=>'estHlp-addr2',
        'src'=>array('tbl'=>'estate_county','idx'=>'cnty_idx','map'=>array('cnty_idx','cnty_name'),'req'=>array('prop_state','cnty_state',EST_PROP_MSG_ST)),
        'fltrs'=>array(
          'prop_city'=>array('map'=>array('cnty_idx','city_county'),'fetch'=>array('prop_city','prop_zip','prop_subdiv'))
          )
        ),
      'prop_city'=>array(
        'type'=>'eselect',
        'str'=>'int',
        'cls'=>'xlarge',
        'hlpm'=>'estHlp-addr2',
        'chng'=>array('estResetSubDivs'),
        'src'=>array('tbl'=>'estate_city','idx'=>'city_idx','map'=>array('city_idx','city_name'),'req'=>array('prop_county','city_county',EST_PROP_MSG_CNTY)),
        'fltrs'=>array(
          'prop_zip'=>array('map'=>array('city_idx','city_idx',',','city_zip')),
          'prop_subdiv'=>array('map'=>array('city_idx','subd_city'),'blank'=>1,'fetch'=>array('prop_subdiv'))
          )
        ),
      'prop_features'=>array(
        'type'=>'commaline',
        'cls'=>'xlarge estJSmaxchar',
        'labl'=>EST_GEN_FEATURES,
        'src'=>array('tbl'=>'self','idx'=>'prop_idx','map'=>array('prop_features','prop_features',',')),
        'plch'=>EST_PROP_FEATURESPLCHLDR
        ),
      'prop_zip'=>array(
        'type'=>'select',
        'cls'=>'xlarge',
        'src'=>array('tbl'=>'estate_city','idx'=>'city_idx','map'=>array('city_idx','city_zip',','),'req'=>array('prop_city','city_zip',EST_PROP_MSG_CITY))
        ),
      'prop_subdiv'=>array(
        'type'=>'eselect',
        'hlpm'=>'estHlp-comm2',
        'str'=>'int',
        'cls'=>'xlarge',
        'chng'=>array('estGetSubDivs'),
        'src'=>array('tbl'=>'estate_subdiv','idx'=>'subd_idx','map'=>array('subd_idx','subd_name'),'req'=>array('prop_city','subd_city',EST_PROP_MSG_CITY))
        ),
      
      'prop_modelname'=>array('hlpm'=>'estHlp-detail0'),
      'prop_intsize'=>array('hlpm'=>'estHlp-detail2'),
      'prop_roofsize'=>array('hlpm'=>'estHlp-detail2'),
      'prop_landsize'=>array('hlpm'=>'estHlp-detail5'),
      'prop_summary'=>array('hlpm'=>'estHlp-detail6'),
      'prop_description'=>array('hlpm'=>'estHlp-detail7'),
      ),
    
    'estate_spaces'=>array(
      'space_idx'=>array('type'=>'idx','str'=>'int'),
      'space_propidx'=>array('type'=>'hidden','str'=>'int','par'=>array('estate_properties','prop_idx')),
      'space_grpid'=>array(
        'type'=>'eselect',
        'str'=>'int',
        'cls'=>'xlarge',
        'labl'=>EST_GEN_GROUP,
        'src'=>array('tbl'=>'estate_group','idx'=>'group_idx','perm'=>array(1,2,3),'map'=>array('group_idx','group_name')),
        ),
      'space_catid'=>array(
        'type'=>'eselect',
        'str'=>'int',
        'cls'=>'xlarge',
        'labl'=>LAN_CATEGORY,
        'src'=>array('tbl'=>'estate_featcats','idx'=>'featcat_idx','perm'=>array(1,2,3),'map'=>array('featcat_idx','featcat_name','req'=>array('prop_zoning','featcat_zone',EST_PROP_MSG_ZONE1))), //,'fltr'=>array()
        'fltrs'=>array('grp'=>array('tbl'=>'estate_features','idx'=>'feature_idx','map'=>array('featcat_idx','featcat_name')))
        ),
      'space_ord'=>array('type'=>'hidden','str'=>'int'),
      'space_name'=>array('type'=>'text','cls'=>'xlarge','chks'=>array('noblank')),
      'space_loc'=>array('type'=>'text','cls'=>'xlarge','chks'=>array('noblank')),
      'space_dimu'=>array(
        'type'=>'select',
        'str'=>'int',
        'cls'=>'medium',
        'src'=>array('tbl'=>'self','idx'=>'key','map'=>$DIMUOPT),
        ),
      'space_dimx'=>array('type'=>'number','cls'=>'small','str'=>'int','attr'=>array('min'=>0)),
      'space_dimy'=>array('type'=>'number','cls'=>'small','str'=>'int','attr'=>array('min'=>0)),
      'space_dimxy'=>array('type'=>'number','cls'=>'small','str'=>'int','attr'=>array('min'=>0)),
      'space_description'=>array('type'=>'textarea','cls'=>'xxlarge'),
      ),
    
    'estate_media'=>array(
      'media_idx'=>array('type'=>'idx'),
      'media_propidx'=>array('type'=>'hidden','str'=>'int'),
      'media_lev'=>array('type'=>'hidden','str'=>'int'),
      'media_levidx'=>array('type'=>'hidden','str'=>'int'),
      'media_levord'=>array('type'=>'hidden','str'=>'int'),
      'media_galord'=>array('type'=>'hidden','str'=>'int'),
      'media_asp'=>array('type'=>'hidden','str'=>'txt'),
      'media_type'=>array('type'=>'hidden','str'=>'int'),
      'media_thm'=>array('type'=>'hidden','str'=>'txt'),
      'media_full'=>array('type'=>'hidden','str'=>'txt'),
      'media_name'=>array('type'=>'text','cls'=>'xlarge','chks'=>array('noblank'),'labl'=>'Caption')
      ),
    
    'estate_group'=>array(
      'group_idx'=>array('type'=>'idx'),
      'group_zone'=>array(
        'type'=>'select',
        'str'=>'int',
        'cls'=>'xlarge',
        'labl'=>EST_GEN_ZONING,
        'src'=>array('tbl'=>'estate_zoning','idx'=>'zoning_idx','map'=>array('zoning_idx','zoning_name')),
        ),
      'group_lev'=>array('type'=>'hidden','str'=>'int','chks'=>array('estNoClear')),
      'group_name'=>array('type'=>'text','cls'=>'xlarge','plch'=>EST_GRP_GROUPPLCH,'chks'=>array('noblank'))
      ),
    
    'estate_grouplist'=>array(
      'grouplist_idx'=>array('type'=>'idx'),
      'grouplist_propidx'=>array('type'=>'hidden','str'=>'int','par'=>array('estate_properties','prop_idx')),
      'grouplist_groupidx'=>array(
        'type'=>'eselect',
        'str'=>'int',
        'cls'=>'xlarge',
        'src'=>array('tbl'=>'estate_group','idx'=>'group_idx','map'=>array('group_idx','group_name')),
        ),
      'grouplist_ord'=>array('type'=>'hidden','str'=>'int')
      ),
    
    'estate_featcats'=>array(
      'featcat_idx'=>array('type'=>'idx'),
      'featcat_zone'=>array(
        'type'=>'select',
        'str'=>'int',
        'cls'=>'xlarge oneBtn',
        'labl'=>EST_GEN_ZONING,
        'src'=>array('tbl'=>'estate_zoning','idx'=>'zoning_idx','map'=>array('zoning_idx','zoning_name'),'zero'=>'- '.EST_GEN_ALL.' '.EST_GEN_CATEGORIES.' -'),
        ),
      'featcat_lev'=>array('type'=>'hidden','str'=>'int','chks'=>array('estNoClear')),
      'featcat_name'=>array('type'=>'text','cls'=>'xlarge','plch'=>EST_FEAT_NAMEPLCH,'chks'=>array('noblank'))
      ),
    
    'estate_features'=>array(
      'feature_idx'=>array('type'=>'idx'),
      'feature_ele'=>array(
        'type'=>'selfselect',
        'str'=>'int',
        'labl'=>EST_OPTIONS,
        'cls'=>'xlarge',
        'src'=>array(
          'tbl'=>'estate_features',
          'idx'=>'feature_idx',
          'map'=>null,
          'hides'=>array('feature_opts','tr'),
          'opts'=>array(LAN_NO.' '.EST_OPTIONS,EST_GEN_HAS.' '.EST_OPTIONS)
          ),
        ),
      'feature_cat'=>array(
        'type'=>'eselect',
        'str'=>'int',
        'cls'=>'xlarge oneBtn',
        'labl'=>LAN_CATEGORY,
        'src'=>array('tbl'=>'estate_featcats','idx'=>'featcat_idx','map'=>array('featcat_idx','featcat_name'),'grep'=>array('featcat_lev')),
        ),
      'feature_name'=>array('type'=>'text','cls'=>'xlarge','chks'=>array('noblank'),'plch'=>EST_GEN_FEATURE.' '.LAN_NAME),
      'feature_opts'=>array(
        'type'=>'commalist',
        'cls'=>'xlarge',
        'labl'=>EST_GEN_OPTLIST,
        'src'=>array('tbl'=>'estate_features','idx'=>'feature_idx','map'=>array('feature_idx','feature_opts',',')),
        'plch'=>EST_PROP_FEATOPTLISTPLC,
        'hint'=>EST_PROP_FEATOPTLISTPLC,
        'inf'=>EST_PROP_FEATOPTLISTPLC,
        ),
      ),
    
    'estate_featurelist'=>array(
      'featurelist_idx'=>array('type'=>'idx'),
      'featurelist_propidx'=>array('type'=>'hidden','str'=>'int'),
      'featurelist_lev'=>array('type'=>'hidden','str'=>'int'), //src table name (estate_sects[0][0] = 'estate_spaces')
      'featurelist_levidx'=>array('type'=>'hidden','str'=>'int'), //idx of src table (estate_sects[0][1] = 'space_idx')
      'featurelist_key'=>array('type'=>'hidden','str'=>'int'),
      'featurelist_dta'=>array(
        'type'=>'commalist',
        'cls'=>'xlarge',
        'labl'=>EST_GEN_OPTLIST,
        'max'=>75,
        'src'=>array('tbl'=>'estate_featurelist','idx'=>'featurelist_idx','map'=>array('featurelist_idx','featurelist_dta',',')),
        'plch'=>EST_PROP_FEATOPTLISTPLC,
        ),
      ),
    
    'estate_zoning'=>array(
      'zoning_idx'=>array('type'=>'idx'),
      'zoning_name'=>array('type'=>'text','cls'=>'xlarge','chks'=>array('noblank'))
      ),
    
    'estate_states'=>array(
      'state_idx'=>array('type'=>'idx'),
      'state_name'=>array('type'=>'text','cls'=>'large','chks'=>array('noblank')),
      'state_init'=>array('type'=>'text','cls'=>'small'),
      'state_country'=>array(
        'type'=>'eselect',
        'cls'=>'xlarge',
        'src'=>array('tbl'=>'estate_nations','idx'=>'nation_idx','map'=>array('nation_idx','nation_name'))
        ),
      'state_url'=>array('text','cls'=>'xlarge','labl'=>LAN_WEBSITE,'plch'=>'www.somesite.com')
      ),
    
    'estate_county'=>array(
      'cnty_idx'=>array('type'=>'idx'),
      'cnty_name'=>array('type'=>'text','cls'=>'xlarge','chks'=>array('noblank')),
      'cnty_state'=>array(
        'type'=>'eselect',
        'str'=>'int',
        'cls'=>'xlarge',
        'src'=>array('tbl'=>'estate_states','idx'=>'state_idx','perm'=>array(1,2,3),'map'=>array('state_idx','state_name'))
        ),
      'cnty_url'=>array('type'=>'text','cls'=>'xlarge','labl'=>LAN_WEBSITE,'plch'=>'www.somesite.com')
      ),
    
    'estate_city'=>array(
      'city_idx'=>array('type'=>'idx'),
      'city_name'=>array('type'=>'text','cls'=>'xlarge','chks'=>array('noblank')),
      'city_county'=>array(
        'type'=>'eselect',
        'str'=>'int',
        'cls'=>'xlarge',
        'src'=>array('tbl'=>'estate_county','idx'=>'cnty_idx','map'=>array('cnty_idx','cnty_name'))
        ),
      'city_zip'=>array(
        'type'=>'commalist',
        'cls'=>'xlarge',
        'labl'=>EST_PROP_POSTCODELIST,
        'src'=>array('tbl'=>'estate_city','idx'=>'city_idx','map'=>array('cnty_idx','city_zip',',')),
        'plch'=>'12345,65432,98754,etc'
        ),
      'city_timezone'=>array(
        'type'=>'select',
        'cls'=>'xlarge',
        'labl'=>EST_PROP_TIMEZONE,
        'src'=>array('tbl'=>'estate_timezone','idx'=>'tz_idx','map'=>array('tz_idx','tz_name')),
        ),
      'city_url'=>array('type'=>'text','cls'=>'xlarge','labl'=>LAN_WEBSITE,'plch'=>'www.somesite.com')
      ),
    
    'estate_subdiv'=>array(
      'subd_idx'=>array('type'=>'idx','tab'=>0),
      'subd_city'=>array(
        'type'=>'eselect',
        'tab'=>0,
        'str'=>'int',
        'cls'=>'xlarge',
        'src'=>array('tbl'=>'estate_city','idx'=>'city_idx','map'=>array('cnty_idx','cnty_name'))
        ),
      'subd_name'=>array('type'=>'text','tab'=>0,'cls'=>'xlarge','chks'=>array('noblank')),
      'subd_type'=>array(
        'type'=>'select',
        'tab'=>0,
        'labl'=>EST_GEN_TYPE,
        'str'=>'int',
        'cls'=>'xlarge',
        'src'=>array('tbl'=>'self','idx'=>'key','map'=>null,'opts'=>EST_GEN_SUBDIVTYPE)
        ),
      'subd_url'=>array('type'=>'text','tab'=>0,'cls'=>'xlarge','labl'=>LAN_WEBSITE,'plch'=>'www.somesite.com'),
      'subd_description'=>array(
        'type'=>'textarea',
        'tab'=>1,
        'cls'=>'xxlarge WD100',
        'cspan'=>2,
        'labl'=>'no-lab',
        'plch'=>EST_GEN_ADDITIONAL.' '.EST_GEN_INFO,
        'rows'=>16
        ),
      'subd_hoaname'=>array('type'=>'text','tab'=>2,'cls'=>'xlarge','labl'=>EST_PROP_HOANAME,'plch'=>EST_GEN_HOAPLCH),
      'subd_hoaweb'=>array('type'=>'text','tab'=>2,'cls'=>'xlarge','labl'=>EST_PROP_HOAWEB,'plch'=>'www.somesite.com'),
      'subd_hoafee'=>array('type'=>'number','tab'=>2,'cls'=>'large','str'=>'int','labl'=>EST_PROP_HOAFEE,'attr'=>array('min'=>0)),
      'subd_hoafrq'=>array(
        'type'=>'select',
        'tab'=>2,
        'str'=>'int',
        'labl'=>EST_PROP_HOAFRQ,
        'cls'=>'large',
        'src'=>array('tbl'=>'self','idx'=>'key','map'=>null,'opts'=>$GLOBALS['EST_HOAFREQ'])
        ),
      'subd_hoareq'=>array(
        'type'=>'select',
        'tab'=>2,
        'str'=>'int',
        'labl'=>EST_PROP_HOAREQ,
        'cls'=>'large',
        'src'=>array('tbl'=>'self','idx'=>'key','map'=>null,'opts'=>$GLOBALS['EST_HOAREQD'])
        ),
      'subd_hoaappr'=>array(
        'type'=>'select',
        'tab'=>2,
        'str'=>'int',
        'labl'=>EST_GEN_HOAAPPR1,
        'cls'=>'large',
        'src'=>array('tbl'=>'self','idx'=>'key','map'=>null,'opts'=>array(EST_GEN_NOTREQUIRED,EST_GEN_REQUIRED))
        ),
      ),
    
    'estate_listypes'=>array(
      'listype_idx'=>array('type'=>'idx'),
      'listype_zone'=>array(
        'type'=>'select',
        'str'=>'int',
        'cls'=>'xlarge',
        'labl'=>EST_PROP_LISTZONE,
        'src'=>array('tbl'=>'estate_zoning','idx'=>'zoning_idx','map'=>array('zoning_idx','zoning_name'))
        ),
      'listype_name'=>array('type'=>'text','cls'=>'xlarge','plch'=>EST_PROP_TYPEPLCH,'labl'=>EST_PROP_TYPE,'chks'=>array('noblank'))
      )
    );
  }


$EST_CONTACTBLS = array(
  0=>array('estate_properties','prop_idx','prop_name',EST_GEN_PROPERTY),
  1=>array('estate_subdiv','subd_idx','subd_name',EST_GEN_SUBDIVISION),
  2=>array('estate_clients','client_idx','client_name',EST_GEN_CLIENT), //seller ?
  3=>array('estate_clients','client_idx','client_name',EST_GEN_CLIENT), //buyer ?
  4=>array('estate_clients','client_idx','client_name',EST_GEN_CLIENT), // not used
  5=>array('estate_agencies','agency_idx','agency_name',EST_GEN_AGENCY),
  6=>array('estate_agents','agent_idx','agent_name',EST_GEN_AGENT)
  );
  // = $estateCore->estGetSectTlbKeys();

function estSects(){
  return array(
    0=>array('estate_subdiv','subd_idx','subd_name'),
    1=>array('estate_properties','prop_idx','prop_name'),
    2=>array('estate_spaces','space_idx','space_name')
    );
  }

function preg_grep_keys($pattern, $input, $flags = 0 ){
    $keys = preg_grep( $pattern, array_keys( $input ), $flags );
    $vals = array();
    if(count($keys) > 0){foreach ($keys as $key ){$vals[$key] = $input[$key];}}
    return $vals;
  }


function estMediaDirs(){}

?>