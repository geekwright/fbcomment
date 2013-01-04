<?php
/**
* admin/help.php - admin help for non Xoops 2.5+ systems
*
* @copyright  Copyright © 2012 geekwright, LLC. All rights reserved. 
* @license    fbcomment/docs/license.txt  GNU General Public License (GPL)
* @since      1.0
* @author     Richard Griffith <richard@geekwright.com>
* @package    fbcomment
* @version    $Id$
*/

include 'header.php';

if(!$xoop25plus) adminmenu(0);

$help = XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar("dirname") . '/language/' . $xoopsConfig['language'] . '/help/help.html';
if(!file_exists($help)) $help = XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar("dirname") . "/language/english/help/help.html" ;

echo '<table width="100%" border="0" cellspacing="1" class="outer">';
echo '<tr><th>'._AM_FBCOM_ADMENU_HELP.'</th></tr><tr><td width="100%" >';
$helptext=utf8_encode(implode("\n", file($help)));
$helptext=str_replace ( '<{$xoops_url}>', XOOPS_URL, $helptext);
echo $helptext.'<br /></td></tr></table>';

include "footer.php";
?>