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
                                <span class="float-md-right mr-1"><a href="{$BACK_LINK}" class="btn btn-primary">{$BACK}</a></span>
                                {if $IS_ADMIN}
                                  <span class="float-md-right mr-1"><a href="{$PERMISSION_LINK}" class="btn btn-primary">{$PERMISSION_LABEL}</a></span>
                                {/if}
                                
                            </div>
                        </div>
                        <hr />

                        <!-- Success and Error Alerts -->
                        {include file='includes/alerts.tpl'}

                    </div>
                </div>

            {if !empty($SERVER)}
            <section class="terminal-container terminal-fixed-top">
              <header class="terminal">
                <span class="button red"></span>
                <span class="button yellow"></span>
                <span class="button green"></span>
              </header>

              <div id="terminal" class="terminal-home overflow-auto"></div>
              <form action="" method="post">
                <div class="input-group">
                  <input type="commands" id="cmd_input" class="form-control form-control-sm">
                  <button type="button" onclick="cmdSend('input')" class="btn btn-sm btn-primary"><i class="fas fa-greater-than"></i></button>
                  <input id="token" type="hidden" name="token" value="{$TOKEN}">

                </div>
              </form>
            </section>
          {/if}

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