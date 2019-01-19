<?php
// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');
// Module Info
//if (defined('XOOPS_ROOT_PATH')) {
// The name and description of module
define('_MI_FBCOM_NAME', 'FBComments');
define('_MI_FBCOM_DESC', 'Facebook Comment and Like Blocks for XOOPS');

define('_MI_FBCOM_ADMENU', 'Home');
define('_MI_FBCOM_ABOUT', 'About');
define('_MI_FBCOM_RECENT_COMMENTS', 'Recent Comments');
define('_MI_FBCOM_RECENT_LIKES', 'Recent Likes');

// config options
define('_MI_FBCOM_CONFIG_FBADMINS', 'Facebook Id(s) for Admin (recommended)');
define('_MI_FBCOM_CONFIG_FBADMINS_DESC', 'Numeric Facebook User Id of admin, or comma separated list of same.');

define('_MI_FBCOM_CONFIG_FBAPPID', 'or Facebook App Id');
define('_MI_FBCOM_CONFIG_FBAPPID_DESC', 'App Id assigned when site was registered as an application on Facebook');

define('_MI_FBCOM_CONFIG_ENABLE_SMARTY_METAS', 'Enable Smarty Open Graph Meta data');
define('_MI_FBCOM_CONFIG_ENABLE_SMARTY_METAS_DESC', 'Set SMARTY variable $fbcomment_og_metas for use in theme.html instead of regular meta tags.');

define('_MI_FBCOM_CONFIG_DEFAULT_IMAGE', 'URL for Default Image');
define('_MI_FBCOM_CONFIG_DEFAULT_IMAGE_DESC', 'URL of image to be used as og:image if no other is available. Should be at lease 200x200 pixels. This should be a fully qualified URL, i.e. http://example.com/logo.jpg');

// Blocks
define('_MI_FBCOM_COMMENT', 'Comments');
define('_MI_FBCOM_COMMENT_DESC', 'Facebook comment block');

define('_MI_FBCOM_LIKE', 'Like Button');
define('_MI_FBCOM_LIKE_DESC', 'Facebook like button block');

define('_MI_FBCOM_COMBO', 'Combination Like and Comments');
define('_MI_FBCOM_COMBO_DESC', 'Facebook like and comment combination block');

// notifications

define('_MI_FBCOM_GLOBAL_NOTIFY', 'FBComments');
define('_MI_FBCOM_GLOBAL_NOTIFY_DESC', 'Notifications of FBComments Activity');

define('_MI_FBCOM_COMMENT_NOTIFY', 'newcomment');
define('_MI_FBCOM_COMMENT_NOTIFY_CAPTION', 'Comment Made');
define('_MI_FBCOM_COMMENT_NOTIFY_DESC', 'Receive notification when anyone leaves a comment.');
define('_MI_FBCOM_COMMENT_NOTIFY_SUBJECT', '[{X_SITENAME}] auto-notify : New Comment');

define('_MI_FBCOM_LIKE_NOTIFY', 'newlike');
define('_MI_FBCOM_LIKE_NOTIFY_CAPTION', 'Liked');
define('_MI_FBCOM_LIKE_NOTIFY_DESC', 'Receive notification when anyone likes something.');
define('_MI_FBCOM_LIKE_NOTIFY_SUBJECT', '[{X_SITENAME}] auto-notify : New LIKE');

// new in version 1.1

// new config option
define('_MI_FBCOM_CONFIG_FBAPPSECRET', 'Secret for Facebook App Id');
define('_MI_FBCOM_CONFIG_FBAPPSECRET_DESC', 'Secret for App Id (only needed if using Show Feed ');

// support for new blocks
define('_MI_FBCOM_ACTIVITY', 'Activity Feed');
define('_MI_FBCOM_ACTIVITY_DESC', 'Facebook activity feed block');

define('_MI_FBCOM_LIKEBOX', 'Like Box');
define('_MI_FBCOM_LIKEBOX_DESC', 'Show Like Box for a Facebook Page');

define('_MI_FBCOM_FEED_POST', 'Post To Feed');
define('_MI_FBCOM_FEED_POST_DESC', "Post page to user's Facebook newsfeed");

define('_MI_FBCOM_SHOW_FEED', 'Show Feed');
define('_MI_FBCOM_SHOW_FEED_DESC', 'Show recent activity on a specific Facebook feed');
//}
