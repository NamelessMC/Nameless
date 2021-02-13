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
                    <h1 class="h3 mb-0 text-gray-800">{$DEBUGGING_AND_MAINTENANCE}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                        <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                        <li class="breadcrumb-item active">{$DEBUGGING_AND_MAINTENANCE}</li>
                    </ol>
                </div>

                <!-- Update Notification -->
                {include file='includes/update.tpl'}

                <div class="card shadow mb-4">
                    <div class="card-body">
                        {if isset($ERROR_LOGS)}
                            <a href="{$ERROR_LOGS_LINK}" class="btn btn-primary">{$ERROR_LOGS}</a>
                            <hr />
                        {/if}

                        <!-- Success and Error Alerts -->
                        {include file='includes/alerts.tpl'}

                        <form action="" method="post">
                            <div class="form-group">
                                <label for="InputDebug">{$ENABLE_DEBUG_MODE}</label>
                                <input id="InputDebug" name="enable_debugging" type="checkbox" class="js-switch"
                                       value="1" {if $ENABLE_DEBUG_MODE_VALUE eq 1} checked{/if} />
                            </div>
                            <div class="form-group">
                                <label for="InputMaintenance">{$ENABLE_MAINTENANCE_MODE}</label>
                                <input id="InputMaintenance" name="enable_maintenance" type="checkbox" class="js-switch"
                                       value="1" {if $ENABLE_MAINTENANCE_MODE_VALUE eq 1} checked{/if} />
                            </div>
                            <div class="form-group">
                                <label for="InputPageLoad">{$ENABLE_PAGE_LOAD_TIMER}</label>
                                <input id="InputPageLoad" name="enable_page_load_timer" type="checkbox"
                                       class="js-switch"
                                       value="1" {if $ENABLE_PAGE_LOAD_TIMER_VALUE eq 1} checked{/if} />
                            </div>
                            <div class="form-group">
                                <label for="inputMaintenanceMessage">{$MAINTENANCE_MODE_MESSAGE}</label>
                                <textarea style="width:100%" rows="10" name="message"
                                          id="InputMaintenanceMessage">{$MAINTENANCE_MODE_MESSAGE_VALUE}</textarea>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <input type="submit" value="{$SUBMIT}" class="btn btn-primary">
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