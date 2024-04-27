<?php

if (!defined('e107_INIT')) { exit; }

e107::lan('estate', true);

class estate_frontpage
{
	function config()
	{
    $config = array();
		$config['title'] = EST_PLUGNAME;
		$config['page'][0] = array('page' => e107::url('estate', 'listings'), 'title' => EST_GEN_LISTINGS); 
		return $config;
	}
}