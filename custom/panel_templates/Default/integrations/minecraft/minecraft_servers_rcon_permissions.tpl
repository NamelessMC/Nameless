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
                    {$PERMISSION_LABEL} {$SERVER->name}
                </strong>

                <!-- Update Notification -->
                {include file='includes/update.tpl'}

                <div class="card shadow mb-4">
                    <div class="card-body">
                      
                        <div class="row">
                            <div class="col">
                                <span class="float-md-right mr-1"><a href="{$BACK_LINK}" class="btn btn-primary">{$BACK}</a></span>
                            </div>
                        </div>
                        <hr />

                        <!-- Success and Error Alerts -->
                        {include file='includes/alerts.tpl'}

                        <div class="table-responsive">
                          <table class="table table-striped">
                              <thead>
                              </thead>
                              <tbody>
                                  <form id="group-form" action="" method="post">
                                    {foreach from=$GROUPS  item=group}
                                    {if $group->id == 2}
                                      {continue}
                                    {/if}
                                    {if isset($group->permissions[$SERVER_PERMISSION])}
                                    <tr>
                                      <td onclick="checkboxToggle('{$group->id}'); this.classList.toggle('bg-danger');" class="bg-success text-white">
                                        {$group->name}
                                        <input style="display:none" type="checkbox" name="group[]" id="group-{$group->id}" value="{$group->id}" checked />
                                      </td>
                                    </tr>
                                    {else}
                                      <tr>
                                      <td onclick="checkboxToggle('{$group->id}'); this.classList.toggle('bg-danger');" class="bg-success bg-danger text-white">
                                        {$group->name}
                                        <input style="display:none" type="checkbox" name="group[]" id="group-{$group->id}" value="{$group->id}" />
                                      </td>
                                    </tr>
                                    {/if}
                                    
                                    {/foreach}
                                    <input type="hidden" name="permissions" value="1">
                                    <input type="hidden" name="token" value="{$TOKEN}">
                                  </form>

                                  

                              </tbody>
                          </table>
                          <input type="submit" class="btn btn-success" value="SUBMIT" form="group-form">
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
<script>
function checkboxToggle(id){
    if (document.getElementById("group-" + id).checked !== true) {
      document.getElementById("group-" + id).checked = true;
    } else {
      document.getElementById("group-" + id).checked = false;
    }
}
</script>

</body>

</html>