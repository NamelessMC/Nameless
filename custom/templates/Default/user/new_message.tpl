{include file='navbar.tpl'}

<div class="container" style="padding-top: 5rem;">
  <div class="row">
	<div class="col-md-3">
	  {include file='user/navigation.tpl'}
	</div>
	<div class="col-md-9">
	  <div class="card">
		<div class="card-block">
		  <h2 class="card-title" style="display:inline;">{$NEW_MESSAGE}</h2>
		  <span class="pull-right"><a href="{$CANCEL_LINK}" onclick="return confirm('{$CONFIRM_CANCEL}');" class="btn btn-danger">{$CANCEL}</a></span>
		  <br /><br />
		  
		  {if isset($ERROR)}
		  <div class="alert alert-danger">
		    {$ERROR}
		  </div>
		  {/if}
		  
		  <form action="" method="post">
		    <div class="form-group">
			  <label for="inputTitle">{$MESSAGE_TITLE}</label>
			  <input type="text" id="inputTitle" class="form-control" name="title" placeholder="{$MESSAGE_TITLE}" value="{$MESSAGE_TITLE_VALUE}">
			</div>
			
			<div class="form-group">
			  <label for="InputTo">{$TO} <small><em>{$SEPARATE_USERS_WITH_COMMAS}</em></small></label>
			  <input class="form-control" type="text" id="InputTo" name="to" {if isset($TO_USER)}value="{$TO_USER}"{/if}data-provide="typeahead" data-items="4" data-source='[{$ALL_USERS}]'>
			</div>
			
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