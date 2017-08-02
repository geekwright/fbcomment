<?php
/**
 * ogupdate.php - update open graph data by ajax or traditional form submit
 *
 * @copyright  Copyright © 2012 geekwright, LLC. All rights reserved.
 * @license    fbcomment/docs/license.txt  GNU General Public License (GPL)
 * @since      1.0
 * @author     Richard Griffith <richard@geekwright.com>
 * @package    fbcomment
 */
include __DIR__ . '/../../mainfile.php';

global $xoopsDB, $xoopsUser, $xoopsModuleConfig, $xoopsOption;
$moduleDirName = basename(__DIR__);
$moduleHelper  = Xmf\Module\Helper::getHelper($moduleDirName);

// check for ajax upload
$ogimage       = (isset($_SERVER['HTTP_X_FILENAME']) ? $_SERVER['HTTP_X_FILENAME'] : false);
$ogtitle       = (isset($_SERVER['HTTP_X_OGTITLE']) ? $_SERVER['HTTP_X_OGTITLE'] : false);
$ogdescription = (isset($_SERVER['HTTP_X_OGDESC']) ? $_SERVER['HTTP_X_OGDESC'] : false);
$ogurl         = (isset($_SERVER['HTTP_X_OGURL']) ? $_SERVER['HTTP_X_OGURL'] : false);
if ($ogurl) {
    $url = urldecode($ogurl);
}
if ($ogimage) {
    $ogimage = urldecode($ogimage);
}
if ($ogtitle) {
    $ogtitle = urldecode($ogtitle);
}
if ($ogdescription) {
    $ogdescription = urldecode($ogdescription);
}

// check for authority - use module admin permission
if (!($moduleHelper->isUserAdmin())) {
    if ($ogimage) {
        header('Status: 500 Unspecified Failure');
        exit();
    } else {
        redirect_header($url, 3, _NOT_AUTH);
    }
}

// need url if any other ajax headers specified
if (!$ogurl && ($ogimage || $ogtitle || $ogdescription)) {
    header('Status: 500 Unspecified Failure');
    exit();
}

if ($ogtitle) {
    update_fbc_og_meta($url, 'title', $ogtitle);
    exit(0);
}
if ($ogdescription) {
    update_fbc_og_meta($url, 'description', $ogdescription);
    exit(0);
}

if ($ogimage) {
    // provide error logging for our sanity in debugging ajax use (won't see xoops logger)
    restore_error_handler();
    error_reporting(-1);

    $tempfn = tempnam(XOOPS_ROOT_PATH . '/uploads/fbcomment/', 'OGIMG_');
    $image  = file_get_contents('php://input');
    file_put_contents($tempfn, $image);

    $ogimage_parts = pathinfo($ogimage);

    // we are intentionally ignoring $ogimage_parts['dirname']
    // get rid of extra dots, commas and spaces
    $ogimage  = str_replace(array('.', ' ', ','), '_', $ogimage_parts['basename']) . '.' . strtolower($ogimage_parts['extension']);
    $filename = $tempfn . '_' . $ogimage;
    $justfn   = basename($filename);

    rename($tempfn, $filename);
    chmod($filename, 0644);

    $q_url = $GLOBALS['xoopsDB']->escape($url);
    $sql   = 'SELECT image FROM ' . $xoopsDB->prefix('fbc_og_meta') . " where url='{$q_url}' ";

    $result = $xoopsDB->query($sql);
    if ($result) {
        if ($myrow = $xoopsDB->fetchArray($result)) {
            if (!empty($myrow['image'])) {
                $oldfilename = XOOPS_ROOT_PATH . '/uploads/fbcomment/' . $myrow['image'];
                unlink($oldfilename);
            }
        }
    }

    $fn = $GLOBALS['xoopsDB']->escape($justfn);
    update_fbc_og_meta($url, 'image', $fn);

    exit();
}

/**
 * @param $url
 * @param $col
 * @param $val
 */
