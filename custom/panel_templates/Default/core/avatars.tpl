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
                        <h1 class="h3 mb-0 text-gray-800">{$AVATARS}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                            <li class="breadcrumb-item active">{$AVATARS}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='includes/update.tpl'}

                    <div class="card shadow mb-4">
                        <div class="card-body">

                            <!-- Success and Error Alerts -->
                            {include file='includes/alerts.tpl'}
                            <div class="card shadow border-left-primary">
                                <div class="card-body">
                                    <h5><i class="icon fa fa-info-circle"></i> {$INFO}</h5>
                                    {$AVATARS_INFO}
                                </div>
                            </div>
                            <br />

                            <form action="" method="post">
                                <table class="table table-borderless table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Module</th>
                                            <th>Enabled</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="sortable">
                                        {foreach $AVATAR_SOURCES as $source}
                                            <tr data-safe_name="{$source->getSafeName()}">
                                                <td>
                                                    {$source->getName()}
                                                </td>
                                                <td>
                                                    {$source->getModule()}
                                                </td>
                                                <td>
                                                    <div class="form-group custom-control custom-switch">
                                                        <input type="hidden" name="toggle[{$source->getSafeName()}]" value="0">
                                                        <input id="toggle[{$source->getSafeName()}]" name="toggle[{$source->getSafeName()}]" type="checkbox" class="custom-control-input" value="1" {if $source->isEnabled() || !$source->canBeDisabled() eq 1}checked{/if} {if !$source->canBeDisabled()}disabled{/if} />
                                                        <label for="toggle[{$source->getSafeName()}]" class="custom-control-label"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="float-md-right">
                                                        <div class="btn btn-secondary btn-sm">
                                                            <i class="fa fa-arrows-alt"></i>
                                                        </div>
                                                        {if $source->getSettings()}
                                                            <a href="{$source->getSettingsUrl()}" class="btn btn-info btn-sm">Settings</a>
                                                        {/if}
                                                    </div>
                                                </td>
                                            </tr>
                                        {/foreach}
                                    </tbody>
                                </table>
                                <div class="form-group">
                                    <input type="hidden" name="token" value="{$TOKEN}">
                                    <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
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

    <script>
        $(document).ready(function () {
            $("#sortable").sortable({
                start: function (event, ui) {
                    let start_pos = ui.item.index();
                    ui.item.data('startPos', start_pos);
                },
                update: function (event, ui) {
                    let sources = $("#sortable").children();
                    let toSubmit = [];
                    sources.each(function () {
                        toSubmit.push($(this).data().safe_name);
                    });

                    $.ajax({
                        url: "{$REORDER_DRAG_URL}",
                        type: "GET",
                        data: {
                            action: "order",
                            {literal}sources: JSON.stringify(toSubmit){/literal},
                            token: "{$TOKEN}"
                        },
                        success: function (response) {
                            // Success
                            if (response === 'Invalid Token') {
                                window.location.reload();
                            }
                        },
                        error: function (xhr) {
                            // Error
                            console.log(xhr);
                        }
                    });
                }
            });
        });
    </script>

</body>

</html>
