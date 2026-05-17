{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="max-w-3xl mx-auto">
  <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight mb-6">{$COOKIE_NOTICE_HEADER}</h1>

  <div class="nx-surface p-6 sm:p-8">
    <div class="prose prose-invert max-w-none forum_post">
      {$COOKIE_NOTICE}
    </div>
    <hr class="my-6 border-border-subtle">
    <button class="nx-btn nx-btn--primary" onclick="configureCookies()">{$UPDATE_SETTINGS}</button>
  </div>
</div>

{include file='footer.tpl'}
