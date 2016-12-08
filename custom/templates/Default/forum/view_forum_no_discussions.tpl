{include file='navbar.tpl'}

<div class="container" style="padding-top: 5rem;">
<div class="card">
  <div class="card-block">
	<div class="container">
	  <div class="row">
		<div class="col-md-9">
		  <ol class="breadcrumb">
			{foreach from=$BREADCRUMBS item=breadcrumb}
			<li{if isset($breadcrumb.active)} class="active"{/if}>{if !isset($breadcrumb.active)}<a href="{$breadcrumb.link}">{/if}{$breadcrumb.forum_title}{if !isset($breadcrumb.active)}</a>{/if}</li>
			{/foreach}
		  </ol>
		  <h3 style="display: inline;">{$FORUM_TITLE}</h3>{if $NEW_TOPIC_BUTTON}<span class="pull-right"><a href="{$NEW_TOPIC_BUTTON}" class="btn btn-primary">{$NEW_TOPIC}</a></span>{/if}<br /><br />
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
			    <td><a href="{$subforum.link}">{$subforum.title}</a></td>
				<td><strong>{$subforum.topics}</strong> {$TOPICS}</td>
				<td>
				  {if count($subforum.latest_post)}
				  <div class="row">
				    <div class="col-md-3">
					  <div class="frame">
					    <a href="{$subforum.latest_post.last_user_link}"><img class="img-centre img-rounded" style="height:30px; width:30px;" src="{$subforum.latest_post.last_user_avatar}" alt="{$subforum.latest_post.last_user}" /></a>
					  </div>
				    </div>
				    <div class="col-md-9">
					  <a href="{$subforum.latest_post.link}">{$subforum.latest_post.title}</a>
					  <br />
					  <span data-toggle="tooltip" data-trigger="hover" data-original-title="{$subforum.latest_post.time}">{$subforum.latest_post.timeago}</span><br />{$BY} <a style="{$subforum.latest_post.last_user_style}" href="{$subforum.latest_post.last_user_link}">{$subforum.latest_post.last_user}</a>
				    </div>
				  </div>
				  {else}
				  {$NO_TOPICS}
				  {/if}
				</td>
			  </tr>
			  {/foreach}
		    </table>
		  </div>
		  {/if}
		  
		  {$NO_TOPICS_FULL}
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
			  <h2>{$STATISTICS} <i class="fa fa-bar-chart"></i></h2>
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