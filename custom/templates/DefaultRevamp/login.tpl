{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
    {$SIGN_IN}
</h2>

{if count($ERROR)}
<div class="ui error icon message">
    <i class="x icon"></i>
    <div class="content">
        <div class="header">{$ERROR_TITLE}</div>
        <ul class="list">
            {foreach from=$ERROR item=error}
            <li>{$error}</li>
            {/foreach}
        </ul>
    </div>
</div>
{/if}

<div class="ui padded segment" id="login">
    <div class="ui stackable grid">
        <div class="ui centered row">
            <div class="ui sixteen wide tablet ten wide computer column">
                <form class="ui form" action="" method="post" id="form-login">
                    {if isset($EMAIL)}
                    <div class="field">
                        <label>{$EMAIL}</label>
                        <input type="email" name="email" id="email" value="{$USERNAME_INPUT}" placeholder="{$EMAIL}"
                            tabindex="1">
                    </div>
                    {else}
                    <div class="field">
                        <label>{$USERNAME}</label>
                        <input type="text" name="username" id="username" value="{$USERNAME_INPUT}"
                            placeholder="{$USERNAME}" tabindex="1">
                    </div>
                    {/if}
                    <div class="field">
                        <label>{$PASSWORD}</label>
                        <input type="password" name="password" id="password" placeholder="{$PASSWORD}" tabindex="2">
                    </div>
                    <div class="inline field">
                        <div class="ui checkbox">
                            <input type="checkbox" name="remember" id="remember" value="1" tabindex="3">
                            <label for="remember">{$REMEMBER_ME}</label>
                        </div>
                    </div>
                    {if $CAPTCHA}
                    <div class="field">
                        {$CAPTCHA}
                    </div>
                    {/if}
                    <input type="hidden" name="token" value="{$FORM_TOKEN}">
                    <input type="submit" class="ui primary button" value="{$SIGN_IN}" tabindex="5">
                    <a class="ui negative button right floated" href="{$FORGOT_PASSWORD_URL}">{$FORGOT_PASSWORD}</a>
                </form>
                {if $OAUTH_AVAILABLE}
                <div class="ui horizontal divider">{$OR}</div>
                <div class="ui equal width two column grid middle aligned">
                    {foreach $OAUTH_PROVIDERS as $name => $meta}
                    <div class="column">
                        <a href="{$meta.url}" class="ui fluid button left floated" {if $meta.button_css}style="{$meta.button_css}"{/if}>
                            {if $meta.logo_url}
                                <img src="{$meta.logo_url}" {if $meta.logo_css}style="{$meta.logo_css}"{/if} alt="{$name|ucfirst}">
                            {elseif $meta.icon}
                                <i class="{$meta.icon} fa-lg"></i>
                            {/if}
                            <span {if $meta.text_css}style="{$meta.text_css}"{/if}>{$meta.log_in_with}</span>
                        </a>
                    </div>
                    {/foreach}
                </div>
                {/if}
                <div class="ui horizontal divider">{$NOT_REGISTERED_YET}</div>
                <div class="ui center aligned">
                    <a class="ui large positive button" href="{$REGISTER_URL}">{$REGISTER}</a>
                </div>
            </div>
        </div>
    </div>
</div>

{include file='footer.tpl'}
