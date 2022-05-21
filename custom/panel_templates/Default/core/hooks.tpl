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
                        <h1 class="h3 mb-0 text-gray-800">{$HOOKS}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                            <li class="breadcrumb-item active">{$HOOKS}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='includes/update.tpl'}

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="row" style="margin-bottom: 10px;">
                                <div class="col-md-9">
                                    <p style="margin-top: 7px; margin-bottom: 7px;">{$HOOKS_INFO}</p>
                                </div>
                                <div class="col-md-3">
                                    <span class="float-md-right">
                                        <a href="{$NEW_HOOK_LINK}" class="btn btn-primary"><i
                                                class="fas fa-plus-circle"></i> {$NEW_HOOK}</a>
                                    </span>
                                </div>
                            </div>
                            <hr />

                            <!-- Success and Error Alerts -->
                            {include file='includes/alerts.tpl'}

                            {if count($HOOKS_LIST)}
                            <div class="table-responsive">
                                <table class="table table-borderless table-striped">
                                    <thead>
                                        <tr>
                                            <th>{$NAME}</th>
                                            <th>{$LINK}</th>
                                            <th class="float-md-right">{$EDIT}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {foreach from=$HOOKS_LIST item=item item=hook name=hook_list}
                                        <tr>
                                            <td>{$hook.name}</td>
                                            <td><a href="{$hook.edit_link}">{$hook.url}</a></td>
                                            <td>
                                                <div class="float-md-right">
                                                    <a href="{$hook.edit_link}" class="btn btn-warning btn-sm"><i
                                                            class="fas fa-edit fa-fw"></i></a>
                                                    <button class="btn btn-danger btn-sm" type="button"
                                                        onclick="showDeleteModal('{$hook.delete_link}')"><i
                                                            class="fas fa-trash fa-fw"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        {/foreach}
                                    </tbody>
                                </table>
                            </div>
                            {else}
                            {$NO_HOOKS}
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
                        {$CONFIRM_DELETE_HOOK}
                    </div>
                    <div class="modal-footer">
                        <form action="" id="deleteForm" method="post">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{$NO}</button>
                            <input type="hidden" name="token" value="{$TOKEN}">
                            <button type="submit" class="btn btn-primary">{$YES}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- End Wrapper -->
    </div>

    {include file='scripts.tpl'}

    <script type="text/javascript">
        function showDeleteModal(id) {
            $('#deleteForm').attr('action', id);
            $('#deleteModal').modal().show();
        }
    </script>

</body>

</html>