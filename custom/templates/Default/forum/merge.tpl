{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="container">
  <div class="card">
    <div class="card-body">
	    <h3>{$MERGE_TOPICS}</h3>

	    <form action="" method="post">
		  <div class="form-group">
		    <p>{$MERGE_INSTRUCTIONS}</p>
		    <select class="form-control" name="merge" id="InputTopic">
			  {foreach from=$TOPICS item=topic}
			  <option value="{$topic->id}">{$topic->topic_title|escape}</option>
			  {/foreach}
			</select>
		  </div>

		  <div class="form-group">
		    <input type="hidden" name="token" value="{$TOKEN}">
		    <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
		    <a class="btn btn-danger" href="{$CANCEL_LINK}" onclick="return confirm('{$CONFIRM_CANCEL}')">{$CANCEL}</a>
		  </div>
	    </form>
    </div>
  </div>
</div>

{include file='footer.tpl'}