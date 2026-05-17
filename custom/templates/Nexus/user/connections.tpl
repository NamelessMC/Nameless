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
                    {$CONNECTIONS}
                </h3>

                {foreach from=$INTEGRATIONS item=integration}
                <div class="ui segment">
                    <div class="ui middle aligned stackable grid">
                        <div class="one wide column right aligned mobile hidden">
                            <svg width="14" height="14" viewBox="0 0 14 14"
                                fill="{if $integration.connected}{if $integration.verified}green{else}orange{/if}{else}red{/if}"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M3 7C3 4.79086 4.79086 3 7 3V3C9.20914 3 11 4.79086 11 7V7C11 9.20914 9.20914 11 7 11V11C4.79086 11 3 9.20914 3 7V7Z" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M7 14C3.13401 14 0 10.866 0 7C0 3.13401 3.13401 0 7 0C10.866 0 14 3.13401 14 7C14 10.866 10.866 14 7 14ZM7 3C4.79086 3 3 4.79086 3 7C3 9.20914 4.79086 11 7 11C9.20914 11 11 9.20914 11 7C11 4.79086 9.20914 3 7 3Z"
                                    fill-opacity="0.27" />
                            </svg>
                        </div>
                        <div class="nine wide column">
                            <strong>{$integration.name}</strong>
                            {if $integration.connected && !$integration.verified}
                            <div class="ui orange tiny label">{$PENDING_VERIFICATION}</div> {if $integration.required}
                            <div class="ui red tiny label">{$REQUIRED}</div>{/if}
                            {else if !$integration.connected && $integration.required}
                            <div class="ui red tiny label">{$REQUIRED}</div>
                            {/if}
                            </br>
                            {if $integration.connected}
                            {$integration.username}
                            {else}
                            {$NOT_CONNECTED}
                            {/if}
                        </div>
                        <div class="six wide column right aligned">
                            {if $integration.connected}
                            {if $integration.connected && !$integration.verified}
                            <form class="ui form" action="" method="post" style="display: inline">
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <input type="hidden" name="action" value="verify">
                                <input type="hidden" name="integration" value="{$integration.name}">
                                <input type="submit" class="ui mini orange button" value="{$VERIFY}">
                            </form>
                            {/if}
                            {if $integration.can_unlink}
                            <form class="ui form" action="" method="post" style="display: inline">
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <input type="hidden" name="action" value="unlink">
                                <input type="hidden" name="integration" value="{$integration.name}">
                                <input type="submit" class="ui mini negative button" value="{$UNLINK}">
                            </form>
                            {/if}
                            {else}
                            <form class="ui form" action="" method="post" style="display: inline">
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <input type="hidden" name="action" value="link">
                                <input type="hidden" name="integration" value="{$integration.name}">
                                <input type="submit" class="ui mini positive button" value="{$CONNECT}">
                            </form>
                            {/if}
                        </div>
                    </div>
                </div>
                {/foreach}

            </div>
        </div>
    </div>
</div>

{include file='footer.tpl'}