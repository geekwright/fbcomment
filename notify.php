<?php
/**
 * notify.php - a page where notifications can hang
 *
 * @copyright  Copyright © 2012 geekwright, LLC. All rights reserved.
 * @license    fbcomment/docs/license.txt  GNU General Public License (GPL)
 * @since      1.0
 * @author     Richard Griffith <richard@geekwright.com>
 * @package    fbcomment
 */
include __DIR__ . '/../../mainfile.php';
$GLOBALS['xoopsOption']['template_main'] = 'fbcomment_notify.tpl';
include XOOPS_ROOT_PATH . '/header.php';

if (isset($body)) {
    $xoopsTpl->assign('body', $body);
}

include XOOPS_ROOT_PATH . '/footer.php';
