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
                        <h1 class="h3 mb-0 text-gray-800">{$SETTINGS}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$MEMBERS}</li>
                            <li class="breadcrumb-item active">{$SETTINGS}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='includes/update.tpl'}

                    <div class="card shadow mb-4">
                        <div class="card-body">

                            <!-- Success and Error Alerts -->
                            {include file='includes/alerts.tpl'}

                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="link_location">{$LINK_LOCATION}</label>
                                    <select class="form-control" id="link_location" name="link_location">
                                        <option value="1" {if $LINK_LOCATION_VALUE eq 1} selected{/if}>
                                            {$LINK_NAVBAR}
                                        </option>
                                        <option value="2" {if $LINK_LOCATION_VALUE eq 2} selected{/if}>
                                            {$LINK_MORE}
                                        </option>
                                        <option value="3" {if $LINK_LOCATION_VALUE eq 3} selected{/if}>
                                            {$LINK_FOOTER}
                                        </option>
                                        <option value="4" {if $LINK_LOCATION_VALUE eq 4} selected{/if}>
                                            {$LINK_NONE}
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group custom-control custom-switch">
                                    <input type="hidden" name="action" value="toggle_hide_banned_users">
                                    <input id="inputToggleHideBannedUsers" name="hide_banned_users" type="checkbox" class="custom-control-input js-check-change" value="1" {if $HIDE_BANNED_USERS_VALUE eq 1} checked{/if} />
                                    <label for="inputToggleHideBannedUsers" class="custom-control-label">
                                        {$HIDE_BANNED_USERS}
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label for="groups">{$GROUPS}</label>
                                    <select name="groups[]" id="groups" class="form-control" multiple>
                                        {foreach from=$GROUPS_ARRAY item=group}
                                            <option value="{$group.id}" {if in_array($group.id, $GROUPS_VALUE)} selected{/if}>{$group.name}</option>
                                        {/foreach}
                                    </select>
                                </div>

                                <div class="form-group">
                                    <input type="hidden" name="token" value="{$TOKEN}">
                                    <input type="submit" class="btn btn-primary" value="{$SUBMIT}" />
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
    <script>
        $('#groups').select2({
            placeholder: "{$NO_ITEM_SELECTED}"
        });
    </script>

</body>

</html>
