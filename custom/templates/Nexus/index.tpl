{include file='header.tpl'}
{include file='navbar.tpl'}

{if isset($HOME_SESSION_FLASH)}
  <div class="nx-alert nx-alert--success mb-5 animate-fade-in">
    {include file='components/icon.tpl' name='check' size=18 class="nx-alert__icon"}
    <div><div class="nx-alert__title">{$SUCCESS_TITLE}</div><div class="nx-alert__body">{$HOME_SESSION_FLASH}</div></div>
  </div>
{/if}

{if isset($HOME_SESSION_ERROR_FLASH)}
  <div class="nx-alert nx-alert--danger mb-5 animate-fade-in">
    {include file='components/icon.tpl' name='alert' size=18 class="nx-alert__icon"}
    <div><div class="nx-alert__title">{$ERROR_TITLE}</div><div class="nx-alert__body">{$HOME_SESSION_ERROR_FLASH}</div></div>
  </div>
{/if}

<div class="grid gap-6 lg:gap-8
            {if count($WIDGETS_LEFT) && count($WIDGETS_RIGHT)}lg:grid-cols-[18rem_minmax(0,1fr)_18rem]
            {elseif count($WIDGETS_LEFT) || count($WIDGETS_RIGHT)}lg:grid-cols-[18rem_minmax(0,1fr)]
            {else}grid-cols-1{/if}">

  {if count($WIDGETS_LEFT)}
    <aside class="space-y-4 order-2 lg:order-1">
      {foreach from=$WIDGETS_LEFT item=widget}{$widget}{/foreach}
    </aside>
  {/if}

  <section class="order-1 lg:order-2 min-w-0">
    {if $HOME_TYPE === 'news'}
      <div class="space-y-4">
        {foreach from=$NEWS item=item}
          <article class="nx-card nx-card--interactive animate-fade-in">
            <div class="nx-card__body">
              <div class="flex items-start gap-3 mb-3">
                <a href="{$item.author_url}" class="nx-avatar nx-avatar--sm">
                  <img src="{$item.author_avatar}" alt="{$item.author_name}" loading="lazy" />
                </a>
                <div class="flex-1 min-w-0">
                  <div class="flex items-center flex-wrap gap-2 text-xs text-text-muted">
                    <a href="{$item.author_url}" style="{$item.author_style}" class="text-text-secondary hover:text-text-primary font-medium">{$item.author_name}</a>
                    <span>·</span>
                    <time class="text-text-muted" title="{$item.date}">{$item.time_ago}</time>
                    {if isset($item.label)}<span class="ml-1">{$item.label}</span>{/if}
                  </div>
                  <h2 class="nx-card__title mt-1">
                    <a href="{$item.url}" class="hover:text-accent transition">{$item.title}</a>
                  </h2>
                </div>
              </div>
              <div class="forum_post text-text-secondary line-clamp-5">
                {$item.content}
              </div>
            </div>
            <div class="nx-card__footer">
              <span class="nx-card__meta">{$item.date}</span>
              <a class="nx-btn nx-btn--primary nx-btn--sm" href="{$item.url}">
                {$READ_FULL_POST}
                {include file='components/icon.tpl' name='arrow-right' size=12}
              </a>
            </div>
          </article>
        {foreachelse}
          <div class="nx-alert nx-alert--info">
            {include file='components/icon.tpl' name='info' size=18 class="nx-alert__icon"}
            <div class="nx-alert__body">{$NO_NEWS}</div>
          </div>
        {/foreach}
      </div>
    {elseif $HOME_TYPE === 'custom'}
      <div class="nx-surface p-6 sm:p-8 forum_post">
        {$CUSTOM_HOME_CONTENT}
      </div>
    {/if}
  </section>

  {if count($WIDGETS_RIGHT)}
    <aside class="space-y-4 order-3">
      {foreach from=$WIDGETS_RIGHT item=widget}{$widget}{/foreach}
    </aside>
  {/if}
</div>

{include file='footer.tpl'}
