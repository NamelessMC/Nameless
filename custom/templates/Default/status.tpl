{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="container">
    <div class="card">
        <div class="card-body">
            <h2>{$STATUS}</h2>

            {if isset($CLICK_TO_COPY_TOOLTIP)}
            <div class="alert alert-info" style="text-align:center">
                <span onclick="copyToClipboard('#ip')" data-toggle="tooltip" title="{$CLICK_TO_COPY_TOOLTIP}">{$CONNECT_WITH}</span>
            </div>
            {/if}

            {if count($SERVERS)}
                {assign var=i value=0}
                {foreach from=$SERVERS item=server name=serverArray}
                    {if $i eq 0 OR ($i % 3) eq 0}
                        <div class="card-deck" style="text-align:center">
                    {/if}

                    <div class="card server" id="server{$server->id}" data-id="{$server->id}" data-bungee="{$server->bungee}" data-players="{$server->player_list}">
                        <div class="card-header">
                            {$server->name|escape:'html'}<br />
                            {if $server->show_ip}<span id="copy{$server->id|escape}" onclick="copyToClipboard('#copy{$server->id|escape}')">{$server->query_ip|escape:'html'}{if $server->port && $server->port != 25565}:{$server->port|escape:'html'}{/if}</span>{/if}
                        </div>
                        <div class="card-body" id="content{$server->id}">
                            <i class="fa fa-spinner fa-pulse fa-2x" id="spinner{$server->id}"></i>
                        </div>
                    </div>

                    {if (($i+1) % 3) eq 0 OR $smarty.foreach.serverArray.last}
                        </div><br />
                    {/if}
                    {assign var=i value=$i+1}
                {/foreach}
            {else}
                <div class="alert alert-warning" style="text-align:center">{$NO_SERVERS}</div>
            {/if}
        </div>
    </div>
</div>

{include file='footer.tpl'}