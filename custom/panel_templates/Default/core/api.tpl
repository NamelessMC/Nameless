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
                    <h1 class="h3 mb-0 text-gray-800">{$API}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                        <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                        <li class="breadcrumb-item active">{$API}</li>
                    </ol>
                </div>

                <!-- Update Notification -->
                {include file='includes/update.tpl'}

                <div class="card shadow mb-4">
                    <div class="card-body">
                        <a class="btn btn-primary" href="{$API_ENDPOINTS_LINK}">{$API_ENDPOINTS}</a>
                        <a class="btn btn-primary" href="{$GROUP_SYNC_LINK}">{$GROUP_SYNC}</a>
                        <hr />

                        <!-- Success and Error Alerts -->
                        {include file='includes/alerts.tpl'}

                        <div class="card shadow border-left-primary">
                            <div class="card-body">
                                <h5><i class="icon fa fa-info-circle"></i> {$INFO}</h5>
                                {$API_INFO}
                            </div>
                        </div>
                        <br />
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="InputAPIKey">{$API_KEY}</label>
                                <div class="input-group">
                                    <input type="text" name="api_key" id="InputAPIKey" class="form-control" readonly
                                           value="{if $API_ENABLED}{$API_KEY_VALUE}{else}{$ENABLE_API_FOR_URL}{/if}">
                                    {if $API_ENABLED}
                                        <span class="input-group-append"><a onclick="showRegenModal();"
                                                                            class="btn btn-info text-white">{$CHANGE}</a></span>
                                    {/if}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="InputAPIURL">{$API_URL}</label>
                                <div class="input-group">
                                    <input type="text" name="api_url" id="InputAPIURL" class="form-control" readonly
                                           value="{if $API_ENABLED}{$API_URL_VALUE}{else}{$ENABLE_API_FOR_URL}{/if}">
                                    {if $API_ENABLED}
                                        <span class="input-group-append"><a onclick="copyURL();"
                                                                            class="btn btn-info text-white">{$COPY}</a></span>
                                    {/if}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="enable_api" style="margin-right:10px">{$ENABLE_API}</label>
                                <input type="hidden" name="enable_api" value="0">
                                <input id="enable_api" name="enable_api" type="checkbox"
                                       class="js-switch"
                                       value="1"{if $API_ENABLED eq 1} checked{/if} />
                            </div>

                            <div class="form-group">
                                <label for="verification" style="margin-right:10px">{$EMAIL_VERIFICATION}</label>
                                <input name="verification" id="verification" type="checkbox"
                                       class="js-switch"{if $EMAIL_VERIFICATION_VALUE eq 1} checked{/if} />
                            </div>

                            <div class="form-group">
                                <label for="api_verification">{$API_VERIFICATION}</label> <span class="badge badge-info"
                                                                                                style="margin-right:10px"
                                                                                                data-toggle="popover"
                                                                                                data-title="{$INFO}"
                                                                                                data-content="{$API_VERIFICATION_INFO}"><i
                                            class="fas fa-question-circle"></i></span>
                                <input name="api_verification" id="api_verification" type="checkbox"
                                       class="js-switch"{if $API_VERIFICATION_VALUE eq 1} checked{/if} />
                            </div>

                            <div class="form-group">
                                <label for="username_sync">{$USERNAME_SYNC}</label> <span class="badge badge-info"
                                                                                          style="margin-right:10px"
                                                                                          data-toggle="popover"
                                                                                          data-html="true"
                                                                                          data-title="{$INFO}"
                                                                                          data-content="{$USERNAME_SYNC_INFO}"><i
                                            class="fas fa-question-circle"></i></span>
                                <input name="username_sync" id="username_sync" type="checkbox"
                                       class="js-switch"{if $USERNAME_SYNC_VALUE eq 1} checked{/if} />
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

    <!-- Key regen modal -->
    <div class="modal fade" id="regenModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{$ARE_YOU_SURE}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {$CONFIRM_API_REGEN}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{$NO}</button>
                    <button type="button" onclick="regenKey()" class="btn btn-primary">{$YES}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- End Wrapper -->
</div>

{include file='scripts.tpl'}

<script type="text/javascript">
  function showRegenModal() {
    $('#regenModal').modal().show();
  }

  function regenKey() {
    const regen = $.post("{$API_KEY_REGEN_URL}", { action: 'regen', token: "{$TOKEN}" });
    regen.done(function() { window.location.reload(); })
  }

  function copyURL() {
    let url = document.getElementById("InputAPIURL");
    url.select();
    document.execCommand("copy");

    // Toastr
    toastr.options.progressBar = true;
    toastr.options.closeButton = true;
    toastr.options.positionClass = 'toast-bottom-left';
    toastr.success("{$COPIED}");
  }
</script>

</body>

</html>
