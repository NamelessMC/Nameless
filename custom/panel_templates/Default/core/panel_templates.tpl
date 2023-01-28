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
                        <h1 class="h3 mb-0 text-gray-800">{$PANEL_TEMPLATES}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$LAYOUT}</li>
                            <li class="breadcrumb-item active">{$PANEL_TEMPLATES}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='includes/update.tpl'}

                    <div class="card shadow mb-4">
                        <div class="card-body">

                            <form style="display:inline" action="{$INSTALL_TEMPLATE_LINK}" method="post">
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <button type="submit" class="btn btn-primary"
                                    style="margin-bottom: 15px;">{$INSTALL_TEMPLATE}</button>
                            </form>
                            <form style="display:inline" action="{$CLEAR_CACHE_LINK}" method="post">
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <button type="submit" class="btn btn-secondary"
                                    style="margin-bottom: 15px;">{$CLEAR_CACHE}</button>
                            </form>

                            <!-- Success and Error Alerts -->
                            {include file='includes/alerts.tpl'}

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <colgroup>
                                        <col width="75%">
                                        <col width="25%">
                                    </colgroup>
                                    {foreach from=$TEMPLATE_LIST item=template}
                                    <tr>
                                        <td>
                                            <strong>{$template.name}</strong> <small>{$template.version}</small>
                                            {if $template.version_mismatch}
                                            &nbsp;
                                            <button role="button" class="btn btn-sm btn-warning" data-toggle="popover"
                                                data-title="{$WARNING}" data-content="{$template.version_mismatch}"><i
                                                    class="fa fa-exclamation-triangle"></i></button>
                                            {/if}
                                            {if $template.third_party}
                                                &nbsp;
                                                <button role="button" class="btn btn-sm btn-warning" data-toggle="popover"
                                                        data-title="{$WARNING}" data-content="{$template.third_party}"><i
                                                            class="fa fa-exclamation-triangle"></i></button>
                                            {/if}
                                            <br />
                                            <small>{$template.author_x}</small>
                                        </td>
                                        <td>
                                            <div class="float-md-right">
                                                {if $template.enabled}
                                                {if $template.deactivate_link}
                                                <form action="{$template.deactivate_link}" method="post"
                                                    style="display:inline">
                                                    <input type="hidden" name="token" value="{$TOKEN}">
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        {$DEACTIVATE}
                                                    </button>
                                                </form>
                                                {else}
                                                <button role="button" class="btn btn-success btn-sm"
                                                    disabled>{$ACTIVE}</button>
                                                {/if}

                                                {if $template.default}
                                                <button role="button" class="btn btn-success btn-sm"
                                                    disabled>{$DEFAULT}</button>
                                                {else}
                                                <form action="{$template.default_link}" method="post"
                                                    style="display:inline">
                                                    <input type="hidden" name="token" value="{$TOKEN}">
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        {$MAKE_DEFAULT}
                                                    </button>
                                                </form>
                                                {/if}
                                                {else}
                                                <form action="{$template.activate_link}" method="post"
                                                    style="display:inline">
                                                    <input type="hidden" name="token" value="{$TOKEN}">
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        {$ACTIVATE}
                                                    </button>
                                                </form>
                                                <button role="button"
                                                    onclick="showDeleteModal('{$template.delete_link}')"
                                                    class="btn btn-danger btn-sm">{$DELETE}</button>
                                                {/if}
                                            </div>
                                        </td>
                                    </tr>
                                    {/foreach}
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

        <!-- Delete modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{$ARE_YOU_SURE}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {$CONFIRM_DELETE_TEMPLATE}
                    </div>
                    <div class="modal-footer">
                        <form action="" id="deleteForm" method="post">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{$NO}</button>
                            <input type="hidden" name="token" value="{$TOKEN}">
                            <button type="submit" class="btn btn-primary">{$YES}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- End Wrapper -->
    </div>

    {include file='scripts.tpl'}

    <script type="text/javascript">
        function showDeleteModal(id) {
            $('#deleteForm').attr('action', id);
            $('#deleteModal').modal().show();
        }

        var $star_rating = $('.star-rating.view .fa-star');

        var SetRatingStar = function (type = 0) {
            if (type === 0) {
                return $star_rating.each(function () {
                    if (parseInt($(this).parent().children('input.rating-value').val()) >= parseInt($(this).data('rating'))) {
                        return $(this).removeClass('far').addClass('fas');
                    } else {
                        return $(this).removeClass('fas').addClass('far');
                    }
                });
            }
        };

        SetRatingStar();
    </script>

</body>

</html>
