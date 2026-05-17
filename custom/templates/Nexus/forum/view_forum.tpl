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
  <div class="flex-1">
    <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight">{$FORUM_TITLE}</h1>
  </div>
  <div class="flex gap-2 w-full sm:w-auto">
    <form method="post" action="{$SEARCH_URL}" name="searchForm" class="flex-1 sm:w-72">
      <input type="hidden" name="token" value="{$TOKEN}">
      <div class="nx-input-group">
        <span class="nx-input-group__icon">{include file='components/icon.tpl' name='search' size=14}</span>
        <input type="text" name="forum_search" placeholder="{$SEARCH}" class="nx-input" />
      </div>
    </form>
    {if $NEW_TOPIC_BUTTON}
      <a class="nx-btn nx-btn--primary" href="{$NEW_TOPIC_BUTTON}">
        {include file='components/icon.tpl' name='plus' size=14}
        <span class="hidden sm:inline">{$NEW_TOPIC}</span>
      </a>
    {/if}
  </div>
</div>

{if isset($PAGINATION)}<div class="flex justify-end mb-4">{$PAGINATION}</div>{/if}

<div class="grid gap-6
            {if count($WIDGETS_LEFT) && count($WIDGETS_RIGHT)}lg:grid-cols-[16rem_minmax(0,1fr)_16rem]
            {elseif count($WIDGETS_LEFT) || count($WIDGETS_RIGHT)}lg:grid-cols-[16rem_minmax(0,1fr)]
            {else}grid-cols-1{/if}">

  {if count($WIDGETS_LEFT)}
    <aside class="space-y-4 order-2 lg:order-1">{foreach from=$WIDGETS_LEFT item=widget}{$widget}{/foreach}</aside>
  {/if}

  <main class="order-1 lg:order-2 min-w-0 space-y-6">

    {if count($SUBFORUMS)}
      <section class="nx-surface overflow-hidden" id="subforums-table">
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
              {if !$subforum.redirect && !empty($subforum.latest_post)}
                <div class="hidden md:flex items-center gap-3 min-w-[14rem] flex-shrink-0">
                  <span class="nx-avatar nx-avatar--sm"><img src="{$subforum.latest_post.last_user_avatar}" loading="lazy" alt="" /></span>
                  <div class="min-w-0 flex-1">
                    <a href="{$subforum.latest_post.link}" class="block text-sm text-text-primary hover:text-accent truncate">{$subforum.latest_post.title}</a>
                    <div class="text-xs text-text-muted">
                      <a href="{$subforum.latest_post.last_user_link}" style="{$subforum.latest_post.last_user_style}" data-poload="{$USER_INFO_URL}{$subforum.latest_post.last_user_id}">{$subforum.latest_post.last_user}</a>
                      · <span title="{$subforum.latest_post.time}">{$subforum.latest_post.timeago}</span>
                    </div>
                  </div>
                </div>
              {/if}
            </li>
          {/foreach}
        </ul>
      </section>
    {/if}

    {if count($STICKY_DISCUSSIONS)}
      <section class="nx-surface overflow-hidden" id="sticky-threads">
        <header class="nx-card__header flex items-center gap-2">
          {include file='components/icon.tpl' name='pin' size=14 class="text-accent"}
          <h2 class="nx-card__title">{$STICKY_TOPICS}</h2>
        </header>
        <ul class="divide-y divide-border-subtle">
          {foreach from=$STICKY_DISCUSSIONS item=discussion}
            {include file='forum/_topic_row.tpl' discussion=$discussion}
          {/foreach}
        </ul>
      </section>
    {/if}

    {if count($LATEST_DISCUSSIONS)}
      <section class="nx-surface overflow-hidden" id="normal-threads">
        <header class="nx-card__header"><h2 class="nx-card__title">{$TOPICS|capitalize}</h2></header>
        <ul class="divide-y divide-border-subtle">
          {foreach from=$LATEST_DISCUSSIONS item=discussion}
            {include file='forum/_topic_row.tpl' discussion=$discussion}
          {/foreach}
        </ul>
      </section>
    {/if}

    {if isset($NO_TOPICS_FULL) && !count($LATEST_DISCUSSIONS) && !count($STICKY_DISCUSSIONS)}
      <div class="nx-alert nx-alert--info">
        {include file='components/icon.tpl' name='info' size=18 class="nx-alert__icon"}
        <div class="nx-alert__body">{$NO_TOPICS_FULL}</div>
      </div>
    {/if}

    {if isset($PAGINATION)}<div class="flex justify-center">{$PAGINATION}</div>{/if}
  </main>

  {if count($WIDGETS_RIGHT)}
    <aside class="space-y-4 order-3">{foreach from=$WIDGETS_RIGHT item=widget}{$widget}{/foreach}</aside>
  {/if}
</div>

{include file='footer.tpl'}
