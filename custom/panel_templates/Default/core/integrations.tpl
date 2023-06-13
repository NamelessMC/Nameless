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
                            <!-- Success and Error Alerts -->
                            {include file='includes/alerts.tpl'}

                            <div class="table-responsive">
                                <table class="table table-borderless table-striped">
                                    <thead>
                                        <tr>
                                            <th>{$INTEGRATION}</th>
                                            <th>{$ENABLED}</th>
                                            <th>{$CAN_UNLINK}</th>
                                            <th>{$REQUIRED}</th>
                                            <th></th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        {foreach $INTEGRATIONS_LIST as $integration_name => $integration}
                                        <tr>
                                            <td>
                                                {if $integration.icon}
                                                <i class="{$integration.icon} fa-lg align-middle">&nbsp;</i>
                                                {/if}
                                                {$integration.name}
                                            </td>
                                            <td>{if $integration.enabled eq 1}<i
                                                    class="fa fa-check-circle text-success"></i>{else}<i
                                                    class="fa fa-times-circle text-danger"></i>{/if}</td>
                                            <td>{if $integration.can_unlink eq 1}<i
                                                    class="fa fa-check-circle text-success"></i>{else}<i
                                                    class="fa fa-times-circle text-danger"></i>{/if}</td>
                                            <td>{if $integration.required eq 1}<i
                                                    class="fa fa-check-circle text-success"></i>{else}<i
                                                    class="fa fa-times-circle text-danger"></i>{/if}</td>
                                            <td class="text-right">
                                                <a href="{$integration.edit_link}"
                                                    class="btn btn-warning btn-sm">{$EDIT}</a>
                                            </td>
                                        </tr>
                                        {/foreach}
                                    </tbody>
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