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
                    {if count($SESSIONS_LIST) > 1}
                        <div class="res right floated">
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
                                <i class="{if $session.device_type === 'smartphone'}mobile alternate{elseif $session.device_type === 'tablet'}tablet alternate{else}desktop{/if} icon big"></i>
                            </div>
                            <div class="nine wide column">
                                <span>{$session.device_os} &middot; {$session.location}</span>
                                <br>
                                {$session.device_browser} &middot; {if $session.is_current}<span class="ui success text">Active now</span>{else}<span data-tooltip="{$session.last_seen}">{$session.last_seen_timeago}</span>{/if}
                            </div>
                            <div class="six wide column right aligned">
                                {if !$session.is_current}
                                    <button class="ui mini negative button" href="#" data-toggle="modal" data-target="#modal-logout">
                                        {$LOGOUT}
                                    </button>
                                {/if}
                            </div>
                        </div>
                    </div>
                {/foreach}
                {$PAGINATION}
            </div>
        </div>
    </div>
</div>

{include file='footer.tpl'}
