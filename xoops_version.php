<?php
/**
 * xoops_version.php
 *
 * @copyright  Copyright © 2012 geekwright, LLC. All rights reserved.
 * @license    fbcomment/docs/license.txt  GNU General Public License (GPL)
 * @since      1.0
 * @author     Richard Griffith <richard@geekwright.com>
 * @package    fbcomment
 */
// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

$modversion['version']       = '1.4';
$modversion['module_status'] = 'Beta 1';
$modversion['release_date']  = '2014/04/23';
$modversion['name']          = _MI_FBCOM_NAME;
$modversion['description']   = _MI_FBCOM_DESC;
$modversion['author']        = 'Richard Griffith';
$modversion['credits']       = 'geekwright';
$modversion['license']       = 'GPL V2';
$modversion['official']      = 0; //1 indicates supported by XOOPS Dev Team, 0 means 3rd party supported
$modversion['image']         = 'assets/images/logoModule.png';
$modversion['dirname']       = basename(__DIR__);

$modversion['modicons16'] = 'assets/images/icons/16';
$modversion['modicons32'] = 'assets/images/icons/32';

// things for ModuleAdmin() class

$modversion['license_url']         = XOOPS_URL . '/modules/fbcomment/docs/license.txt';
$modversion['license_url']         = substr($modversion['license_url'], strpos($modversion['license_url'], '//') + 2);
$modversion['module_website_url']  = 'geekwright.com';
$modversion['module_website_name'] = 'geekwright.com';
$modversion['min_php']             = '5.5';
$modversion['min_xoops']           = '2.5.9';
$modversion['system_menu']         = 1;
$modversion['help']                = 'page=help';
// Admin things
$modversion['hasAdmin']   = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu']  = 'admin/menu.php';

// Menu
$modversion['hasMain'] = 1;

// Search
$modversion['hasSearch'] = 0;

// comments
$modversion['hasComments'] = 0;

// notification
$modversion['hasNotification'] = 1;
//$modversion['notification']['lookup_file'] = 'include/notification.inc.php';
//$modversion['notification']['lookup_func'] = 'notify_iteminfo';

$modversion['notification']['category'][1]['name']           = 'global';
$modversion['notification']['category'][1]['title']          = _MI_FBCOM_GLOBAL_NOTIFY;
$modversion['notification']['category'][1]['description']    = _MI_FBCOM_GLOBAL_NOTIFY_DESC;
$modversion['notification']['category'][1]['subscribe_from'] = array('notify.php');

$modversion['notification']['event'][1]['name']          = 'new_comment';
$modversion['notification']['event'][1]['category']      = 'global';
$modversion['notification']['event'][1]['title']         = _MI_FBCOM_COMMENT_NOTIFY;
$modversion['notification']['event'][1]['caption']       = _MI_FBCOM_COMMENT_NOTIFY_CAPTION;
$modversion['notification']['event'][1]['description']   = _MI_FBCOM_COMMENT_NOTIFY_DESC;
$modversion['notification']['event'][1]['mail_template'] = 'new_comment';
$modversion['notification']['event'][1]['mail_subject']  = _MI_FBCOM_COMMENT_NOTIFY_SUBJECT;
$modversion['notification']['event'][1]['admin_only']    = 1;

$modversion['notification']['event'][2]['name']          = 'new_like';
$modversion['notification']['event'][2]['category']      = 'global';
$modversion['notification']['event'][2]['title']         = _MI_FBCOM_LIKE_NOTIFY;
$modversion['notification']['event'][2]['caption']       = _MI_FBCOM_LIKE_NOTIFY_CAPTION;
$modversion['notification']['event'][2]['description']   = _MI_FBCOM_LIKE_NOTIFY_DESC;
$modversion['notification']['event'][2]['mail_template'] = 'new_like';
$modversion['notification']['event'][2]['mail_subject']  = _MI_FBCOM_LIKE_NOTIFY_SUBJECT;
$modversion['notification']['event'][2]['admin_only']    = 1;

// Config

$modversion['config'][1] = array(
    'name'        => 'facebook-admins',
    'title'       => '_MI_FBCOM_CONFIG_FBADMINS',
    'description' => '_MI_FBCOM_CONFIG_FBADMINS_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '',
    'options'     => array()
);
$modversion['config'][]  = array(
    'name'        => 'facebook-appid',
    'title'       => '_MI_FBCOM_CONFIG_FBAPPID',
    'description' => '_MI_FBCOM_CONFIG_FBAPPID_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '',
    'options'     => array()
);
$modversion['config'][]  = array(
    'name'        => 'facebook-appsecret',
    'title'       => '_MI_FBCOM_CONFIG_FBAPPSECRET',
    'description' => '_MI_FBCOM_CONFIG_FBAPPSECRET_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '',
    'options'     => array()
);
$modversion['config'][]  = array(
    'name'        => 'use-smarty-for-ogdata',
    'title'       => '_MI_FBCOM_CONFIG_ENABLE_SMARTY_METAS',
    'description' => '_MI_FBCOM_CONFIG_ENABLE_SMARTY_METAS_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '0',
    'options'     => array()
);
$modversion['config'][]  = array(
    'name'        => 'default-og-image',
    'title'       => '_MI_FBCOM_CONFIG_DEFAULT_IMAGE',
    'description' => '_MI_FBCOM_CONFIG_DEFAULT_IMAGE_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '',
    'options'     => array()
);

