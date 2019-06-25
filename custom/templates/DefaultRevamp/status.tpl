{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
  {$STATUS}
</h2>

<br />

{if count($SERVERS)}
  <div class="ui centered three stackable cards" id="servers">
    {foreach from=$SERVERS item=server}
      <div class="ui fluid card center aligned server" id="server{$server->id|escape}" data-id="{$server->id|escape}" data-bungee="{$server->bungee|escape}" data-players="{$server->player_list|escape}">
        <div class="content">
          <div class="header">
            {if $server->show_ip}<div class="ui top right attached label" data-toggle="popup" data-html="<span id='copy{$server->id|escape}'>{$server->query_ip|escape:'html'}{if $server->port && $server->port != 25565}:{$server->port|escape:'html'}{/if}</span>" onclick="copy('#copy{$server->id|escape}')">{$IP}</div>{/if}
            {$server->name|escape:'html'}
          </div>
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