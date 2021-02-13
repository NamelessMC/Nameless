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
                    <h1 class="h3 mb-0 text-gray-800">{$WIDGETS}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                        <li class="breadcrumb-item active">{$LAYOUT}</li>
                        <li class="breadcrumb-item active">{$WIDGETS}</li>
                    </ol>
                </div>

                <!-- Update Notification -->
                {include file='includes/update.tpl'}

                <div class="card shadow mb-4">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-9">
                                <h5 style="margin-top: 7px; margin-bottom: 7px;">{$EDITING_WIDGET}</h5>
                            </div>
                            <div class="col-md-3">
                                    <span class="float-md-right">
                                        {if isset($SETTINGS)}<a href="{$SETTINGS_LINK}"
                                                                class="btn btn-success">{$SETTINGS}</a>{/if}
                                        <a href="{$BACK_LINK}" class="btn btn-primary">{$BACK}</a>
                                    </span>
                            </div>
                        </div>
                        <hr />

                        <!-- Success and Error Alerts -->
                        {include file='includes/alerts.tpl'}

                        <form action="" method="post">

                            <div class="form-group">
                                <label for="inputOrder">{$WIDGET_ORDER}</label>
                                <input id="inputOrder" name="order" type="number" class="form-control" value="{$ORDER}">
                            </div>

                            <div class="form-group">
                                <label for="inputLocation">{$WIDGET_LOCATION}</label>
                                <select name="location" class="form-control" id="inputLocation">
                                    <option value="right" {if $LOCATION eq 'right' } selected{/if}>{$RIGHT}</option>
                                    <option value="left" {if $LOCATION eq 'left' } selected{/if}>{$LEFT}</option>
                                </select>
                            </div>

                            {foreach from=$POSSIBLE_PAGES key=module item=module_pages}
                                {if count($module_pages)}
                                    <div class="table table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>{$MODULE} {$MODULE_SEPERATOR} {$module|escape}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            {foreach from=$module_pages key=page item=value}
                                                <tr>
                                                    <td>
                                                        <label for="{$page|escape}"
                                                               style="font-weight: normal;">{($page|escape)|ucfirst}</label>
                                                        <div class="float-md-right">
                                                            <input class="js-switch" type="checkbox" name="pages[]"
                                                                   id="{$page|escape}"
                                                                   value="{$page|escape}" {if in_array($page, $ACTIVE_PAGES)} checked{/if}>
                                                        </div>
                                                    </td>
                                                </tr>
                                            {/foreach}
                                            </tbody>
                                        </table>
                                    </div>
                                {/if}
                            {/foreach}

                            <div class="form-group">
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

</body>

</html>