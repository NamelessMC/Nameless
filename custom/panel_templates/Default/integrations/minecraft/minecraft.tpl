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
                    <h1 class="h3 mb-0 text-gray-800">{$MINECRAFT}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                        <li class="breadcrumb-item active">{$INTEGRATIONS}</li>
                        <li class="breadcrumb-item active">{$MINECRAFT}</li>
                    </ol>
                </div>

                <!-- Update Notification -->
                {include file='includes/update.tpl'}

                <div class="card shadow mb-4">
                    <div class="card-body">

                        <!-- Success and Error Alerts -->
                        {include file='includes/alerts.tpl'}

                        <form id="enableMinecraft" action="" method="post">
                            <div class="form-group">
                                <label for="inputEnableMinecraft">{$ENABLE_MINECRAFT_INTEGRATION}</label>
                                <input type="hidden" name="enable_minecraft" value="0">
                                <input id="inputEnableMinecraft" name="enable_minecraft" type="checkbox"
                                       class="js-switch js-check-change" {if $MINECRAFT_ENABLED eq 1} checked{/if}
                                       value="1" />
                                <input type="hidden" name="token" value="{$TOKEN}">
                            </div>
                        </form>

                        {if $MINECRAFT_ENABLED eq 1}
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    {if isset($AUTHME)}
                                        <tr>
                                            <td>
                                                <a href="{$AUTHME_LINK}">{$AUTHME}</a>
                                            </td>
                                        </tr>
                                    {/if}
                                    {if isset($ACCOUNT_VERIFICATION)}
                                        <tr>
                                            <td>
                                                <a href="{$ACCOUNT_VERIFICATION_LINK}">{$ACCOUNT_VERIFICATION}</a>
                                            </td>
                                        </tr>
                                    {/if}
                                    {if isset($SERVERS)}
                                        <tr>
                                            <td>
                                                <a href="{$SERVERS_LINK}">{$SERVERS}</a>
                                            </td>
                                        </tr>
                                    {/if}
                                    {if isset($QUERY_ERRORS)}
                                        <tr>
                                            <td>
                                                <a href="{$QUERY_ERRORS_LINK}">{$QUERY_ERRORS}</a>
                                            </td>
                                        </tr>
                                    {/if}
                                    {if isset($BANNERS)}
                                        <tr>
                                            <td>
                                                <a href="{$BANNERS_LINK}">{$BANNERS}</a>
                                            </td>
                                        </tr>
                                    {/if}
                                    {if isset($PLACEHOLDERS)}
                                        <tr>
                                            <td>
                                                <a href="{$PLACEHOLDERS_LINK}">{$PLACEHOLDERS}</a>
                                            </td>
                                        </tr>
                                    {/if}
                                </table>
                            </div>
                        {/if}

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