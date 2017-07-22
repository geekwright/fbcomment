<?php
/**
 * admin/index.php - admin area home, config checks
 *
 * @copyright  Copyright © 2012 geekwright, LLC. All rights reserved.
 * @license    fbcomment/docs/license.txt  GNU General Public License (GPL)
 * @since      1.0
 * @author     Richard Griffith <richard@geekwright.com>
 * @package    fbcomment
 */
include __DIR__ . '/header.php';

compatabilitytest();

$uploadpath = XOOPS_ROOT_PATH . '/uploads/fbcomment';

if ($xoop25plus) {
    $adminObject->displayNavigation(basename(__FILE__));
    $welcome = _AM_FBCOM_ADMENU_WELCOME;
    $adminObject->addInfoBox($welcome);
    $adminObject->addInfoBoxLine($welcome, _AM_FBCOM_ADMENU_MESSAGE, '', '', 'information');

    if ($xoop25plus) {
        $adminObject->addConfigBoxLine($uploadpath, 'folder');
    }

    if (empty($xoopsModuleConfig['facebook-admins']) && empty($xoopsModuleConfig['facebook-appid'])) {
        $adminObject->addConfigBoxLine('<span style="color:orange"><img src="../assets/images/admin/warn.png" alt="!">' . _AM_FBCOM_WARN_SET_ADMIN . '</span>', 'default');
    }
    if (!$xoopsModuleConfig['use-smarty-for-ogdata']) {
        $adminObject->addConfigBoxLine('<span style="color:orange"><img src="../assets/images/admin/warn.png" alt="!">' . _AM_FBCOM_WARN_USE_SMARTY . '</span>', 'default');
    }
    if (!function_exists('curl_version')) {
        $adminObject->addConfigBoxLine('<span style="color:orange"><img src="../assets/images/admin/warn.png" alt="!">' . _AM_FBCOM_WARN_NO_CURL . '</span>', 'default');
    }
    $adminObject->displayIndex();
} else {
    adminmenu(1);

    echo '<table width="100%" border="0" cellspacing="1" class="outer">';
    echo '<tr><th>' . _AM_FBCOM_ADMENU_WELCOME . '</th></tr>';
    echo '<tr><td width="100%" ><div style="margin:2em;">' . _AM_FBCOM_ADMENU_MESSAGE . '</td></tr>';
    if (empty($xoopsModuleConfig['facebook-admins']) && empty($xoopsModuleConfig['facebook-appid'])) {
        echo '<tr><td><br><br><span style="color:orange; margin:2em;"><img src="../assets/images/admin/warn.png" alt="!">' . _AM_FBCOM_WARN_SET_ADMIN . '</span></td></tr>';
    }
    if (!$xoopsModuleConfig['use-smarty-for-ogdata']) {
        echo '<tr><td><br><br><span style="color:orange; margin:2em;"><img src="../assets/images/admin/warn.png" alt="!">' . _AM_FBCOM_WARN_USE_SMARTY . '</span></td></tr>';
    }
    if (!is_writable($uploadpath)) {
        echo '<tr><td><br><br><span style="color:orange; margin:2em;"><img src="../assets/images/admin/warn.png" alt="!">' . _AM_FBCOM_WARN_NO_UPLOADS . '</span></td></tr>';
    }
    if (!function_exists('curl_version')) {
        echo '<tr><td><br><br><span style="color:orange; margin:2em;"><img src="../assets/images/admin/warn.png" alt="!">' . _AM_FBCOM_WARN_NO_CURL . '</span></td></tr>';
    }
    echo '</table>';
}
include __DIR__ . '/footer.php';
