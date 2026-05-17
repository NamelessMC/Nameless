{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="grid lg:grid-cols-2 gap-10 items-center min-h-[60vh]">
  {* === Visual side === *}
  <aside class="hidden lg:block relative">
    <div class="nx-surface-elevated overflow-hidden p-10 h-full min-h-[420px] relative">
      <div class="absolute inset-0 bg-gradient-mesh opacity-80"></div>
      <div class="relative z-10 h-full flex flex-col justify-between">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-br from-accent to-accent-hover shadow-glow text-white">
          {include file='components/icon.tpl' name='sparkle' size=22}
        </div>
        <div>
          <h1 class="font-display text-3xl font-bold tracking-tight text-text-primary mb-3">
            {$SIGN_IN}
          </h1>
          <p class="text-text-secondary max-w-md leading-relaxed">
            {$smarty.const.SITE_NAME} &mdash; pick up where you left off.
          </p>
        </div>
      </div>
    </div>
  </aside>

  {* === Form side === *}
  <section class="max-w-md mx-auto w-full">
    <div class="lg:hidden mb-6">
      <h1 class="font-display text-2xl font-bold tracking-tight">{$SIGN_IN}</h1>
    </div>

    {if count($ERROR)}
      <div class="nx-alert nx-alert--danger mb-5">
        {include file='components/icon.tpl' name='alert' size=18 class="nx-alert__icon"}
        <div class="flex-1">
          <div class="nx-alert__title">{$ERROR_TITLE}</div>
          <ul class="nx-alert__body list-disc pl-4 space-y-0.5">
            {foreach from=$ERROR item=error}<li>{$error}</li>{/foreach}
          </ul>
        </div>
      </div>
    {/if}

    <form class="space-y-4" action="" method="post" id="form-login">
      {if isset($EMAIL)}
        <div class="nx-field">
          <label class="nx-label" for="email">{$EMAIL}</label>
          <input type="email" name="email" id="email" value="{$USERNAME_INPUT}" placeholder="{$EMAIL}"
                 tabindex="1" class="nx-input" autocomplete="email" />
        </div>
      {else}
        <div class="nx-field">
          <label class="nx-label" for="username">{$USERNAME}</label>
          <input type="text" name="username" id="username" value="{$USERNAME_INPUT}" placeholder="{$USERNAME}"
                 tabindex="1" class="nx-input" autocomplete="username" />
        </div>
      {/if}

      <div class="nx-field">
        <label class="nx-label" for="password">{$PASSWORD}</label>
        <input type="password" name="password" id="password" placeholder="{$PASSWORD}"
               tabindex="2" class="nx-input" autocomplete="current-password" />
      </div>

      <div class="flex items-center justify-between">
        <label class="inline-flex items-center gap-2 cursor-pointer text-sm text-text-secondary">
          <input type="checkbox" name="remember" id="remember" value="1" tabindex="3" class="nx-checkbox" />
          {$REMEMBER_ME}
        </label>
        <a href="{$FORGOT_PASSWORD_URL}" class="text-sm text-accent hover:text-accent-hover transition">{$FORGOT_PASSWORD}</a>
      </div>

      {if $CAPTCHA}
        <div class="nx-field">{$CAPTCHA}</div>
      {/if}

      <input type="hidden" name="token" value="{$FORM_TOKEN}">

      <button type="submit" class="nx-btn nx-btn--primary nx-btn--lg nx-btn--block" tabindex="5">
        {$SIGN_IN}
        {include file='components/icon.tpl' name='arrow-right' size=14}
      </button>
    </form>

    {if $OAUTH_AVAILABLE}
      <div class="nx-divider">{$OR}</div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
        {foreach $OAUTH_PROVIDERS as $name => $meta}
          <a href="{$meta.url}" class="nx-btn nx-btn--outline gap-2" {if $meta.button_css}style="{$meta.button_css}"{/if}>
            {if $meta.logo_url}
              <img src="{$meta.logo_url}" {if $meta.logo_css}style="{$meta.logo_css}"{/if} alt="{$name|ucfirst}" class="w-4 h-4" />
            {elseif $meta.icon}
              <i class="{$meta.icon}"></i>
            {/if}
            <span {if $meta.text_css}style="{$meta.text_css}"{/if}>{$meta.log_in_with}</span>
          </a>
        {/foreach}
      </div>
    {/if}

    <div class="nx-divider">{$NOT_REGISTERED_YET}</div>
    <a href="{$REGISTER_URL}" class="nx-btn nx-btn--outline nx-btn--block">{$REGISTER}</a>
  </section>
</div>

{include file='footer.tpl'}
