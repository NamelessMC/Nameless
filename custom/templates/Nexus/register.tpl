{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="max-w-2xl mx-auto">
  <header class="mb-6">
    <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight">{$CREATE_AN_ACCOUNT}</h1>
    {if $OAUTH_FLOW}<p class="text-text-secondary mt-2">{$OAUTH_MESSAGE_CONTINUE}</p>{/if}
  </header>

  {if isset($REGISTRATION_ERROR)}
    <div class="nx-alert nx-alert--danger mb-5">
      {include file='components/icon.tpl' name='alert' size=18 class="nx-alert__icon"}
      <div class="flex-1">
        <div class="nx-alert__title">{$ERROR_TITLE}</div>
        <ul class="nx-alert__body list-disc pl-4 space-y-0.5">
          {foreach from=$REGISTRATION_ERROR item=error}<li>{$error}</li>{/foreach}
        </ul>
      </div>
    </div>
  {/if}

  <div class="nx-surface p-6 sm:p-8">
    <form class="space-y-4" action="" method="post" id="form-register">

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
            <input type="password" name="{$field_key}" id="{$field_key}" value="{$field.value}" placeholder="{$field.placeholder}" tabindex="{$counter++}" {if $field.required}required{/if} class="nx-input" autocomplete="new-password" />
          {else if $field.type eq 5}
            <select class="nx-select" name="{$field_key}" id="{$field_key}" {if $field.required}required{/if}>
              {foreach from=$field.options item=option}
                <option value="{$option.value}" {if $option.value eq $field.value} selected{/if}>{$option.option}</option>
              {/foreach}
            </select>
          {else if $field.type eq 6}
            <input type="number" name="{$field_key}" id="{$field_key}" value="{$field.value}" placeholder="{$field.name}" tabindex="{$counter++}" {if $field.required}required{/if} class="nx-input" />
          {else if $field.type eq 7}
            <input type="email" name="{$field_key}" id="{$field_key}" value="{$field.value}" placeholder="{$field.placeholder}" tabindex="{$counter++}" {if $field.required}required{/if} class="nx-input" autocomplete="email" />
          {else if $field.type eq 8}
            <fieldset class="space-y-2">
              <legend class="nx-label mb-1">{$field.name}{if $field.required}<span class="nx-required">*</span>{/if}</legend>
              {foreach from=$field.options item=option}
                <label class="inline-flex items-center gap-2 text-sm text-text-primary cursor-pointer mr-4">
                  <input type="radio" name="{$field_key}" value="{$option.value}" {if $field.value eq $option.value}checked{/if} {if $field.required}required{/if} tabindex="{$counter++}" class="nx-radio" />
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

      {if $CAPTCHA}<div class="nx-field">{$CAPTCHA}</div>{/if}

      <label class="inline-flex items-start gap-2 cursor-pointer text-sm text-text-secondary">
        <input type="checkbox" name="t_and_c" id="t_and_c" value="1" tabindex="7" class="nx-checkbox mt-0.5" />
        <span>{$AGREE_TO_TERMS}</span>
      </label>

      <input type="hidden" name="token" value="{$TOKEN}">
      <input id="timezone" type="hidden" name="timezone" value="">

      <div class="flex flex-wrap gap-2 pt-2">
        <button type="submit" class="nx-btn nx-btn--primary nx-btn--lg flex-1 sm:flex-initial" tabindex="8">{$REGISTER}</button>
        {if $OAUTH_FLOW}
          <a class="nx-btn nx-btn--outline nx-btn--lg" href="{$OAUTH_CANCEL_REGISTER_URL}">{$CANCEL}</a>
        {/if}
      </div>
    </form>

    {if $OAUTH_AVAILABLE and !$OAUTH_FLOW}
      <div class="nx-divider">{$OR}</div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
        {foreach $OAUTH_PROVIDERS as $name => $meta}
          <a href="{$meta.url}" class="nx-btn nx-btn--outline gap-2" {if $meta.button_css}style="{$meta.button_css}"{/if}>
            {if $meta.logo_url}<img src="{$meta.logo_url}" {if $meta.logo_css}style="{$meta.logo_css}"{/if} alt="{$name|ucfirst}" class="w-4 h-4" />
            {elseif $meta.icon}<i class="{$meta.icon}"></i>{/if}
            <span {if $meta.text_css}style="{$meta.text_css}"{/if}>{$meta.continue_with}</span>
          </a>
        {/foreach}
      </div>
    {/if}

    {if !$OAUTH_FLOW}
      <div class="nx-divider">{$ALREADY_REGISTERED}</div>
      <a href="{$LOGIN_URL}" class="nx-btn nx-btn--outline nx-btn--block">{$LOG_IN}</a>
    {/if}
  </div>
</div>

{if $OAUTH_FLOW && $OAUTH_EMAIL_VERIFIED}
<script>
  document.getElementById('email')?.addEventListener('keyup', (e) => checkEmailValidity(e.target.value));
  const checkEmailValidity = (email) => {
    const old = document.getElementById('email-caption'); if (old) old.remove();
    const note = document.createElement('div');
    note.id = 'email-caption';
    note.className = 'nx-help-text mt-1';
    if ('{$OAUTH_EMAIL_VERIFIED}' && email !== '{$OAUTH_EMAIL_ORIGINAL}') {
      note.textContent = '{$OAUTH_EMAIL_NOT_VERIFIED_MESSAGE}';
      note.style.color = 'var(--warning)';
    } else {
      note.textContent = '{$OAUTH_EMAIL_VERIFIED_MESSAGE}';
      note.style.color = 'var(--success)';
    }
    document.getElementById('email')?.parentElement.appendChild(note);
  };
  window.addEventListener('load', () => checkEmailValidity('{$EMAIL_INPUT}'));
</script>
{/if}

{include file='footer.tpl'}
