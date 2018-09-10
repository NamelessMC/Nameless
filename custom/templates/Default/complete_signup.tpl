{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="container">
    <div class="card">
        <div class="card-body">
            <form role="form" action="" method="post">
                <h2>{$REGISTER}</h2>

                {if isset($ERRORS)}
                    <div class="alert alert-danger" role="alert">
                        {foreach from=$ERRORS item=item name=err}
                            {$item}
                            {if not $smarty.foreach.err.last}<br/>{/if}
                        {/foreach}
                    </div>
                {/if}

                <div class="form-group">
                    <label for="inputPassword">{$PASSWORD}</label>
                    <input type="password" class="form-control" name="password" id="inputPassword" autocomplete="off"
                           placeholder="{$PASSWORD}" tabindex="1">
                </div>

                <div class="form-group">
                    <label for="inputPasswordAgain">{$CONFIRM_PASSWORD}</label>
                    <input type="password" class="form-control" name="password_again" id="inputPasswordAgain"
                           autocomplete="off" placeholder="{$CONFIRM_PASSWORD}" tabindex="2">
                </div>

                <hr />
                {$AGREE_TO_TERMS}
                <br />
                <span class="button-checkbox">
				  <button type="button" class="btn" data-color="info" tabindex="7"> {$I_AGREE}</button>
				  <input type="checkbox" name="t_and_c" id="t_and_c" style="display:none;" value="1">
				</span>
                <br /><br />

                <div class="form-group">
                    <input type="hidden" name="token" value="{$TOKEN}">
                    <input type="submit" class="btn btn-primary" value="{$REGISTER}">
                </div>
            </form>
        </div>
    </div>
</div>

{include file='footer.tpl'}
