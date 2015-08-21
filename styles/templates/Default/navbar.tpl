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
	<ul class="nav navbar-nav">
	  {$NAVBAR_LINKS}
	</ul>
	<ul class="nav navbar-nav navbar-right">
	  {* User dropdown and notifications *}
	  {$USER_AREA}
	</ul>
  </div><!-- /.navbar-collapse -->
</div><!-- /.container -->
</nav>
{$GLOBAL_MESSAGES}