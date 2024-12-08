<?php
if(!defined('e107_INIT')){exit;}

e107::includeLan(e_PLUGIN.'estate/languages/'.e_LANGUAGE.'/'.e_LANGUAGE.'_msg.php');



class estate_listing_ui extends e_admin_ui{
  
	protected $pluginTitle		= EST_PLUGNAME;
	protected $pluginName		= 'estate';
//	protected $eventName		= 'estate-estate'; // remove comment to enable event triggers in admin. 		
	
  protected $table			= 'estate_properties';
	protected $pid				= 'prop_idx';
	protected $perPage			= 30; 
  
	protected $batchDelete		= true;
	protected $batchExport     = true;
	protected $batchCopy		= true;

	//protected $sortField		= 'prop_ord';
//	protected $sortParent      = 'somefield_parent';
//	protected $orderStep		= 50;
//	protected $treePrefix      = 'somefield_title';

  
  protected $listQry      = (ADMINPERMS !== '0' ? (EST_USERPERM == 2 ? "SELECT * FROM `#estate_properties` WHERE prop_agency = '".EST_AGENCYID."' OR prop_agent='".EST_AGENTID."'" : "SELECT * FROM `#estate_properties` WHERE prop_agent='".EST_AGENTID."' ") : '');
	
  protected $listOrder		= 'prop_idx DESC';
  
  protected $tabs				= array(
    EST_GEN_LISTING,
    EST_GEN_ADDRESS,
    EST_GEN_COMMUNITY,
    EST_GEN_SPACES,
    EST_GEN_DETAILS,
    EST_GEN_GALLERY,
    EST_GEN_SCHEDULING,
    EST_GEN_TEMPLAYOUT
    );
    
    
    //protected $preftabs = array();
    
