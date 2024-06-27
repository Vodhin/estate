<?php
if (!defined('e107_INIT')) { exit; }


class estate_dashboard // include plugin-folder in the name.
{
	
	function chart(){
		return false;
	}
	
	
	function status(){
		return false;
    }	
	
	
	function latest(){
    if(EST_USERPERM == 0){return false;}
    $i = 0;
    $var[$i]['icon'] 	= '<i class="fas fa-envelope"></i>';
		$var[$i]['title'] 	= EST_GEN_ESTATE.' '.EST_GEN_AGENT.' '.EST_MSG_INBOX;
		$var[$i]['url']		= e_PLUGIN."estate/admin_config.php?messages";
		$var[$i]['total'] 	= EST_MGS_NEWMSGS;
    $i++;
    
  	if(EST_USERPERM >= e107::pref('estate','public_mod')){
      $var[$i]['icon'] 	= '<i class="fas fa-house"></i>';
  		$var[$i]['title'] 	= defset('EST_GEN_SUBMITTEDLIST');
  		$var[$i]['url']		= e_PLUGIN."estate/admin_config.php";
  		$var[$i]['total'] 	= EST_NEW_PROPSUBMITTED;
      $i++;
      }
    
    $AID = defset(EST_AGENTID,-1);
    $lkct = e107::getDb()->count("estate_likes","('like_aid')","WHERE like_aid='".EST_AGENTID."'");
    $var[$i]['icon'] 	= '<i class="fas fa-heart"></i>';
		$var[$i]['title'] 	= defset('EST_GEN_ESTATE').' '.defset('EST_GEN_SAVES');
		$var[$i]['url']		= e_PLUGIN."estate/admin_config.php";
		$var[$i]['total'] 	= $lkct;
    unset($lkct,$i);
    return $var;
    }
  }
