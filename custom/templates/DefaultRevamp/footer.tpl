    </div>
  </div>
  
  <div class="ui inverted vertical footer segment" id="footer">
    <div class="ui container">
      <div class="ui stackable inverted divided equal height stackable grid">
        <div class="six wide column">
          <h4 class="ui inverted header">{$SITE_NAME}</h4>
          <div class="ui inverted link list">
            <span class="item">&copy; {$SITE_NAME} {'Y'|date}</span>
            <span class="item">Powered By <a href="https://namelessmc.com">NamelessMC</a></span>
            {if $PAGE_LOAD_TIME}
              <span class="item" id="page_load"></span>
            {/if}
            {if isset($LOGGED_IN_USER)}
              <a class="item" href="javascript:" onclick="toggleDarkLightMode()">{$DARK_LIGHT_MODE}</a>
            {/if}
          </div>
        </div>
        <div class="five wide column">
          <h4 class="ui inverted header">{$FOOTER_LINKS_TITLE}</h4>
          <div class="ui inverted link list">
            {foreach from=$FOOTER_NAVIGATION key=name item=item}
              {if isset($item.items)}
                <div class="ui pointing dropdown link item">
                  <span class="text">{$item.icon} {$item.title}</span> <i class="dropdown icon"></i>
                  <div class="menu">
                    <div class="header">{$item.title}</div>
                    {foreach from=$item.items item=dropdown}
                      <a class="item" href="{$dropdown.link}" target="{$dropdown.target}">{$dropdown.icon} {$dropdown.title}</a>
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
        <div class="five wide column">
          <h4 class="ui inverted header">{$FOOTER_SOCIAL_TITLE}</h4>
          <div class="ui inverted link list">
            {foreach from=$SOCIAL_MEDIA_ICONS item=icon}
              <a class="item" href="{$icon.link}">{$icon.text}</a>
            {/foreach}
          </div>
        </div>
      </div>
    </div>
  </div>

  {if isset($GLOBAL_WARNING_TITLE)}
    <div class="ui medium modal" id="modal-acknowledge">
      <div class="header">
        {$GLOBAL_WARNING_TITLE}
      </div>
      <div class="content">
        {$GLOBAL_WARNING_REASON}
      </div>
      <div class="actions">
        <a class="ui positive button" href="{$GLOBAL_WARNING_ACKNOWLEDGE_LINK}">{$GLOBAL_WARNING_ACKNOWLEDGE}</a>
      </div>
    </div>
  {/if}

  {foreach from=$TEMPLATE_JS item=script}
    {$script}
  {/foreach}

  {if isset($GLOBAL_WARNING_TITLE)}
    <script type="text/javascript">
      $('#modal-acknowledge').modal({ closable: false }).modal('show');
    </script>
  {/if}

  {if isset($LOGGED_IN_USER)}
    <script type="text/javascript">
      function toggleDarkLightMode() {
        $.post("{$DARK_LIGHT_MODE_ACTION}", { token: "{$DARK_LIGHT_MODE_TOKEN}" })
          .done(function() {
            window.location.reload();
          });

        return false;
      }
    </script>
  {/if}

  {if isset($NEW_UPDATE) && ($NEW_UPDATE_URGENT != true)}
    <script src="{$TEMPLATE.path}/js/core/update.js"></script>
  {/if}

{if !isset($EXCLUDE_END_BODY)}
  </body>

  </html>
{/if}