		protected $fields = array (
      
      'checkboxes' => array (
        'title' => '',
        'type' => null,
        'data' => null,
        'width' => '5%',
        'thclass' => 'center',
        'forced' => 'value',
        'class' => 'center',
        'toggle' => 'e-multiselect',
        'readParms' =>  array (),
        'writeParms' =>  array (),
        ),
    
			'prop_idx' => array (
        'tab'=>0,
        'title' => 'Idx',
        'type' => 'hidden',
        'data' => 'int',
        'nolist'=>true,
        'width' => 'auto',
        'help' => '',
        'readParms' =>  array (),
        'writeParms' =>  array (),
        'class' => 'left',
        'thclass' => 'left',
        ),
			'prop_thmb' => array (
        'tab'=>0,
        'type' => 'method',
        'title' => EST_GEN_THUMBNAIL,
        'data' => false,
        'width' => '110px',
        'noedit'=>true,
        'readParms' => array(),
        'writeParms' => array(),
        'class' => 'left noPAD posREL',
        'thclass' => 'left',
        ),
			'prop_appr' => array (
        'tab'=>0,
        'type' => 'method',
        'data' => 'int',
        'title'=>EST_GEN_LISTING.' '.EST_GEN_APPROVAL,
        'readParms' => array(),
        'writeParms' => array(),
        'class' => 'left',
        'thclass' => 'left',
        ),
			'prop_views' => array (
        'tab'=>0,
        'type' => 'hidden',
        'data' => 'int',
        'readParms' => array(),
        'writeParms' => array(),
        'class' => 'left',
        'thclass' => 'left',
        ),
			'prop_saves' => array (
        'tab'=>0,
        'type' => 'hidden',
        'data' => 'int',
        'readParms' => array(),
        'writeParms' => array(),
        'class' => 'left',
        'thclass' => 'left',
        ),
      'prop_listaddr' => array (
        'tab'=>0,
        'type' => 'method',
        'data' => false,
        'noedit'=>true,
        'nolist'=>true,
        'width' => 'auto',
        'title'=>EST_GEN_NAMEADDRESS,
        'readParms' => array(),
        'writeParms' => array(), 
        'class' => 'left',
        'thclass' => 'left',
        ),
			'prop_name' => array (
        'tab'=>0,
        'title' => LAN_NAME,
        'type' => 'text',
        'data' => 'safestr',
        'width' => 'auto',
        'help' => EST_PROP_NAMEHLP,
        'readParms' =>  array (),
        'writeParms' =>  array ('size' => 'xxlarge','placeholder' => EST_PROP_NAME),
        'class' => 'left',
        'thclass' => 'left',
        ),
      'prop_sef' => array (
        'tab'=>0,
        'title' => EST_PROP_SEF,
        'type' => 'text',
        'batch'=>true,
        'inline'=>true,
        'noedit'=>false,
        'data' => 'str',
        'width' => 'auto',
        'nolist'=>true,
        'help' => EST_PROP_SEFHLP,
        'readParms' => '',
        'writeParms' =>
          'sef=prop_name&size=xxlarge',
        'class' => 'left',
        'thclass' => 'left',
        ),
			'prop_agent'=>array(
        'tab'=>0,
        'title'=>EST_GEN_LISTAGENT,
        'type' => 'method',
        'data' => 'int',
        'width' => 'auto',
        'help'=>EST_PROP_LISTAGENTHLP.'<ul><li>'.EST_PROP_LISTAGENTHLP1.'</li><li>'.EST_PROP_LISTAGENTHLP2.'</li><li>'.EST_PROP_LISTAGENTHLP3.'</li></ul>',
        'writeParms' => array('size'=>'xlarge'),
        'class' => 'left noPAD',
        'thclass' => 'left',
        'filter' => true,
        'batch' => false,
        ),
			'prop_agency' => array (
        'tab'=>0,
        'type' => 'hidden',
        'data' => 'int',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array(),
        'class' => 'left',
        'thclass' => 'left',
        ),
			'prop_status'=>array(
        'tab'=>0,
        'title'=>EST_PROP_STATUS,
        'type' => 'dropdown',
        'data' => 'int',
        'width' => 'auto',
        'help'=>EST_PROP_STATUSHLP,
        /*'nolist'=>true,*/
        'inline' => 'value',
        'writeParms' => array('size'=>'xlarge'),
        'class' => 'left',
        'thclass' => 'left',
        'filter' => true,
        'batch' => false,
        ),
      'prop_listype'=>array(
        'tab'=>0,
        'title'=>EST_PROP_LISTYPE,
        'type' => 'dropdown',
        'data' => 'int',
        'width' => 'auto',
        //'inline' => 'value',
        'help'=>EST_PROP_LISTYPEHLP,
        'readParms' => array(),
        'writeParms' => array('size'=>'xlarge'),
        'class' => 'left',
        'thclass' => 'left',
        'filter' => true,
        'batch' => false,
        ),
      'prop_locale' => array (
        'tab'=>0,
        'type' => 'hidden',
        'data' => 'safestr',
        'nolist'=>true,
        ),
			'prop_origprice' => array (
        'tab'=>0,
        'title' => EST_PROP_ORIGPRICE,
        'type' => 'text',
        'data' => 'int',
        'nolist'=>true,
        'width' => 'auto',
        'help' => EST_PROP_ORIGPRICEHLP,
        'readParms' => array(),
        'writeParms' => array('type'=>'number','size'=>'medium estNoLeftBord'),
        'class' => 'right',
        'thclass' => 'right',
        ),
			'prop_listprice' => array (
        'tab'=>0,
        'title' => EST_PROP_LISTPRICE,
        'type' => 'text',
        'data' => 'int',
        'width' => 'auto',
        'inline' => 'value',
        'help' => EST_PROP_LISTPRICEHLP,
        'readParms' => array(),
        'writeParms' => array('type'=>'number','size'=>'medium estNoLeftBord'),
        'class' => 'right',
        'thclass' => 'right',
        ),
      'prop_leasedur'=>array(
        'tab'=>0,
        'title'=>EST_PROP_LEASEDUR,
        'type' => 'dropdown',
        'data' => 'int',
        'nolist'=>true,
        'width' => 'auto',
        'help'=>'',
        'readParms' => array(),
        'writeParms' => array('size'=>'large'),
        'class' => 'left',
        'thclass' => 'left',
        ),
			'prop_zoning' => array (
        'tab'=>0,
        'title' => EST_PROP_LISTZONE,
        'type' => 'dropdown',
        'data' => 'int',
        'width' => 'auto',
        'help' => EST_PROP_ZONEHLP,
        'readParms' => array(), 
        'writeParms' => array('size'=>'xlarge estSelNoBlank'),
        'class' => 'left',
        'thclass' => 'left',
        'filter' => true,
        'batch' => false,
        ),
			'prop_type' => array (
        'tab'=>0,
        'title' => EST_PROP_TYPE,
        'type' => 'dropdown',
        'data' => 'int',
        'width' => 'auto',
        'help' => EST_PROP_TYPEHLP,
        'readParms' => array(), 
        'writeParms' => array('size'=>'xlarge'),
        'class' => 'left',
        'thclass' => 'left',
        'filter' => true,
        'batch' => false,
        ),
			'prop_mlsno' => array (
        'tab'=>0,
        'title' => EST_PROP_MLSNO,
        'type' => 'text',
        'data' => 'safestr',
        'width' => 'auto',
        /*'inline' => 'value',*/
        'help' => EST_PROP_MLSNOHLP,
        'readParms' => array(),
        'writeParms' => array('placeholder'=>EST_PROP_MLSNO),
        'class' => 'right',
        'thclass' => 'right',
        ),
			'prop_parcelid' => array (
        'tab'=>0,
        'title' => EST_PROP_PARCELID,
        'type' => 'text',
        'data' => 'safestr',
        'width' => 'auto',
        'inline' => 'value',
        'help' => EST_PROP_PARCELIDHLP,
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array(),
        'class' => 'right',
        'thclass' => 'right',
        ),
			'prop_lotid' => array (
        'tab'=>0,
        'title' => EST_PROP_LOTID,
        'type' => 'text',
        'data' => 'safestr',
        'width' => 'auto',
        'inline' => 'value',
        'help' => EST_PROP_LOTIDHLP,
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array(),
        'class' => 'right',
        'thclass' => 'right',
        ),
			'prop_leasefreq' => array (
        'tab'=>0,
        'type' => 'hidden',
        'data' => 'int',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array(),
        'class' => 'left',
        'thclass' => 'left',
        ),
			'prop_currency' => array (
        'tab'=>0,
        'type' => 'hidden',
        'data' => 'int',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array(),
        'class' => 'left',
        'thclass' => 'left',
        ),
			'prop_uidcreate' => array (
        'tab'=>0,
        //'title' => EST_PROP_UIDCREATE,
        'type' => 'hidden',
        'data' => 'int',
        'width' => 'auto',
        'help' => '',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array(),
        'class' => 'left',
        'thclass' => 'left',
        'filter' => true,
        ),
			
      'prop_addr_lookup' => array (
        'tab'=>1,
        'type' => 'text',
        'data' => 'safestr',
        'width' => 'auto',
        'help' => '',
        'noedit'=>true,
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array(),
        'class' => 'left',
        'thclass' => 'left',
        'filter' => false,
        'batch' => false,
        ),
			'prop_addr1' => array (
        'tab'=>1,
        'title' => EST_PROP_ADDR1,
        'type' => 'text',
        'data' => 'safestr',
        'inline' => 'value',
        'width' => 'auto',
        'help' => '',
        'nolist'=>true,
        'readParms' => array(), 
        'writeParms' => array('size'=>'xxlarge estPropAddr','placeholder'=>EST_PLCH96),
        'class' => 'left',
        'thclass' => 'left',
        'filter' => false,
        'batch' => false,
        ),
			'prop_addr2' => array (
        'tab'=>1,
        'title' => EST_PROP_ADDR2,
        'type' => 'text',
        'data' => 'safestr',
        'inline' => 'value',
        'width' => 'auto',
        'help' => '',
        'nolist'=>true,
        'readParms' => array(), 
        'writeParms' => array('size'=>'xlarge estPropAddr','placeholder' => EST_PLCH96A),
        'class' => 'left',
        'thclass' => 'left',
        'filter' => false,
        'batch' => false,
        ),
			'prop_country' => array (
        'tab'=>1,
        'title' => EST_PROP_COUNTRY,
        'type' => 'dropdown',
        'data' => 'str',
        /*'nolist'=>true,*/
        'width' => 'auto',
        'help' => EST_PROP_COUNTRYHLP,
        'readParms' => array (), 
        'writeParms' => array('size'=>'large estPropAddr'),
        'class' => 'left',
        'thclass' => 'left',
        'filter' => false,
        'batch' => false,
        ),
      'prop_state' => array (
        'tab'=>1,
        'title' => EST_PROP_STATE,
        'type' => 'dropdown',
        'data' => 'str',
        /*'nolist'=>true,*/
        'width' => 'auto',
        'help' => EST_PROP_STATEHLP,
        'readParms' => array (), 
        'writeParms' => array('size'=>'large estPropAddr'),
        'class' => 'left',
        'thclass' => 'left',
        'filter' => true,
        'batch' => false,
        ),
      'prop_county' => array (
        'tab'=>1,
        'title' => EST_PROP_COUNTY,
        'type' => 'dropdown',
        'data' => 'str',
        /*'nolist'=>true,*/
        'width' => 'auto',
        'help' => EST_PROP_COUNTYHLP,
        'readParms' => array(), 
        'writeParms' => array('size'=>'large estPropAddr'),
        'class' => 'left',
        'thclass' => 'left',
        'filter' => true,
        'batch' => false,
        ),
      
			'prop_city' => array (
        'tab'=>1,
        'title' => EST_PROP_CITY,
        'type' => 'dropdown',
        'data' => 'int',
        'width' => 'auto',
        'help' => EST_PROP_CITYHLP,
        /*'nolist'=>true,*/
        'readParms' => array(), 
        'writeParms' => array('size'=>'large estPropAddr'),
        'class' => 'left',
        'thclass' => 'left',
        'filter' => true,
        'batch' => false,
        ),
			'prop_zip' => array (
        'tab'=>1,
        'title' => EST_PROP_POSTCODE,
        'type' => 'method',
        'data' => 'int',
        'width' => 'auto',
        'help' => EST_PROP_POSTCODEHLP,
        'nolist'=>true,
        'readParms' =>  array(), 
        'writeParms' =>  array('size'=>'large'),
        'class' => 'left',
        'thclass' => 'left',
        'filter' => true,
        'batch' => false,
        ),
      'prop_map' => array (
        'tab'=>1,
        'type' => 'method',
        'data' => false,
        'width' => 'auto',
        'nolist'=>true,
        'title'=>'<div id="est_prop_SrchRes" class="estMapBtnCont"></div>',
        'readParms' => array(),
        'writeParms' => array(),
        'class' => 'left',
        'thclass' => 'left',
        ),
			'prop_citypreview' => array (
        'tab'=>1,
        'title'=>EST_GEN_CITYPREVIEW,
        'type' => 'method',
        'data' => false,
        'width' => 'auto',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array(),//'nolabel'=>1
        'class' => 'left',
        'thclass' => 'left',
        ),
			'prop_zoom' => array (
        'tab'=>1,
        'type' => 'hidden',
        'data' => 'safestr',
        'width' => 'auto',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array(),
        'class' => 'left',
        'thclass' => 'left',
        ),
      'prop_lat' => array (
        'tab'=>1,
        'type' => 'hidden',
        'data' => 'safestr',
        'width' => 'auto',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array(),
        'class' => 'left',
        'thclass' => 'left',
        ),
			'prop_lon' => array (
        'tab'=>1,
        'title' => EST_PROP_LON,
        'type' => 'hidden',
        'data' => 'safestr',
        'width' => 'auto',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array(),
        'class' => 'left',
        'thclass' => 'left',
        ),
      'prop_geoarea' => array (
        'tab'=>1,
        'title' => EST_PROP_GEOAREA,
        'type' => 'hidden',
        'data' => 'safestr',
        'width' => 'auto',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array(),
        'class' => 'left',
        'thclass' => 'left',
        ),
      
			'prop_subdiv' => array (
        'tab'=>2,
        'title' => EST_GEN_COMMUNITYSUBDIV,
        'type' => 'dropdown', //hidden
        'data' => 'int',
        'nolist'=>true,
        'width' => 'auto',
        'help' => EST_PROP_SUBDIVHLP,
        'readParms' => array(), 
        'writeParms' => array('size' => 'large'),
        'class' => 'left',
        'thclass' => 'left',
        'filter' => true,
        'batch' => false,
        ),
			'prop_hoaappr' => array (
        'tab'=>2,
        'type' => 'boolean',
        'data' => 'int',
        'title' => EST_PROP_HOAAPPR,
        'nolist'=>true,
        'help' => EST_PROP_HOAAPPRHLP,
        'width' => 'auto',
        'readParms' => array(), 
        'writeParms' => array('size'=>'large'),
        'class' => 'left',
        'thclass' => 'left',
        'filter' => false,
        'batch' => false,
        ),
			'prop_hoafee' => array (
        'tab'=>2,
        'title' => EST_PROP_HOAFEES,
        'type' => 'text',
        'data' => 'str',
        'width' => 'auto',
        'nolist'=>true,
        'help' => EST_PROP_HOAFEESHLP,
        'readParms' => array(), 
        'writeParms' => array('size'=>'medium estNoRightBord FL','min'=>0,'pattern'=>'^\d*(\.\d{0,2})?$','placeholder'=>'123.45'),
        'class' => 'left',
        'thclass' => 'left',
        'filter' => false,
        'batch' => false,
        ),
			'prop_hoaland' => array (
        'tab'=>2,
        'type' => 'boolean',
        'data' => 'int',
        'title' => EST_PROP_HOALAND,
        'nolist'=>true,
        'help' => EST_PROP_HOALANDHLP,
        'width' => 'auto',
        'readParms' => array(), 
        'writeParms' => array('size'=>'large'),
        'class' => 'left',
        'thclass' => 'left',
        'filter' => false,
        'batch' => false,
        ),
			'prop_landfee' => array (
        'tab'=>2,
        'title' => EST_PROP_LANDLEASE,
        'type' => 'text',
        'data' => 'str',
        'width' => 'auto',
        'inline' => 'value',
        'help' => EST_PROP_LANDLEASEHLP,
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array('type'=>'number','size'=>'medium estNoRightBord FL','pattern'=>'^\d*(\.\d{0,2})?$','placeholder'=>'123.45'),
        'class' => 'right',
        'thclass' => 'right',
        ),
			'prop_landfreq' => array (
        'tab'=>2,
        'type' => 'hidden',
        'data' => 'int',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array(),
        'class' => 'left',
        'thclass' => 'left',
        ),
			'prop_hoareq' => array (
        'tab'=>2,
        'type' => 'hidden',
        'data' => 'int',
        'nolist'=>true,
        'width' => 'auto',
        'readParms' => array(), 
        'writeParms' => array('size'=>'large'),
        'class' => 'left',
        'thclass' => 'left',
        'filter' => false,
        'batch' => false,
        ),
      'prop_hoafrq' => array (
        'tab'=>2,
        'type' => 'hidden',
        'data' => 'int',
        'width' => 'auto',
        'nolist'=>true,
        'help' => '',
        'readParms' => array(), 
        'writeParms' => array(),
        'class' => 'left',
        'thclass' => 'left',
        'filter' => false,
        'batch' => false,
        ),
      
			'prop_comminuty' => array (
        'tab'=>2,
        'title'=>EST_GEN_COMMUNITYPREVIEW,
        'type' => 'method',
        'data' => false,
        'width' => 'auto',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array(),//'nolabel'=>1
        'class' => 'left',
        'thclass' => 'left',
        ),
      
			'prop_spaces' => array (
        'tab'=>3,
        'type' => 'method',
        'data' => false,
        'width' => 'auto',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array('nolabel'=>1),
        'class' => 'left',
        'thclass' => 'left',
        ),
      
        
			'prop_flag' => array (
        'tab'=>4,
        'title' => EST_PROP_FLAG,
        'type' => 'text',
        'data' => 'safestr',
        'width' => 'auto',
        'help' => EST_PROP_FLAGHLP,
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array('size' => 'xlarge','placeholder'=>EST_PROP_FLAGPLCHLDR),
        'class' => 'left',
        'thclass' => 'left',
        ),
      
      'prop_summary' => array (
        'tab'=>4,
        'title' => LAN_SUMMARY,
        'type' => 'text',
        'data' => 'safestr',
        'width' => 'auto',
        'help' => EST_PROP_SUMMARYHLP,
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array('size' => 'xxlarge estJSmaxchar','placeholder'=>EST_PROP_SUMMARYPLCHLDR),
        'class' => 'left',
        'thclass' => 'left',
        ),
			'prop_description' => array (
        'tab'=>4,
        'title' => LAN_DESCRIPTION,
        'type' => 'textarea',
        'data' => 'str',
        'width' => 'auto',
        'nolist'=>true,
        'help' => EST_PROP_DESCRIPTIONHLP,
        'readParms' => array(),
        'writeParms' => array('size' => 'xxlarge','counter'=>0,''=>'','placeholder'=>EST_PROP_DESCRIPTIONPLCHLDR),
        'class' => 'left',
        'thclass' => 'left',
        ),
			
      'prop_bedmain' => array (
        'tab'=>4,
        'title' => EST_GEN_BEDROOMS,
        'type' => 'method',
        'data' => 'int',
        'width' => 'auto',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array('size' => 'small'),
        'class' => 'left',
        'thclass' => 'left',
        ),
      'prop_bedtot' => array (
        'tab'=>4,
        'title' => EST_GEN_BEDROOMS,
        'type' => 'method',
        'data' => 'int',
        'width' => 'auto',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array('size' => 'small'),
        'class' => 'left',
        'thclass' => 'left',
        ),
			'prop_bathtot' => array (
        'tab'=>4,
        'title' => EST_GEN_BATHROOMS,
        'type' => 'method',
        'data' => 'int',
        'width' => 'auto',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array('size' => 'small'),
        'class' => 'left',
        'thclass' => 'left',
        ),
			'prop_bathfull' => array (
        'tab'=>4,
        'title' => EST_GEN_BATHROOMS,
        'type' => 'method',
        'data' => 'int',
        'width' => 'auto',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array('size' => 'small'),
        'class' => 'left',
        'thclass' => 'left',
        ),
			'prop_bathhalf' => array (
        'tab'=>4,
        'title' => EST_GEN_BATHROOMS,
        'type' => 'method',
        'data' => 'int',
        'width' => 'auto',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array('size' => 'small'),
        'class' => 'left',
        'thclass' => 'left',
        ),
			'prop_bathmain' => array (
        'tab'=>4,
        'title' => EST_GEN_BATHROOMS,
        'type' => 'method',
        'data' => 'int',
        'width' => 'auto',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array('size' => 'small'),
        'class' => 'left',
        'thclass' => 'left',
        ),
			'prop_features' => array (
        'tab'=>4,
        'title' => EST_GEN_FEATURES,
        'type' => 'textarea',
        'data' => 'str',
        'width' => 'auto',
        'nolist'=>true,
        'help' => EST_PROP_FEATURESHLP,
        'readParms' => array(),
        'writeParms' => array('other'=>array('id'=>'prop-features'),'size'=>'xxlarge estJSmaxchar','counter'=>0,'rows'=>1,'maxlength'=>255,'placeholder'=>EST_PROP_FEATURESPLCHLDR),
        'class' => 'left',
        'thclass' => 'left',
        ),
			'prop_dimu1' => array (
        'tab'=>4,
        'type' => 'hidden',
        'data' => 'int',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array(),
        'class' => 'left',
        'thclass' => 'left',
        ),
      
			'prop_dimu2' => array (
        'tab'=>4,
        'type' => 'hidden',
        'data' => 'int',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array(),
        'class' => 'left',
        'thclass' => 'left',
        ),
      
			'prop_modelname' => array (
        'tab'=>4,
        'title' => EST_GEN_MODELNAME,
        'type' => 'text',
        'data' => 'safestr',
        'width' => 'auto',
        'help' => EST_PROP_MODELNAMEHLP,
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array('size' => 'xxlarge','placeholder'=>EST_PROP_MODELNAMEPLCHLDR),
        'class' => 'left',
        'thclass' => 'left',
        ),
      
      'prop_condit' => array (
        'tab'=>4,
        'title' => EST_GEN_CONDITION,
        'type' => 'text',
        'data' => 'safestr',
        'width' => 'auto',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array('size' => 'large'),
        'class' => 'left',
        'thclass' => 'left',
        ),
      
			'prop_yearbuilt' => array (
        'tab'=>4,
        'title' => EST_PROP_YEARBUILT,
        'type' => 'number',
        'data' => 'int',
        'width' => 'auto',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array('size' => 'small'),
        'class' => 'left',
        'thclass' => 'left',
        ),
        
      'prop_floorct'=> array (
        'tab'=>4,
        'title' => EST_GEN_FLOORCT,
        'type' => 'number',
        'data' => 'int',
        'width' => 'auto',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array('size' => 'small'),
        'class' => 'left',
        'thclass' => 'left',
        ),
      'prop_floorno'=> array (
        'tab'=>4,
        'title' => EST_GEN_COMPLEX,
        'type' => 'method',
        'data' => 'int',
        'width' => 'auto',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array('size' => 'small'),
        'class' => 'left',
        'thclass' => 'left',
        ),
      
      'prop_bldguc'=> array (
        'tab'=>4,
        'title' => EST_GEN_COMPLEX,
        'type' => 'method',
        'data' => 'int',
        'width' => 'auto',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array('size' => 'small'),
        'class' => 'left',
        'thclass' => 'left',
        ),
      'prop_complxuc'=> array (
        'tab'=>4,
        'title' => EST_GEN_COMPLEX,
        'type' => 'method',
        'data' => 'int',
        'width' => 'auto',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array('size' => 'small'),
        'class' => 'left',
        'thclass' => 'left',
        ),
      
			'prop_intsize' => array (
        'tab'=>4,
        'title' => EST_PROP_INTSIZE,
        'type' => 'number',
        'data' => 'int',
        'help' => EST_PROP_INTSIZEHLP,
        'width' => 'auto',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array('size' => 'medium estNoRightBord FL'),
        'class' => 'left',
        'thclass' => 'left',
        ),
			'prop_roofsize' => array (
        'tab'=>4,
        'title' => EST_PROP_ROOFSIZE,
        'type' => 'number',
        'data' => 'int',
        'help' => EST_PROP_ROOFSIZEHLP,
        'width' => 'auto',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array('size' => 'medium estNoRightBord FL'),
        'class' => 'left',
        'thclass' => 'left',
        ),
        
			'prop_landsize' => array (
        'tab'=>4,
        'title' => EST_PROP_LANDSIZE,
        'type' => 'text',
        'data' => 'safestr',
        'help' => EST_PROP_LANDSIZEHLP,
        'width' => 'auto',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array('size' => 'medium estNoRightBord FL'),
        'class' => 'left',
        'thclass' => 'left',
        ),
      
			'prop_gallery' => array (
        'tab'=>5,
        'type' => 'method',
        'data' => false,
        'width' => 'auto',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array('nolabel'=>1),
        'class' => 'left',
        'thclass' => 'left',
        ),
			
			'prop_dateupdated' => array (
        'tab'=>6,
        'title' => EST_PROP_DATEUPDATE,
        'type' => 'datestamp',
        'data' => 'str',
        'width' => 'auto',
        'help' => '',
        'readParms' =>  array (),
        'writeParms' =>  array ('type' => 'datetime','auto' => 1,'readonly' => 1),
        'class' => 'left',
        'thclass' => 'left',
        'filter' => true,
        'batch' => false,
        ),
      'prop_datecreated' => array (
        'tab'=>6,
        'title' => EST_PROP_DATECREATED,
        'type' => 'datestamp',
        'data' => 'int',
        'width' => 'auto',
        'help' => '',
        'readParms' => array(),
        'writeParms' => array('type'=>'date'),
        'class' => 'left',
        'thclass' => 'left',
        'filter' => true,
        ),
      'prop_timezone' => array (
        'tab'=>6,
        'title' => EST_GEN_TIMEZONE,
        'type' => 'method',
        'data' => 'str',
        'width' => 'auto',
        'help'=>EST_PROP_TIMEZONEHLP,
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array(),
        'class' => 'left',
        'thclass' => 'left',
        'filter' => false,
        'batch' => false,
        ),
			'prop_datetease' => array (
        'tab'=>6,
        'title' => EST_PROP_DATETEASE,
        'type' => 'datestamp',
        'data' => 'int',
        'width' => 'auto',
        'help' => EST_PROP_DATETEASEHLP,
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array('type'=>'datetime','size'=>'xlarge estDateSched'),
        'class' => 'left',
        'thclass' => 'left',
        'filter' => false,
        'batch' => false,
        ),
			'prop_dateprevw' => array (
        'tab'=>6,
        'title' => EST_PROP_DATEPREVW,
        'type' => 'datestamp',
        'data' => 'int',
        'width' => 'auto',
        'help' => EST_PROP_DATEPREVWHLP,
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array('type'=>'datetime','size'=>'xlarge estDateSched'),
        'class' => 'left',
        'thclass' => 'left',
        'filter' => false,
        'batch' => false,
        ),
			'prop_datelive' => array (
        'tab'=>6,
        'title' => EST_PROP_DATELIVE,
        'type' => 'datestamp',
        'data' => 'int',
        'width' => 'auto',
        'help' => EST_PROP_DATELIVEHLP,
        'readParms' => array(),
        'writeParms' => array('type'=>'datetime','size'=>'xlarge estDateSched'),
        'class' => 'left',
        'thclass' => 'left',
        'filter' => true,
        ),
			'prop_datepull' => array (
        'tab'=>6,
        'title' => EST_PROP_DATEPULL,
        'type' => 'datestamp',
        'data' => 'int',
        'width' => 'auto',
        'help' => EST_PROP_DATEPULLHLP,
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array('type'=>'datetime','size'=>'xlarge estDateSched'),
        'class' => 'left',
        'thclass' => 'left',
        'filter' => false,
        'batch' => false,
        ),
      
      'prop_hours'=>array(
        'tab'=>6,
        'title'=>EST_PROP_HRS,
        'type'=>'method',
        'data'=>'array',
        'width' => 'auto',
        'help'=>EST_PROP_HRSHLP,
        'nolist'=>true,
        'class' => 'left',
        'thclass' => 'left',
        'filter' => false,
        'batch' => false,
        ),
      
      'prop_events'=>array(
        'tab'=>6,
        'type' => 'method',
        'data' => false,
        'width' => 'auto',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array('nolabel'=>1),
        'class' => 'left',
        'thclass' => 'left',
        ),
      
      
      'prop_template_view'=>array(
        'tab'=>7,
        'title'=>EST_PREF_TEMPLATE_VIEW,
        'type'=>'dropdown',
        'data'=>'safestr',
        'width' => 'auto',
        'writeParms' => array('size'=>'xlarge'),
        'help'=>EST_PREF_TEMPLATE_VIEWHLP,
        'nolist'=>true,
        'class' => 'left',
        'thclass' => 'left',
        'filter' => false,
        'batch' => false,
        ),
      'prop_template_view_ord'=>array(
        'tab'=>7,
        'title'=>EST_PREF_TEMPLATE_VIEWORD,
        'type'=>'method',
        'data'=>'array',
        'width' => 'auto',
        'help'=>EST_PREF_TEMPLATE_VIEWORDHLP,
        'nolist'=>true,
        'class' => 'left',
        'thclass' => 'left',
        'filter' => false,
        'batch' => false,
        ),
      
      'prop_template_menu'=>array(
        'tab'=>7,
        'title'=>EST_PREF_TEMPLATE_MENU,
        'type'=>'dropdown',
        'data'=>'safestr',
        'width' => 'auto',
        'writeParms' => array('size'=>'xlarge'),
        'help'=>EST_PREF_TEMPLATE_MENUHLP,
        'nolist'=>true,
        'class' => 'left',
        'thclass' => 'left',
        'filter' => false,
        'batch' => false,
        ),
      'prop_template_menu_ord'=>array(
        'tab'=>7,
        'title'=>EST_PREF_TEMPLATE_MENUORD,
        'type'=>'method',
        'data'=>'array',
        'width' => 'auto',
        'help'=>EST_PREF_TEMPLATE_MENUORDHLP,
        'nolist'=>true,
        'class' => 'left',
        'thclass' => 'left',
        'filter' => false,
        'batch' => false,
        ),
			'options' => array (
        'title' => LAN_OPTIONS,
        'type' => null,
        'data' => null,
        'width' => '10%',
        'thclass' => 'center last',
        'class' => 'center last',
        'forced' => 'value',
        'readParms' =>  array (),
        'writeParms' =>  array (),
        ),
		);		
		
