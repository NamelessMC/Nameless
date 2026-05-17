<div class="nx-surface overflow-hidden" id="widget-profile-posts">
  <header class="nx-card__header"><h3 class="nx-card__title">{$LATEST_PROFILE_POSTS}</h3></header>
  <div class="p-4">
    {if isset($PROFILE_POSTS_ARRAY)}
      <ul class="space-y-3">
        {foreach from=$PROFILE_POSTS_ARRAY name=profile_posts item=post}
          <li class="flex gap-2.5 pb-3 border-b border-border-subtle last:pb-0 last:border-0">
            <span class="nx-avatar nx-avatar--sm flex-shrink-0"><img src="{$post.avatar}" alt="{$post.username}" loading="lazy" /></span>
            <div class="flex-1 min-w-0">
              <a href="{$post.link}" class="block text-sm text-text-primary hover:text-accent transition line-clamp-2">{$post.content}</a>
              <div class="text-xs text-text-muted mt-1">
                <a href="{$post.user_profile_link}" style="{$post.username_style}" data-poload="{$USER_INFO_URL}{$post.user_id}" class="hover:text-text-primary">{$post.username}</a>
                · <span title="{$post.date_ago}">{$post.ago}</span>
              </div>
            </div>
          </li>
        {/foreach}
      </ul>
    {else}
      <p class="text-sm text-text-muted">{$NO_PROFILE_POSTS}</p>
    {/if}
  </div>
</div>
