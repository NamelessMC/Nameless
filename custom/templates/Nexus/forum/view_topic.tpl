{include file='header.tpl'}
{include file='navbar.tpl'}

<nav class="text-sm text-text-muted mb-4 flex flex-wrap items-center gap-1" aria-label="Breadcrumb">
  {assign i 1}
  {foreach from=$BREADCRUMBS item=breadcrumb}
    {if $i ne 1}<span class="text-text-muted">/</span>{/if}
    <a class="hover:text-text-primary transition {if isset($breadcrumb.active)}text-text-primary{else}text-text-secondary{/if}" href="{$breadcrumb.link}">{$breadcrumb.forum_title}</a>
    {assign i $i+1}
  {/foreach}
</nav>

<header class="mb-6">
  <div class="flex flex-wrap items-start gap-2 mb-1">
    {if count($TOPIC_LABELS)}{foreach from=$TOPIC_LABELS item=label}{$label}{/foreach}{/if}
    <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight">{$TOPIC_TITLE}</h1>
  </div>
  <p class="text-text-secondary text-sm">{$STARTED_BY}</p>
</header>

{if isset($SESSION_SUCCESS_POST)}
  <div class="nx-alert nx-alert--success mb-4">{include file='components/icon.tpl' name='check' size=18 class="nx-alert__icon"}<div><div class="nx-alert__title">{$SUCCESS}</div><div class="nx-alert__body">{$SESSION_SUCCESS_POST}</div></div></div>
{/if}
{if isset($SESSION_FAILURE_POST)}
  <div class="nx-alert nx-alert--danger mb-4">{include file='components/icon.tpl' name='alert' size=18 class="nx-alert__icon"}<div><div class="nx-alert__title">{$ERROR}</div><div class="nx-alert__body">{$SESSION_FAILURE_POST}</div></div></div>
{/if}
{if isset($ERRORS)}
  <div class="nx-alert nx-alert--danger mb-4">{include file='components/icon.tpl' name='alert' size=18 class="nx-alert__icon"}<div class="flex-1"><div class="nx-alert__title">{$ERROR_TITLE}</div><ul class="nx-alert__body list-disc pl-4">{foreach from=$ERRORS item=error}<li>{$error}</li>{/foreach}</ul></div></div>
{/if}

<div class="flex flex-wrap items-center justify-between gap-3 mb-4">
  <div>{$PAGINATION}</div>
  <div class="flex flex-wrap gap-2">
    {if isset($UNFOLLOW)}
      <form action="{$UNFOLLOW_URL}" method="post" class="inline">
        <input type="hidden" value="{$TOKEN}" name="token" />
        <button class="nx-btn nx-btn--outline nx-btn--sm">{include file='components/icon.tpl' name='bell' size=14}{$UNFOLLOW}</button>
      </form>
    {elseif isset($FOLLOW)}
      <form action="{$FOLLOW_URL}" method="post" class="inline">
        <input type="hidden" value="{$TOKEN}" name="token" />
        <button class="nx-btn nx-btn--outline nx-btn--sm">{include file='components/icon.tpl' name='bell' size=14}{$FOLLOW}</button>
      </form>
    {/if}
    {if isset($CAN_MODERATE)}
      <form action="{$LOCK_URL}" method="post" id="lockTopic" class="hidden"><input type="hidden" value="{$TOKEN}" name="token" /></form>
      <form action="{$STICK_URL}" method="post" id="stickTopic" class="hidden"><input type="hidden" value="{$TOKEN}" name="token" /></form>
      <div class="nx-dropdown" x-data="{ open: false }">
        <button class="nx-btn nx-btn--outline nx-btn--sm" @click="open = !open" @click.away="open = false">
          {include file='components/icon.tpl' name='shield' size=14}{$MOD_ACTIONS}
          {include file='components/icon.tpl' name='chevron-down' size=12}
        </button>
        <div class="nx-dropdown__panel right-0" x-show="open" x-cloak x-transition>
          <div class="nx-dropdown__header">{$MOD_ACTIONS}</div>
          <a class="nx-dropdown__item" onclick="document.getElementById('lockTopic').submit()">{include file='components/icon.tpl' name='lock' size=14}{$LOCK}</a>
          <a class="nx-dropdown__item" href="{$MERGE_URL}">{$MERGE}</a>
          <a class="nx-dropdown__item nx-dropdown__item--danger" data-toggle="modal" data-target="#modal-delete">{include file='components/icon.tpl' name='trash' size=14}{$DELETE}</a>
          <a class="nx-dropdown__item" href="{$MOVE_URL}">{$MOVE}</a>
          <a class="nx-dropdown__item" onclick="document.getElementById('stickTopic').submit()">{include file='components/icon.tpl' name='pin' size=14}{$STICK}</a>
        </div>
      </div>
    {/if}
    <div class="nx-dropdown" x-data="{ open: false }">
      <button class="nx-btn nx-btn--outline nx-btn--sm" @click="open = !open" @click.away="open = false">
        {include file='components/icon.tpl' name='external' size=14}{$SHARE}
        {include file='components/icon.tpl' name='chevron-down' size=12}
      </button>
      <div class="nx-dropdown__panel right-0" x-show="open" x-cloak x-transition>
        <div class="nx-dropdown__header">{$SHARE}</div>
        <a class="nx-dropdown__item" href="{$SHARE_TWITTER_URL}">{$SHARE_TWITTER}</a>
        <a class="nx-dropdown__item" href="{$SHARE_FACEBOOK_URL}">{$SHARE_FACEBOOK}</a>
      </div>
    </div>
  </div>
