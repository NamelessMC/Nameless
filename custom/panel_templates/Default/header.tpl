<!DOCTYPE html>
<html{if "HTML_CLASS"|defined} {$smarty.const.HTML_CLASS}{/if} lang="{if "
    HTML_LANG"|defined}{$smarty.const.HTML_LANG}{else}en{/if}" {if "HTML_RTL"|defined && $smarty.const.HTML_RTL eq
    true} dir="rtl" {/if}>

    <head>
        <!-- Standard Meta -->
        <meta charset="{if " LANG_CHARSET"|defined}{$smarty.const.LANG_CHARSET}{else}utf-8{/if}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

        <title>{$TITLE} &bull; {$smarty.const.SITE_NAME}</title>

        {if isset($FAVICON)}
        <link rel="shortcut icon" href="{$FAVICON}" type="image/x-icon" />
        {/if}

        {foreach from=$TEMPLATE_CSS item=css}
        {$css}
        {/foreach}

        {if isset($DEBUGBAR_JS)}
        {$DEBUGBAR_JS}
        {/if}

    </head>
