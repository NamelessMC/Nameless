{include file='navbar.tpl'}

<div class="container" style="padding-top: 5rem;">
  <div class="row">
	<div class="col-md-3">
	  {include file='user/navigation.tpl'}
	</div>
	<div class="col-md-9">
	  <div class="card">
		<div class="card-block">
		  <h2 class="card-title" style="display:inline;">{$MESSAGE_TITLE}</h2>
		  <span class="pull-right">
		    <a href="{$BACK_LINK}" class="btn btn-warning">{$BACK}</a>
			<a href="{$LEAVE_CONVERSATION_LINK}" class="btn btn-danger" onclick="return confirm('{$CONFIRM_LEAVE}');">{$LEAVE_CONVERSATION}</a>
		  </span>

		  <br />
		  {$PARTICIPANTS_TEXT}: {$PARTICIPANTS}
		  
		  <br /><br />
		  
		  {if isset($ERROR)}
		  <div class="alert alert-danger">
		    {$ERROR}
		  </div>
		  {/if}
		  
		  {foreach from=$MESSAGES item=message}
		  <div class="card">
		    <div class="card-block">
			  <div class="row">
			    <div class="col-md-3">
				  <center>
				    <img class="img-rounded" style="width:100px; height:100px;" src="{$message.author_avatar}" alt="{$message.author_username}" />
				    <br /><br />
				    <strong><a style="{$message.author_style}" href="{$message.author_profile}">{$message.author_username}</a></strong>
				    <br />
				    {$message.author_group}
				  </center>
				</div>
				<div class="col-md-9">
				  <div class="forum_post">
				    {$message.content}
				  </div>
				  <hr />
				  <span data-toggle="tooltip" data-trigger="hover" data-original-title="{$message.message_date_full}">{$message.message_date}</span>
				</div>
			  </div>
			</div>
		  </div>
		  {/foreach}
		  
		  {$PAGINATION}
		  
		  <form action="" method="post">
		    <h4>{$NEW_REPLY}</h4>
			{if !isset($MARKDOWN)}
			<div class="form-group">
			  <textarea style="width:100%" name="content" id="reply" rows="15">{$CONTENT}</textarea>
			</div>
			{else}
			<div class="form-group">
			  <textarea class="form-control" style="width:100%" id="markdown" name="content" rows="15">{$CONTENT}</textarea>
			  <span class="pull-right"><i data-toggle="popover" data-placement="top" data-html="true" data-content="{$MARKDOWN_HELP}" class="fa fa-question-circle text-info" aria-hidden="true"></i></span>
			</div>
			{/if}
		
			<div class="form-group">
			  <input type="hidden" name="token" value="{$TOKEN}">
			  <input type="submit" name="{$SUBMIT}" class="btn btn-primary">
			</div>
		  </form>
		  
		</div>
	  </div>
	</div>
  </div>
</div>

{include file='footer.tpl'}