    //protected $fieldset_pre = '<p>Hi There</p>';
    //protected $table_pre = '<p>Hi There</p>';
    
		//protected $fieldpref = array('prop_name','forum_parent','Sort','forum_description','forum_class','forum_postclass','forum_threadclass','forum_order');

		protected $preftabs = array(EST_GEN_GENERAL,EST_PREF_CONTACTFORM,EST_PREF_TEMPLATES,EST_PREF_MENU,EST_GEN_SCHEDULING,EST_GEN_MAP,EST_GEN_NONAGENTLISTINGS);
		protected $prefs = array(
			'adminonly'=>array(
        'tab'=>0,
        'title'=>EST_PREF_ADMINONLY,
        'type'=>'boolean',
        'data'=>'int',
        'help'=>EST_PREF_ADMINONLYHLP,
        ),
      
			'helpinfull'=>array(
        'tab'=>0,
        'title'=>EST_PREF_HELPINFULL,
        'type'=>'boolean',
        'data'=>'int',
        'help'=>EST_PREF_HELPINFULLHLP,
        ),
			'country' => array (
        'tab'=>0,
        'title' => LAN_DEFAULT.' '.EST_PROP_COUNTRY,
        'type' => 'dropdown',
        'data' => 'str',
        'width' => 'auto',
        'help' => EST_PREF_DEFCOUNTRYHLP,
        'readParms' => array (), 
        'writeParms' => array('size'=>'large'),
        ),
			'locale' => array (
        'tab'=>0,
        'title' => LAN_DEFAULT.' '.EST_GEN_CURRENCY,
        'type' => 'method',
        'data' => 'array',
        'width' => 'auto',
        'help' => EST_PREF_DEFLOCALEHLP
        ),
        
			'dimu1' => array (
        'tab'=>0,
        'title' => LAN_DEFAULT.' '.EST_PREF_DIMU1,
        'type' => 'dropdown',
        'data' => 'str',
        'width' => 'auto',
        'help' => EST_PREF_DIMU1HLP,
        'readParms' => array (), 
        'writeParms' => array('size'=>'large','optArray'=>array(EST_PREF_DIMU1A,EST_PREF_DIMU1B)),
        ),
			'dimu2' => array (
        'tab'=>0,
        'title' => LAN_DEFAULT.' '.EST_PREF_DIMU2,
        'type' => 'dropdown',
        'data' => 'str',
        'width' => 'auto',
        'help' => EST_PREF_DIMU2HLP,
        'readParms' => array (), 
        'writeParms' => array('size'=>'small','optArray'=>array(EST_GEN_ACRES,EST_GEN_SQRMI,EST_SQFOOTX,EST_GEN_SQRKM,EST_SQMTRX)),
        ),
      
			'addnewuser' => array (
        'tab'=>0,
        'title' => EST_GEN_ADDNEWUSER,
        'type' => 'method',
        'data' => 'int',
        'width' => 'auto',
        'help' => EST_GEN_ADDNEWUSERHLP,
        'readParms' => array (), 
        'writeParms' => array('size'=>'large'),
        ),
        
			'listing_save'=>array(
        'tab'=>0,
        'title'=>EST_PREF_LISTINGSAVE,
        'type'=>'userclass',
        'data'=>'str',
        'help'=>EST_PREF_LISTINGSAVEHLP,
        ),
      
      'listing_disp'=>array(
        'tab'=>0,
        'title'=>EST_PREF_LISTINGDISP1,
        'type'=>'method',
        'data'=>'array',
        'help'=>EST_PREF_LISTINGDISP1HLP,
        'writeParms' => array('size'=>'small','min'=>2,'max'=>10),
        ),
        
			'contact_class' => array (
        'tab'=>1,
        'title' => EST_PREF_CONTACTFORMACC,
        'type'=>'userclass',
        'data' => 'str',
        'width' => 'auto',
        'help' => EST_PREF_CONTACTFORMACCHLP,
        'readParms' => array (), 
        'writeParms' => array('size'=>'large'),
        ),
			
      'contact_notify'=>array(
        'tab'=>1,
        'title'=>EST_PREF_CONTACTNOTIFY,
        'type'=>'boolean',
        'data'=>'int',
        'help'=>EST_PREF_CONTACTNOTIFYHLP,
        ),
      'contact_mode'=>array(
        'tab'=>1,
        'title'=>EST_PREF_CONTACTMODE,
        'type'=>'dropdown',
        'data'=>'str',
        'help'=>EST_PREF_CONTACTMODEHLP,
        'width' => 'auto',
        'writeParms' => array(
          'size'=>'xxlarge',
          'optArray'=>array(0=>EST_PREF_CONTACTMODE0,1=>EST_PREF_CONTACTMODE1,2=>EST_PREF_CONTACTMODE2,3=>EST_PREF_CONTACTMODE3)
          ),
        ),
      'contact_phone'=>array(
        'tab'=>1,
        'title'=>EST_PREF_CONTACTPHONEREQ,
        'type'=>'boolean',
        'data'=>'int',
        'help'=>EST_PREF_CONTACTPHONEREQHLP,
        ),
      
			'contact_cc' => array (
        'tab'=>1,
        'title' => EST_PREF_CONTACTCC,
        'type'=>'userclass',
        'data' => 'str',
        'width' => 'auto',
        'help' => EST_PREF_CONTACTCCHLP,
        'readParms' => array (), 
        'writeParms' => array('size'=>'large'),
        ),
      
      'contact_max'=>array(
        'tab'=>1,
        'title'=>EST_PREF_CONTACTMAX,
        'type'=>'dropdown',
        'data'=>'str',
        'help'=>EST_PREF_CONTACTMAXHLP,
        'width' => 'auto',
        'writeParms' => array(
          'size'=>'xlarge',
          'optArray'=>array(10=>'10 '.EST_GEN_MESSAGES,20=>'20 '.EST_GEN_MESSAGES,30=>'30 '.EST_GEN_MESSAGES,40=>'40 '.EST_GEN_MESSAGES,50=>'50 '.EST_GEN_MESSAGES)
          ),
        ),
      'contact_maxto'=>array(
        'tab'=>1,
        'title'=>EST_PREF_CONTACTMAXTO,
        'type'=>'dropdown',
        'data'=>'str',
        'help'=>EST_PREF_CONTACTMAXTOHLP,
        'width' => 'auto',
        'writeParms' => array(
          'size'=>'xlarge',
          'optArray'=>array(0=>EST_PREF_CONTACTMAXTO0,1=>EST_PREF_CONTACTMAXTO1,2=>EST_PREF_CONTACTMAXTO2,3=>EST_PREF_CONTACTMAXTO3)
          ),
        ),
      'contact_life'=>array(
        'tab'=>1,
        'title'=>EST_PREF_CONTACTLIFE,
        'type'=>'dropdown',
        'data'=>'str',
        'help'=>EST_PREF_CONTACTLIFEHLP,
        'width' => 'auto',
        'writeParms' => array(
          'size'=>'xlarge',
          'optArray'=>array(30=>'30 '.EST_GEN_DAYS,60=>'60 '.EST_GEN_DAYS,90=>'90 '.EST_GEN_DAYS,180=>'180 '.EST_GEN_DAYS)
          ),
        ),
      'contact_terms'=>array(
        'tab'=>1,
        'title'=>EST_PREF_CONTACTTERMS.'<div id="prefSetDefTermsTarg"></div>',
        'type'=>'method',
        'data'=>'safestr',
        'help'=>EST_PREF_CONTACTTERMSHLP,
        ),
      'template_list'=>array(
        'tab'=>2,
        'title'=>EST_PREF_TEMPLATE_LIST,
        'type'=>'dropdown',
        'data'=>'safestr',
        'help'=>EST_PREF_TEMPLATE_LISTHLP,
        'inline'=>false,
        'nolist'=>true,
        'class' => 'left',
        'thclass' => 'left',
        'writeParms' => array(),
        ),
      
      'template_view'=>array(
        'tab'=>2,
        'title'=>EST_PREF_TEMPLATE_VIEW,
        'type'=>'dropdown',
        'data'=>'safestr',
        'help'=>EST_PREF_TEMPLATE_VIEWHLP,
        'inline'=>false,
        'nolist'=>true,
        'class' => 'left',
        'thclass' => 'left',
        'writeParms' => array(),
        ),
      'template_view_ord'=>array(
        'tab'=>2,
        'title'=>EST_PREF_TEMPLATE_VIEWORD,
        'type'=>'method',
        'data'=>'safestr',
        'help'=>EST_PREF_TEMPLATE_VIEWORDHLP,
        'inline'=>false,
        'nolist'=>true,
        'class' => 'left',
        'thclass' => 'left',
        'writeParms' => array(),
        ),
      
      
      'slideshow_act'=>array(
        'tab'=>2,
        'title'=>EST_PREF_SLIDESHOWACT,
        'type'=>'boolean',
        'data'=>'int',
        'help'=>EST_PREF_SLIDESHOWACTHLP,
        ),
      
      'slideshow_time'=>array(
        'tab'=>2,
        'title'=>EST_PREF_SLIDESHOWTIMING,
        'type'=>'number',
        'data'=>'int',
        'help'=>EST_PREF_SLIDESHOWTIMINGHLP,
        'writeParms' => array('size'=>'small','min'=>2,'max'=>15),
        ),
      
      'slideshow_delay'=>array(
        'tab'=>2,
        'title'=>EST_PREF_SLIDESHOWDELAY,
        'type'=>'number',
        'data'=>'int',
        'help'=>EST_PREF_SLIDESHOWDELAYHLP,
        'writeParms' => array('size'=>'small','min'=>2,'max'=>10),
        ),
      
      'template_menu'=>array(
        'tab'=>3,
        'title'=>EST_PREF_TEMPLATE_MENU,
        'type'=>'dropdown',
        'data'=>'safestr',
        'help'=>EST_PREF_TEMPLATE_MENUHLP,
        'inline'=>false,
        'nolist'=>true,
        'class' => 'left',
        'thclass' => 'left',
        'writeParms' => array(),
        ),
      'template_menu_ord'=>array(
        'tab'=>3,
        'title'=>EST_PREF_TEMPLATE_MENUORD,
        'type'=>'method',
        'data'=>'safestr',
        'help'=>EST_PREF_TEMPLATE_MENUORDHLP,
        'inline'=>false,
        'nolist'=>true,
        'class' => 'left',
        'thclass' => 'left',
        'writeParms' => array(),
        ),
      
      
      'sched_agt_times'=>array(
        'tab'=>4,
        'title'=>EST_PREF_DEFAGTHRS,
        'type'=>'method',
        'data'=>'array',
        'help'=>EST_PREF_DEFAGTHRSHLP,
        ),
      'sched_pub_times'=>array(
        'tab'=>4,
        'title'=>EST_PREF_DEFPUBHRS,
        'type'=>'method',
        'data'=>'array',
        'help'=>EST_PREF_DEFPUBHRSHLP,
        ),
      
      'eventkeys'=>array(
        'tab'=>4,
        'title'=>EST_PREF_DEFEVTLEN,
        'type'=>'method',
        'data'=>'array',
        'help'=>EST_PREF_DEFEVTLENHLP,
        ),
      
      
      'map_include_agency'=>array(
        'tab'=>5,
        'title'=>EST_PREF_MAPAGENCY,
        'help'=>EST_PREF_MAPAGENCYHLP,
        'type'=>'dropdown',
        'data'=>'str',
        'width' => 'auto',
        'writeParms' => array('size'=>'xlarge','optArray'=>array(0=>EST_PREF_MAPAGENCY_NO,1=>EST_PREF_MAPAGENCY_YES)),
        ),
      'map_include_sold'=>array(
        'tab'=>5,
        'title'=>EST_PREF_MAPINCLPROP,
        'help'=>EST_PREF_MAPINCLPROPHLP,
        'type'=>'dropdown',
        'data'=>'str',
        'width' => 'auto',
        'writeParms' => array('size'=>'xlarge','optArray'=>array(0=>EST_PREF_MAPINCLPROPOPT0,1=>EST_PREF_MAPINCLPROPOPT1,2=>EST_PREF_MAPINCLPROPOPT2)),
        ),
      
      'map_jssrc'=>array( 
        'tab'=>5,
        'title'=>EST_PREF_MAPMAP_JSSRC,
        'type'=>'dropdown',
        'data'=>'str',
        'width' => 'auto',
        'help'=>EST_PREF_MAPMAP_JSSRCHLP,
        'writeParms' => array('size'=>'xlarge','optArray'=>array(0=>EST_PREF_MAPMAP_JSSRCOPT0,1=>EST_PREF_MAPMAP_JSSRCOPT1)),
        ),
      'map_url'=>array(
        'tab'=>5,
        'title'=>EST_PREF_MAPURL,
        'type'=>'method',
        'data'=>'safestr',
        'help'=>EST_PREF_MAPURLHLP,
        'inline'=>false,
        'nolist'=>true,
        'class' => 'left',
        'thclass' => 'left',
        ),
      
      'map_key'=>array(
        'tab'=>5,
        'title'=>EST_PREF_MAPKEY,
        'type'=>'method',
        'data'=>'safestr',
        'help'=>EST_PREF_MAPKEYHLP,
        'inline'=>false,
        'nolist'=>true,
        'class' => 'left',
        'thclass' => 'left',
        ),
      
      
      'pref_addr_lookup' => array (
        'tab'=>5,
        'type' => 'method',
        'data' => 'safestr',
        'width' => 'auto',
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array('size'=>'xlarge'),
        'class' => 'left',
        'thclass' => 'left',
        ),
      
      'pref_map'=>array(
        'tab'=>5,
        'type'=>'method',
        'data'=>false,
        'inline'=>false,
        'nolist'=>true,
        'title'=>'<div id="est_pref_SrchRes" class="estMapBtnCont"></div>',
        'writeParms' => array(),
        'class' => 'center',
        ),
			'pref_lat'=>array(
        'tab'=>5,
        'type'=>'hidden',
        'data'=>'safestr',
        'inline'=>false,
        'nolist'=>true,
        'writeParms' => array('size'=>'xlarge estMapReset'),
        'class' => 'left',
        'thclass' => 'left',
        ),
      'pref_lon'=>array(
        'tab'=>5,
        'type'=>'hidden',
        'data'=>'safestr',
        'inline'=>false,
        'nolist'=>true,
        'writeParms' => array('size'=>'xlarge estMapReset'),
        'class' => 'left',
        'thclass' => 'left',
        ),
			'pref_zoom'=>array(
        'tab'=>5,
        'type'=>'hidden',
        'data'=>'int',
        'inline'=>false,
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array(),
        'class' => 'left',
        'thclass' => 'left',
        ),
			'map_width'=>array(
        'tab'=>5,
        'type'=>'hidden',
        'data'=>'int',
        'inline'=>false,
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array(),
        'class' => 'left',
        'thclass' => 'left',
        ),
			'map_height'=>array(
        'tab'=>5,
        'type'=>'hidden',
        'data'=>'int',
        'inline'=>false,
        'nolist'=>true,
        'readParms' => array(),
        'writeParms' => array(),
        'class' => 'left',
        'thclass' => 'left',
        ),
      
			'public_act'=>array(
        'tab'=>6,
        'title'=>EST_GEN_NONAGENTLISTINGS,
        'type'=>'userclass',
        'data'=>'int',
        'help'=>EST_HINT_NONAGENTLISTINGS,
        'readParms' =>  array (),
        'writeParms' =>  array ('classlist'=>'member,nobody,no-excludes'),
        ),
      'public_exp'=>array( 
        'tab'=>6,
        'title'=>EST_GEN_NONAGENTEXP,
        'type'=>'dropdown',
        'data'=>'str',
        'width' => 'auto',
        'help'=>EST_GEN_NONAGENTEXPHLP,
        'writeParms' => array('size'=>'xlarge','optArray'=>array(0=>EST_GEN_NONE,14=>'2 '.EST_GEN_WEEKS,30=>'1 '.EST_GEN_MONTH,61=>'2 '.EST_GEN_MONTHS,92=>'3 '.EST_GEN_MONTHS,183=>'6 '.EST_GEN_MONTHS)),
        ),
      'public_imgct'=>array(
        'tab'=>6,
        'title'=>EST_GEN_NONAGENTIMGCT,
        'type'=>'number',
        'data'=>'int',
        'help'=>EST_GEN_NONAGENTIMGCTHLP,
        'writeParms' => array('size'=>'small','min'=>3,'max'=>18),
        ),
			'public_apr'=>array(
        'tab'=>6,
        'title'=>EST_GEN_NONAGENTAPPROVED,
        'type'=>'userclass',
        'data'=>'int',
        'help'=>EST_GEN_NONAGENTAPPROVEDHLP,
        'readParms' =>  array (),
        'writeParms' =>  array ('classlist'=>'member,nobody,no-excludes'),
        ),
      'public_mod'=>array( 
        'tab'=>6,
        'title'=>EST_GEN_NONAGENTMOD,
        'type'=>'method',
        'data'=>'str',
        'width' => 'auto',
        'help'=>EST_GEN_NONAGENTMODHLP,
        'writeParms' => array('size'=>'xlarge'),
        ),
			'public_notify'=>array(
        'tab'=>6,
        'title'=>EST_GEN_NONAGENTNOTIFY1,
        'type'=>'method',
        'data'=>'safestr',
        'help'=>EST_GEN_NONAGENTNOTIFY1HLP,
        ),
      );
    
    
    
    
    
