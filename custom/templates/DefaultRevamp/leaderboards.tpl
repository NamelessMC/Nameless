{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
  {$LEADERBOARDS}
</h2>

<br />

<div class="ui stackable grid">
  <div class="ui centered row">
    <div class="ui six wide tablet four wide computer column">
        <div class="ui vertical pointing menu">
            {foreach from=$LEADERBOARD_PLACEHOLDERS item=placeholder}
                <a class="item" id="tab-{$placeholder->name}">
                    {$placeholder->leaderboard_title}
                </a>
            {/foreach}
        </div>
    </div>
    <div class="ui ten wide tablet twelve wide computer column">
        {foreach from=$LEADERBOARD_PLACEHOLDERS item=placeholder}
            <h2>{$placeholder->leaderboard_title}</h2>
            <table class="ui fixed single line selectable unstackable small padded res table" id="subforums-table">
                <thead>
                    <tr>
                        <th>Player</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>

                {foreach from=$LEADERBOARD_PLACEHOLDERS_DATA item=data}
                    {if $data->name eq $placeholder->name}
                        <tr>
                            <td>
                                {$data->uuid}
                            </td>
                            <td>
                                {$data->value}
                            </td>
                        </tr>
                    {/if}
                {/foreach}
            </table>
        {/foreach}
    </div>
  </div>
</div>

{include file='footer.tpl'}