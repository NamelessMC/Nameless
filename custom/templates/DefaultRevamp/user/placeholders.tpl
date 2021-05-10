{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
  {$TITLE}
</h2>

{if isset($SUCCESS)}
  <div class="ui success icon message">
    <i class="check icon"></i>
    <div class="content">
      <div class="header">{$SUCCESS_TITLE}</div>
      {$SUCCESS}
    </div>
  </div>
{/if}

{if isset($ERRORS)}
<div class="ui error icon message">
  <i class="x icon"></i>
  <div class="content">
    <ul class="list">
      {foreach from=$ERRORS item=error}
      <li>{$error}</li>
      {/foreach}
    </ul>
  </div>
</div>
{/if}

<div class="ui stackable grid" id="alerts">
  <div class="ui centered row">
    <div class="ui six wide tablet four wide computer column">
      {include file='user/navigation.tpl'}
    </div>
    <div class="ui ten wide tablet twelve wide computer column">
      <div class="ui segment">
        <h3 class="ui header">
          {$PLACEHOLDERS}
        </h3>
        <div class="ui middle aligned">
          {nocache}
            {if count($PLACEHOLDERS_LIST)}
            <table class="ui fixed single line selectable unstackable small padded res table" id="subforums-table">
              <thead>
                <tr>
                  <th>Server ID</th>
                  <th>Name</th>
                  <th>Value</th>
                  <th>Last Updated</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
              
              {foreach from=$PLACEHOLDERS_LIST item=data}
              <tr>
                <td>
                  {$data.server_id}
                </td>
                <td>
                  {$data.name}
                </td>
                <td>
                  {$data.value}
                </td>
                <td>
                  {$data.last_updated}
                </td>
                <td>
                  <button class="ui yellow icon button" onclick="showPlaceholderSettings('{$data.name}')"><i class="fas fa-cog icon black"></i></button>
                </td>
              </tr>
              {/foreach}

            </table>
            {else}
              <div class="ui info message">
                <div class="content">
                  {$NO_PLACEHOLDERS}
                </div>
              </div>
            {/if}
          {/nocache}
        </div>
      </div>

      {nocache}
      {if count($PLACEHOLDERS_LIST)}
        {foreach from=$PLACEHOLDERS_LIST item=data}
            <div class="ui ten wide tablet twelve wide computer column placeholder-settings" id="placeholder-settings-{$data.name}" style="display: none;">
                <div class="ui segment">
                <h3 class="ui header">
                    {$OPTIONS}
                    <div class="sub header">{$data.name}</div>
                </h3>
                <div class="ui middle aligned">
                    <table class="ui fixed single line selectable unstackable small padded res table" id="subforums-table">
                        <thead>
                            <tr>
                            <th class="center aligned">Show on Profile</th>
                            <th class="center aligned">Show on Forum Posts</th>
                            <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        
                        <form action="" method="POST">

                            <input type="hidden" name="placeholder_name" value="{$data.name}">
                            <input type="hidden" name="token" value="{$TOKEN}">

                            <tr class="center aligned">
                                <td>
                                    <div class="ui checkbox">
                                        <input type="checkbox" name="show_on_profile" {if $data.show_on_profile eq 1} checked {/if}>
                                        <label></label>
                                    </div>
                                </td>

                                <td>
                                    <div class="ui checkbox">
                                        <input type="checkbox" name="show_on_forum" {if $data.show_on_forum eq 1} checked {/if}>
                                        <label></label>
                                    </div>
                                </td>

                                <td>
                                    <button class="ui primary icon button" type="submit"><i class="fas fa-save icon white"></i></button>
                                </td>
                            </tr>

                        </form>

                    </table>
                </div>
                </div>
            </div> 
        {/foreach}
      {/if}
      {/nocache}

    </div>
  </div>
</div>

<script>

const placeholder_settings = document.getElementsByClassName('placeholder-settings');

function showPlaceholderSettings(name) {
      Array.prototype.forEach.call(placeholder_settings, (ps) => {
          $(ps).hide(100);
      });

      $('#placeholder-settings-' + name).show(100);
}

</script>

{include file='footer.tpl'}
