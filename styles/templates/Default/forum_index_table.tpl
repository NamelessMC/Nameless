<br />
<div class="container">
  <div class="row">
	<div class="col-md-9">
	  {$BREADCRUMBS}
	  <table class="table table-bordered" style="table-layout:fixed;">
		<colgroup>
		  <col style="width:50%">
		  <col style="width:10%">
		  <col style="width:40%">
		</colgroup>
		<thead>
		  <tr>
			<th>{$FORUM}</th>
			<th>{$STATS}</th>
			<th>{$LAST_POST}</th>
		  </tr>
		</thead>
		<tbody>
	      {foreach from=$FORUMS item=forum}
			<tr>
			  <td>
			    <a href="/forum/view_forum/?fid={$forum.forum_id}">{$forum.forum_title}</a>
				<br />
				{$forum.forum_description}<br />
				{$forum.subforums}
			  </td>
			  <td>
			    <strong>{$forum.forum_topics}</strong> {$TOPICS}<br />
				<strong>{$forum.forum_posts}</strong> {$POSTS}<br />
			  </td>
			  <td>
			    {if $forum.forum_topics eq 0}
				  {$NO_TOPICS}
				{else}
				  {* There are topics, display the latest *}
				  <div class="row">
					<div class="col-md-2">
					  <div class="frame">
						<a href="/profile/{$forum.last_reply_mcname}">{$forum.last_reply_avatar}</a>
					  </div>
					</div>
					<div class="col-md-9">
					  <a href="/forum/view_topic/?tid={$forum.last_topic_id}">{$forum.last_topic_name}</a><br />
					  {$BY} <a href="/profile/{$forum.last_reply_mcname}">{$forum.last_reply_username}</a><br />{$forum.last_topic_time}
					</div>
				  </div>
				{/if}
			  </td>
			</tr>
		  {/foreach}
		</tbody>
	  </table>
	  <div class="panel panel-default">
	    <div class="panel-heading">{$STATISTICS}</div>
		<div class="panel-body">
		  {$USERS_REGISTERED}<br />{$LATEST_MEMBER}
		  <hr>
		  <strong>{$ONLINE_USERS}</strong><br />
		  {$ONLINE_USERS_LIST}
		</div>
	  </div>
	</div>
	<div class="col-md-3">
	  {$SEARCH_FORM}
	  <br />
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
				<span rel="tooltip" data-trigger="hover" data-original-title="{$post.topic_reply_date}">{$post.topic_reply_rough} {$AGO}</span>
			  </div>
		    </div>
			<br />
		  {/foreach}
		</div>
	  </div>
	</div>
  </div>
</div>