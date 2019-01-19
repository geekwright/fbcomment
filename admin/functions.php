<?php
/**
 * admin/functions.php - admin area functions
 *
 * @copyright  Copyright © 2012 geekwright, LLC. All rights reserved.
 * @license    fbcomment/docs/license.txt  GNU General Public License (GPL)
 * @since      1.0
 * @author     Richard Griffith <richard@geekwright.com>
 * @package    fbcomment
 */

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

// if server type or configuration voodoo will keep the detection from working, issue a warning
function compatabilitytest()
{
    if (!(array_key_exists('SERVER_NAME', $_SERVER) && array_key_exists('SERVER_PORT', $_SERVER)
          && array_key_exists('SCRIPT_NAME', $_SERVER))) {
        $issue = '<div class="errorMsg"><div>Automatic URL source option may not work on this server!<br>The required $_SERVER variables are not available.</div></div><br>';
        echo $issue;
    } else {
        echo <<<EOT
<script type="text/javascript">
var doc_url = document.URL;
var n=doc_url.search("//"); n=n+2;
doc_url=doc_url.substring(n);
n=doc_url.indexOf("?");
if (n> -1) { doc_url=doc_url.substring(0, n); }

EOT;
        $port = (int)$_SERVER['SERVER_PORT'];
        if ($port == 80) {
            $port = '';
        } else {
            $port = ':' . $port;
        }
        echo "env_url='" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['SCRIPT_NAME'] . "';";
        echo <<<EOT

if (doc_url != env_url) {
document.write('<div class="errorMsg"><div>Automatic URL source option may not work on this server!<br>The environment derived url does not match the one derived from DOM.<br>');
document.write('The javascrip document.URL derived page is: '+doc_url+'<br>');
document.write('The $ SERVER environment derived page is: '+env_url+'<br></div></div><br>');
}
</script>
EOT;
    }
}

// return an array of most recently updated rows of a tracker table
/**
 * @param $type
 *
 * @return array|bool
 */
function getrecent($type)
{
    global $xoopsDB;

    $table   = 'fbc_comment_tracker';
    $datecol = 'lastcomment';
    if ($type === 'like') {
        $table   = 'fbc_like_tracker';
        $datecol = 'lastlike';
    }

    $sql = "SELECT url, count, {$datecol} FROM " . $xoopsDB->prefix($table) . " ORDER BY {$datecol} DESC LIMIT 50 ";

    $return = array();
    $result = $xoopsDB->query($sql);
    if ($result) {
        while ($myrow = $xoopsDB->fetchArray($result)) {
            $return[] = $myrow;
        }

        return $return;
    } else {
        return false;
    }
}
