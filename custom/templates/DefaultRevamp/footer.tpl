<footer class="ui inverted vertical footer segment" id="footer">
  <div class="ui container">
    <div class="ui stackable inverted divided equal height stackable grid">
      <div class="{if $SOCIAL_MEDIA_ICONS|count > 0}six{else}eight{/if} wide column">
        <h4 class="ui inverted header">{$SITE_NAME}</h4>
        <div class="ui inverted link list">
          <span class="item">&copy; {$SITE_NAME} {'Y'|date}</span>
          <span class="item">Powered By <a href="https://namelessmc.com">NamelessMC</a></span>
            {if $PAGE_LOAD_TIME}
              <span class="item" id="page_load"></span>
            {/if}
          <a class="item" href="javascript:" onclick="toggleDarkLightMode()">{$TOGGLE_DARK_MODE_TEXT}</a>
        </div>
      </div>
      <div class="{if $SOCIAL_MEDIA_ICONS|count > 0}five{else}eight{/if} wide column">
        <h4 class="ui inverted header">{$FOOTER_LINKS_TITLE}</h4>
        <div class="ui inverted link list">
            {foreach from=$FOOTER_NAVIGATION key=name item=item}
                {if isset($item.items)}
                  <div class="ui pointing dropdown link item">
                    <span class="text">{$item.icon} {$item.title}</span> <i class="dropdown icon"></i>
                    <div class="menu">
                      <div class="header">{$item.title}</div>
                        {foreach from=$item.items item=dropdown}
                          <a class="item" href="{$dropdown.link}"
                             target="{$dropdown.target}">{$dropdown.icon} {$dropdown.title}</a>
                        {/foreach}
                    </div>
                  </div>
                {else}
                  <a class="item" href="{$item.link}" target="{$item.target}">{$item.icon} {$item.title}</a>
                {/if}
            {/foreach}
          <a class="item" href="{$TERMS_LINK}">{$TERMS_TEXT}</a>
          <a class="item" href="{$PRIVACY_LINK}">{$PRIVACY_TEXT}</a>
        </div>
      </div>
        {if $SOCIAL_MEDIA_ICONS|count > 0}
          <div class="five wide column">
            <h4 class="ui inverted header">{$FOOTER_SOCIAL_TITLE}</h4>
            <div class="ui inverted link list">
                {foreach from=$SOCIAL_MEDIA_ICONS item=icon}
                  <a class="item" href="{$icon.link}">{$icon.text}</a>
                {/foreach}
            </div>
          </div>
        {/if}
    </div>
  </div>
</footer>