</div>

{if isset($TOPIC_LOCKED_NOTICE)}<div class="nx-alert nx-alert--warning mb-4">{include file='components/icon.tpl' name='lock' size=18 class="nx-alert__icon"}<div class="nx-alert__body">{$TOPIC_LOCKED_NOTICE}</div></div>
{elseif isset($TOPIC_LOCKED)}<div class="nx-alert nx-alert--warning mb-4">{include file='components/icon.tpl' name='lock' size=18 class="nx-alert__icon"}<div class="nx-alert__body">{$TOPIC_LOCKED}</div></div>{/if}

<div class="space-y-4">
  {foreach from=$REPLIES item=reply}
    <article class="nx-surface overflow-hidden" id="topic-post" post-id="{$reply.id}">
      <div class="grid lg:grid-cols-[14rem_minmax(0,1fr)]">
        <aside class="bg-bg-inset p-4 sm:p-5 border-b lg:border-b-0 lg:border-r border-border-subtle" id="post-sidebar">
          <div class="text-center">
            <a href="{$reply.profile}" class="inline-block">
              <span class="nx-avatar nx-avatar--lg mx-auto"><img src="{$reply.avatar}" alt="{$reply.username}" loading="lazy" /></span>
            </a>
            <a href="{$reply.profile}" style="{$reply.user_style}" class="block mt-2 font-medium text-text-primary hover:text-accent transition truncate">{$reply.username}</a>
            {if isset($reply.user_title)}<div class="text-xs text-text-muted mt-0.5">{$reply.user_title}</div>{/if}
          </div>
          <div class="flex flex-wrap gap-1 justify-center mt-3 groups">
            {foreach from=$reply.user_groups item=group}{$group}{/foreach}
          </div>
          <dl class="mt-4 pt-4 border-t border-border-subtle space-y-1.5 text-xs">
            <div class="flex justify-between gap-2"><dt class="text-text-muted">{$reply.user_registered|regex_replace:'/[:].*/':''}</dt><dd class="text-text-primary">{$reply.user_registered_full}</dd></div>
            <div class="flex justify-between gap-2"><dt class="text-text-muted">{$reply.last_seen|regex_replace:'/[:].*/':''}</dt><dd class="text-text-primary">{$reply.last_seen_full}</dd></div>
            <div class="flex justify-between gap-2"><dt class="text-text-muted">{$reply.user_topics_count|regex_replace:'/[0-9]+/':''|capitalize}</dt><dd class="text-text-primary">{$reply.user_topics_count|regex_replace:'/[^0-9]+/':''}</dd></div>
            <div class="flex justify-between gap-2"><dt class="text-text-muted">{$reply.user_posts_count|regex_replace:'/[0-9]+/':''|capitalize}</dt><dd class="text-text-primary">{$reply.user_posts_count|regex_replace:'/[^0-9]+/':''}</dd></div>
          </dl>
          {if count($reply.fields)}
            <dl class="mt-4 pt-4 border-t border-border-subtle space-y-1.5 text-xs">
              {foreach from=$reply.fields item=field}
                {if !empty($field->value)}
                  <div class="flex justify-between gap-2"><dt class="text-text-muted">{$field->name}</dt><dd class="text-text-primary">{$field->value}</dd></div>
                {/if}
              {/foreach}
            </dl>
          {/if}
        </aside>

        <div class="p-5 sm:p-6 min-w-0" id="post-content">
          <div class="forum_post">{$reply.content}</div>
          {if !empty($reply.signature)}
            <hr class="my-4 border-border-subtle" />
            <div class="text-sm text-text-muted overflow-auto max-h-[500px]">{$reply.signature}</div>
          {/if}
          {if $REACTIONS_ENABLED && ((isset($LOGGED_IN_USER) && $reply.user_id !== $USER_ID) || count($reply.post_reactions))}
            <div class="flex items-center justify-between flex-wrap gap-2 mt-4 pt-4 border-t border-border-subtle" id="reactions">
              <div class="flex flex-wrap gap-1.5">
                {foreach from=$reply.post_reactions name=reactions item=reaction}
                  <button class="nx-badge nx-badge--accent cursor-pointer" onclick="openReactionModal({$reply.id}, {$reaction.id})" title="{$reaction.name}">
                    {$reaction.html} {$reaction.count}
                  </button>
                {/foreach}
              </div>
              {if (isset($LOGGED_IN_USER) && $reply.user_id !== $USER_ID)}
                <div class="flex flex-wrap gap-1">
                  {foreach from=$REACTIONS item=reaction}
                    <button title="{$reaction->name}"
                            class="px-2 py-1 rounded-md hover:bg-bg-elevated transition {if array_key_exists($reply.id, $REACTIONS_BY_USER) && in_array($reaction->id, $REACTIONS_BY_USER[$reply.id])}bg-accent-subtle reaction-button-selected{else}reaction-button{/if}"
                            onclick="submitReaction({$reply.id}, {$reaction->id});">{$reaction->html}</button>
                  {/foreach}
                </div>
              {/if}
            </div>
          {/if}
        </div>
      </div>

      <footer class="bg-bg-inset border-t border-border-subtle px-5 py-2.5 flex items-center justify-between flex-wrap gap-2 text-xs text-text-muted" id="post-meta">
        <span>
          <a href="{$reply.profile}" data-poload="{$USER_INFO_URL}{$reply.user_id}" style="{$reply.user_style}" class="text-text-secondary hover:text-text-primary">{$reply.username}</a>
          · <span title="{$reply.post_date}">{$reply.post_date_rough}</span>
          {if $reply.edited !== null} · <span title="{$reply.edited_full}">{$reply.edited}</span>{/if}
        </span>
        <div class="flex gap-1">
          {if isset($reply.buttons.spam)}
            <button class="nx-btn nx-btn--ghost nx-btn--icon nx-btn--sm" data-toggle="modal" data-target="#modal-spam-{$reply.id}" title="{$reply.buttons.spam.TEXT}">{include file='components/icon.tpl' name='alert' size=14}</button>
          {/if}
          {if isset($reply.buttons.edit)}
            <a class="nx-btn nx-btn--ghost nx-btn--icon nx-btn--sm" href="{$reply.buttons.edit.URL}" title="{$reply.buttons.edit.TEXT}">{include file='components/icon.tpl' name='edit' size=14}</a>
          {/if}
          {if isset($reply.buttons.delete)}
            <button class="nx-btn nx-btn--ghost nx-btn--icon nx-btn--sm" data-toggle="modal" data-target="#modal-delete-{$reply.id}" title="{$reply.buttons.delete.TEXT}">{include file='components/icon.tpl' name='trash' size=14}</button>
          {/if}
          {if isset($reply.buttons.report)}
            <button class="nx-btn nx-btn--ghost nx-btn--icon nx-btn--sm" data-toggle="modal" data-target="#modal-report-{$reply.id}" title="{$reply.buttons.report.TEXT}">{include file='components/icon.tpl' name='alert' size=14}</button>
          {/if}
          {if isset($reply.buttons.quote)}
            <button class="nx-btn nx-btn--ghost nx-btn--icon nx-btn--sm" onclick="quote({$reply.id})" title="{$reply.buttons.quote.TEXT}">{include file='components/icon.tpl' name='reply' size=14}</button>
          {/if}
        </div>
      </footer>
    </article>
  {/foreach}
