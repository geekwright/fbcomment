<script>
if (!(document.getElementById("fb-root"))) {
  document.write('<div id="fb-root"></div>');

  window.fbAsyncInit = function() {
    // init the FB JS SDK
    FB.init({
<{if !empty($block.appid)}>      appId      :  '<{$block.appid}>',<{/if}>  // App ID from the App Dashboard
      channelUrl : '<{$block.channel}>', // Channel File for x-domain communication
      status     : false, // check the login status upon init?
      cookie     : false, // set sessions cookies to allow your server to access the session?
      xfbml: true, // enable XFBML and social plugins
      oauth: false, // enable OAuth 2.0
      logging: true
    });

    // Additional initialization code such as adding Event Listeners goes here
    FB.Event.subscribe('comment.create',
      function(response) {
	console.log(response.href);
	var xhr = new XMLHttpRequest();
	xhr.open("POST", '<{$xoops_url}>/modules/fbcomment/recordcomment.php', true);
	xhr.setRequestHeader("X_OGURL", response.href);
	xhr.setRequestHeader("X_OGCOMMENT", response.commentID);
	xhr.send();
      } );

    FB.Event.subscribe('edge.create',
      function(response) {
	console.log(response);
	var xhr = new XMLHttpRequest();
	xhr.open("POST", '<{$xoops_url}>/modules/fbcomment/recordlike.php', true);
	xhr.setRequestHeader("X_OGURL", response);
	xhr.send();
      } );

  };

  // Load the SDK's source Asynchronously
  (function(d){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     ref.parentNode.insertBefore(js, ref);
   }(document));
};
</script>

<!-- open graph metatdata edit button start -->
<{if !empty($block.showogmetaedit)}>
<{if !empty($block.showogmetaform)}>
<!-- edit form - only included once -->
<div id="fbc_dd_dialogform">
<div class="fbc_dd_dialog_title"><{$smarty.const._MB_FBCOM_OGEDIT_TITLE}></div>
<div style="margin: 2em;">
<form id="fbc_dd_upload" action="<{$block.formaction}>" method="POST" enctype="multipart/form-data">
<input type="hidden" id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" value="3000000" />
<input type="hidden" id="X_OGURL" name="X_OGURL" value="<{$block.ogurlenc}>" />
<div>
<h2><{$smarty.const._MB_FBCOM_OGEDIT_OGIMG}></h2>
<div id="fbc_dd_nofiledrag"><{if !empty($block.ogimage)}><img class="fbc_dd_image" src="<{$block.ogimage}>" /><{/if}></div>
<{$smarty.const._MB_FBCOM_OGEDIT_FILE}><input type="file" id="fbc_dd_fileselect" name="fileselect[]" />
<div id="fbc_dd_progress"></div>
<div id="fbc_dd_filedrag">
<{if !empty($block.ogimage)}><img class="fbc_dd_image" src="<{$block.ogimage}>" /><{/if}>
<br /><{$smarty.const._MB_FBCOM_OGEDIT_DROPHERE}>
</div>
<h2><{$smarty.const._MB_FBCOM_OGEDIT_OGTITLE}></h2>
<input type="text" id="fbc_dd_ogtitle" name="fbc_dd_ogtitle" size="40" value="<{$block.ogtitle}>" /> <a href="javascript:getTitle('fbc_dd_ogtitle')" class="fbc_dd_grabbutton"><{$smarty.const._MB_FBCOM_OGEDIT_GRAB}></a>
<h2><{$smarty.const._MB_FBCOM_OGEDIT_OGDESC}></h2>
<textarea id="fbc_dd_ogdesc" name="fbc_dd_ogdesc" rows="4" cols="40"><{$block.ogdescription}></textarea> <a href="javascript:getDescription('fbc_dd_ogdesc')" class="fbc_dd_grabbutton"><{$smarty.const._MB_FBCOM_OGEDIT_GRAB}></a>
</div>
<div id="fbc_dd_submitbutton">
<button type="submit"><{$smarty.const._MB_FBCOM_OGEDIT_UPDATE}></button>
</div>
</form>
<div class="fbc_dd_showhide"><a id="displayText" href="javascript:hideDiv('fbc_dd_dialogform');"><{$smarty.const._MB_FBCOM_OGEDIT_HIDE_EDITOR}></a></div>
<div class="fbc_dd_url"><strong>URL: </strong> <a href="<{$block.ogurl}>" target="_blank"><{$block.ogurl}></a> <a href="http://developers.facebook.com/tools/lint?url=<{$block.ogurlenc}>" target="_blank"><{$smarty.const._MB_FBCOM_OGEDIT_DEBUGGER}></a></div>
</div>
</div>
<script src="<{$block.formddscript}>"></script>
<{/if}>
<{/if}>

