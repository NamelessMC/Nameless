{include file='header.tpl'}
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    {include file='navbar.tpl'}
    {include file='sidebar.tpl'}

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">{$ACCOUNT_VERIFICATION}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$INTEGRATIONS}</li>
                            <li class="breadcrumb-item"><a href="{$MINECRAFT_LINK}">{$MINECRAFT}</a></li>
                            <li class="breadcrumb-item active">{$ACCOUNT_VERIFICATION}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                {if isset($NEW_UPDATE)}
                {if $NEW_UPDATE_URGENT eq true}
                <div class="alert alert-danger">
                    {else}
                    <div class="alert alert-primary alert-dismissible" id="updateAlert">
                        <button type="button" class="close" id="closeUpdate" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {/if}
                        {$NEW_UPDATE}
                        <br />
                        <a href="{$UPDATE_LINK}" class="btn btn-primary" style="text-decoration:none">{$UPDATE}</a>
                        <hr />
                        {$CURRENT_VERSION}<br />
                        {$NEW_VERSION}
                    </div>
                    {/if}

                    <div class="card">
                        <div class="card-body">
                            {if isset($SUCCESS)}
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h5><i class="icon fa fa-check"></i> {$SUCCESS_TITLE}</h5>
                                    {$SUCCESS}
                                </div>
                            {/if}

                            {if isset($ERRORS) && count($ERRORS)}
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h5><i class="icon fas fa-exclamation-triangle"></i> {$ERRORS_TITLE}</h5>
                                    <ul>
                                        {foreach from=$ERRORS item=error}
                                            <li>{$error}</li>
                                        {/foreach}
                                    </ul>
                                </div>
                            {/if}

                            <form id="enablePremium" action="" method="post">
                                <label for="inputPremiumAccounts">{$FORCE_PREMIUM_ACCOUNTS}</label>
                                <input type="hidden" name="enable_premium_accounts" value="0">
                                <input id="inputPremiumAccounts" name="enable_premium_accounts" type="checkbox" class="js-switch js-check-change"{if $FORCE_PREMIUM_ACCOUNTS_VALUE} checked{/if} value="1"/>
                                <input type="hidden" name="premium" value="1">
                                <input type="hidden" name="token" value="{$TOKEN}">
                            </form>

                            {if $FORCE_PREMIUM_ACCOUNTS_VALUE}
                                <hr />

                                <div class="callout callout-info">
                                    <h5><i class="icon fa fa-info-circle"></i> {$INFO}</h5>
                                    {$MCASSOC_INFO}
                                </div>

                                <form action="" method="post">
                                    <div class="form-group">
                                        <label for="use_mcassoc">{$USE_MCASSOC}</label>
                                        <input id="use_mcassoc" name="use_mcassoc" type="checkbox" class="js-switch"{if $USE_MCASSOC_VALUE} checked{/if} />
                                    </div>
                                    <div class="form-group">
                                        <label for="mcassoc_key">{$MCASSOC_KEY}</label>
                                        <input type="text" class="form-control" name="mcassoc_key" id="mcassoc_key" value="{$MCASSOC_KEY_VALUE}" placeholder="{$MCASSOC_KEY}">
                                    </div>
                                    <div class="form-group">
                                        <label for="mcassoc_instance">{$MCASSOC_INSTANCE}</label>
                                        <input type="text" class="form-control" name="mcassoc_instance" id="mcassoc_instance" value="{$MCASSOC_INSTANCE_VALUE}" placeholder="{$MCASSOC_INSTANCE}">
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

                </div>
        </section>
    </div>

    {include file='footer.tpl'}

</div>
<!-- ./wrapper -->

{include file='scripts.tpl'}

<script type="text/javascript">
    function generateInstance() {
        var text = "";
        var possible = "abcdef0123456789";
        // thanks SO 1349426
        for(var i = 0; i < 32; i++)
            text += (possible.charAt(Math.floor(Math.random() * possible.length)));

        document.getElementById("mcassoc_instance").setAttribute("value", text);
    }
</script>

</body>
</html>