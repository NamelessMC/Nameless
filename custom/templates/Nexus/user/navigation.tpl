{*  Nexus · User-CP sidebar nav *}
<nav class="nx-surface p-2 space-y-0.5 h-fit lg:sticky lg:top-20" aria-label="Account">
  {foreach from=$CC_NAV_LINKS key=name item=item}
    <a href="{$item.link}" target="{$item.target}"
       class="flex items-center gap-2 px-3 py-2 rounded-md text-sm transition
              {if isset($item.active)}bg-accent-subtle text-text-primary{else}text-text-secondary hover:text-text-primary hover:bg-bg-elevated{/if}">
      {$item.title}
    </a>
  {/foreach}
</nav>
