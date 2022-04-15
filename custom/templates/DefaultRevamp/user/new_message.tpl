{extends 'user/layout.tpl'}

{block 'userErrors'}
    {if isset($ERROR)}
		<div class="ui error icon message">
			<i class="x icon"></i>
			<div class="content">
				<div class="header">{$ERROR_TITLE}</div>
                {$ERROR}
			</div>
		</div>
    {/if}
{/block}

{block 'userContent'}
	<div class="ui segment">
		<h3 class="ui header">{$NEW_MESSAGE}</h3>
		<form class="ui form" action="" method="post" id="form-new-message">
			<div class="field">
				<label for="inputTitle">{$MESSAGE_TITLE}</label>
				<input type="text" name="title" id="inputTitle" placeholder="{$MESSAGE_TITLE}" value="{$MESSAGE_TITLE_VALUE}">
			</div>
			<div class="field">
				<label for="InputTo">{$TO}</label>
				<div class="ui fluid multiple search selection dropdown">
					<input name="to" id="InputTo" type="hidden" {if isset($TO_USER)}value="{$TO_USER}"{/if}>
					<i class="dropdown icon"></i>
					<div class="default text">{$TO}</div>
					<div class="menu">
                        {foreach from=","|explode:$ALL_USERS|replace:'"':'' item="item"}
							<div class="item" data-value="{$item}">{$item}</div>
                        {/foreach}
					</div>
				</div>
			</div>
			<div class="field">
				<textarea name="content" id="reply"></textarea>
			</div>
			<input type="hidden" name="token" value="{$TOKEN}">
			<input type="submit" class="ui primary button" value="{$SUBMIT}">
		</form>
	</div>
{/block}