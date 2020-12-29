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
                                <div class="table-responsive">
                                    <table id="{$key|escape}" class="table table-striped">
                                        <colgroup>
                                            <col span="1" style="width:70%">
                                            <col span="1" style="width:30%">
                                        </colgroup>
                                        <thead>
                                        <tr>
                                            <th>{$key|escape}</th>
                                            <th><a href="#"
                                                   onclick="selectAllPerms('{$key|escape}'); return false;">{$SELECT_ALL}</a>
                                                | <a href="#"
                                                     onclick="deselectAllPerms('{$key|escape}'); return false;">{$DESELECT_ALL}</a>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        {foreach from=$item key=permission item=title}
                                            <tr>
                                                <td>{$title}</td>
                                                <td><input type="checkbox" name="permissions[{$permission|escape}]"
                                                           class="js-switch"
                                                           value="1" {if is_array($PERMISSIONS_VALUES) && array_key_exists($permission|escape, $PERMISSIONS_VALUES)} checked{/if}>
                                                </td>
                                            </tr>
                                        {/foreach}
                                        </tbody>
                                    </table>
                                </div>
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

<script type="text/javascript">
  function selectAllPerms(section) {
    var table = $('table#' + section);
    table.find('tbody tr td .js-switch').each(function () {
      $(this).prop('checked', true);
      onChange(this);
    });
    return false;
  }

  function deselectAllPerms(section) {
    var table = $('table#' + section);
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