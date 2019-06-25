{*
   There is generally no need to edit this header.tpl file
*}
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

    <!-- Open Graph Properties -->
    <meta property="og:title" content="{$TITLE} &bull; {$smarty.const.SITE_NAME}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{$OG_URL}" />
    <meta property="og:image" content="{$OG_IMAGE}" />

    {if isset($PAGE_DESCRIPTION) && $PAGE_DESCRIPTION|count_characters > 0}
        <meta property="og:description" content="{$PAGE_DESCRIPTION}" />
    {/if}

    <!-- Twitter Card Properties -->
    <meta name="twitter:title" content="{$TITLE} &bull; {$smarty.const.SITE_NAME}" />
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:image" content="{$OG_IMAGE}" />
    
    {if isset($PAGE_DESCRIPTION) && $PAGE_DESCRIPTION|count_characters > 0}
         <meta name="twitter:description" content="{$PAGE_DESCRIPTION}" />
    {/if}

    {foreach from=$TEMPLATE_CSS item=css}
        {$css}
    {/foreach}

</head>

<body>
