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
                        <h1 class="m-0 text-dark">{$QUERY_ERRORS}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$INTEGRATIONS}</li>
                            <li class="breadcrumb-item"><a href="{$MINECRAFT_LINK}">{$MINECRAFT}</a></li>
                            <li class="breadcrumb-item active">{$QUERY_ERRORS}</li>
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

                        <h5 style="display:inline">{$VIEWING_ERROR}</h5>
                        <div class="float-md-right">
                            <button onclick="showDeleteModal()" class="btn btn-danger">{$DELETE}</button>
                            <a class="btn btn-primary" href="{$BACK_LINK}">{$BACK}</a>
                        </div>

                        <hr />

                        {include file='includes/success.tpl'}

                        {include file='includes/errors.tpl'}

                        <ul>
                            <li>{$SERVER_ADDRESS}: <strong>{$SERVER_ADDRESS_VALUE}</strong></li>
                            <li>{$SERVER_PORT}: <strong>{$SERVER_PORT_VALUE}</strong></li>
                            <li>{$DATE}: <strong>{$DATE_VALUE}</strong></li>
                        </ul>

                        <pre class="error_log">{$ERROR_MESSAGE}</pre>

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
                    {$CONFIRM_DELETE_ERROR}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{$NO}</button>
                    <a href="{$DELETE_LINK}" class="btn btn-primary">{$YES}</a>
                </div>
            </div>
        </div>
    </div>

    {include file='footer.tpl'}

</div>
<!-- ./wrapper -->

{include file='scripts.tpl'}

<script type="text/javascript">
    function showDeleteModal(){
        $('#deleteModal').modal().show();
    }
</script>

</body>
</html>