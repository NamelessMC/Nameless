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
                        <h1 class="h3 mb-0 text-gray-800">{$INTEGRATIONS}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$INTEGRATIONS}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='includes/update.tpl'}

                    <div class="card shadow mb-4">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-9">
                                    <h5 style="margin-top: 7px; margin-bottom: 7px;">{$EDITING_INTEGRATION}</h5>
                                </div>
                                <div class="col-md-3">
                                    <span class="float-md-right">
                                        <a href="{$BACK_LINK}" class="btn btn-warning">{$BACK}</a>
                                    </span>
                                </div>
                            </div>
                            <hr />

                            <!-- Success and Error Alerts -->
                            {include file='includes/alerts.tpl'}

                            <form role="form" action="" method="post">
                                <div class="form-group custom-control custom-switch">
                                    <input type="hidden" name="enabled" value="0">
                                    <input type="checkbox" name="enabled" class="custom-control-input" id="InputEnabled"
                                        value="1" {if $ENABLED_VALUE} checked{/if}>
                                    <label class="custom-control-label" for="InputEnabled">{$ENABLED}</label>
                                </div>
                                <div class="form-group custom-control custom-switch">
                                    <input type="hidden" name="can_unlink" value="0">
                                    <input type="checkbox" name="can_unlink" class="custom-control-input"
                                        id="InputCanUnlink" value="1" {if $CAN_UNLINK_VALUE} checked{/if}>
                                    <label class="custom-control-label"
                                        for="InputCanUnlink">{$CAN_UNLINK_INTEGRATION}</label>
                                </div>
                                <div class="form-group custom-control custom-switch">
                                    <input type="hidden" name="required" value="0">
                                    <input type="checkbox" name="required" class="custom-control-input"
                                        id="InputRequired" value="1" {if $REQUIRED_VALUE} checked{/if}>
                                    <label class="custom-control-label"
                                        for="InputRequired">{$REQUIRE_INTEGRATION}</label>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="token" value="{$TOKEN}">
                                    <input type="hidden" name="action" value="general_settings">
                                    <input type="submit" value="{$SUBMIT}" class="btn btn-primary">
                                </div>
                            </form>
                        </div>
                    </div>

                    {if isset($SETTINGS_TEMPLATE)}
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                {include file=$SETTINGS_TEMPLATE}
                            </div>
                        </div>
                    {/if}

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