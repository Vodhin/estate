<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2013 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */

if (!defined('e107_INIT')) { exit; }

// Usage: sublink_type[x]['title'].
//  x should be the same as the plugin folder.

e107::lan('estate', "admin", true);

$sublink_type['estate']['title'] = EST_GEN_LISTINGS;
$sublink_type['estate']['table'] = 'estate_properties';
$sublink_type['estate']['query'] = "prop_idx !='0' ORDER BY prop_datecreated DESC";
$sublink_type['estate']['url'] = "{e_PLUGIN}estate/listings.php?#";
$sublink_type['estate']['fieldid'] = 'prop_idx';
$sublink_type['estate']['fieldname'] = 'prop_name';
$sublink_type['estate']['fielddiz'] = 'prop_summary';
$sublink_type['estate']['sef'] = 'estate/listings';


