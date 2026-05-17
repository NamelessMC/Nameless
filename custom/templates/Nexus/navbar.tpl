{*
 *  Nexus · Top navigation + announcement region + main content opener
 *  Preserves the $NAV_LINKS / $USER_SECTION / $WIDGETS_TOP / $ANNOUNCEMENTS contract.
*}

{* === Off-Canvas Mobile Drawer === *}
<div x-data
     x-show="$store.mobileNav.open"
     x-cloak
     class="fixed inset-0 z-[110] lg:hidden"
     @keydown.window.escape="$store.mobileNav.close()">

  <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"
       x-transition.opacity
       @click="$store.mobileNav.close()"></div>

  <aside class="absolute left-0 top-0 h-full w-[82%] max-w-sm nx-surface-elevated rounded-none border-r border-border-default
                flex flex-col gap-1 p-4 overflow-y-auto"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full">

    <div class="flex items-center justify-between mb-4">
      <a href="/" class="font-display text-lg font-semibold tracking-tight">{$smarty.const.SITE_NAME}</a>
      <button class="nx-btn nx-btn--ghost nx-btn--icon nx-btn--sm" @click="$store.mobileNav.close()" aria-label="Close menu">
        {include file='components/icon.tpl' name='close' size=18}
      </button>
    </div>

    <nav class="flex flex-col gap-0.5" aria-label="Mobile primary">
      {foreach from=$NAV_LINKS key=name item=item}
        {if isset($item.items)}
          <div class="px-3 pt-3 pb-1 text-[11px] uppercase tracking-wider text-text-muted font-semibold">{$item.title}</div>
          {foreach from=$item.items item=dropdown}
            {if !isset($dropdown.separator)}
              <a href="{$dropdown.link}" target="{$dropdown.target}"
                 class="flex items-center gap-2.5 px-3 py-2.5 rounded-md text-sm text-text-secondary hover:text-text-primary hover:bg-bg-surface transition">
                <span class="opacity-80">{$dropdown.icon}</span>{$dropdown.title}
              </a>
            {/if}
          {/foreach}
        {else}
          <a href="{$item.link}" target="{$item.target}" data-nav-key="{$name}"
             class="flex items-center gap-2.5 px-3 py-2.5 rounded-md text-sm hover:bg-bg-surface transition
                    {if isset($item.active)}bg-bg-surface text-text-primary{else}text-text-secondary hover:text-text-primary{/if}">
            <span class="opacity-80">{$item.icon}</span>{$item.title}
          </a>
        {/if}
      {/foreach}
    </nav>

    <div class="mt-auto pt-4 border-t border-border-subtle flex flex-wrap gap-2">
      {foreach from=$USER_SECTION key=name item=item}
        {if !isset($item.items)}
          <a href="{$item.link}" target="{$item.target}"
             class="nx-btn {if $name eq 'register' || $name eq 'panel'}nx-btn--primary{else}nx-btn--outline{/if} nx-btn--sm">
            <span class="opacity-80">{$item.icon}</span>{$item.title}
          </a>
        {/if}
      {/foreach}
    </div>
  </aside>
</div>

