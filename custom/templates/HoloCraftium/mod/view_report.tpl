{include file='navbar.tpl'}

<div class="container" style="padding-top: 5rem;">
  <div class="row">
	<div class="col-md-3">
	  {include file='mod/navigation.tpl'}
	</div>
	<div class="col-md-9">
	  <div class="card">
		<div class="card-block">
		  <h2 class="card-title" style="display:inline;">{$REPORTS}</h2>
		  <span class="pull-right">
		    <a href="{$REPORTS_LINK}" class="btn btn-info">{$BACK}</a>
		  </span>
		  
		  <br /><br />
		  <h4 style="display:inline;">{$VIEWING_REPORT} &raquo; <a target="_blank" href="{$REPORTED_USER_PROFILE}" style="{$REPORTED_USER_STYLE}">{$REPORTED_USER}</a> {if ($TYPE == 0)}| <small><a href="{$CONTENT_LINK}" target="_blank">{$VIEW_CONTENT}</a></small>{/if}</h4>
		  <hr />
		  
		  {if ($ERROR)}
		    <div class="alert alert-danger">
			  {$ERROR}
			</div>
		  {/if}
		  
		  <div class="panel panel-primary">
		    <div class="panel-heading">
			  <a href="{$REPORTER_USER_PROFILE}" target="_blank" class="white-text">{$REPORTER_USER}</a>:
			  <span class="pull-right" data-toggle="tooltip" data-original-title="{$REPORT_DATE}">{$REPORT_DATE_FRIENDLY}</span>
			</div>
		    <div class="panel-body">
			  {$REPORT_CONTENT}
			</div>
		  </div>
		  
		  <h4>{$COMMENTS_TEXT}</h4>
		  
		  {if count($COMMENTS)}
		    {foreach from=$COMMENTS item=comment}
			  <div class="panel panel-primary">
			    <div class="panel-heading">
			      <a href="{$comment.profile}" target="_blank" class="white-text">{$comment.username}</a>:
			      <span class="pull-right" data-toggle="tooltip" data-original-title="{$comment.date}">{$comment.date_friendly}</span>
				</div>
				<div class="panel-body">
				  {$comment.content}
				</div>
			  </div>
			{/foreach}
		  {else}
		    {$NO_COMMENTS}
		  {/if}
		  
		  <hr />
		  
		  <form action="" method="post">
		    <div class="form-group">
		      <textarea class="form-control" name="content" rows="5" placeholder="{$NEW_COMMENT}"></textarea>
			</div>
			<div class="form-group">
			  <input type="hidden" name="token" value="{$TOKEN}">
			  <input type="submit" value="{$SUBMIT}" class="btn btn-primary">
			  <span class="pull-right">
			    {if isset($CLOSE_REPORT)}
				  <a href="{$CLOSE_LINK}" class="btn btn-danger">{$CLOSE_REPORT}</a>
				{else}
				  <a href="{$REOPEN_LINK}" class="btn btn-danger">{$REOPEN_REPORT}</a>
				{/if}
			  </span>
			</div>
		  </form>
		  
		</div>
	  </div>
	</div>
  </div>
</div>

{include file='footer.tpl'}