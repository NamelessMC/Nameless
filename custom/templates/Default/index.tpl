<div class="jumbotron home-header">
  <div class="container">
    {include file='navbar.tpl'}
	
	<center>
	  <br /><br /><br />
	  <h1>{$SITE_NAME}</h1>
	  <hr />
	  <p class="lg">There are currently <strong>0</strong> players online.</p>
	  <p class="lg">Connect now using the IP <strong>example.com</strong></p>
	</center>
	
  </div>
</div>

<div class="home-news">
  <div class="container">
    <br />
	<div class="row">
	
	  {if isset($NEWS)}
	  <div class="col-md-8">
	    <center><h2>{$LATEST_ANNOUNCEMENTS} <i class="fa fa-bullhorn"></i></h2></center>
		<hr />
		{foreach from=$NEWS item=item}
		<div class="card">
		  <div class="card-header">
			<a href="{$item.url}">{$item.title}</a>
			<span class="pull-right" data-toggle="tooltip" title="{$item.date}">{$item.time_ago}</span>
		  </div>
		  <div class="card-block">
			{$item.content}
			<hr />
			<a href="{$item.author_url}"><img class="img-circle" src="{$item.author_avatar}" /></a> <a href="{$item.author_url}" style="{$item.author_style}">{$item.author_name}</a>
		    <span class="pull-right"><a href="{$item.url}" class="btn btn-primary btn-sm">{$READ_FULL_POST} &raquo;</a></span>
		  </div>
		</div>
		{/foreach}
	  </div>
	  <div class="col-md-4">
	  
	  {else}
	  <div class="col-md-4 offset-md-4">
	  {/if}
	  
	    <center><h2>{$SOCIAL} <i class="fa fa-users" aria-hidden="true"></i></h2></center>
	    <hr />
	    <div class="card">
		  <div class="card-block">
	        {$TWITTER}
		  </div>
		</div>
	    <iframe src="https://discordapp.com/widget?id=172055419961868288&theme=dark" width="100%" height="500" allowtransparency="true" frameborder="0"></iframe>
	  </div>
	</div>
  </div>
</div>

<footer class="home-footer">
  <div class="container">
    <div class="row">
	  <div class="col-md-4">
	    <h3>Links</h3>
	    <ul>
	      <li><a target="_blank" href="https://namelesshosting.com/">Free Hosting</a></li>
	    </ul>
	  </div>
	  <div class="col-md-8">
	    <br /><br />
	    <span class="pull-right">
		  <a href="https://github.com/NamelessMC/Nameless/" target="_blank">Powered by NamelessMC 2.0</a>
		</span>
	  </div>
	</div>
  </div>
</footer>