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
                        <h1 class="h3 mb-0 text-gray-800">{$PAGE_METADATA}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$PAGES}</li>
                            <li class="breadcrumb-item active">{$PAGE_METADATA}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='update.tpl'}

                    <div class="card shadow mb-4">
                        <div class="card-body">

                            <!-- Success and Error Alerts -->
                            {include file='alerts.tpl'}

                            <div class="table-responsive">
                                <table class="table table-striped dataTables-pages">
                                    <thead>
                                        <tr>
                                            <th>{$PAGE_TITLE}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {foreach from=$PAGE_LIST key=key item=item}
                                        <tr>
                                            <td><a href="{$EDIT_LINK|replace:'{x}':$item.id}">{$key|escape}</a></td>
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