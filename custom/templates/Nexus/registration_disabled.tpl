{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="max-w-md mx-auto text-center">
  <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-warning/15 text-warning mb-4">
    {include file='components/icon.tpl' name='lock' size=26}
  </div>
  <h1 class="font-display text-2xl font-bold tracking-tight mb-2">{$CREATE_AN_ACCOUNT}</h1>
  <p class="text-text-secondary text-sm">{$REGISTRATION_DISABLED}</p>
</div>

{include file='footer.tpl'}
