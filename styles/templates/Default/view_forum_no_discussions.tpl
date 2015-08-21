<br /><br />
<div class="container">
  <div class="row">
    <div class="col-md-9">
	  <h3 style="display: inline;">{$FORUM_TITLE}</h3><span class="pull-right">{$NEW_TOPIC_BUTTON}</span><br /><br />
	  {if !empty($SUBFORUMS)}
	  <div class="well well-sm">
	    {$SUBFORUMS_LANGUAGE} {$SUBFORUMS}
	  </div>
	  {/if}
	  {$NO_TOPICS}
	</div>
	<div class="col-md-3">
	  <div class="well">
	    <h4>{$FORUMS}</h4>
		<ul class="nav nav-list">
		  <li class="nav-header">{$OVERVIEW}</li>
		  <li><a href="/forum">{$LATEST_DISCUSSIONS_TITLE}</a></li>
		  {foreach from=$SIDEBAR_FORUMS key=category item=subforums}
		    {if !empty($subforums)}
			  <li class="nav-header">{$category}</li>
			  {foreach $subforums item=subforum}
			    <li{if $subforum.title == $FORUM_TITLE} class="active"{/if}><a href="/forum/view_forum/?fid={$subforum.id}">{$subforum.title}</a></li>
			  {/foreach}
			{/if}
		  {/foreach}
		</ul>
	  </div>
	  <div class="well">
	    <h4>{$STATISTICS}</h4>
		{$USERS_REGISTERED}<br />
		{$LATEST_MEMBER}
	  </div>
	</div>
  </div>
</div>