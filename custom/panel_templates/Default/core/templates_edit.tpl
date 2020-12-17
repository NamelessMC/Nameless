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
                        <h1 class="m-0 text-dark">{$TEMPLATES}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$LAYOUT}</li>
                            <li class="breadcrumb-item active">{$TEMPLATES}</li>
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

                        <h5 style="display:inline">{$EDITING_FILE}</h5>
                        <div class="float-md-right">
                            <button class="btn btn-warning" role="button" onclick="showCancelModal();">{$CANCEL}</button>
                        </div>
                        <hr />

                        {include file='includes/success.tpl'}

                        {include file='includes/errors.tpl'}

                        {if isset($DEFAULT_TEMPLATE_WARNING)}
                            <div class="alert alert-warning">{$DEFAULT_TEMPLATE_WARNING}</div>
                        {/if}

                        <form action="" method="post">
                            <div class="form-group">
                                <textarea id="code" name="code">{$FILE_CONTENTS}</textarea>
                            </div>
                            <br />
                            <div class="form-group">
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
                            </div>
                        </form>

                    </div>
                </div>

                <!-- Spacing -->
                <div style="height:1rem;"></div>

            </div>
        </section>
    </div>

    <!-- Cancel modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{$ARE_YOU_SURE}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {$CONFIRM_CANCEL}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{$NO}</button>
                    <a href="{$CANCEL_LINK}" class="btn btn-primary">{$YES}</a>
                </div>
            </div>
        </div>
    </div>

    {include file='footer.tpl'}

</div>
<!-- ./wrapper -->

{include file='scripts.tpl'}

<script type="text/javascript">
    function showCancelModal(){
        $('#cancelModal').modal().show();
    }

    var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
        lineNumbers: true,
        mode: "{$FILE_TYPE}"
    });
    editor.setSize(null, 400);

</script>

</body>
</html>