    public function newPage(){
      e107::redirect(SITEURLBASE.e_PLUGIN_ABS."estate/admin_config.php?mode=estate_properties&action=list&tab=1");
      }
    
    //public function createPage(){
      //e107::redirect(SITEURLBASE.e_PLUGIN_ABS."estate/admin_config.php?mode=estate_properties&action=list&tab=1");
      //}
    
    public function listPage(){
      $ESTPREF = e107::pref('estate');
      $estateCore = new estateCore;
      return $estateCore->estPropertyListTable();
      }
    
    
    
    
    
		public function init() {
      $tp = e107::getParser();
      $mes = e107::getMessage();
      $frm = e107::getForm(false, true);
      
			$this->fields['prop_country']['writeParms']['default'] = 'blank';
			$this->fields['prop_country']['writeParms']['optArray'] = $frm->getCountry();
      
      
			$this->prefs['country']['writeParms']['optArray'] = $frm->getCountry();
      
      
      $data2 = array();
      if(isset($GLOBALS['EST_PROPSTATUS']) && count($GLOBALS['EST_PROPSTATUS'])){foreach($GLOBALS['EST_PROPSTATUS'] as $k=>$v){$data2[$k] = $v['opt'];}}
      $this->fields['prop_status']['writeParms']['optArray'] = $data2;
      
      //$this->fields['prop_datecreated']['writeParms']['value'] = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
      
      $data2 = array();
      $dbRow = e107::getDb()->retrieve('estate_listypes', '*', '',true);
      if(count($dbRow)){foreach($dbRow as $k=>$v){$data2[$v['listype_idx']] = $v['listype_name'];}}
      $this->fields['prop_type']['writeParms']['optArray'] = $data2;
      
      $data2 = array();
      if(EST_USERPERM == 4){$dbRow = e107::getDb()->retrieve('estate_zoning', '*', '',true);}
      else{$dbRow = e107::getDb()->retrieve('estate_zoning', '*', '',true);}
      if(count($dbRow)){foreach($dbRow as $k=>$v){$data2[$v['zoning_idx']] = $v['zoning_name'];}}
      $this->fields['prop_zoning']['writeParms']['optArray'] = $data2;
      
      $data2 = array();
      $dbRow = e107::getDb()->retrieve('estate_states', '*', '',true);
      if(count($dbRow)){foreach($dbRow as $k=>$v){$data2[$v['state_idx']] = $v['state_name'];}}
      $this->fields['prop_state']['writeParms']['optArray'] = $data2;
      
      $data2 = array();
      $dbRow = e107::getDb()->retrieve('estate_county', '*', '',true);
      if(count($dbRow)){foreach($dbRow as $k=>$v){$data2[$v['cnty_idx']] = $v['cnty_name'];}}
      $this->fields['prop_county']['writeParms']['optArray'] = $data2;
      
      $data2 = array();
      $dbRow = e107::getDb()->retrieve('estate_city', '*', '',true);
      if(count($dbRow)){foreach($dbRow as $k=>$v){$data2[$v['city_idx']] = $v['city_name'];}}
      $this->fields['prop_city']['writeParms']['optArray'] = $data2;
      
      $data2 = array();
      $dbRow = e107::getDb()->retrieve('estate_subdiv', '*', '',true);
      if(count($dbRow)){foreach($dbRow as $k=>$v){$data2[$v['subd_idx']] = $v['subd_name'];}}
      $this->fields['prop_subdiv']['writeParms']['optArray'] = $data2;
			$this->fields['prop_subdiv']['writeParms']['default'] = 'blank';
      
      $this->fields['prop_listype']['writeParms']['optArray'] = $GLOBALS['EST_LISTTYPE1'];
      $this->fields['prop_hoafrq']['writeParms']['optArray'] = $GLOBALS['EST_HOAFREQ'];
      
      $this->fields['prop_leasedur']['writeParms']['optArray'] = $GLOBALS['EST_LEASEDUR'];
      
      $this->fields['prop_events'][1] = 2;
		}

		
		// ------- Customize Create --------
		
