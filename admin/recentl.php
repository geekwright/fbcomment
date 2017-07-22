<?php
/**
 * admin/recentl.php - admin area recent likes tracker
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
} else {
    adminmenu(4);
}

$rows = getrecent('like');

echo '<table width="100%" border="0" cellspacing="1" class="outer">';
echo '<tr><th>' . _AD_FBCOM_RECENT_URL . '</th><th>' . _AD_FBCOM_RECENT_COUNT . '</th><th>' . _AD_FBCOM_RECENT_LASTDATE . '</th></tr>';
if ($rows) {
    foreach ($rows as $row) {
        $date = formatTimestamp($row['lastlike']);
        echo "<tr><td><a href=\"{$row['url']}\">{$row['url']}</a></td><td>{$row['count']}</td><td>{$date}</td></tr>";
    }
} else {
    echo '<tr><td colspan="3">' . _AD_FBCOM_RECENT_EMPTY . '</td></tr>';
}
echo '</table>';
echo '<br><a href="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/notify.php">' . _AM_FBCOM_ADMENU_NOTIFY . '</a><b>';

include __DIR__ . '/footer.php';
