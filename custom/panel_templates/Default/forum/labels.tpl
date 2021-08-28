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
                    <h1 class="h3 mb-0 text-gray-800">{$LABELS}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                        <li class="breadcrumb-item active">{$FORUM}</li>
                        <li class="breadcrumb-item active">{$LABELS}</li>
                    </ol>
                </div>

                <!-- Update Notification -->
                {include file='includes/update.tpl'}

                <div class="card shadow mb-4">
                    <div class="card-body">

                        <a href="{$NEW_LABEL_LINK}" class="btn btn-primary">{$NEW_LABEL}</a>
                        <a href="{$LABEL_TYPES_LINK}" class="btn btn-info">{$LABEL_TYPES}</a>
                        <hr />

                        <!-- Success and Error Alerts -->
                        {include file='includes/alerts.tpl'}

                        {if count($ALL_LABELS)}
                            {foreach from=$ALL_LABELS item=label name=label_list}
                                <div class="row">
                                    <div class="col-md-9">
                                        {$label.name}<br />
                                        {$label.enabled_forums}
                                    </div>
                                    <div class="col-md-3">
                                        <div class="float-md-right">
                                            <a href="{$label.edit_link}" class="btn btn-info btn-sm">{$EDIT}</a>
                                            <button onclick="showDeleteModal('{$label.delete_link}')"
                                                    class="btn btn-danger btn-sm">{$DELETE}</button>
                                        </div>
                                    </div>
                                </div>
                                {if !$smarty.foreach.label_list.last}
                                    <hr />
                                {/if}
                            {/foreach}
                        {else}
                            <p>{$NO_LABELS}</p>
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
                    {$CONFIRM_DELETE}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{$NO}</button>
                    <form action="" id="deleteForm" method="post" style="display: inline">
                        <input type="hidden" name="token" value="{$TOKEN}" />
                        <input type="submit" class="btn btn-primary" value="{$YES}" />
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- End Wrapper -->
</div>

{include file='scripts.tpl'}

<script type="text/javascript">
  function showDeleteModal(link) {
    $('#deleteForm').attr('action', link);
    $('#deleteModal').modal().show();
  }
</script>

</body>

</html>