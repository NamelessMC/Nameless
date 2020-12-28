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
                    <h1 class="h3 mb-0 text-gray-800">{$PUNISHMENTS}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                        <li class="breadcrumb-item active">{$USER_MANAGEMENT}</li>
                        <li class="breadcrumb-item active">{$PUNISHMENTS}</li>
                    </ol>
                </div>

                <!-- Update Notification -->
                {include file='includes/update.tpl'}

                <div class="card shadow mb-4">
                    <div class="card-body">

                        <button onclick="showSearchModal()" class="btn btn-primary"><i
                                    class="fa fa-search"></i> {$SEARCH}</button>
                        <hr />

                        <!-- Success and Error Alerts -->
                        {include file='includes/alerts.tpl'}

                        {if isset($RESULTS)}
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <colgroup>
                                        <col span="1" style="width: 20%;">
                                        <col span="1" style="width: 20%;">
                                        <col span="1" style="width: 20%;">
                                        <col span="1" style="width: 20%;">
                                        <col span="1" style="width: 20%;">
                                    </colgroup>
                                    <thead>
                                    <tr>
                                        <th>{$USERNAME}</th>
                                        <th>{$STAFF}</th>
                                        <th>{$TYPE}</th>
                                        <th>{$WHEN}</th>
                                        <th>{$ACTIONS}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {foreach from=$RESULTS item=result}
                                        <tr>
                                            <td><a href="{$result.profile}" style="{$result.style}"><img
                                                            src="{$result.avatar}" class="rounded"
                                                            style="max-width:25px;max-height:25px;"> {$result.nickname}
                                                </a></td>
                                            <td><a href="{$result.staff_profile}" style="{$result.staff_style}"><img
                                                            src="{$result.staff_avatar}" class="rounded"
                                                            style="max-width:25px;max-height:25px;"> {$result.staff_nickname}
                                                </a></td>
                                            <td>
                                                {if $result.type_numeric == 1}
                                                    <span class="badge badge-danger">{$result.type}</span>
                                                {elseif $result.type_numeric == 2}
                                                    <span class="badge badge-warning">{$result.type}</span>
                                                {elseif $result.type_numeric == 3}
                                                    <span class="badge badge-danger">{$result.type}</span>
                                                {/if}
                                                {if $result.revoked == 1}
                                                    <span class="badge badge-info">{$REVOKED}</span>
                                                {/if}
                                                {if $result.acknowledged == 1}
                                                    <span class="badge badge-success">{$ACKNOWLEDGED}</span>
                                                {/if}
                                            </td>
                                            <td><span data-toggle="tooltip"
                                                      data-original-title="{$result.time_full}">{$result.time}</span>
                                            </td>
                                            <td><a href="{$result.link}" class="btn btn-info">{$VIEW_USER}</a></td>
                                        </tr>
                                    {/foreach}
                                    </tbody>
                                </table>
                            </div>
                            <br />
                            {$PAGINATION}
                        {else}
                            {$NO_PUNISHMENTS}
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

    <div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="searchModalLabel">{$SEARCH}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="InputUsername">{$USERNAME}</label>
                            <input type="text" placeholder="{$USERNAME}" class="form-control" id="InputUsername"
                                   name="username">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">{$CANCEL}</button>
                        <input type="hidden" name="token" value="{$TOKEN}">
                        <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- End Wrapper -->
</div>

{include file='scripts.tpl'}

<script type="text/javascript">
  function showSearchModal() {
    $('#searchModal').modal().show();
  }
</script>

</body>

</html>