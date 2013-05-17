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

function b_fbcomment_addfiledropform($metas,&$block) {
global $xoTheme, $xoopsTpl;

	// apply our current meta data
	$block['ogtype']=$metas['og:type'];
	$block['ogtitle']=htmlspecialchars($metas['og:title'], ENT_QUOTES,null,false);
	$block['ogurl']=$metas['og:url'];
	$block['ogurlenc']=urlencode($metas['og:url']);
	$block['ogdescription']=htmlentities($metas['og:description']);
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
function b_fbcomment_core_show($options) {
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
	
	$metas['og:description']=str_replace(array("\r\n", "\n", "\r"), ' ', $metas['og:description']);
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
	    b_fbcomment_addfiledropform($metas,$block);
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
	$form .= _MB_FBCOM_HERE_PARMS.": <input type='text' size='50' value='".$options[1]."' id='options[1]' name='options[1]' /><br /><br />";
	// require parameters - options[2]
	$form .=_MB_FBCOM_REQ_PARMS.": <input type='radio' name='options[2]' value='1' ";
	if($options[2]) $form .="checked='checked'"; 
	$form .=" />&nbsp;"._MB_FBCOM_YES."&nbsp;<input type='radio' name='options[2]' value='0' ";
	if(!$options[2]) $form .="checked='checked'"; 
	$form .= " />&nbsp;"._MB_FBCOM_NO."<br /><br />";
	// static href - options[3]
	$form .= _MB_FBCOM_MANUAL_HREF.": <input type='text' value='".$options[3]."' id='options[3]' name='options[3]' /><br /><br />";
	
	return $form;
}

function b_fbcomment_comment_show($options) {

	$block=b_fbcomment_core_show($options);
	if($block==false) return false;

	if($options[4]) $ourcolor=' data-colorscheme="dark"';
	else $ourcolor='';
	$block['colorscheme']=$ourcolor;
	
	$intwidth=intval($options[5]);
	if($intwidth < 1) $ourwidth="";
	else $ourwidth=' data-width="'.$intwidth.'"';
	$block['width']=$ourwidth;

	$intposts=intval($options[6]);
	if($intposts < 1) $intposts="10";
	$ourpost=" data-num-posts=\"$intposts\"";
	$block['numposts']=$ourpost;
	

	return $block;
}

function b_fbcomment_comment_edit($options) {

	$form = b_fbcomment_core_edit($options);
	
	// colorscheme - options[4]
	$form .=_MB_FBCOM_COLOR.": <input type='radio' name='options[4]' value='0' ";
	if(!$options[4]) $form .="checked='checked'"; 
	$form .=" />&nbsp;"._MB_FBCOM_LIGHT."&nbsp;<input type='radio' name='options[4]' value='1' ";
	if($options[4]) $form .="checked='checked'"; 
	$form .= " />&nbsp;"._MB_FBCOM_DARK."<br /><br />";
	
	// parameter list - options[5]
	$form .= _MB_FBCOM_WIDTH.": <input type='text' size='5' value='".$options[5]."' id='options[5]' name='options[5]' /><br /><br />";
	
	// parameter list - options[6]
	$form .= _MB_FBCOM_NUM_POSTS.": <input type='text' size='6' value='".$options[6]."' id='options[6]' name='options[6]' /><br /><br />";

	return $form;
}

function b_fbcomment_like_show($options) {

	if(!$block=b_fbcomment_core_show($options,'like')) return false;

	if($options[4]) $ourcolor=' data-colorscheme="dark"';
	else $ourcolor='';
	$block['colorscheme']=$ourcolor;
	
	$intwidth=intval($options[5]);
	if($intwidth < 1) $ourwidth="";
	else $ourwidth=' data-width="'.$intwidth.'"';
	$block['width']=$ourwidth;

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

	// colorscheme - options[4]
	$form .=_MB_FBCOM_COLOR.": <input type='radio' name='options[4]' value='0' ";
	if(!$options[4]) $form .="checked='checked'"; 
	$form .=" />&nbsp;"._MB_FBCOM_LIGHT."&nbsp;<input type='radio' name='options[4]' value='1' ";
	if($options[4]) $form .="checked='checked'"; 
	$form .= " />&nbsp;"._MB_FBCOM_DARK."<br /><br />";
	
	// parameter list - options[5]
	$form .= _MB_FBCOM_WIDTH.": <input type='text' size='5' value='".$options[5]."' id='options[5]' name='options[5]' /><br /><br />";
	
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

	if(!$block=b_fbcomment_core_show($options)) return false;

	$i=4;
	
	// color scheme
	if($options[$i]) $ourcolor=' data-colorscheme="dark"';
	else $ourcolor='';
	$block['colorscheme']=$ourcolor;

	// width
	$i += 1;
	$intwidth=intval($options[$i]);
	if($intwidth < 1) $ourwidth="";
	else $ourwidth=' data-width="'.$intwidth.'"';
	$block['width']=$ourwidth;

	// number of posts
	$i += 1;
	$intposts=intval($options[$i]);
	if($intposts < 1) $intposts="10";
	$ourpost=" data-num-posts=\"$intposts\"";
	$block['numposts']=$ourpost;
	
	// show faces
	$i += 1;
	if($options[$i]) $ourfaces=' data-show-faces="true"';
	else $ourfaces=' data-show-faces="false"';
	$block['faces']=$ourfaces;

	// like or recommend
	$i += 1;
	if($options[$i]) $ouraction=' data-action="recommend"';
	else $ouraction='';
	$block['action']=$ouraction;

	// like layout
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
	
	// send button
	$i += 1;
	if($options[$i]) $oursend=' data-send="true"';
	else $oursend=' data-send="false"';
	$block['send']=$oursend;

	return $block;
}

function b_fbcomment_combo_edit($options) {

	$form = b_fbcomment_core_edit($options);

	$i=4;
	// comment portion
	// colorscheme
	$form .=_MB_FBCOM_COLOR.": <input type='radio' name='options[{$i}]' value='0' ";
	if(!$options[$i]) $form .="checked='checked'"; 
	$form .=" />&nbsp;"._MB_FBCOM_LIGHT."&nbsp;<input type='radio' name='options[{$i}]' value='1' ";
	if($options[$i]) $form .="checked='checked'"; 
	$form .= " />&nbsp;"._MB_FBCOM_DARK."<br /><br />";

	// width
	$i += 1;
	$form .= _MB_FBCOM_WIDTH.": <input type='text' size='5' value='".$options[$i]."' id='options[{$i}]' name='options[{$i}]' /><br /><br />";

	// number of comments to show
	$i += 1;
	$form .= _MB_FBCOM_NUM_POSTS.": <input type='text' size='6' value='".$options[$i]."' id='options[{$i}]' name='options[{$i}]' /><br /><br />";

	// like
	// show faces
	$i += 1;
	$form .=_MB_FBCOM_LIKE_FACES.": <input type='radio' name='options[{$i}]' value='1' ";
	if($options[$i]) $form .="checked='checked'"; 
	$form .=" />&nbsp;"._MB_FBCOM_YES."&nbsp;<input type='radio' name='options[{$i}]' value='0' ";
	if(!$options[$i]) $form .="checked='checked'"; 
	$form .= " />&nbsp;"._MB_FBCOM_NO."<br /><br />";
	
	// like or recommend
	$i += 1;
	$form .=_MB_FBCOM_LIKE_ACTION.": <input type='radio' name='options[{$i}]' value='0' ";
	if(!$options[$i]) $form .="checked='checked'"; 
	$form .=" />&nbsp;"._MB_FBCOM_LK_LIKE."&nbsp;<input type='radio' name='options[{$i}]' value='1' ";
	if($options[$i]) $form .="checked='checked'"; 
	$form .= " />&nbsp;"._MB_FBCOM_LK_RCMD."<br /><br />";

	// like button layout
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

//activity
function b_fbcomment_activity_show($options) {
	// set up the basics needed to use the sdk init
	// note we do not establish the open graph data for the page with this block. It isn't required for this plugin, so
	// one of the other blocks can do it, and we won't interfere.
	$dir = basename( dirname ( dirname( __FILE__ ) ) ) ;
	// Access module configs from block:
	$module_handler = xoops_gethandler('module');
	$module         = $module_handler->getByDirname($dir);
	$config_handler = xoops_gethandler('config');
	$moduleConfig   = $config_handler->getConfigsByCat(0, $module->getVar('mid'));

	$block['appid']=$moduleConfig['facebook-appid'];
	// channel for facebook js sdk, see: http://developers.facebook.com/blog/post/530/
	$block['channel'] = XOOPS_URL.'/modules/'.$dir.'/fbchannel.php?locale='._MB_FBCOM_SDK_CHANNEL_LOCALE;
	$block['locale'] = _MB_FBCOM_SDK_CHANNEL_LOCALE;

	$block['site']=(empty($options[0]))?'':' data-site="'.$options[0].'"';
	
	$block['action']=(empty($options[1])?'':' data-action="'.$options[1].'"');
	
	$value=intval($options[2]);
	if($value<50) $value=300;
	$block['width']=' data-width="'.$value.'"';
	
	$value=intval($options[3]);
	if($value<50) $value=300;
	$block['height']=' data-height="'.$value.'"';
	
	if(!$options[4]) $block['header']=' data-header="dark"';
	else $block['header']='';
	
	if($options[5]) $block['colorscheme']=' data-colorscheme="dark"';
	else $block['colorscheme']='';

	$block['font']=(empty($options[6])?'':' data-font="'.$options[6].'"');

	if($options[7]) $block['recommendations']=' data-recommendations="true"';
	else $block['recommendations']='';

	$block['filter']=(empty($options[8])?'':' data-filter="'.$options[8].'"');

	$block['linktarget']=(empty($options[9])?'':' data-linktarget="'.$options[9].'"');
	$block['ref']=(empty($options[10])?'':' data-ref="'.$options[10].'"');

	$value=intval($options[11]);
	if($value>180) $value=180;
	$block['max_age']=($options[10]<1)?'':' data-max_age="'.$value.'"';

	return $block;
}

function b_fbcomment_activity_edit($options) {
	$i=-1;
	$form='';

	$i += 1;
	$form .= _MB_FBCOM_ACTIVITY_DOMAIN.": <input type='text' value='".$options[$i]."' id='options[{$i}]' name='options[{$i}]' /><br /><br />";

	// action - a comma separated list of actions to show activities for.
	$i += 1;
	$form .= _MB_FBCOM_ACTIVITY_ACTIONS.": <input type='text' value='".$options[$i]."' id='options[{$i}]' name='options[{$i}]' /><br /><br />";

	// width - the width of the plugin in pixels. Default width: 300px.
	$i += 1;
	$form .= _MB_FBCOM_WIDTH.": <input type='text' size='5' value='".$options[$i]."' id='options[{$i}]' name='options[{$i}]' /><br /><br />";

	// height - the height of the plugin in pixels. Default height: 300px.
	$i += 1;
	$form .= _MB_FBCOM_HEIGHT.": <input type='text' size='5' value='".$options[$i]."' id='options[{$i}]' name='options[{$i}]' /><br /><br />";

	// header - specifies whether to show the Facebook header.
	$i += 1;
	$form .=_MB_FBCOM_SHOW_HEADER.": <input type='radio' name='options[{$i}]' value='1' ";
	if($options[$i]) $form .="checked='checked'"; 
	$form .=" />&nbsp;"._MB_FBCOM_YES."&nbsp;<input type='radio' name='options[{$i}]' value='0' ";
	if(!$options[$i]) $form .="checked='checked'"; 
	$form .= " />&nbsp;"._MB_FBCOM_NO."<br /><br />";

	// colorscheme - the color scheme for the plugin. Options: 'light', 'dark'
	$i += 1;
	$form .=_MB_FBCOM_COLOR.": <input type='radio' name='options[{$i}]' value='0' ";
	if(!$options[$i]) $form .="checked='checked'"; 
	$form .=" />&nbsp;"._MB_FBCOM_LIGHT."&nbsp;<input type='radio' name='options[{$i}]' value='1' ";
	if($options[$i]) $form .="checked='checked'"; 
	$form .= " />&nbsp;"._MB_FBCOM_DARK."<br /><br />";

	// font - the font to display in the plugin. Options: 'arial', 'lucida grande', 'segoe ui', 'tahoma', 'trebuchet ms', 'verdana'
	// _MB_FBCOM_FONT
	$i += 1;
	$values=array('', 'arial', 'lucida grande', 'segoe ui', 'tahoma', 'trebuchet ms', 'verdana');
	$form .= _MB_FBCOM_FONT . " <select id='options[{$i}]' name='options[{$i}]'>";
	foreach($values as $value) {
		$form.='<option value="'.$value.'"'.($options[$i]==$value?' selected':'').'>'.$value.'</option>';
	}
	$form .= '</select><br /><br />';

	
	// recommendations - specifies whether to always show recommendations in the plugin. If recommendations is set to true, the plugin will display recommendations in the bottom half.
	$i += 1;
	$form .=_MB_FBCOM_SHOW_RECOMMENDATIONS.": <input type='radio' name='options[{$i}]' value='1' ";
	if($options[$i]) $form .="checked='checked'"; 
	$form .=" />&nbsp;"._MB_FBCOM_YES."&nbsp;<input type='radio' name='options[{$i}]' value='0' ";
	if(!$options[$i]) $form .="checked='checked'"; 
	$form .= " />&nbsp;"._MB_FBCOM_NO."<br /><br />";

	// filter - allows you to filter which URLs are shown in the plugin. The plugin will only include URLs which contain the filter string in the first two path parameters of the URL. If nothing in the first two path parameters of the URL matches the filter, the URL will not be included. For example, if the 'site' parameter is set to 'www.example.com' and the 'filter' parameter was set to '/section1/section2' then only pages which matched 'http://www.example.com/section1/section2/*' would be included in the activity feed section of this plugin. The filter parameter does not apply to any recommendations which may appear in this plugin (see above); Recommendations are based only on 'site' parameter.
	$i += 1;
	$form .= _MB_FBCOM_ACTIVITY_FILTER.": <input type='text' value='".$options[$i]."' id='options[{$i}]' name='options[{$i}]' /><br /><br />";

	// linktarget - This specifies the context in which content links are opened. By default all links within the plugin will open a new window. If you want the content links to open in the same window, you can set this parameter to _top or _parent. Links to Facebook URLs will always open in a new window.
	$i += 1;
	$values=array('','_blank','_top','_parent');
	$form .= _MB_FBCOM_LINK_TARGET . " <select id='options[{$i}]' name='options[{$i}]'>";
	foreach($values as $value) {
		$form.='<option value="'.$value.'"'.($options[$i]==$value?' selected':'').'>'.$value.'</option>';
	}
	$form .= '</select><br /><br />';

	// ref - a label for tracking referrals; must be less than 50 characters and can contain alphanumeric characters and some punctuation (currently +/=-.:_). Specifying a value for the ref attribute adds the 'fb_ref' parameter to the any links back to your site which are clicked from within the plugin. Using different values for the ref parameter for different positions and configurations of this plugin within your pages allows you to track which instances are performing the best.
	$i += 1;
	$form .= _MB_FBCOM_REF_LABEL.": <input type='text' value='".$options[$i]."' id='options[{$i}]' name='options[{$i}]' /><br /><br />";

	// max_age - a limit on recommendation and creation time of articles that are surfaced in the plugins, the default is 0 (we don’t take age into account). Otherwise the valid values are 1-180, which specifies the number of days.
	$i += 1;
	$form .= _MB_FBCOM_ACTIVITY_AGE.": <input type='text' size='5' value='".$options[$i]."' id='options[{$i}]' name='options[{$i}]' /><br /><br />";

	return $form;
}

// Likebox
function b_fbcomment_likebox_show($options) {
	// set up the basics needed to use the sdk init
	// note we do not establish the open graph data for the page with this block. It isn't required for this plugin, so
	// one of the other blocks can do it, and we won't interfere.
	$dir = basename( dirname ( dirname( __FILE__ ) ) ) ;
	// Access module configs from block:
	$module_handler = xoops_gethandler('module');
	$module         = $module_handler->getByDirname($dir);
	$config_handler = xoops_gethandler('config');
	$moduleConfig   = $config_handler->getConfigsByCat(0, $module->getVar('mid'));

	$block['appid']=$moduleConfig['facebook-appid'];
	// channel for facebook js sdk, see: http://developers.facebook.com/blog/post/530/
	$block['channel'] = XOOPS_URL.'/modules/'.$dir.'/fbchannel.php?locale='._MB_FBCOM_SDK_CHANNEL_LOCALE;
	$block['locale'] = _MB_FBCOM_SDK_CHANNEL_LOCALE;

	// facebook page
	$block['href']=' data-href="'.$options[0].'"';
	
	$value=intval($options[1]);
	if($value<50) $value=300;
	$block['width']=' data-width="'.$value.'"';
	
	$value=intval($options[2]);
	if($value) $block['height']=' data-height="'.$value.'"';
	
	if($options[3]) $block['colorscheme']=' data-colorscheme="dark"';
	else $block['colorscheme']='';

	if($options[4]) $value=' data-show-faces="true"';
	else $value=' data-show-faces="false"';
	$block['faces']=$value;

	if($options[5]) $value=' data-stream="true"';
	else $value=' data-stream="false"';
	$block['stream']=$value;

	if($options[6]) $value=' data-header="true"';
	else $value=' data-header="false"';
	$block['header']=$value;

	$block['border']=(empty($options[7]))?'':' data-border-color="'.$options[7].'"';

	if($options[8]) $value=' data-force-wall="true"';
	else $value=' data-force-wall="false"';
	$block['forcewall']=$value;

	return $block;
}

function b_fbcomment_likebox_edit($options) {
	$i=-1;
	$form='';

	// url of facebook page
	$i += 1;
	$form .= _MB_FBCOM_LIKEBOX_PAGE_URL.": <input type='text' value='".$options[$i]."' id='options[{$i}]' name='options[{$i}]' /><br /><br />";

	// width - the width of the plugin in pixels. Default width: 300px.
	$i += 1;
	$form .= _MB_FBCOM_WIDTH.": <input type='text' size='5' value='".$options[$i]."' id='options[{$i}]' name='options[{$i}]' /><br /><br />";

	// height - the height of the plugin in pixels. Default height: 300px.
	$i += 1;
	$form .= _MB_FBCOM_HEIGHT.": <input type='text' size='5' value='".$options[$i]."' id='options[{$i}]' name='options[{$i}]' /><br /><br />";

	// colorscheme - the color scheme for the plugin. Options: 'light', 'dark'
	$i += 1;
	$form .=_MB_FBCOM_COLOR.": <input type='radio' name='options[{$i}]' value='0' ";
	if(!$options[$i]) $form .="checked='checked'"; 
	$form .=" />&nbsp;"._MB_FBCOM_LIGHT."&nbsp;<input type='radio' name='options[{$i}]' value='1' ";
	if($options[$i]) $form .="checked='checked'"; 
	$form .= " />&nbsp;"._MB_FBCOM_DARK."<br /><br />";

	// show faces
	$i += 1;
	$form .=_MB_FBCOM_LIKE_FACES.": <input type='radio' name='options[{$i}]' value='1' ";
	if($options[$i]) $form .="checked='checked'"; 
	$form .=" />&nbsp;"._MB_FBCOM_YES."&nbsp;<input type='radio' name='options[{$i}]' value='0' ";
	if(!$options[$i]) $form .="checked='checked'"; 
	$form .= " />&nbsp;"._MB_FBCOM_NO."<br /><br />";

	//show stream
	$i += 1;
	$form .=_MB_FBCOM_SHOW_STREAM.": <input type='radio' name='options[{$i}]' value='1' ";
	if($options[$i]) $form .="checked='checked'"; 
	$form .=" />&nbsp;"._MB_FBCOM_YES."&nbsp;<input type='radio' name='options[{$i}]' value='0' ";
	if(!$options[$i]) $form .="checked='checked'"; 
	$form .= " />&nbsp;"._MB_FBCOM_NO."<br /><br />";

	// header - specifies whether to show the Facebook header.
	$i += 1;
	$form .=_MB_FBCOM_SHOW_HEADER.": <input type='radio' name='options[{$i}]' value='1' ";
	if($options[$i]) $form .="checked='checked'"; 
	$form .=" />&nbsp;"._MB_FBCOM_YES."&nbsp;<input type='radio' name='options[{$i}]' value='0' ";
	if(!$options[$i]) $form .="checked='checked'"; 
	$form .= " />&nbsp;"._MB_FBCOM_NO."<br /><br />";

	// border_color
	$i += 1;
	$form .= _MB_FBCOM_BORDER_COLOR.": <input type='text' size='8' value='".$options[$i]."' id='options[{$i}]' name='options[{$i}]' /><br /><br />";

	// force_wall - for Places, specifies whether the stream contains posts from the Place's wall or just checkins from friends.
	$i += 1;
	$form .=_MB_FBCOM_FORCE_WALL.": <input type='radio' name='options[{$i}]' value='1' ";
	if($options[$i]) $form .="checked='checked'"; 
	$form .=" />&nbsp;"._MB_FBCOM_YES."&nbsp;<input type='radio' name='options[{$i}]' value='0' ";
	if(!$options[$i]) $form .="checked='checked'"; 
	$form .= " />&nbsp;"._MB_FBCOM_NO."<br /><br />";


	return $form;
}

function b_fbcomment_feed_post_show($options) {
	if(!$block=b_fbcomment_core_show($options)) return false;
	return $block;
}

function b_fbcomment_feed_post_edit($options) {
	$form = b_fbcomment_core_edit($options);
	return $form;
}

// include facebook php sdk
if(function_exists('curl_version')) {
	include_once XOOPS_ROOT_PATH.'/modules/fbcomment/include/facebook/facebook.php';
}

function b_fbcomment_show_feed_show($options) {
global $xoTheme;
	
	// set up the basics needed to use the sdk init
	// note we do not establish the open graph data for the page with this block.
	$dir = basename( dirname ( dirname( __FILE__ ) ) ) ;
	// Access module configs from block:
	$module_handler = xoops_gethandler('module');
	$module         = $module_handler->getByDirname($dir);
	$config_handler = xoops_gethandler('config');
	$moduleConfig   = $config_handler->getConfigsByCat(0, $module->getVar('mid'));

	$block['appid']=$moduleConfig['facebook-appid'];

	$config = array();
	$config['appId'] = $moduleConfig['facebook-appid'];
	$config['secret'] = $moduleConfig['facebook-appsecret'];
//	$config['fileUpload'] = false; // optional

	$facebook = new Facebook($config);

	$facebook_access_token = $facebook->getAccessToken();

	$id=$options[0];
	$limit=intval($options[1]);
	if($limit<1) $limit=1;

	try {
		$page = $facebook->api('/'.$id);
	} catch (FacebookApiException $e) {
		$result = $e->getResult();
		//echo '<pre>'.print_r($e).'</pre>';
		return false;
	}
	
	$block['page']=$page;
	$default_link=$page['link'];

/*
$ret = $facebook->api($path, $method, $params);

Name 	Description
path 	The Graph API path for the request, e.g. "/me" for the logged in user's profile.
method 	(optional) Specify the HTTP method for this request: 'GET', 'POST', or 'DELETE'.
params 	(optional) Parameters specific to the particular Graph API method you are calling. Passed in as an associative array of 'name' => 'value' pairs.
*/

// $response=file_get_contents("https://graph.facebook.com/".$id."/feed?access_token=".$facebook_access_token);
// $response_array=json_decode($response,true);

// http://graph.facebook.com/(user)/picture

	try {
		$feed = $facebook->api('/'.$id.'/feed','GET',array('access_token' => $facebook_access_token, 'limit' => $limit));
	} catch (FacebookApiException $e) {
		//echo '<pre>'; print_r($e); echo '</pre>';
		return false;
	}
	
	if(empty($feed['data'])) return false;
	$data=$feed['data'];

	foreach($data as $i=>$item) {
		$data[$i]['display_time']=date('Y-m-d', strtotime($data[$i]['created_time']));
		try {
			$plink = $facebook->api( array(
				'method' => 'fql.query',
				'query' => 'select permalink from stream where post_id="'.$item['id'].'"',
				));
		} catch (FacebookApiException $e) {
			$plink=array();
			$plink[0]['permalink']=$default_link;
		}
		$data[$i]['permalink']=$plink[0]['permalink'];
		if(empty($data[$i]['permalink'])) $data[$i]['permalink']=$default_link;
	}
	$block['feed']=$data;
	
	$block['style']="<style>\n".file_get_contents(XOOPS_ROOT_PATH.'/modules/fbcomment/showfeed.css')."\n</style>\n";
	//$xoTheme->addStyleSheet(XOOPS_URL.'/modules/fbcomment/showfeed.css');

	return $block;
 
}

function b_fbcomment_show_feed_edit($options) {

	$form='';
	if(!function_exists('curl_version')) {
		$form .= '<br /><br /><span style="color:red; font-weight: bold;">'._MB_FBCOM_CURL_REQUIRED.'</span><br /><br />';
	}

	// id - facebook id of feed to be shown
	$form .= _MB_FBCOM_SHOW_FEED_ID.": <input type='text' value='".$options[0]."' id='options[0]' name='options[0]' /><br /><br />";

	// limit - max number of posts to show
	$form .= _MB_FBCOM_SHOW_FEED_MAX.": <input type='text' size='5' value='".$options[1]."' id='options[1]' name='options[1]' /><br /><br />";

	return $form;
}
?>
