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
                                    <label for="InputAPIURL">{$API_URL}</label>
                                    <div class="input-group">
                                        <input type="text" name="api_url" id="InputAPIURL" class="form-control" readonly value="{if $API_ENABLED}{$API_URL_VALUE}{else}{$ENABLE_API_FOR_URL}{/if}">
                                        {if $API_ENABLED}
                                            <span class="input-group-append">
                                                <a onclick="copyURL();" class="btn btn-info text-white" id="copy-url-button">
                                                    {$COPY}
                                                </a>
                                            </span>
                                        {/if}
                                    </div>
                                </div>

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

                                <div class="form-group custom-control custom-switch">
                                    <input type="hidden" name="enable_api" value="0">
                                    <input type="checkbox" class="custom-control-input" id="enable_api"
                                        name="enable_api" value="1" {if $API_ENABLED eq 1} checked{/if}>
                                    <label class="custom-control-label" for="enable_api">{$ENABLE_API}</label>
                                </div>

                                <div class="form-group custom-control custom-switch">
                                    <input name="username_sync" id="username_sync" type="checkbox"
                                        class="custom-control-input" {if $USERNAME_SYNC_VALUE eq 1} checked{/if} />
                                    <label class="custom-control-label" for="username_sync">{$USERNAME_SYNC}</label>
                                    <span class="badge badge-info">
                                        <i class="fas fa-question-circle" data-container="body" data-toggle="popover"
                                            data-placement="top" title="{$INFO}"
                                            data-content="{$USERNAME_SYNC_INFO}"></i></span>
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
            regen.done(() => window.location.reload());
        }

        function copyURL() {
            const url = document.getElementById("InputAPIURL");

            if (window.isSecureContext) {
                navigator.clipboard.writeText(url.value);
            } else {
                url.select();
                document.execCommand("copy");
                url.setSelectionRange(0, 0);
                url.blur();
            }

            const copyUrlButton = document.getElementById('copy-url-button');
            copyUrlButton.innerText = '{$COPIED}';
            copyUrlButton.classList.add('disabled');
        }
    </script>

</body>

</html>
