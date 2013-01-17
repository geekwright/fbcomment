<?php
/**
* blocks.php - block functions
*
* @copyright  Copyright © 2012 geekwright, LLC. All rights reserved. 
* @license    fbcomment/docs/license.txt  GNU General Public License (GPL)
* @since      1.0
* @author     Richard Griffith <richard@geekwright.com>
* @package    fbcomment
* @version    $Id$
*/

if (!defined('XOOPS_ROOT_PATH')){ exit(); }

$b_fbcomment_caller_name=strstr($_SERVER['SCRIPT_NAME'],'modules/');
if(!empty($b_fbcomment_caller_name)) {
  $b_fbcomment_caller_name=substr($b_fbcomment_caller_name,8);
  $b_fbcomment_caller_name=substr($b_fbcomment_caller_name,0,strpos($b_fbcomment_caller_name,'/'));
  $b_fbcomment_caller_name=XOOPS_ROOT_PATH . '/modules/' .$b_fbcomment_caller_name . '/include/fbcomment_plugin.php';
  if (file_exists($b_fbcomment_caller_name)) include_once $b_fbcomment_caller_name;
}


function b_fbcomment_addfiledropform($smartyVar,$metas,&$block) {
global $xoTheme, $xoopsTpl;

	// apply our current meta data
	$block['ogtype']=$metas['og:type'];
	$block['ogtitle']=$metas['og:title'];
	$block['ogurl']=$metas['og:url'];
	$block['ogurlenc']=urlencode($metas['og:url']);
	$block['ogdescription']=$metas['og:description'];
	$block['ogimage']=$metas['og:image'];
	$block['ogsite_name'] = $metas['og:site_name'];

	// our update script
	$block['formaction'] = XOOPS_URL . '/modules/fbcomment/ogdataupdate.php';
//	$block['formaction'] = XOOPS_URL . '/modules/fbcomment/dump.php';
	$block['formddscript'] = XOOPS_URL.'/modules/fbcomment/filedrag.js';

	// this controls the edit button, we always want this if the user can edit
	$block['showogmetaedit']='YES';
	
	// we only want one copy of this form, even if we have more than one block displayed.
	
	if(empty($GLOBALS['b_fbccom_addfiledropform_called'])) {
		$GLOBALS['b_fbccom_addfiledropform_called']=true;

		// add our styles
		$xoTheme->addStyleSheet(XOOPS_URL.'/modules/fbcomment/module.css');
		// this controls the edit form
		$block['showogmetaform']='YES';
		
	}

}

