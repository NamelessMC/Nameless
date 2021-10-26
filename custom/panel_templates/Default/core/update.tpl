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
                    <h1 class="h3 mb-0 text-gray-800">{$UPDATE}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                        <li class="breadcrumb-item active">{$UPDATE}</li>
                    </ol>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-body">
                        <!-- Success and Error Alerts -->
                        {include file='includes/alerts.tpl'}

                        {if isset($PHP_WARNING)}
                            <div class="alert bg-danger text-white">{$PHP_WARNING}</div>
                        {/if}

                        {if !isset($PREVENT_UPGRADE)}
                            {if isset($NEW_UPDATE)}
                                <div class="alert {if $NEW_UPDATE_URGENT eq true}bg-danger{else}bg-primary{/if} text-white">
                                    {$NEW_UPDATE}
                                    <hr />
                                    {$CURRENT_VERSION}<br />
                                    {$NEW_VERSION}
                                </div>
                                <h4>{$INSTRUCTIONS}</h4>
                                <p>{$INSTRUCTIONS_VALUE}</p>
                                <hr />
                                <a href="{$DOWNLOAD_LINK}" class="btn btn-primary">{$DOWNLOAD}</a>
                                <button class="btn btn-primary" type="button"
                                        onclick="showConfirmModal()">{$UPDATE}</button>
                            {else}
                                <div class="alert bg-success text-white">
                                    <span><i class="icon fa fa-check"></i>&nbsp;&nbsp;{$UP_TO_DATE}</span>
                                </div>
                                <a href="{$CHECK_AGAIN_LINK}" class="btn btn-primary">{$CHECK_AGAIN}</a>
                            {/if}
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

    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{$WARNING}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {$INSTALL_CONFIRM}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{$CANCEL}</button>
                    <a href="{$UPGRADE_LINK}" class="btn btn-primary">{$UPDATE}</a>
                </div>
            </div>
        </div>
    </div>

    <!-- End Wrapper -->
</div>

{include file='scripts.tpl'}

<script type="text/javascript">
  function showConfirmModal() {
    $('#confirmModal').modal().show();
  }
</script>

</body>

</html>