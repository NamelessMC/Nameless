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
                    <h1 class="h3 mb-0 text-gray-800">{$MINECRAFT_SERVERS}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                        <li class="breadcrumb-item active">{$INTEGRATIONS}</li>
                        <li class="breadcrumb-item"><a href="{$MINECRAFT_LINK}">{$MINECRAFT}</a></li>
                        <li class="breadcrumb-item active">{$MINECRAFT_SERVERS}</li>
                    </ol>
                </div>

                <!-- Update Notification -->
                {include file='includes/update.tpl'}

                <div class="card shadow mb-4">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-9">
                                <h5 style="margin-top: 7px; margin-bottom: 7px;">{$ADDING_SERVER}</h5>
                            </div>
                            <div class="col-md-3">
                                <span class="float-md-right"><button onclick="showCancelModal()"
                                                                     class="btn btn-warning">{$CANCEL}</button></span>
                            </div>
                        </div>
                        <hr />

                        <!-- Success and Error Alerts -->
                        {include file='includes/alerts.tpl'}

                        <form action="" method="post">
                            <div class="card shadow border-left-primary">
                                <div class="card-body">
                                    {$SERVER_INFORMATION}
                                </div>
                            </div>
                            <br />
                            <div class="form-group">
                                <label for="InputName">{$SERVER_NAME}</label>
                                <input name="server_name" placeholder="{$SERVER_NAME}" id="InputName"
                                       value="{$SERVER_NAME_VALUE}" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="InputAddress">{$SERVER_ADDRESS}</label> <span class="badge badge-info"><i
                                            class="fa fa-question-circle" data-container="body" data-toggle="popover"
                                            data-placement="top" title="{$INFO}"
                                            data-content="{$SERVER_ADDRESS_INFO}"></i></span>
                                <input name="server_address" placeholder="{$SERVER_ADDRESS}" id="InputAddress"
                                       value="{$SERVER_ADDRESS_VALUE}" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="inputPort">{$SERVER_PORT}</label> <span class="badge badge-info"><i
                                            class="fa fa-question-circle" data-container="body" data-toggle="popover"
                                            data-placement="top" title="{$INFO}" data-content="{$SERVER_PORT_INFO}"></i></span>
                                <input name="server_port" placeholder="{$SERVER_PORT}" id="inputPort" value="25565"
                                       class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="InputParentServer">{$PARENT_SERVER}</label> <span
                                        class="badge badge-info"><i class="fa fa-question-circle" data-container="body"
                                                                    data-toggle="popover" data-placement="top"
                                                                    title="{$INFO}"
                                                                    data-content="{$PARENT_SERVER_INFO}"></i></span>
                                <select id="InputParentServer" class="form-control" name="parent_server">
                                    <option value="none" selected>{$NO_PARENT_SERVER}</option>
                                    {if count($AVAILABLE_PARENT_SERVERS)}
                                        {foreach from=$AVAILABLE_PARENT_SERVERS item=available_server}
                                            <option value="{$available_server->id|escape}">{$available_server->name|escape}</option>
                                        {/foreach}
                                    {/if}
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="inputBungeeInstance">{$BUNGEE_INSTANCE}</label> <span
                                        class="badge badge-info"><i class="fa fa-question-circle" data-container="body"
                                                                    data-toggle="popover" data-placement="top"
                                                                    title="{$INFO}"
                                                                    data-content="{$BUNGEE_INSTANCE_INFO}"></i></span>
                                <input type="hidden" name="bungee_instance" value="0">
                                <input id="inputBungeeInstance" name="bungee_instance" type="checkbox" class="js-switch"
                                       value="1" />
                            </div>

                            <div class="form-group">
                                <label for="inputPre17">{$PRE_17}</label>
                                <input type="hidden" name="pre_17" value="0">
                                <input id="inputPre17" name="pre_17" type="checkbox" class="js-switch" value="1" />
                            </div>

                            <div class="card shadow border-left-primary">
                                <div class="card-body">
                                    {$QUERY_INFORMATION}
                                </div>
                            </div>
                            <br />
                            <div class="form-group">
                                <label for="inputStatusQueryEnabled">{$ENABLE_STATUS_QUERY}</label> <span
                                        class="badge badge-info"><i class="fa fa-question-circle" data-container="body"
                                                                    data-toggle="popover" data-placement="top"
                                                                    data-html="true" title="{$INFO}"
                                                                    data-content="{$ENABLE_STATUS_QUERY_INFO}"></i></span>
                                <input type="hidden" name="status_query_enabled" value="0">
                                <input id="inputStatusQueryEnabled" name="status_query_enabled" type="checkbox"
                                       class="js-switch" value="1" />
                            </div>
                            <div class="form-group">
                                <label for="inputShowIPOnStatus">{$SHOW_IP_ON_STATUS_PAGE}</label> <span
                                        class="badge badge-info"><i class="fa fa-question-circle" data-container="body"
                                                                    data-toggle="popover" data-placement="top"
                                                                    data-html="true" title="{$INFO}"
                                                                    data-content="{$SHOW_IP_ON_STATUS_PAGE_INFO}"></i></span>
                                <input type="hidden" name="show_ip_enabled" value="0">
                                <input id="inputShowIPOnStatus" name="show_ip_enabled" type="checkbox" class="js-switch"
                                       value="1" />
                            </div>
                            {if isset($SERVER_QUERY_INFORMATION)}
                                <div class="card shadow border-left-primary">
                                    <div class="card-body">
                                        <h5><i class="icon fa fa-info-circle"></i> {$INFO}</h5>
                                        {$SERVER_QUERY_INFORMATION}
                                    </div>
                                </div>
                                <br />
                            {/if}
                            <div class="form-group">
                                <label for="inputQueryEnabled">{$ENABLE_PLAYER_LIST}</label> <span
                                        class="badge badge-info"><i class="fa fa-question-circle" data-container="body"
                                                                    data-toggle="popover" data-placement="top"
                                                                    data-html="true" title="{$INFO}"
                                                                    data-content="{$ENABLE_PLAYER_LIST_INFO}"></i></span>
                                <input type="hidden" name="query_enabled" value="0">
                                <input id="inputQueryEnabled" name="query_enabled" type="checkbox" class="js-switch"
                                       value="1" />
                            </div>
                            <div class="form-group">
                                <label for="inputQueryPort">{$SERVER_QUERY_PORT}</label> <span class="badge badge-info"><i
                                            class="fa fa-question-circle" data-container="body" data-toggle="popover"
                                            data-placement="top" data-html="true" title="{$INFO}"
                                            data-content="{$SERVER_QUERY_PORT_INFO}"></i></span>
                                <input name="query_port" placeholder="{$SERVER_QUERY_PORT}" id="inputQueryPort"
                                       value="25565" class="form-control">
                            </div>
                            <hr />
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