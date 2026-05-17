<div class="nx-surface overflow-hidden">
  <header class="nx-card__header"><h3 class="nx-card__title">{$MINECRAFT_ACCOUNT}</h3></header>
  <div class="p-4 flex justify-center">
    <canvas id="skin_container"></canvas>
  </div>
  <footer class="nx-card__footer text-xs">
    <span>
      {$LAST_SEEN_TEXT}
      <span {if !$ALL_UNKNOWN}title="{$LAST_ONLINE}"{/if}>{$LAST_ONLINE_AGO}</span>
      {$ON}
      <span {if !$ALL_UNKNOWN && !$SERVER_UNKNOWN}onclick="copy('#last_seen_ip')" class="cursor-pointer hover:text-text-primary transition" title="{$LAST_ONLINE_SERVER_IP}"{/if}>{$LAST_ONLINE_SERVER}</span>
      {if !$ALL_UNKNOWN && !$SERVER_UNKNOWN}<span class="hidden" id="last_seen_ip">{$LAST_ONLINE_SERVER_IP}</span>{/if}
    </span>
  </footer>
</div>

<style>
  @font-face { font-family: 'Minecraft'; src: url('{$MINECRAFT_FONT_URL}') format('woff2'); }
</style>
<script src="{$SKINVIEW_3D_JS_URL}"></script>
<script>
  const skinViewer = new skinview3d.SkinViewer({
    canvas: document.getElementById("skin_container"),
    skin: "https://crafthead.net/skin/{$UUID}",
  });
  skinViewer.width = 150; skinViewer.height = 200;
  skinViewer.nameTag = '{$USERNAME}';
  skinViewer.zoom = 0.8;
  skinViewer.animation = new skinview3d.IdleAnimation();
  skinViewer.controls.enablePan = false;
  skinViewer.controls.enableZoom = false;
</script>
