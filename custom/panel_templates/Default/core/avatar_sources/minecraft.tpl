<form action="" method="post">
    <div class="form-group">
        <label for="inputMinecraftAvatarSource">{$MINECRAFT_AVATAR_SOURCE}</label>
        <select class="form-control" name="minecraft_avatar_source" id="inputMinecraftAvatarSource">
            {foreach from=$MINECRAFT_AVATAR_VALUES key=name item=url}
                <option value="{$name}" {if $name eq $MINECRAFT_AVATAR_VALUE} selected{/if}>
                    {$url}
                </option>
            {/foreach}
        </select>
    </div>
    <div class="form-group">
        <label for="inputAvatarPerspective">{$MINECRAFT_AVATAR_PERSPECTIVE}</label>
        <select class="form-control" name="minecraft_avatar_perspective" id="inputAvatarPerspective">
        </select>
    </div>
    <div class="form-group">
        <input type="hidden" name="token" value="{$TOKEN}">
        <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
    </div>
</form>

<script>
    const perspective_selector = document.getElementById('inputAvatarPerspective');
    const source_selector = document.getElementById('inputMinecraftAvatarSource');
    source_selector.addEventListener('change', () => reloadPerspectives(source_selector.value));

    document.onLoad = reloadPerspectives(source_selector.value, true);

    function reloadPerspectives(source, firstLoad = false) {
        removeOptions(perspective_selector);
        {foreach $MINECRAFT_AVATAR_PERSPECTIVE_VALUES key=source item=perspectives}
        if ('{$source}' === source) {
            {foreach $perspectives item=$perspective}
                if (firstLoad) {
                    {if $perspective|strtolower eq $MINECRAFT_AVATAR_PERSPECTIVE_VALUE|strtolower}
                        option = new Option('{$perspective|ucfirst}', '{$perspective|ucfirst}', true, true);
                        perspective_selector.add(option, undefined);
                    {else}
                        option = new Option('{$perspective|ucfirst}', '{$perspective|ucfirst}');
                        perspective_selector.add(option, undefined);
                    {/if}
                } else {
                    option = new Option('{$perspective|ucfirst}', '{$perspective|ucfirst}');
                    perspective_selector.add(option, undefined);
                }
            {/foreach}
        }
        {/foreach}
    }

    function removeOptions(selectElement) {
        for (let i = selectElement.options.length - 1; i >= 0; i--) {
            selectElement.remove(i);
        }
    }

</script>
