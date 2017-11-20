<div class="container">
  {$BREADCRUMBS}
  
  {$SESSION_SUCCESS_POST}
  {$SESSION_FAILURE_POST}
  
  {$COOKIE_MESSAGE}

  {$BUTTONS}
  
  <br />
  
  {$PAGINATION}
  
  {foreach from=$REPLIES item=reply}
    <div class="panel panel-primary">
	  <div class="panel-heading">
	    {$reply.heading}
	  </div>
	  <div class="panel-body" id="{$reply.post_id}">
	    <div class="row">
		  <div class="col-md-3">
		    <center>
			  {$reply.avatar}
			  <br /><br />
			  <strong><a href="/profile/{$reply.mcname}">{$reply.username}</a></strong>
			  <br />
			  {$reply.user_group}
			  {if !is_null($reply.user_group2)}<br />
			  {$reply.user_group2}{/if}
              <br />
			  {$reply.user_title}
			  <hr>
			  {$reply.user_posts_count} {$POSTS}<br />
			  {$reply.user_reputation} {$REPUTATION}<br /><br />
			</center>
			<blockquote>
			  <small>IGN: {$reply.mcname}</small>
			</blockquote>
		  </div>
		  <div class="col-md-9">
		    {$BY} <a href="/profile/{$reply.mcname}">{$reply.username}</a> &raquo; <span rel="tooltip" data-trigger="hover" data-original-title="{$reply.post_date}">{$reply.post_date_rough} {$AGO}</span>
		    {$reply.buttons}
			<hr>
			<div class="forum_post">
			  {$reply.content}
			</div>
			<br /><br />
			<span class="pull-right">
			  {$reply.reputation}
			</span>
			<br /><br />
			<hr>
			{$reply.signature}
		  </div>
		</div>
	  </div>
	</div>
  {/foreach}
  
  {$PAGINATION}
  
  {$QUICK_REPLY}
  
</div>