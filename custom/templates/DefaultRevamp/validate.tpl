{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
    {$VALIDATE_EMAIL}
</h2>

<div class="ui padded segment" id="login">
    <div class="ui stackable grid">
        <div class="ui centered row">
            <div class="ui sixteen wide tablet ten wide computer column">
                {if isset($SUCCESS) && !isset($ERRORS)}
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
                            <div class="header">{$ERRORS_TITLE}</div>
                            <ul class="list">
                                {foreach from=$ERRORS item=error}
                                    <li>{$error}</li>
                                {/foreach}
                            </ul>
                        </div>
                    </div>
                {/if}

                <div class="ui center aligned">
                    <a class="ui primary button" onClick="$('#changeEmailModal').modal('show');">{$CHANGE_OR_RESEND_EMAIL}</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change email modal -->
<div class="ui small modal" id="changeEmailModal">
    <div class="header">
        {$CHANGE_OR_RESEND_EMAIL}
    </div>
    <div class="content">
        <form class="ui form" action="" method="post" id="validate-change-email">
            <div class="field">
                <label for="inputEmail">{$EMAIL_ADDRESS}</label>
                <input type="email" name="email" id="inputEmail" value="{$EMAIL_ADDRESS_VALUE}">
            </div>
            <div class="field">
                <input type="hidden" name="action" value="change_email">
                <input type="hidden" name="token" value="{$TOKEN}">
            </div>
        </form>

    </div>
    <div class="actions">
        <a class="ui negative button">{$CANCEL}</a>
        <a class="ui positive button" onclick="$('#validate-change-email').submit();">{$CHANGE_OR_RESEND_EMAIL}</a>
    </div>
</div>

{include file='footer.tpl'}