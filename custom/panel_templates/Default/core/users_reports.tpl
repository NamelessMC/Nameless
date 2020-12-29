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
                    <h1 class="h3 mb-0 text-gray-800">{$REPORTS}{if isset($VIEWING_USER)} | {$VIEWING_USER}{/if}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                        <li class="breadcrumb-item active">{$USER_MANAGEMENT}</li>
                        <li class="breadcrumb-item active">{$REPORTS}</li>
                    </ol>
                </div>

                <!-- Update Notification -->
                {include file='includes/update.tpl'}

                <div class="card shadow mb-4">
                    <div class="card-body">

                        <a href="{$CHANGE_VIEW_LINK}" {if count($ALL_REPORTS)}style="margin-bottom: 15px"
                           {/if}class="btn btn-primary">{$CHANGE_VIEW}</a>

                        {if isset($NO_REPORTS)}
                            <hr />
                        {/if}

                        <!-- Success and Error Alerts -->
                        {include file='includes/alerts.tpl'}

                        {if count($ALL_REPORTS)}
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>{$USER_REPORTED}</th>
                                        <th>{$UPDATED_BY}</th>
                                        <th>{$COMMENTS}</th>
                                        <th>{$ACTIONS}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {foreach from=$ALL_REPORTS item=report}
                                        <tr>
                                            <td><a href="{$report.user_profile}"
                                                   style="{$report.user_reported_style}"><img
                                                            src="{$report.user_reported_avatar}"
                                                            style="max-height:25px;max-width:25px;"
                                                            alt="{$report.user_reported}"
                                                            class="rounded"> {$report.user_reported}</a><br /><span
                                                        data-toggle="tooltip"
                                                        data-original-title="{$report.reported_at_full}">{$report.reported_at}</span>
                                            </td>
                                            <td><a href="{$report.updated_by_profile}"
                                                   style="{$report.updated_by_style}"><img
                                                            src="{$report.updated_by_avatar}"
                                                            style="max-height:25px;max-width:25px;"
                                                            alt="{$report.updated_by}"
                                                            class="rounded"> {$report.updated_by}</a><br /><span
                                                        data-toggle="tooltip"
                                                        data-original-title="{$report.updated_at_full}">{$report.updated_at}</span>
                                            </td>
                                            <td><i class="fa fa-comments" aria-hidden="true"></i> {$report.comments}
                                            </td>
                                            <td><a href="{$report.link}" class="btn btn-primary">{$VIEW} &raquo;</a>
                                            </td>
                                        </tr>
                                    {/foreach}
                                    </tbody>
                                </table>
                            </div>
                            {$PAGINATION}

                        {elseif isset($NO_REPORTS)}
                            {$NO_REPORTS}
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