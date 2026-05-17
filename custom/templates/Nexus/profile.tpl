{include file='header.tpl'}
{include file='navbar.tpl'}

{* === Profile hero with banner === *}
<div class="nx-surface overflow-hidden mb-6">
  <div class="relative h-44 sm:h-56 lg:h-64 bg-bg-elevated bg-cover bg-center"
       {if isset($BANNER)}style="background-image:linear-gradient(180deg, transparent 0%, var(--bg-surface) 100%), url('{$BANNER}')"{/if}>
    {if isset($LOGGED_IN)}
      <div class="absolute top-3 right-3 flex gap-1.5">
        {if isset($SELF)}
          <a class="nx-btn nx-btn--ghost nx-btn--icon nx-btn--sm bg-bg-overlay backdrop-blur" href="{$SETTINGS_LINK}" title="Settings">
            {include file='components/icon.tpl' name='settings' size=16}
          </a>
          <button type="button" class="nx-btn nx-btn--ghost nx-btn--icon nx-btn--sm bg-bg-overlay backdrop-blur"
                  onclick="showBannerSelect()" title="Change banner">
            {include file='components/icon.tpl' name='edit' size=16}
          </button>
        {else}
          {if ($MOD_OR_ADMIN != true)}
            <button class="nx-btn nx-btn--ghost nx-btn--icon nx-btn--sm bg-bg-overlay backdrop-blur"
                    @click="$store.modal.open('block')" title="Block">
              {include file='components/icon.tpl' name='lock' size=16}
            </button>
          {/if}
          <a class="nx-btn nx-btn--ghost nx-btn--icon nx-btn--sm bg-bg-overlay backdrop-blur"
             href="{$MESSAGE_LINK}" title="Message">
            {include file='components/icon.tpl' name='mail' size=16}
          </a>
          {if isset($RESET_PROFILE_BANNER)}
            <form action="{$RESET_PROFILE_BANNER_LINK}" method="post" class="inline">
              <input type="hidden" name="token" value="{$TOKEN}" />
              <button class="nx-btn nx-btn--ghost nx-btn--icon nx-btn--sm bg-bg-overlay backdrop-blur"
                      title="{$RESET_PROFILE_BANNER}">
                {include file='components/icon.tpl' name='trash' size=16}
              </button>
            </form>
          {/if}
        {/if}
      </div>
    {/if}
  </div>

  <div class="px-6 sm:px-8 pb-6 -mt-12 sm:-mt-16 relative">
    <div class="flex flex-col sm:flex-row items-start sm:items-end gap-4 sm:gap-6">
      <span class="nx-avatar nx-avatar--2xl ring-4 ring-bg-surface">
        <img src="{$AVATAR}" alt="{$NICKNAME}" />
      </span>
      <div class="flex-1 min-w-0 sm:pb-3">
        <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight">
          <span {if $USERNAME_COLOUR != false}style="{$USERNAME_COLOUR}"{/if}>{$NICKNAME}</span>
        </h1>
        {if isset($USER_TITLE)}<p class="text-text-secondary text-sm mt-0.5">{$USER_TITLE}</p>{/if}
        <div class="flex flex-wrap gap-1.5 mt-3">
          {foreach from=$GROUPS item=group}{$group}{/foreach}
        </div>
      </div>
    </div>
  </div>
</div>

