{*
 *  Nexus · Document head
 *  Preserves the DefaultRevamp $TEMPLATE_CSS / $TEMPLATE_JS / $TITLE contract.
*}
{if "HTML_CLASS"|defined}{assign var="HTMLCLASS" value=" {$smarty.const.HTML_CLASS}"}{else}{assign var="HTMLCLASS" value=""}{/if}
{if "HTML_LANG"|defined}{assign var="HTMLLANG" value=" lang='{$smarty.const.HTML_LANG}'"}{else}{assign var="HTMLLANG" value=" lang='en'"}{/if}
{if "HTML_RTL"|defined && $smarty.const.HTML_RTL eq true}{assign var="HTMLRTL" value=" dir='rtl'"}{else}{assign var="HTMLRTL" value=" dir='ltr'"}{/if}
{if "LANG_CHARSET"|defined}{assign var="METACHARSET" value="{$smarty.const.LANG_CHARSET}"}{else}{assign var="METACHARSET" value="utf-8"}{/if}
{if isset($PAGE_DESCRIPTION) && $PAGE_DESCRIPTION|count_characters > 0}{assign var="PAGEDESCRIPTION" value="{$PAGE_DESCRIPTION}"}{else}{assign var="PAGEDESCRIPTION" value=""}{/if}
{if isset($PAGE_KEYWORDS) && $PAGE_KEYWORDS|count_characters > 0}{assign var="PAGEKEYWORDS" value="{$PAGE_KEYWORDS}"}{else}{assign var="PAGEKEYWORDS" value=""}{/if}

<!DOCTYPE html>
<html{$HTMLCLASS}{$HTMLLANG}{$HTMLRTL} data-theme="{if $NEXUS_DARK_MODE}dark{else}light{/if}">
<head>
    <meta charset="{$METACHARSET}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#07080B" media="(prefers-color-scheme: dark)">
    <meta name="theme-color" content="#FAFAFB" media="(prefers-color-scheme: light)">

    <title>{$TITLE} &bull; {$smarty.const.SITE_NAME}</title>

    {if isset($FAVICON)}<link rel="shortcut icon" href="{$FAVICON}" type="image/x-icon" />{/if}

    <meta name="author" content="{$smarty.const.SITE_NAME}">
    <meta name="description" content="{$PAGEDESCRIPTION}" />
    <meta name="keywords" content="{$PAGEKEYWORDS}" />

    <meta property="og:title" content="{$TITLE} &bull; {$smarty.const.SITE_NAME}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{$OG_URL}" />
    <meta property="og:image" content="{$OG_IMAGE}" />
    <meta property="og:description" content="{$PAGEDESCRIPTION}" />

    <meta name="twitter:title" content="{$TITLE} &bull; {$smarty.const.SITE_NAME}" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:image" content="{$OG_IMAGE}" />
    {if isset($PAGE_DESCRIPTION) && $PAGE_DESCRIPTION|count_characters > 0}
        <meta name="twitter:description" content="{$PAGEDESCRIPTION}" />
    {/if}

    {* FOUC-prevention: apply persisted theme before paint *}
    <script>
      (function () {
        try {
          var t = localStorage.getItem('nexus.theme');
          if (t === 'dark' || t === 'light') {
            document.documentElement.setAttribute('data-theme', t);
          }
        } catch (e) {}
      })();
    </script>

    {* Preconnect to font sources *}
    <link rel="preconnect" href="https://rsms.me" crossorigin>
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" media="print" onload="this.media='all'">

    {foreach from=$TEMPLATE_CSS item=css}{$css}{/foreach}

    {if isset($ANALYTICS_ID)}
        {literal}<script async src="https://www.googletagmanager.com/gtag/js?id={/literal}{$ANALYTICS_ID}{literal}"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
          gtag('config', '{/literal}{$ANALYTICS_ID}{literal}');
        </script>{/literal}
    {/if}

    {if isset($DEBUGBAR_JS)}{$DEBUGBAR_JS}{/if}
</head>
<body class="font-sans antialiased" id="page-{if is_numeric($smarty.const.PAGE)}{$TITLE}{else}{$smarty.const.PAGE}{/if}">

<a href="#nx-main" class="skip-link">Skip to main content</a>
