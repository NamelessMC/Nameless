{include file='header.tpl'}
{include file='navbar.tpl'}

<h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight mb-6">{$EDITING_POST}</h1>

{if isset($ERRORS)}
  <div class="nx-alert nx-alert--danger mb-4">
    {include file='components/icon.tpl' name='alert' size=18 class="nx-alert__icon"}
    <div class="flex-1">
      <div class="nx-alert__title">{$ERROR_TITLE}</div>
      <ul class="nx-alert__body list-disc pl-4">{foreach from=$ERRORS item=error}<li>{$error}</li>{/foreach}</ul>
    </div>
  </div>
{/if}

<div class="nx-surface p-6 sm:p-8" id="post-edit">
  <form class="space-y-4" action="" method="post" id="form-post-edit">
    {if isset($EDITING_TOPIC)}
      <div class="nx-field">
        <label class="nx-label" for="title">{$TOPIC_TITLE}</label>
        <input type="text" id="title" name="title" value="{$TOPIC_TITLE_VALUE}" class="nx-input" />
      </div>
      {if count($LABELS)}
        <div class="nx-field">
          <span class="nx-label">Labels</span>
          <div class="flex flex-wrap gap-2 labels">
            {foreach from=$LABELS item=label}
              <label class="cursor-pointer">
                <input type="checkbox" name="topic_label[]" id="{$label.id}" value="{$label.id}" {if $label.active}checked{/if} hidden class="peer">
                <span class="nx-badge peer-checked:bg-accent peer-checked:text-accent-fg peer-checked:border-accent transition">{$label.html}</span>
              </label>
            {/foreach}
          </div>
        </div>
      {/if}
    {/if}
    <div class="nx-field">
      <label class="nx-label" for="editor">{$CONTENT_LABEL}</label>
      <textarea name="content" id="editor" class="nx-textarea min-h-[300px]"></textarea>
    </div>
    <input type="hidden" name="token" value="{$TOKEN}">
    <div class="flex flex-wrap gap-2">
      <button type="submit" class="nx-btn nx-btn--primary">{$SUBMIT}</button>
      <a class="nx-btn nx-btn--outline" href="{$CANCEL_LINK}" onclick="return confirm('{$CONFIRM_CANCEL}')">{$CANCEL}</a>
    </div>
  </form>
</div>

{include file='footer.tpl'}