</div>

{if isset($TOPIC_LOCKED_NOTICE)}<div class="nx-alert nx-alert--warning my-4">{include file='components/icon.tpl' name='lock' size=18 class="nx-alert__icon"}<div class="nx-alert__body">{$TOPIC_LOCKED_NOTICE}</div></div>{/if}

<div class="my-4">{$PAGINATION}</div>

{if isset($CAN_REPLY)}
  <section class="nx-surface overflow-hidden" id="topic-reply">
    <div class="grid lg:grid-cols-[14rem_minmax(0,1fr)]">
      <aside class="bg-bg-inset p-4 sm:p-5 text-center border-b lg:border-b-0 lg:border-r border-border-subtle" id="reply-sidebar">
        <span class="nx-avatar nx-avatar--lg mx-auto"><img src="{$LOGGED_IN_USER.avatar}" alt="" /></span>
        <a href="{$LOGGED_IN_USER.profile}" style="{$LOGGED_IN_USER.username_style}" class="block mt-2 font-medium text-text-primary hover:text-accent transition truncate">{$LOGGED_IN_USER.username}</a>
      </aside>
      <div class="p-5 sm:p-6" id="reply-content">
        <form class="space-y-4" action="" method="post">
          <textarea name="content" id="quickreply" class="nx-textarea" placeholder="Reply…"></textarea>
          <input type="hidden" name="token" value="{$TOKEN}">
          <button type="submit" class="nx-btn nx-btn--primary">{$SUBMIT}</button>
        </form>
      </div>
    </div>
  </section>
{/if}

