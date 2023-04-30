{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
    {$TWO_FACTOR_AUTH}
</h2>

{if isset($ERROR)}
<div class="ui error icon message">
    <i class="x icon"></i>
    <div class="content">
        <div class="header">{$ERROR_TITLE}</div>
        {$ERROR}
    </div>
</div>
{/if}

<div class="ui padded segment" id="tfa">
    <div class="ui stackable grid">
        <div class="ui centered row">
            <div class="ui sixteen wide tablet ten wide computer column">
                <form class="ui form" action="" method="post" id="form-tfa">
                    <div class="field">
                        <label for="inputEmail">{$TFA_ENTER_CODE}</label>
                        <input type="text" name="tfa_code">
                    </div>
                    <input type="hidden" name="tfa" value="true">
                    <input type="hidden" name="token" value="{$TOKEN}">
                    <input type="submit" class="ui primary button" value="{$SUBMIT}">
                </form>
            </div>
        </div>
    </div>
</div>

{include file='footer.tpl'}