<div class="grid gap-6
            {if $CAN_VIEW && count($WIDGETS_LEFT) && count($WIDGETS_RIGHT)}lg:grid-cols-[18rem_minmax(0,1fr)_18rem]
            {elseif $CAN_VIEW && (count($WIDGETS_LEFT) || count($WIDGETS_RIGHT))}lg:grid-cols-[18rem_minmax(0,1fr)]
            {else}grid-cols-1{/if}">

  {if $CAN_VIEW && count($WIDGETS_LEFT)}
    <aside class="space-y-4 order-2 lg:order-1">{foreach from=$WIDGETS_LEFT item=widget}{$widget}{/foreach}</aside>
  {/if}

  <main class="order-1 lg:order-2 min-w-0">
    {if isset($SUCCESS)}
      <div class="nx-alert nx-alert--success mb-4">
        {include file='components/icon.tpl' name='check' size=18 class="nx-alert__icon"}
        <div><div class="nx-alert__title">{$SUCCESS_TITLE}</div><div class="nx-alert__body">{$SUCCESS}</div></div>
      </div>
    {/if}
    {if isset($ERROR)}
      <div class="nx-alert nx-alert--danger mb-4">
        {include file='components/icon.tpl' name='alert' size=18 class="nx-alert__icon"}
        <div><div class="nx-alert__title">{$ERROR_TITLE}</div><div class="nx-alert__body">{$ERROR}</div></div>
      </div>
    {/if}

    {if $CAN_VIEW}
      <div class="nx-tabs mb-4">
        <a class="nx-tab is-active" data-tab="feed">{$FEED}</a>
        <a class="nx-tab" data-tab="about">{$ABOUT}</a>
        {foreach from=$TABS key=key item=tab}<a class="nx-tab" data-tab="{$key}">{$tab.title}</a>{/foreach}
      </div>

      {* === Feed tab === *}
      <div class="ui bottom attached tab active" data-tab="feed" id="profile-feed">
        {if isset($LOGGED_IN) && $CAN_PROFILE_POST}
          <form class="nx-surface p-4 mb-4 space-y-3" action="" method="post" id="form-profile-post">
            <textarea name="post" placeholder="{$POST_ON_WALL}" class="nx-textarea"></textarea>
            <input type="hidden" name="action" value="new_post">
            <input type="hidden" name="token" value="{$TOKEN}">
            <div class="flex justify-end">
              <button type="submit" class="nx-btn nx-btn--primary nx-btn--sm">{$SUBMIT}</button>
            </div>
          </form>
        {/if}

        {if count($WALL_POSTS)}
          <div class="space-y-3">
            {foreach from=$WALL_POSTS item=post}
              <article class="nx-surface p-4 sm:p-5" id="post-{$post.id}">
                <div class="flex gap-3">
                  <span class="nx-avatar nx-avatar--sm flex-shrink-0">
                    <img src="{$post.avatar}" alt="{$post.nickname}" loading="lazy" />
                  </span>
                  <div class="flex-1 min-w-0">
                    <div class="flex items-center flex-wrap gap-2 text-sm mb-1">
                      <a href="{$post.profile}" data-poload="{$USER_INFO_URL}{$post.user_id}" style="{$post.user_style}"
                         class="font-medium text-text-primary hover:text-accent transition">{$post.nickname}</a>
                      <span class="text-text-muted text-xs" title="{$post.date}">{$post.date_rough}</span>
                    </div>
                    <div class="forum_post text-text-secondary">{$post.content}</div>

                    {if ((isset($LOGGED_IN_USER) && $post.user_id != $USER_ID) || count($post.reactions))}
                      <div class="flex items-center justify-between flex-wrap gap-2 mt-3 pt-3 border-t border-border-subtle" id="reactions">
                        <div class="flex flex-wrap gap-1.5">
                          {foreach from=$post.reactions item=reaction}
                            <button class="nx-badge nx-badge--accent cursor-pointer"
                                    onclick="openReactionModal({$post.id}, {$reaction.id})" title="{$reaction.name}">
                              {$reaction.html} {$reaction.count}
                            </button>
                          {/foreach}
                        </div>
                        {if (isset($LOGGED_IN_USER) && $post.user_id != $USER_ID)}
                          <div class="flex flex-wrap gap-1">
                            {foreach from=$REACTIONS item=reaction}
                              <button title="{$reaction->name}"
                                      class="px-2 py-1 rounded-md hover:bg-bg-elevated transition {if array_key_exists($post.id, $REACTIONS_BY_USER) && in_array($reaction->id, $REACTIONS_BY_USER[$post.id])}bg-accent-subtle reaction-button-selected{else}reaction-button{/if}"
                                      onclick="submitReaction({$post.id}, {$reaction->id});">
                                {$reaction->html}
                              </button>
                            {/foreach}
                          </div>
                        {/if}
                      </div>
                    {/if}

                    {if isset($LOGGED_IN_USER)}
                      <div class="flex flex-wrap gap-3 mt-3 text-xs text-text-muted">
                        {if $CAN_PROFILE_POST}
                          <a class="hover:text-text-primary cursor-pointer" data-toggle="modal" data-target="#modal-reply-{$post.id}">
                            {$REPLY}{if ($post.replies.count|regex_replace:'/[^0-9]+/':'' !=0)} ({$post.replies.count|regex_replace:'/[^0-9]+/':''}){/if}
                          </a>
                        {/if}
                        {if (isset($CAN_MODERATE) && $CAN_MODERATE == 1) || $post.self == 1}
                          <a class="hover:text-text-primary cursor-pointer" data-toggle="modal" data-target="#modal-edit-{$post.id}">{$EDIT}</a>
                          <a class="hover:text-danger cursor-pointer" onclick="{literal}if(confirm(confirmDelete)){$('#form-delete-post-{/literal}{$post.id}{literal}').submit();}{/literal}">{$DELETE}</a>
                          <form action="" method="post" id="form-delete-post-{$post.id}" class="hidden">
                            <input type="hidden" name="post_id" value="{$post.id}">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="token" value="{$TOKEN}">
                          </form>
                        {/if}
                      </div>
                    {/if}

                    {if isset($post.replies.replies)}
                      <ul class="space-y-2 mt-3 pl-4 border-l border-border-subtle">
                        {foreach from=$post.replies.replies item=item}
                          <li class="flex gap-2.5">
                            <span class="nx-avatar nx-avatar--xs flex-shrink-0">
                              <img src="{$item.avatar}" alt="{$item.nickname}" loading="lazy" />
                            </span>
                            <div class="flex-1 min-w-0">
                              <div class="text-xs flex items-center flex-wrap gap-2">
                                <a href="{$item.profile}" style="{$item.style}" class="font-medium text-text-primary hover:text-accent transition">{$item.nickname}</a>
                                <span class="text-text-muted" title="{$item.time_full}">{$item.time_friendly}</span>
                              </div>
                              <div class="forum_post text-sm text-text-secondary mt-1">{$item.content}</div>
                              {if (isset($CAN_MODERATE) && $CAN_MODERATE eq 1) || $post.self eq 1}
                                <form class="hidden" action="" method="post" id="form-delete-{$item.id}">
                                  <input type="hidden" name="action" value="deleteReply">
                                  <input type="hidden" name="token" value="{$TOKEN}">
                                  <input type="hidden" name="post_id" value="{$item.id}">
                                </form>
                                <a class="text-xs text-text-muted hover:text-danger cursor-pointer mt-1 inline-block"
                                   onclick="{literal}if(confirm(confirmDelete)){$('#form-delete-{/literal}{$item.id}{literal}').submit();}{/literal}">
                                  {$DELETE}
                                </a>
                              {/if}
                            </div>
                          </li>
                        {/foreach}
                      </ul>
                    {/if}
                  </div>
                </div>
              </article>
            {/foreach}
          </div>
          <div class="mt-4">{$PAGINATION}</div>
        {else}
          <div class="nx-alert nx-alert--info">
            {include file='components/icon.tpl' name='info' size=18 class="nx-alert__icon"}
            <div class="nx-alert__body">{$NO_WALL_POSTS}</div>
          </div>
        {/if}
      </div>

      {* === About tab === *}
      <div class="ui bottom attached tab" data-tab="about" id="profile-about">
        <div class="nx-surface p-6">
          <h3 class="font-display text-lg font-semibold mb-4">{$ABOUT}</h3>
          <dl class="grid sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
            <div class="flex items-start gap-3">
              <span class="text-text-muted mt-0.5">{include file='components/icon.tpl' name='user' size=16}</span>
              <div class="min-w-0">
                <dt class="text-text-muted text-xs">{$ABOUT_FIELDS.registered.title|replace:':':''}</dt>
                <dd class="text-text-primary">{$ABOUT_FIELDS.registered.value}</dd>
              </div>
            </div>
            <div class="flex items-start gap-3">
              <span class="text-text-muted mt-0.5">{include file='components/icon.tpl' name='clock' size=16}</span>
              <div class="min-w-0">
                <dt class="text-text-muted text-xs">{$ABOUT_FIELDS.last_seen.title|replace:':':''}</dt>
                <dd class="text-text-primary">{$ABOUT_FIELDS.last_seen.value}</dd>
              </div>
            </div>
            <div class="flex items-start gap-3">
              <span class="text-text-muted mt-0.5">{include file='components/icon.tpl' name='eye' size=16}</span>
              <div class="min-w-0">
                <dt class="text-text-muted text-xs">{$ABOUT_FIELDS.profile_views.title|replace:':':''}</dt>
                <dd class="text-text-primary">{$ABOUT_FIELDS.profile_views.value}</dd>
              </div>
            </div>
            {foreach from=$ABOUT_FIELDS key=key item=field}
              {if is_numeric($key)}
                <div class="flex items-start gap-3">
                  <span class="text-text-muted mt-0.5">{include file='components/icon.tpl' name='tag' size=16}</span>
                  <div class="min-w-0">
                    <dt class="text-text-muted text-xs">{$field.title}</dt>
                    <dd class="text-text-primary">{$field.value}</dd>
                  </div>
                </div>
              {/if}
            {/foreach}
          </dl>
        </div>
      </div>

      {foreach from=$TABS key=key item=tab}
        <div class="ui bottom attached tab" data-tab="{$key}" id="profile-{$key}">
          <div class="nx-surface p-6">{include file=$tab.include}</div>
        </div>
      {/foreach}
    {elseif isset($BLOCKED)}
      <div class="nx-alert nx-alert--danger">
        {include file='components/icon.tpl' name='lock' size=18 class="nx-alert__icon"}
        <div class="nx-alert__body">{$BLOCKED}</div>
      </div>
    {else}
      <div class="nx-alert nx-alert--danger">
        {include file='components/icon.tpl' name='lock' size=18 class="nx-alert__icon"}
        <div class="nx-alert__body">{$PRIVATE_PROFILE}</div>
      </div>
    {/if}
  </main>

  {if $CAN_VIEW && count($WIDGETS_RIGHT)}
    <aside class="space-y-4 order-3">{foreach from=$WIDGETS_RIGHT item=widget}{$widget}{/foreach}</aside>
  {/if}
