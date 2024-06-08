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
                        <h1 class="h3 mb-0 text-gray-800">{$GROUPS}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$GROUPS}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='includes/update.tpl'}

                    <div class="card shadow mb-4">
                        <div class="card-body">

                            <div class="row" style="margin-bottom: 10px;">
                                <div class="col-md-9">
                                    <h5 style="margin-top: 7px; margin-bottom: 7px;">{$PERMISSIONS}</h5>
                                </div>
                                <div class="col-md-3">
                                    <span class="float-md-right">
                                        <a class="btn btn-primary" href="{$BACK_LINK}">{$BACK}</a>
                                    </span>
                                </div>
                            </div>

                            <!-- Success and Error Alerts -->
                            {include file='includes/alerts.tpl'}

                            {foreach from=$ALL_PERMISSIONS key=key item=item}
                                <h3 class="mt-3">{$key|escape}</h3>

                                {if $item|@count > 1}
                                    <span class="btn btn-link" onclick="return selectAllPerms('{$key|escape}');">{$SELECT_ALL}</span>
                                    <span class="btn btn-link" onclick="return inheritAllPerms('{$key|escape}');">{$INHERIT_ALL}</span>
                                    <span class="btn btn-link" onclick="return deselectAllPerms('{$key|escape}');">{$DESELECT_ALL}</span>
                                {/if}

                                <table class="table table-striped" id="permission-section-{$key|escape}">
                                    <tbody>
                                        {foreach from=$item key=permission item=title}
                                            <tr>
                                                <td>{$title}</td>
                                                <td class="btn-group pull-right" id="permission-buttons-{$permission}">
                                                    <button type="button" class="btn btn-success" id="permission-allow-{$permission}" onclick="togglePermissionButtons('allow', '{$permission}')" {if array_key_exists($permission, $GROUP_PERMISSIONS) && $GROUP_PERMISSIONS[$permission] == 1} disabled{/if}>Allow</button>
                                                    <button type="button" class="btn btn-secondary" id="permission-inherit-{$permission}" onclick="togglePermissionButtons('inherit', '{$permission}')" {if array_key_exists($permission, $GROUP_PERMISSIONS) && $GROUP_PERMISSIONS[$permission] == -1} disabled{/if}>Inherit</button>
                                                    <button type="button" class="btn btn-danger" id="permission-deny-{$permission}" onclick="togglePermissionButtons('deny', '{$permission}')" {if array_key_exists($permission, $GROUP_PERMISSIONS) && $GROUP_PERMISSIONS[$permission] == 0} disabled{/if}>Deny</button>
                                                </td>
                                            </tr>
                                        {/foreach}
                                    </tbody>
                                </table>
                            {/foreach}

                            <div class="form-group mt-3">
                                <button type="button" class="btn btn-primary" onclick="submitForm()">{$SUBMIT}</button>
                                <form id="permissions-form" method="post">
                                    <input type="hidden" name="token" value="{$TOKEN}">
                                    <input type="hidden" name="permissions" value="">
                                </form>
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

    <script type="text/javascript">
        const permissions = {$GROUP_PERMISSIONS_JSON};

        function selectAllPerms(sectionName) {
            toggleTypeForSection(sectionName, 'allow');
        }

        function deselectAllPerms(sectionName) {
            toggleTypeForSection(sectionName, 'deny');
        }

        function inheritAllPerms(sectionName) {
            toggleTypeForSection(sectionName, 'inherit');
        }

        function toggleTypeForSection(sectionName, type) {
            const buttons = document.getElementById('permission-section-' + sectionName).querySelectorAll('button');
            for (const button of buttons) {
                const bits = button.id.split('-');
                const buttonValue = bits[1];
                const permission = bits[2];
                if (buttonValue === type) {
                    togglePermissionButtons(buttonValue, permission);
                }
            };
        }

        function togglePermissionButtons(value, permission) {
            const enableButton = document.getElementById('permission-' + value + '-' + permission);
            enableButton.disabled = true;
            const buttons = document.getElementById('permission-buttons-' + permission).querySelectorAll('button');
            for (const button of buttons) {
                if (button.id !== enableButton.id) {
                    button.disabled = false;
                }
            }
            if (value === 'inherit') {
                delete permissions[permission];
                return;
            }
            permissions[permission] = value === 'allow' ? 1 : 0;
        }

        function submitForm() {
            document.getElementsByName('permissions')[0].value = JSON.stringify(permissions);
            document.getElementById('permissions-form').submit();
        }
    </script>

</body>

</html>