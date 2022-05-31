{include file='header.tpl'}
<div class="ui container" id="maintenance">
    <div class="ui segment">
        <h2 class="ui header">{$MAINTENANCE_TITLE}</h2>
        <div class="ui divider"></div>
        <p>{$MAINTENANCE_MESSAGE}</p>
        <div class="ui buttons">
            <button class="ui positive button" onclick="window.location.reload()">{$RETRY}</button>
        </div>
        {if isset($LOGIN)}
        <div class="ui divider"></div>
        <a href="{$LOGIN_LINK}">{$LOGIN}</a>
        {/if}
    </div>
</div>
</body>

</html>