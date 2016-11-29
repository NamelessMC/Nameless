{include file='navbar.tpl'}

<div class="container" style="padding-top: 5rem;">
<div class="card">
  <div class="card-block">
	<div class="container">
	  <div class="row">
		<div class="col-md-9">
		  <ol class="breadcrumb">
		    <li><a href="{$BREADCRUMB_URL}">{$BREADCRUMB_TEXT}</a></li>
		  </ol>
		  
		  {if isset($SPAM_INFO)}
		  <div class="alert alert-info">{$SPAM_INFO}</div>
		  {/if}
		  
		  {foreach from=$FORUMS key=category item=forum}
		    {assign var=counter value=1}
		    <div class="card card-default">
		    {if !empty($forum.subforums)}
			  <div class="card-header">{$forum.title}</div>
			  <div class="card-block">
			  {foreach $forum.subforums item=subforum}
			    <div class="row">
				  <div class="col-md-6">
				    <a href="{$subforum->link}">{$subforum->forum_title}</a>
					<p>{$subforum->forum_description}</p>
				  </div>
				  <div class="col-md-2">
				    <strong>{$subforum->topics}</strong> {$TOPICS}<br />
					<strong>{$subforum->posts}</strong> {$POSTS}
				  </div>
				  <div class="col-md-4">
				    {if isset($subforum->last_post)}
					<div class="row">
				      <div class="col-md-3">
						<div class="frame">
						  <a href="{$subforum->last_post->profile}"><img alt="{$subforum->last_post->profile}" class="img-centre img-rounded" src="{$subforum->last_post->avatar}" /></a>
						</div>
					  </div>
					  <div class="col-md-9">
					    <a href="{$subforum->last_post->link}">{$subforum->last_post->title}</a>
					    <br />
					    <span data-toggle="tooltip" data-trigger="hover" data-original-title="{$subforum->last_post->post_date}">{$subforum->last_post->date_friendly}</span><br />{$BY} <a style="{$subforum->last_post->user_style}" href="{$subforum->last_post->profile}">{$subforum->last_post->username}</a>
					  </div>
					</div>
					{else}
					{$NO_TOPICS}
					{/if}
				  </div>
				</div>
				{if ($forum.subforums|@count) != $counter}
				<hr />
				{/if}
				{assign var=counter value=$counter+1}
			  {/foreach}
			  </div>
		    {/if}
			</div>
		  {/foreach}
		</div>
		<div class="col-md-3">
		
		  <form class="form-horizontal" role="form" method="post" action="{$SEARCH_URL}">
		    <div class="input-group">
			  <input type="text" class="form-control input-sm" name="forum_search" placeholder="{$SEARCH}">
			  <input type="hidden" name="token" value="{$TOKEN}">
			  <span class="input-group-btn">
			    <button type="submit" class="btn btn-default">
				  <i class="fa fa-search"></i>
			    </button>
			  </span>
		    </div>
		  </form>
		  
		  <br />
		  
		  <div class="card">
		    <div class="card-block">
			  <h2>{$STATS} <i class="fa fa-bar-chart"></i></h2>
			  {$USERS_REGISTERED}<br />
			  {$LATEST_MEMBER}
			  
			  <hr />
			  
			  <h3>{$ONLINE_USERS}</h3>
			  {$ONLINE_USERS_LIST}
			  
			</div>
		  </div>
		</div>
	  </div>
	</div>
  </div>
</div>
</div>

{include file='footer.tpl'}