<?php
/*
 * e107 website system
 *
 * Copyright (C) e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * Estate SQL
 * `subdivcat_lev` tinyint(1) unsigned NOT NULL,
 *
*/
header("location:../../index.php");
exit;
?>

CREATE TABLE `estate_agencies` (
  `agency_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `agency_pub` tinyint(1) NOT NULL default '1',
  `agency_name` varchar(100) NOT NULL,
  `agency_image` varchar(100) NOT NULL default '',
  `agency_imgsrc` tinyint(1) unsigned NOT NULL,
  `agency_addr` text NOT NULL,
  `agency_lat` varchar(21) NOT NULL,
  `agency_lon` varchar(21) NOT NULL,
  `agency_geoarea` varchar(20) NOT NULL,
  `agency_zoom` tinyint(2) unsigned NOT NULL,
  `agency_timezone` varchar(100) NOT NULL,
  `agency_txt1` text NOT NULL,
  PRIMARY KEY (`agency_idx`)
) ENGINE=InnoDB;

CREATE TABLE `estate_agents` (
  `agent_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `agent_agcy` int(10) unsigned NOT NULL,
  `agent_name` varchar(100) NOT NULL,
  `agent_lev` tinyint(1) unsigned NOT NULL,
  `agent_uid` int(10) unsigned NOT NULL,
  `agent_image` varchar(100) NOT NULL default '',
  `agent_imgsrc` tinyint(1) unsigned NOT NULL,
  `agent_txt1` text NOT NULL,
  PRIMARY KEY (`agent_idx`)
) ENGINE=InnoDB;

CREATE TABLE `estate_city` (
  `city_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `city_county` int(11) unsigned NOT NULL,
  `city_name` varchar(100) NOT NULL,
  `city_zip` text NOT NULL,
  `city_timezone` varchar(100) NOT NULL,
  `city_url` varchar(100) NOT NULL,
  `city_description` text NOT NULL,
  PRIMARY KEY (`city_idx`)
) ENGINE=InnoDB;

CREATE TABLE `estate_clients` (
  `client_idx` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `client_name` varchar(100) NOT NULL,
  `client_info` text NOT NULL,
  PRIMARY KEY (`client_idx`)
) ENGINE=InnoDB;

CREATE TABLE `estate_client_bridge` (
  `clientbr_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `clientbr_clientidx` int(11) unsigned NOT NULL,
  `clientbr_lev` tinyint(1) unsigned NOT NULL,
  `clientbr_levidx` int(11) unsigned NOT NULL,
  PRIMARY KEY (`clientbr_idx`)
) ENGINE=InnoDB;

CREATE TABLE `estate_contacts` (
  `contact_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contact_tabkey` tinyint(1) unsigned NOT NULL,
  `contact_tabidx` int(10) unsigned NOT NULL,
  `contact_key` varchar(25) NOT NULL,
  `contact_ord` tinyint(1) unsigned NOT NULL,
  `contact_data` varchar(100) NOT NULL,
  PRIMARY KEY (`contact_idx`)
) ENGINE=InnoDB;

CREATE TABLE `estate_county` (
  `cnty_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cnty_name` varchar(100) NOT NULL,
  `cnty_state` int(10) unsigned NOT NULL,
  `cnty_url` varchar(100) NOT NULL,
  PRIMARY KEY (`cnty_idx`)
) ENGINE=InnoDB;


CREATE TABLE `estate_curcodes` (
  `curcode_init` varchar(5) NOT NULL,
  `curcode_name` varchar(50) NOT NULL,
  PRIMARY KEY (`curcode_init`)
) ENGINE=InnoDB;

CREATE TABLE `estate_events` (
  `event_idx` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `event_agt` int(11) unsigned NOT NULL,
  `event_type` tinyint(1) unsigned NOT NULL,
  `event_stat` tinyint(1) unsigned NOT NULL,
  `event_class` tinyint(1) unsigned NOT NULL,
  `event_propidx` int(11) unsigned NOT NULL,
  `event_name` varchar(75) NOT NULL,
  `event_start` int(10) unsigned NOT NULL default '0',
  `event_end` int(10) unsigned NOT NULL default '0',
  `event_timezone` varchar(100) NOT NULL,
  `event_text` text NOT NULL,
  PRIMARY KEY (`event_idx`)
) ENGINE=InnoDB;

