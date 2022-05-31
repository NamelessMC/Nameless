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
                        <h1 class="h3 mb-0 text-gray-800">{$PROFILE_FIELDS}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                            <li class="breadcrumb-item active">{$PROFILE_FIELDS}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='includes/update.tpl'}

                    <div class="card shadow mb-4">
                        <div class="card-body">

                            <a class="btn btn-primary" {if count($FIELDS)}style="margin-bottom: 15px"
                                {/if}href="{$NEW_FIELD_LINK}">{$NEW_FIELD}</a>
                            {if !count($FIELDS)}
                            <hr />
                            {/if}

                            <!-- Success and Error Alerts -->
                            {include file='includes/alerts.tpl'}

                            {if count($FIELDS)}
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{$FIELD_NAME}</th>
                                            <th>{$TYPE}</th>
                                            <th>{$REQUIRED}</th>
                                            <th>{$EDITABLE}</th>
                                            <th>{$PUBLIC}</th>
                                            <th>{$FORUM_POSTS}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {foreach from=$FIELDS item=field}
                                        <tr>
                                            <td><a href="{$field.edit_link}">{$field.name}</a></td>
                                            <td>{$field.type}</td>
                                            <td>{if $field.required eq 1}
                                                <i class="fa fa-check-circle text-success"></i>
                                                {else}
                                                <i class="fa fa-times-circle text-danger"></i>
                                                {/if}
                                            </td>
                                            <td>{if $field.editable eq 1}
                                                <i class="fa fa-check-circle text-success"></i>
                                                {else}
                                                <i class="fa fa-times-circle text-danger"></i>
                                                {/if}
                                            </td>
                                            <td>{if $field.public eq 1}
                                                <i class="fa fa-check-circle text-success"></i>
                                                {else}
                                                <i class="fa fa-times-circle text-danger"></i>
                                                {/if}
                                            </td>
                                            <td>{if $field.forum_posts eq 1}
                                                <i class="fa fa-check-circle text-success"></i>
                                                {else}
                                                <i class="fa fa-times-circle text-danger"></i>
                                                {/if}
                                            </td>
                                        </tr>
                                        {/foreach}
                                    </tbody>
                                </table>
                            </div>
                            {else}
                            {$NO_FIELDS}
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