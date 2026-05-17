{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="max-w-md mx-auto">
  <header class="mb-6">
    <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight">{$FORGOT_PASSWORD}</h1>
    <p class="text-text-secondary mt-2 text-sm">{$FORGOT_PASSWORD_INSTRUCTIONS}</p>
  </header>

  {if isset($ERROR)}
    <div class="nx-alert nx-alert--danger mb-5">
      {include file='components/icon.tpl' name='alert' size=18 class="nx-alert__icon"}
      <div><div class="nx-alert__title">{$ERROR_TITLE}</div><div class="nx-alert__body">{$ERROR}</div></div>
    </div>
  {else if isset($SUCCESS)}
    <div class="nx-alert nx-alert--success mb-5">
      {include file='components/icon.tpl' name='check' size=18 class="nx-alert__icon"}
      <div><div class="nx-alert__title">{$SUCCESS_TITLE}</div><div class="nx-alert__body">{$SUCCESS}</div></div>
    </div>
  {/if}

  <div class="nx-surface p-6 sm:p-8">
    <form class="space-y-4" action="" method="post" id="form-forgot-password">
      <div class="nx-field">
        <label class="nx-label" for="inputEmail">{$EMAIL_ADDRESS}</label>
        <input type="email" id="inputEmail" name="email" placeholder="{$EMAIL_ADDRESS}" tabindex="1" class="nx-input" autocomplete="email" />
      </div>
      <input type="hidden" name="token" value="{$TOKEN}">
      <button type="submit" class="nx-btn nx-btn--primary nx-btn--block" tabindex="2">{$SUBMIT}</button>
    </form>
  </div>
</div>

{include file='footer.tpl'}
