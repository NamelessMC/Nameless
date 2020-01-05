{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
  {$TITLE}
</h2>

{if !empty($SUCCESS)}
  <div class="ui success icon message">
    <i class="check icon"></i>
    <div class="content">
      <div class="header">{$SUCCESS_TITLE}</div>
      {$SUCCESS}
    </div>
  </div>
{/if}

{if !empty($ERROR)}
  <div class="ui error icon message">
    <i class="x icon"></i>
    <div class="content">
      <div class="header">{$ERROR_TITLE}</div>
      {$ERROR}
    </div>
  </div>
{/if}

<div class="ui stackable grid" id="user-settings">
  <div class="ui centered row">
    <div class="ui six wide tablet four wide computer column">
	  {include file='user/navigation.tpl'}
    </div>
    <div class="ui ten wide tablet twelve wide computer column">
      <div class="ui segment">
        <h3 class="ui header">{$SETTINGS}</h3>
        <form class="ui form" action="" method="post" id="form-user-settings">
          {nocache}
            {foreach from=$PROFILE_FIELDS item=field}
              <div class="field">
                {if !isset($field.disabled)}
                  <label for="input{$field.id}">{$field.name}</label>
                  {if $field.type == "text"}
                    <input type="text" name="{$field.id}" id="input{$field.id}" value="{$field.value}" placeholder="{$field.name}">
                  {elseif $field.type == "textarea"}
                    <textarea name="{$field.id}" id="input{$field.id}">{$field.value}</textarea>
                  {elseif $field.type == "date"}
                    <input type="text" name="{$field.id}" id="input{$field.id}" value="{$field.value}">
                  {/if}
                {/if}
              </div>
            {/foreach}
            {if isset($PRIVATE_PROFILE)}
              <div class="field">
                <label for="inputPrivateProfile">{$PRIVATE_PROFILE}</label>
                <select class="ui fluid dropdown" name="privateProfile" id="inputPrivateProfile">
                  <option value="1"{if ($PRIVATE_PROFILE_ENABLED == true)} selected {/if}>{$ENABLED}</option>
                  <option value="0"{if ($PRIVATE_PROFILE_ENABLED == false)} selected {/if}>{$DISABLED}</option>
                </select>
              </div>
            {/if}
            <div class="field">
              <label for="inputLanguage">{$ACTIVE_LANGUAGE}</label>
              <select class="ui fluid dropdown" name="language" id="inputLanguage">
                {foreach from=$LANGUAGES item=language}
                  <option value="{$language.name}"{if $language.active == true} selected{/if}>{$language.name}</option>
                 {/foreach}
              </select>
            </div>
            {if count($TEMPLATES)}
              <div class="field">
                <label for="inputTemplate">{$ACTIVE_TEMPLATE}</label>
                <select class="ui fluid dropdown" name="template" id="inputTemplate">
                  {foreach from=$TEMPLATES item=template}
                    <option value="{$template.id}"{if $template.active == true} selected{/if}>{$template.name}</option>
                  {/foreach}
                </select>
              </div>
            {/if}
            <div class="field">
              <label for="inputTimezone">{$TIMEZONE}</label>
              <select class="ui fluid dropdown" name="timezone" id="inputTimezone">
                {foreach from=$TIMEZONES key=KEY item=ITEM}
                  <option value="{$KEY}"{if $SELECTED_TIMEZONE eq $KEY} selected{/if}>
                    ({$ITEM.offset}) {$ITEM.name} &middot; ({$ITEM.time})
                  </option>
                {/foreach}
              </select>
            </div>
            {if isset($SIGNATURE)}
              <div class="field">
                <label for="inputSignature">{$SIGNATURE}</label>
                {if !isset($MARKDOWN)}
                  <textarea name="signature" id="inputSignature">{$SIGNATURE_VALUE}</textarea>
                {else}
                  <textarea name="signature" id="inputSignature">{$SIGNATURE_VALUE}</textarea>
                {/if}
              </div>
            {/if}
          {/nocache}
          <input type="hidden" name="action" value="settings">
          <input type="hidden" name="token" value="{$TOKEN}">
          <input type="submit" class="ui primary button" value="{$SUBMIT}">
        </form>
      </div>
      <div class="ui segment">
        <h3 class="ui header">{$CHANGE_EMAIL_ADDRESS}</h3>
        <form class="ui form" action="" method="post" id="form-user-email">
          <div class="field">
            <label for="inputPassword">{$CURRENT_PASSWORD}</label>
            <input type="password" name="password" id="inputPassword">
          </div>
          <div class="field">
            <label for="inputEmail">{$EMAIL_ADDRESS}</label>
            <input type="email" name="email" id="inputEmail" value="{$CURRENT_EMAIL}">
          </div>
          <input type="hidden" name="action" value="email">
          <input type="hidden" name="token" value="{$TOKEN}">
          <input type="submit" value="{$SUBMIT}" class="ui primary button">
        </form>
      </div>
      <div class="ui segment">
        <h3 class="ui header">{$CHANGE_PASSWORD}</h3>
        <form class="ui form" action="" method="post" id="form-user-password">
          <div class="field">
            <label for="inputOldPassword">{$CURRENT_PASSWORD}</label>
            <input type="password" name="old_password" id="inputOldPassword" >
          </div>
          <div class="field">
            <label for="inputNewPassword">{$NEW_PASSWORD}</label>
            <input type="password" name="new_password" id="inputNewPassword">
          </div>
          <div class="field">
            <label for="inputNewPasswordAgain">{$CONFIRM_NEW_PASSWORD}</label>
            <input type="password" name="new_password_again" id="inputNewPasswordAgain">
          </div>
          <input type="hidden" name="action" value="password">
          <input type="hidden" name="token" value="{$TOKEN}">
          <input type="submit" value="{$SUBMIT}" class="ui primary button">
        </form>
      </div>
      <div class="ui segment">
        <h3 class="ui header">{$TWO_FACTOR_AUTH}</h3>
        {if isset($ENABLE)}
          <a class="ui positive button" href="{$ENABLE_LINK}">{$ENABLE}</a>
         {else}
           <a class="ui negative button" href="{$DISABLE_LINK}">{$DISABLE}</a>
         {/if}
      </div>
      {if isset($CUSTOM_AVATARS)}
        <div class="ui segment">
          <h3 class="ui header">{$UPLOAD_NEW_PROFILE_IMAGE}</h3>
          <form class="ui form" action="{$CUSTOM_AVATARS_SCRIPT}" method="post" enctype="multipart/form-data" id="form-user-avatar">
            <div class="field">
              <label class="ui default button" display="inline block">
                {$BROWSE}
                <input type="file" name="file" hidden/>
              </label>
            </div>
            <input type="hidden" name="type" value="avatar">
            <input type="hidden" name="token" value="{$TOKEN}">
            <input type="submit" class="ui primary button" value="{$SUBMIT}">
          </form>
        </div>
      {/if}
    </div>
  </div>
</div>

{include file='footer.tpl'}