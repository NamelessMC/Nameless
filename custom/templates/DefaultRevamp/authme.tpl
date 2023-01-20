{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
    {$CONNECT_WITH_AUTHME}
    {if $AUTHME_SETUP}
        <div class="sub header">{$AUTHME_INFO}</div>
    {/if}
</h2>

{if isset($ERRORS)}
<div class="ui error icon message">
    <i class="x icon"></i>
    <div class="content">
        <div class="header">{$ERROR}</div>
        <ul class="list">
            {foreach from=$ERRORS item=error}
            <li>{$error}</li>
            {/foreach}
        </ul>
    </div>
</div>
{/if}

<div class="ui padded segment" id="authme-email">
    <div class="ui stackable grid">
        <div class="ui centered row">
            <div class="ui sixteen wide tablet ten wide computer column">
                {if $AUTHME_SETUP}
                    <form class="ui form" action="" method="post" id="form-authme-email">
                        <div class="field">
                            <label for="inputUsername">{$USERNAME}</label>
                            <input type="text" id="inputUsername" name="username" placeholder="{$USERNAME}" value="{$USERNAME_INPUT}" tabindex="1" required>
                        </div>
                        <div class="field">
                            <label for="inputPassword">{$PASSWORD}</label>
                            <input type="password" id="inputPassword" name="password" placeholder="{$PASSWORD}" tabindex="2" required>
                        </div>
                        {if $CAPTCHA}
                            <div class="field">
                                {$CAPTCHA}
                            </div>
                        {/if}
                        <div class="inline field">
                            <div class="ui checkbox">
                                <input type="checkbox" name="t_and_c" id="t_and_c" value="1" tabindex="7" required>
                                <label for="t_and_c">{$AGREE_TO_TERMS}</label>
                            </div>
                        </div>
                        <input type="hidden" name="token" value="{$TOKEN}">
                        <input type="submit" class="ui primary button" value="{$SUBMIT}" tabindex="5">
                    </form>
                {else}
                    <div class="ui message red">
                        {$AUTHME_NOT_SETUP}
                    </div>
                {/if}
            </div>
        </div>
    </div>
</div>

{include file='footer.tpl'}
