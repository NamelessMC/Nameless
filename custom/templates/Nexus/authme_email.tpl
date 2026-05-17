{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="max-w-2xl mx-auto">
  <header class="mb-6">
    <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight">{$CONNECT_WITH_AUTHME}</h1>
  </header>

  {if isset($ERRORS)}
    <div class="nx-alert nx-alert--danger mb-5">
      {include file='components/icon.tpl' name='alert' size=18 class="nx-alert__icon"}
      <div class="flex-1">
        <div class="nx-alert__title">{$ERROR}</div>
        <ul class="nx-alert__body list-disc pl-4 space-y-0.5">
          {foreach from=$ERRORS item=error}<li>{$error}</li>{/foreach}
        </ul>
      </div>
    </div>
  {/if}

  <div class="nx-alert nx-alert--success mb-5">
    {include file='components/icon.tpl' name='check' size=18 class="nx-alert__icon"}
    <div><div class="nx-alert__title">{$AUTHME_SUCCESS}</div><div class="nx-alert__body">{$AUTHME_INFO}</div></div>
  </div>

  <div class="nx-surface p-6 sm:p-8">
    <form class="space-y-4" action="" method="post" id="form-authme-email">
      {assign var=counter value=1}
      {foreach $FIELDS as $field_key => $field}
        <div class="nx-field">
          {if $field.type neq 8 && $field.type neq 9}
            <label class="nx-label" for="{$field_key}">{$field.name}{if $field.required}<span class="nx-required">*</span>{/if}</label>
          {/if}
          {if $field.type eq 1}
            <input type="text" name="{$field_key}" id="{$field_key}" value="{$field.value}" placeholder="{$field.placeholder}" tabindex="{$counter++}" {if $field.required}required{/if} class="nx-input" />
          {else if $field.type eq 2}
            <textarea name="{$field_key}" id="{$field_key}" placeholder="{$field.placeholder}" tabindex="{$counter++}" class="nx-textarea"></textarea>
          {else if $field.type eq 3}
            <input type="date" name="{$field_key}" id="{$field_key}" value="{$field.value}" tabindex="{$counter++}" class="nx-input" />
          {else if $field.type eq 4}
            <input type="password" name="{$field_key}" id="{$field_key}" value="{$field.value}" placeholder="{$field.placeholder}" tabindex="{$counter++}" {if $field.required}required{/if} class="nx-input" />
          {else if $field.type eq 5}
            <select class="nx-select" name="{$field_key}" id="{$field_key}" {if $field.required}required{/if}>
              {foreach from=$field.options item=option}
                <option value="{$option.value}" {if $option.value eq $field.value} selected{/if}>{$option.option}</option>
              {/foreach}
            </select>
          {else if $field.type eq 6}
            <input type="number" name="{$field_key}" id="{$field_key}" value="{$field.value}" placeholder="{$field.name}" tabindex="{$counter++}" {if $field.required}required{/if} class="nx-input" />
          {else if $field.type eq 7}
            <input type="email" name="{$field_key}" id="{$field_key}" value="{$field.value}" placeholder="{$field.placeholder}" tabindex="{$counter++}" {if $field.required}required{/if} class="nx-input" />
          {else if $field.type eq 8}
            <fieldset class="space-y-2">
              <legend class="nx-label mb-1">{$field.name}{if $field.required}<span class="nx-required">*</span>{/if}</legend>
              {foreach from=$field.options item=option}
                <label class="inline-flex items-center gap-2 text-sm text-text-primary cursor-pointer mr-4">
                  <input type="radio" name="{$field_key}" value="{$option.value}" {if $field.value eq $option.value}checked{/if} {if $field.required}required{/if} class="nx-radio" />
                  {$option.option}
                </label>
              {/foreach}
            </fieldset>
          {else if $field.type eq 9}
            <fieldset class="space-y-2">
              <legend class="nx-label mb-1">{$field.name}{if $field.required}<span class="nx-required">*</span>{/if}</legend>
              {foreach from=$field.options item=option}
                <label class="inline-flex items-center gap-2 text-sm text-text-primary cursor-pointer mr-4">
                  <input type="checkbox" name="{$field_key}[]" value="{$option.value}"
                         {if is_array($field.value) && in_array($option.value, $field.value)}checked{/if}
                         tabindex="{$counter++}" class="nx-checkbox" />
                  {$option.option}
                </label>
              {/foreach}
            </fieldset>
          {/if}
        </div>
      {/foreach}

      <label class="inline-flex items-start gap-2 cursor-pointer text-sm text-text-secondary">
        <input type="checkbox" name="authme_sync_password" id="authme_sync_password" tabindex="{$counter++}"
               {if $AUTHME_SYNC_PASSWORD_CHECKED}checked{/if} class="nx-checkbox mt-0.5" />
        <span>
          {$AUTHME_SYNC_PASSWORD}
          <span class="ml-1 text-text-muted" title="{$AUTHME_SYNC_PASSWORD_INFO}">ⓘ</span>
        </span>
      </label>

      <input type="hidden" name="token" value="{$TOKEN}">
      <button type="submit" class="nx-btn nx-btn--primary nx-btn--block">{$SUBMIT}</button>
    </form>
  </div>
</div>

{include file='footer.tpl'}
