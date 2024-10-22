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
                    <h1 class="h3 mb-0 text-gray-800">{$MEMBER_LISTS}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                        <li class="breadcrumb-item active">{$MEMBERS}</li>
                        <li class="breadcrumb-item active">{$MEMBER_LISTS}</li>
                    </ol>
                </div>

                <!-- Update Notification -->
                {include file='includes/update.tpl'}

                <div class="card shadow mb-4">
                    <div class="card-body">
                        <!-- Success and Error Alerts -->
                        {include file='includes/alerts.tpl'}

                        <div class="table-responsive">
                            <table class="table table-borderless table-striped">
                                <thead>
                                    <tr>
                                        <th>{$NAME}</th>
                                        <th>{$MODULE}</th>
                                        <th>{$ENABLED}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                {foreach $MEMBER_LISTS_VALUES as $member_list}
                                    <tr>
                                        <td>
                                            {$member_list->getFriendlyName()}
                                        </td>
                                        <td>
                                            {$member_list->getModule()}
                                        </td>
                                        <td>{if $member_list->isEnabled() eq 1}<i
                                                    class="fa fa-check-circle text-success"></i>{else}<i
                                                    class="fa fa-times-circle text-danger"></i>{/if}</td>
                                        <td class="text-right">
                                            <form action="" method="post">
                                                <input type="hidden" name="token" value="{$TOKEN}">
                                                <input hidden name="list" value="{$member_list->getName()}">
                                                {if $member_list->isEnabled() eq 1}
                                                    <button class="btn btn-sm btn-danger">{$DISABLE}</button>
                                                {else}
                                                    <button class="btn btn-sm btn-success">{$ENABLE}</button>
                                                {/if}
                                            </form>
                                        </td>
                                    </tr>
                                {/foreach}
                                </tbody>
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
