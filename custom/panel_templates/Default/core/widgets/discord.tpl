<form action="" method="post">
    <div class="form-group">
        <label for="inputDiscordId">{$DISCORD_ID} <span class="badge badge-info" data-toggle="popover" data-title="{$INFO}" data-content="{$ID_INFO|escape}"><i class="fa fa-question"></i></label>
        <input class="form-control" type="number" name="discord_guild_id" id="inputDiscordId" value="{$DISCORD_ID_VALUE}">
    </div>
    <div class="form-group">
        <label for="inputDiscordTheme">{$DISCORD_THEME}</label>
        <select class="form-control" id="inputDiscordTheme" name="theme">
            <option value="dark" {if $DISCORD_THEME_VALUE eq 'dark' } selected{/if}>{$DARK}</option>
            <option value="light" {if $DISCORD_THEME_VALUE eq 'light' } selected{/if}>{$LIGHT}</option>
        </select>
    </div>
    <div type="form-group">
        <input type="hidden" name="token" value="{$TOKEN}">
        <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
    </div>
</form>