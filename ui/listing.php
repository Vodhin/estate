<?php
if(!defined('e107_INIT')){exit;}



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
    EST_GEN_SEO
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
			'prop_views' => array (
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
			'prop_landfreq' => array (
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
        'title' => EST_GEN_SUBDIVISION,
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
			'prop_hoaappr' => array (
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
      
      'prop_bedmain' => array (
        'tab'=>3,
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
        'tab'=>3,
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
        'tab'=>3,
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
        'tab'=>3,
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
        'tab'=>3,
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
        'tab'=>3,
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
      
      'prop_sef' => array (
        'tab'=>7,
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
    
		protected $fieldpref = array('prop_name','forum_parent','Sort','forum_description','forum_class','forum_postclass','forum_threadclass','forum_order');










		protected $preftabs = array(EST_GEN_GENERAL,EST_GEN_LAYOUT_LIST,EST_GEN_LAYOUT_VIEW,EST_GEN_SCHEDULING,EST_GEN_MAP,EST_GEN_NONAGENTLISTINGS);
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
      
      'layout_preview_listpage'=>array(
        'tab'=>1,
        'title'=>EST_PREF_LISTPAGESECT.'<table class="table adminform WD100 BGCTransp"><colgroup style="width:50%"></colgroup><colgroup style="width:50%"></colgroup><tbody id="estPrefListPageOptTB"></tbody></table>',
        'type'=>'method',
        'data'=>false,
        'writeParms' => array(),
        ),
      
      'layout_list'=>array(
        'tab'=>1,
        'type'=>'dropdown',
        'data'=>'str',
        'width' => 'auto',
        //'estListTiles'=>EST_PREF_TILELAYOUT
        'writeParms' => array(
          'nolabel'=>1,
          'size'=>'xlarge',
          'optArray'=>array(''=>EST_PREF_ROWLAYOUT,'estListTiles estListTile45'=>EST_PREF_2TILEACROSS,'estListTiles estListTile30'=>EST_PREF_3TILEACROSS)
          ),
        ),
      /*
      'layout_list_wd'=>array(
        'tab'=>1,
        'type'=>'dropdown',
        'data'=>'str',
        'width' => 'auto',
        'writeParms' => array('nolabel'=>1,'size'=>'xlarge','optArray'=>array(),
        ),
      */
      'layout_list_map'=>array(
        'tab'=>1,
        'type'=>'dropdown',
        'data'=>'str',
        'width' => 'auto',
        'writeParms' => array('nolabel'=>1,'size'=>'xlarge','optArray'=>array(0=>EST_PREF_MAPVIEWNOMAP,1=>EST_PREF_MAPLISTABOVE,2=>EST_PREF_MAPLISTBELOW)),
        ),
      
      'layout_list_mapagnt'=>array(
        'tab'=>1,
        'type'=>'dropdown',
        'data'=>'str',
        'width' => 'auto',
        'writeParms' => array('nolabel'=>1,'size'=>'xlarge','optArray'=>array(0=>EST_PREF_MAPVIEWNOMAPAG,1=>EST_PREF_MAPVIEWPROPMAPAG)),
        ),
      
      
      'view_template'=>array(
        'tab'=>2,
        'title'=>EST_PREF_VIEWTEMPLATE,
        'type'=>'method',
        'data'=>'safestr',
        'help'=>EST_PREF_TEMPLATEHLP,
        'inline'=>false,
        'nolist'=>true,
        'class' => 'left',
        'thclass' => 'left',
        'writeParms' => array(),
        ),
      
      'layout_preview'=>array(
        'tab'=>2,
        'title'=>EST_PREF_SAMPLISTING,
        'type'=>'method',
        'data'=>false,
        'help'=>EST_PREF_VIEWLAYOUTHLP,
        'writeParms' => array(),
        ),
      
      'layout_preview_top'=>array(
        'tab'=>2,
        'title'=>EST_PREF_SLIDESHOWSECT.'<table class="table adminform WD100 BGCTransp"><colgroup style="width:50%"></colgroup><colgroup style="width:50%"></colgroup><tbody id="estPrefSlideShowOptTB"></tbody></table>',
        'type'=>'method',
        'data'=>false,
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
      
      
      
      'layout_preview_summary'=>array(
        'tab'=>2,
        'title'=>'<table class="table adminform WD100 BGCTransp"><colgroup style="width:50%"></colgroup><colgroup style="width:50%"></colgroup><tbody id="estPrefAgentTB"></tbody></table>',
        'type'=>'method',
        'data'=>false,
        'writeParms' => array(),
        ),
      
      'layout_view_summbg'=>array(
        'tab'=>2,
        'title'=>EST_PREF_SUMMSECT,
        'type'=>'dropdown',
        'data'=>'str',
        'width' => 'auto',
        'writeParms' => array('nolabel'=>1,'size'=>'xlarge','optArray'=>array(''=>EST_PREF_SECTIONBG0,'estBGGrad1'=>EST_PREF_SECTIONBG1)),
        ),
      'layout_view_agent'=>array(
        'tab'=>2,
        //'title'=>EST_PREF_AGNTCARD,
        'type'=>'dropdown',
        'data'=>'str',
        'width' => 'auto',
        'writeParms' => array('nolabel'=>1,'size'=>'xlarge','optArray'=>array(''=>EST_PREF_OVERVIEWNORM,'flexRev'=>EST_PREF_OVERVIEWREV)),
        ),
      'layout_view_agntbg'=>array(
        'tab'=>2,
        //'title'=>EST_PREF_AGNTCARDBG,
        'type'=>'dropdown',
        'data'=>'str',
        'width' => 'auto',
        'writeParms' => array('nolabel'=>1,'size'=>'xlarge','optArray'=>array(''=>EST_PREF_AGNTBG0,'estBGGrad2'=>EST_PREF_AGNTBG1)),
        ),
      
      
      'layout_preview_spaces'=>array(
        'tab'=>2,
        'title'=>'<table class="table adminform WD100 BGCTransp"><colgroup style="width:50%"></colgroup><colgroup style="width:50%"></colgroup><tbody id="estPrefSpacesTB"></tbody></table><div id="estPrefSpcLay1"><p>'.EST_PREF_SPACEHLP1.'</p><p>'.EST_PREF_SPACEHLP2.'</p><p>'.EST_PREF_SPACEHLP3.'</p></div>',
        'type'=>'method',
        'data'=>false,
        'writeParms' => array(),
        ),
      'layout_view_spacesbg'=>array(
        'tab'=>2,
        'title'=>EST_PREF_SPACES,
        'type'=>'dropdown',
        'data'=>'str',
        'width' => 'auto',
        'writeParms' => array('nolabel'=>1,'size'=>'xlarge','optArray'=>array(''=>EST_PREF_SECTIONBG0,'estBGGrad1'=>EST_PREF_SECTIONBG1)),
        ),
      
      'layout_view_spaces_ss'=>array(
        'tab'=>2,
        'title'=>EST_PREF_SLIDESHOWACT,
        'type'=>'boolean',
        'data'=>'int',
        'help'=>EST_PREF_SLIDESHOWACTHLP1,
        ),
      
      'layout_view_spaces'=>array(
        'tab'=>2,
        //'title'=>EST_PREF_SPACES,
        'type'=>'dropdown',
        'data'=>'str',
        'width' => 'auto',
        'writeParms' => array('nolabel'=>1,'size'=>'xlarge','optArray'=>array('tiles'=>EST_PREF_SPACETILES,'dynamic'=>EST_PREF_SPACEDYNAM,'dynamicf'=>EST_PREF_SPACEDYNAMF)),
        ),
      'layout_view_spacetilebg'=>array(
        'tab'=>2,
        //'title'=>EST_PREF_SPACES_TILEBG,
        'type'=>'dropdown',
        'data'=>'str',
        'width' => 'auto',
        'writeParms' => array('nolabel'=>1,'size'=>'xlarge','optArray'=>array(''=>EST_PREF_SPTILEBG0,'estBGGrad2'=>EST_PREF_SPTILEBG1)),
        ),
      'layout_view_spacedynbg'=>array(
        'tab'=>2,
        //'title'=>EST_PREF_SPACES_DYNAMBG,
        'type'=>'dropdown',
        'data'=>'str',
        'width' => 'auto',
        'writeParms' => array('nolabel'=>1,'size'=>'xlarge','optArray'=>array(''=>EST_PREF_SPDYNBG0,'estBGGrad2'=>EST_PREF_SPDYNBG1)),
        ),
      
      
      'layout_preview_mapview'=>array(
        'tab'=>2,
        'title'=>'<table class="table adminform WD100 BGCTransp"><colgroup style="width:50%"></colgroup><colgroup style="width:50%"></colgroup><tbody id="estPrefMapOptTB"></tbody></table>',
        'type'=>'method',
        'data'=>false,
        'writeParms' => array(),
        ),
      
      
      'layout_view_map'=>array(
        'tab'=>2,
        'type'=>'dropdown',
        'data'=>'str',
        'width' => 'auto',
        'writeParms' => array('nolabel'=>1,'size'=>'xlarge','optArray'=>array(0=>EST_PREF_MAPVIEWNOMAP,1=>EST_PREF_MAPVIEWPROPMAP)),
        ),
      
      'layout_view_mapagnt'=>array(
        'tab'=>2,
        'type'=>'dropdown',
        'data'=>'str',
        'width' => 'auto',
        'writeParms' => array('nolabel'=>1,'size'=>'xlarge','optArray'=>array(0=>EST_PREF_MAPVIEWNOMAPAG,1=>EST_PREF_MAPVIEWPROPMAPAG)),
        ),
      
      'layout_view_mapbg'=>array(
        'tab'=>2,
        //'title'=>EST_PREF_MAPBG,
        'type'=>'dropdown',
        'data'=>'str',
        'width' => 'auto',
        'writeParms' => array('nolabel'=>1,'size'=>'xlarge','optArray'=>array(''=>EST_PREF_SECTIONBG0,'estBGGrad1'=>EST_PREF_SECTIONBG1)),
        ),
      
      
      'layout_preview_gallery'=>array(
        'tab'=>2,
        'title'=>'<table class="table adminform WD100 BGCTransp"><colgroup style="width:50%"></colgroup><colgroup style="width:50%"></colgroup><tbody id="estPrefgalOptTB"></tbody></table>',
        'type'=>'method',
        'data'=>false,
        'writeParms' => array(),
        ),
      'layout_view_gallbg'=>array(
        'tab'=>2,
        'title'=>EST_PREF_GALBG,
        'type'=>'dropdown',
        'data'=>'str',
        'width' => 'auto',
        'writeParms' => array('nolabel'=>1,'size'=>'xlarge','optArray'=>array(''=>EST_PREF_SECTIONBG0,'estBGGrad1'=>EST_PREF_SECTIONBG1)),
        ),
      
      'sched_agt_times'=>array(
        'tab'=>3,
        'title'=>EST_PREF_DEFAGTHRS,
        'type'=>'method',
        'data'=>'array',
        'help'=>EST_PREF_DEFAGTHRSHLP,
        ),
      'sched_pub_times'=>array(
        'tab'=>3,
        'title'=>EST_PREF_DEFPUBHRS,
        'type'=>'method',
        'data'=>'array',
        'help'=>EST_PREF_DEFPUBHRSHLP,
        ),
      
      'sched_evt_lengths'=>array(
        'tab'=>3,
        'title'=>EST_PREF_DEFEVTLEN,
        'type'=>'method',
        'data'=>'array',
        'help'=>EST_PREF_DEFEVTLENHLP,
        ),
      
      'map_jssrc'=>array( 
        'tab'=>4,
        'title'=>EST_PREF_MAPMAP_JSSRC,
        'type'=>'dropdown',
        'data'=>'str',
        'width' => 'auto',
        'help'=>EST_PREF_MAPMAP_JSSRCHLP,
        'writeParms' => array('size'=>'xlarge','optArray'=>array(0=>EST_PREF_MAPMAP_JSSRCOPT0,1=>EST_PREF_MAPMAP_JSSRCOPT1)),
        ),
      'map_url'=>array(
        'tab'=>4,
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
        'tab'=>4,
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
        'tab'=>4,
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
        'tab'=>4,
        'type'=>'method',
        'data'=>false,
        'inline'=>false,
        'nolist'=>true,
        'title'=>'<div id="est_pref_SrchRes" class="estMapBtnCont"></div>',
        'writeParms' => array(),
        'class' => 'center',
        ),
			'pref_lat'=>array(
        'tab'=>4,
        'type'=>'hidden',
        'data'=>'safestr',
        'inline'=>false,
        'nolist'=>true,
        'writeParms' => array('size'=>'xlarge estMapReset'),
        'class' => 'left',
        'thclass' => 'left',
        ),
      'pref_lon'=>array(
        'tab'=>4,
        'type'=>'hidden',
        'data'=>'safestr',
        'inline'=>false,
        'nolist'=>true,
        'writeParms' => array('size'=>'xlarge estMapReset'),
        'class' => 'left',
        'thclass' => 'left',
        ),
			'pref_zoom'=>array(
        'tab'=>4,
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
        'tab'=>4,
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
        'tab'=>4,
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
        'tab'=>5,
        'title'=>EST_GEN_NONAGENTLISTINGS,
        'type'=>'userclass',
        'data'=>'int',
        'help'=>EST_HINT_NONAGENTLISTINGS,
        'readParms' =>  array (),
        'writeParms' =>  array ('classlist'=>'member,nobody,no-excludes'),
        ),
      /*
		<pref name='public_act'>255</pref>
		<pref name='public_apr'>1</pref>
		<pref name='public_exp'>30</pref>
		<pref name='public_imgct'>12</pref>
      
      */
      );
    
    
    
		public function init() {
      $tp = e107::getParser();
      $mes = e107::getMessage();
      
      
			$this->fields['prop_country']['writeParms']['default'] = 'blank';
			$this->fields['prop_country']['writeParms']['optArray'] = e_form::getCountry();
			$this->fields['prop_country']['writeParms']['default'] = 'blank';
      
			$this->prefs['country']['writeParms']['optArray'] = e_form::getCountry();
      
      $data2 = array();
      if(count($GLOBALS['EST_PROPSTATUS'])){foreach($GLOBALS['EST_PROPSTATUS'] as $k=>$v){$data2[$k] = $v['opt'];}}
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
      
      //$text = $this->table;
      //$mes->addInfo('blurf: '.$table);//'Test: ['.$tst2.'] '.$data2[0]['city_zip']
      
      
      //$sql = e107::getDB();
      //if($sql->gen('SELECT * FROM #estate_states ')){
        //while($row = $sql->fetch()){$ESTATES[$row['state_idx']] = $tp->toHTML($row['state_name']);}
        //}
      
			// Set drop-down values (if any). 
      //$optarr[-1] = 'New Subdivision';
		
      //$tp = e107::getParser();
      
      //e_form:datepicker($name, $datestamp = false, $options = null)
      //$frm = e_form;
      //$frm->datepicker('my_field',time(),'mode=date&format=yyyy-mm-dd');
      
      //$testm = $this->getMode();
      //$testm = $this->getAction();
      //$mes->addSuccess('You did it!');
      //echo $mes->render();
      
    
		}

		
		// ------- Customize Create --------
		
		public function beforeCreate($new_data,$old_data){
      $new_data['prop_datecreated'] = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
      $new_data['prop_dateupdated'] = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
      $new_data['prop_uidcreate'] = USERID;
      $new_data['prop_uidupdate'] = USERID;
      
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
      //$time = time();
      $tp = e107::getParser();
      $new_data['prop_dateupdated'] = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
      $new_data['prop_uidupdate'] = USERID;
      
      if(empty($old_data['prop_name']) || $old_data['prop_name'] = EST_PROP_UNNAMEDPROP || $new_data['prop_name'] == EST_PROP_UNNAMEDPROP){
        if(!empty($new_data['prop_addr1'])){$new_data['prop_name'] = trim($tp->toText($new_data['prop_addr1']));}
        elseif(!empty($old_data['prop_addr1'])){$new_data['prop_name'] = trim($tp->toText($old_data['prop_addr1']));}
        else{$new_data['prop_name'] = trim($tp->toText(EST_PROP_UNNAMEDPROP));}
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
            <b>'.EST_GEN_SEO.'</b>
            <p>'.EST_HLPMNU_SEO1.'</p>
          </div>
          <div id="estEditHelp-8" class="estEditHelpSect">
          </div>';
          }
        else if($hlpactn == 'prefs'){
          $text .= '
          <div id="estEditHelp-0" class="estEditHelpSect">
            <b id="estHlp-prefGen1">General Options</b>
            <p id="estHlp-prefGen1">Set general options for this plugin.</p>
          </div>
          <div id="estEditHelp-1" class="estEditHelpSect">
            <b id="estHlp-prefGen1">List Page Template</b>
            <p id="estHlp-prefGen1">Set options for the Property Listings Page</p>
          </div>
          <div id="estEditHelp-2" class="estEditHelpSect">
            <b id="estHlp-prefGen1">View Page Template</b>
            <p id="estHlp-prefGen1">Set Options for the View Listing Template</p>
          </div>
          <div id="estEditHelp-3" class="estEditHelpSect">
            <b id="estHlp-prefGen1">Scheduling Options</b>
            <p id="estHlp-prefGen1">Set Default Schedule Options</p>
          </div>
          <div id="estEditHelp-4" class="estEditHelpSect">
            <b id="estHlp-prefGen1">Map Options</b>
            <p id="estHlp-prefGen1">Set map options for this plugin</p>
          </div>
          <div id="estEditHelp-5" class="estEditHelpSect">
            <b id="estHlp-prefGen1">Non-Agent Listings</b>
            <p id="estHlp-prefGen1">Set options for listings posted by non-agents</p>
          </div>';
          }
        else{
          $text .= '
          <div class="estEditHelpSect">
            <p id="estHlp-proplist1">'.EST_HLPMNU_PROPLIST1.'</p>
            <p id="estHlp-proplist2">'.EST_HLPMNU_PROPLIST2.'</p>
            <b id="estHlp-proplist3">'.EST_HLPMNU_PROPLIST3.'</b>
            <p id="estHlp-proplist4">'.EST_HLPMNU_PROPLIST4.'</p>
            <p id="estHlp-proplist5">'.EST_HLPMNU_PROPLIST5.'</p>
            <b id="estHlp-proplist6">'.EST_HLPMNU_PROPLIST6.'</b>
            <p id="estHlp-proplist7">'.EST_HLPMNU_PROPLIST7.'</p>
            <p id="estHlp-proplist8">'.EST_HLPMNU_PROPLIST8.'</p>
          </div>';
          }
        }
      $text .= '</div>';
      $text = $tp->toHTML($text,true);
      //$text = str_replace("[b]","<b>",str_replace("[/b]","</b>",$text));
			return array('caption'=>$caption,'text'=> $text);
		  }
			
	/*	
		// optional - a custom page.  
		public function customPage()
		{
			$text = 'Hello World!';
			$otherField  = $this->getController()->getFieldVar('other_field_name');
			return $text;
			
		}
		
	*/
			
  }




class estate_listing_form_ui extends e_admin_form_ui{
  
  
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
    $rows = $estateCore->estGetAgentById($curVal);
    switch($mode){
			case 'read': 
        //$dta = $this->getController()->getListModel();
        $text = '<div class="estPropListAgentCont"><div>'.$tp->toHTML($rows['agency_name']).'</div><div><div style="background-image:url(\''.$rows['agent_profimg'].'\')"></div><a href="'.e_SELF.'?mode=estate_agencies&action=agent&id='.intval($rows['agent_idx']).'">'.$tp->toHTML($rows['agent_name']).'</a></div></div>';
        return $text;
				break;

			case 'write': 
        //$dta = $this->getController()->getModel()->getData();
        $rows = $estateCore->estGetAgentById($curVal);
        $text .= '
          <input type="hidden" name="prop_agent" value="'.intval($rows['agent_idx']).'">
          <div id="estAgentContDiv" class="estBtnCont">
            <button type="button" id="estAgencySelBtn" class="btn btn-default estNoRightBord" data-agcy="'.intval($rows['agent_agcy']).'">'.$tp->toHTML($rows['agency_name']).'</button>
            <button type="button" id="estAgentSelBtn" class="btn btn-primary estNoLRBord">
              <div id="estAgentMinPic" style="background-image:url(\''.$rows['agent_profimg'].'\')"></div>
              <span id="estAgentSelBtnTxt">'.$tp->toHTML($rows['agent_name']).'</span>
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
  
  
  public function prop_hours($curVal,$mode){
    switch($mode){
			case 'write' :
        $estateCore = new estateCore;
        return $estateCore->estPropHoursForm($curVal);
        break;
			case 'read': 
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
        if(count($GLOBALS['EST_LISTTYPE1']) > 0){
          foreach($GLOBALS['EST_LISTTYPE1'] as $K=>$V){
            $text .= '<option value="'.$K.'"'.($K == $curVal ? ' selected="selected"' : '').'>'.$tp->toHTML($V).'</option>';
            }
          }
        
        $text .= '</select>';
        return $text;
				break;

			case 'write': // Edit Page
        $text = $frm->select_open("prop_listype",array('value'=>$curVal,'size'=>'xlarge'));
        if(count($GLOBALS['EST_LISTTYPE1']) > 0){
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
  
  public function prop_events($curVal,$mode){
    switch($mode){
      case 'write':
        parse_str(html_entity_decode(e_QUERY), $URLQ);
        
        if($URLQ['action'] == 'create'){
          return '<div id="estNoEvtWarn" class="s-message alert alert-block warning alert-warning" style="display: block;">This Property needs to be saved before scheduling Events</div>';
          }
        
        if(intval($URLQ['id']) == 0){
          return '<div id="estNoEvtWarn" class="s-message alert alert-block warning alert-warning" style="display: block;">No Property Index: '.intval($URLQ['id']).'. Cannot Schedule Events!</div>';
          }
        
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
        $estateCore = new estateCore;
        $dta = array('deftime'=>array('n'=>'sched_agt_times','v'=>$curVal,'l'=>EST_GEN_AVAILABLE,'h'=>array(EST_PREF_DEFHRSHINT0,EST_PREF_DEFHRSHINT1)));
        $text = $estateCore->getCalTbl('start');
        $text .= $estateCore->getCalTbl('head');
        $text .= '<tbody>';
        $text .= $estateCore->getCalTbl('tr',$dta);
        $text .= '</tbody>';
        $text .= $estateCore->getCalTbl('end');
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
        $estateCore = new estateCore;
        $dta = array('deftime'=>array('n'=>'sched_pub_times','v'=>$curVal,'l'=>EST_GEN_AVAILABLE,'h'=>array(EST_PREF_DEFHRSHINT0,EST_PREF_DEFHRSHINT1)));
        $text = $estateCore->getCalTbl('start');
        $text .= $estateCore->getCalTbl('head');
        $text .= '<tbody>';
        $text .= $estateCore->getCalTbl('tr',$dta);
        $text .= '</tbody>';
        $text .= $estateCore->getCalTbl('end');
        return $text;
        break;
			case 'read': 
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  public function sched_evt_lengths($curVal,$mode){
    switch($mode){
			case 'write':
        //$dta = array('defevtlen'=>array('n'=>'sched_evt_lengths','v'=>$curVal,'h'=>array(EST_PREF_DEFHRSHINT0,EST_PREF_DEFHRSHINT1)));
        if(!$curVal || count($curVal) == 0){
          $curVal = array();
          if(count($GLOBALS['EST_EVENTS']) > 0){foreach($GLOBALS['EST_EVENTS'] as $k=>$v){$curVal[$k] = $v['t'];}}
          }
        
        $text = '
        <table class="table adminform">
          <colgroup></colgroup>
          <colgroup></colgroup>
          <colgroup></colgroup>
          <thead>
          <tr>
            <th>'.EST_PREF_EVENTNAME.'</th>
            <th>'.EST_PREF_EVENTLEN.'</th>
            <th>'.LAN_OPTIONS.'</th>
          </tr>
          </thead>
          <tbody>';
        
        $text .= '<tbody>';
        if(count($GLOBALS['EST_EVENTS']) > 0){
          foreach($GLOBALS['EST_EVENTS'] as $k=>$v){
            $text .= '<tr>';
            $text .= '<td>'.$v['l'].'</td>';
            $text .= '<td>';
            //$text .= '<input type="time" name="sched_evt_lengths['.$k.']" class="estPrefInptTime" value="'.$curVal[$k].'"/>';
            $text .= '<select name="sched_evt_lengths['.$k.']" class="form-control input-large ui-state-valid">';
            for($i = 0.25; $i <= 12; $i = $i + 0.25){
              $text .= '<option'.($i == $curVal[$k] ? ' selected="selected"' : '').'>'.gmdate('H:i', floor($i * 3600)).'</option>';
              }
            $text .= '</select>';
            
            $text .= '</td>';
            $text .= '<td>'.gmdate('H:i', floor($curVal[$k] * 3600)).'</td>';
            $text .= '</tr>';
            }
          }
        $text .= '</tbody></table>';
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
        return '<div id="propBathsCont" class="estInptCont"><div class="ILBLK">'.EST_GEN_MAINLEV.'<input type="number" name="prop_bathmain" value="'.intval($curVal).'" min="0" step="1" id="prop-bathmain" class="tbox number e-spinner input-small form-control ui-state-valid" pattern="^[0-9]*" data-original-title="" title=""></div></div>';
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
        return '<div class="estSpaceTRrem"></div><div class="ILMINI">'.EST_GEN_FULL.'<input type="number" name="prop_bathfull" value="'.intval($curVal).'" min="0" step="1" id="prop-bathfull" class="tbox number e-spinner input-small form-control ui-state-valid " pattern="^[0-9]*" data-original-title="" title=""></div>';
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
        return '<div class="estSpaceTRrem"></div><div class="ILMINI">'.EST_GEN_HALF.'<input type="number" name="prop_bathhalf" value="'.intval($curVal).'" min="0" step="1" id="prop-bathhalf" class="tbox number e-spinner input-small form-control ui-state-valid " pattern="^[0-9]*" data-original-title="" title=""></div>';
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
        return '<div class="estSpaceTRrem"></div><div class="ILMINI">'.EST_GEN_TOTAL.'<input type="number" name="prop_bathtot" value="'.intval($curVal).'" min="0" step="1" id="prop-bathtot" class="tbox number e-spinner input-small form-control ui-state-valid " pattern="^[0-9]*" data-original-title="" title=""></div>';
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
        return '<div id="propBedsCont" class="estInptCont"><div class="ILMINI">'.EST_GEN_MAINLEV.'<input type="number" name="prop_bedmain" value="'.intval($curVal).'" min="0" step="1" id="prop-bedmain" class="tbox number e-spinner input-small form-control ui-state-valid" pattern="^[0-9]*" data-original-title="" title=""></div></div>';
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
        return '<div class="estSpaceTRrem"></div><div class="ILMINI">'.EST_GEN_TOTAL.'<input type="number" name="prop_bedtot" value="'.intval($curVal).'" min="0" step="1" id="prop-bedtot" class="tbox number e-spinner input-small form-control ui-state-valid " pattern="^[0-9]*" data-original-title="" title=""></div>';
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
        return '<div id="propUnitCont" class="estInptCont"><div class="ILMINI">'.EST_GEN_FLOORNO.'<input type="number" name="prop_floorno" value="'.intval($curVal).'" min="0" step="1" id="prop-floorno" class="tbox number e-spinner input-small form-control ui-state-valid " pattern="^[0-9]*" data-original-title="" title=""></div></div>';
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
        return '<div class="estSpaceTRrem"></div><div class="ILMINI">'.EST_GEN_UNITSBLDG.'<input type="number" name="prop_bldguc" value="'.intval($curVal).'" min="0" step="1" id="prop-bldguc" class="tbox number e-spinner input-small form-control ui-state-valid " pattern="^[0-9]*" data-original-title="" title=""></div>';
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
        return '<div class="estSpaceTRrem"></div><div class="ILMINI">'.EST_GEN_UNITSCOMPLX.'<input type="number" name="prop_complxuc" value="'.intval($curVal).'" min="0" step="1" id="prop-complxuc" class="tbox number e-spinner input-small form-control ui-state-valid " pattern="^[0-9]*" data-original-title="" title=""></div>';
        break;
			case 'filter':
			case 'batch':
				break;
      }
    }
    
  
  public function prop_spaces($curVal,$mode){
    $DIMU = explode(',',$GLOBALS['EST_DIMUNITS'][0]);
    $tp = e107::getParser();
    switch($mode){
			case 'read': // List Page
        return $curVal;
				break;

			case 'write': // Edit Page
        $sql = e107::getDB();
        $frm = e107::getForm();
        $LEVDTA = array('dimu'=>$DIMU,'lev'=>1);
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
  
  
  public function layout_preview_listpage($curVal,$mode){
    switch($mode){
			case 'read':
				break;
			case 'write': 
        return '<iframe id="estLayoutPreviewIframeList" class="estLayoutPreviewCont"></iframe>';
        break;
      }
    }
  
  public function layout_preview_top($curVal,$mode){
    switch($mode){
			case 'read':
				break;
			case 'write': 
        return '<iframe id="estLayoutPreviewIframe-top" scrolling="no" class="estLayoutPreviewCont"></iframe>';
        break;
      }
    }
  
  public function layout_preview_summary($curVal,$mode){
    switch($mode){
			case 'read':
				break;
			case 'write': 
        return '<iframe id="estLayoutPreviewIframe-sum" scrolling="no" class="estLayoutPreviewCont"></iframe>';
        break;
      }
    }
  
  public function layout_preview_spaces($curVal,$mode){
    switch($mode){
			case 'read':
				break;
			case 'write': 
        return '<iframe id="estLayoutPreviewIframe-spaces" scrolling="no" class="estLayoutPreviewCont"></iframe>';
        break;
      }
    }
  
  
  public function layout_preview_mapview($curVal,$mode){
    switch($mode){
			case 'read':
				break;
			case 'write': 
        return '<iframe id="estLayoutPreviewIframe-map" scrolling="no" class="estLayoutPreviewCont"></iframe>';
        break;
      }
    }
  
  public function layout_preview_gallery($curVal,$mode){
    switch($mode){
			case 'read':
				break;
			case 'write': 
        return '<iframe id="estLayoutPreviewIframe-gal" class="estLayoutPreviewCont"></iframe>';
        break;
      }
    }
  
  public function layout_preview($curVal,$mode){
    switch($mode){
			case 'read':
				break;
			case 'write': 
        $tp = e107::getParser();
        $sql = e107::getDB();
        $pOpt = array();//'<option value="0">'.EST_GEN_LISTINGS.'</option>';
        $retrn = '<select id="estPrefPropSel">';
        
        $PVQRY = 'SELECT prop_idx, prop_name FROM #estate_properties ORDER BY prop_datecreated DESC LIMIT 5';
        /*
        $PVQRY = 'SELECT prop_idx, prop_name, space_idx, space_propidx
        FROM #estate_spaces
        LEFT JOIN #estate_properties
        ON prop_idx = space_propidx
        GROUP BY space_propidx
        ORDER BY prop_datecreated DESC LIMIT 5';
        */
        
        if($data = $sql->retrieve($PVQRY,true)){
          if(count($data) > 0){foreach($data as $row){$pOpt[$row['prop_idx']] = $row['prop_name'];}}
          }
        if(count($pOpt) > 0){foreach($pOpt as $k=>$v){$retrn .= '<option value="'.intval($k).'">'.$tp->toHTML($v).'</option>';}}
        else{$retrn .= '<option value="-1">'.EST_PREF_LAYOUT_NOLISTINGS.'</option>';}
        
        $retrn .= '</select>';
        $retrn .= '<select id="estPrefPropOpt1"><option value="1">Desktop</option><option value="2">Mobile</option></select>';
        return $retrn;
        break;
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  
  public function view_template($curVal,$mode){
    switch($mode){
			case 'read':
				break;
			case 'write': 
        $retrn = '<select id="estPrefTemplateSel">';
        $retrn .= '<option value="default">Default</option>';
        $retrn .= '</select>';
        return $retrn;
        break;
			case 'filter':
			case 'batch':
				break;
      }
    }
  
  
  }
