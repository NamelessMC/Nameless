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
                    <h1 class="h3 mb-0 text-gray-800">{$TEMPLATES}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                        <li class="breadcrumb-item active">{$LAYOUT}</li>
                        <li class="breadcrumb-item active">{$TEMPLATES}</li>
                    </ol>
                </div>

                <!-- Update Notification -->
                {include file='includes/update.tpl'}

                <div class="card shadow mb-4">
                    <div class="card-body">

                        <div class="row" style="margin-bottom: 15px;">
                            <div class="col-md-9">
                                <h5 style="margin-top: 7px; margin-bottom: 7px;">{$EDITING_TEMPLATE}</h5>
                            </div>
                            <div class="col-md-3">
                                <span class="float-md-right"><a href="{$BACK_LINK}" class="btn btn-primary">{$BACK}</a></span>
                            </div>
                        </div>

                        <!-- Success and Error Alerts -->
                        {include file='includes/alerts.tpl'}

                        <form role="form" action="" method="post">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <colgroup>
                                        <col span="1" style="width:70%">
                                        <col span="1" style="width:30%">
                                    </colgroup>
                                    <thead>
                                    <tr>
                                        <th>{$GROUP}</th>
                                        <th><a href="#" onclick="selectAllPerms(); return false;">{$SELECT_ALL}</a> | <a
                                                    href="#"
                                                    onclick="deselectAllPerms(); return false;">{$DESELECT_ALL}</a></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>{$GUEST}</td>
                                        <td><input type="hidden" name="perm-use-0" value="0" /><input
                                                    onclick="colourUpdate(this);" class="js-switch" name="perm-use-0"
                                                    id="Input-use-0" value="1"
                                                    type="checkbox" {if isset($GUEST_PERMISSIONS->can_use_template) && $GUEST_PERMISSIONS->can_use_template eq 1} checked{/if} />
                                        </td>
                                    </tr>
                                    {foreach from=$GROUP_PERMISSIONS item=group}
                                        <tr>
                                            <td>{$group->name|escape}</td>
                                            <td><input type="hidden" name="perm-use-{$group->id|escape}" value="0" />
                                                <input onclick="colourUpdate(this);" class="js-switch"
                                                       name="perm-use-{$group->id|escape}"
                                                       id="Input-use-{$group->id|escape}" value="1"
                                                       type="checkbox" {if isset($group->can_use_template) && $group->can_use_template eq 1} checked{/if} />
                                            </td>
                                        </tr>
                                    {/foreach}
                                    </tbody>
                                </table>
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

    <!-- End Wrapper -->
</div>

{include file='scripts.tpl'}

<script type="text/javascript">
  function selectAllPerms() {
    var table = $('table');
    table.find('tbody tr td .js-switch').each(function () {
      $(this).prop('checked', true);
      onChange(this);
    });
    return false;
  }

  function deselectAllPerms() {
    var table = $('table');
    table.find('tbody tr td .js-switch').each(function () {
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