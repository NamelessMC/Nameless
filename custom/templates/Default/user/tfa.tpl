{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="container">
  <div class="card">
    <div class="card-body">
	
	  <h2>{$TWO_FACTOR_AUTH}</h2>
	  
	  {if isset($TFA_SCAN_CODE_TEXT)}
	  
	  <p>{$TFA_SCAN_CODE_TEXT}</p>
	  <img src="{$IMG_SRC}">
	  <hr />
	  <p>{$TFA_CODE_TEXT}</p>
	  <br />
	  <strong>{$TFA_CODE}</strong>
	  
	  <hr />
	  
	  <a href="{$LINK}" class="btn btn-primary">{$NEXT}</a>
	  <a href="{$CANCEL_LINK}" class="btn btn-danger">{$CANCEL}</a>
	  
	  {else}
	  
	  {if isset($ERROR)}
	  <div class="alert alert-danger">
	    {$ERROR}
	  </div>
	  {/if}
	  {if isset($ERRORS)}
	  <div class="alert alert-danger">
		{foreach from=$ERRORS item=error}
			{$error}
		{/foreach}
	  </div>
	  {/if}
	  
	  <p>{$TFA_ENTER_CODE}</p>
	  
	  <form action="" method="post">
	    <div class="form-group">
		  <input type="text" class="form-control" name="tfa_code">
		</div>
		<div class="form-group">
		  <input type="hidden" name="token" value="{$TOKEN}">
		  <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
		  <a href="{$CANCEL_LINK}" class="btn btn-danger">{$CANCEL}</a>
		</div>
	  </form>
	  
	  {/if}
	</div>
  </div>
</div>

{include file='footer.tpl'}