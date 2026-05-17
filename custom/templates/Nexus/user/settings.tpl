{include file='header.tpl'}
{include file='navbar.tpl'}

<h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight mb-6">{$TITLE}</h1>

{if !empty($SUCCESS)}
  <div class="nx-alert nx-alert--success mb-4">
    {include file='components/icon.tpl' name='check' size=18 class="nx-alert__icon"}
    <div><div class="nx-alert__title">{$SUCCESS_TITLE}</div><div class="nx-alert__body">{$SUCCESS}</div></div>
  </div>
{/if}
{if (isset($ERRORS) || isset($ERROR))}
  <div class="nx-alert nx-alert--danger mb-4">
    {include file='components/icon.tpl' name='alert' size=18 class="nx-alert__icon"}
    <div class="flex-1">
      <ul class="nx-alert__body list-disc pl-4 space-y-0.5">
        {foreach from=$ERRORS item=error}<li>{$error}</li>{/foreach}
        {if isset($ERROR)}<li>{$ERROR}</li>{/if}
      </ul>
    </div>
  </div>
{/if}

<div class="grid lg:grid-cols-[18rem_minmax(0,1fr)] gap-6" id="user-settings">
  <aside>{include file='user/navigation.tpl'}</aside>

  <main class="min-w-0 space-y-4">

    <section class="nx-surface p-6">
      <h2 class="font-display text-lg font-semibold mb-4">{$SETTINGS}</h2>
      <form class="space-y-4" action="" method="post" id="form-user-settings">
        {nocache}
        {foreach from=$PROFILE_FIELDS key=name item=field}
          {if !isset($field.disabled)}
            <div class="nx-field">
              <label class="nx-label" for="input{$field.id}">{$field.name}{if $field.required}<span class="nx-required">*</span>{/if}</label>
              {if $field.type == "text"}
                <input type="text" name="{if $name == 'nickname'}nickname{else}profile_fields[{$field.id}]{/if}" id="input{$field.id}" value="{$field.value}" placeholder="{$field.description}" class="nx-input" />
              {elseif $field.type == "textarea"}
                <textarea name="profile_fields[{$field.id}]" id="input{$field.id}" placeholder="{$field.description}" class="nx-textarea">{$field.value}</textarea>
              {elseif $field.type == "date"}
                <input type="date" name="profile_fields[{$field.id}]" id="input{$field.id}" value="{$field.value}" class="nx-input" />
              {/if}
            </div>
          {/if}
        {/foreach}

        {if isset($TOPIC_UPDATES)}
          <div class="nx-field"><label class="nx-label" for="inputTopicUpdates">{$TOPIC_UPDATES}</label>
            <select class="nx-select" name="topicUpdates" id="inputTopicUpdates">
              <option value="1" {if $TOPIC_UPDATES_ENABLED==true}selected{/if}>{$ENABLED}</option>
              <option value="0" {if $TOPIC_UPDATES_ENABLED==false}selected{/if}>{$DISABLED}</option>
            </select></div>
        {/if}
        {if isset($AUTHME_SYNC_PASSWORD)}
          <div class="nx-field"><label class="nx-label" for="inputAuthmeSync">{$AUTHME_SYNC_PASSWORD} <span class="text-text-muted ml-1" title="{$AUTHME_SYNC_PASSWORD_INFO}">ⓘ</span></label>
            <select class="nx-select" name="authmeSync" id="inputAuthmeSync">
              <option value="1" {if $AUTHME_SYNC_PASSWORD_ENABLED==true}selected{/if}>{$ENABLED}</option>
              <option value="0" {if $AUTHME_SYNC_PASSWORD_ENABLED==false}selected{/if}>{$DISABLED}</option>
            </select></div>
        {/if}
        {if isset($PRIVATE_PROFILE)}
          <div class="nx-field"><label class="nx-label" for="inputPrivateProfile">{$PRIVATE_PROFILE}</label>
            <select class="nx-select" name="privateProfile" id="inputPrivateProfile">
              <option value="1" {if $PRIVATE_PROFILE_ENABLED==true}selected{/if}>{$ENABLED}</option>
              <option value="0" {if $PRIVATE_PROFILE_ENABLED==false}selected{/if}>{$DISABLED}</option>
            </select></div>
        {/if}
        {if isset($CUSTOM_AVATARS)}
          <div class="nx-field"><label class="nx-label" for="inputGravatar">{$GRAVATAR}</label>
            <select class="nx-select" name="gravatar" id="inputGravatar">
              <option value="0" {if $GRAVATAR_VALUE=='0'}selected{/if}>{$DISABLED}</option>
              <option value="1" {if $GRAVATAR_VALUE=='1'}selected{/if}>{$ENABLED}</option>
            </select></div>
        {/if}
        <div class="nx-field"><label class="nx-label" for="inputLanguage">{$ACTIVE_LANGUAGE}</label>
          <select class="nx-select" name="language" id="inputLanguage">
            {foreach from=$LANGUAGES item=language}<option value="{$language.name}" {if $language.active}selected{/if}>{$language.name}</option>{/foreach}
          </select></div>
        {if count($TEMPLATES) > 2}
          <div class="nx-field"><label class="nx-label" for="inputTemplate">{$ACTIVE_TEMPLATE}</label>
            <select class="nx-select" name="template" id="inputTemplate">
              {foreach from=$TEMPLATES item=template}<option value="{$template.id}" {if $template.active==true}selected{/if}>{$template.name}</option>{/foreach}
            </select></div>
        {/if}
        <div class="nx-field"><label class="nx-label" for="inputTimezone">{$TIMEZONE}</label>
          <select class="nx-select" name="timezone" id="inputTimezone">
            {foreach from=$TIMEZONES key=KEY item=ITEM}<option value="{$KEY}" {if $SELECTED_TIMEZONE eq $KEY}selected{/if}>({$ITEM.offset}) {$ITEM.name} &middot; ({$ITEM.time})</option>{/foreach}
          </select></div>
        {if isset($SIGNATURE)}
          <div class="nx-field"><label class="nx-label" for="inputSignature">{$SIGNATURE}</label>
            <textarea name="signature" id="inputSignature" class="nx-textarea">{$SIGNATURE_VALUE}</textarea></div>
        {/if}
        {/nocache}
        <input type="hidden" name="action" value="settings">
        <input type="hidden" name="token" value="{$TOKEN}">
        <button type="submit" class="nx-btn nx-btn--primary">{$SUBMIT}</button>
      </form>
    </section>

    <section class="nx-surface p-6">
      <h2 class="font-display text-lg font-semibold mb-4">{$CHANGE_EMAIL_ADDRESS}</h2>
      <form class="space-y-4" action="" method="post" id="form-user-email">
        <div class="nx-field"><label class="nx-label" for="inputPassword">{$CURRENT_PASSWORD}</label>
          <input type="password" name="password" id="inputPassword" class="nx-input" autocomplete="current-password" /></div>
        <div class="nx-field"><label class="nx-label" for="inputEmail">{$EMAIL_ADDRESS}</label>
          <input type="email" name="email" id="inputEmail" value="{$CURRENT_EMAIL}" class="nx-input" autocomplete="email" /></div>
        <input type="hidden" name="action" value="email">
        <input type="hidden" name="token" value="{$TOKEN}">
        <button type="submit" class="nx-btn nx-btn--primary">{$SUBMIT}</button>
      </form>
    </section>

    <section class="nx-surface p-6">
      <h2 class="font-display text-lg font-semibold mb-4">{$CHANGE_PASSWORD}</h2>
      <form class="space-y-4" action="" method="post" id="form-user-password">
        <div class="nx-field"><label class="nx-label" for="inputOldPassword">{$CURRENT_PASSWORD}</label>
          <input type="password" name="old_password" id="inputOldPassword" class="nx-input" autocomplete="current-password" /></div>
        <div class="nx-field"><label class="nx-label" for="inputNewPassword">{$NEW_PASSWORD}</label>
          <input type="password" name="new_password" id="inputNewPassword" class="nx-input" autocomplete="new-password" /></div>
        <div class="nx-field"><label class="nx-label" for="inputNewPasswordAgain">{$CONFIRM_NEW_PASSWORD}</label>
          <input type="password" name="new_password_again" id="inputNewPasswordAgain" class="nx-input" autocomplete="new-password" /></div>
        <input type="hidden" name="action" value="password">
        <input type="hidden" name="token" value="{$TOKEN}">
        <button type="submit" class="nx-btn nx-btn--primary">{$SUBMIT}</button>
      </form>
    </section>

    <section class="nx-surface p-6">
      <h2 class="font-display text-lg font-semibold mb-4 flex items-center gap-2">
        {include file='components/icon.tpl' name='shield' size=18 class="text-accent"}{$TWO_FACTOR_AUTH}
      </h2>
      {if isset($ENABLE)}
        <a class="nx-btn nx-btn--success" href="{$ENABLE_LINK}">{$ENABLE}</a>
      {elseif isset($FORCED)}
        <button class="nx-btn nx-btn--danger" disabled>{$DISABLE}</button>
      {else}
        <form action="{$DISABLE_LINK}" method="post">
          <input type="hidden" name="token" value="{$TOKEN}">
          <button type="submit" class="nx-btn nx-btn--danger">{$DISABLE}</button>
        </form>
      {/if}
    </section>

    {if isset($CUSTOM_AVATARS)}
      <section class="nx-surface p-6">
        <h2 class="font-display text-lg font-semibold mb-4">{$UPLOAD_NEW_PROFILE_IMAGE}</h2>
        <form class="space-y-4" action="{$CUSTOM_AVATARS_SCRIPT}" method="post" enctype="multipart/form-data" id="form-user-avatar">
          <label class="nx-btn nx-btn--outline cursor-pointer">
            {include file='components/icon.tpl' name='arrow-up' size=14}{$BROWSE}
            <input type="file" name="file" hidden />
          </label>
          <input type="hidden" name="type" value="avatar">
          <input type="hidden" name="token" value="{$TOKEN}">
          <button type="submit" class="nx-btn nx-btn--primary">{$SUBMIT}</button>
        </form>
      </section>
      {if $HAS_CUSTOM_AVATAR}
        <section class="nx-surface p-6">
          <h2 class="font-display text-lg font-semibold mb-4">{$REMOVE_AVATAR}</h2>
          <form action="" method="post" id="form-reset-avatar">
            <input type="hidden" name="action" value="reset_avatar">
            <input type="hidden" name="token" value="{$TOKEN}">
            <button type="submit" class="nx-btn nx-btn--danger">{$REMOVE}</button>
          </form>
        </section>
      {/if}
    {/if}
  </main>
</div>

{include file='footer.tpl'}
