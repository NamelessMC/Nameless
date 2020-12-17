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
                        <h1 class="m-0 text-dark">{$LABELS}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$FORUM}</li>
                            <li class="breadcrumb-item active">{$LABELS}</li>
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
                        <a href="{$NEW_LABEL_LINK}" class="btn btn-primary">{$NEW_LABEL}</a>
                        <a href="{$LABEL_TYPES_LINK}" class="btn btn-info">{$LABEL_TYPES}</a>
                        <hr />

                        {include file='includes/success.tpl'}

                        {include file='includes/errors.tpl'}

                        <div class="card">
                            <div class="card-header">
                                {$LABELS}
                            </div>
                            <div class="card-body">
                                {if count($ALL_LABELS)}
                                    {foreach from=$ALL_LABELS item=label name=label_list}
                                        <div class="row">
                                            <div class="col-md-9">
                                                {$label.name}<br />
                                                {$label.enabled_forums}
                                            </div>
                                            <div class="col-md-3">
                                                <div class="float-md-right">
                                                    <a href="{$label.edit_link}" class="btn btn-info">{$EDIT}</a>
                                                    <button onclick="showDeleteModal('{$label.delete_link}')" class="btn btn-danger">{$DELETE}</button>
                                                </div>
                                            </div>
                                        </div>
                                        {if !$smarty.foreach.label_list.last}<hr />{/if}
                                    {/foreach}
                                {else}
                                    <p>{$NO_LABELS}</p>
                                {/if}
                            </div>
                        </div>

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
                    {$CONFIRM_DELETE}
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
    function showDeleteModal(link){
        $('#deleteLink').attr('href', link);
        $('#deleteModal').modal().show();
    }
</script>

</body>
</html>