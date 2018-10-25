{include file='header.tpl'}
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    {include file='navbar.tpl'}
    {include file='sidebar.tpl'}

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">{$SOCIAL_MEDIA}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                            <li class="breadcrumb-item active">{$SOCIAL_MEDIA}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                {if isset($NEW_UPDATE)}
                {if $NEW_UPDATE_URGENT eq true}
                <div class="alert alert-danger">
                    {else}
                    <div class="alert alert-primary alert-dismissible" id="updateAlert">
                        <button type="button" class="close" id="closeUpdate" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {/if}
                        {$NEW_UPDATE}
                        <br />
                        <a href="{$UPDATE_LINK}" class="btn btn-primary" style="text-decoration:none">{$UPDATE}</a>
                        <hr />
                        {$CURRENT_VERSION}<br />
                        {$NEW_VERSION}
                    </div>
                    {/if}

                    <div class="card">
                        <div class="card-body">
                            {if isset($SUCCESS)}
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h5><i class="icon fa fa-check"></i> {$SUCCESS_TITLE}</h5>
                                    {$SUCCESS}
                                </div>
                            {/if}

                            {if isset($ERRORS) && count($ERRORS)}
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h5><i class="icon fas fa-exclamation-triangle"></i> {$ERRORS_TITLE}</h5>
                                    <ul>
                                        {foreach from=$ERRORS item=error}
                                            <li>{$error}</li>
                                        {/foreach}
                                    </ul>
                                </div>
                            {/if}

                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="InputYoutube">{$YOUTUBE_URL}</label>
                                    <input type="text" name="youtubeurl" class="form-control" id="InputYoutube"
                                           placeholder="{$YOUTUBE_URL}"
                                           value="{$YOUTUBE_URL_VALUE}">
                                </div>
                                <div class="form-group">
                                    <label for="InputTwitter">{$TWITTER_URL}</label>
                                    <input type="text" name="twitterurl" class="form-control" id="InputTwitter"
                                           placeholder="{$TWITTER_URL}"
                                           value="{$TWITTER_URL_VALUE}">
                                </div>
                                <div class="form-group">
                                    <label for="InputTwitterStyle">{$TWITTER_STYLE}</label>
                                    <input id="InputTwitterStyle" name="twitter_dark_theme" type="checkbox"
                                           class="js-switch"
                                           value="1"{if $TWITTER_STYLE_VALUE eq 'dark'} checked{/if} />
                                </div>
                                <div class="form-group">
                                    <label for="InputDiscord">{$DISCORD_SERVER_ID}</label>
                                    <input type="text" name="discordid" class="form-control" id="InputDiscord"
                                           placeholder="{$DISCORD_SERVER_ID}"
                                           value="{$DISCORD_SERVER_ID_VALUE}">
                                </div>
                                <div class="form-group">
                                    <label for="InputGPlus">{$GOOGLE_PLUS_URL}</label>
                                    <input type="text" name="gplusurl" class="form-control" id="InputGPlus"
                                           placeholder="{$GOOGLE_PLUS_URL}"
                                           value="{$GOOGLE_PLUS_URL_VALUE}">
                                </div>
                                <div class="form-group">
                                    <label for="InputFacebook">{$FACEBOOK_URL}</label>
                                    <input type="text" name="fburl" class="form-control" id="InputFacebook"
                                           placeholder="{$FACEBOOK_URL}"
                                           value="{$FACEBOOK_URL_VALUE}">
                                </div>
                                <h4>{$DISCORD_HOOKS}</h4>
                                <div class="callout callout-info">
                                    <h5><i class="icon fa fa-info-circle"></i> {$INFO}</h5>
                                    {$DISCORD_HOOKS_INFO}
                                </div>
                                <div class="form-group">
                                    <label for="InputDiscordHookURL">{$DISCORD_HOOK_URL}</label>
                                    <input type="text" class="form-control" name="discord_url"
                                           placeholder="{$DISCORD_HOOK_URL}"
                                           value="{$DISCORD_HOOK_URL_VALUE}"
                                           id="InputDiscordHookURL">
                                </div>
                                <div class="form-group">
                                    <label for="InputDiscordHooks">{$DISCORD_HOOK_EVENTS}</label>
                                    <select multiple class="form-control" name="discord_hooks[]"
                                            id="InputDiscordHooks">
                                        {foreach from=$DISCORD_ALL_HOOKS key=key item=item}
                                            <option value="{$key|escape}"{if in_array($key|escape, $DISCORD_ENABLED_HOOKS)} selected{/if}>{$item|escape}</option>
                                        {/foreach}
                                    </select>
                                </div>
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <input type="submit" class="btn btn-primary"
                                       value="{$SUBMIT}">
                            </form>

                        </div>
                    </div>

                    <!-- Spacing -->
                    <div style="height:1rem;"></div>

                </div>
        </section>
    </div>

    {include file='footer.tpl'}

</div>
<!-- ./wrapper -->

{include file='scripts.tpl'}

</body>
</html>