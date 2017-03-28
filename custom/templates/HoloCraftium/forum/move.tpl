{include file='navbar.tpl'}

<div class="container" style="padding-top:5rem;">
  <div class="card">
    <div class="card-block">
	  <div class="container">
	    <h3>{$MOVE_TOPIC}</h3>

	    <form action="" method="post">
		  <div class="form-group">
		    <label for="InputForum">{$MOVE_TO}</label>
		    <select class="form-control" name="forum" id="InputForum">
			  {foreach from=$FORUMS item=forum}
			  <option value="{$forum->id}">{$forum->forum_title|escape}</option>
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
</div>

{include file='footer.tpl'}