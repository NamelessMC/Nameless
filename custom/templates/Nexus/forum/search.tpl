{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="max-w-2xl mx-auto">
  <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight mb-6 flex items-center gap-3">
    <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-accent-subtle text-accent">{include file='components/icon.tpl' name='search' size=18}</span>
    {$FORUM_SEARCH}
  </h1>

  {if isset($ERROR)}
    <div class="nx-alert nx-alert--danger mb-4">{include file='components/icon.tpl' name='alert' size=18 class="nx-alert__icon"}<div><div class="nx-alert__title">{$ERROR_TITLE}</div><div class="nx-alert__body">{$ERROR}</div></div></div>
  {/if}

  <div class="nx-surface p-6 sm:p-8">
    <form action="" method="post" id="form-forum-search">
      <label class="nx-label" for="forum-search-input">{$SEARCH}</label>
      <div class="nx-input-group">
        <span class="nx-input-group__icon">{include file='components/icon.tpl' name='search' size=14}</span>
        <input type="text" id="forum-search-input" name="forum_search" placeholder="{$SEARCH}" class="nx-input" autofocus />
      </div>
      <input type="hidden" name="token" value="{$TOKEN}">
      <button type="submit" class="nx-btn nx-btn--primary nx-btn--block mt-4">{$SEARCH}</button>
    </form>
  </div>
</div>

{include file='footer.tpl'}
