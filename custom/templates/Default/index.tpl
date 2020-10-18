{include file='header.tpl'}

<div class="jumbotron home-header"{if isset($BANNER_IMAGE)} style="background-image:url('{$BANNER_IMAGE}')"{/if}>
  <div class="container">
	{include file='navbar.tpl'}
	<center>
	  <br /><br /><br />
	  <h1>{$SITE_NAME}</h1>

	  {if isset($MINECRAFT)}
		<hr />
		{if isset($SERVER_QUERY)}
		  {if isset($SERVER_QUERY.status_value) && $SERVER_QUERY.status_value == 1}
			{if isset($SERVER_QUERY.status_full)}
				<p class="lg">{$SERVER_QUERY.status_full}</p>
			{else}
			    <p class="lg">{$SERVER_QUERY.x_players_online}</p>
			{/if}
		  {else}
		    <p class="lg">{$SERVER_OFFLINE}</p>
		  {/if}
		{/if}
		{if isset($CLICK_TO_COPY_TOOLTIP)}
		  <p class="lg"><span onclick="copyToClipboard('#ip')" data-toggle="tooltip" title="{$CLICK_TO_COPY_TOOLTIP}">{$CONNECT_WITH}</span></p>
		{/if}
	  {/if}
	</center>

  </div>
</div>

<div class="container">
<div class="card">
  <div class="card-body">
	{if isset($HOME_SESSION_FLASH)}
        <div class="alert alert-info">
            {$HOME_SESSION_FLASH}
        </div>
	{/if}
	{if isset($HOME_SESSION_ERROR_FLASH)}
        <div class="alert alert-danger">
            {$HOME_SESSION_ERROR_FLASH}
        </div>
	{/if}
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
	  {if isset($NEWS)}
		<div class="col-md-{if count($WIDGETS_LEFT) && count($WIDGETS_RIGHT)}6{elseif count($WIDGETS_RIGHT) || count($WIDGETS_LEFT)}9{else}12{/if}">
	    <center><h2>{$LATEST_ANNOUNCEMENTS} <i class="fa fa-bullhorn"></i></h2></center>
		<hr />
		{foreach from=$NEWS item=item}
		<div class="card">
		  <div class="card-header">
			{if $item.label}{$item.label} {/if}<a href="{$item.url}">{$item.title}</a>
			<span class="float-md-right" data-toggle="tooltip" title="{$item.date}">{$item.time_ago}</span>
		  </div>
		  <div class="card-body">
			<div class="forum_post">
			  {$item.content}
			</div>
			<hr />
			<a href="{$item.author_url}"><img class="rounded-circle" style="height:30px;width=30px;" src="{$item.author_avatar}" /></a> <a data-poload="{$USER_INFO_URL}{$item.author_id}" data-html="true" data-placement="top" href="{$item.author_url}" style="{$item.author_style}">{$item.author_name}</a>
		    <span class="float-md-right"><a href="{$item.url}" class="btn btn-primary btn-sm">{$READ_FULL_POST} »</a></span>
		  </div>
		</div>
		<br />
		{/foreach}
	  </div>
	  <div class="col-md-3">

	  {else}
	  <div class="col-md-3 offset-md-6">
	  {/if}

		{if count($WIDGETS_RIGHT)}
	    <center><h2>{$SOCIAL} <i class="fa fa-users" aria-hidden="true"></i></h2></center>
	    <hr />
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
