<div class="ui fluid card">
    <div class="content">
        <div class="header">Minecraft Account</div>
    </div>
    <div class="content center aligned">
        <img class="ui image tiny" src="https://visage.surgeplay.com/full/500/{$UUID}" alt="Minecraft image">
        <div class="ui left pointing label">
            <strong data-tooltip="{$UUID_FORMATTED}" style="text-overflow: clip;">
                {$USERNAME}
            </strong>
        </div>
    </div>
    <div class="extra content">
        Last seen: <span data-tooltip="{$LAST_ONLINE}">{$LAST_ONLINE_AGO}</span> on <span data-tooltip="{$LAST_ONLINE_SERVER_IP}">{$LAST_ONLINE_SERVER}</span>
    </div>
</div>
