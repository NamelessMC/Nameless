<div class="nx-surface overflow-hidden" id="widget-online-users">
  <header class="nx-card__header"><h3 class="nx-card__title">{$ONLINE_USERS}</h3></header>
  <div class="p-4">
    {if isset($ONLINE_USERS_LIST)}
      <div class="flex flex-wrap gap-1.5">
        {foreach from=$ONLINE_USERS_LIST name=online_users_arr item=user}
          <a href="{$user.profile}" data-poload="{$USER_INFO_URL}{$user.id}"
             class="inline-flex items-center gap-1.5 pl-1 pr-2 py-1 rounded-full bg-bg-elevated border border-border-default hover:border-accent transition text-xs">
            <img src="{$user.avatar}" alt="" class="w-5 h-5 rounded-full" loading="lazy" />
            <span>{if $SHOW_NICKNAME_INSTEAD}{$user.nickname}{else}{$user.username}{/if}</span>
          </a>
        {/foreach}
      </div>
    {else}
      <p class="text-sm text-text-muted">{$NO_USERS_ONLINE}</p>
    {/if}
  </div>
  <footer class="nx-card__footer"><span class="nx-card__meta">{$TOTAL_ONLINE_USERS}</span></footer>
</div>
