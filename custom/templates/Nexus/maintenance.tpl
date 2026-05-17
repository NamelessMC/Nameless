{include file='header.tpl'}

<div class="nx-container py-16 lg:py-24 text-center max-w-xl mx-auto">
  <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-warning/15 text-warning mb-6">
    {include file='components/icon.tpl' name='settings' size=28 class="animate-spin" strokeWidth=1.5}
  </div>
  <h1 class="font-display text-3xl font-bold tracking-tight mb-3">{$MAINTENANCE_TITLE}</h1>
  <p class="text-text-secondary mb-6">{$MAINTENANCE_MESSAGE}</p>

  <div class="flex justify-center gap-2">
    <button class="nx-btn nx-btn--primary" onclick="window.location.reload()">{$RETRY}</button>
  </div>

  {if isset($LOGIN)}
    <div class="mt-6 pt-6 border-t border-border-subtle">
      <a class="text-sm text-text-secondary hover:text-text-primary transition" href="{$LOGIN_LINK}">{$LOGIN}</a>
    </div>
  {/if}
</div>
</body>
</html>
