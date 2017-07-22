<?php
/**
 * install.php - initializations on module installation
 *
 * @copyright  Copyright © 2012 geekwright, LLC. All rights reserved.
 * @license    fbcomment/docs/license.txt  GNU General Public License (GPL)
 * @since      1.0
 * @author     Richard Griffith <richard@geekwright.com>
 * @package    fbcomment
 */

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

/**
 * @param $module
 *
 * @return bool
 */
function xoops_module_install_fbcomment(XoopsModule $module)
{
    // global $xoopsDB,$xoopsConfig;

    // make upload directory
    mkdir(XOOPS_ROOT_PATH . '/uploads/fbcomment');

    return true;
}
