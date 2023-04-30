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
                        <h1 class="h3 mb-0 text-gray-800">{$PLACEHOLDERS}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$INTEGRATIONS}</li>
                            <li class="breadcrumb-item"><a href="{$MINECRAFT_LINK}">{$MINECRAFT}</a></li>
                            <li class="breadcrumb-item active">{$PLACEHOLDERS}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='includes/update.tpl'}

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="card shadow border-left-primary">
                                <div class="card-body">
                                    <h5><i class="icon fa fa-info-circle"></i> {$INFO}</h5>
                                    {$PLACEHOLDERS_INFO}
                                </div>
                            </div>
                            <hr />

                            <!-- Success and Error Alerts -->
                            {include file='includes/alerts.tpl'}

                            {if count($ALL_PLACEHOLDERS)}
                            <form action="" method="POST">
                                <input type="hidden" name="action" value="settings">

                                <div class="form-group custom-control custom-switch">
                                    <input name="placeholders_enabled"
                                           id="InputEnablePlaceholders"
                                           type="checkbox"
                                           class="custom-control-input js-check-change"
                                           {if $ENABLE_PLACEHOLDERS_VALUE eq 1} checked{/if}>
                                    <label class="custom-control-label" for="InputEnablePlaceholders">
                                        {$ENABLE_PLACEHOLDERS}
                                    </label>
                                </div>

                                <input type="hidden" name="token" value="{$TOKEN}">
                                <div class="table-responsive">
                                    <table class="table table-borderless table-striped">
                                        <thead>
                                            <tr>
                                                <th>{$SERVER_ID}</th>
                                                <th>{$NAME}</th>
                                                <th>
                                                    {$FRIENDLY_NAME}
                                                    <span class="badge badge-info" style="margin-right:10px"><i
                                                            class="fas fa-question-circle" data-container="body"
                                                            data-toggle="popover" title="{$INFO}" data-placement="top"
                                                            data-content="{$FRIENDLY_NAME_INFO}"></i></span>
                                                </th>
                                                <th class="text-center">
                                                    {$SHOW_ON_PROFILE}
                                                    <span class="badge badge-info" style="margin-right:10px"><i
                                                            class="fas fa-question-circle" data-container="body"
                                                            data-toggle="popover" title="{$INFO}" data-placement="top"
                                                            data-content="{$SHOW_ON_PROFILE_INFO}"></i></span>
                                                </th>
                                                <th class="text-center">
                                                    {$SHOW_ON_FORUM}
                                                    <span class="badge badge-info" style="margin-right:10px"><i
                                                            class="fas fa-question-circle" data-container="body"
                                                            data-toggle="popover" title="{$INFO}" data-placement="top"
                                                            data-content="{$SHOW_ON_FORUM_INFO}"></i></span>
                                                </th>
                                                <th class="text-center">{$LEADERBOARD_ENABLED}</th>
                                                <th class="text-center">{$LEADERBOARD_SETTINGS}</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {foreach from=$ALL_PLACEHOLDERS item=placeholder}
                                                <tr>
                                                    <td>{$placeholder->server_id}</td>
                                                    <td><code>{$placeholder->name}</code></td>
                                                    <td>
                                                        <input type="text" class="form-control" name="friendly_name-{$placeholder->name}-server-{$placeholder->server_id}" value="{$placeholder->friendly_name}">
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="form-group custom-control custom-switch">
                                                            <input type="checkbox"
                                                                    id="InputShowOnProfile-{$placeholder->name}-{$placeholder->server_id}"
                                                                    class="custom-control-input"
                                                                    name="show_on_profile-{$placeholder->name}-server-{$placeholder->server_id}"
                                                                    {if $placeholder->show_on_profile eq 1} checked {/if}>
                                                            <label class="custom-control-label" for="InputShowOnProfile-{$placeholder->name}-{$placeholder->server_id}"></label>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="form-group custom-control custom-switch">
                                                            <input type="checkbox"
                                                                    id="InputShowOnForum-{$placeholder->name}-{$placeholder->server_id}"
                                                                    class="custom-control-input"
                                                                    name="show_on_forum-{$placeholder->name}-server-{$placeholder->server_id}"
                                                                    {if $placeholder->show_on_forum eq 1} checked {/if}>
                                                            <label class="custom-control-label" for="InputShowOnForum-{$placeholder->name}-{$placeholder->server_id}"></label>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        {if $placeholder->leaderboard eq 1}
                                                            <i class="fa fa-check-circle text-success"></i>
                                                        {else}
                                                            <i class="fa fa-times-circle text-danger"></i>
                                                        {/if}
                                                    </td>
                                                    <td class="text-center">
                                                        <a class="btn btn-secondary text-white" href="{$placeholder->leaderboard_settings_url}">
                                                            <i class="fas fa-cog"></i>
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="btn btn-danger text-white" onclick="showDeleteModal('{$placeholder->safe_name}', {$placeholder->server_id})">
                                                            {$DELETE}
                                                        </span>
                                                    </td>
                                                </tr>
                                            {/foreach}
                                        </tbody>
                                    </table>
                                </div>

                                <button type="submit" class="btn btn-primary">{$SUBMIT}</button>
                            </form>
                            {else}
                                {$NO_PLACEHOLDERS}
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
                        {$CONFIRM_DELETE_PLACEHOLDER}
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="deleteSafeName" value="">
                        <input type="hidden" id="deleteServerId" value="">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{$NO}</button>
                        <button type="button" onclick="deletePlaceholder()" class="btn btn-primary">{$YES}</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- End Wrapper -->
    </div>

    <script>
        const showDeleteModal = (placeholder_name, server_id) => {
            $('#deleteSafeName').attr('value', placeholder_name);
            $('#deleteServerId').attr('value', server_id);
            $('#deleteModal').modal().show();
        }

        const deletePlaceholder = () => {
            const placeholder_safe_name = $('#deleteSafeName').val();
            const server_id = $('#deleteServerId').val();
            const response = $.post("{$DELETE_LINK}", {
                placeholder_safe_name,
                server_id,
                action: 'delete',
                token: '{$TOKEN}',
            });
            response.done((r) => {
                r === 'Ok'
                    ? window.location.reload()
                    : console.error(r);
            });
        }
    </script>
    {include file='scripts.tpl'}

</body>

</html>
