<div class="container">
  <div class="panel panel-default">
    <div class="panel-body">
      <div class="row">
        <div class="col-md-9">
    	  <ol class="breadcrumb">
    	    {$BREADCRUMBS}
    	  </ol>
    	  <h3 style="display: inline;">{$FORUM_TITLE}</h3><span class="pull-right">{$NEW_TOPIC_BUTTON}</span><br /><br />
    	  {if !empty($SUBFORUMS)}
    	  <div class="well well-sm">
    	    {$SUBFORUMS_LANGUAGE} {$SUBFORUMS}
    	  </div>
    	  {/if}
    	  <table class="table table-striped">
    		<tr>
    		  <th>{$DISCUSSION}</th>
    		  <th>{$STATS}</th>
    		  <th>{$LAST_REPLY}</th>
    		</tr>
    		{foreach from=$STICKY_DISCUSSIONS item=sticky}
    		<tr>
    		  <td>
    		    <i class="fa fa-thumb-tack"></i> {if $sticky.locked == 1}<i class="fa fa-lock"></i> {/if}{$sticky.label} <a href="/forum/view_topic/?tid={$sticky.topic_id}">{$sticky.topic_title}</a><br />
    			<small><span rel="tooltip" data-trigger="hover" data-original-title="{$sticky.topic_created}">{$sticky.topic_created_rough}</span> {$BY} <a href="/profile/{$sticky.topic_created_mcname}">{$sticky.topic_created_username}</a></small>
    		  </td>
    		  <td>
    		    <strong>{$sticky.views}</strong> {$VIEWS}<br />
    			<strong>{$sticky.posts}</strong> {$POSTS}
    		  </td>
    		  <td>
    			<div class="row">
    			  <div class="col-md-3">
    				<div class="frame">
    				  <a href="/profile/{$sticky.last_reply_mcname}">{$sticky.last_reply_avatar}</a>
    				</div>
    			  </div>
    			  <div class="col-md-9">
    				<span rel="tooltip" data-trigger="hover" data-original-title="{$sticky.last_reply}">{$sticky.last_reply_rough}</span><br />{$BY} <a href="/profile/{$sticky.last_reply_mcname}">{$sticky.last_reply_username}</a>
    			  </div>
    			</div>
    		  </td>
    		</tr>
    		{/foreach}
    	    {foreach from=$LATEST_DISCUSSIONS item=discussion}
    	    <tr>
    		  <td>
    		    {if $discussion.locked == 1}<i class="fa fa-lock"></i> {/if}<h4 style="display:inline;">{$discussion.label}</h4> <a href="/forum/view_topic/?tid={$discussion.topic_id}">{$discussion.topic_title}</a><br />
    			<small><span rel="tooltip" data-trigger="hover" data-original-title="{$discussion.topic_created}">{$discussion.topic_created_rough}</span> {$BY} <a href="/profile/{$discussion.topic_created_mcname}">{$discussion.topic_created_username}</a></small>
    		  </td>
    		  <td>
    		    <strong>{$discussion.views}</strong> {$VIEWS}<br />
    			<strong>{$discussion.posts}</strong> {$POSTS}
    		  </td>
    		  <td>
    			<div class="row">
    			  <div class="col-md-3">
    				<div class="frame">
    				  <a href="/profile/{$discussion.last_reply_mcname}">{$discussion.last_reply_avatar}</a>
    				</div>
    			  </div>
    			  <div class="col-md-9">
    				<span rel="tooltip" data-trigger="hover" data-original-title="{$discussion.last_reply}">{$discussion.last_reply_rough}</span><br />{$BY} <a href="/profile/{$discussion.last_reply_mcname}">{$discussion.last_reply_username}</a>
    			  </div>
    			</div>
    		  </td>
    		</tr>
    	    {/foreach}
    	  </table>
    	  {$PAGINATION}
    	</div>
    	<div class="col-md-3">
    	  {$SEARCH_FORM}

    	  <br />

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

    	  <div class="well">
    	  <h4>{$ONLINE_USERS}</h4>
    	  {$ONLINE_USERS_LIST}
    	  </div>

    	  <div class="well">
    	    <h4>{$STATISTICS}</h4>
    		{$USERS_REGISTERED}<br />
    		{$LATEST_MEMBER}
    	  </div>
    	</div>
      </div>
    </div>
  </div>
</div>
