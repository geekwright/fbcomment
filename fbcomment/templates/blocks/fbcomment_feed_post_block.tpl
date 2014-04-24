<{include file="db:blocks/fbcomment_common_head.tpl"}>
<script>
function postToFeed() {
        var obj = {
          method: 'feed',
          redirect_uri: '<{xoAppUrl /modules/fbcomment/bye.html}>',
          link: '<{$block.ogurl}>',
          picture: '<{$block.ogimage}>',
          name: '<{$block.ogtitle}>',
          caption: '<{$block.ogsite_name}>',
          description: '<{$block.ogdescription}>'
	};

        function callback(response) {
		if (response && response.post_id) {
			document.getElementById('fbc_posttofeed_msg').innerHTML = "<{$smarty.const._MB_FBCOM_POST_OK}>";
		} else {
			document.getElementById('fbc_posttofeed_msg').innerHTML = "<{$smarty.const._MB_FBCOM_POST_ERROR}>";
			console.log(response);
		}
	}

	FB.ui(obj, callback);
	return false;
}
</script>
<div class="fbc_posttofeed"><img src="<{xoAppUrl /modules/fbcomment/assets/images/icon_small.png}>" /> <a href="javascript:void(0);" onclick="return postToFeed();" title="<{$smarty.const._MB_FBCOM_POST_TO_FEED}>"><{$smarty.const._MB_FBCOM_POST_TO_FEED}></a> <span id="fbc_posttofeed_msg"> </span></div>
<{include file="db:blocks/fbcomment_common_tail.tpl"}>
