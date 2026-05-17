{include file='header.tpl'}
{include file='navbar.tpl'}

<h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight mb-6">{$USER_CP}</h1>

<div class="grid lg:grid-cols-[18rem_minmax(0,1fr)] gap-6" id="alerts">
  <aside>{include file='user/navigation.tpl'}</aside>

  <main class="min-w-0">
    <section class="nx-surface overflow-hidden">
      <header class="nx-card__header flex items-center justify-between">
        <h2 class="nx-card__title">{$FOLLOWING_TOPICS}</h2>
        {if count($TOPICS_LIST)}
          <a class="nx-btn nx-btn--outline nx-btn--sm" href="#" data-toggle="modal" data-target="#modal-delete">
            {include file='components/icon.tpl' name='trash' size=14}{$UNFOLLOW_ALL}
          </a>
        {/if}
      </header>

      {if isset($SUCCESS_MESSAGE)}
        <div class="px-5 pt-4">
          <div class="nx-alert nx-alert--success">
            {include file='components/icon.tpl' name='check' size=18 class="nx-alert__icon"}
            <div><div class="nx-alert__title">{$SUCCESS}</div><div class="nx-alert__body">{$SUCCESS_MESSAGE}</div></div>
          </div>
        </div>
      {/if}

      {nocache}
      {if count($TOPICS_LIST)}
        <ul class="divide-y divide-border-subtle">
          {foreach from=$TOPICS_LIST item=topic}
            <li class="flex items-center gap-3 px-5 py-4 hover:bg-bg-elevated transition cursor-pointer"
                onclick="window.location.href = '{$topic.last_post_link}'">
              <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                  {if $topic.unread}{include file='components/icon.tpl' name='bell' size=14 class="text-accent"}{/if}
                  <span class="font-medium text-text-primary {if $topic.unread}font-bold{/if} truncate">{$topic.topic_title}</span>
                </div>
              </div>
              <div class="hidden sm:flex items-center gap-2 min-w-0">
                <span class="nx-avatar nx-avatar--sm"><img src="{$topic.reply_author_avatar}" loading="lazy" alt="" /></span>
                <div class="min-w-0">
                  <a href="{$topic.reply_author_link}" data-poload="{$USER_INFO_URL}{$topic.reply_author_id}" style="{$topic.reply_author_style}" onclick="event.stopPropagation()" class="text-sm text-text-primary hover:text-accent truncate block">{$topic.reply_author_nickname}</a>
                  <div class="text-xs text-text-muted" title="{$topic.reply_date_full}">{$topic.reply_date}</div>
                </div>
              </div>
              <form action="{$topic.unfollow_link}" method="post" onclick="event.stopPropagation()" class="flex-shrink-0">
                <input type="hidden" value="{$TOKEN}" name="token" />
                <button class="nx-btn nx-btn--danger nx-btn--sm">{$UNFOLLOW_TOPIC}</button>
              </form>
            </li>
          {/foreach}
        </ul>
        <div class="p-4 border-t border-border-subtle">{$PAGINATION}</div>
      {else}
        <div class="p-6">
          <div class="nx-alert nx-alert--info">
            {include file='components/icon.tpl' name='info' size=18 class="nx-alert__icon"}
            <div class="nx-alert__body">{$NO_TOPICS}</div>
          </div>
        </div>
      {/if}
      {/nocache}
    </section>
  </main>
</div>

<div class="ui small modal" id="modal-delete">
  <div class="header">{$UNFOLLOW_ALL}</div>
  <div class="content">{$CONFIRM_UNFOLLOW}</div>
  <div class="actions">
    <a class="ui negative button">{$NO}</a>
    <form action="" method="post" class="inline">
      <input type="hidden" name="token" value="{$TOKEN}">
      <input type="hidden" name="action" value="purge">
      <button type="submit" class="ui positive button">{$YES}</button>
    </form>
  </div>
</div>

{include file='footer.tpl'}
