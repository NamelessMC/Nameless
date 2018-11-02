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
                        <h1 class="m-0 text-dark">{$MINECRAFT}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$INTEGRATIONS}</li>
                            <li class="breadcrumb-item active">{$MINECRAFT}</li>
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

                            <form id="enableMinecraft" action="" method="post">
                                <div class="form-group">
                                    <label for="inputEnableMinecraft">{$ENABLE_MINECRAFT_INTEGRATION}</label>
                                    <input type="hidden" name="enable_minecraft" value="0">
                                    <input id="inputEnableMinecraft" name="enable_minecraft" type="checkbox" class="js-switch js-check-change"{if $MINECRAFT_ENABLED eq 1} checked{/if} value="1"/>
                                    <input type="hidden" name="token" value="{$TOKEN}">
                                </div>
                            </form>

                            {if $MINECRAFT_ENABLED eq 1}
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        {if isset($AUTHME)}
                                            <tr>
                                                <td>
                                                    <a href="{$AUTHME_LINK}">{$AUTHME}</a>
                                                </td>
                                            </tr>
                                        {/if}
                                        {if isset($ACCOUNT_VERIFICATION)}
                                            <tr>
                                                <td>
                                                    <a href="{$ACCOUNT_VERIFICATION_LINK}">{$ACCOUNT_VERIFICATION}</a>
                                                </td>
                                            </tr>
                                        {/if}
                                        {if isset($SERVERS)}
                                            <tr>
                                                <td>
                                                    <a href="{$SERVERS_LINK}">{$SERVERS}</a>
                                                </td>
                                            </tr>
                                        {/if}
                                        {if isset($QUERY_ERRORS)}
                                            <tr>
                                                <td>
                                                    <a href="{$QUERY_ERRORS_LINK}">{$QUERY_ERRORS}</a>
                                                </td>
                                            </tr>
                                        {/if}
                                        {if isset($BANNERS)}
                                            <tr>
                                                <td>
                                                    <a href="{$BANNERS_LINK}">{$BANNERS}</a>
                                                </td>
                                            </tr>
                                        {/if}
                                    </table>
                                </div>
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

</body>
</html>