		public function beforeCreate($new_data,$old_data){
      $TNOW = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
      $new_data['prop_datecreated'] = $TNOW;
      $new_data['prop_dateupdated'] = $TNOW;
      $new_data['prop_uidcreate'] = USERID;
      $new_data['prop_uidupdate'] = USERID;
      
      $new_data['prop_locale'] = implode(",",e107::pref('estate','locale'));
      
      if(empty($new_data['prop_name']) || $new_data['prop_name'] = EST_PROP_UNNAMEDPROP){
        if(!empty($new_data['prop_addr1'])){$new_data['prop_name'] = $new_data['prop_addr1'];}
        else{$new_data['prop_name'] = EST_PROP_UNNAMEDPROP;}
        }
      $new_data['prop_name'] = trim(e107::getParser()->toText($new_data['prop_name']));
			
      if(isset($new_data['prop_sef']) && empty($new_data['prop_sef']) && !empty($new_data['prop_name'])){
				$new_data['prop_sef'] = eHelper::title2sef($new_data['prop_name']);
		    }
			elseif(!empty($new_data['prop_sef'])){$new_data['prop_sef'] = eHelper::title2sef($new_data['prop_sef']);}
      return $new_data;
		  }
	
		public function afterCreate($new_data, $old_data, $id){
      //$mes = e107::getMessage();
		  }

		public function onCreateError($new_data, $old_data){
			// do something		
		  }
		
    
    
    
    
    
		// ------- Customize Delete --------
    /*
    public function beforeDelete($data, $id){
      $mes = e107::getMessage();
			$text = $data."<br />[".$id."]".$PREDEL;
      e107::getRender()->tablerender("BEFORE DELETE", $text);
      }
    */
  	public function afterDelete($deleted_data, $id, $deleted_check){
			$ns = e107::getRender();
      $mes = e107::getMessage();
      if($deleted_check == 1){
        $PREDEL = estPropDelete($id);
  			$text = EST_GEN_DELETEDPROPERTY.' #'.$id.' '.$PREDEL;
  			e107::getMessage()->addSuccess($text);
        }
      }
    
		
		// ------- Customize Update --------
		
		public function beforeUpdate($new_data, $old_data, $id){
      $tp = e107::getParser();
      $msg = e107::getMessage();
      $new_data['prop_dateupdated'] = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
      $new_data['prop_uidupdate'] = USERID;
      
      $ndate = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
      $msg->addInfo('['.$old_data['prop_idx'].'] '.$tp->toDate($ndate,'short').' '.$new_data['prop_listprice'].' ('.$new_data['prop_status'].')');
      
      $chkHist = estChkPriceHist($old_data['prop_idx'],$ndate,$new_data['prop_listprice'],$new_data['prop_status']);
      if(count($chkHist) > 0){
        if($chkHist[0] !== 'noc'){
          switch($chkHist[0]){
            case 'err' : $mtx1 = EST_GEN_ERROR; break;
            case 'upd' : $mtx1 = EST_GEN_UPDATED; break;
            case 'add' : $mtx1 = EST_GEN_ADDED; break;
            }
          $msg->addInfo($mtx1.' '.EST_PROP_UPHIST1.' '.$chkHist[1].' '.EST_GEN_AMOUNT.': '.$chkHist[2].' ');
          }
        }
      
      if(empty($old_data['prop_name']) || $old_data['prop_name'] == EST_PROP_UNNAMEDPROP){
        if(!empty($new_data['prop_addr1'])){$new_data['prop_name'] = trim($tp->toText($new_data['prop_addr1']));}
        elseif(!empty($old_data['prop_addr1'])){$new_data['prop_name'] = trim($tp->toText($old_data['prop_addr1']));}
        //else{$new_data['prop_name'] = trim($tp->toText(EST_PROP_UNNAMEDPROP));}
        }
			if(empty($old_data['prop_sef']) && empty($new_data['prop_sef'])){
        $new_data['prop_sef'] = eHelper::title2sef($new_data['prop_name']);
        }
			elseif(!empty($new_data['prop_sef'])){$new_data['prop_sef'] = eHelper::title2sef($new_data['prop_sef']);}
			return $new_data;
		  }

		public function afterUpdate($new_data, $old_data, $id){
      //$mes = e107::getMessage();
			return $new_data;
		  }
		
