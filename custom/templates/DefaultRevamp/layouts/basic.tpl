{if "HTML_CLASS"|defined}{assign var="HTMLCLASS" value=" {$smarty.const.HTML_CLASS}"}{/if}
{if "HTML_LANG"|defined}{assign var="HTMLLANG" value=" lang='{$smarty.const.HTML_LANG}'"}{else}{assign var="HTMLLANG" value=" lang='en'"}{/if}
{if "HTML_RTL"|defined && $smarty.const.HTML_RTL eq true}{assign var="HTMLRTL" value=" dir='rtl'"}{/if}
{if "LANG_CHARSET"|defined}{assign var="METACHARSET" value="{$smarty.const.LANG_CHARSET}"}{else}{assign var="METACHARSET" value="utf-8"}{/if}
{if isset($PAGE_DESCRIPTION) && $PAGE_DESCRIPTION|count_characters > 0}{assign var="PAGEDESCRIPTION" value="{$PAGE_DESCRIPTION}"}{else}{assign var="PAGEDESCRIPTION" value=" "}{/if}
{if isset($PAGE_KEYWORDS) && $PAGE_KEYWORDS|count_characters > 0}{assign var="PAGEKEYWORDS" value="{$PAGE_KEYWORDS}"}{else}{assign var="PAGEKEYWORDS" value=" "}{/if}

<!DOCTYPE html>
<html{$HTMLCLASS}{$HTMLLANG}{$HTMLRTL}>
<head>

  <meta charset="{$METACHARSET}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <title>{$TITLE} &bull; {$smarty.const.SITE_NAME}</title>

    {if isset($FAVICON)}
      <link rel="shortcut icon" href="{$FAVICON}" type="image/x-icon"/>
    {/if}

  <meta name="author" content="{$smarty.const.SITE_NAME}">
  <meta name="description" content="{$PAGEDESCRIPTION}"/>
  <meta name="keywords" content="{$PAGEKEYWORDS}"/>

  <!-- Open Graph meta properties used for embeds -->
  <meta property="og:title" content="{$TITLE} &bull; {$smarty.const.SITE_NAME}"/>
  <meta property="og:type" content="website"/>
  <meta property="og:url" content="{$OG_URL}"/>
  <meta property="og:image" content="{$OG_IMAGE}"/>
  <meta property='og:description' content='{$PAGEDESCRIPTION}'/>

  <!-- Twitter Card Properties -->
  <meta name="twitter:title" content="{$TITLE} &bull; {$smarty.const.SITE_NAME}"/>
  <meta name="twitter:card" content="summary"/>
  <meta name="twitter:image" content="{$OG_IMAGE}"/>

    {if isset($PAGE_DESCRIPTION) && $PAGE_DESCRIPTION|count_characters > 0}
      <meta name="twitter:description" content="{$PAGE_DESCRIPTION}"/>
    {/if}

    {foreach from=$TEMPLATE_CSS item=css}
        {$css}
    {/foreach}
    {block 'css'}{/block}

    {if isset($ANALYTICS_ID)}
    {literal}
      <script async src="https://www.googletagmanager.com/gtag/js?id={/literal}{$ANALYTICS_ID}{literal}"></script>
      <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
          dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', '{/literal}{$ANALYTICS_ID}{literal}');
      </script>
    {/literal}
    {/if}

    {if isset($DEBUGBAR_JS)}
        {$DEBUGBAR_JS}
    {/if}
</head>

<body{if $DEFAULT_REVAMP_DARK_MODE} class="dark"{/if}
  id="page-{if is_numeric($smarty.const.PAGE)}{$TITLE}{else}{$smarty.const.PAGE}{/if}">
<div class="pusher">
  <div></div>
  <div class="page-content">
      {block 'body'}{/block}
  </div>
  <div></div>
</div>

{block 'modals'}
    {if isset($GLOBAL_WARNING_TITLE)}
      <div class="ui medium modal" id="modal-acknowledge">
        <div class="header">
            {$GLOBAL_WARNING_TITLE}
        </div>
        <div class="content">
            {$GLOBAL_WARNING_REASON}
        </div>
        <div class="actions">
          <a class="ui positive button" href="{$GLOBAL_WARNING_ACKNOWLEDGE_LINK}">{$GLOBAL_WARNING_ACKNOWLEDGE}</a>
        </div>
      </div>
    {/if}
{/block}

{foreach from=$TEMPLATE_JS item=script}
    {$script}
{/foreach}

{if isset($GLOBAL_WARNING_TITLE)}
  <script type="text/javascript">
    $('#modal-acknowledge').modal({closable: false}).modal('show');
  </script>
{/if}

<script type="text/javascript">
  function toggleDarkLightMode() {
    $.post("{$DARK_LIGHT_MODE_ACTION}", {token: "{$DARK_LIGHT_MODE_TOKEN}"})
      .done(function () {
        window.location.reload();
      });

    return false;
  }
</script>
{block 'scripts'}{/block}

{if isset($NEW_UPDATE) && ($NEW_UPDATE_URGENT != true)}
  <script src="{$TEMPLATE.path}/js/core/update.js"></script>
{/if}

{if !isset($EXCLUDE_END_BODY)}
{if isset($DEBUGBAR_HTML)}
    {$DEBUGBAR_HTML}
{/if}
</body>

</html>
{/if}
