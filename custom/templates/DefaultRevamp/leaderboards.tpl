{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
    {$LEADERBOARDS}
</h2>

<br />

<div class="ui stackable equal width grid">
    <div class="ui centered row">
        <div class="ui four wide column">
            <div class="ui fluid vertical menu pointing">
                {foreach from=$LEADERBOARD_PLACEHOLDERS item=placeholder}
                    <a
                        class="item leaderboard_tab"
                        name="{$placeholder->safe_name}"
                        server_id="{$placeholder->server_id}"
                        id="tab-{$placeholder->safe_name}-server-{$placeholder->server_id}"
                        onclick="showTable('{$placeholder->safe_name}', '{$placeholder->server_id}');"
                    >
                        {$placeholder->leaderboard_title}
                    </a>
                {/foreach}
            </div>
        </div>

        <div class="ui column">
            {foreach from=$LEADERBOARD_PLACEHOLDERS item=placeholder}
                <div class="ui stackable equal width left aligned three column grid segment leaderboard_table" style="display: none;" id="table-{$placeholder->safe_name}-server-{$placeholder->server_id}">
                    <div class="ui column">
                        <h3>{$placeholder->leaderboard_title}</h3>
                        <div>
                            <ol class="ui list large selection">
                                {foreach from=$LEADERBOARD_PLACEHOLDERS_DATA item=data}
                                    {if $data->name eq $placeholder->name and $data->server_id eq $placeholder->server_id}
                                        <li class="item">
                                            <a href="{$data->profile}">
                                                <div class="right floated content">
                                                    <div class="ui header">{$data->value}</div>
                                                </div>

                                                <img class="ui avatar image" src="{$data->avatar}" alt="{$data->username}" />

                                                <div class="middle aligned content">
                                                    <span style="{$data->style}">
                                                        {$data->username}
                                                    </span>

                                                    {foreach from=$data->groups item=group}
                                                        {$group}
                                                    {/foreach}

                                                    <div class="description">
                                                        <span class="ui text small" data-toggle="tooltip" data-content="{$data->last_updated_full}">{$data->last_updated_string}</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    {/if}
                                {/foreach}
                            </ol>
                        </div>
                    </div>
                </div>
            {/foreach}
        </div>
    </div>
</div>

{include file='footer.tpl'}
