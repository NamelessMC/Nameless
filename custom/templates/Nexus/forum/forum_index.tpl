{include file='header.tpl'}
{include file='navbar.tpl'}

<nav class="text-sm text-text-muted mb-4" aria-label="Breadcrumb">
  <a class="text-text-secondary hover:text-text-primary transition" href="{$BREADCRUMB_URL}">{$BREADCRUMB_TEXT}</a>
</nav>

<div class="flex flex-col sm:flex-row items-start sm:items-end gap-4 mb-6">
  <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight flex-1">{$TITLE}</h1>
  <form method="post" action="{$SEARCH_URL}" name="searchForm" class="w-full sm:w-80">
    <input type="hidden" name="token" value="{$TOKEN}">
    <div class="nx-input-group">
      <span class="nx-input-group__icon">{include file='components/icon.tpl' name='search' size=14}</span>
      <input type="text" name="forum_search" placeholder="{$SEARCH}" minlength="3" maxlength="128" class="nx-input" />
    </div>
  </form>
</div>

<div class="grid gap-6
            {if count($WIDGETS_LEFT) && count($WIDGETS_RIGHT)}lg:grid-cols-[16rem_minmax(0,1fr)_16rem]
            {elseif count($WIDGETS_LEFT) || count($WIDGETS_RIGHT)}lg:grid-cols-[16rem_minmax(0,1fr)]
            {else}grid-cols-1{/if}">

  {if count($WIDGETS_LEFT)}
    <aside class="space-y-4 order-2 lg:order-1">{foreach from=$WIDGETS_LEFT item=widget}{$widget}{/foreach}</aside>
  {/if}

  <main class="order-1 lg:order-2 min-w-0">
    {if isset($SPAM_INFO)}
      <div class="nx-alert nx-alert--warning mb-4">
        {include file='components/icon.tpl' name='alert' size=18 class="nx-alert__icon"}
        <div><div class="nx-alert__title">{$FORUM_SPAM_WARNING_TITLE}</div><div class="nx-alert__body">{$SPAM_INFO}</div></div>
      </div>
    {/if}

    <div class="space-y-6">
    {foreach from=$FORUMS key=category item=forum}
      {if !empty($forum.subforums)}
        <section class="nx-surface overflow-hidden">
          <header class="nx-card__header flex items-center justify-between gap-3">
            <h2 class="font-display font-semibold text-text-primary">
              <a href="{$forum.link}" class="hover:text-accent transition">{$forum.title}</a>
            </h2>
          </header>
          <ul class="divide-y divide-border-subtle">
            {foreach from=$forum.subforums item=subforum}
              {if $subforum->redirect_forum neq 1}
                <li class="flex items-center gap-4 px-5 py-4 hover:bg-bg-elevated transition">
                  <span class="hidden sm:inline-flex w-10 h-10 rounded-lg bg-accent-subtle text-accent items-center justify-center flex-shrink-0">
                    {if empty($subforum->icon)}{include file='components/icon.tpl' name='forum' size=18}{else}{$subforum->icon}{/if}
                  </span>
                  <div class="flex-1 min-w-0">
                    <a href="{$subforum->link}" class="block font-semibold text-text-primary hover:text-accent transition truncate">{$subforum->forum_title}</a>
                    <div class="text-xs text-text-muted mt-0.5 truncate">
                      {if !empty($subforum->forum_description)}{$subforum->forum_description}{/if}
                    </div>
                    <div class="flex flex-wrap gap-x-3 gap-y-1 text-xs text-text-muted mt-1">
                      <span>{$TOPICS|capitalize}: <span class="text-text-primary font-medium">{$subforum->topics}</span></span>
                      <span>{$POSTS|capitalize}: <span class="text-text-primary font-medium">{$subforum->posts}</span></span>
                      {if isset($subforum->subforums)}
                        <span class="text-text-muted">·
                          {foreach from=$subforum->subforums item=ss name=ss}
                            <a href="{$ss->link}" class="text-text-secondary hover:text-text-primary">{$ss->title}</a>{if !$smarty.foreach.ss.last}, {/if}
                          {/foreach}
                        </span>
                      {/if}
                    </div>
                  </div>
                  <div class="hidden md:flex items-center gap-3 min-w-[14rem] flex-shrink-0">
                    {if isset($subforum->last_post)}
                      <span class="nx-avatar nx-avatar--sm"><img src="{$subforum->last_post->avatar}" alt="{$subforum->last_post->username}" loading="lazy" /></span>
                      <div class="min-w-0 flex-1">
                        <a href="{$subforum->last_post->link}" class="block text-sm text-text-primary hover:text-accent truncate">{$subforum->last_post->title}</a>
                        <div class="text-xs text-text-muted">
                          <a href="{$subforum->last_post->profile}" style="{$subforum->last_post->user_style}" data-poload="{$USER_INFO_URL}{$subforum->last_post->post_creator}" class="hover:text-text-primary">{$subforum->last_post->username}</a>
                          · <span title="{$subforum->last_post->post_date}">{$subforum->last_post->date_friendly}</span>
                        </div>
                      </div>
                    {else}
                      <span class="text-xs text-text-muted">{$NO_TOPICS}</span>
                    {/if}
                  </div>
                </li>
              {else}
                <li class="flex items-center gap-4 px-5 py-4 hover:bg-bg-elevated transition">
                  <span class="text-accent flex-shrink-0">
                    {if empty($subforum->icon)}{include file='components/icon.tpl' name='external' size=18}{else}{$subforum->icon}{/if}
                  </span>
                  <a class="font-semibold text-text-primary hover:text-accent transition"
                     {if isset($subforum->redirect_confirm)}href="#" data-toggle="modal" data-target="#modal-redirect-{$subforum->id}"
                     {else}href="{$subforum->redirect_url}"{/if}>{$subforum->forum_title}</a>
                </li>
                <div class="ui mini modal" id="modal-redirect-{$subforum->id}">
                  <div class="content">{$subforum->redirect_confirm}</div>
                  <div class="actions">
                    <a class="ui negative button">{$NO}</a>
                    <a class="ui positive button" href="{$subforum->redirect_url}" target="_blank" rel="noopener nofollow">{$YES}</a>
                  </div>
                </div>
              {/if}
            {/foreach}
          </ul>
        </section>
      {/if}
    {/foreach}
    </div>
  </main>

  {if count($WIDGETS_RIGHT)}
    <aside class="space-y-4 order-3">{foreach from=$WIDGETS_RIGHT item=widget}{$widget}{/foreach}</aside>
  {/if}
</div>

{include file='footer.tpl'}