		public function onUpdateError($new_data, $old_data, $id){
      $mes = e107::getMessage();
      $mes->addSuccess($old_data);
      $mes->addWarning($new_data);
			// do something		
		  }
		
    
		public function renderHelp(){
			$tp = e107::getParser();
      $hlpmode = $this->getMode();
      $hlpactn = $this->getAction();
      
			$caption = '<span id="estHelpSpan" class="'.($GLOBALS['EST_PREF']['helpinfull'] == 1 ? 'estHlpFull' : 'estHelpSAuto').'">'.LAN_HELP.'</span>';
			$text = '<div id="estHelpBlock" class="'.($GLOBALS['EST_PREF']['helpinfull'] == 1 ? '' : 'estHelpBAuto').'">';
      if($hlpmode == 'estate_properties'){
        if($hlpactn == 'edit' || $hlpactn == 'create'){
          $text .= '
          <div id="estEditHelp-0" class="estEditHelpSect">
            <b id="estHlp-list0">'.EST_GEN_LISTING.'</b>
            <p id="estHlp-list1">'.EST_HLPMNU_LISTING1.'</p>
            <p>'.EST_HLPMNU_SEO1.'</p>
            <p id="estHlp-list2">'.EST_HLPMNU_LISTING2.'</p>
            <p id="estHlp-list3">'.EST_HLPMNU_LISTING3.'</p>
            <p id="estHlp-list4">'.EST_HLPMNU_LISTING4.'</p>
            <p id="estHlp-list5">'.EST_HLPMNU_LISTING5.'</p>
          </div>
          <div id="estEditHelp-1" class="estEditHelpSect">
            <b id="estHlp-addr0">'.EST_GEN_ADDRESS.'</b>
            <p id="estHlp-addr1">'.EST_HLPMNU_ADDRESS1.'</p>
            <p id="estHlp-addr2">'.EST_HLPMNU_LISTING2.'</p>
            <p id="estHlp-addr3">'.EST_HLPMNU_LISTING3.'</p>
            <b id="estHlp-map0">'.EST_GEN_MAP.'</b>
            <p>'.EST_PROP_MAPMAINHLP1.'</p>
            <p>'.EST_PROP_MAPMAINHLP2.'</p>
            <p>'.EST_PROP_MAPMAINHLP3.'</p>
            <p>'.EST_PROP_MAPMAINHLP4.'</p>
          </div>
          <div id="estEditHelp-2" class="estEditHelpSect">
            <b id="estHlp-comm0">'.EST_GEN_COMMUNITY.'</b>
            <p id="estHlp-comm1">'.EST_HLPMNU_COMMUNITY1.'</p>
            <p id="estHlp-comm2">'.EST_HLPMNU_COMMUNITY2.'</p>
            <p>'.EST_HLPMNU_COMMUNITY3.'</p>
            <p>'.EST_HLPMNU_COMMUNITY4.'</p>
          </div>
          
          <div id="estEditHelp-3" class="estEditHelpSect">
            <b>'.EST_GEN_SPACES.'</b>
            <p>'.EST_HLPMNU_SPACES1.'</p>
            <p>'.EST_HLPMNU_SPACES2.'</p>
            <p>'.EST_HLPMNU_SPACES3.'</p>
            <p>'.EST_HLPMNU_SPACES4.'</p>
            <p>'.EST_HLPMNU_SPACES5.'</p>
            <p>'.EST_HLPMNU_SPACES6.'</p>
            <p>'.EST_HLPMNU_SPACES7.'</p>
            <p>'.EST_HLPMNU_SPACES8.'</p>
          </div>
          <div id="estEditHelp-4" class="estEditHelpSect">
            <b id="estHlp-detail0">'.EST_GEN_DETAILS.'</b>
            <p id="estHlp-detail1">'.EST_HLPMNU_DETAILS1.'</p>
            <p id="estHlp-detail2">'.EST_HLPMNU_DETAILS2.'</p>
            <p id="estHlp-detail3">'.EST_HLPMNU_DETAILS3.'</p>
            <p id="estHlp-detail4">'.EST_HLPMNU_DETAILS4.'</p>
            <p id="estHlp-detail5">'.EST_HLPMNU_DETAILS5.'</p>
            <p id="estHlp-detail6">'.EST_HLPMNU_DETAILS6.'</p>
            <p id="estHlp-detail7">'.EST_HLPMNU_DETAILS7.'</p>
            <p id="estHlp-detail8">'.EST_HLPMNU_DETAILS8.'[code][b]'.EST_HLPMNU_DETAILS9a.'[/b]
[i]'.EST_HLPMNU_DETAILS9b.'[/i]
[u]'.EST_HLPMNU_DETAILS9c.'[/u]
'.EST_HLPMNU_DETAILS9d.'[/code]</p>
          </div>
          <div id="estEditHelp-5" class="estEditHelpSect">
            <b id="estHlp-gal0">'.EST_GEN_GALLERY.'</b>
            <p id="estHlp-gal1">'.EST_HLPMNU_GALLERY1.'</p>
            <p id="estHlp-gal2">'.EST_HLPMNU_GALLERY2.'</p>
            <p id="estHlp-gal3">'.EST_HLPMNU_GALLERY3.'</p>
            <p id="estHlp-gal4">'.EST_HLPMNU_GALLERY4.'</p>
            <p id="estHlp-gal5">'.EST_HLPMNU_GALLERY5.'</p>
            <p id="estHlp-gal6">'.EST_HLPMNU_GALLERY6.'</p>
          </div>
          <div id="estEditHelp-6" class="estEditHelpSect">
            <b id="estHlp-sched0">'.EST_GEN_SCHEDULING.'</b>
            <p id="estHlp-sched1">'.EST_HLPMNU_SCHED0.'</p>
            <b id="estHlp-sched5">'.EST_GEN_EVENTS.'</b>
            <p id="estHlp-sched6">'.EST_HLPMNU_SCHED5.'</p>
            <p id="estHlp-sched7">'.EST_HLPMNU_SCHED6.'</p>
          </div>
          <div id="estEditHelp-7" class="estEditHelpSect">
            <b>'.EST_PREF_TEMPLATES.'</b>
            <p>'.EST_HLPMNU_PREF_TEMPLATES02.'</p>
            <p>'.EST_HLPMNU_PREF_TEMPLATES03.'</p>
          </div>
          <div id="estEditHelp-8" class="estEditHelpSect">
          </div>';
          }
        else if($hlpactn == 'prefs'){
          $text .= '
          <div id="estEditHelp-0" class="estEditHelpSect">
            <b id="estHlp-prefGen1">'.EST_GEN_GENERALOPTS.'</b>
            <p>'.EST_GEN_GENERALOPTSHLP1.'</p>
          </div>
          <div id="estEditHelp-1" class="estEditHelpSect">
            <b>'.EST_PREF_CONTACTFORM.'</b>
            <p>'.EST_HLPMNU_PREF_CONTACTFORM01.'</p>
          </div>
          <div id="estEditHelp-2" class="estEditHelpSect">
            <b>'.EST_PREF_TEMPLATES.'</b>
            <p>'.EST_HLPMNU_PREF_TEMPLATES01.'</p>
          </div>
          <div id="estEditHelp-3" class="estEditHelpSect">
            <b>'.EST_PREF_MENU.'</b>
            <p>'.EST_HLPMNU_PREF_MENU01.'</p>
          </div>
          <div id="estEditHelp-4" class="estEditHelpSect">
            <b>'.EST_GEN_SCHEDULEOPTS.'</b>
            <p>'.EST_GEN_SCHEDULEOPTSHLP1.'</p>
          </div>
          <div id="estEditHelp-5" class="estEditHelpSect">
            <b>'.EST_GEN_MAPOPTS.'</b>
            <p>'.EST_GEN_MAPOPTSHLP1.'</p>
          </div>
          <div id="estEditHelp-6" class="estEditHelpSect">
            <b>'.EST_GEN_NONAGENTLISTINGS.'</b>
            <p>'.EST_GEN_NONAGENTLISTINGHLP1.'</p>
          </div>';
          }
        else{
          $text .= '
          <div id="estEditHelp-0" class="estEditHelpSect">
            <b>'.EST_GEN_AGENT.' '.EST_GEN_LISTINGS.'</b>
            <p id="estHlp-proplist1">'.EST_HLPMNU_PROPLIST1.'</p>
            <p id="estHlp-proplist2">'.EST_HLPMNU_PROPLIST2.'</p>
            <b id="estHlp-proplist3">'.EST_HLPMNU_PROPLIST3.'</b>
            <p id="estHlp-proplist4">'.EST_HLPMNU_PROPLIST4.'</p>
            <p id="estHlp-proplist5">'.EST_HLPMNU_PROPLIST5.'</p>
            <b id="estHlp-proplist6">'.EST_HLPMNU_PROPLIST6.'</b>
            <p id="estHlp-proplist7">'.EST_HLPMNU_PROPLIST7.'</p>
            <p id="estHlp-proplist8">'.EST_HLPMNU_PROPLIST8.'</p>
          </div>
          <div id="estEditHelp-1" class="estEditHelpSect">
            <b>'.EST_GEN_NONAGENTLISTINGS.'</b>
            <p id="estHlp-proplist1">'.EST_HLPMNU_PROPLIST1.'</p>
            <p id="estHlp-proplist2">'.EST_HLPMNU_PROPLIST2.'</p>
            <b id="estHlp-proplist3">'.EST_HLPMNU_PROPLIST3.'</b>
            <p id="estHlp-proplist4">'.EST_HLPMNU_PROPLIST4.'</p>
            <p id="estHlp-proplist5">'.EST_HLPMNU_PROPLIST5.'</p>
            <b id="estHlp-proplist6">'.EST_HLPMNU_PROPLIST6.'</b>
            <p id="estHlp-proplist7">'.EST_HLPMNU_PROPLIST7.'</p>
            <p id="estHlp-proplist8">'.EST_HLPMNU_PROPLIST8.'</p>
          </div>
          <div id="estEditHelp-2" class="estEditHelpSect">
            <b>'.EST_GEN_NEW.' '.EST_GEN_LISTING.'</b>
            <p id="estHlp-proplist8">'.EST_HLPMNU_PROPLIST9.'</p>
            <p id="estHlp-proplist8">'.EST_HLPMNU_PROPLIST9a.'</p>
          </div>';
          }
        }
      $text .= '</div>';
      $text = $tp->toHTML($text,true);
      //$text = str_replace("[b]","<b>",str_replace("[/b]","</b>",$text));
			return array('caption'=>$caption,'text'=> $text);
		  }
  }




class estate_listing_form_ui extends e_admin_form_ui{
  
  public function locale($curVal,$mode){
    $estateCore = new estateCore;
    
    if($curVal[0] == ''){$curVal[0] = 3;}
    if($curVal[1] == ''){$curVal[1] = 1;}
    if($curVal[2] == ''){$curVal[2] = 'en_US';}
    if($curVal[3] == ''){$curVal[3] = 'USD';}
    
    return $estateCore->estLocalOptsForm($curVal);
    
    }
  
  //helpinfull
  public function listing_disp($curVal,$mode){
    $tp = e107::getParser();
    
    if(is_array($GLOBALS['EST_PROPSTATUS'])){
      if(count($GLOBALS['EST_PROPSTATUS']) > 0){
        $frm = e107::getForm(false, true);
        $caviats = array(
          0 => array(' ¹ ',EST_PREF_LISTINGCAVI1),
          5 => array(' ² ',EST_PREF_LISTINGCAVI2)
          );
        //³⁴⁵
        $txt = '
        <table class="table estCustomTable1 table-striped">
          <colgroup style="width:35%;"></colgroup>
          <colgroup style="width:65%;"></colgroup>
          <tbody>';
        foreach($GLOBALS['EST_PROPSTATUS'] as $k=>$v){
          $txt .= '
          <tr>
            <td>'.$v['opt'].$caviats[$k][0].'</td><td>';
          $txt .= $frm->flipswitch('listing_disp['.$k.']', intval($curVal[$k]),array('off'=>EST_GEN_HIDDEN,'on'=>EST_GEN_PUBLIC));
          $txt .= '
            </td>
          </tr>';
          unset($k,$v);
          }
        
        $txt .= '
          </tbody>
          <tfoot><tr><td colspan="2">';
        foreach($caviats as $k=>$v){
          $txt .= '<div>'.$v[0].$v[1].'</div>';
          }
        $txt .= '</td></tr></tfoot>';
        $txt .= '
        </table>';
        }
      else{$txt = 'No Prop Status settings found';}
      }
    else{$txt = 'Prop Status settings are not in array format';}
    return $txt;
    }
  
  public function public_mod($curVal,$mode){
    $estateCore = new estateCore;
    return $estateCore->estModList($curVal,'public_mod');
    //template_view_ord
    }
  
  public function public_notify($curVal,$mode){
    $estateCore = new estateCore;
    return $estateCore->estModList($curVal,'public_notify');
    }
  
  
  public function contact_terms($curVal,$mode){
    $tp = e107::getParser();
    return '<button id="prefSetDefTerms" data-t1="'.EST_GEN_USE.' '.EST_GEN_DEFAULT.'" data-t2="'.EST_GEN_USE.' '.EST_GEN_CUSTOM.'" class="btn btn-primary">'.EST_GEN_USE.' '.(trim($curVal) == '' ? EST_GEN_CUSTOM : EST_GEN_DEFAULT).'</button>
    <textarea id="contact-terms" name="contact_terms" cols="40" rows="5" class="tbox form-control input-xxlarge" '.(trim($curVal) == '' ? 'style="display:none;"' : '').'>'.$tp->toForm($curVal).'</textarea>
    <div id="contact-terms-def" class="tbox"'.(trim($curVal) == '' ? '' : 'style="display:none;"').'><h4>'.EST_GEN_DEFAULT.':</h4>'.$tp->toHTML(EST_MSG_CONSTXT1.'<br /><br />'.EST_MSG_CONSTXT2).'</div>';
    }
  
