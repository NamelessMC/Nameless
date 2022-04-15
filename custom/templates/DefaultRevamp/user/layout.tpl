{extends 'layouts/default.tpl'}

{block 'body'}
	<div class="ui container">
		<h2 class="ui header">
            {$TITLE}
		</h2>

        {block 'userErrors'}{/block}

		<div class="ui stackable grid" id="alerts">
			<div class="ui centered row">
				<div class="ui six wide tablet four wide computer column">
                    {include file='user/navigation.tpl'}
				</div>
				<div class="ui ten wide tablet twelve wide computer column">
					{block "userContent"}{/block}
				</div>
			</div>
		</div>
	</div>
{/block}
