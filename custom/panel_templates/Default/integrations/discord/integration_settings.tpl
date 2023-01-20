<form action="" method="post">
    <div class="form-group">
        <label for="inputLinkMethod">{$LINK_METHOD}</label>
        <select name="link_method" class="form-control" id="inputLinkMethod">
            <option value="bot" {if $LINK_METHOD_VALUE eq "bot" }
                selected{/if}>{$DISCORD_BOT}</option>
            <option value="oauth" {if $LINK_METHOD_VALUE eq "oauth" }
                selected{/if}>{$OAUTH}</option>
        </select>
    </div>
    <div class="form-group">
        <input type="hidden" name="token" value="{$TOKEN}">
        <input type="hidden" name="action" value="integration_settings">
        <input type="submit" value="{$SUBMIT}" class="btn btn-primary">
    </div>
</form>