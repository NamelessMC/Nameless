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
                        <h1 class="m-0 text-dark">{$API}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                            <li class="breadcrumb-item active">{$API}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                {if isset($NEW_UPDATE)}
                {if $NEW_UPDATE_URGENT eq true}
                <div class="alert alert-danger">
                    {else}
                    <div class="alert alert-primary alert-dismissible" id="updateAlert">
                        <button type="button" class="close" id="closeUpdate" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {/if}
                        {$NEW_UPDATE}
                        <br />
                        <a href="{$UPDATE_LINK}" class="btn btn-primary" style="text-decoration:none">{$UPDATE}</a>
                        <hr />
                        {$CURRENT_VERSION}<br />
                        {$NEW_VERSION}
                    </div>
                    {/if}

                    <div class="card">
                        <div class="card-body">
                            <a class="btn btn-primary" href="{$GROUP_SYNC_LINK}">{$GROUP_SYNC}</a>
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

                            <div class="callout callout-info">
                                <h5><i class="icon fa fa-info-circle"></i> {$INFO}</h5>
                                {$API_INFO}
                            </div>

                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="InputAPIKey">{$API_KEY}</label>
                                    <div class="input-group">
                                        <input type="text" name="api_key" id="InputAPIKey" class="form-control" readonly value="{$API_KEY_VALUE}">
                                        <span class="input-group-append"><a onclick="showRegenModal();" class="btn btn-info text-white">{$CHANGE}</a></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="InputAPIURL">{$API_URL}</label>
                                    <div class="input-group">
                                        <input type="text" name="api_url" id="InputAPIURL" class="form-control" readonly value="{$API_URL_VALUE}">
                                        <span class="input-group-append"><a onclick="copyURL();" class="btn btn-info text-white">{$COPY}</a></span>
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
                                    <label for="enable_legacy_api">{$ENABLE_LEGACY_API}</label> <span class="badge badge-info" style="margin-right:10px" data-toggle="popover" data-title="{$INFO}" data-content="{$LEGACY_API_INFO}"><i class="fas fa-question-circle"></i></span>
                                    <input type="hidden" name="enable_legacy_api" value="0">
                                    <input id="enable_legacy_api" name="enable_legacy_api" type="checkbox"
                                           class="js-switch"
                                           value="1"{if $LEGACY_API_ENABLED eq 1} checked{/if} />
                                </div>

                                <div class="form-group">
                                    <label for="verification" style="margin-right:10px">{$EMAIL_VERIFICATION}</label>
                                    <input name="verification" id="verification" type="checkbox" class="js-switch"{if $EMAIL_VERIFICATION_VALUE eq 1} checked{/if} />
                                </div>

                                <div class="form-group">
                                    <label for="api_verification">{$API_VERIFICATION}</label> <span class="badge badge-info" style="margin-right:10px" data-toggle="popover" data-title="{$INFO}" data-content="{$API_VERIFICATION_INFO}"><i class="fas fa-question-circle"></i></span>
                                    <input name="api_verification" id="api_verification" type="checkbox" class="js-switch"{if $API_VERIFICATION_VALUE eq 1} checked{/if} />
                                </div>

                                <div class="form-group">
                                    <label for="username_sync">{$USERNAME_SYNC}</label> <span class="badge badge-info" style="margin-right:10px" data-toggle="popover" data-html="true" data-title="{$INFO}" data-content="{$USERNAME_SYNC_INFO}"><i class="fas fa-question-circle"></i></span>
                                    <input name="username_sync" id="username_sync" type="checkbox" class="js-switch"{if $USERNAME_SYNC_VALUE eq 1} checked{/if} />
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

                </div>
        </section>
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
                    <a href="{$API_KEY_REGEN_URL}" class="btn btn-primary">{$YES}</a>
                </div>
            </div>
        </div>
    </div>

    {include file='footer.tpl'}

</div>
<!-- ./wrapper -->

{include file='scripts.tpl'}

<script type="text/javascript">
    function showRegenModal(){
        $('#regenModal').modal().show();
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