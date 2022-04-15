{extends 'layouts/default.tpl'}

{block 'body'}
	<div class="ui container">
		<h2 class="ui header">{$STATUS}</h2>

		<br />

		<div class="ui centered three stackable cards" id="servers">
            {foreach from=$SERVERS item=server}
				<div class="ui fluid card center aligned server" style="height: 100%;" id="server{$server->id|escape}" data-id="{$server->id|escape}" data-bungee="{$server->bungee|escape}" data-bedrock="{$server->bedrock|escape}" data-players="{$server->player_list|escape}">
					<div class="content">
						<div class="header">
                            {if $server->show_ip}<div class="ui top right attached label" data-toggle="popup" data-html="<span id='copy{$server->id|escape}'>{$server->query_ip|escape:'html'}{if $server->port && $server->port != 25565}:{$server->port|escape:'html'}{/if}</span>" onclick="copy('#copy{$server->id|escape}')">{$IP}</div>{/if}
                            {$server->name|escape:'html'}
						</div>
						<div class="description" id="server-status">
							<i class="notched circle loading icon"></i>
						</div>
					</div>
                    {if !$server->bedrock}
						<div class="extra content" id="server-players"></div>
                    {/if}
				</div>
            {foreachelse}
				<div class="ui error icon message">
					<i class="x icon"></i>
					<div class="content">
						<div class="header">Error</div>
                        {$NO_SERVERS}
					</div>
				</div>
            {/foreach}
		</div>
	</div>
{/block}