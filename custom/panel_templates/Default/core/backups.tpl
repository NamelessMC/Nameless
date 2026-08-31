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
                            <li class="breadcrumb-item"><a href="{$BACK_LINK}">{$DEBUGGING_AND_MAINTENANCE}</a></li>
                            <li class="breadcrumb-item active">{$BACKUPS}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='includes/update.tpl'}

                    <div class="card shadow mb-4">
                        <div class="card-body">

                            <h5 style="display: inline-block; margin-top: 7px; margin-bottom: 7px;">{$BACKUPS}</h5>

                            <div class="float-right">
                                <a href="{$CREATE_BACKUP_LINK}" class="btn btn-success">{$CREATE_BACKUP}</a>
                                <a href="{$BACK_LINK}" class="btn btn-primary">{$BACK}</a>
                            </div>
                            <hr />

                            <!-- Success and Error Alerts -->
                            {include file='includes/alerts.tpl'}

                            <div class="card shadow border-left-primary">
                                <div class="card-body">
                                    <h5><i class="icon fa fa-info-circle"></i> {$INFO}</h5>
                                    {$BACKUPS_INFO}
                                </div>
                            </div>

                            <br />

                            <!-- Backup Settings -->
                            <div class="card shadow border-left-info mb-4">
                                <div class="card-body">
                                    <h5><i class="icon fa fa-cogs"></i> {$BACKUP_SETTINGS}</h5>

                                    <form action="" method="post">
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <label for="inputMaxRetention">{$MAX_BACKUP_RETENTION}</label>
                                                <input type="number" name="max_backup_retention" id="inputMaxRetention"
                                                       class="form-control" value="{$MAX_BACKUP_RETENTION_VALUE}" min="0" step="1">
                                                <small class="form-text text-muted">{$MAX_BACKUP_RETENTION_INFO}</small>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="inputDailyScheduling">{$DAILY_BACKUP_SCHEDULING}</label>
                                                <select name="daily_backup_scheduling" id="inputDailyScheduling" class="form-control">
                                                    <option value="0" {if $DAILY_BACKUP_SCHEDULING_VALUE eq '0'}selected{/if}>{$DISABLED}</option>
                                                    <option value="1" {if $DAILY_BACKUP_SCHEDULING_VALUE eq '1'}selected{/if}>{$ENABLED}</option>
                                                </select>
                                                <small class="form-text text-muted">{$DAILY_BACKUP_SCHEDULING_INFO}</small>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" name="token" value="{$TOKEN}">
                                            <input type="hidden" name="action" value="settings">
                                            <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Existing Backups -->
                            {if isset($EXISTING_BACKUPS) && count($EXISTING_BACKUPS) > 0}
                                <h5>{$EXISTING}</h5>

                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>{$FILENAME}</th>
                                                <th>{$DATE_CREATED}</th>
                                                <th>{$FILE_SIZE}</th>
                                                {if $CAN_DOWNLOAD}
                                                    <th>{$ACTIONS}</th>
                                                {/if}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {foreach from=$EXISTING_BACKUPS item=backup}
                                                <tr>
                                                    <td>{$backup.filename}</td>
                                                    <td>{$backup.date}</td>
                                                    <td>{$backup.size}</td>
                                                    {if $CAN_DOWNLOAD}
                                                    <td>
                                                        <a href="{$backup.download_link}" target="_blank" class="btn btn-sm btn-primary">
                                                            <i class="fa fa-download"></i> {$DOWNLOAD}
                                                        </a>
                                                    </td>
                                                    {/if}
                                                </tr>
                                            {/foreach}
                                        </tbody>
                                    </table>
                                </div>
                            {else}
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle"></i> {$NO_BACKUPS}
                                </div>
                            {/if}
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
