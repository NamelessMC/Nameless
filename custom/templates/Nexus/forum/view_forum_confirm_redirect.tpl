{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="max-w-xl mx-auto text-center">
  <div class="nx-surface p-8" id="forum-redirect">
    <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-warning/15 text-warning mb-4">
      {include file='components/icon.tpl' name='external' size=24}
    </div>
    <h2 class="font-display text-xl font-semibold mb-6">{$CONFIRM_REDIRECT}</h2>
    <div class="flex justify-center gap-2">
      <a class="nx-btn nx-btn--outline" href="{$FORUM_INDEX}">{$NO}</a>
      <a class="nx-btn nx-btn--primary" href="{$REDIRECT_URL}" target="_blank" rel="noopener nofollow">
        {$YES}
        {include file='components/icon.tpl' name='external' size=14}
      </a>
    </div>
  </div>
</div>

{include file='footer.tpl'}
