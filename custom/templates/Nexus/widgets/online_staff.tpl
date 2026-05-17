<div class="nx-surface overflow-hidden" id="widget-online-staff">
  <header class="nx-card__header"><h3 class="nx-card__title">{$ONLINE_STAFF}</h3></header>
  <div class="p-4">
    {if isset($ONLINE_STAFF_LIST)}
      <ul class="space-y-2">
        {foreach from=$ONLINE_STAFF_LIST name=online_staff_arr item=user}
          <li class="flex items-center gap-2">
            <span class="nx-avatar nx-avatar--sm"><img src="{$user.avatar}" alt="{$user.username}" loading="lazy" /></span>
            <div class="flex-1 min-w-0">
              <a href="{$user.profile}" data-poload="{$USER_INFO_URL}{$user.id}" style="{$user.style}"
                 class="block text-sm text-text-primary hover:text-accent truncate">{$user.nickname}</a>
              <div class="flex flex-wrap gap-1 mt-0.5">{$user.group}</div>
            </div>
            <span class="w-2 h-2 rounded-full bg-success flex-shrink-0"></span>
          </li>
        {/foreach}
      </ul>
    {else}
      <p class="text-sm text-text-muted">{$NO_STAFF_ONLINE}</p>
    {/if}
  </div>
  <footer class="nx-card__footer"><span class="nx-card__meta">{$TOTAL_ONLINE_STAFF}</span></footer>
</div>
