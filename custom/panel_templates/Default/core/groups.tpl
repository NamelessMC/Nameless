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
                        <a class="btn btn-primary" style="margin-bottom: 10px" href="{$NEW_GROUP_LINK}">{$NEW_GROUP}</a>
                        <a class="btn btn-primary" style="margin-bottom: 10px" href="{$GROUP_SYNC_LINK}"><i class="fas fa-external-link-alt"></i> {$GROUP_SYNC}</a>

                        <!-- Success and Error Alerts -->
                        {include file='includes/alerts.tpl'}

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>{$ORDER}</th>
                                    <th>{$GROUP_ID}</th>
                                    <th>{$NAME}</th>
                                    <th>{$USERS}</th>
                                    <th>{$STAFF}</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="sortable">
                                {foreach from=$GROUP_LIST item=group}
                                    <tr data-id="{$group.id}">
                                        <td>{$group.order}</td>
                                        <td>{$group.id}</td>
                                        <td><a href="{$group.edit_link}">{$group.name}</a></td>
                                        <td>{$group.users}</td>
                                        <td>{if $group.staff}
                                                <i class="fas fa-check-circle text-success"></i>
                                            {else}
                                                <i class="fas fa-times-circle text-danger"></i>
                                            {/if}</td>
                                        <td>
                                            <div class="float-md-right">
                                                <div class="btn btn-secondary btn-sm"><i class="fas fa-arrows-alt"></i></div>
                                                <a href="{$group.edit_link}" class="btn btn-warning btn-sm"><i class="fas fa-edit fa-fw"></i></a>
                                            </div>
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

<script type="text/javascript">
  $(document).ready(function () {
    $("#sortable").sortable({
      start: function (event, ui) {
        let start_pos = ui.item.index();
        ui.item.data('startPos', start_pos);
      },
      update: function (event, ui) {
        let groups = $("#sortable").children();
        let toSubmit = [];
        groups.each(function () {
          toSubmit.push($(this).data().id);
        });

        $.ajax({
          url: "{$REORDER_DRAG_URL}",
          type: "POST",
          data: {
            token: "{$TOKEN}",
            {literal}groups: JSON.stringify({"groups": toSubmit}){/literal}
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