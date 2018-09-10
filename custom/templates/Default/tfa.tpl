{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="container">
  <div class="card">
    <div class="card-body">
	  <form action="" method="post">
	    <h2>{$TWO_FACTOR_AUTH}</h2>
		
		{if isset($ERROR)}
		  <div class="alert alert-danger">
		    {$ERROR}
		  </div>
		{/if}
		
	    <p>{$TFA_ENTER_CODE}</p>
	    <input type="text" class="form-control" name="tfa_code">
	    <input type="hidden" name="tfa" value="true">
	    <input type="hidden" name="token" value="{$TOKEN}">
	    <br />
	    <input type="submit" value="{$SUBMIT}" class="btn btn-primary">
	  </form>
	</div>
  </div>
</div>

{include file='footer.tpl'}