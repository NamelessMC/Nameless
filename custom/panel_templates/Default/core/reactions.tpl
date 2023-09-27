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
                        <h1 class="h3 mb-0 text-gray-800">{$REACTIONS}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                            <li class="breadcrumb-item active">{$REACTIONS}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='includes/update.tpl'}

                    <div class="card shadow mb-4">
                        <div class="card-body">

                            <a href="{$NEW_REACTION_LINK}" class="btn btn-primary">{$NEW_REACTION}</a>
                            <hr />

                            <!-- Success and Error Alerts -->
                            {include file='includes/alerts.tpl'}

                            {if count($REACTIONS_LIST)}
                                <div class="table-responsive">
                                    <table class="table table-borderless table-striped">
                                        <thead>
                                            <tr>
                                                <td>{$NAME}</td>
                                                <td>{$ICON}</td>
                                                <td>{$TYPE}</td>
                                                <td>{$ENABLED}</td>
                                            </tr>
                                        </thead>
                                        <tbody id="sortable">
                                            {foreach from=$REACTIONS_LIST item=reaction}
                                            <tr data-id="{$reaction.id}">
                                                <td><a href="{$reaction.edit_link}">{$reaction.name}</a></td>
                                                <td>{$reaction.html}</td>
                                                <td>{$reaction.type} {if $reaction.type_id eq 3}({$reaction.custom_score}){/if}</td>
                                                <td>
                                                    {if $reaction.enabled eq 1}
                                                        <i class="fa fa-check-circle fa-fw text-success"></i>
                                                    {else}
                                                        <i class="fa fa-times-circle fa-fw text-danger"></i>
                                                    {/if}
                                                </td>
                                            </tr>
                                            {/foreach}
                                        </tbody>
                                    </table>
                                </div>
                            {else}
                                {$NO_REACTIONS}
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

    <script type="text/javascript">
        $(document).ready(function () {
            $("#sortable").sortable({
                start: function (event, ui) {
                    let start_pos = ui.item.index();
                    ui.item.data('startPos', start_pos);
                },
                update: function (event, ui) {
                    let forums = $("#sortable").children();
                    let toSubmit = [];
                    forums.each(function () {
                        toSubmit.push($(this).data().id);
                    });

                    $.ajax({
                        url: "{$REORDER_DRAG_URL}",
                        type: "GET",
                        data: {
                            action: "order",
                            token: "{$TOKEN}",
                            {literal}reactions: JSON.stringify({"reactions": toSubmit}){/literal}
                        },
                        success: function (response) {
                            // Success
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