CREATE TABLE `estate_featcats` (
  `featcat_idx` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `featcat_zone` int(10) unsigned NOT NULL,
  `featcat_lev` tinyint(1) unsigned NOT NULL,
  `featcat_name` varchar(50) NOT NULL,
  PRIMARY KEY (`featcat_idx`)
) ENGINE=InnoDB;

CREATE TABLE `estate_featurelist` (
  `featurelist_idx` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `featurelist_propidx` int(11) unsigned NOT NULL,
  `featurelist_lev` tinyint(1) unsigned NOT NULL,
  `featurelist_levidx` int(11) unsigned NOT NULL,
  `featurelist_key` int(11) unsigned NOT NULL,
  `featurelist_dta` varchar(75) NOT NULL,
  PRIMARY KEY (`featurelist_idx`)
) ENGINE=InnoDB;

CREATE TABLE `estate_features` (
  `feature_idx` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `feature_ele` tinyint(1) unsigned NOT NULL,
  `feature_cat` int(11) unsigned NOT NULL,
  `feature_name` varchar(50) NOT NULL,
  `feature_opts` text NOT NULL,
  PRIMARY KEY (`feature_idx`)
) ENGINE=InnoDB;

CREATE TABLE `estate_group` (
  `group_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_zone` int(10) unsigned NOT NULL,
  `group_lev` tinyint(1) unsigned NOT NULL,
  `group_name` varchar(25) NOT NULL,
  PRIMARY KEY (`group_idx`)
) ENGINE=InnoDB;

CREATE TABLE `estate_grouplist` (
  `grouplist_idx` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `grouplist_propidx` int(11) unsigned NOT NULL,
  `grouplist_groupidx` int(10) unsigned NOT NULL,
  `grouplist_ord` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`grouplist_idx`)
) ENGINE=InnoDB;


CREATE TABLE `estate_likes` (
  `like_idx` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `like_pid` int(11) unsigned NOT NULL default '0',
  `like_aid` int(10) unsigned NOT NULL default '0',
  `like_uid` int(10) unsigned NOT NULL default '0',
  `like_ip` varchar(55) NOT NULL default '',
  `like_exp` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY (`like_idx`)
) ENGINE=InnoDB;

CREATE TABLE `estate_locales` (
  `locale_init` varchar(20) NOT NULL,
  `locale_name` varchar(60) NOT NULL,
  PRIMARY KEY (`locale_init`)
) ENGINE=InnoDB;



CREATE TABLE `estate_listypes` (
  `listype_idx` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `listype_zone` smallint(10) unsigned NOT NULL,
  `listype_name` varchar(35) NOT NULL,
  PRIMARY KEY (`listype_idx`)
) ENGINE=InnoDB;

CREATE TABLE `estate_media` (
  `media_idx` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `media_propidx` int(11) unsigned NOT NULL,
  `media_lev` tinyint(1) unsigned NOT NULL,
  `media_levidx` int(11) unsigned NOT NULL,
  `media_levord` tinyint(2) unsigned NOT NULL,
  `media_galord` tinyint(2) unsigned NOT NULL,
  `media_asp` varchar(6) NOT NULL,
  `media_type` tinyint(1) unsigned NOT NULL,
  `media_thm` varchar(55) NOT NULL,
  `media_full` varchar(55) NOT NULL,
  `media_name` varchar(35) NOT NULL,
  PRIMARY KEY (`media_idx`)
) ENGINE=InnoDB;

