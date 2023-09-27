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
                    <table class="ui fixed single line selectable unstackable small padded res table">
                        <thead>
                            <tr>
                                <th>{$NAME}</th>
                                <th>{$VALUE}</th>
                                <th>{$LAST_UPDATED}</th>
                                <th>{$SHOW_ON_PROFILE}</th>
                                <th>{$SHOW_ON_FORUM}</th>
                            </tr>
                        </thead>
                        <tbody>

                            {foreach from=$PLACEHOLDERS_LIST item=data}
                            <tr>
                                <td>
                                    {$data.friendly_name}
                                </td>
                                <td>
                                    {$data.value}
                                </td>
                                <td>
                                    {$data.last_updated}
                                </td>
                                <td>
                                    {if $data.show_on_profile eq 1}
                                    <i class="fa fa-check-circle"></i>
                                    {else}
                                    <i class="fa fa-times-circle"></i>
                                    {/if}
                                </td>
                                <td>
                                    {if $data.show_on_forum eq 1}
                                    <i class="fa fa-check-circle"></i>
                                    {else}
                                    <i class="fa fa-times-circle"></i>
                                    {/if}
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
        </div>
    </div>
</div>

{include file='footer.tpl'}