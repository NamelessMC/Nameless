{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
    {$FORGOT_PASSWORD}
    <div class="sub header">{$ENTER_NEW_PASSWORD}</div>
</h2>

{if isset($ERROR)}
<div class="ui error icon message">
    <i class="x icon"></i>
    <div class="content">
        <div class="header">{$ERROR_TITLE}</div>
        <ul class="list">
            {foreach from=$ERROR item=error}
            <li>{$error}</li>
            {/foreach}
        </ul>
    </div>
</div>
{/if}

<div class="ui padded segment" id="change-password">
    <div class="ui stackable grid">
        <div class="ui centered row">
            <div class="ui sixteen wide tablet ten wide computer column">
                <form class="ui form" action="" method="post" id="form-change-password">
                    <div class="field">
                        <label for="inputEmail">{$EMAIL_ADDRESS}</label>
                        <input type="email" name="email" id="inputEmail" placeholder="{$EMAIL_ADDRESS}" tabindex="1">
                    </div>
                    <div class="field">
                        <label for="inputPassword">{$PASSWORD}</label>
                        <input type="password" name="password" id="inputPassword" placeholder="{$PASSWORD}"
                            autocomplete="off" tabindex="2">
                    </div>
                    <div class="field">
                        <label for="inputPasswordAgain">{$CONFIRM_PASSWORD}</label>
                        <input type="password" name="password_again" id="inputPasswordAgain"
                            placeholder="{$CONFIRM_PASSWORD}" autocomplete="off" tabindex="3">
                    </div>
                    <input type="hidden" name="token" value="{$TOKEN}">
                    <input type="submit" class="ui primary button" value="{$SUBMIT}" tabindex="4">
                </form>
            </div>
        </div>
    </div>
</div>

{include file='footer.tpl'}