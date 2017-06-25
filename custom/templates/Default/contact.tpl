{include file='navbar.tpl'}

<div class="container">
    <div class="card">
        <div class="card-block">
            <h2>{$CONTACT}</h2>
            {if isset($ERROR)}
                <div class="alert alert-danger">
                    {$ERROR}
                </div>
            {/if}
            {if isset($SUCCESS)}
                <div class="alert alert-success">
                    {$SUCCESS}
                </div>
            {/if}
            <form action="" method="post">
                <div class="form-group">
                    <label for="inputMessage">{$MESSAGE}</label>
                    <textarea id="inputMessage" name="content" class="form-control" rows="5"></textarea>
                </div>
                {if isset($RECAPTCHA)}
                    <div class="form-group">
                        <div class="g-recaptcha" data-sitekey="{$RECAPTCHA}"></div>
                    </div>
                {/if}
                <div class="form-group">
                    <input type="hidden" name="token" value="{$TOKEN}">
                    <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
                </div>
            </form>
        </div>
    </div>
</div>

{include file='footer.tpl'}