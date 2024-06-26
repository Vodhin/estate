<?php
/* Estate Inbox Menu
 *
 */

if (!defined('e107_INIT')) { exit; }
if (!e107::isInstalled('estate')) { return; }

if(!defined("EST_RENDERED_INBOX")){
  define("EST_RENDERED_INBOX",true);
  if(EST_USERPERM > 0 || check_class(e107::pref('estate','contact_class'))){
    e107::css('url',e_PLUGIN.'estate/css/msg.css');
    e107::getRender()->setStyle('menu');
    echo estMsgInbox();
    }
  }
?>