function update_fbc_og_meta($url, $col, $val)
    //  url varchar(767) CHARACTER SET ascii  NOT NULL,
    //  image varchar(1024),
    //  title varchar(255),
    //  description varchar(1024),
    //  lastupdate  int(10) NOT NULL default '0',
{
    global $xoopsDB;

    $q_url = $GLOBALS['xoopsDB']->escape($url);
    $q_col = $GLOBALS['xoopsDB']->escape($col);
    $q_val = $GLOBALS['xoopsDB']->escape($val);

    $sql = 'UPDATE ' . $xoopsDB->prefix('fbc_og_meta') . " set {$q_col} = '{$q_val}', lastupdate = UNIX_TIMESTAMP() " . " where url='{$q_url}' ";

    $result = $xoopsDB->queryF($sql);
    if ($result && !$xoopsDB->getAffectedRows()) { // database is OK but nothing to update
        $sql    = 'insert into ' . $xoopsDB->prefix('fbc_og_meta') . " (url, {$col}, lastupdate) " . " values('{$q_url}','{$val}', UNIX_TIMESTAMP() )";
        $result = $xoopsDB->queryF($sql);
    }
}

/*
Form fields
input MAX_FILE_SIZE
input X_OGURL
input fileselect[]
input  fbc_dd_ogtitle
textarea fbc_dd_ogdesc
*/
// check for multifile upload (fallback if browser can't use our ajax code)
if (empty($_POST['X_OGURL'])) {
    redirect_header('', 5, 'Required URL missing');
}
$ogurl = urldecode($_POST['X_OGURL']);
$url   = $GLOBALS['xoopsDB']->escape($ogurl);

$ogtitle = '';
if (!empty($_POST['fbc_dd_ogtitle'])) {
    $ogtitle = urldecode($_POST['fbc_dd_ogtitle']);
}
$ogtitle = $GLOBALS['xoopsDB']->escape($ogtitle);
update_fbc_og_meta($url, 'title', $ogtitle);

$ogdescription = '';
if (!empty($_POST['fbc_dd_ogdesc'])) {
    $ogdescription = urldecode($_POST['fbc_dd_ogdesc']);
}
$ogdescription = $GLOBALS['xoopsDB']->escape($ogdescription);
update_fbc_og_meta($url, 'description', $ogdescription);

if (!empty($_FILES['fileselect'])) {
    restore_error_handler();
    error_reporting(-1);

    $error = false;
    $files = $_FILES['fileselect'];
    foreach ($files['error'] as $id => $err) {
        $fn     = $files['name'][$id];
        $tempfn = $files['tmp_name'][$id];
        $type   = $files['type'][$id];
        if ($err != UPLOAD_ERR_NO_FILE) {
            if ($err == UPLOAD_ERR_OK && !strcasecmp(strstr($type, '/', true), 'image')) {
                //
                $newfn = tempnam(XOOPS_ROOT_PATH . '/uploads/fbcomment/', 'OGIMG_');

                $ogimage_parts = pathinfo($fn);

                // we are intentionally ignoring $ogimage_parts['dirname']
                // get rid of extra dots, commas and spaces
                $ogimage  = str_replace(array('.', ' ', ','), '_', $ogimage_parts['basename']) . '.' . strtolower($ogimage_parts['extension']);
                $filename = $newfn . '_' . $ogimage;
                $justfn   = basename($filename);

                rename($newfn, $filename);
                move_uploaded_file($tempfn, $filename);
                chmod($filename, 0644);

                $q_url = $GLOBALS['xoopsDB']->escape($url);
                $sql   = 'SELECT image FROM ' . $xoopsDB->prefix('fbc_og_meta') . " where url='{$q_url}' ";

                $result = $xoopsDB->query($sql);
                if ($result) {
                    if ($myrow = $xoopsDB->fetchArray($result)) {
                        if (!empty($myrow['image'])) {
                            $oldfilename = XOOPS_ROOT_PATH . '/uploads/fbcomment/' . $myrow['image'];
                            unlink($oldfilename);
                        }
                    }
                }

                $fn = $GLOBALS['xoopsDB']->escape($justfn);
                update_fbc_og_meta($url, 'image', $fn);
            } else {
                $error = true;
            }
            // we end up here if no file was uploaded - this is not an error, but no action
        }
    }
    if ($error) {
        redirect_header($ogurl, 5, _MD_FBCOM_IMAGE_UPLOAD_ERROR);
    } else {
        redirect_header($ogurl, 5, _MD_FBCOM_UPDATE_OK);
    }
}
// chmod("/somedir/somefile", 0644);

/*-----------�q�X���G��--------------*/
include XOOPS_ROOT_PATH . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'fbcomment_index.tpl';
$xoopsTpl->assign('body', '(empty)');
require_once XOOPS_ROOT_PATH . '/footer.php';
