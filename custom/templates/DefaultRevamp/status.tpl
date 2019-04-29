{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
  {$STATUS}
</h2>

<br />

{if count($SERVERS)}
  <div class="ui centered three stackable cards" id="servers">
    {foreach from=$SERVERS item=server}
      <div class="ui fluid card center aligned server" id="server{$server->id}" data-id="{$server->id}" data-bungee="{$server->bungee}" data-players="{$server->player_list}">
        <div class="content">
          <div class="header">{$server->name|escape:'html'}</div>
          <div class="description" id="server-status">
            <i class="notched circle loading icon"></i>
          </div>
        </div>
        <div class="extra content" id="server-players"></div>
      </div>
    {/foreach}
  </div>
{else}
  <div class="ui error icon message">
    <i class="x icon"></i>
    <div class="content">
      <div class="header">Error</div>
      {$NO_SERVERS}
    </div>
  </div>
{/if}

{include file='footer.tpl'}