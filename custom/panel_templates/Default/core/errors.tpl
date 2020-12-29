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
                    <h1 class="h3 mb-0 text-gray-800">{$DEBUGGING_AND_MAINTENANCE}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                        <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                        <li class="breadcrumb-item"><a href="{$BACK_LINK}">{$DEBUGGING_AND_MAINTENANCE}</a></li>
                        <li class="breadcrumb-item active">{$ERROR_LOGS}</li>
                    </ol>
                </div>

                <!-- Update Notification -->
                {include file='includes/update.tpl'}

                <div class="card shadow mb-4">
                    <div class="card-body">

                        <h5 style="display: inline-block; margin-top: 7px; margin-bottom: 7px;">{$ERROR_LOGS}</h5>

                        <div class="float-right">
                            <a href="{$BACK_LINK}" class="btn btn-primary">{$BACK}</a>
                        </div>
                        <hr />

                        <!-- Success and Error Alerts -->
                        {include file='includes/alerts.tpl'}

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <td>
                                        <a href="{$FATAL_LOG_LINK}">{$FATAL_LOG}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href="{$NOTICE_LOG_LINK}">{$NOTICE_LOG}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href="{$WARNING_LOG_LINK}">{$WARNING_LOG}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href="{$OTHER_LOG_LINK}">{$OTHER_LOG}</a>
                                    </td>
                                </tr>
                            </table>
                        </div>

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