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
                        <h1 class="h3 mb-0 text-gray-800">{$ANNOUNCEMENTS}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$ANNOUNCEMENTS}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='includes/update.tpl'}

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-9">
                                    <p style="margin-top: 7px; margin-bottom: 7px;">{$ANNOUCEMENTS_INFO}</p>
                                </div>
                                <div class="col-md-3">
                                    <span class="float-md-right"><a href="{$NEW_LINK}" class="btn btn-primary"><i class="fas fa-plus-circle"></i> {$NEW}</a></span>
                                </div>
                            </div>
                            {if isset($ALL_ANNOUNCEMENTS)}
                            <hr />{else}<br />{/if}

                            <!-- Success and Error Alerts -->
                            {include file='includes/alerts.tpl'}

                            {if isset($ALL_ANNOUNCEMENTS)}
                            <div class="table-responsive">
                                <table class="table table-borderless table-striped">
                                    <thead>
                                        <tr>
                                            <th>{$HEADER}</th>
                                            <th>{$PAGES}</th>
                                            <th>{$TEXT_COLOUR}</th>
                                            <th>{$BACKGROUND_COLOUR}</th>
                                            <th>{$ACTIONS}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {foreach from=$ALL_ANNOUNCEMENTS item=announcement}
                                        <tr>
                                            <td>{$announcement[0]->header}</td>
                                            <td>{if count($announcement['pages']) gt 0}{$announcement['pages']}{else}<i>{$NONE}</i>{/if}</td>
                                            <td><span class="badge border" style="display: inline-block; width: 50px; height: 25px; background-color: {$announcement[0]->text_colour};" title="{$announcement[0]->text_colour}"></span></td>
                                            <td><span class="badge border" style="display: inline-block; width: 50px; height: 25px; background-color: {$announcement[0]->background_colour}; color:#ffffff;" title="{$announcement[0]->background_colour}"></span></td>
                                            <td>
                                                <a href="{$EDIT_LINK}{$announcement[0]->id}" class="btn btn-warning btn-sm"><i class="fa fa-fw fa-edit"></i></a>
                                                <a href="#" onclick="showDeleteModal({$announcement[0]->id})" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        {/foreach}
                                    </tbody>
                                </table>
                            </div>
                            {else}
                                {$NO_ANNOUNCEMENTS}
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
                        {$CONFIRM_DELETE_ANNOUNCEMENT}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{$NO}</button>
                        <a href="{$DELETE_LINK}" id="deleteLink" class="btn btn-primary">{$YES}</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- End Wrapper -->
    </div>

    {include file='scripts.tpl'}

    <script type="text/javascript">
        function showDeleteModal(id) {
                       $('#deleteLink').attr('href', '{$DELETE_LINK}'.replace('{literal}{x}{/literal}', id));
            $('#deleteModal').modal().show();
        }
    </script>

</body>

</html>