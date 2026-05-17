{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="max-w-xl mx-auto">
  <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight mb-6">{$MERGE_TOPICS}</h1>
  <div class="nx-surface p-6 sm:p-8" id="merge-topic">
    <form class="space-y-4" action="" method="post" id="form-merge-topic">
      <div class="nx-field">
        <label class="nx-label" for="InputTopic">{$MERGE_INSTRUCTIONS}</label>
        <select name="merge" id="InputTopic" class="nx-select">
          {foreach from=$TOPICS item=topic}<option value="{$topic->id}">{$topic->topic_title|escape}</option>{/foreach}
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
