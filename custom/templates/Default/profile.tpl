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
			<a href="{$FOLLOW_LINK}" class="btn btn-primary btn-lg"><i class="fa fa-users fa-fw"></i> {$FOLLOW}</a>
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

  <ul class="nav nav-tabs">
	<li class="nav-item">
	  <a class="nav-link active" href="#">Feed</a>
	</li>
	<li class="nav-item">
	  <a class="nav-link" href="#">About</a>
	</li>
	<li class="nav-item">
	  <a class="nav-link" href="#">Posts</a>
	</li>
  </ul>
	  
  <hr />
	  
  <form action="" method="post">
	<div class="form-group">
	  <textarea name="post" class="form-control" placeholder="Post on Samerton's wall"></textarea>
	</div>
	 
	<input type="submit" class="btn btn-primary" value="Submit">
  </form>

  <hr />

  {if count($WALL_POSTS)}
    {foreach from=$WALL_POSTS item=post}
  <div class="timeline">
	<div class="line text-muted"></div>
	
	<article class="panel panel-primary">
	  <div class="panel-heading icon">
		<img class="img-circle" style="height:40px; width=40px;" src="{$post.avatar}" />
	  </div>

	  <div class="panel-heading">
		<h2 class="panel-title" style="display:inline;"><a href="{$post.profile}">{$post.nickname}:</a></h2>
		<span class="pull-right"><span rel="tooltip" data-original-title="{$post.date}">{$post.date_rough}</span></span>
	  </div>

	  <div class="panel-body">
		{$post.content}
	  </div>

	  <div class="panel-footer">
		<a href="#"><i class="fa fa-thumbs-up"></i> 1 like </a> | <a href="#"><i class="fa fa-comments"></i> 2 comments</a>
	  </div>
	
	</article>
	
  </div>
    {/foreach}
  {else}
    <div class="alert alert-info">{$NO_WALL_POSTS}</div>
	<br /><br />
  {/if}
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