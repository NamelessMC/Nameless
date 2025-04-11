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
            </div>
        </div>
    </div>
</div>

{include file='footer.tpl'}