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
                            <h1 class="m-0 text-dark">{$API_ENDPOINTS}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                                <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                                <li class="breadcrumb-item active">{$API_ENDPOINTS}</li>
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
                                <p style="display:inline;">{$ENDPOINTS_INFO}</p>
                                <span class="float-md-right"><a class="btn btn-warning" href="{$BACK_LINK}">{$BACK}</a></span>
                                <hr />

                                {if count($ENDPOINTS_ARRAY)}
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <th>{$ROUTE}</th>
                                            <th>{$DESCRIPTION}</th>
                                            <th>{$MODULE}</th>
                                            {foreach from=$ENDPOINTS_ARRAY item=endpoint}
                                                <tr>
                                                    <td>
                                                        <div>/{$endpoint.route}</div>
                                                    </td>
                                                    <td>
                                                        <div>{$endpoint.description}</div>    
                                                    </td>
                                                    <td>
                                                        <div>{$endpoint.module}</div>
                                                    </td>
                                                </tr>
                                            {/foreach}
                                        </table>
                                    </div>
                                <hr />
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