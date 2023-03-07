{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
    {$LEADERBOARDS}
</h2>

<br />

<div class="ui stackable grid">
    <div class="ui centered row">
        <div class="ui six wide tablet four wide computer column">
            <div class="ui fluid vertical pointing menu">
                {foreach from=$LEADERBOARD_PLACEHOLDERS item=placeholder}
                <a class="item leaderboard_tab" name="{$placeholder->safe_name}" server_id="{$placeholder->server_id}"
                    id="tab-{$placeholder->safe_name}-server-{$placeholder->server_id}"
                    onclick="showTable('{$placeholder->safe_name}', '{$placeholder->server_id}');">
                    {$placeholder->leaderboard_title}
                </a>
                {/foreach}
            </div>
        </div>
        <div class="ui ten wide tablet twelve wide computer column">
            {foreach from=$LEADERBOARD_PLACEHOLDERS item=placeholder}
            <div class="leaderboard_table" id="table-{$placeholder->safe_name}-server-{$placeholder->server_id}"
                style="display: none;">
                <h2>{$placeholder->leaderboard_title}</h2>
                <table class="ui fixed single line selectable unstackable small padded res table">
                    <thead>
                        <tr>
                            <th>{$PLAYER}</th>
                            <th>{$SCORE}</th>
                            <th>{$LAST_UPDATED}</th>
                        </tr>
                    </thead>
                    <tbody>

                        {foreach from=$LEADERBOARD_PLACEHOLDERS_DATA item=data}
                        {if $data->name eq $placeholder->name and $data->server_id eq $placeholder->server_id}
                        <tr>
                            <td>
                                <img class="ui middle aligned" src="{$data->avatar}" alt="{$data->username}">
                                <span>{$data->username}</span>
                            </td>
                            <td>
                                {$data->value}
                            </td>
                            <td>
                                {$data->last_updated}
                            </td>
                        </tr>
                        {/if}
                        {/foreach}
                </table>
            </div>
            {/foreach}
        </div>
    </div>
</div>

{include file='footer.tpl'}
