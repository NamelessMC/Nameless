{include file='header.tpl'}
{include file='navbar.tpl'}

<nav class="text-sm text-text-muted mb-4 flex flex-wrap items-center gap-1" aria-label="Breadcrumb">
  {assign i 1}
  {foreach from=$BREADCRUMBS item=breadcrumb}
    {if $i != 1}<span class="text-text-muted">/</span>{/if}
    <a class="hover:text-text-primary transition {if isset($breadcrumb.active)}text-text-primary{else}text-text-secondary{/if}" href="{$breadcrumb.link}">{$breadcrumb.forum_title}</a>
    {assign i $i+1}
  {/foreach}
</nav>

<div class="flex flex-col sm:flex-row items-start sm:items-end gap-4 mb-6">
  <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight flex-1">{$FORUM_TITLE}</h1>
  <div class="flex gap-2">
    <form method="post" action="{$SEARCH_URL}" name="searchForm" class="w-72">
      <input type="hidden" name="token" value="{$TOKEN}">
      <div class="nx-input-group">
        <span class="nx-input-group__icon">{include file='components/icon.tpl' name='search' size=14}</span>
        <input type="text" name="forum_search" placeholder="{$SEARCH}" class="nx-input" />
      </div>
    </form>
    {if $NEW_TOPIC_BUTTON}
      <a class="nx-btn nx-btn--primary" href="{$NEW_TOPIC_BUTTON}">{include file='components/icon.tpl' name='plus' size=14}{$NEW_TOPIC}</a>
    {/if}
  </div>
</div>

<div class="grid gap-6
            {if count($WIDGETS_LEFT) && count($WIDGETS_RIGHT)}lg:grid-cols-[16rem_minmax(0,1fr)_16rem]
            {elseif count($WIDGETS_LEFT) || count($WIDGETS_RIGHT)}lg:grid-cols-[16rem_minmax(0,1fr)]
            {else}grid-cols-1{/if}">

  {if count($WIDGETS_LEFT)}<aside class="space-y-4 order-2 lg:order-1">{foreach from=$WIDGETS_LEFT item=widget}{$widget}{/foreach}</aside>{/if}

  <main class="order-1 lg:order-2 min-w-0 space-y-6">
    {if count($SUBFORUMS)}
      <section class="nx-surface overflow-hidden">
        <header class="nx-card__header"><h2 class="nx-card__title">{$SUBFORUM_LANGUAGE}</h2></header>
        <ul class="divide-y divide-border-subtle">
          {foreach from=$SUBFORUMS item=subforum}
            <li class="flex items-center gap-4 px-5 py-4 hover:bg-bg-elevated transition">
              <span class="hidden sm:inline-flex w-9 h-9 rounded-lg bg-accent-subtle text-accent items-center justify-center flex-shrink-0">
                {if empty($subforum.icon)}{include file='components/icon.tpl' name='forum' size=16}{else}{$subforum.icon}{/if}
              </span>
              <div class="flex-1 min-w-0">
                <a href="{$subforum.link}" class="block font-semibold text-text-primary hover:text-accent transition truncate">{$subforum.title}</a>
                {if !$subforum.redirect}<div class="text-xs text-text-muted">{$TOPICS|capitalize}: <span class="text-text-primary font-medium">{$subforum.topics}</span></div>{/if}
              </div>
            </li>
          {/foreach}
        </ul>
      </section>
    {/if}

    <div class="nx-surface p-8 text-center">
      <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-bg-elevated text-text-muted mb-3">
        {include file='components/icon.tpl' name='forum' size=24}
      </div>
      <p class="text-text-secondary">{$NO_TOPICS_FULL}</p>
    </div>
  </main>

  {if count($WIDGETS_RIGHT)}<aside class="space-y-4 order-3">{foreach from=$WIDGETS_RIGHT item=widget}{$widget}{/foreach}</aside>{/if}
</div>

{include file='footer.tpl'}
