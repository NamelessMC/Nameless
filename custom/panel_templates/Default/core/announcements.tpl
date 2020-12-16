{include file='header.tpl'}

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        {include file='navbar.tpl'}
        {include file='sidebar.tpl'}

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">{$ANNOUNCEMENTS}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                                <li class="breadcrumb-item active">{$ANNOUNCEMENTS}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    {include file='includes/update.tpl'}

                    <div class="card">
                        <div class="card-body">
                            <p style="display:inline;">{$ANNOUCEMENTS_INFO}</p>
                            <span class="float-md-right"><a href="{$NEW_LINK}" class="btn btn-primary"><i class="fas fa-plus-circle"></i> {$NEW}</a></span>
                            <hr />
                            {if isset($SUCCESS)}
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h5><i class="icon fa fa-check"></i> {$SUCCESS_TITLE}</h5>
                                {$SUCCESS}
                            </div>
                            {/if}

                            {if isset($ERRORS) && count($ERRORS)}
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h5><i class="icon fas fa-exclamation-triangle"></i> {$ERRORS_TITLE}</h5>
                                <ul>
                                    {foreach from=$ERRORS item=error}
                                    <li>{$error}</li>
                                    {/foreach}
                                </ul>
                            </div>
                            {/if}

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
                                            <td>{$announcement['pages']}</td>
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

                </div>
            </section>
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

        {include file='footer.tpl'}

    </div>
    <!-- ./wrapper -->

    {include file='scripts.tpl'}
    <script type="text/javascript">
        function showDeleteModal(id) {
            $('#deleteLink').attr('href', '{$DELETE_LINK}'.replace('{literal}{x}{/literal}', id));
            $('#deleteModal').modal().show();
        }
    </script>

</body>

</html>