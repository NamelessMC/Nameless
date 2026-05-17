{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="max-w-xl mx-auto">
  <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight mb-6">{$MOVE_TOPIC}</h1>
  <div class="nx-surface p-6 sm:p-8" id="move-topic">
    <form class="space-y-4" action="" method="post" id="form-move-topic">
      <div class="nx-field">
        <label class="nx-label" for="InputForum">{$MOVE_TO}</label>
        <select name="forum" id="InputForum" class="nx-select">
          <option value="">{$MOVE_TO}</option>
          {foreach from=$FORUMS item=forum}
            {if $forum->category}
              <optgroup label="{$forum->forum_title}"></optgroup>
            {else}
              <option value="{$forum->id}">{$forum->forum_title}</option>
            {/if}
          {/foreach}
        </select>
      </div>
      <input type="hidden" name="token" value="{$TOKEN}">
      <div class="flex flex-wrap gap-2">
        <button type="submit" class="nx-btn nx-btn--primary">{$SUBMIT}</button>
        <a class="nx-btn nx-btn--outline" href="{$CANCEL_LINK}" onclick="return confirm('{$CONFIRM_CANCEL}')">{$CANCEL}</a>
      </div>
    </form>
  </div>
</div>

{include file='footer.tpl'}
