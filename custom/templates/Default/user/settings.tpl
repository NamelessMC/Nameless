{include file='navbar.tpl'}

<div class="container" style="padding-top: 5rem;">
  <div class="row">
	<div class="col-md-3">
	  {include file='user/navigation.tpl'}
	</div>
	<div class="col-md-9">
	  <div class="card">
		<div class="card-block">
		  <h2 class="card-title">{$SETTINGS}</h2>
		  
		  {if $ERROR}
		  <div class="alert alert-danger">
		    {$ERROR}
		  </div>
		  {/if}
		  
		  {if $SUCCESS}
		  <div class="alert alert-success">
		    {$SUCCESS}
		  </div>
		  {/if}
		  
		  <form action="" method="post">
		  {nocache}
		  
		    <div class="form-group">
			  <label for="inputLanguage">{$ACTIVE_LANGUAGE}</label>
			  <select name="language" id="inputLanguage" class="form-control">
			    {foreach from=$LANGUAGES item=language}
				<option value="{$language.name}"{if $language.active == true} selected{/if}>{$language.name}</option>
				{/foreach}
			  </select>
			</div>

		    {foreach from=$PROFILE_FIELDS item=field}
		    <div class="form-group">
			
			  {if !isset($field.disabled)}
			  <label for="input{$field.id}">{$field.name}</label>
			
			  {if $field.type == "text"}
		      <input type="text" class="form-control" name="{$field.id}" id="input{$field.id}" value="{$field.value}" placeholder="{$field.name}">
			
			  {else if $field.type == "textarea"}
			  <textarea class="form-control" name="{$field.id}" id="input{$field.id}">{$field.value}</textarea>
			
			  {else if $field.type == "date"}
			  <input name="{$field.id}" id="input{$field.id}" value="{$field.value}" type="text" class="form-control datepicker">
			
			  {/if}

			  {/if}
		    </div>
			{/foreach}
		  {/nocache}
		  
		    <div class="form-group">
			  <input type="hidden" name="action" value="settings">
			  <input type="hidden" name="token" value="{$TOKEN}">
			  <input type="submit" value="{$SUBMIT}" class="btn btn-primary">
		    </div>
		  </form>
		  
		  <hr />
		  
		  {nocache}
		  <h4>{$CHANGE_PASSWORD}</h4>
		  <form action="" method="post">
		    <div class="form-group">
			  <label for="inputOldPassword">{$CURRENT_PASSWORD}</label>
			  <input type="password" name="old_password" id="inputOldPassword" class="form-control">
			</div>
			
		    <div class="form-group">
			  <label for="inputNewPassword">{$NEW_PASSWORD}</label>
			  <input type="password" name="new_password" id="inputNewPassword" class="form-control">
			</div>
			
		    <div class="form-group">
			  <label for="inputNewPasswordAgain">{$CONFIRM_NEW_PASSWORD}</label>
			  <input type="password" name="new_password_again" id="inputNewPasswordAgain" class="form-control">
			</div>
			
			<div class="form-group">
			  <input type="hidden" name="action" value="password">
			  <input type="hidden" name="token" value="{$TOKEN}">
			  <input type="submit" value="{$SUBMIT}" class="btn btn-primary">
			</div>
		  </form>
		  {/nocache}
		  
		  <hr />
		  
		  <h4>{$TWO_FACTOR_AUTH}</h4>
		  {nocache}
		    {if isset($ENABLE)}
		  <a href="{$ENABLE_LINK}" class="btn btn-success">{$ENABLE}</a>
		    {else}
		  <a href="{$DISABLE_LINK}" class="btn btn-danger">{$DISABLE}</a>
			{/if}
		  {/nocache}
		</div>
	  </div>
	</div>
  </div>
</div>

{include file='footer.tpl'}