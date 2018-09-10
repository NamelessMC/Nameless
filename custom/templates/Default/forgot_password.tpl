{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="container">
    <div class="card">
        <div class="card-body">
            <form role="form" action="" method="post">
                <h2>{$FORGOT_PASSWORD}</h2>
                <p>{$FORGOT_PASSWORD_INSTRUCTIONS}</p>

                {if isset($ERROR)}
                    <div class="alert alert-danger">
                        {$ERROR}
                    </div>
                {elseif isset($SUCCESS)}
                    <div class="alert alert-success">
                        {$SUCCESS}
                    </div>
                {/if}

                <div class="form-group">
                    <label for="inputEmail">{$EMAIL_ADDRESS}</label>
                    <input type="email" id="inputEmail" name="email" placeholder="{$EMAIL_ADDRESS}" class="form-control">
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