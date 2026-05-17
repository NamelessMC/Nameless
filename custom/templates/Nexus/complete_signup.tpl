{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="max-w-md mx-auto">
  <header class="mb-6">
    <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight">{$REGISTER}</h1>
  </header>

  {if isset($ERRORS)}
    <div class="nx-alert nx-alert--danger mb-5">
      {include file='components/icon.tpl' name='alert' size=18 class="nx-alert__icon"}
      <div class="flex-1">
        <div class="nx-alert__title">{$ERRORS_TITLE}</div>
        <ul class="nx-alert__body list-disc pl-4 space-y-0.5">
          {foreach from=$ERRORS item=error}<li>{$error}</li>{/foreach}
        </ul>
      </div>
    </div>
  {/if}

  <div class="nx-surface p-6 sm:p-8">
    <form class="space-y-4" action="" method="post" id="form-complete-signup">
      <div class="nx-field">
        <label class="nx-label" for="inputPassword">{$PASSWORD}</label>
        <input type="password" name="password" id="inputPassword" placeholder="{$PASSWORD}" autocomplete="new-password" tabindex="1" class="nx-input" />
      </div>
      <div class="nx-field">
        <label class="nx-label" for="inputPasswordAgain">{$CONFIRM_PASSWORD}</label>
        <input type="password" name="password_again" id="inputPasswordAgain" placeholder="{$CONFIRM_PASSWORD}" autocomplete="new-password" tabindex="2" class="nx-input" />
      </div>
      <label class="inline-flex items-start gap-2 cursor-pointer text-sm text-text-secondary">
        <input type="checkbox" name="t_and_c" id="t_and_c" value="1" tabindex="3" class="nx-checkbox mt-0.5" />
        <span>{$AGREE_TO_TERMS}</span>
      </label>
      <input type="hidden" name="token" value="{$TOKEN}">
      <button type="submit" class="nx-btn nx-btn--primary nx-btn--block" tabindex="4">{$REGISTER}</button>
    </form>
  </div>
</div>

{include file='footer.tpl'}
