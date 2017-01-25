<nav class="navbar navbar-default navbar-fixed-top{$NAVBAR_INVERSE}">
<div class="container">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header">
	<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main_navbar_collapse">
	  <span class="sr-only">Toggle navigation</span>
	  <span class="icon-bar"></span>
	  <span class="icon-bar"></span>
	  <span class="icon-bar"></span>
	</button>
	<a class="navbar-brand" href="/">{$SITE_NAME}</a>
  </div>

  <!-- Collect the nav links, forms, and other content for toggling -->
  <div class="collapse navbar-collapse" id="main_navbar_collapse">
	{$NAVBAR_LINKS}
	<ul class="nav navbar-nav navbar-right">
	  {* User dropdown and notifications *}
	  {$USER_AREA}
	</ul>
  </div><!-- /.navbar-collapse -->
</div><!-- /.container -->
</nav>

<br />

{if isset($GLOBAL_MESSAGES) && !empty($GLOBAL_MESSAGES)}
<div class="container">
  {$GLOBAL_MESSAGES}
</div>
{/if}

{if isset($ANNOUNCEMENTS) && !empty($ANNOUNCEMENTS)}
  <div class="container">
  {foreach from=$ANNOUNCEMENTS item=item}
    <div class="alert alert-{$item.type}{if $item.can_close == 1} alert-announcement-{$item.id} alert-dismissible{/if}" id="{$item.id}">
	  {if $item.can_close == 1}<button type="button" class="close close-announcement" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>{/if}
	  {$item.content}
	</div>
  {/foreach}
  </div>
{/if}
