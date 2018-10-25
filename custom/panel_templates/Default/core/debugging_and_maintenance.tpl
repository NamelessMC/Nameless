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
                        <h1 class="m-0 text-dark">{$DEBUGGING_AND_MAINTENANCE}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                            <li class="breadcrumb-item active">{$DEBUGGING_AND_MAINTENANCE}</li>
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

                            {if isset($ERROR_LOGS)}
                                <a href="{$ERROR_LOGS_LINK}" class="btn btn-primary">{$ERROR_LOGS}</a>
                                <hr />
                            {/if}

                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="InputDebug">{$ENABLE_DEBUG_MODE}</label>
                                    <input id="InputDebug" name="enable_debugging" type="checkbox" class="js-switch"
                                           value="1"{if $ENABLE_DEBUG_MODE_VALUE eq 1} checked{/if} />
                                </div>
                                <div class="form-group">
                                    <label for="InputMaintenance">{$ENABLE_MAINTENANCE_MODE}</label>
                                    <input id="InputMaintenance" name="enable_maintenance" type="checkbox"
                                           class="js-switch"
                                           value="1"{if $ENABLE_MAINTENANCE_MODE_VALUE eq 1} checked{/if} />
                                </div>
                                <div class="form-group">
                                    <label for="InputPageLoad">{$ENABLE_PAGE_LOAD_TIMER}</label>
                                    <input id="InputPageLoad" name="enable_page_load_timer" type="checkbox"
                                           class="js-switch"
                                           value="1"{if $ENABLE_PAGE_LOAD_TIMER_VALUE eq 1} checked{/if} />
                                </div>
                                <div class="form-group">
                                    <label for="inputMaintenanceMessage">{$MAINTENANCE_MODE_MESSAGE}</label>
                                    <textarea style="width:100%" rows="10" name="message"
                                              id="InputMaintenanceMessage">{$MAINTENANCE_MODE_MESSAGE_VALUE}</textarea>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="token" value="{$TOKEN}">
                                    <input type="submit" value="{$SUBMIT}"
                                           class="btn btn-primary">
                                </div>
                            </form>

                        </div>
                    </div>

                    <!-- Spacing -->
                    <div style="height:1rem;"></div>

                </div>
        </section>
    </div>

    {include file='footer.tpl'}

</div>
<!-- ./wrapper -->

{include file='scripts.tpl'}

</body>
</html>