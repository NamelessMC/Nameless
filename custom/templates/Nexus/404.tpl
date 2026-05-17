{include file='header.tpl'}

<div class="nx-container py-16 lg:py-24 text-center max-w-xl mx-auto">
  <div class="font-display text-8xl sm:text-9xl font-bold tracking-tighter bg-gradient-to-br from-accent to-accent-hover bg-clip-text text-transparent mb-2">
    404
  </div>
  <h1 class="font-display text-2xl font-bold tracking-tight mb-3">{$404_TITLE}</h1>
  <p class="text-text-secondary mb-2">{$CONTENT}</p>
  {if isset($ERROR)}<p class="text-text-muted text-sm font-mono mb-6">{$ERROR}</p>{else}<div class="mb-6"></div>{/if}

  <div class="flex justify-center gap-2">
    <button class="nx-btn nx-btn--outline" onclick="javascript:history.go(-1)">
      {include file='components/icon.tpl' name='arrow-left' size=14}
      {$BACK}
    </button>
    <a class="nx-btn nx-btn--primary" href="{$SITE_HOME}">
      {include file='components/icon.tpl' name='home' size=14}
      {$HOME}
    </a>
  </div>
</div>
</body>
</html>