CREATE TABLE `estate_msg` (
  `msg_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `msg_sent` int(10) unsigned NOT NULL default '0',
  `msg_read` int(10) unsigned NOT NULL default '0',
  `msg_exp` int(10) unsigned NOT NULL default '0',
  `msg_pm` int(10) unsigned NOT NULL default '0',
  `msg_email` tinyint(1) unsigned NOT NULL default '0',
  `msg_to_uid` int(10) unsigned NOT NULL default '0',
  `msg_to_addr` varchar(65) NOT NULL,
  `msg_to_name` varchar(65) NOT NULL,
  `msg_mode` tinyint(1) unsigned NOT NULL default '0',
  `msg_propidx` int(11) unsigned NOT NULL default '0',
  `msg_from_cc` tinyint(1) unsigned NOT NULL default '0',
  `msg_from_uid` int(10) unsigned NOT NULL default '0',
  `msg_from_name` varchar(55) NOT NULL,
  `msg_from_addr` varchar(65) NOT NULL,
  `msg_from_ip` varchar(55) NOT NULL default '',
  `msg_from_phone` varchar(25) NOT NULL,
  `msg_top` varchar(100) NOT NULL,
  `msg_text` text NOT NULL,
  PRIMARY KEY (`msg_idx`)
) ENGINE=InnoDB;

CREATE TABLE `estate_properties` (
  `prop_idx` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `prop_mlsno` varchar(100) NOT NULL,
  `prop_listype` tinyint(1) unsigned NOT NULL,
  `prop_name` varchar(100) NOT NULL,
  `prop_agency` int(10) unsigned NOT NULL,
  `prop_agent` int(10) unsigned NOT NULL,
  `prop_addr_lookup` text NOT NULL,
  `prop_addr1` varchar(75) NOT NULL,
  `prop_addr2` varchar(50) NOT NULL,
  `prop_country` varchar(2) NOT NULL,
  `prop_state` int(10) unsigned NOT NULL,
  `prop_county` int(10) unsigned NOT NULL,
  `prop_city` int(10) unsigned NOT NULL,
  `prop_subdiv` int(10) unsigned NOT NULL,
  `prop_zip` varchar(15) NOT NULL,
  `prop_features` varchar(255) NOT NULL,
  `prop_sef` varchar(100) NOT NULL,
  `prop_timezone` varchar(100) NOT NULL,
  `prop_datecreated` int(10) unsigned NOT NULL default '0',
  `prop_dateupdated` int(10) unsigned NOT NULL default '0',
  `prop_datetease` int(10) unsigned NOT NULL default '0',
  `prop_dateprevw` int(10) unsigned NOT NULL default '0',
  `prop_datelive` int(10) unsigned NOT NULL default '0',
  `prop_datepull` int(10) unsigned NOT NULL default '0',
  `prop_hours` text NOT NULL,
  `prop_uidcreate` int(10) unsigned NOT NULL,
  `prop_uidupdate` int(10) unsigned NOT NULL,
  `prop_status` tinyint(1) unsigned NOT NULL,
  `prop_parcelid` varchar(50) NOT NULL,
  `prop_lotid` varchar(20) NOT NULL,
  `prop_lat` varchar(21) NOT NULL,
  `prop_lon` varchar(21) NOT NULL,
  `prop_zoom` tinyint(2) unsigned NOT NULL,
  `prop_geoarea` varchar(20) NOT NULL,
  `prop_yearbuilt` smallint(4) NOT NULL,
  `prop_dimu1` tinyint(1) unsigned NOT NULL,
  `prop_intsize` int(8) unsigned NOT NULL,
  `prop_roofsize` int(8) unsigned NOT NULL,
  `prop_dimu2` tinyint(1) unsigned NOT NULL,
  `prop_landsize` varchar(10) NOT NULL,
  `prop_zoning` int(10) unsigned NOT NULL,
  `prop_type` smallint(5) unsigned NOT NULL,
  `prop_modelname` varchar(50) NOT NULL,
  `prop_listprice` int(10) unsigned NOT NULL,
  `prop_origprice` int(10) unsigned NOT NULL,
  `prop_locale` varchar(40) NOT NULL,
  `prop_leasefreq` tinyint(1) unsigned NOT NULL,
  `prop_leasedur` tinyint(1) unsigned NOT NULL,
  `prop_currency` tinyint(1) unsigned NOT NULL,
  `prop_landfee` decimal(10,2) unsigned NOT NULL,
  `prop_landfreq` tinyint(1) unsigned NOT NULL,
  `prop_thmb` varchar(55) NOT NULL,
  `prop_summary` varchar(255) NOT NULL,
  `prop_description` text NOT NULL,
  `prop_hoafee` decimal(6,2) unsigned NOT NULL,
  `prop_hoaland` tinyint(1) unsigned NOT NULL,
  `prop_hoaappr` tinyint(1) unsigned NOT NULL,
  `prop_hoareq` tinyint(1) unsigned NOT NULL,
  `prop_hoafrq` tinyint(1) unsigned NOT NULL,
  `prop_bathtot` tinyint(1) unsigned NOT NULL,
  `prop_bathmain` tinyint(1) unsigned NOT NULL,
  `prop_bathhalf` tinyint(1) unsigned NOT NULL,
  `prop_bathfull` tinyint(1) unsigned NOT NULL,
  `prop_bedtot` tinyint(1) unsigned NOT NULL,
  `prop_bedmain` tinyint(1) unsigned NOT NULL,
  `prop_floorct` tinyint(1) unsigned NOT NULL,
  `prop_floorno` tinyint(1) unsigned NOT NULL,
  `prop_bldguc` tinyint(1) unsigned NOT NULL,
  `prop_complxuc` smallint(1) unsigned NOT NULL,
  `prop_condit` varchar(55) NOT NULL,
  `prop_flag` varchar(35) NOT NULL,
  `prop_views` int(8) unsigned NOT NULL default '0',
  `prop_saves` int(8) unsigned NOT NULL default '0',
  `prop_appr` int(10) unsigned NOT NULL default '1',
  `prop_template_view` varchar(55) NOT NULL,
  `prop_template_view_ord` text NOT NULL,
  `prop_template_menu` varchar(55) NOT NULL,
  `prop_template_menu_ord` text NOT NULL,
  PRIMARY KEY (`prop_idx`)
) ENGINE=InnoDB;


CREATE TABLE `estate_prophist` (
  `prophist_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prophist_propidx` int(11) unsigned NOT NULL,
  `prophist_date` int(10) unsigned NOT NULL default '0',
  `prophist_price` int(10) unsigned NOT NULL default '0',
  `prophist_status` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY (`prophist_idx`)
) ENGINE=InnoDB;

CREATE TABLE `estate_spaces` (
  `space_idx` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `space_lev` tinyint(1) unsigned NOT NULL default '1',
  `space_levidx` int(11) unsigned NOT NULL,
  `space_grpid` int(11) unsigned NOT NULL,
  `space_catid` int(11) unsigned NOT NULL,
  `space_ord` tinyint(1) unsigned NOT NULL,
  `space_name` varchar(55) NOT NULL,
  `space_loc` varchar(25) NOT NULL,
  `space_dimu` tinyint(1) unsigned NOT NULL,
  `space_dimx` int(6) unsigned NOT NULL,
  `space_dimy` int(6) unsigned NOT NULL,
  `space_dimxy` int(8) unsigned NOT NULL,
  `space_description` text NOT NULL,
  PRIMARY KEY (`space_idx`)
) ENGINE=InnoDB;

CREATE TABLE `estate_states` (
  `state_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `state_name` varchar(100) NOT NULL,
  `state_init` varchar(2) NOT NULL,
  `state_country` varchar(2) NOT NULL,
  `state_url` varchar(100) NOT NULL,
  PRIMARY KEY (`state_idx`)
) ENGINE=InnoDB;

