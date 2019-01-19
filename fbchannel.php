<?php
/**
 * fbchannel.php - Facebook recommended optimization channel file
 *
 * @copyright  Copyright © 2012 geekwright, LLC. All rights reserved.
 * @license    fbcomment/docs/license.txt  GNU General Public License (GPL)
 * @since      1.0
 * @author     Richard Griffith <richard@geekwright.com>
 * @package    fbcomment
 */

$cache_expire = 60 * 60 * 24 * 365;
header('Pragma: public');
header('Cache-Control: maxage=' . $cache_expire);
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $cache_expire) . ' GMT');

// passing locale should achieve both long cache and localization
$locale = empty($_GET['locale']) ? 'en_US' : $_GET['locale'];
echo '<script src="//connect.facebook.net/' . $locale . '/all.js"></script>';
