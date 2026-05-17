{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="max-w-md mx-auto">
  <header class="mb-6">
    <h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight">{$CONNECT_WITH_AUTHME}</h1>
    {if $AUTHME_SETUP}<p class="text-text-secondary mt-2 text-sm">{$AUTHME_INFO}</p>{/if}
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

  <div class="nx-surface p-6 sm:p-8">
    {if $AUTHME_SETUP}
      <form class="space-y-4" action="" method="post" id="form-authme-email">
        <div class="nx-field">
          <label class="nx-label" for="inputUsername">{$USERNAME}</label>
          <input type="text" id="inputUsername" name="username" placeholder="{$USERNAME}" value="{$USERNAME_INPUT}" tabindex="1" required class="nx-input" />
        </div>
        <div class="nx-field">
          <label class="nx-label" for="inputPassword">{$PASSWORD}</label>
          <input type="password" id="inputPassword" name="password" placeholder="{$PASSWORD}" tabindex="2" required class="nx-input" />
        </div>
        {if $CAPTCHA}<div class="nx-field">{$CAPTCHA}</div>{/if}
        <label class="inline-flex items-start gap-2 cursor-pointer text-sm text-text-secondary">
          <input type="checkbox" name="t_and_c" id="t_and_c" value="1" tabindex="7" required class="nx-checkbox mt-0.5" />
          <span>{$AGREE_TO_TERMS}</span>
        </label>
        <input type="hidden" name="token" value="{$TOKEN}">
        <button type="submit" class="nx-btn nx-btn--primary nx-btn--block" tabindex="5">{$SUBMIT}</button>
      </form>
    {else}
      <div class="nx-alert nx-alert--danger">
        {include file='components/icon.tpl' name='alert' size=18 class="nx-alert__icon"}
        <div class="nx-alert__body">{$AUTHME_NOT_SETUP}</div>
      </div>
    {/if}
  </div>
</div>

{include file='footer.tpl'}
