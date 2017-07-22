<?php
/**
 * admin/header.php - preamble for all admin pages
 *
 * @copyright  Copyright © 2012 geekwright, LLC. All rights reserved.
 * @license    fbcomment/docs/license.txt  GNU General Public License (GPL)
 * @since      1.0
 * @author     Richard Griffith <richard@geekwright.com>
 * @package    fbcomment
 */

require_once __DIR__ . '/../../../include/cp_header.php';

$xoop25plus = false;
if (is_object($GLOBALS['xoops'])) {
    if (file_exists($GLOBALS['xoops']->path('Frameworks/moduleclasses/moduleadmin/moduleadmin.php'))) {
        require_once $GLOBALS['xoops']->path('Frameworks/moduleclasses/moduleadmin/moduleadmin.php');
        $xoop25plus = true;
    } else {
        $xoop25plus = false;
    }
}

//if ( !@require_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar("dirname") . "/language/" . $xoopsConfig['language'] . "/main.php") {
//    require_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar("dirname") . "/language/english/main.php" ;
//}

if (!defined('_MI_FBCOM_NAME')) { // if modinfo isn't loaded, do it
    if (!@require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/modinfo.php') {
        require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/english/modinfo.php';
    }
}

/**
 * @param int    $currentoption
 * @param string $breadcrumb
 */
function adminmenu($currentoption = 0, $breadcrumb = '')
{
    global $xoopsModule, $xoopsConfig;
    $tblColors = array();
    $tblColors = array_fill(0, 8, '');
    if ($currentoption >= 0) {
        $tblColors[$currentoption] = 'id=\'current\'';
    }

    /* Nice buttons styles */
    $return = "
        <style type='text/css'>

        #admintop { float:left; width:100%; background: #dae0d2; font-size:93%; line-height:normal; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin: 0; }

        #admintabs {
            font-size: 93%; background: url(../assets/images/bg.gif) #dae0d2 repeat-x 50% bottom; float: left; width: 100%; line-height: normal; border-left: 1px solid black; border-right: 1px solid black;
        }
        #admintabs ul {
            padding-right: 10px; padding-left: 10px; padding-bottom: 0px; margin: 0px; padding-top: 10px; list-style-type: none;
        }
        #admintabs li {
            padding-right: 0px; padding-left: 9px; background: url(../assets/images/left.gif) no-repeat left top; float: left; padding-bottom: 0px; margin: 0px; padding-top: 0px; list-style: none;
        }
        #admintabs a {
            padding-right: 15px; display: block; padding-left: 6px; font-weight: bold; background: url(../assets/images/right.gif) no-repeat right top; float: left; padding-bottom: 4px; color: #765; padding-top: 5px; text-decoration: none
        }
        #admintabs a {
            float: left;
        }
        #admintabs a:hover {
            color: #333;
        }
        #admintabs #current {
            background: url(../assets/images/left_on.gif) no-repeat left top;
        }
        #admintabs #current a {
            background: url(../assets/images/right_on.gif) no-repeat right top; color: #333; float:left;
        }
        </style>
    ";

    include XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/menu.php';

    $return .= "<div id='admintop'>";
    $return .= "<table style=\"width: 100%; padding: 0; \" cellspacing=\"0\"><tr>";
    $return .= "<td style='width: 60%; font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;'><a href='"
               . XOOPS_URL
               . '/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod='
               . $xoopsModule->getVar('mid')
               . "'>"
               . _AM_FBCOM_ADMENU_PREF
               . "</a> | <a href='"
               . XOOPS_URL
               . '/modules/'
               . $xoopsModule->getVar('dirname')
               . "/index.php'>"
               . _AM_FBCOM_ADMENU_GOMOD
               . '</a> | <a href="help.php">'
               . _AM_FBCOM_ADMENU_HELP
               . '</a></td>';
    $return .= "<td style='width: 40%; font-size: 10px; text-align: right; color: #2F5376; padding: 0 6px; line-height: 18px;'>&nbsp;" . $breadcrumb . '</td>';
    $return .= '</tr></table>';
    $return .= '</div>';

    $return .= "<div id='admintabs'>";
    $return .= '<ul>';
    foreach ($adminObject as $key => $menu) {
        $return .= '<li ' . $tblColors[$key] . "><a href=\"" . XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/' . $menu['link'] . "\">" . $menu['title'] . '</a></li>';
    }
    $return .= "</ul></div><div style=\"clear:both;\"></div>";

    echo $return;
}

include __DIR__ . '/functions.php';

xoops_cp_header();

if ($xoop25plus) {
    $adminObject = \Xmf\Module\Admin::getInstance();
    if (!is_object($adminObject)) {
        $xoop25plus = false;
    }
}
//$xoop25plus=false;
