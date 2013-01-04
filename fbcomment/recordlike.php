<?php
/**
* recordlike.php - ajax called tracker logging
*
* @copyright  Copyright © 2012 geekwright, LLC. All rights reserved. 
* @license    fbcomment/docs/license.txt  GNU General Public License (GPL)
* @since      1.0
* @author     Richard Griffith <richard@geekwright.com>
* @package    fbcomment
* @version    $Id$
*/
include '../../mainfile.php';
// include(XOOPS_ROOT_PATH."/header.php");
$xoopsLogger->activated = false;
if(!empty($_SERVER['HTTP_X_OGURL'])) {

	$url = urldecode ($_SERVER['HTTP_X_OGURL']);

	$q_url=mysql_real_escape_string($url);

	$sql = "UPDATE ".$xoopsDB->prefix('fbc_like_tracker').
	    " set count = count + 1, lastlike = UNIX_TIMESTAMP() " .
	    " where url='{$q_url}' ";
	
	$result = $xoopsDB->queryF($sql);
	if ($result && !$xoopsDB->getAffectedRows()) { // database is OK but nothing to update
		$sql = "insert into ".$xoopsDB->prefix('fbc_like_tracker').
		    " (url, count, lastlike, firstlike) " .
		    " values('{$q_url}',1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP() )";
		$result = $xoopsDB->queryF($sql);
	}
	$extra_tags['OG_URL']=$url;
	$notification_handler =& xoops_gethandler('notification'); 
        $notification_handler->triggerEvent ('global', 0, 'new_like', $extra_tags);
}

// include(XOOPS_ROOT_PATH."/footer.php");
?>