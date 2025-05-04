{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
    {$VALIDATE_EMAIL}
</h2>

<div class="ui padded segment" id="login">
    <div class="ui stackable grid">
        <div class="ui centered row">
            <div class="ui sixteen wide tablet ten wide computer column">
                <div class="ui positive message">
                    {$VALIDATE_EMAIL_INFO}
                </div>

                <div class="ui center aligned">
                    <form class="ui form" action="" method="post" id="form-sponsor">
                        <a class="ui primary button" onClick="$('#changeEmailModal').modal('show');">{$CHANGE_EMAIL}</a>

                        <input type="hidden" name="token" value="{$TOKEN}">
                        <input type="hidden" name="type" value="paypal">
                        <input type="submit" class="ui primary button" value="Resend Email" name="single" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change email modal -->
<div class="ui small modal" id="changeEmailModal">
    <div class="header">
        {$CHANGE_EMAIL}
    </div>
    <div class="content">
        <form class="ui form" action="" method="post" id="validate-change-email">
            <div class="field">
                <label for="inputEmail">{$EMAIL_ADDRESS}</label>
                <input type="email" name="email" id="inputEmail" value="{$EMAIL_ADDRESS_VALUE}">
            </div>
            <div class="field">
                <input type="hidden" name="token" value="{$TOKEN}">
            </div>
        </form>

    </div>
    <div class="actions">
        <a class="ui negative button">{$CANCEL}</a>
        <a class="ui positive button" onclick="$('#validate-change-email').submit();">{$CHANGE_EMAIL}</a>
    </div>
</div>

{include file='footer.tpl'}