// 'core' functions handle attributes common to both facebook comment and like button apis
function b_fbcomment_core_show($options,$LikeOrCom) {
global $xoopsTpl, $xoTheme, $xoopsUser, $xoopsDB;

$plugin_env = array();
// apply block options

      switch ($options[0]) {
	case 1: // SITE
		$oururl=XOOPS_URL.'/';
		break;
	case 2: // SPECIFIC
		$oururl=$options[3];
		break;
	default: // AUTO = 0 FORCE = 3
		$protocol = 'http://'; $defaultport=80;
		if (!empty($_SERVER['HTTPS']) && ($_SERVER['HTTPS']=='on')) {
			$protocol = 'https://';
			$defaultport = 443;
		}
		// add port if not default
		$port= empty($_SERVER['SERVER_PORT']) ? $defaultport : intval($_SERVER['SERVER_PORT']);
		$port = ($port==$defaultport) ? '' : ':'.$port;
		// if force option chosen, set http: on default port
		if ($options[0]==3) { $protocol = 'http://'; $port=''; }

		$names_to_check = explode(',',$options[1]);
		$query='';
		foreach($names_to_check as $i) {
			if($i) {
				if(isset($_REQUEST[$i])) {
					if($query!='') $query .= '&';
					$query .= $i."=".$_REQUEST[$i];
					$plugin_env[$i]=$_REQUEST[$i];
				}
			}
		}
		if($options[2] && $query=='') return false;
		if($query!='') $query='?'.$query;
		$ourscript=$_SERVER['SCRIPT_NAME'];
		// eliminate index.php if no query string
		$ourscript_parts = pathinfo($ourscript);
		if ($ourscript_parts['basename']=='index.php' && $query=='') {
			if(substr($ourscript_parts['dirname'],-1)!='/') $ourscript=$ourscript_parts['dirname'].'/';
			else $ourscript=$ourscript_parts['dirname'];
		}

		$oururl=$protocol.$_SERVER['SERVER_NAME'].$port.$ourscript.$query;
		break;
	}
	
	if($options[4]) $ourcolor=' data-colorscheme="dark"';
	else $ourcolor='';
	$block['colorscheme']=$ourcolor;
	
	$intwidth=intval($options[5]);
	if($intwidth < 1) $ourwidth="";
	else $ourwidth=' data-width="'.$intwidth.'"';
	$block['width']=$ourwidth;


// Now that we are definitely showing a block, supply meta tags the Facebook wants to see

// Note from facebook docs:
// If your site has many comments boxes, we strongly recommend you specify a Facebook app id as the administrator
// (all administrators of the app will be able to moderate comments). Doing this enables a moderator interface on
// Facebook where comments from all plugins administered by your app id can be easily moderated together.
// get directory of module from block code

	$dir = basename( dirname ( dirname( __FILE__ ) ) ) ;
	// Access module configs from block:
	$module_handler = xoops_gethandler('module');
	$module         = $module_handler->getByDirname($dir);
	$config_handler = xoops_gethandler('config');
	$moduleConfig   = $config_handler->getConfigsByCat(0, $module->getVar('mid'));

	$appid = $moduleConfig['facebook-appid'];
	$admins = $moduleConfig['facebook-admins'];
	
	// channel for facebook js sdk, see: http://developers.facebook.com/blog/post/530/
	$block['channel'] = XOOPS_URL.'/modules/'.$dir.'/fbchannel.php?locale='._MB_FBCOM_SDK_CHANNEL_LOCALE;
	$block['locale'] = _MB_FBCOM_SDK_CHANNEL_LOCALE;
	
	$useSmartyVar = $moduleConfig['use-smarty-for-ogdata'];

/* If you use Open Graph tags, the following six are required:
    og:title - The title of the entity.
    og:type - The type of entity. You must select a type from the list of Open Graph types.
    og:image - The URL to an image that represents the entity. Images must be at least 50 pixels by 50 pixels (though minimum 200px by 200px is preferred). Square images work best, but you are allowed to use images up to three times as wide as they are tall.
    og:url - The canonical, permanent URL of the page representing the entity. When you use Open Graph tags, the Like button posts a link to the og:url instead of the URL in the Like button code.
    og:site_name - A human-readable name for your site, e.g., "IMDb".
    fb:admins or fb:app_id - A comma-separated list of either the Facebook IDs of page administrators or a Facebook Platform application ID. At a minimum, include only your own Facebook ID.
*/

	//set some defaults
	$type = (strpos($oururl, '?') ? 'article' : 'website');
	$image=$moduleConfig['default-og-image'];
	$sitename=$xoopsTpl -> get_template_vars( 'xoops_sitename' );
	$title = '';
	$description='';
	
	$metas=array();
	$metas['fb:admins'] = $admins;
	$metas['fb:app_id'] = $appid;

	$metas['og:type']=$type;
	$metas['og:url']=$oururl;
	$metas['og:title']=$title;
	$metas['og:description']=$description;
	$metas['og:image']=$image;
	$metas['og:site_name'] = $sitename;
	
	// call plugin if it exists
	if(function_exists('fbcom_plugin')) {
		$goahead=fbcom_plugin($metas,$plugin_env);
		if(!$goahead) return false;
	}
	
	$oururl=$metas['og:url']; // allow plugin to enhance cannonical url
	
	// get user specifed page data
	$q_url=mysql_real_escape_string($oururl);

	$sql = "SELECT image, title, description FROM ".$xoopsDB->prefix('fbc_og_meta').
	    " where url='{$q_url}' ";
	
	$result = $xoopsDB->query($sql);
	if ($result) { 
		if($myrow=$xoopsDB->fetchArray($result)) {
			if(!empty($myrow['image'])) $metas['og:image']=XOOPS_URL . '/uploads/fbcomment/'.$myrow['image'];
			if(!empty($myrow['title'])) $metas['og:title']=$myrow['title'];
			if(!empty($myrow['description'])) $metas['og:description']=$myrow['description'];
		}
	}

	$ourtags='';
	foreach ($metas as $property => $content) {
	    if($useSmartyVar) {
		if(!empty($content)) { // don't add empty values, let facebook pick default
		    if($property=='fb:admins') {
			$ouradmins=explode(',',$content);
			foreach($ouradmins as $eachadmin) {
			  $ourtags .= sprintf('<meta property="%s" content="%s">'."\n",$property,htmlspecialchars($eachadmin, ENT_QUOTES,null,false));
			}
		    } else {
			$ourtags .= sprintf('<meta property="%s" content="%s">'."\n",$property,htmlspecialchars($content, ENT_QUOTES,null,false));
		    }
		}
	    }
	    else
		if(!empty($content)) $xoTheme->addMeta('meta',$property,htmlspecialchars($content, ENT_QUOTES,null,false));
	}

	// add our editor for open graph meta data
	if(is_object($xoopsUser) && $xoopsUser->isAdmin($module->getVar('mid'))) {	
	    b_fbcomment_addfiledropform('fbc_dd_form_'.$LikeOrCom,$metas,$block);
	 }

	if($useSmartyVar) $xoopsTpl->assign('fbcomment_og_metas', $ourtags);
	
	// assign everything that may have changed with plugins
	$block['appid']=$metas['fb:app_id'];
	$block['admins']=$metas['fb:admins'];
//	$block['href']= urlencode($metas['og:url']);  // this uesd to work, but SOME (not all) like buttons break, so trying next line
	$block['href']= $metas['og:url'];

	return $block;
}

