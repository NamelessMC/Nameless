{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="container">
  <div class="card">
    <div class="card-body">
	    <h3>{$MOVE_TOPIC}</h3>

	    <form action="" method="post">
		  <div class="form-group">
		    <label for="InputForum">{$MOVE_TO}</label>
		    <select class="form-control" name="forum" id="InputForum">
			    {foreach from=$FORUMS item=forum}
				    {if $forum->category}
					    <option value="{$forum->id}" disabled>{$forum->forum_title}</option>
				    {else}
					    <option value="{$forum->id}">{$forum->forum_title}</option>
				    {/if}
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