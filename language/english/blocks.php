<?php
// @version    $Id$
if (!defined('XOOPS_ROOT_PATH')) die('Root path not defined');

// used in blocks/blocks.php and passed to fbchannel.php as path to SDK
// see https://developers.facebook.com/docs/internationalization/ for options
define('_MB_FBCOM_SDK_CHANNEL_LOCALE', 'en_US');

define('_MB_FBCOM_HREF_SOURCE' , 'Source of URL sent to Facebook');
define('_MB_FBCOM_MANUAL_HREF' , 'URL to specify');
define('_MB_FBCOM_HERE_PARMS' , 'Variables to include in URL sent separated by comma (i.e. \'post_id,page\')');
define('_MB_FBCOM_REQ_PARMS' , 'Require variable(s) from the list to display block');
define('_MB_FBCOM_WIDTH' , 'Width of Facebook plugin in pixels');
define('_MB_FBCOM_COLOR' , 'Color scheme for plugin');
define('_MB_FBCOM_NUM_POSTS' , 'Number of posts to show');
define('_MB_FBCOM_LIKE_LAYOUT' , 'Layout for Like plugin');
define('_MB_FBCOM_LIKE_ACTION' , 'Like action verb');
define('_MB_FBCOM_LIKE_FACES' , 'Show faces');
define('_MB_FBCOM_LIKE_SEND' , 'Show send button');

define('_MB_FBCOM_YES' , 'Yes');
define('_MB_FBCOM_NO' , 'No');
define('_MB_FBCOM_HREF_AUTO' , 'Automatic');
define('_MB_FBCOM_HREF_SPECIFIC' , 'Manual Entry');
define('_MB_FBCOM_HREF_SITE' , 'System URL');
define('_MB_FBCOM_HREF_FORCE_HTTP' , 'Automatic Force to http://');
define('_MB_FBCOM_LIGHT' , 'Light');
define('_MB_FBCOM_DARK' , 'Dark');
define('_MB_FBCOM_LK_STD' , 'Standard');
define('_MB_FBCOM_LK_BTN' , 'Button Count');
define('_MB_FBCOM_LK_BOX' , 'Box Count');
define('_MB_FBCOM_LK_LIKE' , 'Like');
define('_MB_FBCOM_LK_RCMD' , 'Recommend');

// for Open Graph Metadata editor form
define('_MB_FBCOM_OGEDIT_GRAB', '(Grab)');
define('_MB_FBCOM_OGEDIT_DROPHERE', '(drag and drop new file here)');
define('_MB_FBCOM_OGEDIT_TITLE', 'Open Graph Metadata Editor');
define('_MB_FBCOM_OGEDIT_HIDE_EDITOR', '<img src="' . XOOPS_URL . '/modules/fbcomment/images/ogdone.png" title="Done" alt="(Done)" />');
define('_MB_FBCOM_OGEDIT_TOG_EDITOR', '<img src="' . XOOPS_URL . '/modules/fbcomment/images/ogedit.png" title="Edit OG Metadata" alt="(Edit OG Data)" />');
define('_MB_FBCOM_OGEDIT_OGIMG', 'Page Image');
define('_MB_FBCOM_OGEDIT_FILE', 'File to upload:');
define('_MB_FBCOM_OGEDIT_OGTITLE', 'Title');
define('_MB_FBCOM_OGEDIT_OGDESC', 'Description');
define('_MB_FBCOM_OGEDIT_UPDATE', 'Update Data');
define('_MB_FBCOM_OGEDIT_DEBUGGER', '<img src="' . XOOPS_URL . '/modules/fbcomment/images/ogcheck.png" title="OG Object Debugger" alt="(Debug)" />');

// new in version 1.1

// support for activity feed
define('_MB_FBCOM_ACTIVITY_DOMAIN' , 'Domain for Activity');
define('_MB_FBCOM_ACTIVITY_ACTIONS' , 'Action to include (comma separated list)');
define('_MB_FBCOM_HEIGHT' , 'Height of Facebook plugin in pixels');
define('_MB_FBCOM_SHOW_HEADER' , 'Show Facebook Header');
define('_MB_FBCOM_FONT' , 'Font for plugin display');
define('_MB_FBCOM_SHOW_RECOMMENDATIONS' , 'Always show Recommendations');
define('_MB_FBCOM_ACTIVITY_FILTER' , 'URL filter within domain (i.e. modules/news)');
define('_MB_FBCOM_LINK_TARGET' , 'Link target for in domain links');
define('_MB_FBCOM_REF_LABEL' , 'Referral tracking label (50 char max, alphanumeric and "+/=-.:_")');
define('_MB_FBCOM_ACTIVITY_AGE' , 'Age limit on activity shown in day (0-180, 0=no limit');

// support for post to feed
define('_MB_FBCOM_POST_TO_FEED', 'Post this to Facebook');
define('_MB_FBCOM_POST_OK', 'OK');
define('_MB_FBCOM_POST_ERROR', 'Error');

// support for show feed
define('_MB_FBCOM_SHOW_FEED_ID', 'Feed id to show'); 
define('_MB_FBCOM_SHOW_FEED_MAX', 'Number of posts to show'); 
?>