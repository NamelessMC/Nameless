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
                <strong class="d-sm-flex align-items-center justify-content-center mb-4">
                    {$MINECRAFT_SERVERS}
                </strong>

                <!-- Update Notification -->
                {include file='includes/update.tpl'}

                <div class="card shadow mb-4">
                    <div class="card-body">

                        <div class="row">
                            <div class="col">
                                <span class="float-md-right mr-1"><a href="{$NEW_SERVER_LINK}" class="btn btn-primary">{$NEW_SERVER}</a></span>
                                <span class="float-md-right mr-1"><a href="{$MINECRAFT_SERVERS_LINK}" class="btn btn-primary"><i class="fas fa-list"></i> {$MINECRAFT_SERVERS}</a></span>
																{* <span class="float-md-right mr-1"><a href="" class="btn btn-primary"><i class="fas fa-cog"></i> SETTINGS</a></span> *}
                            </div>
                        </div>
                        <hr />

                        <!-- Success and Error Alerts -->
                        {include file='includes/alerts.tpl'}


              {if count($SERVERS)}
                <div class="table-responsive">
                <table class="table table-striped">
                  <tbody>
                 {foreach from=$SERVERS item=server}
                      <tr>
                        <td>
                          <strong><a href="{$CONSOLE_RCON_LINK}{$server->id}">{$server->name}</strong>
                        </td>
               
                          <td>
                            <div class="float-md-right">
                              <a class="btn btn-warning btn-sm" href="{$EDIT_SERVER_LINK}{$server->id}"><i
                                  class="fas fa-edit fa-fw"></i></a>
                            </div>
                          </td>
                   {/foreach}
                      </tr>
   
                  </tbody>
                </table>
              </div>
            {else}
                {$NO_RCON_SERVERS}
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

    <!-- End Wrapper -->
</div>

{include file='scripts.tpl'}

</body>

</html>