// Database
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['onInstall']        = 'include/install.php';
//$modversion['onUpdate'] = 'include/update.php';
//$modversion['onUninstall'] = 'include/uninstall.php';
$modversion['tables'][0] = 'fbc_og_meta';
$modversion['tables'][]  = 'fbc_like_tracker';
$modversion['tables'][]  = 'fbc_comment_tracker';

// Blocks

$modversion['blocks'][1] = array(
    'file'        => 'blocks.php',
    'name'        => _MI_FBCOM_COMMENT,
    'description' => _MI_FBCOM_COMMENT_DESC,
    'show_func'   => 'b_fbcomment_comment_show',
    'edit_func'   => 'b_fbcomment_comment_edit',
    'options'     => '0|page,post_id,itemid,topic_id,forum,storyid,lid|0|' . XOOPS_URL . '/|0|450|10',
    'template'    => 'fbcomment_com_block.tpl'
);

$modversion['blocks'][] = array(
    'file'        => 'blocks.php',
    'name'        => _MI_FBCOM_LIKE,
    'description' => _MI_FBCOM_LIKE_DESC,
    'show_func'   => 'b_fbcomment_like_show',
    'edit_func'   => 'b_fbcomment_like_edit',
    'options'     => '0|page,post_id,itemid,topic_id,forum,storyid,lid|0|' . XOOPS_URL . '/|0|0|0|0|0',
    'template'    => 'fbcomment_like_block.tpl'
);

$modversion['blocks'][] = array(
    'file'        => 'blocks.php',
    'name'        => _MI_FBCOM_COMBO,
    'description' => _MI_FBCOM_COMBO_DESC,
    'show_func'   => 'b_fbcomment_combo_show',
    'edit_func'   => 'b_fbcomment_combo_edit',
    'options'     => '0|page,post_id,itemid,topic_id,forum,storyid,lid|0|' . XOOPS_URL . '/|0|450|10|0|0|0|0',
    'template'    => 'fbcomment_combo_block.tpl'
);

$modversion['blocks'][] = array(
    'file'        => 'blocks.php',
    'name'        => _MI_FBCOM_ACTIVITY,
    'description' => _MI_FBCOM_ACTIVITY_DESC,
    'show_func'   => 'b_fbcomment_activity_show',
    'edit_func'   => 'b_fbcomment_activity_edit',
    'options'     => '||300|300|1|0||0||||0',
    'template'    => 'fbcomment_activity_block.tpl'
);

$modversion['blocks'][] = array(
    'file'        => 'blocks.php',
    'name'        => _MI_FBCOM_FEED_POST,
    'description' => _MI_FBCOM_FEED_POST_DESC,
    'show_func'   => 'b_fbcomment_feed_post_show',
    'edit_func'   => 'b_fbcomment_feed_post_edit',
    'options'     => '0|page,post_id,itemid,topic_id,forum,storyid,lid|0|' . XOOPS_URL . '/',
    'template'    => 'fbcomment_feed_post_block.tpl'
);

$modversion['blocks'][] = array(
    'file'        => 'blocks.php',
    'name'        => _MI_FBCOM_SHOW_FEED,
    'description' => _MI_FBCOM_SHOW_FEED_DESC,
    'show_func'   => 'b_fbcomment_show_feed_show',
    'edit_func'   => 'b_fbcomment_show_feed_edit',
    'options'     => '|5',
    'template'    => 'fbcomment_show_feed_block.tpl'
);

$modversion['blocks'][] = array(
    'file'        => 'blocks.php',
    'name'        => _MI_FBCOM_LIKEBOX,
    'description' => _MI_FBCOM_LIKEBOX_DESC,
    'show_func'   => 'b_fbcomment_likebox_show',
    'edit_func'   => 'b_fbcomment_likebox_edit',
    'options'     => '|300|||1|1|1||0',
    'template'    => 'fbcomment_likebox_block.tpl'
);

// Templates
$modversion['templates'][1] = array(
    'file'        => 'fbcomment_index.tpl',
    'description' => 'Module Index'
);

$modversion['templates'][] = array(
    'file'        => 'fbcomment_notify.tpl',
    'description' => 'Notification Options'
);

$modversion['templates'][] = array(
    'file'        => 'blocks/fbcomment_common_head.tpl',
    'description' => 'Block Header'
);

$modversion['templates'][] = array(
    'file'        => 'blocks/fbcomment_common_tail.tpl',
    'description' => 'Block Footer'
);