</div>

{* === Legacy modals (preserved class hooks so Fomantic JS still wires them) === *}
{if count($WALL_POSTS)}
  <div class="ui small modal" id="modal-reactions">
    <i class="close icon"></i>
    <div class="header">{$REACTIONS_TEXT}</div>
    <div class="scrolling content"></div>
  </div>

  {foreach from=$WALL_POSTS item=post}
    {if (isset($CAN_MODERATE) && $CAN_MODERATE eq 1) || $post.self eq 1}
      <div class="ui small modal" id="modal-edit-{$post.id}">
        <div class="header">{$EDIT_POST}</div>
        <div class="content">
          <form action="" method="post" id="form-edit-{$post.id}">
            <textarea name="content" class="nx-textarea">{$post.content}</textarea>
            <input type="hidden" name="token" value="{$TOKEN}">
            <input type="hidden" name="post_id" value="{$post.id}">
            <input type="hidden" name="action" value="edit">
          </form>
        </div>
        <div class="actions">
          <a class="ui negative button">{$CANCEL}</a>
          <a class="ui positive button" onclick="$('#form-edit-{$post.id}').submit();">{$SUBMIT}</a>
        </div>
      </div>
    {/if}
    {if isset($LOGGED_IN_USER)}
      <div class="ui small modal" id="modal-reply-{$post.id}">
        <div class="header">{$REPLY}</div>
        <div class="content">
          <form action="" method="post" id="form-reply-{$post.id}">
            <textarea name="reply" placeholder="{$NEW_REPLY}" class="nx-textarea"></textarea>
            <input type="hidden" name="token" value="{$TOKEN}">
            <input type="hidden" name="post" value="{$post.id}">
            <input type="hidden" name="action" value="reply">
          </form>
        </div>
        <div class="actions">
          <a class="ui negative button">{$CANCEL}</a>
          <a class="ui positive button" onclick="$('#form-reply-{$post.id}').submit();">{$SUBMIT}</a>
        </div>
      </div>
    {/if}
  {/foreach}
{/if}

