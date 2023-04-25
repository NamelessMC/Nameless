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
                        <h1 class="h3 mb-0 text-gray-800">{$HOOKS}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                            <li class="breadcrumb-item active">{$HOOKS}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='includes/update.tpl'}

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="row" style="margin-bottom: 10px;">
                                <div class="col-md-9">
                                    <h5 style="margin-top: 7px; margin-bottom: 7px;">{$CREATING_NEW_HOOK}</h5>
                                </div>
                                <div class="col-md-3">
                                    <span class="float-md-right">
                                        <a class="btn btn-primary" href="{$BACK_LINK}">{$BACK}</a>
                                    </span>
                                </div>
                            </div>
                            <hr />

                            <!-- Success and Error Alerts -->
                            {include file='includes/alerts.tpl'}

                            <form role="form" action="" method="post">
                                <div class="form-group">
                                    <label for="InputName">{$HOOK_NAME}</label>
                                    <input type="text" name="hook_name" class="form-control" id="InputName"
                                        value="{$HOOK_NAME_VALUE}" placeholder="{$HOOK_NAME}">
                                </div>
                                <div class="form-group">
                                    <label for="InputURL">{$HOOK_URL}</label>
                                    <input type="text" name="hook_url" class="form-control" id="InputURL"
                                        placeholder="https://example.com/examplelistener">
                                </div>
                                <div class="form-group">
                                    <label for="link_location">{$HOOK_TYPE}</label>
                                    <select class="form-control" id="hook_type" name="hook_type">
                                        <option value="2">{$DISCORD}</option>
                                        <option value="1">{$NORMAL}</option>
                                    </select>
                                </div>
                                <label for="InputName">{$HOOK_EVENTS}</label>
                                {foreach from=$ALL_EVENTS key=key item=meta}
                                    <div class="form-group custom-control custom-switch">
                                        <input type="checkbox" id="inputevents[{$key|escape}]" name="events[{$key|escape}]" class="custom-control-input">
                                        <label class="custom-control-label" for="inputevents[{$key|escape}]">
                                            {$meta.description|escape}
                                            {if $meta.supports_discord}
                                                <span data-toggle="tooltip" data-original-title="{$SUPPORTS_DISCORD}">
                                                    <i class="fab fa-discord"></i>
                                                </span>
                                            {/if}
                                            {if $meta.supports_normal}
                                                <span data-toggle="tooltip" data-original-title="{$SUPPORTS_NORMAL}">
                                                    <i class="fas fa-globe"></i>
                                                </span>
                                            {/if}
                                        </label>
                                    </div>
                                {/foreach}
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

        <!-- End Wrapper -->
    </div>

    {include file='scripts.tpl'}

</body>

</html>
