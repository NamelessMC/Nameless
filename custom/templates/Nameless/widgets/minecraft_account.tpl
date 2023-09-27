<div class="ui fluid card">
    <div class="content" style="padding-bottom: 0;">
        <h4 class="ui header">{$MINECRAFT_ACCOUNT}</h4>
        <div class="center aligned">
            <canvas id="skin_container"></canvas>
        </div>
    </div>
    <div class="extra content">
        {$LAST_SEEN_TEXT} <span {if !$ALL_UNKNOWN}data-tooltip="{$LAST_ONLINE}"{/if}>{$LAST_ONLINE_AGO}</span> {$ON} <span {if !$ALL_UNKNOWN && !$SERVER_UNKNOWN}onclick="copy('#last_seen_ip')" style="cursor: pointer;" data-tooltip="{$LAST_ONLINE_SERVER_IP}"{/if}>{$LAST_ONLINE_SERVER}</span>
        {if !$ALL_UNKNOWN && !$SERVER_UNKNOWN}
            <span style="display: none;" id="last_seen_ip">{$LAST_ONLINE_SERVER_IP}</span>
        {/if}
    </div>
</div>

<style>
    @font-face {
        font-family: 'Minecraft';
        src: url('{$MINECRAFT_FONT_URL}') format('woff2');
    }
</style>

<script src="{$SKINVIEW_3D_JS_URL}"></script>

<script>
    const skinViewer = new skinview3d.SkinViewer({
        canvas: document.getElementById("skin_container"),
        skin: "https://crafthead.net/skin/{$UUID}",
    });

    skinViewer.width = 150;
    skinViewer.height = 200;
    skinViewer.nameTag = '{$USERNAME}';
    skinViewer.zoom = 0.8;
    skinViewer.animation = new skinview3d.IdleAnimation();
    skinViewer.controls.enablePan = false;
    skinViewer.controls.enableZoom = false;
</script>
