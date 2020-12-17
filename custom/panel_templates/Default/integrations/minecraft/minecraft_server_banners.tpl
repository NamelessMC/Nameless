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
                        <h1 class="m-0 text-dark">{$SERVER_BANNERS}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$INTEGRATIONS}</li>
                            <li class="breadcrumb-item"><a href="{$MINECRAFT_LINK}">{$MINECRAFT}</a></li>
                            <li class="breadcrumb-item active">{$SERVER_BANNERS}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                {include file='includes/update.tpl'}

                <div class="card">
                    <div class="card-body">

                        {include file='includes/success.tpl'}

                        {include file='includes/errors.tpl'}

                        {if isset($NO_SERVERS)}
                            <p>{$NO_SERVERS}</p>

                        {else}
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tbody>
                                    {foreach from=$SERVERS item=server}
                                        <tr>
                                            <td>{$server.name}</td>
                                            <td>
                                                <div class="float-md-right">
                                                    <a href="{$server.edit_link}" class="btn btn-warning">{$EDIT}</a>
                                                    <a href="{$server.view_link}" class="btn btn-info">{$VIEW}</a>
                                                </div>
                                            </td>
                                        </tr>
                                    {/foreach}
                                    </tbody>
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