{if $REACTIONS_ENABLED}
  <div class="ui small modal" id="modal-reactions">
    <i class="close icon"></i>
    <div class="header">{$REACTIONS_TEXT}</div>
    <div class="scrolling content"></div>
  </div>
{/if}

{foreach from=$REPLIES item=reply}
  {if isset($reply.buttons.report)}
    <div class="ui small modal" id="modal-report-{$reply.id}">
      <div class="header">{$reply.buttons.report.TEXT}</div>
      <div class="content">
        <form action="{$reply.buttons.report.URL}" method="post" id="form-report-{$reply.id}">
          <label class="nx-label" for="InputReason-{$reply.id}">{$reply.buttons.report.REPORT_TEXT}</label>
          <textarea id="InputReason-{$reply.id}" name="reason" class="nx-textarea"></textarea>
          <input type="hidden" name="post" value="{$reply.id}">
          <input type="hidden" name="topic" value="{$TOPIC_ID}">
          <input type="hidden" name="token" value="{$TOKEN}">
        </form>
      </div>
      <div class="actions">
        <a class="ui negative button">{$CANCEL}</a>
        <a class="ui positive button" onclick="$('#form-report-{$reply.id}').submit();">{$reply.buttons.report.TEXT}</a>
      </div>
    </div>
  {/if}
  {if isset($CAN_MODERATE)}
    <div class="ui small modal" id="modal-spam-{$reply.id}">
      <div class="header">{$MARK_AS_SPAM}</div>
      <div class="content">
        {$CONFIRM_SPAM_POST}
        <form action="{$reply.buttons.spam.URL}" method="post" id="form-spam-{$reply.id}">
          <input type="hidden" name="post" value="{$reply.id}">
          <input type="hidden" name="token" value="{$TOKEN}">
        </form>
      </div>
      <div class="actions">
        <a class="ui negative button">{$CANCEL}</a>
        <a class="ui positive button" onclick="$('#form-spam-{$reply.id}').submit();">{$MARK_AS_SPAM}</a>
      </div>
    </div>
    <div class="ui small modal" id="modal-delete-{$reply.id}">
      <div class="header">{$CONFIRM_DELETE_SHORT}</div>
      <div class="content">
        {$CONFIRM_DELETE_POST}
        <form action="{$reply.buttons.delete.URL}" method="post" id="form-delete-{$reply.id}">
          <input type="hidden" name="tid" value="{$TOPIC_ID}">
          <input type="hidden" name="number" value="{$reply.buttons.delete.NUMBER}">
          <input type="hidden" name="pid" value="{$reply.id}">
          <input type="hidden" name="token" value="{$TOKEN}">
        </form>
      </div>
      <div class="actions">
        <a class="ui negative button">{$CANCEL}</a>
        <a class="ui positive button" onclick="$('#form-delete-{$reply.id}').submit();">{$reply.buttons.delete.TEXT}</a>
      </div>
    </div>
  {/if}
{/foreach}

{if isset($CAN_MODERATE)}
  <div class="ui small modal" id="modal-delete">
    <div class="header">{$CONFIRM_DELETE_SHORT}</div>
    <div class="content">{$CONFIRM_DELETE}</div>
    <div class="actions">
      <a class="ui negative button">{$CANCEL}</a>
      <form action="{$DELETE_URL}" method="post" id="deleteTopic" class="hidden">
        <input type="hidden" value="{$TOKEN}" name="token" />
      </form>
      <a class="ui positive button" onclick="document.getElementById('deleteTopic').submit()">{$DELETE}</a>
    </div>
  </div>
{/if}

{if $REACTIONS_ENABLED}
<script>
  const submitReaction = (post_id, reaction_id) => {
    $.post("{$REACTIONS_URL}", { token: "{$TOKEN}", reaction_id, reactable_id: post_id, context: 'forum_post' })
      .done((r) => { if (r.startsWith('Reaction ')) { window.location.replace(window.location.href.replace(/#.*$/, '') + '#post-' + post_id); window.location.reload(); } else console.error(r); })
      .fail(console.error);
  };
  const openReactionModal = (post_id, reaction_id) => {
    const modal = $('#modal-reactions'); modal.modal('show');
    modal.find('.content').html('<div class="text-center py-4"><span class="nx-spinner inline-block"></span></div>');
    $.get("{$REACTIONS_URL}", { reactable_id: post_id, context: 'forum_post', tab: reaction_id })
      .done((r) => modal.find('.content').html(r))
      .fail(console.error);
  };
</script>
{/if}

{include file='footer.tpl'}
