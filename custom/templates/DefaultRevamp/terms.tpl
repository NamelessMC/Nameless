{extends 'layouts/default.tpl'}

{block 'body'}
	<div class="ui container">
		<h2 class="ui header">
            {$TERMS}
		</h2>

		<div class="ui padded segment" id="terms">
            {$SITE_TERMS}
		</div>
	</div>
{/block}