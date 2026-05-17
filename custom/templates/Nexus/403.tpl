{include file='header.tpl'}

<div class="nx-container py-16 lg:py-24 text-center max-w-xl mx-auto">
  <div class="font-display text-8xl sm:text-9xl font-bold tracking-tighter bg-gradient-to-br from-warning to-danger bg-clip-text text-transparent mb-2">
    403
  </div>
  <h1 class="font-display text-2xl font-bold tracking-tight mb-3">{$403_TITLE}</h1>
  <p class="text-text-secondary mb-2">{$CONTENT}</p>
  {if !isset($LOGGED_IN_USER)}<p class="text-text-muted text-sm mb-6">{$CONTENT_LOGIN}</p>{else}<div class="mb-6"></div>{/if}

  <div class="flex justify-center gap-2">
    <button class="nx-btn nx-btn--outline" onclick="javascript:history.go(-1)">
      {include file='components/icon.tpl' name='arrow-left' size=14}
      {$BACK}
    </button>
    {if isset($LOGGED_IN_USER)}
      <a class="nx-btn nx-btn--primary" href="{$SITE_HOME}">{include file='components/icon.tpl' name='home' size=14}{$HOME}</a>
    {else}
      <a class="nx-btn nx-btn--primary" href="{$LOGIN_LINK}">{include file='components/icon.tpl' name='log-in' size=14}{$LOGIN}</a>
    {/if}
  </div>
</div>
</body>
</html>
