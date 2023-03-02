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
                        <li class="breadcrumb-item active">{$MEMBER_LISTS}</li>
                    </ol>
                </div>

                <!-- Update Notification -->
                {include file='includes/update.tpl'}

                <div class="card shadow mb-4">
                    <div class="card-body">
                        <!-- Success and Error Alerts -->
                        {include file='includes/alerts.tpl'}

                        <form id="toggleHideBannedUsers" action="" method="post">
                            <input type="hidden" name="token" value="{$TOKEN}">
                            <div class="form-group custom-control custom-switch">
                                <input type="hidden" name="action" value="toggle_hide_banned_users">
                                <input id="inputToggleHideBannedUsers" name="hide_banned_users" type="checkbox" class="custom-control-input js-check-change" value="1" {if $HIDE_BANNED_USERS_VALUE eq 1} checked{/if} />
                                <label for="inputToggleHideBannedUsers" class="custom-control-label">
                                    {$HIDE_BANNED_USERS}
                                </label>
                            </div>
                        </form>

                        <form action="" method="post">
                            <input type="hidden" name="token" value="{$TOKEN}">
                            <div class="form-group">
                                <label for="groups">{$GROUPS}</label>
                                <select name="groups[]" id="groups" class="form-control" multiple>
                                    {foreach from=$GROUPS_ARRAY item=group}
                                        <option value="{$group.id}" {if in_array($group.id, $GROUPS_VALUE)} selected{/if}>{$group.name}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </form>

                        <hr>

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
<script>
    const groupSelector = $('#groups');
    groupSelector.select2({
        placeholder: "{$NO_ITEM_SELECTED}"
    });
    groupSelector.on('change', (e) => {
        const selectedGroups = [];
        const selectedOptions = e.delegateTarget.selectedOptions;
        for (const group of selectedOptions) {
            selectedGroups.push(group.value);
        }

        $.ajax({
            url: "{$SELECT_CHANGE_URL}",
            type: "POST",
            data: {
                token: "{$TOKEN}",
                action: "update_groups",
                groups: selectedGroups,
            },
            success: function (response) {
                // Success
            },
            error: function (xhr) {
                // Error
                console.log(xhr);
            }
        });
    });
</script>

</body>

</html>
