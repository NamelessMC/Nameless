{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
    {$SESSIONS}
</h2>

<div class="ui stackable grid" id="alerts">
    <div class="ui centered row">
        <div class="ui six wide tablet four wide computer column">
            {include file='user/navigation.tpl'}
        </div>
        <div class="ui ten wide tablet twelve wide computer column">
            <div class="ui segment">
                <h3 class="ui header">
                    {$SESSIONS}
                    {if $CAN_LOGOUT_ALL}
                        <div class="right floated">
                            <a class="ui mini negative button" href="#" data-toggle="modal" data-target="#modal-logout-all">
                                {$LOGOUT_OTHER_SESSIONS}
                            </a>
                        </div>
                    {/if}
                </h3>
                {if isset($SUCCESS_MESSAGE)}
                    <div class="ui success icon message">
                        <i class="check icon"></i>
                        <div class="content">
                            <div class="header">{$SUCCESS}</div>
                            {$SUCCESS_MESSAGE}
                        </div>
                    </div>
                {/if}
                {if isset($ERROR_MESSAGE)}
                    <div class="ui negative icon message">
                        <i class="x icon"></i>
                        <div class="content">
                            <div class="header">{$ERROR}</div>
                            {$ERROR_MESSAGE}
                        </div>
                    </div>
                {/if}
                {foreach from=$SESSIONS_LIST item=session}
                    <div class="ui segment">
                        <div class="ui middle aligned stackable grid">
                            <div class="one wide column">
                                <i class="{if $session.device_type === 'phone'}mobile alternate{elseif $session.device_type === 'tablet'}tablet alternate{elseif $session.device_type === 'laptop'}laptop alternate{else}desktop{/if} icon big"></i>
                            </div>
                            <div class="nine wide column">
                                <span>{$session.device_os} &middot; {$session.device_browser} {$session.device_browser_version}</span>
                                <br>
                                {$session.location}, {if $session.is_current}<span class="ui success text">{$THIS_DEVICE}</span>{else}<span data-tooltip="{$session.last_seen}">{$session.last_active}</span>{/if}
                                {if $session.is_admin}
                                    <br>
                                    <span class="ui mini label">
                                        <i class="user secret icon"></i> {$ADMIN_LOGGED_IN}
                                    </span>
                                {elseif $session.is_remembered}
                                    <br>
                                    <span class="ui mini label">
                                        <i class="check icon"></i> {$REMEMBERED}
                                    </span>
                                {/if}
                            </div>
                            <div class="six wide column right aligned">
                                {if !$session.is_current}
                                    <a class="ui mini negative button" href="#" data-toggle="modal" data-target="#modal-logout-{$session.id}">
                                        {$LOGOUT}
                                    </a>
                                {/if}
                            </div>
                        </div>
                    </div>
                {/foreach}
            </div>
        </div>
    </div>
</div>

{if $CAN_LOGOUT_ALL}
    <div class="ui small modal" id="modal-logout-all">
        <div class="header">
            {$LOGOUT}
        </div>
        <div class="content">
            {$LOGOUT_ALL_CONFIRM}
        </div>
        <div class="actions">
            <a class="ui negative button">{$NO}</a>
            <form action="" method="post" style="display: inline;">
                <input type="hidden" name="token" value="{$TOKEN}" />
                <input type="hidden" name="action" value="logout_other_sessions" />
                <input type="submit" class="ui green button" value="{$YES}" />
            </form>
        </div>
    </div>
{/if}

{foreach from=$SESSIONS_LIST item=session}
    <div class="ui small modal" id="modal-logout-{$session.id}">
        <div class="header">
            {$LOGOUT}
        </div>
        <div class="content">
            {$LOGOUT_CONFIRM}
        </div>
        <div class="actions">
            <a class="ui negative button">{$NO}</a>
            <form action="" method="post" style="display: inline;">
                <input type="hidden" name="token" value="{$TOKEN}" />
                <input type="hidden" name="session_hash" value="{$session.id}" />
                <input type="submit" class="ui green button" value="{$YES}" />
            </form>
        </div>
    </div>
{/foreach}

{include file='footer.tpl'}
