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
                        <h1 class="m-0 text-dark">{$HOOKS}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                            <li class="breadcrumb-item active">{$HOOKS}</li>
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
                        <p style="display:inline;">{$HOOKS_INFO}</p>
                        <span class="float-md-right"><a href="{$NEW_HOOK_LINK}" class="btn btn-primary"><i class="fas fa-plus-circle"></i> {$NEW_HOOK}</a></span>
                        <hr />

                        {include file='includes/success.tpl'}

                        {include file='includes/errors.tpl'}

                        {if count($HOOKS_LIST)}
                            {foreach from=$HOOKS_LIST item=hook name=hook_list}
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
                                                            <a href="{$hook.edit_link}" class="btn btn-warning btn-sm"><i class="fas fa-edit fa-fw"></i></a>
                                                            <button class="btn btn-danger btn-sm" type="button" onclick="showDeleteModal('{$hook.delete_link}')"><i class="fas fa-trash fa-fw"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            {/foreach}
                                        </tbody>
                                    </table>
                                </div>
                            {/foreach}
                        {else}
                            {$NO_HOOKS}
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
                    {$CONFIRM_DELETE_HOOK}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{$NO}</button>
                    <a href="#" id="deleteLink" class="btn btn-primary">{$YES}</a>
                </div>
            </div>
        </div>
    </div>

    {include file='footer.tpl'}

</div>
<!-- ./wrapper -->

{include file='scripts.tpl'}

<script type="text/javascript">
    function showDeleteModal(id){
        $('#deleteLink').attr('href', id);
        $('#deleteModal').modal().show();
    }
</script>

</body>
</html>