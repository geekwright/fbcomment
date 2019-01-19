<?php
/**
 * admin/about.php
 *
 * @copyright  Copyright © 2012 geekwright, LLC. All rights reserved.
 * @license    fbcomment/docs/license.txt  GNU General Public License (GPL)
 * @since      1.0
 * @author     Richard Griffith <richard@geekwright.com>
 * @package    fbcomment
 */

include __DIR__ . '/header.php';
if ($xoop25plus) {
    $adminObject->displayNavigation(basename(__FILE__));
    $adminObject->displayAbout('', false);
} else { // !$xoop25plus
    adminmenu(2);

    echo '<table width="100%" border="0" cellspacing="1" class="outer">';
    echo '<tr><th>' . _AM_FBCOM_ADMENU_ABOUT . '</th></tr><tr><td width="100%" >';
    echo '<div class="center;"><br><b>' . _AM_FBCOM_ADMENU_DESC . '</b></div><br>';
    echo '<div class="center;">Brought to you by <a href="http://www.geekwright.com/" target="_blank">geekwright, LLC</a></div><br>';
    echo '</td></tr>';
    echo '</table>';
}

include __DIR__ . '/footer.php';
