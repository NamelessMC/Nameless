{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
  {$TITLE}
</h2>

{if isset($ERROR)}
  <div class="ui error icon message">
    <i class="x icon"></i>
    <div class="content">
      <div class="header">{$ERROR_TITLE}</div>
      {$ERROR}
    </div>
  </div>
{/if}

<div class="ui stackable grid" id="new-message">
  <div class="ui centered row">
    <div class="ui six wide tablet four wide computer column">
	  {include file='user/navigation.tpl'}
    </div>
    <div class="ui ten wide tablet twelve wide computer column">
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
 			 <input name="to" id="InputTo" type="hidden">
 			 <i class="dropdown icon"></i>
 			 <div class="default text">{$TO}</div>
 			 <div class="menu">
 			   {foreach from=","|explode:$ALL_USERS|replace:'"':'' item="item"}
 			     <div class="item" data-value="{$item}">{$item}</div>
 			   {/foreach}
 			 </div>
			</div>
		  </div>
		  {if isset($MARKDOWN)}
			<div class="field">
			  <textarea name="content" id="markdown">{$CONTENT}</textarea>
			</div>
		  {else}
			<div class="field">
			  <textarea name="content" id="reply">{$CONTENT}</textarea>
			</div>
		  {/if}
		  <input type="hidden" name="token" value="{$TOKEN}">
		  <input type="submit" class="ui primary button" value="{$SUBMIT}">
		</form>
      </div>
    </div>
  </div>
</div>
			
{include file='footer.tpl'}