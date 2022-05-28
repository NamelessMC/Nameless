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

                            <form action="" method="post">
                                {foreach from=$ALL_PERMISSIONS key=key item=item}
                                <h3 class="mt-3">{$key|escape}</h3>
                                {if $item|@count > 1}
                                <a href="#" onclick="return selectAllPerms('{$key|escape}');">{$SELECT_ALL}</a>
                                |
                                <a href="#" onclick="return deselectAllPerms('{$key|escape}');">{$DESELECT_ALL}</a>
                                {/if}
                                <!-- </h3> -->
                                <div id="perm-section-{$key|escape}">
                                    {foreach from=$item key=permission item=title}
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="permissions[{$permission|escape}]"
                                            id="permissions-checkbox-[{$permission|escape}]"
                                            class="custom-control-input" value="1" {if is_array($PERMISSIONS_VALUES) &&
                                            array_key_exists($permission|escape, $PERMISSIONS_VALUES)} checked{/if}>
                                        <label class="custom-control-label"
                                            for="permissions-checkbox-[{$permission|escape}]">
                                            {$title}
                                        </label>
                                    </div>
                                    {/foreach}
                                </div>
                                {/foreach}

                                <div class="form-group mt-3">
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

    <script type="text/javascript">
        function selectAllPerms(sectionName) {
            let section = $('#perm-section-' + sectionName);
            section.find('.custom-control-input').each(function () {
                $(this).prop('checked', true);
                onChange(this);
            });
            return false;
        }

        function deselectAllPerms(sectionName) {
            let section = $('#perm-section-' + sectionName);
            section.find('.custom-control-input').each(function () {
                $(this).prop('checked', false);
                onChange(this);
            });
            return false;
        }

        function onChange(el) {
            if (typeof Event === 'function' || !document.fireEvent) {
                var event = document.createEvent('HTMLEvents');
                event.initEvent('change', true, true);
                el.dispatchEvent(event);
            } else {
                el.fireEvent('onchange');
            }
        }
    </script>

</body>

</html>