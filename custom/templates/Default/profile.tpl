{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="container">
  <div class="jumbotron" style="background-image:url('{$BANNER}');background-size:cover;">
	<div class="row">
	  <div class="col-md-8">
		<h2>
		  <img class="rounded" style="height:60px;width:60px;" src="{$AVATAR}" />
		  <strong{if $USERNAME_COLOUR != false} style="{$USERNAME_COLOUR}"{/if}>{$NICKNAME}</strong>
		  {foreach from=$GROUPS item=group}
			{$group}
		  {/foreach}
		</h2>
	  </div>
	  <div class="col-md-4">
		<span class="float-md-right">
		  {nocache}
		  {if isset($LOGGED_IN)}
		    {if !isset($SELF)}
		  <div class="btn-group">
			<!--<a href="{$FOLLOW_LINK}" class="btn btn-primary btn-lg"><i class="fas fa-users fa-fw"></i> {$FOLLOW}</a>-->
			{if $MOD_OR_ADMIN ne true}<a href="#" data-toggle="modal" data-target="#blockModal" class="btn btn-danger btn-lg"><i class="fas fa-ban fa-fw"></i></a>{/if}
			<a href="{$MESSAGE_LINK}" class="btn btn-secondary btn-lg"><i class="fas fa-envelope fa-fw"></i></a>
			{if isset($RESET_PROFILE_BANNER)}
			  <a href="{$RESET_PROFILE_BANNER_LINK}" class="btn btn-danger btn-lg" rel="tooltip" data-title="{$RESET_PROFILE_BANNER}">
			    <i class="fas fa-image fa-fw"></i>
			  </a>
			{/if}
		  </div>
		    {else}
		  <div class="btn-group">
		    <a href="{$SETTINGS_LINK}" class="btn btn-secondary btn-lg"><i class="fas fa-cogs fa-fw"></i></a>
		    <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#imageModal"><i class="fas fa-image fa-fw" aria-hidden="true"></i></button>
		  </div>
		    {/if}
		  {/if}
		  {/nocache}
		</span>
	  </div>
	</div>
  </div>
  
{if $CAN_VIEW}
	{if count($WIDGETS_RIGHT) || count($WIDGETS_LEFT)}
		    <div class="row">
	{/if}
	
	{if count($WIDGETS_LEFT)}
		<div class="col-md-3">
			<br />
			{foreach from=$WIDGETS_LEFT item=widget}
				{$widget}
				<br />
			{/foreach}
		</div>
	{/if}

  {if count($WIDGETS_RIGHT) && count($WIDGETS_LEFT)}
    <div class="col-md-6">
  {elseif count($WIDGETS_RIGHT) || count($WIDGETS_LEFT)}
	<div class="col-md-9">
  {/if}
  <div class="card">
    <div class="card-body">
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
            {if isset($SUCCESS)}
			<div class="alert alert-success">
			  {$SUCCESS}
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
			{foreach from=$WALL_POSTS item=post}

			<div class="card card-default">
			  <div class="card-header">
				<img class="rounded-circle" style="max-height:25px; max-width:25px;" src="{$post.avatar}" /> <a data-poload="{$USER_INFO_URL}{$post.user_id}" data-html="true" data-placement="top" href="{$post.profile}" style="{$post.user_style}">{$post.nickname}:</a>
				<span class="float-md-right"><span rel="tooltip" data-original-title="{$post.date}">{$post.date_rough}</span></span>
			  </div>

			  <div class="card-body">
				<div class="forum_post" id="post-{$post.id}/">
				  {$post.content}
				</div>
			  </div>

			  <div class="card-footer">
				<a href="{if $post.reactions_link ne "#"}{$post.reactions_link}{else}#{/if}" class="pop" data-content='{if isset($post.reactions.reactions)} {foreach from=$post.reactions.reactions item=reaction name=reactions}<a href="{$reaction.profile}" style="{$reaction.style}"><img class="rounded" src="{$reaction.avatar}" alt="{$reaction.username}" style="max-height:30px; max-width:30px;" /> {$reaction.nickname}</a>{if !$smarty.foreach.reactions.last}<br />{/if}{/foreach} {else}{$post.reactions.count}{/if}'><i class="fas fa-thumbs-up"></i> {$post.reactions.count} </a> | <a href="#" data-toggle="modal" data-target="#replyModal{$post.id}"><i class="fas fa-comments"></i> {$post.replies.count}</a>
				<span class="float-md-right">
				  {if (isset($CAN_MODERATE) && $CAN_MODERATE eq 1) || $post.self eq 1}
				    <form action="" method="post" id="delete{$post.id}">
					  <input type="hidden" name="post_id" value="{$post.id}">
					  <input type="hidden" name="action" value="delete">
				      <input type="hidden" name="token" value="{$TOKEN}">
					</form>
					<a href="#" data-toggle="modal" data-target="#editModal{$post.id}">{$EDIT}</a> | <a href="#" onclick="deletePost({$post.id})">{$DELETE}</a>
				  {/if}
				</span>
			  </div>
			</div>

			{if (isset($CAN_MODERATE) && $CAN_MODERATE eq 1) || $post.self eq 1}
				<!-- Post editing modal -->
				<div class="modal fade" id="editModal{$post.id}" tabindex="-1" role="dialog" aria-labelledby="editModal{$post.id}Label" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5>{$EDIT_POST}</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form action="" method="post">
								  <div class="form-group">
									<textarea class="form-control" name="content">{$post.content}</textarea>
								  </div>
								  <div class="form-group">
									<input type="hidden" name="token" value="{$TOKEN}">
									<input type="hidden" name="post_id" value="{$post.id}">
									<input type="hidden" name="action" value="edit">
									<input type="submit" class="btn btn-primary" value="{$SUBMIT}">
								  </div>
								</form>
							</div>
						</div>
					</div>
				</div>
			{/if}

			{if $post.reactions_link ne "#"}
			<!-- Reaction modal -->
			<div class="modal fade" id="reactModal{$post.id}" tabindex="-1" role="dialog" aria-labelledby="reactModal{$post.id}Label" aria-hidden="true">
			  <div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-header">
				    <h5 class="modal-title" id="reactModal{$post.id}Label">{$REACTIONS_TITLE}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
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
				    <h5 class="modal-title" id="replyModal{$post.id}Label">{$REPLIES_TITLE}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				  </div>
				  <div class="modal-body">
				    {if isset($post.replies.replies)}
					  {foreach from=$post.replies.replies name=replies item=reply}
					  <img src="{$reply.avatar}" alt="{$reply.username}" style="max-height:20px; max-width:20px;" class="rounded" /> <a href="{$reply.profile}" style="{$reply.style}">{$reply.nickname}</a> &raquo;
					  <span class="float-md-right">
					    <span rel="tooltip" title="{$reply.time_full}">{$reply.time_friendly}</span>
					  </span>
					  <div style="height:15px;"></div>
					  <div class="forum_post">
					    {$reply.content}
					  </div>
                      {if (isset($CAN_MODERATE) && $CAN_MODERATE eq 1) || $reply.self eq 1}
						<form action="" method="post" id="deleteReply{$reply.id}">
						  <input type="hidden" name="action" value="deleteReply">
						  <input type="hidden" name="token" value="{$TOKEN}">
						  <input type="hidden" name="post_id" value="{$reply.id}">
						</form>
						<br /><a href="#" onclick="deleteReply({$reply.id})">{$DELETE}</a>
					  {/if}
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

			<br />

			{/foreach}
		    <hr />

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
			    <div class="card-body">
			      <center>
			        <img {if isset($ABOUT_FIELDS.minecraft)}src="{$ABOUT_FIELDS.minecraft.image}{else}class="rounded" style="max-height:75px;max-width:75px;" src="{$AVATAR}{/if}" alt="{$USERNAME}" onerror="this.style.display='none'" />
			        <h2{if $USERNAME_COLOUR != false} style="{$USERNAME_COLOUR}"{/if}>{$NICKNAME}</h2>
			        {$USER_TITLE}
			      </center>
			      <hr />
			      <ul>
			        <li><strong>{$ABOUT_FIELDS.registered.title}</strong> <span rel="tooltip" title="{$ABOUT_FIELDS.registered.tooltip}">{$ABOUT_FIELDS.registered.value}</li>
			        <li><strong>{$ABOUT_FIELDS.last_seen.title}</strong> <span rel="tooltip" title="{$ABOUT_FIELDS.last_seen.tooltip}">{$ABOUT_FIELDS.last_seen.value}</li>
			        <li><strong>{$ABOUT_FIELDS.profile_views.title}</strong> {$ABOUT_FIELDS.profile_views.value}</li>
			      </ul>
			    </div>
			  </div>
			</div>
			
			<div class="col-md-8">
			  <div class="card">
			    <div class="card-body">
			      {if !isset($NO_ABOUT_FIELDS)}
			        {foreach from=$ABOUT_FIELDS key=key item=field}
			          {if is_numeric($key)}
			            <h3>{$field.title}</h3>
			            <p>{$field.value}</p>
			            <hr />
			          {/if}
			        {/foreach}
			      {else}
			        <div class="alert alert-info">{$NO_ABOUT_FIELDS}</div>
			      {/if}
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
  {if isset($WIDGETS_RIGHT)}
	</div>
	<div class="col-md-3">
		{foreach from=$WIDGETS_RIGHT item=widget}
			{$widget}<br /><br />
		{/foreach}
	</div>
	</div>
  {/if}
{else}
	<div class="alert alert-danger" role="alert">
        {$PRIVATE_PROFILE}
	</div>
{/if}
</div>

{if isset($LOGGED_IN)}
  {if isset($SELF)}
	<!-- Change background image modal -->
	<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
		    <h5 class="modal-title" id="imageModalLabel">{$CHANGE_BANNER}</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<form action="" name="updateBanner" method="post" style="display:inline;">
			  <select name="banner" class="image-picker show-html">
			    {foreach from=$BANNERS item=banner}
				  <option data-img-src="{$banner.src}" value="{$banner.name}"{if $banner.active == true} selected{/if}>{$banner.name}</option>
				{/foreach}
			  </select>
			  <input type="hidden" name="token" value="{$TOKEN}">
			  <input type="hidden" name="action" value="banner">
			</form>

			{if isset($PROFILE_BANNER)}
			  <hr />
			  <strong>{$UPLOAD_PROFILE_BANNER}</strong>
			  <br />
			  <form action="{$UPLOAD_BANNER_URL}" method="post" enctype="multipart/form-data" style="display:inline;">
				  <label class="btn btn-secondary" style="margin-bottom: 0">
				    {$BROWSE} <input type="file" name="file" hidden/>
				  </label>
				  <input type="hidden" name="token" value="{$TOKEN}">
				  <input type="hidden" name="type" value="profile_banner">
				  <input type="submit" value="{$UPLOAD}" class="btn btn-primary">
			{/if}
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-danger" data-dismiss="modal">{$CANCEL}</button>
			<button type="button" onclick="document.updateBanner.submit()" class="btn btn-primary">{$SUBMIT}</button>
		  </div>
		</div>
	  </div>
	</div>
  {else}
	{if $MOD_OR_ADMIN ne true}
	<!-- user modal -->
	<div class="modal fade" id="blockModal" tabindex="-1" role="dialog" aria-labelledby="blockModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
		    <h5 class="modal-title" id="blockModalLabel">{if isset($BLOCK_USER)}{$BLOCK_USER}{else}{$UNBLOCK_USER}{/if}</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <form action="" method="post" style="display:inline;" >
		    <div class="modal-body">
			  <p>{if isset($CONFIRM_BLOCK_USER)}{$CONFIRM_BLOCK_USER}{else}{$CONFIRM_UNBLOCK_USER}{/if}</p>
			  <input type="hidden" name="token" value="{$TOKEN}">
			  <input type="hidden" name="action" value="block">
		    </div>
		    <div class="modal-footer">
			  <button type="button" class="btn btn-danger" data-dismiss="modal">{$CANCEL}</button>
			  <input type="submit" class="btn btn-primary" value="{$CONFIRM}">
		    </div>
		  </form>
		</div>
	  </div>
	</div>
	{/if}
  {/if}
{/if}

{include file='footer.tpl'}
