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
                            <li class="breadcrumb-item"><a href="{$BACK_LINK}">{$DEBUGGING_AND_MAINTENANCE}</a></li>
                            <li class="breadcrumb-item active">{$ERROR_LOGS}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                {include file='includes/update.tpl'}

                <div class="card">
                    <div class="card-body">

                        {include file='includes/success.tpl'}

                        <h5 style="display:inline">{$ERROR_LOGS}</h5>

                        <div class="float-md-right">
                            <a href="{$BACK_LINK}" class="btn btn-primary">{$BACK}</a>
                        </div>
                        <hr />

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <td>
                                        <a href="{$FATAL_LOG_LINK}">{$FATAL_LOG}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href="{$NOTICE_LOG_LINK}">{$NOTICE_LOG}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href="{$WARNING_LOG_LINK}">{$WARNING_LOG}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href="{$OTHER_LOG_LINK}">{$OTHER_LOG}</a>
                                    </td>
                                </tr>
                            </table>
                        </div>

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