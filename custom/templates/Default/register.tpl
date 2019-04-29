{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="container">
  <div class="row">
	<div class="col-xs-12 col-sm-8 col-md-6 offset-sm-2 offset-md-3">
	  <div class="card">
		<div class="card-body">
		  <form action="" method="post">
			<h2>{$CREATE_AN_ACCOUNT}</h2>
			{if isset($REGISTRATION_ERROR)}
			  <div class="alert alert-danger">
			  {foreach from=$REGISTRATION_ERROR item=error}
			    {$error}<br />
			  {/foreach}
			  </div>
			{/if}

			<hr class="colorgraph" />
			
			{if isset($NICKNAMES)}
			  {* Custom usernames are enabled *}
			  <div class="form-group">
				<input type="text" name="{if isset($MINECRAFT)}nickname{else}username{/if}" id="username" autocomplete="off" class="form-control form-control-lg" placeholder="{$NICKNAME}" tabindex="1">
			  </div>
			  
			  {if isset($MINECRAFT)}
				<div class="form-group">
				  <input type="text" name="username" id="mcname" autocomplete="off" class="form-control form-control-lg" placeholder="{$MINECRAFT_USERNAME}" tabindex="2">
				</div>
			  {/if}
			{else}
			  {if isset($MINECRAFT)}
			    {* Minecraft username required *}
				<div class="form-group">
				  <input type="text" name="username" id="mcname" autocomplete="off" class="form-control form-control-lg" placeholder="{$MINECRAFT_USERNAME}" tabindex="1">
				</div>
			  {else}
			    {* Minecraft integration disabled, just ask for a username *}
				<div class="form-group">
				  <input type="text" name="username" id="mcname" autocomplete="off" class="form-control form-control-lg" placeholder="{$NICKNAME}" tabindex="2">
				</div>
			  {/if}
			{/if}
			
			<div class="form-group">
			  <input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="{$EMAIL}" tabindex="3">
			</div>
			
			<div class="row">
			  <div class="col-xs-12 col-sm-6 col-md-6">
				<div class="form-group">
				  <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="{$PASSWORD}" tabindex="4">
				</div>
			  </div>
			  <div class="col-xs-12 col-sm-6 col-md-6">
				<div class="form-group">
				  <input type="password" name="password_again" id="password_again" class="form-control form-control-lg" placeholder="{$CONFIRM_PASSWORD}" tabindex="5">
				</div>
			  </div>
			</div>
			
			{if isset($RECAPTCHA)}
			<div class="form-group">
			  <center>
				<div class="g-recaptcha" data-sitekey="{$RECAPTCHA}"></div>
			  </center>
			</div>
			{/if}
			
			<div class="row">
			  <div class="col-xs-4 col-sm-3 col-md-3">
				<span class="button-checkbox">
				  <button type="button" class="btn" data-color="info" tabindex="7"> {$I_AGREE}</button>
				  <input type="checkbox" name="t_and_c" id="t_and_c" style="display:none;" value="1">
				</span>
			  </div>
			  <div class="col-xs-8 col-sm-9 col-md-9">
			    {$AGREE_TO_TERMS}
			  </div>
			</div>
			
			<hr class="colorgraph" />
			
			<div class="row">
			  <input type="hidden" name="token" value="{$TOKEN}">
			  <div class="col-xs-12 col-md-6"><input type="submit" value="{$REGISTER}" class="btn btn-primary btn-block btn-lg" tabindex="8"></div>
			  <div class="col-xs-12 col-md-6"><a href="{$LOGIN_URL}" class="btn btn-success btn-block btn-lg">{$LOG_IN}</a></div>
			</div>
		  </form>
		</div>
	  </div>
    </div>
  </div>
</div>

{include file='footer.tpl'}