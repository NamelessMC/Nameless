{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="container">
<div class="card">
  <div class="card-body">
	  <div class="row">
		{if count($WIDGETS_LEFT)}
			<div class="col-md-3">
				<br />
				{foreach from=$WIDGETS_LEFT item=widget}
					{$widget}
					<br />
				{/foreach}
			</div>
		{/if}
		<div class="col-md-{if count($WIDGETS_LEFT)}6{else}9{/if}">
		  <ol class="breadcrumb">
			{foreach from=$BREADCRUMBS item=breadcrumb}
			<li class="breadcrumb-item{if isset($breadcrumb.active)} active{/if}">{if !isset($breadcrumb.active)}<a href="{$breadcrumb.link}">{/if}{$breadcrumb.forum_title}{if !isset($breadcrumb.active)}</a>{/if}</li>
			{/foreach}
		  </ol>
		  <h3 style="display: inline;">{$FORUM_TITLE}</h3>{if $NEW_TOPIC_BUTTON}<span class="float-md-right"><a href="{$NEW_TOPIC_BUTTON}" class="btn btn-primary">{$NEW_TOPIC}</a></span>{/if}<br /><br />
		  {if !empty($SUBFORUMS)}
		  <div class="table-responsive">
		    <table class="table table-striped">
		      <colgroup>
			    <col span="1" style="width:50%">
			    <col span="1" style="width:20%">
			    <col span="1" style="width:30%">
			  </colgroup>
			  <tr>
			    <th colspan="3">{$SUBFORUM_LANGUAGE}</th>
			  </tr>
			  {foreach from=$SUBFORUMS item=subforum}
			  <tr>
			    <td>{$subforum.icon} <a href="{$subforum.link}">{$subforum.title}</a></td>
				<td>{if !$subforum.redirect}<strong>{$subforum.topics}</strong> {$TOPICS}{/if}</td>
				<td>
				  {if !$subforum.redirect}
				    {if count($subforum.latest_post)}
				      <div class="row">
				        <div class="col-md-3">
				          <div class="frame">
				            <a href="{$subforum.latest_post.last_user_link}"><img class="img-centre rounded" style="height:40px; width:40px;" src="{$subforum.latest_post.last_user_avatar}" alt="{$subforum.latest_post.last_user}" /></a>
				          </div>
				        </div>
				        <div class="col-md-9">
				          <a href="{$subforum.latest_post.link}">{$subforum.latest_post.title}</a>
				          <br />
				          <span data-toggle="tooltip" data-trigger="hover" data-original-title="{$subforum.latest_post.time}">{$subforum.latest_post.timeago}</span><br />{$BY} <a style="{$subforum.latest_post.last_user_style}" href="{$subforum.latest_post.last_user_link}" data-poload="{$USER_INFO_URL}{$subforum.latest_post.last_user_id}" data-html="true" data-placement="top">{$subforum.latest_post.last_user}</a>
				        </div>
				      </div>
				    {else}
				      {$NO_TOPICS}
				    {/if}
				  {/if}
				</td>
			  </tr>
			  {/foreach}
		    </table>
		  </div>
		  {/if}
		  
		  <div class="table-responsive">
		  <table class="table table-striped">
			<tr>
			  <th>{$TOPIC}</th>
			  <th>{$STATS}</th>
			  <th>{$LAST_REPLY}</th>
			</tr>
			{foreach from=$STICKY_DISCUSSIONS item=sticky}
			<tr>
			  <td>
				<i class="fas fa-thumbtack"></i> {if $sticky.locked == 1}<i class="fa fa-lock"></i> {/if}{$sticky.label} <a href="{$sticky.link}">{$sticky.topic_title}</a><br />
				<small><span data-toggle="tooltip" data-trigger="hover" data-original-title="{$sticky.topic_created}">{$sticky.topic_created_rough}</span> {$BY} <a style="{$sticky.topic_created_style}" href="{$sticky.author_link}" data-poload="{$USER_INFO_URL}{$sticky.topic_created_user_id}" data-html="true" data-placement="top">{$sticky.topic_created_username}</a></small>
			  </td>
			  <td>
				<strong>{$sticky.views}</strong> {$VIEWS}<br />
				<strong>{$sticky.posts}</strong> {$POSTS}
			  </td>
			  <td>
				<div class="row">
				  <div class="col-md-3">
					<div class="frame">
					  <a href="{$sticky.last_reply_link}"><img class="img-centre rounded" style="height:40px; width:40px;" src="{$sticky.last_reply_avatar}" alt="{$sticky.last_reply_username}" /></a>
					</div>
				  </div>
				  <div class="col-md-9">
					<span data-toggle="tooltip" data-trigger="hover" data-original-title="{$sticky.last_reply}">{$sticky.last_reply_rough}</span><br />{$BY} <a style="{$sticky.last_reply_style}" href="{$sticky.last_reply_link}" data-poload="{$USER_INFO_URL}{$sticky.last_reply_user_id}" data-html="true" data-placement="top">{$sticky.last_reply_username}</a>
				  </div>
				</div>
			  </td>
			</tr>
			{/foreach}
			{foreach from=$LATEST_DISCUSSIONS item=discussion}
			<tr>
			  <td>
				{if $discussion.locked == 1}<i class="fa fa-lock"></i> {/if}{$discussion.label} <a href="{$discussion.link}">{$discussion.topic_title}</a><br />
				<small><span data-toggle="tooltip" data-trigger="hover" data-original-title="{$discussion.topic_created}">{$discussion.topic_created_rough}</span> {$BY} <a style="{$discussion.topic_created_style}" href="{$discussion.author_link}" data-poload="{$USER_INFO_URL}{$discussion.topic_created_user_id}" data-html="true" data-placement="top">{$discussion.topic_created_username}</a></small>
			  </td>
			  <td>
				<strong>{$discussion.views}</strong> {$VIEWS}<br />
				<strong>{$discussion.posts}</strong> {$POSTS}
			  </td>
			  <td>
				<div class="row">
				  <div class="col-md-3">
					<div class="frame">
					  <a href="{$discussion.last_reply_link}"><img class="img-centre rounded" style="height:40px; width:40px;" src="{$discussion.last_reply_avatar}" alt="{$discussion.last_reply_username}" /></a>
					</div>
				  </div>
				  <div class="col-md-9">
					<span data-toggle="tooltip" data-trigger="hover" data-original-title="{$discussion.last_reply}">{$discussion.last_reply_rough}</span><br />{$BY} <a style="{$discussion.last_reply_style}" href="{$discussion.last_reply_link}" data-poload="{$USER_INFO_URL}{$discussion.last_reply_user_id}" data-html="true" data-placement="top">{$discussion.last_reply_username}</a>
				  </div>
				</div>
			  </td>
			</tr>
			{/foreach}
		  </table>
		  </div>
		  
		  {$PAGINATION}
		  
		</div>
		<div class="col-md-3">
			<form class="form-horizontal" role="form" method="post" action="{$SEARCH_URL}">
				<div class="input-group">
					<input type="text" class="form-control input-sm" name="forum_search" placeholder="{$SEARCH}">
					<input type="hidden" name="token" value="{$TOKEN}">
					<div class="input-group-append">
						<button class="btn btn-outline-secondary" type="submit"><i class="fa fa-search"></i></button>
					</div>
				</div>
			</form>

		  {if count($WIDGETS_RIGHT)}
		    <br />
		    {foreach from=$WIDGETS_RIGHT item=widget}
		      {$widget}
		      <br />
		    {/foreach}
		  {/if}
		</div>
	  </div>
  </div>
</div>
</div>
{include file='footer.tpl'}
