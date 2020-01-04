<!DOCTYPE html>
<html{if "HTML_CLASS"|defined} {$smarty.const.HTML_CLASS}{/if} lang="{if "HTML_LANG"|defined}{$smarty.const.HTML_LANG}{else}en{/if}" {if "HTML_RTL"|defined && $smarty.const.HTML_RTL eq true} dir="rtl"{/if}>
<head>
	<!-- Standard Meta -->
	<meta charset="{if "LANG_CHARSET"|defined}{$smarty.const.LANG_CHARSET}{else}utf-8{/if}">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

	<!-- Site Properties -->
	<title>{$TITLE} &bull; {$smarty.const.SITE_NAME}</title>
	<meta name="author" content="{$smarty.const.SITE_NAME}">

	{if isset($PAGE_DESCRIPTION) && $PAGE_DESCRIPTION|count_characters > 0}
		<meta name="description" content="{$PAGE_DESCRIPTION}" />
	{/if}

	{if isset($PAGE_KEYWORDS) && $PAGE_KEYWORDS|count_characters > 0}
		<meta name="keywords" content="{$PAGE_KEYWORDS}" />
	{/if}

	<meta property="og:title" content="{$TITLE} &bull; {$smarty.const.SITE_NAME}" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="{$OG_URL}" />
	<meta property="og:image" content="{$OG_IMAGE}" />

	{if isset($PAGE_DESCRIPTION) && $PAGE_DESCRIPTION|count_characters > 0}
		<meta property="og:description" content="{$PAGE_DESCRIPTION}" />
	{/if}

	{foreach from=$TEMPLATE_CSS item=css}
		{$css}
	{/foreach}

	<meta name="robots" content="noindex">

</head>

<body>
<br /><br /><br />
<div class="container">
  <center><h1>403</h1></center>
  <div class="row">
	<div class="col-md-6 offset-md-3">
	  <div class="jumbotron">
		<center>
		  <h4>{$403_TITLE}</h4>
		  <p>{$CONTENT}</p>
		  {if !isset($LOGGED_IN_USER)}<p>{$CONTENT_LOGIN}</p>{/if}
		  <div class="btn-group" role="group" aria-label="...">
			<button class="btn btn-primary btn-lg" onclick="javascript:history.go(-1)">{$BACK}</button>
			{if isset($LOGGED_IN_USER)}
			  <a href="{$SITE_HOME}" class="btn btn-success btn-lg">{$HOME}</a>
			{else}
			  <a href="{$LOGIN_LINK}" class="btn btn-success btn-lg">{$LOGIN}</a>
			{/if}
		  </div>
		</center>
	  </div>
	</div>
  </div>
</div>
</body>
</html>