<div class="nx-surface overflow-hidden" id="widget-server-status">
  <header class="nx-card__header flex items-center justify-between">
    <h3 class="nx-card__title">{$SERVER_STATUS}</h3>
    {if isset($SERVER) && $SERVER.status_value eq 1}
      <span class="nx-badge nx-badge--success nx-badge--dot">{$ONLINE}</span>
    {elseif isset($SERVER)}
      <span class="nx-badge nx-badge--danger nx-badge--dot">{$OFFLINE}</span>
    {/if}
  </header>
  <div class="p-4">
    {if isset($SERVER)}
      <div class="text-text-secondary text-sm mb-3">{$SERVER.name}</div>
      {if $SERVER.status_value eq 1}
        <div class="flex items-center justify-between text-sm mb-3">
          <span class="text-text-muted">{$ONLINE}</span>
          <span class="text-text-primary font-medium">{$SERVER.player_count} / {$SERVER.player_count_max}</span>
        </div>
        {if isset($SERVER.format_player_list) && count($SERVER.format_player_list) && ($SERVER.player_count > 0)}
          <div class="flex flex-wrap gap-1 mb-3">
            {foreach from=$SERVER.format_player_list item=player}
              <a href="{$player.profile}" title="{$player.username}">
                <img class="w-7 h-7 rounded-full border border-border-default" src="{$player.avatar}" alt="{$player.username}" loading="lazy" />
              </a>
            {/foreach}
          </div>
        {/if}
        {if isset($VERSION)}<p class="text-xs text-text-muted">{$VERSION}</p>{/if}
      {/if}
      <div class="flex items-center justify-between text-sm pt-2 border-t border-border-subtle">
        <span class="text-text-muted">{$IP}</span>
        <code class="text-text-primary text-xs">{$SERVER.join_at}</code>
      </div>
    {else}
      <p class="text-sm text-text-muted">{$NO_SERVERS}</p>
    {/if}
  </div>
</div>
