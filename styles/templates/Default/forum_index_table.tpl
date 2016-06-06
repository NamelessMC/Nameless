<br />
<div class="container">
  <div class="row">
	<div class="col-md-9">
	  {$BREADCRUMBS}
	  {foreach from=$FORUMS item=parent}
	    {assign var=counter value=1}
		<div class="panel panel-default">
		  <div class="panel-heading" id="{$parent.forum_title}">
              {if $parent.forum_type == "category"}
                  <strong>{$parent.forum_title}</strong>
              {else}
                  <a href="/forum/view_forum/?fid={$parent.id}"><strong>{$parent.forum_title}</strong></a>
              {/if}
		  </div>
		  <div class="panel-body">
			{foreach from=$parent.forums item=forum}
		    <div class="row">
			  <div class="col-md-6">
				  <a href="/forum/view_forum/?fid={$forum.forum_id}">{$forum.forum_title}</a>
				  <br />
				  {$forum.forum_description}<br />
				  {$forum.subforums}
			  </div>
			  <div class="col-md-2">
				  <strong>{$forum.forum_topics}</strong> {$TOPICS}<br />
				  <strong>{$forum.forum_posts}</strong> {$POSTS}<br />
			  </div>
			  <div class="col-md-4">
				{if $forum.forum_topics eq 0}
				  {$NO_TOPICS}
				{else}
				  {* There are topics, display the latest *}
				  <div class="row">
					<div class="col-md-2">
					  <div class="frame" style="position:static !important;">
						<a href="/profile/{$forum.last_reply_mcname}">{$forum.last_reply_avatar}</a>
					  </div>
					</div>
					<div class="col-md-9">
					  {$LAST_POST}:
					  {$forum.label} <a href="/forum/view_topic/?tid={$forum.last_topic_id}">{$forum.last_topic_name}</a><br />
					  {$BY} <a href="/profile/{$forum.last_reply_mcname}">{$forum.last_reply_username}</a><br />{$forum.last_topic_time}
					</div>
				  </div>
				{/if}
			  </div>
			</div>
			{if ($parent.forums|@count) != $counter}
			<hr>
			{/if}
			{assign var=counter value=$counter+1}
		    {/foreach}
		  </div>
		</div>
	  {/foreach}
	  <div class="panel panel-default">
	    <div class="panel-heading">{$STATISTICS}</div>
		<div class="panel-body">
		  {$USERS_REGISTERED}<br />{$LATEST_MEMBER}
		</div>
	  </div>
	</div>
	<div class="col-md-3">
	  {$SEARCH_FORM}
	  
	  <br />
	  
	  {if !empty($SERVER_STATUS)}
	  <div class="well">
	    <h4>{$SERVER_STATUS}</h4>
	    <table class="table">
		  <tr class="{if $MAIN_ONLINE == 1}success{else}danger{/if}">
			<td><b>{$STATUS}</b></td>
			<td>{if $MAIN_ONLINE == 1}{$ONLINE}{else}{$OFFLINE}{/if}</td>
		  </tr>
		  <tr>
		    <td><b>{$PLAYERS_ONLINE}</b></td>
			<td>{$PLAYER_COUNT}</td>
		  </tr>
		  <tr>
		    <td><b>{$QUERIED_IN}</b></td>
			<td>{$TIMER}</td>
		  </tr>
		</table>
	  </div>
	  {/if}
	  
	  <div class="panel panel-default">
	    <div class="panel-heading">
		  {$ONLINE_USERS}
		</div>
		<div class="panel-body">
		  {$ONLINE_USERS_LIST}
		</div>
	  </div>
	  <div class="panel panel-default">
	    <div class="panel-heading">
		  {$LATEST_POSTS}
		</div>
		<div class="panel-body">
		  {foreach from=$postsArray item=post}
		    <div class="row">
			  <div class="col-md-3">
			    <div class="frame">
				  <a href="/profile/{$post.topic_last_user_mcname}">{$post.topic_last_user_avatar}</a>
			    </div>
			  </div>
			  <div class="col-md-9">
			    <a href="/forum/view_topic/?tid={$post.topic_id}">{$post.topic_title}</a><br />
			    {$BY} <a href="/profile/{$post.topic_last_user_mcname}">{$post.topic_last_user_username}</a><br />
				<span rel="tooltip" data-trigger="hover" data-original-title="{$post.topic_reply_date}">{$post.topic_reply_rough}</span>
			  </div>
		    </div>
			<br />
		  {/foreach}
		</div>
	  </div>
	  
	  
	  
	</div>
  </div>
</div>
