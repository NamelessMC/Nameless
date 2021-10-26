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
                    <h1 class="h3 mb-0 text-gray-800">{$MINECRAFT_SERVERS}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                        <li class="breadcrumb-item active">{$INTEGRATIONS}</li>
                        <li class="breadcrumb-item"><a href="{$MINECRAFT_LINK}">{$MINECRAFT}</a></li>
                        <li class="breadcrumb-item active">{$MINECRAFT_SERVERS}</li>
                    </ol>
                </div>

                <!-- Update Notification -->
                {include file='includes/update.tpl'}

                <!-- Success and Error Alerts -->
                {include file='includes/alerts.tpl'}

                <div class="card shadow mb-4">
                    <div class="card-body">

                        <a href="{$NEW_SERVER_LINK}" class="btn btn-primary"
                           {if !isset($NO_SERVERS)}style="margin-bottom: 15px" {/if}}>{$NEW_SERVER}</a>

                        {if isset($NO_SERVERS)}
                            <hr />
                            <p>{$NO_SERVERS}</p>
                        {else}
                            <div class="table table-responsive">
                                <table class="table table-striped">
                                    <tbody id="sortable">
                                    {foreach from=$SERVERS item=server}
                                        <tr data-id="{$server.id}">
                                            <td><strong>{$server.name}</strong> ({$server.server_id})</td>
                                            <td>
                                                <div class="float-md-right">
                                                    <div class="btn btn-secondary"><i class="fas fa-arrows-alt"></i></div>
                                                    <a href="{$server.edit_link}" class="btn btn-info">{$EDIT}</a>
                                                    <button onclick="showDeleteModal('{$server.delete_link}')"
                                                            class="btn btn-danger">{$DELETE}</button>
                                                </div>
                                            </td>
                                        </tr>
                                    {/foreach}
                                    </tbody>
                                </table>
                            </div>
                        {/if}

                    </div>
                </div>
                <div class="card shadow mb-4">
                    <div class="card-body">

                        <h5>{$QUERY_SETTINGS}</h5>
                        <hr />
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="inputDefaultServer">{$DEFAULT_SERVER}</label>
                                <select id="inputDefaultServer" class="form-control" name="default_server">
                                    {if $SERVERS|count > 1}
                                        <option value="none" {if $DEFAULT_SERVER_VALUE eq 0} selected{/if}>{$NO_DEFAULT_SERVER}</option>
                                    {/if}
                                    {if isset($SERVERS) && count($SERVERS)}
                                        {foreach from=$SERVERS item=server}
                                            <option value="{$server.id}" {if $server.is_default eq 1} selected{/if}>{$server.name}</option>
                                        {/foreach}
                                    {/if}
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="inputQueryInterval">{$QUERY_INTERVAL}</label>
                                <input id="inputQueryInterval" name="interval" type="number" class="form-control"
                                       value="{$QUERY_INTERVAL_VALUE}" min="5" max="60" />
                            </div>

                            <div class="form-group">
                                <label for="inputExternalQuery">{$EXTERNAL_QUERY}</label> <span
                                        class="badge badge-info"><i class="fa fa-question-circle" data-container="body"
                                                                    data-toggle="popover" data-placement="top"
                                                                    title="{$INFO}"
                                                                    data-content="{$EXTERNAL_QUERY_INFO}"></i></span>
                                <input type="hidden" name="external_query" value="0">
                                <input id="inputExternalQuery" name="external_query" type="checkbox" class="js-switch"
                                       value="1" {if $EXTERNAL_QUERY_VALUE} checked{/if} />
                            </div>

                            <div class="form-group">
                                <label for="inputStatusPage">{$STATUS_PAGE}</label>
                                <input type="hidden" name="status_page" value="0">
                                <input id="inputStatusPage" name="status_page" type="checkbox" class="js-switch"
                                       value="1" {if $STATUS_PAGE_VALUE} checked{/if} />
                            </div>

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
                    {$CONFIRM_DELETE_SERVER}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{$NO}</button>
                    <form action="" method="post" id="deleteLink" style="display: inline">
                        <input type="hidden" name="token" value="{$TOKEN}" />
                        <input type="submit" class="btn btn-primary" value="{$YES}" />
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- End Wrapper -->
</div>

{include file='scripts.tpl'}

<script type="text/javascript">
  function showDeleteModal(link) {
    $('#deleteLink').attr('action', link);
    $('#deleteModal').modal().show();
  }
</script>


<script type="text/javascript">
  $(document).ready(function () {
    $("#sortable").sortable({
      start: function (event, ui) {
        let start_pos = ui.item.index();
        ui.item.data('startPos', start_pos);
      },
      update: function (event, ui) {
        let servers = $("#sortable").children();
        let toSubmit = [];
        servers.each(function () {
          toSubmit.push($(this).data().id);
        });

        $.ajax({
          url: "{$REORDER_DRAG_URL}",
          type: "POST",
          data: {
            token: "{$TOKEN}",
            {literal}servers: JSON.stringify({"servers": toSubmit}){/literal}
          },
          success: function (response) {
            // Success
          },
          error: function (xhr) {
            // Error
            console.log(xhr);
          }
        });
      }
    });
  });
</script>

</body>

</html>