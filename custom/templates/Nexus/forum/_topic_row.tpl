{*  Nexus · Topic-row partial shared by view_forum, sticky lists, etc. *}
<li class="flex items-center gap-4 px-5 py-4 hover:bg-bg-elevated transition">
  <span class="hidden sm:inline-flex w-9 h-9 rounded-lg bg-bg-elevated text-text-muted items-center justify-center flex-shrink-0">
    {include file='components/icon.tpl' name='forum' size=14}
  </span>
  <div class="flex-1 min-w-0">
    <div class="flex flex-wrap items-center gap-2 mb-1">
      {if isset($discussion.labels) && count($discussion.labels)}
        {foreach from=$discussion.labels item=label}{$label}{/foreach}
      {/if}
      <a href="{$discussion.link}" class="font-semibold text-text-primary hover:text-accent transition truncate">{$discussion.topic_title}</a>
    </div>
    <div class="text-xs text-text-muted">
      <a href="{$discussion.author_link}" data-poload="{$USER_INFO_URL}{$discussion.topic_created_user_id}" style="{$discussion.topic_created_style}" class="hover:text-text-primary">{$discussion.topic_created_username}</a>
      · <span title="{$discussion.topic_created}">{$discussion.topic_created_rough}</span>
    </div>
  </div>
  <div class="hidden md:flex flex-col items-end text-xs text-text-muted min-w-[6rem]">
    <span>{$VIEWS|capitalize}: <span class="text-text-primary font-medium">{$discussion.views}</span></span>
    <span>{$POSTS|capitalize}: <span class="text-text-primary font-medium">{$discussion.posts}</span></span>
  </div>
  <div class="hidden md:flex items-center gap-3 min-w-[12rem] flex-shrink-0">
    <span class="nx-avatar nx-avatar--sm"><img src="{$discussion.last_reply_avatar}" loading="lazy" alt="" /></span>
    <div class="min-w-0 flex-1">
      <a href="{$discussion.last_reply_link}" data-poload="{$USER_INFO_URL}{$discussion.last_reply_user_id}" style="{$discussion.last_reply_style}" class="block text-sm text-text-primary hover:text-accent truncate">{$discussion.last_reply_username}</a>
      <div class="text-xs text-text-muted" title="{$discussion.last_reply}">{$discussion.last_reply_rough}</div>
    </div>
  </div>
</li>
