<div class="nx-surface overflow-hidden" id="widget-latest-posts">
  <header class="nx-card__header"><h3 class="nx-card__title">{$LATEST_POSTS}</h3></header>
  <div class="p-4">
    <ul class="space-y-3">
      {foreach from=$LATEST_POSTS_ARRAY name=latest_posts item=post}
        <li class="flex gap-2.5 pb-3 border-b border-border-subtle last:pb-0 last:border-0">
          <span class="nx-avatar nx-avatar--sm flex-shrink-0"><img src="{$post.last_reply_avatar}" alt="{$post.last_reply_username}" loading="lazy" /></span>
          <div class="flex-1 min-w-0">
            <a href="{$post.last_reply_link}" class="block text-sm text-text-primary hover:text-accent transition font-medium truncate">{$post.topic_title}</a>
            <div class="text-xs text-text-muted mt-0.5 truncate">
              <a href="{$post.last_reply_profile_link}" style="{$post.last_reply_style}" data-poload="{$USER_INFO_URL}{$post.last_reply_user_id}" class="hover:text-text-primary">{$post.last_reply_username}</a>
              · <span title="{$post.last_reply}">{$post.last_reply_rough}</span>
            </div>
          </div>
        </li>
      {foreachelse}
        <li class="text-sm text-text-muted">{$NO_POSTS_FOUND}</li>
      {/foreach}
    </ul>
  </div>
</div>
