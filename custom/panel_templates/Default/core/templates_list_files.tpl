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
                    <h1 class="h3 mb-0 text-gray-800">{$TEMPLATES}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                        <li class="breadcrumb-item active">{$LAYOUT}</li>
                        <li class="breadcrumb-item active">{$TEMPLATES}</li>
                    </ol>
                </div>

                <!-- Update Notification -->
                {include file='includes/update.tpl'}

                <div class="card shadow mb-4">
                    <div class="card-body">

                        <div class="row" style="margin-bottom: 15px;">
                            <div class="col-md-9">
                                <h5 style="margin-top: 7px; margin-bottom: 7px;">{$EDITING_TEMPLATE}</h5>
                            </div>
                            <div class="col-md-3">
                                    <span class="float-md-right">
                                        {if $PERMISSIONS_LINK}<a class="btn btn-info"
                                                                 href="{$PERMISSIONS_LINK}">{$PERMISSIONS}</a>{/if}
                                        <a href="{$BACK_LINK}" class="btn btn-primary">{$BACK}</a>
                                    </span>
                            </div>
                        </div>
                        <hr />

                        <!-- Success and Error Alerts -->
                        {include file='includes/alerts.tpl'}

                        {if isset($DEFAULT_TEMPLATE_WARNING)}
                            <div class="alert bg-warning text-white">{$DEFAULT_TEMPLATE_WARNING}</div>
                        {/if}

                        <div class="table-responsive">
                            <table class="table table-striped">
                                {if count($TEMPLATE_DIRS)}
                                    {foreach from=$TEMPLATE_DIRS item=dir}
                                        <tr>
                                            <td>
                                                <i class="fa fa-folder"></i> {$dir.name}
                                                <div class="float-right">
                                                    <a href="{$dir.link}" class="btn btn-primary btn-sm"><i
                                                                class="fas fa-search fa-fw"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    {/foreach}
                                {/if}
                                {if count($TEMPLATE_FILES)}
                                    {foreach from=$TEMPLATE_FILES item=file}
                                        <tr>
                                            <td>
                                                <i class="fa fa-file"></i> {$file.name}
                                                <div class="float-right">
                                                    <a href="{$file.link}" class="btn btn-primary btn-sm"><i
                                                                class="fas fa-edit fa-fw"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    {/foreach}
                                {/if}
                            </table>
                        </div>

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