CREATE TABLE `estate_subdiv` (
  `subd_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subd_city` int(10) unsigned NOT NULL,
  `subd_name` varchar(100) NOT NULL,
  `subd_type` tinyint(1) unsigned NOT NULL default '2',
  `subd_url` varchar(100) NOT NULL,
  `subd_hoaname` varchar(100) NOT NULL,
  `subd_hoaweb` varchar(100) NOT NULL,
  `subd_hoareq` tinyint(1) unsigned NOT NULL,
  `subd_hoafee` int(6) unsigned NOT NULL,
  `subd_hoafrq` tinyint(1) unsigned NOT NULL,
  `subd_hoaappr` tinyint(1) unsigned NOT NULL,
  `subd_hoaland` tinyint(1) unsigned NOT NULL,
  `subd_landfee` decimal(10,2) unsigned NOT NULL,
  `subd_landfreq` tinyint(1) unsigned NOT NULL,
  `subd_description` text NOT NULL,
  PRIMARY KEY (`subd_idx`)
) ENGINE=InnoDB;


CREATE TABLE `estate_subdiv_spaces` (
  `space_idx` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `space_lev` tinyint(1) unsigned NOT NULL default '4',
  `space_levidx` int(11) unsigned NOT NULL,
  `space_grpid` int(11) unsigned NOT NULL,
  `space_catid` int(11) unsigned NOT NULL,
  `space_ord` tinyint(1) unsigned NOT NULL,
  `space_name` varchar(55) NOT NULL,
  `space_description` text NOT NULL,
  PRIMARY KEY (`space_idx`)
) ENGINE=InnoDB;



CREATE TABLE `estate_user` (
  `user_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(25) NOT NULL,
  PRIMARY KEY (`user_idx`)
) ENGINE=InnoDB;

CREATE TABLE `estate_zoning` (
  `zoning_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `zoning_name` varchar(25) NOT NULL,
  PRIMARY KEY (`zoning_idx`)
) ENGINE=InnoDB;
