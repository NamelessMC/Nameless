{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
    {$CONNECT_WITH_AUTHME}
</h2>

{if isset($ERRORS)}
<div class="ui error icon message">
    <i class="x icon"></i>
    <div class="content">
        <div class="header">{$ERROR}</div>
        <ul class="list">
            {foreach from=$ERRORS item=error}
            <li>{$error}</li>
            {/foreach}
        </ul>
    </div>
</div>
{/if}

<div class="ui padded segment" id="authme-email">
    <div class="ui stackable grid">
        <div class="ui centered row">
            <div class="ui sixteen wide tablet ten wide computer column">
                <div class="ui success icon message">
                    <i class="check icon"></i>
                    <div class="content">
                        <div class="header">{$AUTHME_SUCCESS}</div>
                        {$AUTHME_INFO}
                    </div>
                </div>
                <form class="ui form" action="" method="post" id="form-authme-email">
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

                    <div class="inline field">
                        <div class="ui checkbox">
                            <input type="checkbox" name="authme_sync_password" id="authme_sync_password" tabindex="{$counter++}" {if $AUTHME_SYNC_PASSWORD_CHECKED}checked{/if}>
                            <label for="authme_sync_password">
                                {$AUTHME_SYNC_PASSWORD}
                                <div class="ui icon label mini" data-tooltip="{$AUTHME_SYNC_PASSWORD_INFO}">
                                    <i class="question icon"></i>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="field">
                        <input type="hidden" name="token" value="{$TOKEN}">
                        <input type="submit" class="ui primary button" value="{$SUBMIT}">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{include file='footer.tpl'}
