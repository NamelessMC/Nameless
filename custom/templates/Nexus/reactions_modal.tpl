{*
 *  Nexus · Reactions modal (injected via $.get into legacy .ui.modal container)
 *  Keeps the tab/menu DOM contract because forum/profile JS reaches in.
*}
<div class="ui menu mb-4">
  {foreach from=$REACTIONS item=reaction}
    <a class="{if $ACTIVE_TAB == $reaction.id}active {/if}item nx-btn nx-btn--ghost nx-btn--sm" data-tab="{$reaction.id}">
      {if $reaction.id != 0}{$reaction.html} <span class="mx-1"></span>{/if}{$reaction.name}
      <span class="nx-badge ml-1">{$reaction.count}</span>
    </a>
  {/foreach}
</div>

{foreach from=$REACTIONS item=reaction}
  <div class="ui bottom attached tab {if $ACTIVE_TAB == $reaction.id}active{/if}" data-tab="{$reaction.id}">
    <ul class="divide-y divide-border-subtle">
      {foreach from=$reaction.users item=user}
        <li class="flex items-center gap-3 px-2 py-3 hover:bg-bg-elevated rounded-md cursor-pointer"
            onclick="window.location.href='{$user.profile}'">
          <span class="nx-avatar nx-avatar--sm"><img src="{$user.avatar}" alt="{$user.nickname}" loading="lazy" /></span>
          <span class="flex-1 min-w-0">
            <span class="block text-text-primary font-medium truncate" style="{$user.group_style}">
              {$user.nickname}
              {foreach from=$user.group_html item=group_html}{$group_html}{/foreach}
            </span>
            <span class="block text-xs text-text-muted">{$user.reacted_time}</span>
          </span>
          {if $reaction.id == 0}<span class="text-lg">{$user.reaction_html}</span>{/if}
        </li>
      {/foreach}
    </ul>
  </div>
{/foreach}

<script>$('.menu .item').tab();</script>
