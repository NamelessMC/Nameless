{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
    {$REGISTER}
</h2>

{if isset($ERRORS)}
<div class="ui error icon message">
    <i class="x icon"></i>
    <div class="content">
        <div class="header">{$ERRORS_TITLE}</div>
        <ul class="list">
            {foreach from=$ERRORS item=error}
            <li>{$error}</li>
            {/foreach}
        </ul>
    </div>
</div>
{/if}

<div class="ui padded segment" id="complete-signup">
    <div class="ui stackable grid">
        <div class="ui centered row">
            <div class="ui sixteen wide tablet ten wide computer column">
                <form class="ui form" action="" method="post" id="form-complete-signup">
                    <div class="field">
                        <label for="inputPassword">{$PASSWORD}</label>
                        <input type="password" name="password" id="inputPassword" placeholder="{$PASSWORD}"
                            autocomplete="off" tabindex="1">
                    </div>
                    <div class="field">
                        <label for="inputPasswordAgain">{$CONFIRM_PASSWORD}</label>
                        <input type="password" name="password_again" id="inputPasswordAgain"
                            placeholder="{$CONFIRM_PASSWORD}" autocomplete="off" tabindex="2">
                    </div>
                    <div class="inline field">
                        <div class="ui checkbox">
                            <input type="checkbox" name="t_and_c" id="t_and_c" value="1" tabindex="7">
                            <label for="t_and_c">{$AGREE_TO_TERMS}</label>
                        </div>
                    </div>
                    <input type="hidden" name="token" value="{$TOKEN}">
                    <input type="submit" class="ui primary button" value="{$REGISTER}" tabindex="4">
                </form>
            </div>
        </div>
    </div>
</div>

{include file='footer.tpl'}