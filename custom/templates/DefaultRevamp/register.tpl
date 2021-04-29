{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
  {$CREATE_AN_ACCOUNT}
</h2>

{if isset($REGISTRATION_ERROR)}
  <div class="ui error icon message">
    <i class="x icon"></i>
    <div class="content">
      <div class="header">{$ERROR_TITLE}</div>
      <ul class="list">
        {foreach from=$REGISTRATION_ERROR item=error}
          <li>{$error}</li>
        {/foreach}
      </ul>
    </div>
  </div>
{/if}

<div class="ui padded segment" id="register">
  <div class="ui stackable grid">
    <div class="ui centered row">
      <div class="ui sixteen wide tablet ten wide computer column">
        <form class="ui form" action="" method="post" id="form-register">
          {if isset($NICKNAMES)}
            <div class="field">
              <label>{$NICKNAME}</label>
              <input type="text" name="{if isset($MINECRAFT)}nickname{else}username{/if}" id="username" value="{if isset($MINECRAFT)}{$NICKNAME_VALUE}{else}{$USERNAME_VALUE}{/if}" placeholder="{$NICKNAME}" tabindex="1">
            </div>
            {if isset($MINECRAFT)}
              <div class="field">
                <label>{$MINECRAFT_USERNAME}</label>
                <input type="text" name="username" id="mcname" value="{$USERNAME_VALUE}" placeholder="{$MINECRAFT_USERNAME}" tabindex="2">
              </div>
            {/if}
          {else}
            {if isset($MINECRAFT)}
              <div class="field">
                <label>{$MINECRAFT_USERNAME}</label>
                <input type="text" name="username" id="mcname" value="{$USERNAME_VALUE}" placeholder="{$MINECRAFT_USERNAME}" tabindex="1">
              </div>
            {else}
              <div class="field">
                <label>{$USERNAME}</label>
                <input type="text" name="username" id="mcname" value="{$USERNAME_VALUE}" placeholder="{$NICKNAME}" tabindex="1">
              </div>
            {/if}
          {/if}
          <div class="field">
            <label>{$EMAIL}</label>
            <input type="email" name="email" id="email" value="{$EMAIL_VALUE}" placeholder="{$EMAIL}" tabindex="3">
          </div>
          <div class="field">
            <label>{$PASSWORD}</label>
            <input type="password" name="password" id="password" placeholder="{$PASSWORD}" tabindex="4">
          </div>
          <div class="field">
            <label>{$CONFIRM_PASSWORD}</label>
            <input type="password" name="password_again" id="password_again" placeholder="{$CONFIRM_PASSWORD}" tabindex="5">
          </div>
          {if count($CUSTOM_FIELDS)}
            {foreach $CUSTOM_FIELDS as $field}
                <div class="field">
                <label>{$field.name}</label>
                    {if $field.type eq 1}
                    <input type="text" name="{$field.name}" id="{$field.name}" value="{$field.value}" placeholder="{$field.name}" tabindex="5">
                    {elseif $field.type eq 2}
                    <textarea name="{$field.name}" id="{$field.name}" placeholder="{$field.description}" tabindex="5"></textarea>
                    {elseif $field.type eq 3}
                    <input type="date" name="{$field.name}" id="{$field.name}" value="{$field.value}" tabindex="5">
                    {/if}
                </div>
            {/foreach}
          {/if}
          {if $CAPTCHA}
            <div class="field">
              {$CAPTCHA}
            </div>
          {/if}
          <div class="inline field">
            <div class="ui checkbox">
              <input type="checkbox" name="t_and_c" id="t_and_c" value="1" tabindex="7">
              <label for="t_and_c">{$AGREE_TO_TERMS}</label>
            </div>
          </div>
          <input type="hidden" name="token" value="{$TOKEN}">
          <input id="timezone" type="hidden" name="timezone" value=''>
          <input type="submit" class="ui primary button" value="{$REGISTER}" tabindex="8">
        </form>
        <div class="ui horizontal divider">{$ALREADY_REGISTERED}</div>
        <div class="ui center aligned">
          <a class="ui large positive button" href="{$LOGIN_URL}">{$LOG_IN}</a>
        </div>
      </div>
    </div>
  </div>
</div>

{include file='footer.tpl'}
