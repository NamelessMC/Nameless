{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="max-w-sm mx-auto">
  <div class="text-center mb-6">
    <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-accent-subtle text-accent mb-4">
      {include file='components/icon.tpl' name='shield' size=26}
    </div>
    <h1 class="font-display text-2xl font-bold tracking-tight">{$TWO_FACTOR_AUTH}</h1>
    <p class="text-text-secondary mt-2 text-sm">{$TFA_ENTER_CODE}</p>
  </div>

  {if isset($ERROR)}
    <div class="nx-alert nx-alert--danger mb-5">
      {include file='components/icon.tpl' name='alert' size=18 class="nx-alert__icon"}
      <div><div class="nx-alert__title">{$ERROR_TITLE}</div><div class="nx-alert__body">{$ERROR}</div></div>
    </div>
  {/if}

  <div class="nx-surface p-6 sm:p-8">
    <form class="space-y-4" action="" method="post" id="form-tfa">
      <input type="text" name="tfa_code" inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code"
             maxlength="6" autofocus
             class="nx-input text-center text-2xl tracking-[0.5em] font-mono" />
      <input type="hidden" name="tfa" value="true">
      <input type="hidden" name="token" value="{$TOKEN}">
      <button type="submit" class="nx-btn nx-btn--primary nx-btn--block">{$SUBMIT}</button>
    </form>
  </div>
</div>

{include file='footer.tpl'}
