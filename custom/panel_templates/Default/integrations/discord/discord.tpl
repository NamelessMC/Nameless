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
                {include file='includes/update.tpl'}

                <div class="card">
                    <div class="card-body">

                        {include file='includes/success.tpl'}

                        {include file='includes/errors.tpl'}

                        <div class="callout callout-info">
                            <h5><i class="icon fa fa-info-circle"></i> {$INFO}</h5>
                            {$INVITE_LINK}
                        </div>

                        <h4>{$REQUIREMENTS}</h4>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td>{$BOT_SETUP}</td>
                                        <td>{if $BOT_URL_SET && $GUILD_ID_SET}<i class="fas fa-check-circle text-success"></i>{else}<i class="fas fa-times-circle text-danger"></i>{/if}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <hr>

                        <form id="settings" action="" method="post">
                            <div class="form-group">
                                <label for="inputEnableDiscord">{$ENABLE_DISCORD_INTEGRATION}</label>
                                <input type="hidden" name="enable_discord" value="0">
                                <input id="inputEnableDiscord" name="enable_discord" type="checkbox" class="js-switch" {if $DISCORD_ENABLED eq 1} checked{/if} value="1"/>
                                <input type="hidden" name="token" value="{$TOKEN}">
                            </div>

                            <div class="form-group">
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <input type="submit" value="{$SUBMIT}" class="btn btn-primary" {if !$BOT_URL_SET || !$GUILD_ID_SET} disabled {/if}>
                            </div>
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