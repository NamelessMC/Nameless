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
                    <h1 class="h3 mb-0 text-gray-800">{$PROFILE_FIELDS}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                        <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                        <li class="breadcrumb-item active">{$PROFILE_FIELDS}</li>
                    </ol>
                </div>

                <!-- Update Notification -->
                {include file='includes/update.tpl'}

                <div class="card shadow mb-4">
                    <div class="card-body">

                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-9">
                                <h5 style="margin-top: 7px; margin-bottom: 7px;">{$CREATING_PROFILE_FIELD}</h5>
                            </div>
                            <div class="col-md-3">
                                    <span class="float-md-right">
                                        <a class="btn btn-warning text-white" onclick="showCancelModal()">{$CANCEL}</a>
                                    </span>
                            </div>
                        </div>
                        <hr />

                        <!-- Success and Error Alerts -->
                        {include file='includes/alerts.tpl'}

                        <form action="" method="post">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="inputFieldName">{$FIELD_NAME}</label>
                                        <input type="text" class="form-control" id="inputFieldName" name="name"
                                               placeholder="{$FIELD_NAME}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="inputFieldType">{$TYPE}</label>
                                        <select class="form-control" name="type" id="inputFieldType">
                                            {foreach from=$TYPES key=key item=item}
                                                <option value="{$key}">{$item}</option>
                                            {/foreach}
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputDescription">{$DESCRIPTION}</label>
                                <textarea class="form-control" id="inputDescription" name="description"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="inputRequired">{$REQUIRED}</label>
                                <span class="badge badge-info" style="margin-right:10px"><i
                                            class="fas fa-question-circle" data-container="body" data-toggle="popover"
                                            title="{$INFO}" data-content="{$REQUIRED_HELP}"></i></span>
                                <input type="checkbox" id="inputRequired" name="required" class="js-switch" />
                            </div>

                            <div class="form-group">
                                <label for="inputEditable">{$EDITABLE}</label>
                                <span class="badge badge-info" style="margin-right:10px"><i
                                            class="fas fa-question-circle" data-container="body" data-toggle="popover"
                                            title="{$INFO}" data-content="{$EDITABLE_HELP}"></i></span>
                                <input type="checkbox" id="inputEditable" name="editable" class="js-switch" />
                            </div>

                            <div class="form-group">
                                <label for="inputPublic">{$PUBLIC}</label>
                                <span class="badge badge-info" style="margin-right:10px"><i
                                            class="fas fa-question-circle" data-container="body" data-toggle="popover"
                                            title="{$INFO}" data-content="{$PUBLIC_HELP}"></i></span>
                                <input type="checkbox" id="inputPublic" name="public" class="js-switch" />
                            </div>

                            <div class="form-group">
                                <label for="inputForum">{$DISPLAY_FIELD_ON_FORUM}</label>
                                <span class="badge badge-info"><i class="fas fa-question-circle" data-container="body"
                                                                  data-toggle="popover" title="{$INFO}"
                                                                  data-content="{$DISPLAY_FIELD_ON_FORUM_HELP}"></i></span>
                                <input type="checkbox" id="inputForum" name="forum" class="js-switch" />
                            </div>

                            <div class="form-group">
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
                            </div>
                        </form>

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

    <!-- End Wrapper -->
</div>

{include file='scripts.tpl'}


<script type="text/javascript">
  function showCancelModal() {
    $('#cancelModal').modal().show();
  }
</script>

</body>

</html>