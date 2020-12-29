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

                        <a class="btn btn-primary" style="margin-bottom: 15px;"
                           href="{$INSTALL_TEMPLATE_LINK}">{$INSTALL_TEMPLATE}</a>
                        <a class="btn btn-secondary" style="margin-bottom: 15px;"
                           href="{$CLEAR_CACHE_LINK}">{$CLEAR_CACHE}</a>

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
                                                <button role="button" class="btn btn-sm btn-warning"
                                                        data-toggle="popover" data-title="{$WARNING}"
                                                        data-content="{$template.version_mismatch}"><i
                                                            class="fa fa-exclamation-triangle"></i></button>
                                            {/if}
                                            <br />
                                            <small>{$template.author_x}</small>
                                        </td>
                                        <td>
                                            <div class="float-md-right">
                                                {if $template.enabled}
                                                    {if $template.deactivate_link}
                                                        <a class="btn btn-danger btn-sm"
                                                           href="{$template.deactivate_link}">{$DEACTIVATE}</a>
                                                    {else}
                                                        <button role="button" class="btn btn-success btn-sm"
                                                                disabled>{$ACTIVE}</button>
                                                    {/if}

                                                    {if $template.default}
                                                        <button role="button" class="btn btn-success btn-sm"
                                                                disabled>{$DEFAULT}</button>
                                                    {else}
                                                        <a class="btn btn-primary btn-sm"
                                                           href="{$template.default_link}">{$MAKE_DEFAULT}</a>
                                                    {/if}
                                                {else}
                                                    <a class="btn btn-primary btn-sm"
                                                       href="{$template.activate_link}">{$ACTIVATE}</a>
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

                        <br />
                        <h5>{$FIND_TEMPLATES}</h5>
                        <br />

                        {if isset($WEBSITE_TEMPLATES_ERROR)}
                            <div class="alert bg-danger text-white">{$WEBSITE_TEMPLATES_ERROR}</div>
                        {/if}

                        {if count($WEBSITE_TEMPLATES)}
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <colgroup>
                                        <col width="70%">
                                        <col width="20%">
                                        <col width="10%">
                                    </colgroup>
                                    <thead>
                                    <tr>
                                        <th>{$TEMPLATE}</th>
                                        <th>{$STATS}</th>
                                        <th>{$ACTIONS}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {foreach from=$WEBSITE_TEMPLATES item=item}
                                        <tr>
                                            <td>
                                                <strong>{$item.name}</strong> <small>{$item.latest_version}</small>
                                                <br />
                                                <small>{$item.author_x}</small>
                                            </td>
                                            <td>
                                                <div class="star-rating view">
                                                    <span class="far fa-star" data-rating="1"
                                                          style="color:gold;"></span>
                                                    <span class="far fa-star" data-rating="2" style="color:gold"></span>
                                                    <span class="far fa-star" data-rating="3"
                                                          style="color:gold;"></span>
                                                    <span class="far fa-star" data-rating="4"
                                                          style="color:gold;"></span>
                                                    <span class="far fa-star" data-rating="5"
                                                          style="color:gold;"></span>
                                                    <input type="hidden" name="rating" class="rating-value"
                                                           value="{($item.rating/10)|round}">
                                                </div>
                                                {$item.downloads_full}<br />
                                                {$item.views_full}
                                            </td>
                                            <td><a href="{$item.url}" target="_blank"
                                                   class="btn btn-primary btn-sm">{$VIEW} &raquo;</a></td>
                                        </tr>
                                    {/foreach}
                                    </tbody>
                                </table>
                            </div>
                        {else}
                            <div class="alert bg-danger text-white">{$UNABLE_TO_RETRIEVE_TEMPLATES}</div>
                        {/if}

                        <a href="{$VIEW_ALL_PANEL_TEMPLATES_LINK}" class="btn btn-primary"
                           target="_blank">{$VIEW_ALL_PANEL_TEMPLATES} &raquo;</a>

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

    <!-- Delete error modal -->
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{$NO}</button>
                    <a href="#" id="deleteLink" class="btn btn-primary">{$YES}</a>
                </div>
            </div>
        </div>
    </div>

    <!-- End Wrapper -->
</div>

{include file='scripts.tpl'}

<script type="text/javascript">
  function showDeleteModal(id) {
    $('#deleteLink').attr('href', id);
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