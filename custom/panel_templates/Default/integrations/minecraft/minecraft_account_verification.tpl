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
                    <h1 class="h3 mb-0 text-gray-800">{$ACCOUNT_VERIFICATION}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                        <li class="breadcrumb-item active">{$INTEGRATIONS}</li>
                        <li class="breadcrumb-item"><a href="{$MINECRAFT_LINK}">{$MINECRAFT}</a></li>
                        <li class="breadcrumb-item active">{$ACCOUNT_VERIFICATION}</li>
                    </ol>
                </div>

                <!-- Update Notification -->
                {include file='includes/update.tpl'}

                <div class="card shadow mb-4">
                    <div class="card-body">

                        <!-- Success and Error Alerts -->
                        {include file='includes/alerts.tpl'}

                        <form id="enablePremium" action="" method="post">
                            <label for="inputPremiumAccounts">{$FORCE_PREMIUM_ACCOUNTS}</label>
                            <input type="hidden" name="enable_premium_accounts" value="0">
                            <input id="inputPremiumAccounts" name="enable_premium_accounts" type="checkbox"
                                   class="js-switch js-check-change" {if $FORCE_PREMIUM_ACCOUNTS_VALUE} checked{/if}
                                   value="1" />
                            <input type="hidden" name="premium" value="1">
                            <input type="hidden" name="token" value="{$TOKEN}">
                        </form>

                        {if $FORCE_PREMIUM_ACCOUNTS_VALUE}
                            <hr />
                            <div class="card shadow border-left-primary">
                                <div class="card-body">
                                    <h5><i class="icon fa fa-info-circle"></i> {$INFO}</h5>
                                    {$MCASSOC_INFO}
                                </div>
                            </div>
                            <br />
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="use_mcassoc">{$USE_MCASSOC}</label>
                                    <input id="use_mcassoc" name="use_mcassoc" type="checkbox"
                                           class="js-switch" {if $USE_MCASSOC_VALUE} checked{/if} />
                                </div>
                                <div class="form-group">
                                    <label for="mcassoc_key">{$MCASSOC_KEY}</label>
                                    <input type="text" class="form-control" name="mcassoc_key" id="mcassoc_key"
                                           value="{$MCASSOC_KEY_VALUE}" placeholder="{$MCASSOC_KEY}">
                                </div>
                                <div class="form-group">
                                    <label for="mcassoc_instance">{$MCASSOC_INSTANCE}</label>
                                    <input type="text" class="form-control" name="mcassoc_instance"
                                           id="mcassoc_instance" value="{$MCASSOC_INSTANCE_VALUE}"
                                           placeholder="{$MCASSOC_INSTANCE}">
                                    <br />
                                    <p>{$MCASSOC_INSTANCE_HELP}</p>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="token" value="{$TOKEN}">
                                    <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
                                </div>
                            </form>
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

<script type="text/javascript">
  function generateInstance() {
    var text = "";
    var possible = "abcdef0123456789";
    // thanks SO 1349426
    for (var i = 0; i < 32; i++)
      text += (possible.charAt(Math.floor(Math.random() * possible.length)));

    document.getElementById("mcassoc_instance").setAttribute("value", text);
  }
</script>

</body>

</html>