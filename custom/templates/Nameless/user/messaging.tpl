{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
    {$TITLE}
</h2>

<div class="ui stackable grid" id="messages">
    <div class="ui centered row">
        <div class="ui six wide tablet four wide computer column">
            {include file='user/navigation.tpl'}
        </div>
        <div class="ui ten wide tablet twelve wide computer column">
            <div class="ui segment">
                <h3 class="ui header">{$MESSAGING}
                    {if isset($NEW_MESSAGE)}
                    <div class="res right floated">
                        <a class="ui mini primary button" href="{$NEW_MESSAGE_LINK}">{$NEW_MESSAGE}</a>
                    </div>
                    {/if}
                </h3>
                {nocache}
                {if count($MESSAGES)}
                <table class="ui fixed single line selectable unstackable small padded res table">
                    <thead>
                        <tr>
                            <th class="nine wide">
                                <h5>{$MESSAGE_TITLE}</h5>
                            </th>
                            <th class="seven wide">
                                <h5>{$LAST_MESSAGE}</h5>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$MESSAGES item=message}
                        <tr>
                            <td>
                                <h5 class="ui header">
                                    <div class="content">
                                        <a href="{$message.link}" data-toggle="popup">{$message.title}</a>
                                        <div class="ui wide popup">
                                            <h4 class="ui header">{$message.title}</h4>
                                            {$message.participants}
                                        </div>
                                        <div class="sub header">{$message.participants}</div>
                                    </div>
                                </h5>
                            </td>
                            <td>
                                <h5 class="ui image header">
                                    <img src="{$message.last_message_user_avatar}" class="ui mini circular image">
                                    <div class="content">
                                        <a href="{$message.last_message_user_profile}"
                                            style="{$message.last_message_user_style}"
                                            data-poload="{$USER_INFO_URL}{$message.last_message_user_id}">{$message.last_message_user}</a>
                                        <div class="sub header" data-toggle="tooltip"
                                            data-content="{$message.last_message_date_full}">
                                            {$message.last_message_date}</div>
                                    </div>
                                </h5>
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
                {$PAGINATION}
                {else}
                <div class="ui info message">
                    <div class="content">
                        {$NO_MESSAGES}
                    </div>
                </div>
                {/if}
                {/nocache}
            </div>
        </div>
    </div>
</div>

{include file='footer.tpl'}