{if isset($LOGGED_IN_USER)}
  {if !isset($SELF) && $MOD_OR_ADMIN != true}
    <div class="ui small modal" id="modal-block">
      <div class="header">{if isset($BLOCK_USER)}{$BLOCK_USER}{else}{$UNBLOCK_USER}{/if}</div>
      <div class="content">
        {if isset($CONFIRM_BLOCK_USER)}{$CONFIRM_BLOCK_USER}{else}{$CONFIRM_UNBLOCK_USER}{/if}
        <form action="" method="post" id="form-block">
          <input type="hidden" name="token" value="{$TOKEN}">
          <input type="hidden" name="action" value="block">
        </form>
      </div>
      <div class="actions">
        <a class="ui negative button">{$CANCEL}</a>
        <a class="ui positive button" onclick="$('#form-block').submit();">{$SUBMIT}</a>
      </div>
    </div>
  {/if}
  {if isset($SELF)}
    <div class="ui small modal" id="imageModal">
      <div class="header">{$CHANGE_BANNER}</div>
      <div class="content">
        <form action="" name="updateBanner" method="post">
          <select name="banner" class="image-picker show-html">
            {foreach from=$BANNERS item=banner}
              <option data-img-src="{$banner.src}" value="{$banner.name}" {if $banner.active==true} selected{/if}>{$banner.name}</option>
            {/foreach}
          </select>
          <input type="hidden" name="token" value="{$TOKEN}">
          <input type="hidden" name="action" value="banner">
        </form>
        {if isset($PROFILE_BANNER)}
          <div class="nx-divider">Or {$UPLOAD_PROFILE_BANNER}</div>
          <form action="{$UPLOAD_BANNER_URL}" method="post" enctype="multipart/form-data" id="form-banner" class="flex gap-2">
            <input type="file" class="inputFile" name="file" id="uploadBannerInput" hidden />
            <label class="nx-btn nx-btn--outline" for="uploadBannerInput">
              {include file='components/icon.tpl' name='arrow-up' size=14} {$BROWSE}
            </label>
            <input type="hidden" name="token" value="{$TOKEN}">
            <input type="hidden" name="type" value="profile_banner">
            <button type="submit" class="nx-btn nx-btn--primary">{$UPLOAD}</button>
          </form>
        {/if}
      </div>
      <div class="actions">
        <button class="ui negative button">{$CANCEL}</button>
        <button class="ui positive button" onclick="document.updateBanner.submit()">{$SUBMIT}</button>
      </div>
    </div>
  {/if}
{/if}

