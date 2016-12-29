{include file='navbar.tpl'}

<div class="container" style="padding-top:5rem;">
<div class="card">
  <div class="card-block">
	<div class="container">
	  <h3>{$CREATING_TOPIC_IN}</h3>
	  
	  {if isset($ERROR)}
	  <div class="alert alert-danger">
	    {foreach from=$ERROR item=item name=arr}
		  {$item}<br />
		{/foreach}
	  </div>
	  {/if}

	  <form action="" method="post">
		<div class="form-group">
		  <input type="text" class="form-control form-control-lg" name="title" placeholder="{$TOPIC_TITLE}" maxlength="64">
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
		
		{$TOKEN}
		<input type="submit" class="btn btn-primary" value="{$SUBMIT}">
		<a href="#" class="btn btn-danger" data-toggle="modal" data-target="#cancelModal">{$CANCEL}</a>
	  </form>
	</div>
  </div>
</div>
</div>

{include file='footer.tpl'}

<!-- Cancellation modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="cancelModalLabel">{$CANCEL}</h4>
      </div>
      <div class="modal-body">
        {$CONFIRM_CANCEL}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">{$CLOSE}</button>
        <a href="{$FORUM_LINK}" class="btn btn-danger">{$CANCEL}</a>
      </div>
    </div>
  </div>
</div>