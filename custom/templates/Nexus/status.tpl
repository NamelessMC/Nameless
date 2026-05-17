{include file='header.tpl'}
{include file='navbar.tpl'}

<h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight mb-6">{$STATUS}</h1>

{if count($SERVERS)}
  <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4" id="servers">
    {foreach from=$SERVERS item=server}
      <div class="nx-card server" style="height:100%" id="server{$server->id|escape}"
           data-id="{$server->id|escape}" data-bungee="{$server->bungee|escape}" data-bedrock="{$server->bedrock|escape}"
           data-players="{$server->player_list|escape}">
        <div class="nx-card__body">
          <div class="flex items-start justify-between gap-3 mb-3">
            <h3 class="nx-card__title">{$server->name|escape:'html'}</h3>
            {if $server->show_ip}
              <button class="nx-badge nx-badge--accent cursor-pointer"
                      onclick="copy('#copy{$server->id|escape}')"
                      title="{$IP}">
                <span id="copy{$server->id|escape}">{$server->query_ip|escape:'html'}{if $server->port && $server->port != 25565}:{$server->port|escape:'html'}{/if}</span>
              </button>
            {/if}
          </div>
          <div class="text-text-secondary text-sm" id="server-status">
            <span class="nx-spinner inline-block align-middle mr-2"></span>
          </div>
        </div>
        {if !$server->bedrock}
          <div class="nx-card__footer" id="server-players"></div>
        {/if}
      </div>
    {/foreach}
  </div>
{else}
  <div class="nx-alert nx-alert--danger">
    {include file='components/icon.tpl' name='alert' size=18 class="nx-alert__icon"}
    <div><div class="nx-alert__title">{$ERROR_TITLE}</div><div class="nx-alert__body">{$NO_SERVERS}</div></div>
  </div>
{/if}

{include file='footer.tpl'}
