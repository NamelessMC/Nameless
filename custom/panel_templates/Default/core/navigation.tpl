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
                    <h1 class="h3 mb-0 text-gray-800">{$NAVIGATION}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                        <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                        <li class="breadcrumb-item active">{$NAVIGATION}</li>
                    </ol>
                </div>

                <!-- Update Notification -->
                {include file='includes/update.tpl'}

                <div class="card shadow mb-4">
                    <div class="card-body">

                        <!-- Success and Error Alerts -->
                        {include file='includes/alerts.tpl'}

                        <form action="" method="post">
                            <div class="card shadow border-left-primary">
                                <div class="card-body">
                                    <h5><i class="icon fa fa-info-circle"></i> {$INFO}</h5>
                                    <p>{$NAVBAR_ORDER_INSTRUCTIONS}</p>
                                    <p>{$NAVBAR_ICON_INSTRUCTIONS}</p>
                                </div>
                            </div>
                            <br />
                            {foreach from=$NAV_ITEMS key=key item=item}
                                <strong>{$item.title|escape}</strong>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="input{$item.title|escape}">{$NAVBAR_ORDER}</label>
                                            <input type="number" min="1" class="form-control"
                                                   id="input{$item.title|escape}"
                                                   name="inputOrder[{if isset($item.custom) && is_numeric($item.custom)}{$item.custom}{else}{$key}{/if}]"
                                                   value="{$item.order|escape}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="input{$item.title|escape}Icon">{$NAVBAR_ICON}</label>
                                            <input type="text" class="form-control" id="input{$item.title|escape}Icon"
                                                   name="inputIcon[{if isset($item.custom) && is_numeric($item.custom)}{$item.custom}{else}{$key}{/if}]"
                                                   value="{$item.icon|escape}">
                                        </div>
                                    </div>
                                </div>
                                {if isset($item.items) && count($item.items)}
                                    <br>
                                    <strong>{$item.title|escape} &raquo; {$DROPDOWN_ITEMS}</strong>
                                    <br />
                                    {foreach from=$item.items key=dropdown_key item=dropdown_item}
                                        <strong>{$dropdown_item.title|escape}</strong>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="input{$dropdown_item.title|escape}">{$NAVBAR_ORDER}</label>
                                                    <input type="number" min="1" class="form-control"
                                                           id="input{$dropdown_item.title|escape}"
                                                           name="inputOrder[{if isset($dropdown_item.custom) && is_numeric($dropdown_item.custom)}{$dropdown_item.custom}{else}{$dropdown_key}{/if}]"
                                                           value="{$dropdown_item.order|escape}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="input{$dropdown_item.title|escape}Icon">{$NAVBAR_ICON}</label>
                                                    <input type="text" class="form-control"
                                                           id="input{$dropdown_item.title|escape}Icon"
                                                           name="inputIcon[{if isset($dropdown_item.custom) && is_numeric($dropdown_item.custom)}{$dropdown_item.custom}{else}{$dropdown_key}{/if}]"
                                                           value="{$dropdown_item.icon|escape}">
                                                </div>
                                            </div>
                                        </div>
                                    {/foreach}
                                {/if}

                            {/foreach}
                            <hr>
                            <div class="form-group">
                                <label for="dropdown_name">{$DROPDOWN_NAME}</label>
                                <input type="text" class="form-control" id="dropdown_name" name="dropdown_name"
                                       value="{$DROPDOWN_NAME_VALUE}">
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <input type="submit" value="{$SUBMIT}" class="btn btn-primary">
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