function b_fbcomment_core_edit($options) {
	// href source - options[0]
	$form = _MB_FBCOM_HREF_SOURCE.": <br /><input type='radio' name='options[0]' value='0' ";
	if(!$options[0]) $form .="checked='checked'"; 
	$form .=" />&nbsp;"._MB_FBCOM_HREF_AUTO."&nbsp;<input type='radio' name='options[0]' value='1' ";
	if($options[0]==1) $form .="checked='checked'"; 
	$form .=" />&nbsp;"._MB_FBCOM_HREF_SITE."&nbsp;<input type='radio' name='options[0]' value='2' ";
	if($options[0]==2) $form .="checked='checked'"; 
	$form .= " />&nbsp;"._MB_FBCOM_HREF_SPECIFIC."&nbsp;<input type='radio' name='options[0]' value='3' ";
	if($options[0]==3) $form .="checked='checked'"; 
	$form .= " />&nbsp;"._MB_FBCOM_HREF_FORCE_HTTP."<br /><br />";
	// parameter list - options[1]
	$form .= _MB_FBCOM_HERE_PARMS.": <input type='text' size='50' value='".$options[1]."'id='options[1]' name='options[1]' /><br /><br />";
	// require parameters - options[2]
	$form .=_MB_FBCOM_REQ_PARMS.": <input type='radio' name='options[2]' value='1' ";
	if($options[2]) $form .="checked='checked'"; 
	$form .=" />&nbsp;"._MB_FBCOM_YES."&nbsp;<input type='radio' name='options[2]' value='0' ";
	if(!$options[2]) $form .="checked='checked'"; 
	$form .= " />&nbsp;"._MB_FBCOM_NO."<br /><br />";
	// static href - options[3]
	$form .= _MB_FBCOM_MANUAL_HREF.": <input type='text' value='".$options[3]."'id='options[3]' name='options[3]' /><br /><br />";
	// colorscheme - options[4]
	$form .=_MB_FBCOM_COLOR.": <input type='radio' name='options[4]' value='0' ";
	if(!$options[4]) $form .="checked='checked'"; 
	$form .=" />&nbsp;"._MB_FBCOM_LIGHT."&nbsp;<input type='radio' name='options[4]' value='1' ";
	if($options[4]) $form .="checked='checked'"; 
	$form .= " />&nbsp;"._MB_FBCOM_DARK."<br /><br />";
	// parameter list - options[5]
	$form .= _MB_FBCOM_WIDTH.": <input type='text' size='5' value='".$options[5]."'id='options[5]' name='options[5]' /><br /><br />";
	
	return $form;
}

