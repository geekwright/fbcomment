<{$block.style}>
<div class="fbc_showfeed">
<div class="fbc_showfeed_page">
<a href="<{$block.page.link}>" title="<{$block.page.name}>"  target="_blank" ><img src="//graph.facebook.com/<{$block.page.id}>/picture"  alt="<{$block.page.name}>" title="<{$block.page.name}>" /> <span class="fbc_showfeed_pagename"><{$block.page.name}></span></a>
<br /><div class="fbc_showfeed_subtitle"><{$block.page.about|truncate:100}></div>
</div>
<div class="fbc_showfeed_feed">
<{foreach key=id item=post from=$block.feed}>
<{if isset($post.picture)}><a href="<{$post.permalink}>" target="_blank" ><img class="fbc_showfeed_pic" src="<{$post.picture}>" /></a><{/if}>
<a href="<{$post.permalink}>" target="_blank" ><img class="fbc_showfeed_poster_icon" src="//graph.facebook.com/<{$post.from.id}>/picture"  alt="<{$post.from.name}>" title="<{$post.from.name}>" />
<span class="fbc_showfeed_postname"><{$post.from.name}></span></a> <span class="fbc_showfeed_date"><{$post.display_time}></span>
<a href="<{$post.permalink}>" target="_blank" ><div class="fbc_showfeed_post"><br /><{$post.story|truncate:200}><{$post.message|truncate:200}></a></div>
<hr />
<{/foreach}>
</div>
</div>

