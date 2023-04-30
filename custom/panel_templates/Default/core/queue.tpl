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
                    <h1 class="h3 mb-0 text-gray-800">{$QUEUE}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                        <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                        <li class="breadcrumb-item active">{$QUEUE}</li>
                    </ol>
                </div>

                <!-- Update Notification -->
                {include file='includes/update.tpl'}

                <div class="card shadow mb-4">
                    <div class="card-body">

                        <a class="btn btn-primary" href="{$QUEUE_STATUS_LINK}">{$QUEUE_STATUS}</a>
                        <hr />

                        <!-- Success and Error Alerts -->
                        {include file='includes/alerts.tpl'}

                        <div class="card shadow border-left-primary">
                            <div class="card-body">
                                <h5><i class="icon fa fa-info-circle"></i> {$INFO}</h5>
                                <p>{$QUEUE_INFO}</p>
                                <p><code>{$QUEUE_CRON_URL}</code></p>
                            </div>
                        </div>

                        <br />

                        <form action="" method="post">
                            <div class="form-group">
                                <label for="InputRunner">{$QUEUE_RUNNER}</label>
                                <select name="runner" id="InputRunner" class="form-control">
                                    {foreach from=$QUEUE_RUNNERS item=option}
                                        <option value="{$option.value}"{if $option.selected} selected{/if}>
                                            {$option.label}
                                        </option>
                                    {/foreach}
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="InputInterval">{$QUEUE_INTERVAL}</label>
                                <input name="interval" id="InputInterval" class="form-control" type="number" min="0.5" step="0.1" value="{$QUEUE_INTERVAL_VALUE}" />
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

    <!-- End Wrapper -->
</div>

{include file='scripts.tpl'}

</body>

</html>
