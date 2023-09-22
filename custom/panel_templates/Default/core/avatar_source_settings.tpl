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

                            <div class="row" style="margin-bottom: 15px;">
                                <div class="col-md-9">
                                    <h5 style="margin-top: 7px; margin-bottom: 7px;">{$EDITING_AVATAR_SOURCE}</h5>
                                </div>
                                <div class="col-md-3">
                                    <span class="float-md-right">
                                        <a href="{$BACK_LINK}" class="btn btn-primary">{$BACK}</a>
                                    </span>
                                </div>
                            </div>
                            <hr />

                            <!-- Success and Error Alerts -->
                            {include file='includes/alerts.tpl'}

                            {include file=$SETTINGS_TEMPLATE}
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

        <!-- Modal -->
        <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadModalLabel">{$UPLOAD_NEW_IMAGE}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="{$CLOSE}">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Upload modal -->
                        <form action="{$UPLOAD_FORM_ACTION}" class="dropzone" id="upload_avatar_dropzone">
                            <div class="dz-message" data-dz-message>
                                <span>{$DRAG_FILES_HERE}</span>
                            </div>
                            <input type="hidden" name="token" value="{$TOKEN}">
                            <input type="hidden" name="type" value="default_avatar">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="location.reload();"
                                data-dismiss="modal">{$CLOSE}</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- End Wrapper -->
    </div>

    {include file='scripts.tpl'}

</body>

</html>
