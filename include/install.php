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
    $folder = XOOPS_ROOT_PATH . '/uploads/fbcomment';
    try {
        if (!file_exists($folder)) {
            if (!mkdir($folder) && !is_dir($folder)) {
                throw new \RuntimeException(sprintf('Unable to create the %s directory', $folder));
            } else {
                file_put_contents($folder . '/index.html', '<script>history.go(-1);</script>');
            }
        }
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n", '<br/>';
    }

    return true;
}
