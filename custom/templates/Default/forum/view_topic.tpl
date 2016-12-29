{include file='navbar.tpl'}

<div class="container" style="padding-top: 5rem;">
  <ol class="breadcrumb">
	{foreach from=$BREADCRUMBS item=breadcrumb}
	<li{if isset($breadcrumb.active)} class="active"{/if}>{if !isset($breadcrumb.active)}<a href="{$breadcrumb.link}">{/if}{$breadcrumb.forum_title}{if !isset($breadcrumb.active)}</a>{/if}</li>
	{/foreach}
  </ol>
  
  {if isset($SESSION_SUCCESS_POST)}
  <div class="alert alert-success">
    {$SESSION_SUCCESS_POST}
  </div>
  {/if}
  
  {if isset($SESSION_FAILURE_POST)}
  <div class="alert alert-danger">
    {$SESSION_FAILURE_POST}
  </div>
  {/if}

  {if isset($CAN_REPLY)}
  <a{if isset($LOCKED) && !isset($CAN_MODERATE)} disabled="disabled"{else} href="#reply_section"{/if} class="btn btn-{if isset($LOCKED) && !isset($CAN_MODERATE)}warning disabled{else}primary{/if}">{if isset($LOCKED) && !isset($CAN_MODERATE)}<i class="fa fa-lock" aria-hidden="true"></i> {/if}{$NEW_REPLY}</a>
  {/if}
  
  <span class="pull-right">
    {if isset($CAN_MODERATE)}
    <div class="btn-group">
	  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">{$MOD_ACTIONS} <span class="caret"></span></button>
	  <ul class="dropdown-menu" role="menu">
		<li><a class="dropdown-item" href="{$LOCK_URL}">{$LOCK}</a></li>
		<li><a class="dropdown-item" href="{$MERGE_URL}">{$MERGE}</a></li>
		<li><a class="dropdown-item" href="" data-toggle="modal" data-target="#deleteModal">{$DELETE}</a></li>
		<li><a class="dropdown-item" href="{$MOVE_URL}">{$MOVE}</a></li>
		<li><a class="dropdown-item" href="{$STICK_URL}">{$STICK}</a></li>
	  </ul>
	</div>
    {/if}
  
    <div class="btn-group">
	  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">{$SHARE} <span class="caret"></span></button>
	  <ul class="dropdown-menu" role="menu">
		<li><a target="_blank" class="dropdown-item" href="{$SHARE_TWITTER_URL}">{$SHARE_TWITTER}</a></li>
		<li><a target="_blank" class="dropdown-item" href="{$SHARE_FACEBOOK_URL}">{$SHARE_FACEBOOK}</a></li>
	  </ul>
	</div>
	
  </span>
  
  <br />
  
  {$PAGINATION}
  
  {foreach from=$REPLIES item=reply name=arr}
    <div class="panel panel-primary">
	  <div class="panel-heading">
	    <a href="{$reply.url}" class="white-text">{if isset($LOCKED) && $smarty.foreach.arr.first}<span class="fa fa-lock"></span> {/if}{$reply.heading}</a>
	  </div>
	  <div class="panel-body" id="post-{$reply.id}">
	    <div class="row">
		  <div class="col-md-3">
		    <center>
			  <img class="img-rounded" style="width:100px; height:100px;" src="{$reply.avatar}" />
			  <br /><br />
			  <strong><a style="{$reply.user_style}" href="{$reply.profile}">{$reply.username}</a></strong>
			  <br />
			  {$reply.user_group}
			  {if $reply.user_title}
			    <br />
			    <small>{$reply.user_title}</small>
			  {/if}
			  <hr />
			  {$reply.user_posts_count} {$POSTS}<br />
			  <hr />
			</center>
			{if count($reply.fields)}
			<blockquote class="blockquote">
			  {foreach from=$reply.fields item=field}
			  <small>{$field.name}: {$field.value}</small><br />
			  {/foreach}
			</blockquote>
			{/if}
		  </div>
		  <div class="col-md-9">
		    {$BY} <a style="{$reply.user_style}" href="{$reply.profile}">{$reply.username}</a> &raquo; <span data-toggle="tooltip" data-trigger="hover" data-original-title="{$reply.post_date}">{$reply.post_date_rough}</span>
			
			<span class="pull-right">
			  {if isset($reply.buttons.spam)}
			  <button class="btn btn-danger btn-sm" rel="tooltip" data-trigger="hover" data-original-title="{$reply.buttons.spam.TEXT}" data-toggle="modal" data-target="#spam{$reply.id}Modal"><i class="fa fa-flag fa-fw" aria-hidden="true"></i></button>
			  {/if}
			
			  {if isset($reply.buttons.edit)}
			  <a class="btn btn-secondary btn-sm" data-toggle="tooltip" data-trigger="hover" data-original-title="{$reply.buttons.edit.TEXT}" href="{$reply.buttons.edit.URL}"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i></a>
			  {/if}
			  
			  {if isset($reply.buttons.delete)}
			  <button class="btn btn-danger btn-sm" rel="tooltip" data-trigger="hover" data-original-title="{$reply.buttons.delete.TEXT}" data-toggle="modal" data-target="#delete{$reply.id}Modal"><i class="fa fa-trash fa-fw" aria-hidden="true"></i></button>
			  {/if}
			  
			  {if isset($reply.buttons.report)}
			  <button class="btn btn-warning btn-sm" rel="tooltip" data-trigger="hover" data-original-title="{$reply.buttons.report.TEXT}" data-toggle="modal" data-target="#report{$reply.id}Modal"><i class="fa fa-exclamation-triangle fa-fw" aria-hidden="true"></i></button>
			  {/if}
			  
			  {if isset($reply.buttons.quote)}
			  <button class="btn btn-info btn-sm" data-toggle="tooltip" data-trigger="hover" data-original-title="{$reply.buttons.quote.TEXT}" onclick="quote({$reply.id});"><i class="fa fa-quote-left fa-fw" aria-hidden="true"></i></button>
			  {/if}
			</span>
			
			<hr />
			
			<div class="forum_post">
			  {$reply.content}
			</div>
			<br /><br /><br />
			{if $reply.edited !== null}<small><span rel="tooltip" data-toggle="hover" data-original-title="{$reply.edited_full}">{$reply.edited}</span></small>{/if}
			{if count($reply.post_reactions)}
			<span class="pull-right" data-toggle="modal" data-target="#reactions{$reply.id}Modal">
			  {foreach from=$reply.post_reactions name="reactions" item=reaction}
			    {$reaction.html} x {$reaction.count}
				{if !($smarty.foreach.reactions.last)} | {/if}
			  {/foreach}
			</span>
			{/if}
			
			{if $reply.user_id !== $USER_ID}
			  {if isset($REACTIONS) && count($REACTIONS)}
			<br />
			<div class="well">
			  {foreach from=$REACTIONS item=reaction}
			    <form style="display:inline;" action="{$REACTIONS_URL}" method="post">
				  <input type="hidden" name="token" value="{$TOKEN}">
				  <input type="hidden" name="reaction" value="{$reaction->id}">
				  <input type="hidden" name="post" value="{$reply.id}">
			      <a href="#" onclick="$(this).closest('form').submit();" style="padding:10px;" rel="tooltip" data-toggle="hover" data-original-title="{$reaction->name}">{$reaction->html}</a>
				</form>
			  {/foreach}
			</div>
			  {else}
			  <br />
			  {/if}
			{else}
			<br />
			{/if}
			<hr />
			{$reply.signature}
		  </div>
		</div>
	  </div>
	</div>
	
	{if count($reply.post_reactions)}
	<!-- Reactions modal -->
	<div class="modal fade" id="reactions{$reply.id}Modal" tabindex="-1" role="dialog" aria-labelledby="reactions{$reply.id}ModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
			<h4 class="modal-title" id="reactions{$reply.id}ModalLabel">{$REACTIONS_TEXT}</h4>
		  </div>
		  <div class="modal-body">
			{foreach from=$reply.post_reactions name=reactions item=reaction}
			  <strong>{$reaction.html} x {$reaction.count}:</strong><br />
			  {foreach from=$reaction.users item=user}
			    <a style="{$user.style}" href="{$user.profile}"><img src="{$user.avatar}" class="img-rounded" style="height:25px;width:25px;" alt="{$user.username}" /> {$user.nickname}</a><br />
			  {/foreach}
			  {if !($smarty.foreach.reactions.last)}<hr />{/if}
			{/foreach}
		  </div>
		</div>
	  </div>
	</div>
	{/if}
	
	{if isset($reply.buttons.report)}
	<!-- Post report modal -->
	<div class="modal fade" id="report{$reply.id}Modal" tabindex="-1" role="dialog" aria-labelledby="report{$reply.id}ModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
			<h4 class="modal-title" id="report{$reply.id}ModalLabel">{$reply.buttons.report.TEXT}</h4>
		  </div>
		  <form action="{$reply.buttons.report.URL}" method="post">
		    <div class="modal-body">
			  <div class="form-group">
			    <label for="InputReason">{$reply.buttons.report.REPORT_TEXT}</label>
			    <textarea class="form-control" id="InputReason" name="reason"></textarea>
			  </div>
		    </div>
		    <div class="modal-footer">
			  <button type="button" class="btn btn-warning" data-dismiss="modal">{$CANCEL}</button>
			  <input type="hidden" name="post" value="{$reply.id}">
			  <input type="hidden" name="topic" value="{$TOPIC_ID}">
			  <input type="hidden" name="token" value="{$TOKEN}">
			  <input type="submit" class="btn btn-danger" value="{$reply.buttons.report.TEXT}">
		    </div>
		  </form>
		</div>
	  </div>
	</div>
	{/if}
	
	{if isset($CAN_MODERATE)}
	<!-- Post spam modal -->
	<div class="modal fade" id="spam{$reply.id}Modal" tabindex="-1" role="dialog" aria-labelledby="spam{$reply.id}ModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
			<h4 class="modal-title" id="spam{$reply.id}ModalLabel">{$MARK_AS_SPAM}</h4>
		  </div>
		  <div class="modal-body">
			{$CONFIRM_SPAM_POST}
		  </div>
		  <div class="modal-footer">
		    <form action="{$reply.buttons.spam.URL}" method="post">
			  <button type="button" class="btn btn-warning" data-dismiss="modal">{$CANCEL}</button>
			  <input type="hidden" name="post" value="{$reply.id}">
			  <input type="hidden" name="token" value="{$TOKEN}">
			  <input type="submit" class="btn btn-danger" value="{$MARK_AS_SPAM}">
			</form>
		  </div>
		</div>
	  </div>
	</div>
	
	<!-- Post deletion modal -->
	<div class="modal fade" id="delete{$reply.id}Modal" tabindex="-1" role="dialog" aria-labelledby="delete{$reply.id}ModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
			<h4 class="modal-title" id="delete{$reply.id}ModalLabel">{$CONFIRM_DELETE_SHORT}</h4>
		  </div>
		  <div class="modal-body">
			{$CONFIRM_DELETE_POST}
		  </div>
		  <div class="modal-footer">
		    <form action="{$reply.buttons.delete.URL}" method="post">
			  <button type="button" class="btn btn-warning" data-dismiss="modal">{$CANCEL}</button>
			  <input type="hidden" name="tid" value="{$TOPIC_ID}">
			  <input type="hidden" name="number" value="{$reply.buttons.delete.NUMBER}">
			  <input type="hidden" name="pid" value="{$reply.id}">
			  <input type="hidden" name="token" value="{$TOKEN}">
			  <input type="submit" class="btn btn-danger" value="{$reply.buttons.delete.TEXT}">
			</form>
		  </div>
		</div>
	  </div>
	</div>
	{/if}
	
  {/foreach}
  
  {$PAGINATION}
  
  {if isset($TOPIC_LOCKED_NOTICE)}
    <div class="alert alert-info">{$TOPIC_LOCKED_NOTICE}</div>
  {/if}
  
  {if isset($CAN_REPLY)}
  <div id="reply_section">
    <h3>{$NEW_REPLY}</h3>
    <form action="" method="post">
	  {if !isset($MARKDOWN)}
	  <textarea style="width:100%" name="content" id="quickreply" rows="15">{$CONTENT}</textarea>
	  {else}
	  <div class="form-group">
		<textarea class="form-control" style="width:100%" id="markdown" name="content" rows="20">{$CONTENT}</textarea>
		<span class="pull-right"><i data-toggle="popover" data-placement="top" data-html="true" data-content="{$MARKDOWN_HELP}" class="fa fa-question-circle text-info" aria-hidden="true"></i></span>
	  </div>
	  {/if}
	  <br />
	  <input type="hidden" name="token" value="{$TOKEN}">
	  <button type="submit" class="btn btn-primary">{$SUBMIT}</button>
	  <button type="button" class="btn btn-info" id="quoteButton" onclick="insertQuotes();">Insert Quotes</button>
    </form>
  </div>
  {/if}
  
</div>

<br />

{include file='footer.tpl'}

{if isset($CAN_MODERATE)}
<!-- Deletion modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="deleteModalLabel">{$CONFIRM_DELETE_SHORT}</h4>
      </div>
      <div class="modal-body">
        {$CONFIRM_DELETE}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">{$CANCEL}</button>
        <a href="{$DELETE_URL}" class="btn btn-danger">{$DELETE}</a>
      </div>
    </div>
  </div>
</div>
{/if}