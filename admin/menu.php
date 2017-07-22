<?php
/**
 * admin/menu.php - admin area menu definitions
 *
 * @copyright  Copyright © 2012 geekwright, LLC. All rights reserved.
 * @license    fbcomment/docs/license.txt  GNU General Public License (GPL)
 * @since      1.0
 * @author     Richard Griffith <richard@geekwright.com>
 * @package    fbcomment
 */

$moduleDirName = basename(dirname(__DIR__));

if (false !== ($moduleHelper = Xmf\Module\Helper::getHelper($moduleDirName))) {
} else {
    $moduleHelper = Xmf\Module\Helper::getHelper('system');
}
$adminObject = \Xmf\Module\Admin::getInstance();

$pathIcon32 = \Xmf\Module\Admin::menuIconPath('');
//$pathModIcon32 = $moduleHelper->getModule()->getInfo('modicons32');

$moduleHelper->loadLanguage('modinfo');

$adminmenu[1] = array(
    'title' => _MI_FBCOM_ADMENU,
    'link'  => 'admin/index.php',
    'icon'  => 'assets/images/admin/home.png'
);

$adminmenu[] = array(
    'title' => _MI_FBCOM_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => 'assets/images/admin/about.png'
);

$adminmenu[] = array(
    'title' => _MI_FBCOM_RECENT_COMMENTS,
    'link'  => 'admin/recent.php',
    'icon'  => 'assets/images/admin/comment.png'
);

$adminmenu[] = array(
    'title' => _MI_FBCOM_RECENT_LIKES,
    'link'  => 'admin/recentl.php',
    'icon'  => 'assets/images/admin/like.png'
);
