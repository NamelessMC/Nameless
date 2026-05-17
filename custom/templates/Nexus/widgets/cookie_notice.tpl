<div class="nx-surface overflow-hidden">
  <header class="nx-card__header"><h3 class="nx-card__title">{$COOKIE_NOTICE_HEADER}</h3></header>
  <div class="p-4 space-y-3">
    <p class="text-text-secondary text-sm">{$COOKIE_NOTICE_BODY}</p>
    {if $COOKIE_DECISION_MADE}
      <a class="nx-btn nx-btn--primary nx-btn--block" href="{$COOKIE_URL}">{$COOKIE_NOTICE_CONFIGURE}</a>
    {/if}
  </div>
</div>
