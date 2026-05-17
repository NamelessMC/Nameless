{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
  <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight">{$SEARCH_RESULTS}</h1>
  <a href="{$NEW_SEARCH_URL}" class="nx-btn nx-btn--primary">
    {include file='components/icon.tpl' name='search' size=14}
    {$NEW_SEARCH}
  </a>
</div>

{if isset($PAGINATION)}<div class="mb-4">{$PAGINATION}</div>{/if}

{if empty($RESULTS)}
  <div class="nx-alert nx-alert--danger">
    {include file='components/icon.tpl' name='alert' size=18 class="nx-alert__icon"}
    <div class="nx-alert__body">{$NO_RESULTS}</div>
  </div>
{else}
  <div class="space-y-3">
  {foreach from=$RESULTS item=result}
    <article class="nx-surface overflow-hidden" id="forum-search-result">
      <div class="p-5">
        <h2 class="font-display text-lg font-semibold mb-1">
          <a href="{$result.post_url}" class="hover:text-accent transition">{$result.topic_title}</a>
        </h2>
        <div class="text-xs text-text-muted mb-3">
          <a href="{$result.post_author_profile}" style="{$result.post_author_style}" data-poload="{$USER_INFO_URL}{$result.post_author_id}" class="hover:text-text-primary">{$result.post_author}</a>
          · <span title="{$result.post_date_full}">{$result.post_date_friendly}</span>
        </div>
        <div class="forum_post text-text-secondary line-clamp-4">{$result.content}</div>
      </div>
      <div class="bg-bg-inset border-t border-border-subtle px-5 py-3 flex justify-end">
        <a class="nx-btn nx-btn--primary nx-btn--sm" href="{$result.post_url}">{$READ_FULL_POST}</a>
      </div>
    </article>
  {/foreach}
  </div>
{/if}

{if isset($PAGINATION)}<div class="mt-4">{$PAGINATION}</div>{/if}

{include file='footer.tpl'}