<script>
  const submitReaction = (post_id, reaction_id) => {
    $.post("{$REACTIONS_URL}", { token: "{$TOKEN}", reactable_id: post_id, context: 'profile_post', reaction_id })
      .done((r) => { if (r.startsWith('Reaction ')) { window.location.replace(window.location.href.replace(/#.*$/, '') + '#post-' + post_id); window.location.reload(); } else console.error(r); })
      .fail(console.error);
  };
  const openReactionModal = (post_id, reaction_id) => {
    const modal = $('#modal-reactions'); modal.modal('show');
    modal.find('.content').html('<div class="text-center py-4"><span class="nx-spinner inline-block"></span></div>');
    $.get("{$REACTIONS_URL}", { reactable_id: post_id, context: 'profile_post', tab: reaction_id })
      .done((r) => modal.find('.content').html(r))
      .fail(console.error);
  };

  // Tabs (Fomantic-like data-tab)
  document.querySelectorAll('.nx-tabs .nx-tab').forEach(tab => {
    tab.addEventListener('click', () => {
      const key = tab.dataset.tab;
      tab.parentElement.querySelectorAll('.nx-tab').forEach(t => t.classList.remove('is-active'));
      tab.classList.add('is-active');
      document.querySelectorAll('[data-tab]').forEach(el => {
        if (el.classList.contains('nx-tab')) return;
        el.classList.toggle('active', el.dataset.tab === key);
      });
    });
  });
</script>

{include file='footer.tpl'}
