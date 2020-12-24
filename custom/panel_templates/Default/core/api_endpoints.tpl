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
                        <h1 class="h3 mb-0 text-gray-800">{$API_ENDPOINTS}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                            <li class="breadcrumb-item active">{$API_ENDPOINTS}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='update.tpl'}

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-9">
                                    <h5 style="margin-top: 7px; margin-bottom: 7px;">{$ENDPOINTS_INFO}</h5>
                                </div>
                                <div class="col-md-3">
                                    <span class="float-md-right"><a class="btn btn-warning" href="{$BACK_LINK}">{$BACK}</a></span>
                                </div>
                            </div>

                            {if count($ENDPOINTS_ARRAY)}
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <th>{$ROUTE}</th>
                                        <th>{$DESCRIPTION}</th>
                                        <th>{$MODULE}</th>
                                        {foreach from=$ENDPOINTS_ARRAY item=endpoint}
                                            <tr>
                                                <td>
                                                    <div><code>/{$endpoint.route}</code></div>
                                                </td>
                                                <td>
                                                    <div>{$endpoint.description}</div>
                                                </td>
                                                <td>
                                                    <div>{$endpoint.module}</div>
                                                </td>
                                            </tr>
                                        {/foreach}
                                    </table>
                                </div>
                                <hr />
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
