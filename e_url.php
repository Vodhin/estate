<?php
/*
 * e107 Bootstrap CMS
 *
 * Copyright (C) 2008-2015 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 * 
 * IMPORTANT: Make sure the redirect script uses the following code to load class2.php: 
 * 
 * 	if (!defined('e107_INIT'))
 * 	{
 * 		require_once(__DIR__.'/../../class2.php');
 * 	}
 * 
 */
 
if (!defined('e107_INIT')) { exit; }

// v2.x Standard  - Simple mod-rewrite module. 

class estate_url // plugin-folder + '_url'
{
	function config() 
	{
		$config = array();

    /*
		$config['property'] = array(
			'alias'			=> EST_SEF_PROPERTY,
			'regex'			=> '^{alias}/{prop_sef}?$',
			'sef'			=> '{prop_sef}',
			'redirect'		=> '{e_PLUGIN}estate/listings.php?view.$2'
		);
    
    
		$config['topic'] = array(
			'regex'         => 'forum\/([^\/]*)\/([\d]*)(?:\/|-)([\w-]*)/?\??(.*)',
			'sef'			=> 'forum/{forum_sef}/{thread_id}/{thread_sef}/',
			'redirect'		=> '{e_PLUGIN}estate/listings.php?view=$2'
		);
    */
    
		$config['listings'] = array(
			'alias'			=> EST_SEF_LISTINGS,
			'regex'			=> '^{alias}/?$',
			'sef'			=> '{alias}',
			'redirect'		=> '{e_PLUGIN}estate/listings.php',
		);

		return $config;
	}
	

	
}