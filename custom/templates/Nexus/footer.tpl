{*
 *  Nexus · Document footer
 *  Closes <main>, renders footer-widgets and the site footer, then injects $TEMPLATE_JS.
 *  Preserves $WIDGETS_FOOTER, $FOOTER_NAVIGATION, $SOCIAL_MEDIA_ICONS, $TERMS_*, $PRIVACY_*,
 *  $DARK_LIGHT_MODE_ACTION/$DARK_LIGHT_MODE_TOKEN, $AUTO_LANGUAGE_*, $GLOBAL_WARNING_* contracts.
*}
  {if count($WIDGETS_FOOTER)}
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
      {foreach from=$WIDGETS_FOOTER item=widget}{$widget}{/foreach}
    </div>
  {/if}
</main>

<footer class="mt-12 border-t border-border-subtle bg-bg-inset">
  <div class="nx-container py-10 grid gap-8 md:grid-cols-3">
    <div>
      <div class="flex items-center gap-2 font-display font-semibold text-text-primary text-base mb-3">
        <span class="inline-flex items-center justify-center w-7 h-7 rounded-md bg-gradient-to-br from-accent to-accent-hover text-white">
          {include file='components/icon.tpl' name='sparkle' size=14}
        </span>
        {$smarty.const.SITE_NAME}
      </div>
      <p class="text-text-muted text-sm leading-relaxed">
        &copy; {$smarty.const.SITE_NAME} {'Y'|date}<br>
        Powered by <a href="https://namelessmc.com" class="text-text-secondary hover:text-text-primary">NamelessMC</a>
      </p>
      {if $PAGE_LOAD_TIME}
        <p class="text-text-muted text-xs mt-3" id="page_load"></p>
      {/if}
    </div>

    <div>
      <h4 class="text-xs uppercase tracking-wider font-semibold text-text-muted mb-3">{$FOOTER_LINKS_TITLE}</h4>
      <ul class="space-y-1.5 text-sm">
        {foreach from=$FOOTER_NAVIGATION key=name item=item}
          {if isset($item.items)}
            <li x-data="{ open: false }">
              <button class="flex items-center gap-1.5 text-text-secondary hover:text-text-primary transition"
                      @click="open = !open" :aria-expanded="open">
                <span class="opacity-80">{$item.icon}</span>{$item.title}
                {include file='components/icon.tpl' name='chevron-down' size=12}
              </button>
              <ul x-show="open" x-cloak x-transition class="pl-3 mt-1 space-y-1.5 border-l border-border-subtle">
                {foreach from=$item.items item=dropdown}
                  <li><a href="{$dropdown.link}" target="{$dropdown.target}"
                         class="text-text-muted hover:text-text-primary transition">
                    <span class="opacity-80">{$dropdown.icon}</span> {$dropdown.title}
                  </a></li>
                {/foreach}
              </ul>
            </li>
          {else}
            <li><a href="{$item.link}" target="{$item.target}"
                   class="text-text-secondary hover:text-text-primary transition flex items-center gap-1.5">
              <span class="opacity-80">{$item.icon}</span>{$item.title}
            </a></li>
          {/if}
        {/foreach}
        <li><a href="{$TERMS_LINK}" class="text-text-secondary hover:text-text-primary transition">{$TERMS_TEXT}</a></li>
        <li><a href="{$PRIVACY_LINK}" class="text-text-secondary hover:text-text-primary transition">{$PRIVACY_TEXT}</a></li>
      </ul>
    </div>

    <div>
      {if $SOCIAL_MEDIA_ICONS|count > 0}
        <h4 class="text-xs uppercase tracking-wider font-semibold text-text-muted mb-3">{$FOOTER_SOCIAL_TITLE}</h4>
        <ul class="flex flex-wrap gap-2">
          {foreach from=$SOCIAL_MEDIA_ICONS item=icon}
            <li>
              <a href="{$icon.link}" target="_blank" rel="noopener"
                 class="nx-btn nx-btn--outline nx-btn--sm">
                {$icon.text}
              </a>
            </li>
          {/foreach}
        </ul>
      {/if}

      <div class="mt-6 flex items-center gap-2 text-xs text-text-muted">
        {if isset($AUTO_LANGUAGE)}
          <a class="hover:text-text-primary cursor-pointer" onclick="toggleAutoLanguage()" id="auto-language"></a>
        {/if}
      </div>
    </div>
  </div>
</footer>

{* === Legacy global warning modal === *}
{if isset($GLOBAL_WARNING_TITLE)}
<div class="ui medium modal" id="modal-acknowledge">
  <div class="header">{$GLOBAL_WARNING_TITLE}</div>
  <div class="content">{$GLOBAL_WARNING_REASON}</div>
  <div class="actions">
    <a class="ui positive button" href="{$GLOBAL_WARNING_ACKNOWLEDGE_LINK}">{$GLOBAL_WARNING_ACKNOWLEDGE}</a>
  </div>
</div>
{/if}

{* === Toast container === *}
{include file='components/toasts.tpl'}

{* === Command palette === *}
{include file='components/command_palette.tpl'}

{* === Module/template JS === *}
{foreach from=$TEMPLATE_JS item=script}{$script}{/foreach}

{if isset($GLOBAL_WARNING_TITLE)}
<script>$('#modal-acknowledge').modal({ closable: false }).modal('show');</script>
{/if}

<script>
  // Dark/light toggle bridges into the legacy server-side preference if available.
  // The header-inline script already applied the preferred theme pre-paint.
  {if isset($DARK_LIGHT_MODE_ACTION) && isset($DARK_LIGHT_MODE_TOKEN)}
    function toggleDarkLightMode() {
      $.post("{$DARK_LIGHT_MODE_ACTION}", { token: "{$DARK_LIGHT_MODE_TOKEN}" })
        .done(function () { window.location.reload(); });
      return false;
    }
  {/if}

  {if isset($AUTO_LANGUAGE)}
    const autoLanguage = document.getElementById('auto-language');
    const autoLanguageValue = $.cookie('auto_language') ?? 'true';
    autoLanguage.innerText = '{$AUTO_LANGUAGE_TEXT} (' + (autoLanguageValue === 'true' ? '{$ENABLED}' : '{$DISABLED}') + ')';
    {if isset($AUTO_LANGUAGE_VALUE)}
      if (autoLanguageValue) autoLanguage.title = '{$AUTO_LANGUAGE_VALUE}';
    {/if}
    function toggleAutoLanguage() {
      $.cookie('auto_language', autoLanguageValue === 'true' ? 'false' : 'true', { path: '/' });
      window.location.reload();
    }
  {/if}
</script>

{if isset($NEW_UPDATE) && ($NEW_UPDATE_URGENT != true)}
  <script src="{$TEMPLATE.path}/js/core/update.js"></script>
{/if}

{if !isset($EXCLUDE_END_BODY)}
  {if isset($DEBUGBAR_HTML)}{$DEBUGBAR_HTML}{/if}
</body>
</html>
{/if}
