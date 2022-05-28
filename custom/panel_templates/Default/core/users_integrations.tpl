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
                                    <h5 style="margin-top: 7px; margin-bottom: 7px;">{$VIEWING_USER_INTEGRATIONS}</h5>
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

                            <div class="table-responsive">
                                <table class="table table-borderless table-striped">
                                    <thead>
                                        <tr>
                                            <th>{$INTEGRATION}</th>
                                            <th>{$USERNAME}</th>
                                            <th>{$IDENTIFIER}</th>
                                            <th>{$VERIFIED}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {foreach $INTEGRATIONS as $integration_name => $integration}
                                        <tr>
                                            <td>
                                                {if $integration.icon}
                                                <i class="{$integration.icon} fa-2x align-middle">&nbsp;</i>
                                                {/if}
                                                {$integration_name}
                                            </td>

                                            <td>
                                                {if isset($USER_INTEGRATIONS[$integration_name])}
                                                <code>{$USER_INTEGRATIONS[$integration_name].username}</code>
                                                {else}
                                                <i>{$NOT_LINKED}</i>
                                                {/if}
                                            </td>

                                            <td>
                                                {if isset($USER_INTEGRATIONS[$integration_name])}
                                                <code>{$USER_INTEGRATIONS[$integration_name].identifier}</code>
                                                {else}
                                                <i>{$NOT_LINKED}</i>
                                                {/if}
                                            </td>

                                            <td>
                                                {if isset($USER_INTEGRATIONS[$integration_name]) &&
                                                $USER_INTEGRATIONS[$integration_name].verified}
                                                <i class="fa fa-check-circle text-success"></i>
                                                {else}
                                                <i class="fa fa-times-circle text-danger"></i>
                                                {/if}
                                            </td>

                                            <td class="text-right">
                                                {if isset($USER_INTEGRATIONS[$integration_name])}
                                                <button class="btn btn-danger btn-sm"
                                                    onclick="showDeleteModal('{$integration_name}')">{$UNLINK}</button>
                                                <a href="{$integration.edit}" class="btn btn-warning btn-sm">{$EDIT}</a>
                                                {else}
                                                <a href="{$integration.link}"
                                                    class="btn btn-success btn-sm">{$MANUAL_LINKING}</a>
                                                {/if}
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

            <!-- Unlink Modal -->
            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{$ARE_YOU_SURE}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            {$ARE_YOU_SURE_MESSAGE}
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" id="deleteId" value="">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">{$NO}</button>
                            <button type="button" onclick="unlinkProvider()" class="btn btn-success">{$YES}</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- End Content Wrapper -->
        </div>

        <!-- End Wrapper -->
    </div>

    {include file='scripts.tpl'}

    <script type="text/javascript">
        function showDeleteModal(id) {
            $('#deleteId').attr('value', id);
            $('#deleteModal').modal().show();
        }

        function unlinkProvider() {
            const id = $('#deleteId').attr('value');
            if (id) {
                const response = $.post("{$UNLINK_LINK}" + id, { integration: id, action: 'unlink', token: "{$TOKEN}" });
                response.done(function () { window.location.reload(); });
            }
        }
    </script>

</body>

</html>