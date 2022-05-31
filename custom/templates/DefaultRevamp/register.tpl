{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
    {$CREATE_AN_ACCOUNT}
    {if $OAUTH_FLOW}
    <div class="sub header">{$OAUTH_MESSAGE_CONTINUE}</div>
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
                </form>
                {if $OAUTH_AVAILABLE and !$OAUTH_FLOW}
                <div class="ui horizontal divider">{$OR}</div>
                <div class="ui equal width grid">
                    {foreach $OAUTH_PROVIDERS as $name => $meta}
                    <div class="column">
                        <a href="{$meta.url}" class="ui fluid button left floated">
                            {if $meta.icon}
                            <i class="{$meta.icon} fa-lg"></i>
                            {/if}
                            {$name|ucfirst}
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

{include file='footer.tpl'}