{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
    {$TITLE}
</h2>

<div class="ui stackable grid" id="alerts">
    <div class="ui centered row">
        <div class="ui six wide tablet four wide computer column">
            {include file='user/navigation.tpl'}
        </div>
        <div class="ui ten wide tablet twelve wide computer column">
            <div class="ui segment">
                <h3 class="ui header">
                    {$ALERT_TITLE}
                    {if !$ALERT_READ}
                        <span class="ui green label">
                            {$NEW}
                        </span>
                    {/if}
                    <div class="res right floated">
                        {if isset($VIEW)}
                            <a href="{$VIEW_LINK}" class="ui mini button">{$VIEW}</a>
                        {/if}
                        <a href="{$BACK_LINK}" class="ui mini blue button">{$BACK}</a>
                        <form action="{$DELETE_LINK}" method="post" style="display:inline">
                            <input type="hidden" name="token" value="{$TOKEN}">
                            <button type="submit" class="ui mini negative button">{$DELETE}</button>
                        </form>
                    </div>
                </h3>
                {if isset($ERROR)}
                <div class="ui negative message">{$ERROR}</div>
                {/if}

                <div class="ui divider"></div>

                <div class="forum_post">
                    {$ALERT_CONTENT}
                </div>
            </div>
        </div>
    </div>
</div>

{include file='footer.tpl'}