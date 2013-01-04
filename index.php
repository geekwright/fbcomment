<?php
/**
* index.php - an empty page that should never see much action
*
* @copyright  Copyright © 2012 geekwright, LLC. All rights reserved. 
* @license    fbcomment/docs/license.txt  GNU General Public License (GPL)
* @since      1.0
* @author     Richard Griffith <richard@geekwright.com>
* @package    fbcomment
* @version    $Id$
*/
include '../../mainfile.php';
$GLOBALS['xoopsOption']['template_main'] = 'fbcomment_index.html';
include(XOOPS_ROOT_PATH."/header.php");

if(isset($body)) $xoopsTpl->assign('body', $body);

include(XOOPS_ROOT_PATH."/footer.php");
?>
