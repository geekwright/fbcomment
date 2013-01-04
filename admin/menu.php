<?php
/**
* admin/menu.php - admin area menu definitions
*
* @copyright  Copyright © 2012 geekwright, LLC. All rights reserved. 
* @license    fbcomment/docs/license.txt  GNU General Public License (GPL)
* @since      1.0
* @author     Richard Griffith <richard@geekwright.com>
* @package    fbcomment
* @version    $Id$
*/
$adminmenu[1] = array(
	'title'	=> _MI_FBCOM_ADMENU ,
	'link'	=> 'admin/index.php' ,
	'icon'	=> 'images/admin/home.png'
) ;

$adminmenu[] = array(
	'title'	=> _MI_FBCOM_ABOUT ,
	'link'	=> 'admin/about.php' ,
	'icon'	=> 'images/admin/about.png'
) ;

$adminmenu[] = array(
	'title'	=> _MI_FBCOM_RECENT_COMMENTS ,
	'link'	=> 'admin/recent.php' ,
	'icon'	=> 'images/admin/comment.png'
) ;

$adminmenu[] = array(
	'title'	=> _MI_FBCOM_RECENT_LIKES ,
	'link'	=> 'admin/recentl.php' ,
	'icon'	=> 'images/admin/like.png'
) ;

?>