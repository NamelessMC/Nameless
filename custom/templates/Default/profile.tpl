{include file='navbar.tpl'}

<div class="container" style="padding-top: 5rem;">
  <div class="jumbotron" style="background-image:url('{$BANNER}');">
	<div class="row">
	  <div class="col-md-8">
		<h2>
		  <img class="img-rounded" style="height:60px;width=60px;" src="{$AVATAR}" />
		  <strong{if $USERNAME_COLOUR != false} style="{$USERNAME_COLOUR}"{/if}>{$NICKNAME}</strong> 
		  {$GROUP}
		</h2>
	  </div>
	  <div class="col-md-4">
		<div class="pull-xs-right">
		  {nocache}
		  {if isset($LOGGED_IN)}
		    {if !isset($SELF)}
		  <div class="btn-group">
			<!--<a href="{$FOLLOW_LINK}" class="btn btn-primary btn-lg"><i class="fa fa-users fa-fw"></i> {$FOLLOW}</a>-->
			<a href="{$MESSAGE_LINK}" class="btn btn-secondary btn-lg"><i class="fa fa-envelope fa-fw"></i></a>
		  </div>
		    {else}
		  <div class="btn-group">
		    <a href="{$SETTINGS_LINK}" class="btn btn-secondary btn-lg"><i class="fa fa-cogs fa-fw"></i></a>
		    <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#imageModal"><i class="fa fa-picture-o fa-fw" aria-hidden="true"></i></button>
		  </div>
		    {/if}
		  {/if}
		  {/nocache}
		</div>
	  </div>
	</div>
  </div>

  <div class="card">
    <div class="card-block">
	  <ul class="nav nav-tabs">
		<li class="nav-item">
		  <a class="nav-link active" data-toggle="tab" href="#feed" role="tab">{$FEED}</a>
		</li>
		<li class="nav-item">
		  <a class="nav-link" data-toggle="tab" href="#about" role="tab">{$ABOUT}</a>
		</li>
		{foreach from=$TABS key=key item=tab}
		<li class="nav-item">
		  <a class="nav-link" data-toggle="tab" href="#{$key}" role="tab">{$tab.title}</a>
		</li>
		{/foreach}
	  </ul>

	  <br />

	  <div class="tab-content">
		<div class="tab-pane active" id="feed" role="tabpanel">
		  {if isset($LOGGED_IN)}
			{if isset($ERROR)}
			<div class="alert alert-danger">
			  {$ERROR}
			</div>
			{/if}
		  <form action="" method="post">
			<div class="form-group">
			  <textarea name="post" class="form-control" placeholder="{$POST_ON_WALL}"></textarea>
			</div>

			<input type="hidden" name="action" value="new_post">
			<input type="hidden" name="token" value="{$TOKEN}">
			<input type="submit" class="btn btn-primary" value="{$SUBMIT}">
		  </form>

		  <hr />
		  {/if}

		  {if count($WALL_POSTS)}
		  <div class="timeline">
			<div class="line text-muted"></div>
			{foreach from=$WALL_POSTS item=post}

			<article class="panel panel-primary">
			  <div class="panel-heading icon">
				<img class="img-circle" style="height:40px; width=40px;" src="{$post.avatar}" />
			  </div>

			  <div class="panel-heading">
				<h2 class="panel-title" style="display:inline;"><a href="{$post.profile}">{$post.nickname}:</a></h2>
				<span class="pull-right"><span rel="tooltip" data-original-title="{$post.date}">{$post.date_rough}</span></span>
			  </div>

			  <div class="panel-body">
				<div class="forum_post">
				  {$post.content}
				</div>
			  </div>

			  <div class="panel-footer">
				<a href="{if $post.reactions_link ne "#"}{$post.reactions_link}{else}#{/if}" class="pop" data-content='{if isset($post.reactions.reactions)} {foreach from=$post.reactions.reactions item=reaction name=reactions}<a href="{$reaction.profile}" style="{$reaction.style}"><img class="img-rounded" src="{$reaction.avatar}" alt="{$reaction.username}" style="max-height:30px; max-width:30px;" /> {$reaction.nickname}</a>{if !$smarty.foreach.reactions.last}<br />{/if}{/foreach} {else}{$post.reactions.count}{/if}'><i class="fa fa-thumbs-up"></i> {$post.reactions.count} </a> | <a href="#" data-toggle="modal" data-target="#replyModal{$post.id}"><i class="fa fa-comments"></i> {$post.replies.count}</a>
			  </div>

			</article>

			{if $post.reactions_link ne "#"}
			<!-- Reaction modal -->
			<div class="modal fade" id="reactModal{$post.id}" tabindex="-1" role="dialog" aria-labelledby="reactModal{$post.id}Label" aria-hidden="true">
			  <div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="reactModal{$post.id}Label">{$REACTIONS_TITLE}</h4>
				  </div>
				  <div class="modal-body">
					
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">{$CLOSE}</button>
				  </div>
				</div>
			  </div>
			</div>
			{/if}
			
			<!-- Replies modal -->
			<div class="modal fade" id="replyModal{$post.id}" tabindex="-1" role="dialog" aria-labelledby="replyModal{$post.id}Label" aria-hidden="true">
			  <div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="replyModal{$post.id}Label">{$REPLIES_TITLE}</h4>
				  </div>
				  <div class="modal-body">
				    {if isset($post.replies.replies)}
					  {foreach from=$post.replies.replies name=replies item=reply}
					  <img src="{$reply.avatar}" alt="{$reply.username}" style="max-height:20px; max-width:20px;" class="img-rounded" /> <a href="{$reply.profile}" style="{$reply.style}">{$reply.nickname}</a> &raquo;
					  <span class="pull-right">
					    <span rel="tooltip" title="{$reply.time_full}">{$reply.time_friendly}</span>
					  </span>
					  <div style="height:15px;"></div>
					  <div class="forum_post">
					    {$reply.content}
					  </div>
					  {if !$smarty.foreach.replies.last}<hr />{/if}
					  {/foreach}
					{else}
					  <p>{$NO_REPLIES}</p>
					{/if}
					
					{if isset($LOGGED_IN)}
					<hr />
					<form action="" method="post">
					  <textarea class="form-control" name="reply" placeholder="{$NEW_REPLY}"></textarea>
					  <input type="hidden" name="token" value="{$TOKEN}">
					  <input type="hidden" name="post" value="{$post.id}">
					  <input type="hidden" name="action" value="reply">
					{/if}
				  </div>
				  <div class="modal-footer">
				    {if isset($LOGGED_IN)}
					<input type="submit" value="{$SUBMIT}" class="btn btn-primary">
					</form>
				    {/if}
					<button type="button" class="btn btn-secondary" data-dismiss="modal">{$CLOSE}</button>
				  </div>
				</div>
			  </div>
			</div>

			{/foreach}
		  </div>

			{$PAGINATION}
		  {else}
			<div class="alert alert-info">{$NO_WALL_POSTS}</div>
			<br /><br />
		  {/if}
		</div>

		<div class="tab-pane" id="about" role="tabpanel">
		  <div class="row">
		    <div class="col-md-4">
			  <div class="card">
			    <div class="card-block">
				  {if isset($ABOUT_FIELDS.minecraft)}
				    <center>
					  <img src="{$ABOUT_FIELDS.minecraft.image}" alt="{$USERNAME}" class="img-rounded" />
					  <h2{if $USERNAME_COLOUR != false} style="{$USERNAME_COLOUR}"{/if}>{$NICKNAME}</h2>
					  {$USER_TITLE}
					</center>
					<hr />
					<ul>
					  <li>{$ABOUT_FIELDS.registered.title}</strong> <span rel="tooltip" title="{$ABOUT_FIELDS.registered.tooltip}">{$ABOUT_FIELDS.registered.value}</li>
					  <li>{$ABOUT_FIELDS.last_seen.title}</strong> <span rel="tooltip" title="{$ABOUT_FIELDS.last_seen.tooltip}">{$ABOUT_FIELDS.last_seen.value}</li>
					</ul>
				  {else}
				    <h2{if $USERNAME_COLOUR != false} style="{$USERNAME_COLOUR}"{/if}>{$NICKNAME}</h2>
					<hr />
				  {/if}
				</div>
			  </div>
			</div>
			
			<div class="col-md-8">
			  <div class="card">
			    <div class="card-block">
				  {foreach from=$ABOUT_FIELDS key=key item=field}
					{if is_numeric($key)}
					  <h3>{$field.title}</h3>
					  <p>{$field.value}</p>
					  <hr />
					{/if}
				  {/foreach}
			    </div>
			  </div>
			</div>
		  </div>
		</div>
		
		{foreach from=$TABS key=key item=tab}
		<div class="tab-pane" id="{$key}" role="tabpanel">
		  {include file=$tab.include}
		</div>
		{/foreach}
		
	  </div>
    </div>
  </div>
</div>

{if isset($LOGGED_IN)}
  {if isset($SELF)}
	<!-- Change background image modal -->
	<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
			<h4 class="modal-title" id="imageModalLabel">{$CHANGE_BANNER}</h4>
		  </div>
		  <form action="" method="post" style="display:inline;" >
		    <div class="modal-body">
			  <select name="banner" class="image-picker show-html">
			    {foreach from=$BANNERS item=banner}
				  <option data-img-src="{$banner.src}" value="{$banner.name}"{if $banner.active == true} selected{/if}>{$banner.name}</option>
				{/foreach}
			  </select>
			  <input type="hidden" name="token" value="{$TOKEN}">
			  <input type="hidden" name="action" value="banner">
		    </div>
		    <div class="modal-footer">
			  <button type="button" class="btn btn-danger" data-dismiss="modal">{$CANCEL}</button>
			  <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
		    </div>
		  </form>
		</div>
	  </div>
	</div>
  {/if}
{/if}

{include file='footer.tpl'}