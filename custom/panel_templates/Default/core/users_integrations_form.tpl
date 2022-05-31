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
                        <h1 class="h3 mb-0 text-gray-800">{$USERS}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$USER_MANAGEMENT}</li>
                            <li class="breadcrumb-item active">{$USERS}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='includes/update.tpl'}

                    <div class="card shadow mb-4">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-9">
                                    <h5 style="margin-top: 7px; margin-bottom: 7px;">{$INTEGRATION_TITLE}</h5>
                                </div>
                                <div class="col-md-3">
                                    <span class="float-md-right">
                                        <a href="{$BACK_LINK}" class="btn btn-warning">{$BACK}</a>
                                        {if isset($SYNC_INTEGRATION)}
                                        <form role="form" action="" method="post" style="display:inline">
                                            <input type="hidden" name="token" value="{$TOKEN}">
                                            <input type="hidden" name="action" value="sync">
                                            <input type="submit" value="{$SYNC_INTEGRATION}" class="btn btn-primary">
                                        </form>
                                        {/if}
                                    </span>
                                </div>
                            </div>
                            <hr />

                            <!-- Success and Error Alerts -->
                            {include file='includes/alerts.tpl'}

                            <form role="form" action="" method="post">
                                <div class="form-group">
                                    <label for="InputUsername">{$USERNAME}</label>
                                    <input type="text" name="username" class="form-control" id="InputUsername"
                                        placeholder="{$USERNAME}" value="{$USERNAME_VALUE}">
                                </div>
                                <div class="form-group">
                                    <label for="InputIdentifier">{$IDENTIFIER}</label>
                                    <input type="text" name="identifier" class="form-control" id="InputIdentifier"
                                        placeholder="{$IDENTIFIER}" value="{$IDENTIFIER_VALUE}">
                                </div>
                                <div class="form-group custom-control custom-switch">
                                    <input type="hidden" name="verified" value="0">
                                    <input type="checkbox" name="verified" class="custom-control-input"
                                        id="InputVerified" value="1" {if $VERIFIED_VALUE} checked{/if}>
                                    <label class="custom-control-label" for="InputVerified">{$IS_VERIFIED}</label>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="token" value="{$TOKEN}">
                                    <input type="hidden" name="action" value="details">
                                    <input type="submit" value="{$SUBMIT}" class="btn btn-primary">
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