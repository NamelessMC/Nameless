{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
    {$TITLE}
</h2>

{if isset($ERROR)}
<div class="ui error icon message">
    <i class="x icon"></i>
    <div class="content">
        <div class="header">{$ERROR_TITLE}</div>
        {$ERROR}
    </div>
</div>
{/if}

{if isset($MESSAGE_SENT)}
<div class="ui success icon message">
    <i class="check icon"></i>
    <div class="content">
        <div class="header">{$SUCCESS_TITLE}</div>
        {$MESSAGE_SENT}
    </div>
</div>
{/if}

<div class="ui stackable grid" id="view-message">
    <div class="ui centered row">
        <div class="ui six wide tablet four wide computer column">
            {include file='user/navigation.tpl'}
        </div>
        <div class="ui ten wide tablet twelve wide computer column">
            <div class="ui segment">
                <h3 class="ui header">
                    {$MESSAGE_TITLE}
                    <div class="sub header">{$PARTICIPANTS_TEXT}: {$PARTICIPANTS}</div>
                </h3>
            </div>
            {$PAGINATION}
            <div class="res right floated">
                <a class="ui small primary button" href="{$BACK_LINK}">{$BACK}</a>
                <button class="ui small negative button" type="button" data-toggle="modal"
                    data-target="#modal-leave">{$LEAVE_CONVERSATION}</button>
            </div>
            {foreach from=$MESSAGES item=message}
            <div class="ui fluid card" id="message">
                <div class="content">
                    <img class="ui left floated mini circular image" src="{$message.author_avatar}">
                    <div class="header">
                        <a href="{$message.author_profile}" data-poload="{$USER_INFO_URL}{$message.author_id}"
                            style="{$message.author_style}">{$message.author_username}</a>
                    </div>
                    <div class="meta">
                        <span data-toggle="tooltip"
                            data-content="{$message.message_date_full}">{$message.message_date}</span>
                    </div>
                    <div class="description forum_post">
                        {$message.content}
                    </div>
                </div>
            </div>
            {/foreach}
            {$PAGINATION}
            <div class="ui segment">
                <h3 class="ui header">{$NEW_REPLY}</h3>
                <form class="ui form" action="" method="post">
                    <div class="field">
                        <textarea name="content" id="reply"></textarea>
                    </div>
                    <input type="hidden" name="token" value="{$TOKEN}">
                    <input type="submit" class="ui primary button" value="{$SUBMIT}">
                </form>
            </div>
        </div>
    </div>
</div>

<div class="ui small modal" id="modal-leave">
    <div class="header">
        {$LEAVE_CONVERSATION}
    </div>
    <div class="content">
        {$CONFIRM_LEAVE}
        <form action="{$LEAVE_CONVERSATION_LINK}" method="post" id="leave-form">
            <input type="hidden" name="token" value="{$TOKEN}">
        </form>
    </div>
    <div class="actions">
        <a class="ui negative button">{$NO}</a>
        <a class="ui positive button" onclick="$('#leave-form').submit();">{$YES}</a>
    </div>
</div>

{include file='footer.tpl'}
