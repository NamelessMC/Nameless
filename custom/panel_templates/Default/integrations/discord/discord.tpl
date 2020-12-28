{include file='header.tpl'}

<body id="page-top">

<!-- Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    {include file='sidebar.tpl'}

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main content -->
        <div id="content">

            <!-- Topbar -->
            {include file='navbar.tpl'}

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">{$DISCORD}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                        <li class="breadcrumb-item active">{$INTEGRATIONS}</li>
                        <li class="breadcrumb-item active">{$DISCORD}</li>
                    </ol>
                </div>

                <!-- Update Notification -->
                {include file='includes/update.tpl'}

                <div class="card shadow mb-4">
                    <div class="card-body">

                        <!-- Success and Error Alerts -->
                        {include file='includes/alerts.tpl'}

                        <div class="card shadow border-left-primary">
                            <div class="card-body">
                                <h5><i class="icon fa fa-info-circle"></i> {$INFO}</h5>
                                {$INVITE_LINK}
                            </div>
                        </div>
                        <br />

                        <h4>{$REQUIREMENTS}</h4>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tbody>
                                <tr>
                                    <td>{$BOT_SETUP}</td>
                                    <td>{if $BOT_URL_SET && $GUILD_ID_SET && $BOT_USERNAME_SET}
                                            <i class="fas fa-check-circle text-success"></i>
                                        {else}
                                            <i class="fas fa-times-circle text-danger"></i>
                                        {/if}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr>

                        <form id="settings" action="" method="post">
                            <div class="form-group">
                                <label for="inputEnableDiscord">{$ENABLE_DISCORD_INTEGRATION}</label>
                                <input type="hidden" name="enable_discord" value="0">
                                <input id="inputEnableDiscord" name="enable_discord" type="checkbox"
                                       class="js-switch" {if $DISCORD_ENABLED eq 1} checked{/if} value="1" />
                                <input type="hidden" name="token" value="{$TOKEN}">
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <input type="submit" value="{$SUBMIT}"
                                       class="btn btn-primary" {if !$BOT_URL_SET || !$GUILD_ID_SET} disabled {/if}>
                            </div>
                        </form>

                    </div>
                </div>

                <!-- Spacing -->
                <div style="height:1rem;"></div>

                <!-- End Page Content -->
            </div>

            <!-- End Main Content -->
        </div>

        {include file='footer.tpl'}

        <!-- End Content Wrapper -->
    </div>

    <!-- End Wrapper -->
</div>

{include file='scripts.tpl'}

</body>

</html>