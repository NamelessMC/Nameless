{extends 'layouts/default.tpl'}

{block 'body'}
	<div class="ui container">
		<h2 class="ui header">
            {$COOKIE_NOTICE_HEADER}
		</h2>

		<div class="ui padded segment" id="cookies">
            {$COOKIE_NOTICE}

			<div class="ui divider"></div>
			<div class="ui blue button" onclick="configureCookies()">{$UPDATE_SETTINGS}</div>
		</div>
	</div>
{/block}