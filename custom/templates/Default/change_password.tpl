{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="container">
    <div class="card">
        <div class="card-body">
            <form role="form" action="" method="post">
                <h2>{$FORGOT_PASSWORD}</h2>
                <p>{$ENTER_NEW_PASSWORD}</p>

                {if isset($ERROR)}
                    <div class="alert alert-danger" role="alert">
                        {foreach from=$ERROR item=item name=err}
                            {$item}
                            {if not $smarty.foreach.err.last}<br/>{/if}
                        {/foreach}
                    </div>
                {/if}

                <div class="form-group">
                    <label for="inputEmail">{$EMAIL_ADDRESS}</label>
                    <input type="email" class="form-control" name="email" id="inputEmail" placeholder="{$EMAIL_ADDRESS}"
                           tabindex="1">
                </div>


                <div class="form-group">
                    <label for="inputPassword">{$PASSWORD}</label>
                    <input type="password" class="form-control" name="password" id="inputPassword" autocomplete="off"
                           placeholder="{$PASSWORD}" tabindex="2">
                </div>

                <div class="form-group">
                    <label for="inputPasswordAgain">{$CONFIRM_PASSWORD}</label>
                    <input type="password" class="form-control" name="password_again" id="inputPasswordAgain"
                           autocomplete="off" placeholder="{$CONFIRM_PASSWORD}" tabindex="3">
                </div>

                <div class="form-group">
                    <input type="hidden" name="token" value="{$TOKEN}">
                    <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
                </div>
            </form>
        </div>
    </div>
</div>

{include file='footer.tpl'}
