<div class="ui fluid card">
    <div class="content" style="padding-bottom: 0;">
        <h4 class="ui header">Minecraft Account</h4>
        <div class="center aligned">
            <canvas id="skin_container"></canvas>
        </div>
    </div>
    <div class="extra content">
        Last seen: <span data-tooltip="{$LAST_ONLINE}">{$LAST_ONLINE_AGO}</span> on <span data-tooltip="{$LAST_ONLINE_SERVER_IP}">{$LAST_ONLINE_SERVER}</span>
    </div>
</div>

<style>
    @font-face {
        font-family: 'Minecraft';
        src: url('https://bs-community.github.io/skinview3d/font/minecraft.woff2') format('woff2');
    }
</style>

<script src="https://bs-community.github.io/skinview3d/js/skinview3d.bundle.js"></script>

<script>
    let skinViewer = new skinview3d.SkinViewer({
        canvas: document.getElementById("skin_container"),
        skin: "https://crafthead.net/skin/{$UUID}",
    });

    // Change viewer size
    skinViewer.width = 150;
    skinViewer.height = 200;
    skinViewer.nameTag = '{$USERNAME}';
    skinViewer.controls.enableZoom = false;
    skinViewer.controls.enablePan = false;
    skinViewer.controls.enableRotate = false;

    // Zoom out
    skinViewer.zoom = 0.8;

    // Rotate the player
    skinViewer.autoRotate = true;

    // Apply an animation
    skinViewer.animation = new skinview3d.IdleAnimation();
</script>
