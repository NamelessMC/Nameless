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
                        <h1 class="h3 mb-0 text-gray-800">{$GROUPS}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$GROUPS}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='update.tpl'}

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <a class="btn btn-primary" style="margin-bottom: 10px" href="{$NEW_GROUP_LINK}">{$NEW_GROUP}</a>

                            <!-- Success and Error Alerts -->
                            {include file='alerts.tpl'}

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{$ORDER}</th>
                                            <th>{$GROUP_ID}</th>
                                            <th>{$NAME}</th>
                                            <th>{$USERS}</th>
                                            <th>{$STAFF}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {foreach from=$GROUP_LIST item=group}
                                            <tr>
                                                <td>{$group.order}</td>
                                                <td>{$group.id}</td>
                                                <td><a href="{$group.edit_link}">{$group.name}</a></td>
                                                <td>{$group.users}</td>
                                                <td>{if $group.staff}<i class="fas fa-check-circle text-success"></i>{else}<i class="fas fa-times-circle text-danger"></i>{/if}</td>
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