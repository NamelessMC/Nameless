{include file='navbar.tpl'}

<div class="container" style="padding-top: 5rem;">
  <div class="row">
	<div class="col-xs-12 col-sm-8 col-md-6 offset-sm-2 offset-md-3">
	  <div class="card">
	    <div class="card-block">
		  <form role="form" action="" method="post">
			<h2>{$SIGN_IN}</h2>
		
			{if count($ERROR)}
			<div class="alert alert-danger" role="alert">
			  {foreach from=$ERROR item=item name=err}
				{$item}
				{if not $smarty.foreach.err.last}<br />{/if}
			  {/foreach}
			</div>
			{/if}

			<hr class="colorgraph">
			
			<div class="form-group">
			  <input type="text" class="form-control form-control-lg" name="username" id="username" autocomplete="off" value="{$USERNAME_INPUT}" placeholder="{$USERNAME}" tabindex="1">
			</div>
			
			<div class="form-group">
			  <input type="password" class="form-control form-control-lg" name="password" id="password" autocomplete="off" placeholder="{$PASSWORD}" tabindex="2">
			</div>
			
			<div class="form-group">
			  <div class="row">
				<div class="col-xs-12 col-md-6">
				  <div class="form-group">
					<span class="button-checkbox">
						<button type="button" class="btn" data-color="info" tabindex="7"> {$REMEMBER_ME}</button>
						<input type="checkbox" name="remember" id="remember" style="display:none;" value="1">
					</span>				
				  </div>
				</div>
				<div class="col-xs-12 col-md-6">
				  <span class="pull-right">
					<a class="btn btn-warning" href="{$FORGOT_PASSWORD_URL}">{$FORGOT_PASSWORD}</a>
				  </span>
				</div>
			  </div>
			</div>
			
			<input type="hidden" name="token" value="{$FORM_TOKEN}">
			
			<hr class="colorgraph">
			<div class="row">
			  <div class="col-xs-12 col-md-6">
				<input type="submit" class="btn btn-primary btn-block btn-lg" value="{$SUBMIT}">
			  </div>
			  <div class="col-xs-12 col-md-6">
				<a class="btn btn-success btn-block btn-lg" href="{$REGISTER_URL}">{$REGISTER}</a>
			  </div>
			</div>
		  </form>
	    </div>
	  </div>
	</div>
  </div>
</div>

<br />

{include file='footer.tpl'}