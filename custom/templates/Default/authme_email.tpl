{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="container">
    <div class="card">
        <div class="card-body">
            <form action="" method="post">
                <h2>{$CONNECT_WITH_AUTHME}</h2>
                <hr />

                {if isset($ERRORS)}
                    <div class="alert alert-danger">
                        {foreach from=$ERRORS item=error}
                            {$error}<br />
                        {/foreach}
                    </div>
                {/if}

                <div class="alert alert-info">
                    <p>{$AUTHME_SUCCESS}</p>
                    {$AUTHME_INFO}
                </div>

                <div class="form-group">
                    <label for="inputEmail">{$EMAIL}</label>
                    <input type="email" id="inputEmail" name="email" class="form-control" placeholder="{$EMAIL}">
                </div>

                {if isset($NICKNAME)}
                    <div class="form-group">
                        <label for="inputNickname">{$NICKNAME}</label>
                        <input type="text" id="inputNickname" name="nickname" class="form-control" placeholder="{$NICKNAME}">
                    </div>
                {/if}

                <div class="form-group">
                    <input type="hidden" name="token" value="{$TOKEN}">
                    <input type="submit" value="{$SUBMIT}" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>
</div>

{include file='footer.tpl'}