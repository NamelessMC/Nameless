{*
 *  Nexus · User popover (returned via ajax for hover-cards)
*}
<div id="user-popup" class="nx-surface-elevated p-4 min-w-[260px]">
  <div class="flex items-center gap-3 mb-2">
    <span class="nx-avatar"><img src="{$AVATAR}" alt="{$USERNAME}" loading="lazy" /></span>
    <div class="flex-1 min-w-0">
      <h4 class="font-display font-semibold text-text-primary truncate" style="{$STYLE}">{$NICKNAME}</h4>
      <div class="flex flex-wrap gap-1 mt-1">
        {if count($GROUPS)}
          {foreach from=$GROUPS item=group_html}{$group_html}{/foreach}
        {else}
          <span class="nx-badge">{$GUEST}</span>
        {/if}
      </div>
    </div>
  </div>

  {if isset($REGISTERED)}
    <hr class="border-border-subtle my-3" />
    <dl class="space-y-1 text-sm">
      <div class="flex justify-between gap-3">
        <dt class="text-text-muted">{$REGISTERED|regex_replace:'/[:].*/':''}</dt>
        <dd class="text-text-primary font-medium" title="{$REGISTERED_DATE}">{$REGISTERED|regex_replace:'/^[^:]+:\h*/':''}</dd>
      </div>
      {if isset($LAST_SEEN)}
        <div class="flex justify-between gap-3">
          <dt class="text-text-muted">{$LAST_SEEN|regex_replace:'/[:].*/':''}</dt>
          <dd class="text-text-primary font-medium" title="{$LAST_SEEN_DATE}">{$LAST_SEEN|regex_replace:'/^[^:]+:\h*/':''}</dd>
        </div>
      {/if}
      {if isset($TOPICS) && isset($POSTS)}
        <div class="flex justify-between gap-3">
          <dt class="text-text-muted">{$TOPICS|regex_replace:'/[0-9]+/':''|capitalize}</dt>
          <dd class="text-text-primary font-medium">{$TOPICS|regex_replace:'/[^0-9]+/':''}</dd>
        </div>
        <div class="flex justify-between gap-3">
          <dt class="text-text-muted">{$POSTS|regex_replace:'/[0-9]+/':''|capitalize}</dt>
          <dd class="text-text-primary font-medium">{$POSTS|regex_replace:'/[^0-9]+/':''}</dd>
        </div>
      {/if}
    </dl>
  {/if}
</div>