{* === Top Bar === *}
<header class="sticky top-0 z-50 backdrop-blur-xl bg-bg-overlay border-b border-border-subtle" x-data>
  <div class="nx-container flex items-center gap-3 h-16">

    {* Mobile burger *}
    <button class="nx-btn nx-btn--ghost nx-btn--icon nx-btn--sm lg:hidden"
            @click="$store.mobileNav.toggle()" aria-label="Open menu">
      {include file='components/icon.tpl' name='menu' size=18}
    </button>

    {* Brand *}
    <a href="/" class="flex items-center gap-2 font-display font-semibold text-text-primary text-[15px] tracking-tight">
      <span class="inline-flex items-center justify-center w-7 h-7 rounded-md bg-gradient-to-br from-accent to-accent-hover shadow-glow text-white">
        {include file='components/icon.tpl' name='sparkle' size=14}
      </span>
      <span class="hidden sm:inline">{$smarty.const.SITE_NAME}</span>
    </a>

    {* Primary nav (desktop) *}
    <nav class="hidden lg:flex items-center gap-1 ml-4" aria-label="Primary">
      {foreach from=$NAV_LINKS key=name item=item}
        {if isset($item.items)}
          <div x-data="{ open: false }" class="relative">
            <button class="nx-btn nx-btn--ghost nx-btn--sm" @click="open = !open" @click.away="open = false"
                    :aria-expanded="open" aria-haspopup="menu">
              <span class="opacity-80">{$item.icon}</span>{$item.title}
              {include file='components/icon.tpl' name='chevron-down' size=14}
            </button>
            <div x-show="open" x-cloak x-transition
                 class="nx-dropdown__panel left-0">
              <div class="nx-dropdown__header">{$item.title}</div>
              {foreach from=$item.items item=dropdown}
                {if isset($dropdown.separator)}
                  <div class="nx-dropdown__divider"></div>
                {else}
                  <a class="nx-dropdown__item" href="{$dropdown.link}" target="{$dropdown.target}">
                    <span class="opacity-80">{$dropdown.icon}</span>{$dropdown.title}
                  </a>
                {/if}
              {/foreach}
            </div>
          </div>
        {else}
          <a href="{$item.link}" target="{$item.target}" data-nav-key="{$name}"
             class="nx-btn nx-btn--ghost nx-btn--sm{if isset($item.active)} bg-bg-elevated text-text-primary{/if}">
            <span class="opacity-80">{$item.icon}</span>{$item.title}
          </a>
        {/if}
      {/foreach}
    </nav>

    <div class="flex-1"></div>

    {* Cmd+K trigger *}
    <button class="nx-btn nx-btn--outline nx-btn--sm hidden md:inline-flex gap-2"
            @click="$store.palette.open()" aria-label="Open command palette">
      {include file='components/icon.tpl' name='search' size=14}
      <span class="text-text-muted">Search…</span>
      <span class="nx-kbd ml-2">⌘K</span>
    </button>
    <button class="nx-btn nx-btn--ghost nx-btn--icon nx-btn--sm md:hidden"
            @click="$store.palette.open()" aria-label="Search">
      {include file='components/icon.tpl' name='search' size=18}
    </button>

    {* Theme toggle *}
    <button class="nx-btn nx-btn--ghost nx-btn--icon nx-btn--sm"
            @click="$store.theme.toggle()"
            :aria-label="$store.theme.current === 'dark' ? 'Switch to light mode' : 'Switch to dark mode'">
      <span x-show="$store.theme.current === 'dark'">{include file='components/icon.tpl' name='moon' size=18}</span>
      <span x-show="$store.theme.current === 'light'" x-cloak>{include file='components/icon.tpl' name='sun' size=18}</span>
    </button>

    {* Right user section *}
    <div class="hidden md:flex items-center gap-2">
      {foreach from=$USER_SECTION key=name item=item}
        {if isset($item.items)}
          <div x-data="{ open: false }" class="relative">
            <button class="nx-btn {if $name eq 'account'}nx-btn--ghost{else}nx-btn--ghost nx-btn--icon{/if} nx-btn--sm"
                    @click="open = !open" @click.away="open = false"
                    :aria-expanded="open" aria-haspopup="menu"
                    id="button-{$name}">
              <span class="opacity-80">{$item.icon}</span>
              {if $name eq 'account'}<span class="hidden lg:inline">{$item.title}</span>{/if}
            </button>
            <div x-show="open" x-cloak x-transition
                 class="nx-dropdown__panel right-0">
              <div class="nx-dropdown__header">{$item.title}</div>
              <div id="list-{$name}">
                {foreach from=$item.items item=dropdown}
                  {if isset($dropdown.separator)}
                    <div class="nx-dropdown__divider"></div>
                  {else}
                    {if isset($dropdown.action)}
                      <a class="nx-dropdown__item" href="#"
                         data-link="{$dropdown.link}" data-action="{$dropdown.action}">
                        <span class="opacity-80">{$dropdown.icon}</span>{$dropdown.title}
                      </a>
                    {else}
                      <a class="nx-dropdown__item" href="{$dropdown.link}" target="{$dropdown.target}">
                        <span class="opacity-80">{$dropdown.icon}</span>{$dropdown.title}
                      </a>
                    {/if}
                  {/if}
                {/foreach}
              </div>
              {if !empty($item.meta)}
                <div class="nx-dropdown__divider"></div>
                <a class="nx-dropdown__item text-text-muted" href="{$item.link}">{$item.meta}</a>
              {/if}
            </div>
          </div>
        {else}
          {if $name eq 'panel'}
            <a href="{$item.link}" target="{$item.target}" class="nx-btn nx-btn--primary nx-btn--icon nx-btn--sm" aria-label="{$item.title}">
              <span>{$item.icon}</span>
            </a>
          {elseif $name eq 'register'}
            <a href="{$item.link}" target="{$item.target}" class="nx-btn nx-btn--primary nx-btn--sm">{$item.title}</a>
          {else}
            <a href="{$item.link}" target="{$item.target}" class="nx-btn nx-btn--outline nx-btn--sm">{$item.title}</a>
          {/if}
        {/if}
      {/foreach}
    </div>
  </div>
</header>

