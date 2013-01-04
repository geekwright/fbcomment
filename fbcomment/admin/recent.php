<?php
/**
* admin/recent.php - admin area recent comments tracker
*
* @copyright  Copyright © 2012 geekwright, LLC. All rights reserved. 
* @license    fbcomment/docs/license.txt  GNU General Public License (GPL)
* @since      1.0
* @author     Richard Griffith <richard@geekwright.com>
* @package    fbcomment
* @version    $Id$
*/
include 'header.php';

if($xoop25plus) echo $moduleAdmin->addNavigation('recent.php');
else adminmenu(3);

	$rows=getrecent('comment');

	echo '<table width="100%" border="0" cellspacing="1" class="outer">';
	echo '<tr><th>'._AD_FBCOM_RECENT_URL.'</th><th>'._AD_FBCOM_RECENT_COUNT.'</th><th>'._AD_FBCOM_RECENT_LASTDATE.'</th></tr>';
	if($rows) {
		foreach ($rows as $row) {
			$date=formatTimeStamp($row['lastcomment']);
			echo "<tr><td><a href=\"{$row['url']}\">{$row['url']}</a></td><td>{$row['count']}</td><td>{$date}</td></tr>";
		}
	} else echo '<tr><td colspan="3">'._AD_FBCOM_RECENT_EMPTY.'</td></tr>';
	echo '</table>';
	echo '<br /><a href="' . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/notify.php">' . _AM_FBCOM_ADMENU_NOTIFY . '</a><br/>';

	include 'footer.php'
?>
