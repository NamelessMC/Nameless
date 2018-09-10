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
			{$AUTHME_INFO}
		</div>

		<div class="form-group">
			<label for="inputUsername">{$USERNAME}</label>
			<input type="text" id="inputUsername" name="username" class="form-control" placeholder="{$USERNAME}">
		</div>

		<div class="form-group">
			<label for="inputPassword">{$PASSWORD}</label>
			<input type="password" id="inputPassword" name="password" class="form-control" placeholder="{$PASSWORD}">
		</div>

        {if isset($RECAPTCHA)}
        <div class="form-group">
            <center>
              <div class="g-recaptcha" data-sitekey="{$RECAPTCHA}"></div>
            </center>
        </div>
        {/if}

        <hr />
        {$AGREE_TO_TERMS}
        <br />
		<span class="button-checkbox">
		  <button type="button" class="btn" data-color="info" tabindex="7"> {$I_AGREE}</button>
		  <input type="checkbox" name="t_and_c" id="t_and_c" style="display:none;" value="1">
		</span>


        <br />

	    <input type="hidden" name="token" value="{$TOKEN}">
	    <br />
	    <input type="submit" value="{$SUBMIT}" class="btn btn-primary">
	  </form>
	</div>
  </div>
</div>

{include file='footer.tpl'}