{* === Optional Hero / Server-Status (Minecraft) === *}
{if isset($BANNER_IMAGE) || (isset($MINECRAFT) && isset($SERVER_QUERY))}
<section class="relative overflow-hidden border-b border-border-subtle"
         {if isset($BANNER_IMAGE)}style="background:linear-gradient(180deg, transparent, var(--bg-base)), url('{$BANNER_IMAGE}') center/cover"{/if}>
  <div class="nx-container py-12 sm:py-16">
    <div class="grid lg:grid-cols-2 gap-8 items-end">
      <div>
        <h1 class="font-display text-3xl sm:text-4xl font-bold tracking-tight text-text-primary">{$smarty.const.SITE_NAME}</h1>
      </div>
      {if isset($MINECRAFT) && isset($SERVER_QUERY)}
        <div class="flex justify-start lg:justify-end">
          <div class="nx-surface-elevated px-5 py-4 inline-flex items-center gap-4">
            {include file='components/icon.tpl' name='server' size=20 class="text-accent"}
            <div>
              {if isset($SERVER_QUERY.status_value) && ($SERVER_QUERY.status_value == 1)}
                <div class="text-text-primary text-sm font-medium">
                  {if isset($SERVER_QUERY.status_full)}{$SERVER_QUERY.status_full}{else}{$SERVER_QUERY.x_players_online}{/if}
                </div>
              {else}
                <div class="text-danger text-sm font-medium">{$SERVER_OFFLINE}</div>
              {/if}
              {if isset($CLICK_TO_COPY_TOOLTIP)}
                <button class="text-text-muted text-xs hover:text-text-primary transition"
                        onclick="copy('#ip')" data-tooltip="{$CLICK_TO_COPY_TOOLTIP}">{$CONNECT_WITH}</button>
              {/if}
            </div>
          </div>
        </div>
      {/if}
    </div>
  </div>
</section>
{/if}

{* === Main content opens here === *}
<main id="nx-main" class="nx-container py-6 sm:py-8 lg:py-10 animate-fade-in">

  {* IE warning — JS removes if not IE *}
  <div class="nx-alert nx-alert--danger mb-4" id="ie-message">
    {include file='components/icon.tpl' name='alert' size=18 class="nx-alert__icon"}
    <div>
      <div class="nx-alert__title">{$INTERNET_EXPLORER_HEADER}</div>
      <div class="nx-alert__body">{$INTERNET_EXPLORER_INFO}</div>
    </div>
  </div>

  {if isset($NEW_UPDATE)}
    <div class="nx-alert {if $NEW_UPDATE_URGENT eq true}nx-alert--danger{else}nx-alert--info{/if} mb-4" id="update-message">
      {include file='components/icon.tpl' name='arrow-down' size=18 class="nx-alert__icon"}
      <div class="flex-1">
        <div class="nx-alert__title"><a href="{$NAMELESS_UPDATE_LINK}" class="hover:underline">{$NEW_UPDATE}</a></div>
        <div class="nx-alert__body">
          <span class="block">{$CURRENT_VERSION}</span>
          <span class="block">{$NEW_VERSION}</span>
        </div>
      </div>
    </div>
  {/if}

  {if !empty($ANNOUNCEMENTS)}
    {foreach from=$ANNOUNCEMENTS item=ANNOUNCEMENT}
      <div class="nx-alert mb-4 animate-fade-in" id="announcement-{$ANNOUNCEMENT->id}"
           style="background-color:{$ANNOUNCEMENT->background_colour};color:{$ANNOUNCEMENT->text_colour}">
        {if $ANNOUNCEMENT->icon}<i class="{$ANNOUNCEMENT->icon} nx-alert__icon"></i>{/if}
        <div class="flex-1">
          <div class="nx-alert__title">{$ANNOUNCEMENT->header}</div>
          <div class="nx-alert__body" style="color:inherit;opacity:.85">{$ANNOUNCEMENT->message|escape}</div>
        </div>
        {if $ANNOUNCEMENT->closable}
          <button class="nx-btn nx-btn--ghost nx-btn--icon nx-btn--sm" aria-label="Dismiss"
                  onclick="this.parentElement.remove()">{include file='components/icon.tpl' name='close' size=14}</button>
        {/if}
      </div>
    {/foreach}
  {/if}

  {if isset($MUST_VALIDATE_ACCOUNT)}
    <div class="nx-alert nx-alert--warning mb-4">
      {include file='components/icon.tpl' name='info' size=18 class="nx-alert__icon"}
      <div class="nx-alert__body">{$MUST_VALIDATE_ACCOUNT}</div>
    </div>
  {/if}

  {if isset($MAINTENANCE_ENABLED)}
    <div class="nx-alert nx-alert--warning mb-4">
      {include file='components/icon.tpl' name='alert' size=18 class="nx-alert__icon"}
      <div class="nx-alert__body">{$MAINTENANCE_ENABLED}</div>
    </div>
  {/if}

  {if count($WIDGETS_TOP)}
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
      {foreach from=$WIDGETS_TOP item=widget}{$widget}{/foreach}
    </div>
  {/if}
