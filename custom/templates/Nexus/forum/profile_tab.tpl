{*  Nexus · Forum activity tab inside a user profile *}
<h3 class="font-display text-lg font-semibold mb-4">{$PF_LATEST_POSTS_TITLE}</h3>

{if isset($NO_POSTS)}
  <div class="nx-alert nx-alert--info">
    {include file='components/icon.tpl' name='info' size=18 class="nx-alert__icon"}
    <div class="nx-alert__body">{$NO_POSTS}</div>
  </div>
{else}
  <div class="space-y-5">
    {foreach from=$PF_LATEST_POSTS item=post}
      <article class="pb-4 border-b border-border-subtle last:border-0">
        <div class="flex items-start justify-between gap-3 mb-2">
          <h4 class="font-semibold text-text-primary">
            <a href="{$post.link}" class="hover:text-accent transition">{$post.title}</a>
          </h4>
          <span class="text-xs text-text-muted whitespace-nowrap" title="{$post.date_full}">{$post.date_friendly}</span>
        </div>
        <div class="forum_post text-text-secondary text-sm line-clamp-3">{$post.content}</div>
      </article>
    {/foreach}
  </div>
{/if}