  public function prop_timezone($curVal,$mode){
    $timeZones = systemTimeZones();
    $pref = e107::pref();
    switch($mode){
      case 'write':
        return e107::getForm(false, true)->select('prop_timezone', $timeZones, vartrue($curVal, $pref['timezone']),'size=xlarge');
        break;
			case 'read': 
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  
  
  public function prop_agent($curVal,$mode){
    $tp = e107::getParser();
    $sql = e107::getDB();
    $frm = e107::getForm();
    
    $estateCore = new estateCore;
    //$rows = $estateCore->estGetAgentById($curVal);
    
    switch($mode){
			case 'read': 
        //$dta = $this->getController()->getListModel();
        //$text = '<div class="estPropListAgentCont"><div>'.$tp->toHTML($rows['agency_name']).'</div><div><div style="background-image:url(\''.$rows['agent_profimg'].'\')"></div><a href="'.e_SELF.'?mode=estate_agencies&action=agent&id='.intval($rows['agent_idx']).'">'.$tp->toHTML($rows['agent_name']).'</a></div></div>';
        return '[defunct]';
				break;

			case 'write': 
        $dta = $this->getController()->getModel()->getData();
        $AGTID = intval($dta['prop_agent']);
        
        $text = '';
        
        if($AGTID > 0){
          $sql->gen("SELECT user_id,user_name,user_loginname,user_email,user_admin,user_perms,user_class,user_signature,user_image, #estate_agents.*, #estate_agencies.* FROM #estate_agents LEFT JOIN #estate_agencies ON agent_agcy=agency_idx LEFT JOIN #user ON user_id=agent_uid WHERE agent_idx='".$AGTID."' LIMIT 1");
          $rows = $sql->fetch();
          if(intval($rows['agent_imgsrc']) == 1 && trim($rows['agent_image']) !== ""){$SELLER_IMG = EST_PTHABS_AGENT.$tp->toHTML($rows['agent_image']);}
          else{$SELLER_IMG = $tp->toAvatar($rows,array('type'=>'url'));}
          $SELLER_NAME = $tp->toHTML($rows['agent_name']);
          $AGENCY_NAME = $tp->toHTML($rows['agency_name']);
          $AGENCY_ID = intval($rows['agent_agcy']);
          }
        else{
          $USRID = (intval($dta['prop_idx']) === 0 ? USERID : intval($dta['prop_uidcreate']));
          $sql->gen("SELECT user_id,user_name,user_loginname,user_email,user_admin,user_perms,user_class,user_signature,user_image FROM #user WHERE user_id='".$USRID."' LIMIT 1");
          $rows = $sql->fetch();
          $SELLER_IMG = $tp->toAvatar($rows,array('type'=>'url'));
          $SELLER_NAME = $tp->toHTML(trim($rows['user_name']) !== '' ? $rows['user_name'] : $rows['user_loginname']);
          $AGENCY_NAME = EST_GEN_PRIVATE.' '.EST_GEN_SELLER;
          $AGENCY_ID = 0;
          $DISAB = ' disabled="disabled"';
          }
        
        
        $text .= '
        <input type="hidden" name="prop_agent" value="'.intval($rows['agent_idx']).'">
        <div id="estAgentContDiv" class="estBtnCont">
          <button type="button" id="estAgencySelBtn" class="btn btn-default estNoRightBord" data-agcy="'.$AGENCY_ID.'"'.$DISAB.'>'.$AGENCY_NAME.'</button>
          <button type="button" id="estAgentSelBtn" class="btn btn-primary estNoLRBord"'.$DISAB.'>
            <div id="estAgentMinPic" style="background-image:url(\''.$SELLER_IMG.'\')"></div>
            <span id="estAgentSelBtnTxt">'.$SELLER_NAME.'</span>
          </button>
          <div id="estAgentEditDiv" class="estSonar">
            <div class="estSonarBlip"></div>
          </div>
          <div id="estAgentOptsCont" class="form-control">
            <div id="estAgentOptsL"></div>
            <div id="estAgentOptsR"></div>
          </div>
        </div>';
          
        return $text;
        break;
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  
  
  
  public function prop_listype($curVal,$mode){
    $tp = e107::getParser();
    $sql = e107::getDB();
    $frm = e107::getForm();
    
    switch($mode){
			case 'read': // List Page
        $text = $frm->select_open("prop_listype",array('value'=>$curVal,'size'=>'large'));
        if(isset($GLOBALS['EST_LISTTYPE1']) && count($GLOBALS['EST_LISTTYPE1']) > 0){
          foreach($GLOBALS['EST_LISTTYPE1'] as $K=>$V){
            $text .= '<option value="'.$K.'"'.($K == $curVal ? ' selected="selected"' : '').'>'.$tp->toHTML($V).'</option>';
            }
          }
        
        $text .= '</select>';
        return $text;
				break;

			case 'write': // Edit Page
        $text = $frm->select_open("prop_listype",array('value'=>$curVal,'size'=>'xlarge'));
        if(isset($GLOBALS['EST_LISTTYPE1']) && count($GLOBALS['EST_LISTTYPE1']) > 0){
          foreach($GLOBALS['EST_LISTTYPE1'] as $K=>$V){
            $text .= '<option value="'.$K.'"'.($K == $curVal ? ' selected="selected"' : '').'>'.$tp->toHTML($V).'</option>';
            }
          }
        
        $text .= '</select>';
        return $text;
        break;
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  public function prop_listaddr($curVal,$mode){
    switch($mode){
			case 'read': 
		    $tp = e107::getParser();
        $estateCore = new estateCore;
    		$dta = $this->getController()->getListModel();
        return $estateCore->est_PropNameAddr($dta);
				break;
			case 'write':
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  
  public function prop_appr($curVal,$mode){
		$tp = e107::getParser();
    $EST_PREF = e107::pref('estate');
    switch($mode){
			case 'read': // List Page
        return $curVal;
				break;

			case 'write': // Edit Page
        $dta = $this->getController()->getModel()->getData();
        if(intval($dta['prop_agent']) > 0){return EST_GEN_NOT.' '.EST_GEN_REQUIRED.'<input type="hidden" name="prop_appr" value="1" />';}
        else if(EST_USERPERM >= intval($EST_PREF['public_mod'])){
          return '<select name="prop_appr" value="'.intval($curVal).'">
          <option value=""'.(intval($curVal) == 0 ? ' selected="selected"' : '').'>'.EST_GEN_NOT.' '.EST_GEN_APPROVED.'</option><option value=""'.(intval($curVal) == 1 ? ' selected="selected"' : '').'>'.EST_GEN_APPROVED.'</option></select>
          <input type="hidden" name="estDefCur" value="'.$EST_PREF['locale'][1].'" />
          <input type="hidden" name="estDefDIMU1" value="'.$EST_PREF['dimu1'].'" />
          <input type="hidden" name="estDefDIMU2" value="'.$EST_PREF['dimu2'].'" />';
          }
        else{
          if(EST_USERPERM > 0 || check_class($EST_PREF['public_apr'])){$curVal = 1;}      
          return (intval($curVal) == 1 ? EST_GEN_APPROVED : EST_GEN_NOT.' '.EST_GEN_APPROVED).'<input type="hidden" name="prop_appr" value="'.$curVal.'" />';
          }
				break;
      }
    }
  
  public function prop_zip($curVal,$mode){
		$tp = e107::getParser();
    
    switch($mode){
			case 'read': // List Page
        return $curVal;
				break;

			case 'write': // Edit Page
        $sql = e107::getDB();
        $frm = e107::getForm();
        $text = $frm->select_open("prop_zip",array('value'=>$curVal,'size'=>'large estPropAddr'));
        if(intval($curVal) !== 0){
          if($sql->gen('SELECT city_zip FROM #estate_city WHERE FIND_IN_SET('.$curVal.',city_zip) > 0 LIMIT 1')){
            $ZIPARR = explode(',',$sql->fetch()['city_zip']);
            if(count($ZIPARR) > 0){
              foreach($ZIPARR as $zv){$text .= '<option value="'.$zv.'"'.($zv == $curVal ? ' selected="selected"' : '').'>'.$tp->toHTML($zv).'</option>';}
              }
            }
          }
        $text .= '</select>';
        return $text;
        break;
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  
  
  
  public function prop_thmb($curVal,$mode){
    switch($mode){
			case 'read': return '<div class="estPropThumb" title="'.EST_PROP_RESETHM.'"></div>'; break;
			case 'write':
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  
  
  public function prop_hours($curVal,$mode){
    switch($mode){
			case 'write' :
        //prop_events
        $dta = $this->getController()->getModel()->getData();
        $estateCore = new estateCore;
        return $estateCore->estPropHoursForm($dta);
        break;
			case 'read': 
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  
  public function prop_events($curVal,$mode){
    switch($mode){
      case 'write':
        parse_str(html_entity_decode(e_QUERY), $URLQ);
        
        if($URLQ['action'] == 'create' || $URLQ['action'] == 'new'){
          return '<div id="estNoEvtWarn" class="s-message alert alert-block warning alert-warning" style="display: block;">This Property needs to be saved before scheduling Events</div>';
          }
        
        if(intval($URLQ['id']) == 0){
          return '<div id="estNoEvtWarn" class="s-message alert alert-block warning alert-warning" style="display: block;">No Property Index: '.intval($URLQ['id']).'. Cannot Schedule Events!</div>';
          }
        
        $dta = $this->getController()->getModel()->getData();
        $estateCore = new estateCore;
        $text = '<div id="estEvtBox"><div id="estEvtCalCont">';
        $text .= $estateCore->buildEventCal(intval($URLQ['id']));
        $text .= '</div></div>';
        return $text;
        break;
			case 'read': 
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  
  
  public function sched_agt_times($curVal,$mode){
    switch($mode){
			case 'write':
        $locale = e107::pref('estate','locale');
        $estateCore = new estateCore;
        $dta = array('deftime'=>array('n'=>'sched_agt_times','v'=>$curVal,'l'=>EST_GEN_AVAILABLE,'h'=>array(EST_PREF_DEFHRSHINT0,EST_PREF_DEFHRSHINT1)));
        $text = $estateCore->getCalTbl('start',$locale);
        $text .= $estateCore->getCalTbl('head',$locale);
        $text .= '<tbody>';
        $text .= $estateCore->getCalTbl('tr',$locale,$dta);
        $text .= '</tbody>';
        $text .= $estateCore->getCalTbl('end',$locale);
        return $text;
        break;
			case 'read': 
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  public function sched_pub_times($curVal,$mode){
    switch($mode){
			case 'write':
        $locale = e107::pref('estate','locale');
        $estateCore = new estateCore;
        $dta = array('deftime'=>array('n'=>'sched_pub_times','v'=>$curVal,'l'=>EST_GEN_AVAILABLE,'h'=>array(EST_PREF_DEFHRSHINT0,EST_PREF_DEFHRSHINT1)));
        $text = $estateCore->getCalTbl('start',$locale);
        $text .= $estateCore->getCalTbl('head',$locale);
        $text .= '<tbody>';
        $text .= $estateCore->getCalTbl('tr',$locale,$dta);
        $text .= '</tbody>';
        $text .= $estateCore->getCalTbl('end',$locale);
        return $text;
        break;
			case 'read': 
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  public function eventkeys($curVal,$mode){
    switch($mode){
			case 'write':
        //$dta = array('defevtlen'=>array('n'=>'sched_evt_lengths','v'=>$curVal,'h'=>array(EST_PREF_DEFHRSHINT0,EST_PREF_DEFHRSHINT1)));
        if(!is_array($curVal) || count($curVal) == 0){
          $curVal = array(
            0=>array('l'=>EST_GEN_PRIVATEVIEWING,'t'=>'0:30','ms'=>1800),
            1=>array('l'=>EST_GEN_OPENHOUSE,'t'=>'4:00','ms'=>14400),
            2=>array('l'=>EST_GEN_INSPECTION,'t'=>'2:00','ms'=>7200),
            3=>array('l'=>EST_GEN_MEETING,'t'=>'1:30','ms'=>5400),
            4=>array('l'=>EST_GEN_CLOSING,'t'=>'2:00','ms'=>7200),
            );
          }
        
        $text = '
        <table class="table adminform table-striped estCustomTable1" style="width:unset;">
          <colgroup></colgroup>
          <colgroup></colgroup>
          <colgroup></colgroup>
          <thead>
          <tr>
            <th>'.EST_PREF_EVENTNAME.'</th>
            <th>'.EST_PREF_EVENTLEN.'</th>
            <th class="TAC">'.LAN_OPTIONS.'</th>
          </tr>
          </thead>
          <tbody id="estEventKeysTB">';
        
        $ri=0;
        foreach($curVal as $k=>$v){
          if(strlen($v['l']) > 2){
            if(strlen($v['t']) == 4){$v['t'] = '0'.$v['t'];}
            $text .= '
            <tr>
              <td>
                <input type="hidden" name="eventkeys['.$ri.'][ms]" value="'.$v['ms'].'" />
                <input type="text" name="eventkeys['.$ri.'][l]" class="tbox form-control input-large" value="'.$v['l'].'" />
              </td>
              <td>
                <select name="eventkeys['.$ri.'][t]" class="tbox form-control input-medium ui-state-valid estPrefEventSel" value="'.$v['t'].'" data-key="'.$ri.'">';
          
            for($i = 0.25; $i <= 12; $i = $i + 0.25){
              $ms = floor($i * 3600);
              $tme = gmdate('H:i',$ms);
              $text .= '
                  <option'.($tme == $v['t'] ? ' selected="selected"' : '').' value="'.$tme.'" data-ms="'.$ms.'">'.$tme.'</option>';
              $EvtKeyOpts .= '<option value="'.$tme.'" data-ms="'.$ms.'">'.$tme.'</option>';
              }
            $text .= '
                </select>
              </td>
              <td class="TAC">
                <div class="btn-group WSNWRP">
                  <button type="button" name="eventkey_delete['.$ri.']" class="btn btn-small btn-default estPrefEventDel" title="'.EST_GEN_DELETEEVTKEY.'"'.($ri < 5 ? ' disabled="disabled"' : '').'><i class="fa fa-close"></i></button>
                </div>
              </td>
            </tr>';
            $ri++;
            }
            
          }
        
        $text .= '
          </tbody>
          <tfoot>
            <tr>
              <td colspan="3" class="TAC">
                <button type="button" id="estNewEventKey" class="btn btn-primary" title="'.EST_GEN_NEWEVENTNAME.'"><i class="fa fa-plus"></i> '.EST_GEN_NEWEVENTNAME.'</button>
              </td>
            </tr>
          </tfoot>
        </table>
        <select id="estEvtKeyOpts" style="display:none;">'.$EvtKeyOpts.'</select>';
        return $text;
        break;
			case 'read': 
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  
  
  public function prop_map($curVal,$mode){
    switch($mode){
			case 'write':
        return '
        <div class="estInptCont form-group has-feedback-left estMapSearchCont">
          <input type="text" id="prop_addr_lookup" name="prop_addr_lookup" class="tbox form-control input-xxlarge estMapLookupAddr" value="" placeholder="'.EST_PLCH15.'"/>
          <button id="est_prop_SrchBtn" class="btn btn-default estMapSearchBtn">'.LAN_SEARCH.'</button>
        </div>
        <div id="est_prop_MapCont" class="estMapCont"><div id="est_prop_Map" class="estMap"></div></div>';
        break;
			case 'read': 
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  
  public function map_url($curVal,$mode){
    switch($mode){
			case 'write':
        $curVal = e107::getParser()->toFORM($curVal);
        return '
        <div class="estInptCont">
          <input type="text" id="map-url" name="map_url" class="tbox form-control input-xxlarge estMapLookupAddr" value="'.$curVal.'" placeholder="https://somesite.com"/>
          <button id="estMapUrlReset" class="btn btn-default estMapSearchBtn">'.EST_PREF_MAPURLRESET.'</button>
        </div>';
        break;
			case 'read': 
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  public function map_key($curVal,$mode){
    switch($mode){
			case 'write':
        $curVal = e107::getParser()->toFORM($curVal);
        return '
        <div class="estInptCont">
          <input type="text" id="map-key" name="map_key" class="tbox form-control input-xxlarge estMapLookupAddr" value="'.$curVal.'"/>
          <button id="estMapKeyReset" class="btn btn-default estMapSearchBtn">'.EST_PREF_MAPKEYRESET.'</button>
        </div>';
        break;
			case 'read': 
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  public function pref_addr_lookup($curVal,$mode){
    switch($mode){
			case 'write':
        return '<div class="divRemTR"></div>';
        break;
			case 'read': 
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  public function pref_map($curVal,$mode){
    switch($mode){
			case 'write': 
        $pref_addr_lookup = e107::getParser()->toFORM(e107::pref('estate','pref_addr_lookup'));
        return '
        <div>
          <div class="estInptCont form-group has-feedback-left estMapSearchCont">
            <input type="text" id="pref_addr_lookup" name="pref_addr_lookup" class="tbox form-control input-xxlarge estMapLookupAddr" value="'.$pref_addr_lookup.'" placeholder="Enter an address to look up"/>
            <button id="est_pref_SrchBtn" class="btn btn-default estMapSearchBtn">'.LAN_SEARCH.'</button>
          </div>
        </div>
        <div>
          <div id="estPrefMapCont" class="estMapCont">
            <div id="est_pref_Map" class="estMap"></div>
          </div>
          <div style="display:inline-block;vertical-align:top; width:29%;">
            <p>'.EST_GEN_MAPHLP6.'</p>
            <p>'.EST_GEN_MAPHLP7.'</p>
            <p>'.EST_GEN_MAPHLP1.'</p>
            <p>'.EST_GEN_MAPHLP4.'</p>
            <p>'.EST_GEN_MAPHLP5.'</p>
          </div>
        </div>';
        break;
			case 'read': 
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  
  public function prop_gallery($curVal,$mode){
    switch($mode){
			case 'write':
        return '
        <div id="estNoGalWarn" class="s-message alert alert-block warning alert-warning">'.EST_PROP_MSG_NEEDSAVE.' '.EST_PROP_MSG_ADDMEDIA.'</div>
        <div class="WD100"><table id="estate-gallery-tabl" class="table-striped WD100">
          <thead>
            <tr id="estGalleryH1">
              <th>
                <div id="estGalFileSlipCont">
                  <label id="fileSlip" for="upFile">
                    <button id="fileSlipBtn" class="btn btn-primary btn-sm FR">'.EST_UPLOAD.' '.EST_MEDIA.'</button>
                  </label>
                </div>
              '.EST_MEDIAAVAILABLE.'
              </th>
            </tr>
            <tr id="estGalleryH2"><td><div class="estBeltLoop"><div id="estGalleryBelt" class="estBelt estGalCont"></div></div></td></tr>
            <tr id="estGalleryH3"><th>'.EST_MEDIAINUSE.'</th></tr>
          </thead>
          <tbody>
            <tr id="estGalleryH4"><td><div id="estGalleryUsed" class="estGalCont"></div></td></tr>
          </tbody>
        </table></div>';
        break;
			case 'read': 
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  
  
  
  public function prop_bathmain($curVal,$mode){
    switch($mode){
			case 'read': // List Page
        return $curVal;
				break;

			case 'write': // Edit Page
        return '<div id="propBathsCont" class="estInptCont"><div class="ILMINI"><i>'.EST_GEN_MAINLEV.'</i><input type="number" name="prop_bathmain" value="'.intval($curVal).'" min="0" step="1" id="prop-bathmain" class="tbox number e-spinner input-small form-control ui-state-valid" pattern="^[0-9]*" data-original-title="" title=""></div></div>';
        break;
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  
  public function prop_bathfull($curVal,$mode){
    switch($mode){
			case 'read': // List Page
        return $curVal;
				break;
			case 'write': // Edit Page
        return '<div class="estSpaceTRrem"></div><div class="ILMINI"><i>'.EST_GEN_FULL.'</i><input type="number" name="prop_bathfull" value="'.intval($curVal).'" min="0" step="1" id="prop-bathfull" class="tbox number e-spinner input-small form-control ui-state-valid " pattern="^[0-9]*" data-original-title="" title=""></div>';
        break;
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  public function prop_bathhalf($curVal,$mode){
    switch($mode){
			case 'read': // List Page
        return $curVal;
				break;
			case 'write': // Edit Page
        return '<div class="estSpaceTRrem"></div><div class="ILMINI"><i>'.EST_GEN_HALF.'</i><input type="number" name="prop_bathhalf" value="'.intval($curVal).'" min="0" step="1" id="prop-bathhalf" class="tbox number e-spinner input-small form-control ui-state-valid " pattern="^[0-9]*" data-original-title="" title=""></div>';
        break;
			case 'filter':
			case 'batch':
				break;
      }
    }
  public function prop_bathtot($curVal,$mode){
    switch($mode){
			case 'read': // List Page
        return $curVal;
				break;
			case 'write': // Edit Page
        return '<div class="estSpaceTRrem"></div><div class="ILMINI"><i>'.EST_GEN_TOTAL.'</i><input type="number" name="prop_bathtot" value="'.intval($curVal).'" min="0" step="1" id="prop-bathtot" class="tbox number e-spinner input-small form-control ui-state-valid " pattern="^[0-9]*" data-original-title="" title=""></div>';
        break;
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  
  public function prop_bedmain($curVal,$mode){
    switch($mode){
			case 'read': // List Page
        return $curVal;
				break;

			case 'write': // Edit Page
        return '<div id="propBedsCont" class="estInptCont"><div class="ILMINI"><i>'.EST_GEN_MAINLEV.'</i><input type="number" name="prop_bedmain" value="'.intval($curVal).'" min="0" step="1" id="prop-bedmain" class="tbox number e-spinner input-small form-control ui-state-valid" pattern="^[0-9]*" data-original-title="" title=""></div></div>';
        break;
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  public function prop_bedtot($curVal,$mode){
    switch($mode){
			case 'read': // List Page
        return $curVal;
				break;
			case 'write': // Edit Page
        return '<div class="estSpaceTRrem"></div><div class="ILMINI"><i>'.EST_GEN_TOTAL.'</i><input type="number" name="prop_bedtot" value="'.intval($curVal).'" min="0" step="1" id="prop-bedtot" class="tbox number e-spinner input-small form-control ui-state-valid " pattern="^[0-9]*" data-original-title="" title=""></div>';
        break;
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  
  public function prop_floorno($curVal,$mode){
    switch($mode){
			case 'read': // List Page
        return $curVal;
				break;
			case 'write': // Edit Page 
        return '<div id="propUnitCont" class="estInptCont"><div class="ILMINI"><i>'.EST_GEN_FLOORNO.'</i><input type="number" name="prop_floorno" value="'.intval($curVal).'" min="0" step="1" id="prop-floorno" class="tbox number e-spinner input-small form-control ui-state-valid " pattern="^[0-9]*" data-original-title="" title=""></div></div>';
        break;
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  public function prop_bldguc($curVal,$mode){
    switch($mode){
			case 'read': // List Page
        return $curVal;
				break;
			case 'write': // Edit Page 
        return '<div class="estSpaceTRrem"></div><div class="ILMINI"><i>'.EST_GEN_UNITSBLDG.'</i><input type="number" name="prop_bldguc" value="'.intval($curVal).'" min="0" step="1" id="prop-bldguc" class="tbox number e-spinner input-small form-control ui-state-valid " pattern="^[0-9]*" data-original-title="" title=""></div>';
        break;
			case 'filter':
			case 'batch':
				break;
      }
    }
  public function prop_complxuc($curVal,$mode){
    switch($mode){
			case 'read': // List Page
        return $curVal;
				break;
			case 'write': // Edit Page 
        return '<div class="estSpaceTRrem"></div><div class="ILMINI"><i>'.EST_GEN_UNITSCOMPLX.'</i><input type="number" name="prop_complxuc" value="'.intval($curVal).'" min="0" step="1" id="prop-complxuc" class="tbox number e-spinner input-small form-control ui-state-valid " pattern="^[0-9]*" data-original-title="" title=""></div>';
        break;
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  
  
  public function prop_citypreview($curVal,$mode){
    switch($mode){
			case 'write':
        $estateCore = new estateCore;
        return $estateCore->estCitySpaces();
				break;
      }
    }
  
  public function prop_comminuty($curVal,$mode){
    switch($mode){
			case 'write':
        $estateCore = new estateCore;
        return $estateCore->estCommunitySpaces();
				break;
      }
    }
  
  public function prop_spaces($curVal,$mode){
    switch($mode){
			case 'read': // List Page
        return $curVal;
				break;

			case 'write': // Edit Page
        return '<div id="estSpaceGrpDiv" class="estSpaceGrpDiv"></div>';
        break;
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  
  public function addnewuser($curVal,$mode){
    switch($mode){
			case 'read':
				break;
			case 'write': 
        $ANU = intval(e107::pref('estate','addnewuser'));
        return '<select name="addnewuser" class="form-control input-xlarge ui-state-valid" value="'.$ANU.'"><option value="0"'.($ANU == 0 ? ' selected="selected"' : '').'>No One</option><option value="4"'.($ANU == 4 ? ' selected="selected"' : '').'>'.EST_GEN_ESTATE.' '.EST_GEN_MAINADMIN.' '.EST_GEN_ONLY.'</option><option value="3"'.($ANU == 3 ? ' selected="selected"' : '').'>'.EST_GEN_ESTATE.' '.EST_GEN_ADMIN.' & '.EST_GEN_MAINADMIN.'</option><option value="2"'.($ANU == 2 ? ' selected="selected"' : '').'>'.EST_GEN_ESTATE.' '.EST_GEN_MANAGER.', '.EST_GEN_ADMIN.', & '.EST_GEN_MAINADMIN.'</option></select>';
        break;
      }
    }
  
  
  
  public function prop_template_menu_ord($curVal,$mode){
    switch($mode){
			case 'read':
				break;
			case 'write': 
        return '<div id="estprop_menuOrderCont" style="max-width: 312px;"></div>';
        break;
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  
  public function prop_template_view_ord($curVal,$mode){
    switch($mode){
			case 'read':
				break;
			case 'write': 
        //$curVal = e107::unserialize($curVal);
        return '<div id="estprop_viewOrderCont" style="max-width: 312px;"></div>'; //<div>'.$curVal.'</div>
        break;
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  
  public function template_menu_ord($curVal,$mode){
    switch($mode){
			case 'read':
				break;
			case 'write': 
        return '<div id="estmenuOrderCont"></div>';
        break;
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  
  public function template_view_ord($curVal,$mode){
    switch($mode){
			case 'read':
				break;
			case 'write': 
        return '<div id="estviewOrderCont"></div>';
        break;
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  
  }