function b_fbcomment_comment_show($options) {

	$block=b_fbcomment_core_show($options,'comment');
	if($block==false) return false;

	$intposts=intval($options[6]);
	if($intposts < 1) $intposts="10";
	$ourpost=" data-num-posts=\"$intposts\"";
	$block['numposts']=$ourpost;
	

	return $block;
}

function b_fbcomment_comment_edit($options) {

	$form = b_fbcomment_core_edit($options);

	// parameter list - options[6]
	$form .= _MB_FBCOM_NUM_POSTS.": <input type='text' size='6' value='".$options[6]."'id='options[6]' name='options[6]' /><br /><br />";

	return $form;
}

function b_fbcomment_like_show($options) {

	if(!$block=b_fbcomment_core_show($options,'like')) return false;

	if($options[6]) $ourfaces=' data-show-faces="true"';
	else $ourfaces=' data-show-faces="false"';
	$block['faces']=$ourfaces;

	if($options[7]) $ouraction=' data-action="recommend"';
	else $ouraction='';
	$block['action']=$ouraction;

	switch ($options[8]) {
	case 1:
	    $ourlayout=' data-layout="button_count"';
	    break;
	case 2:
	    $ourlayout=' data-layout="box_count"';
	    break;
	default: // standard is default
	    $ourlayout='';
	    break;
	}
	$block['layout']=$ourlayout;
	
	if($options[9]) $oursend=' data-send="true"';
	else $oursend=' data-send="false"';
	$block['send']=$oursend;

	return $block;
}

function b_fbcomment_like_edit($options) {

	$form = b_fbcomment_core_edit($options);

	// show faces - options[6]
	$form .=_MB_FBCOM_LIKE_FACES.": <input type='radio' name='options[6]' value='1' ";
	if($options[6]) $form .="checked='checked'"; 
	$form .=" />&nbsp;"._MB_FBCOM_YES."&nbsp;<input type='radio' name='options[6]' value='0' ";
	if(!$options[6]) $form .="checked='checked'"; 
	$form .= " />&nbsp;"._MB_FBCOM_NO."<br /><br />";
	
	// show faces - options[7]
	$form .=_MB_FBCOM_LIKE_ACTION.": <input type='radio' name='options[7]' value='0' ";
	if(!$options[7]) $form .="checked='checked'"; 
	$form .=" />&nbsp;"._MB_FBCOM_LK_LIKE."&nbsp;<input type='radio' name='options[7]' value='1' ";
	if($options[7]) $form .="checked='checked'"; 
	$form .= " />&nbsp;"._MB_FBCOM_LK_RCMD."<br /><br />";

	// show faces - options[8]
	$form .=_MB_FBCOM_LIKE_LAYOUT.": <input type='radio' name='options[8]' value='0' ";
	if(!$options[8]) $form .="checked='checked'"; 
	$form .=" />&nbsp;"._MB_FBCOM_LK_STD."&nbsp;<input type='radio' name='options[8]' value='1' ";
	if($options[8]==1) $form .="checked='checked'"; 
	$form .=" />&nbsp;"._MB_FBCOM_LK_BTN."&nbsp;<input type='radio' name='options[8]' value='2' ";
	if($options[8]==2) $form .="checked='checked'"; 
	$form .= " />&nbsp;"._MB_FBCOM_LK_BOX."<br /><br />";
	
	// show send button - options[9]
	$form .=_MB_FBCOM_LIKE_SEND.": <input type='radio' name='options[9]' value='1' ";
	if($options[9]) $form .="checked='checked'"; 
	$form .=" />&nbsp;"._MB_FBCOM_YES."&nbsp;<input type='radio' name='options[9]' value='0' ";
	if(!$options[9]) $form .="checked='checked'"; 
	$form .= " />&nbsp;"._MB_FBCOM_NO."<br /><br />";

	return $form;
}

