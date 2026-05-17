<div class="nx-surface overflow-hidden" id="widget-statistics">
  <header class="nx-card__header"><h3 class="nx-card__title">{$STATISTICS}</h3></header>
  <dl class="p-4 space-y-2 text-sm">
    {if isset($FORUM_STATISTICS)}
      <div class="flex items-center justify-between">
        <dt class="text-text-muted">{$TOTAL_THREADS}</dt>
        <dd class="text-text-primary font-medium tabular-nums">{$TOTAL_THREADS_VALUE}</dd>
      </div>
      <div class="flex items-center justify-between">
        <dt class="text-text-muted">{$TOTAL_POSTS}</dt>
        <dd class="text-text-primary font-medium tabular-nums">{$TOTAL_POSTS_VALUE}</dd>
      </div>
    {/if}
    <div class="flex items-center justify-between">
      <dt class="text-text-muted">{$USERS_REGISTERED}</dt>
      <dd class="text-text-primary font-medium tabular-nums">{$USERS_REGISTERED_VALUE}</dd>
    </div>
    <div class="flex items-center justify-between pt-2 border-t border-border-subtle">
      <dt class="text-text-muted">{$LATEST_MEMBER}</dt>
      <dd>
        <a href="{$LATEST_MEMBER_VALUE.profile}" data-poload="{$USER_INFO_URL}{$LATEST_MEMBER_VALUE.id}"
           style="{$LATEST_MEMBER_VALUE.style}" class="text-text-primary font-medium hover:text-accent">{$LATEST_MEMBER_VALUE.nickname}</a>
      </dd>
    </div>
  </dl>
</div>
