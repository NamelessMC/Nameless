{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
    {$CREATE_AN_ACCOUNT}
    {if $OAUTH_FLOW}
    <div class="sub header">
        {$OAUTH_MESSAGE_CONTINUE}
    </div>
    {/if}
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

                    {assign var=counter value=1}
                    {foreach $FIELDS as $field_key => $field}
                    <div class="field">
                        <label>{$field.name}</label>
                        {if $field.type eq 1}
                        <input type="text" name="{$field_key}" id="{$field_key}" value="{$field.value}"
                            placeholder="{$field.placeholder}" tabindex="{$counter++}" {if $field.required}
                            required{/if}>
                        {else if $field.type eq 2}
                        <textarea name="{$field_key}" id="{$field_key}" placeholder="{$field.placeholder}"
                            tabindex="{$counter++}"></textarea>
                        {else if $field.type eq 3}
                        <input type="date" name="{$field_key}" id="{$field_key}" value="{$field.value}"
                            tabindex="{$counter++}">
                        {else if $field.type eq 4}
                        <input type="password" name="{$field_key}" id="{$field_key}" value="{$field.value}"
                            placeholder="{$field.placeholder}" tabindex="{$counter++}" {if $field.required}
                            required{/if}>
                        {else if $field.type eq 5}
                        <select class="ui fluid dropdown" name="{$field_key}" id="{$field_key}" {if
                            $field.required}required{/if}>
                            {foreach from=$field.options item=option}
                            <option value="{$option.value}" {if $option.value eq $field.value} selected{/if}>
                                {$option.option}</option>
                            {/foreach}
                        </select>
                        {else if $field.type eq 6}
                        <input type="number" name="{$field_key}" id="{$field_key}" value="{$field.value}"
                            placeholder="{$field.name}" tabindex="{$counter++}" {if $field.required} required{/if}>
                        {else if $field.type eq 7}
                        <input type="email" name="{$field_key}" id="{$field_key}" value="{$field.value}"
                            placeholder="{$field.placeholder}" tabindex="{$counter++}" {if $field.required}
                            required{/if}>
                        {else if $field.type eq 8}
                        {foreach from=$field.options item=option}
                        <div class="field">
                            <div class="ui radio checkbox" tabindex="{$counter++}">
                                <input type="radio" name="{$field_key}" value="{$option.value}" {if $field.value eq
                                    $option.value}checked{/if} {if $field.required}required{/if}>
                                <label>{$option.option}</label>
                            </div>
                        </div>
                        {/foreach}
                        {else if $field.type eq 9}
                        {foreach from=$field.options item=option}
                        <div class="field">
                            <div class="ui checkbox">
                                <input type="checkbox" name="{$field_key}[]" value="{$option.value}" {if
                                    is_array($field.value) && in_array($option.value, $field.value)}checked{/if}
                                    tabindex="{$counter++}">
                                <label>{$option.option}</label>
                            </div>
                        </div>
                        {/foreach}
                        {/if}
                    </div>
                    {/foreach}

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
                    <input id="timezone" type="hidden" name="timezone" value="">
                    <input type="submit" class="ui primary button" value="{$REGISTER}" tabindex="8">
                    {if $OAUTH_FLOW}
                    <a class="ui button right floated" href="{$OAUTH_CANCEL_REGISTER_URL}">{$CANCEL}</a>
                    {/if}
                </form>
                {if $OAUTH_AVAILABLE and !$OAUTH_FLOW}
                <div class="ui horizontal divider">{$OR}</div>
                <div class="ui equal width grid">
                    {foreach $OAUTH_PROVIDERS as $name => $meta}
                        <div class="column">
                            <a href="{$meta.url}" class="ui fluid button left floated" {if $meta.button_css}style="{$meta.button_css}"{/if}>
                                {if $meta.logo_url}
                                    <img src="{$meta.logo_url}" {if $meta.logo_css}style="{$meta.logo_css}"{/if} alt="{$name|ucfirst}">
                                {elseif $meta.icon}
                                    <i class="{$meta.icon} fa-lg"></i>
                                {/if}
                                <span {if $meta.text_css}style="{$meta.text_css}"{/if}>{$meta.continue_with}</span>
                            </a>
                        </div>
                    {/foreach}
                </div>
                {/if}
                {if !$OAUTH_FLOW}
                <div class="ui horizontal divider">{$ALREADY_REGISTERED}</div>
                <div class="ui center aligned">
                    <a class="ui large positive button" href="{$LOGIN_URL}">{$LOG_IN}</a>
                </div>
                {/if}
            </div>
        </div>
    </div>
</div>

{if $OAUTH_FLOW && $OAUTH_EMAIL_VERIFIED}
    <script>
        document.getElementById('email').addEventListener('keyup', (e) => {
            checkEmailValidity(e.target.value);
        });

        const checkEmailValidity = (email) => {
            if ('{$OAUTH_EMAIL_VERIFIED}' && email !== '{$OAUTH_EMAIL_ORIGINAL}') {
                addEmailCaption('{$OAUTH_EMAIL_NOT_VERIFIED_MESSAGE}', 'orange');
            } else {
                addEmailCaption('{$OAUTH_EMAIL_VERIFIED_MESSAGE}', 'green');
            }
        }

        const addEmailCaption = (text, colour) => {
            const email = document.getElementById('email');
            document.getElementById('email-caption')?.remove();
            email.parentElement.insertAdjacentHTML('beforeend', '<div id="email-caption" style="margin-top: 5px;" class="ui basic ' + colour + ' label">' + text + '</div>');
        };

        window.onload = () => checkEmailValidity('{$EMAIL_INPUT}');
    </script>
{/if}

{include file='footer.tpl'}
