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
                        <h1 class="h3 mb-0 text-gray-800">{$API}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                            <li class="breadcrumb-item active">{$API}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='includes/update.tpl'}

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <a class="btn btn-primary" href="{$BACK_LINK}">{$BACK}</a>
                            <hr />

                            <!-- Success and Error Alerts -->
                            {include file='includes/alerts.tpl'}

                            <div class="card shadow border-left-primary">
                                <div class="card-body">
                                    <h5><i class="icon fa fa-info-circle"></i> {$INFO}</h5>
                                    {$GROUP_SYNC_INFO}
                                </div>
                            </div>
                            <br />

                            {if count($GROUP_SYNC_VALUES)}
                            <h5>{$EXISTING_RULES}</h5>
                            <form action="" method="post">
                                {foreach from=$GROUP_SYNC_VALUES item=group_sync}
                                <div class="form-group">
                                    <div class="row">

                                        {foreach from=$GROUP_SYNC_INJECTORS key=column_name item=injector}
                                        <input type="hidden">
                                        <div class="col-md">
                                            <label for="input_{$column_name}">{$injector->getName()}</label>
                                            {if in_array($injector, $ENABLED_GROUP_SYNC_INJECTORS)}
                                            <select name="existing[{$group_sync['id']}][{$column_name}]"
                                                class="form-control" id="input_{$column_name}">
                                                <option value="0" {if {$group_sync[$column_name]} eq null} selected
                                                    {/if}>{$NONE} ({$DISABLED})</option>
                                                {foreach from=$injector->getSelectionOptions() item=group}
                                                <option value="{$group['id']}" {if $group_sync[$column_name] eq
                                                    $group['id']} selected {/if}>{$group['name']}</option>
                                                {/foreach}
                                            </select>
                                            {else}
                                            <p class="text-muted" style="padding-top: 5px">
                                                {$injector->getNotEnabledMessage($LANGUAGE)}</p>
                                            <input name="{$column_name}" type="hidden" id="input_{$column_name}">
                                            {/if}
                                        </div>
                                        {/foreach}

                                        <div class="col-md-1">
                                            <div style="height:32px"></div>
                                            <button type="button" onclick="deleteGroupSync('{$group_sync['id']}')"
                                                class="btn btn-danger">{$DELETE}</button>
                                        </div>
                                    </div>
                                </div>
                                {/foreach}
                                <div class="form-group">
                                    <input type="hidden" name="token" value="{$TOKEN}">
                                    <input type="hidden" name="action" value="update">
                                    <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
                                </div>
                            </form>
                            <hr />
                            {/if}

                            <h5>{$NEW_RULE}</h5>
                            <form action="" method="post">
                                <div class="form-group">
                                    <div class="row">

                                        {foreach from=$GROUP_SYNC_INJECTORS item=injector key=column_name}
                                        <div class="col-md">
                                            <label for="input_{$column_name}">{$injector->getName()}</label>
                                            {if in_array($injector, $ENABLED_GROUP_SYNC_INJECTORS)}
                                            <select name="{$column_name}" class="form-control"
                                                id="input_{$column_name}">
                                                {if $column_name != $NAMELESS_INJECTOR_COLUMN}
                                                <option value="0">{$NONE} ({$DISABLED})</option>
                                                {/if}
                                                {foreach from=$injector->getSelectionOptions() item=group}
                                                <option value="{$group['id']}">{$group['name']}</option>
                                                {/foreach}
                                            </select>
                                            {else}
                                            <p class="text-muted" style="padding-top: 5px">
                                                {$injector->getNotEnabledMessage($LANGUAGE)}</p>
                                            <input name="{$column_name}" type="hidden" id="input_{$column_name}">
                                            {/if}
                                        </div>
                                        {/foreach}

                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="token" value="{$TOKEN}">
                                    <input type="hidden" name="action" value="create">
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

    <script type="text/javascript">
        function deleteGroupSync(id) {
            const response = $.post("{$DELETE_LINK}", { id, action: 'delete', token: "{$TOKEN}" });
            response.done(function () { window.location.reload(); })
        }
    </script>

</body>

</html>