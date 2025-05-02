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

                    {if isset($OAUTH)}
                        <form action="" method="post">
                            <div class="row">
                                <div class="col">
                                    <div class="card shadow mb-4">
                                        <div class="card-body">

                                            <h5>{$OAUTH} {if $OAUTH_PROVIDER_DATA['logo_url']}
                                                    <img src="{$OAUTH_PROVIDER_DATA['logo_url']}" alt="{$OAUTH_PROVIDER_DATA['name']|ucfirst} Logo" style="width: 16px; height: auto;">
                                                {elseif $OAUTH_PROVIDER_DATA['icon']}
                                                    <i class="{$OAUTH_PROVIDER_DATA['icon']}"></i>
                                                {/if}</h5>
                                            <hr />
                                            <div class="card shadow border-left-primary">
                                                <div class="card-body">
                                                    <h5><i class="icon fa fa-info-circle"></i> {$INFO}</h5>
                                                    {$OAUTH_INFO}
                                                </div>
                                            </div>

                                            <br />

                                            <div class="form-group custom-control custom-switch">
                                                <input id="enable" name="enable"
                                                       type="checkbox" class="custom-control-input" {if $OAUTH_PROVIDER_DATA['enabled']
                                                && $OAUTH_PROVIDER_DATA['setup']} checked{/if} />
                                                <label for="enable" id="enable"
                                                       class="custom-control-label">
                                                    {$REGISTER_LOGIN_WITH_OAUTH}
                                                </label>
                                            </div>

                                            <div class="form-group">
                                                <label for="client-id">{$CLIENT_ID}</label>
                                                <input type="text" name="client-id" class="form-control"
                                                       id="client-id" placeholder="{$CLIENT_ID}"
                                                       value="{$OAUTH_PROVIDER_DATA['client_id']}">
                                            </div>

                                            <div class="form-group">
                                                <label for="client-secret">{$CLIENT_SECRET}</label>
                                                <input type="password" name="client-secret"
                                                       class="form-control" id="client-secret"
                                                       placeholder="{$CLIENT_SECRET}" value="{$OAUTH_PROVIDER_DATA['client_secret']}">
                                            </div>

                                            <div class="form-group">
                                                <label for="client-url">{$REDIRECT_URL}</label>
                                                <input type="text" class="form-control" id="client-url"
                                                       readonly value="{$OAUTH_PROVIDER_DATA['client_url']}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <input type="hidden" name="action" value="oauth">
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
                            </div>
                        </form>
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