function b_fbcomment_combo_show($options) {

	if(!$block=b_fbcomment_core_show($options,'like')) return false;

	$i=6;
	
	// comment related
	$intposts=intval($options[$i]);
	if($intposts < 1) $intposts="10";
	$ourpost=" data-num-posts=\"$intposts\"";
	$block['numposts']=$ourpost;
	
	// like related
	$i += 1;
	if($options[$i]) $ourfaces=' data-show-faces="true"';
	else $ourfaces=' data-show-faces="false"';
	$block['faces']=$ourfaces;

	$i += 1;
	if($options[$i]) $ouraction=' data-action="recommend"';
	else $ouraction='';
	$block['action']=$ouraction;

	$i += 1;
	switch ($options[$i]) {
	case 1:
	    $ourlayout=' data-layout="button_count"';
	    break;
	case 2:
	    $ourlayout=' data-layout="box_count"';
	    break;
	default: // standard is default
	    $ourlayout='';
	    break;
	}
	$block['layout']=$ourlayout;
	
	$i += 1;
	if($options[$i]) $oursend=' data-send="true"';
	else $oursend=' data-send="false"';
	$block['send']=$oursend;

	return $block;
}

function b_fbcomment_combo_edit($options) {

	$form = b_fbcomment_core_edit($options);

	$i=6;
	// comment portion
	// number of comments to show
	$form .= _MB_FBCOM_NUM_POSTS.": <input type='text' size='6' value='".$options[$i]."'id='options[{$i}]' name='options[{$i}]' /><br /><br />";

	// like
	// show faces
	$i += 1;
	$form .=_MB_FBCOM_LIKE_FACES.": <input type='radio' name='options[{$i}]' value='1' ";
	if($options[$i]) $form .="checked='checked'"; 
	$form .=" />&nbsp;"._MB_FBCOM_YES."&nbsp;<input type='radio' name='options[{$i}]' value='0' ";
	if(!$options[$i]) $form .="checked='checked'"; 
	$form .= " />&nbsp;"._MB_FBCOM_NO."<br /><br />";
	
	// show faces
	$i += 1;
	$form .=_MB_FBCOM_LIKE_ACTION.": <input type='radio' name='options[{$i}]' value='0' ";
	if(!$options[$i]) $form .="checked='checked'"; 
	$form .=" />&nbsp;"._MB_FBCOM_LK_LIKE."&nbsp;<input type='radio' name='options[{$i}]' value='1' ";
	if($options[$i]) $form .="checked='checked'"; 
	$form .= " />&nbsp;"._MB_FBCOM_LK_RCMD."<br /><br />";

	// show faces
	$i += 1;
	$form .=_MB_FBCOM_LIKE_LAYOUT.": <input type='radio' name='options[{$i}]' value='0' ";
	if(!$options[$i]) $form .="checked='checked'"; 
	$form .=" />&nbsp;"._MB_FBCOM_LK_STD."&nbsp;<input type='radio' name='options[{$i}]' value='1' ";
	if($options[$i]==1) $form .="checked='checked'"; 
	$form .=" />&nbsp;"._MB_FBCOM_LK_BTN."&nbsp;<input type='radio' name='options[{$i}]' value='2' ";
	if($options[$i]==2) $form .="checked='checked'"; 
	$form .= " />&nbsp;"._MB_FBCOM_LK_BOX."<br /><br />";
	
	// show send button
	$i += 1;
	$form .=_MB_FBCOM_LIKE_SEND.": <input type='radio' name='options[{$i}]' value='1' ";
	if($options[$i]) $form .="checked='checked'"; 
	$form .=" />&nbsp;"._MB_FBCOM_YES."&nbsp;<input type='radio' name='options[{$i}]' value='0' ";
	if(!$options[$i]) $form .="checked='checked'"; 
	$form .= " />&nbsp;"._MB_FBCOM_NO."<br /><br />";

	return $form;
}

?>