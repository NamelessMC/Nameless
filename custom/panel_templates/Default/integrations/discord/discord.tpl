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
                        <h1 class="m-0 text-dark">{$DISCORD}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$INTEGRATIONS}</li>
                            <li class="breadcrumb-item active">{$DISCORD}</li>
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

                            <form id="enableDiscord" action="" method="post">
                                <div class="form-group">
                                    <label for="inputEnableDiscord">{$ENABLE_DISCORD_INTEGRATION}</label>
                                    <input type="hidden" name="enable_discord" value="0">
                                    <input id="inputEnableDiscord" name="enable_discord" type="checkbox" class="js-switch js-check-change-enable" {if $DISCORD_ENABLED eq 1} checked{/if} value="1"/>
                                    <input type="hidden" name="token" value="{$TOKEN}">
                                </div>
                            </form>

                            {if $DISCORD_ENABLED eq 1}
                                <hr>
                                <form id="settings" action="" method="post">
                                    <div class="form-group">
                                        <label for="inputGuildId">{$GUILD_ID} <span class="badge badge-info" data-toggle="popover" data-title="{$INFO}" data-content="{$ID_INFO|escape}"><i class="fa fa-question"></i></label>
                                        <input type="number" name="guild_id" class="form-control" id="inputGuildId" value="{$GUILD_ID_VALUE}">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputBotUrl">{$BOT_URL} <span class="badge badge-warning" data-toggle="popover" data-title="{$INFO}" data-content="{$BOT_URL_INFO|escape}"><i class="fa fa-exclamation-triangle"></i></label>
                                        <input type="text" name="bot_url" class="form-control" id="inputBotUrl" value="{$BOT_URL_VALUE}">
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="token" value="{$TOKEN}">
                                        <input type="submit" value="{$SUBMIT}" class="btn btn-primary">
                                    </div>
                                </form>
                                <a class="btn btn-info" style="color: white;" href="{$TEST_URL}">{$TEST}</a>
                            {/if}

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