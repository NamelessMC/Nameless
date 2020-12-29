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
                    <h1 class="h3 mb-0 text-gray-800">{$EMAIL_ERRORS}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                        <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                        <li class="breadcrumb-item"><a href="{$EMAILS_LINK}">{$EMAILS}</a></li>
                        <li class="breadcrumb-item active">{$EMAIL_ERRORS}</li>
                    </ol>
                </div>

                <!-- Update Notification -->
                {include file='includes/update.tpl'}

                <div class="card shadow mb-4">
                    <div class="card-body">
                        <a class="btn btn-primary" href="{$BACK_LINK}">{$BACK}</a>
                        <hr />

                        <!-- Success and Error Alerts -->
                        {include file='includes/alerts.tpl'}

                        <h5>{$VIEWING_ERROR}</h5>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <td>{$TYPE}</td>
                                    <td>{$TYPE_VALUE}</td>
                                </tr>
                                <tr>
                                    <td>{$DATE}</td>
                                    <td>{$DATE_VALUE}</td>
                                </tr>
                                <tr>
                                    <td>{$USERNAME}</td>
                                    <td>{$USERNAME_VALUE}</td>
                                </tr>
                                <tr>
                                    <td>{$CONTENT}</td>
                                    <td>{$CONTENT_VALUE}</td>
                                </tr>
                            </table>
                        </div>

                        <h5>{$ACTIONS}</h5>

                        {if $TYPE_ID eq 1}
                            {if isset($VALIDATE_USER_TEXT)}
                                <a class="btn btn-secondary" href="{$VALIDATE_USER_LINK}">{$VALIDATE_USER_TEXT}</a>
                            {/if}
                        {elseif $TYPE_ID eq 4}
                            {if isset($SHOW_REGISTRATION_LINK)}
                                <button class="btn btn-secondary" type="button"
                                        onclick="showRegistrationModal()">{$SHOW_REGISTRATION_LINK}</button>
                            {/if}
                        {/if}

                        <a class="btn btn-danger" href="#" onclick="showDeleteModal()">{$DELETE_ERROR}</a>
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


    <!-- Show registration link modal -->
    <div class="modal fade" id="registrationModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{$REGISTRATION_LINK}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {$REGISTRATION_LINK_VALUE}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">{$CLOSE}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete error modal -->
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
                    <a href="{$DELETE_ERROR_LINK}" class="btn btn-primary">{$YES}</a>
                </div>
            </div>
        </div>
    </div>

    <!-- End Wrapper -->
</div>

{include file='scripts.tpl'}

<script type="text/javascript">
  function showDeleteModal() {
    $('#deleteModal').modal().show();
  }

  function showRegistrationModal() {
    $('#registrationModal').modal().show();
  }
</script>

</body>

</html>