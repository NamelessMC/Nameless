{include file='navbar.tpl'}

<div class="container">
  <div class="row">
	<div class="col-md-3">
	  {include file='mod/navigation.tpl'}
	</div>
	<div class="col-md-9">
	  <div class="card">
		<div class="card-body">
		  <h2 class="card-title">{$IP_LOOKUP}</h2>
		  
		  {if not isset($SEARCH)}
		  
		    {if isset($IP_SEARCH)}
			  {* List IPs for user *}
			  
		      {if isset($NO_ACCOUNTS)}
		        {* No accounts found *}
			    <strong>{$NO_ACCOUNTS}</strong>
		      {else}
		        {* Accounts found *}
			    <strong>{$COUNT_ACCOUNTS}</strong>
			
			    <ul>
			    {foreach from=$ACCOUNTS item=account}
			      <li><a href="{$account.account_ips}">{$account.username}</a></li>
			    {/foreach}
			    </ul>
		      {/if}
			  
			{else}
			  {* List IPs for user *}

		      {if isset($NO_ACCOUNTS)}
		        {* No accounts found *}
			    <strong>{$NO_ACCOUNTS}</strong>
		      {else}
		        {* Accounts found *}
			    <strong>{$COUNT_ACCOUNTS}</strong>
			
			    <ul>
			    {foreach from=$ACCOUNTS item=account}
			      <li><a href="{$account.link}">{$account.ip}</a></li>
			    {/foreach}
			    </ul>
		      {/if}
			  
			{/if}
			
		  {else}
		  
		  {if isset($ERROR)}
			<div class="alert alert-danger">{$ERROR}</div>
		  {/if}
		  <form action="" method="post">
			<div class="form-group">
			  <label for="InputIP">{$SEARCH}</label>
			  <input type="text" class="form-control" id="InputIP" name="search">
			</div>
			<div class="form-group">
			  <input type="hidden" name="token" value="{$TOKEN}">
			  <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
			</div>
		  </form>
		  
		  {/if}
		  
		</div>
	  </div>
	</div>
  </div>
</div>

{include file='footer.tpl'}