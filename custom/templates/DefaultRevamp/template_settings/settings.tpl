<form action="" method="post">
    <div class="form-group">
        <label for="inputDarkMode">{$DARK_MODE}</label>
        <select name="darkMode" class="form-control" id="inputDarkMode">
            <option value="0" {if $DARK_MODE_VALUE eq '0' } selected{/if}>{$DISABLED}</option>
            <option value="1" {if $DARK_MODE_VALUE eq '1' } selected{/if}>{$ENABLED}</option>
        </select>
    </div>
    <div class="form-group">
        <label for="inputNavbarColour">{$NAVBAR_COLOUR}</label>
        <select name="navbarColour" class="form-control" id="inputNavbarColour">
            {foreach from=$NAVBAR_COLOURS item=item}
            <option value="{$item.value}" {if $item.selected} selected{/if}>{$item.name}</option>
            {/foreach}
        </select>
    </div>
    <div class="form-group">
        <label for="inputHomeCustomContent">{$HOME_CUSTOM_CONTENT}</label>
        <textarea name="home_custom_content" id="inputHomeCustomContent"></textarea>
    </div>
    <div class="form-group">
        <input type="hidden" name="token" value="{$TOKEN}">
        <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
    </div>
</form>