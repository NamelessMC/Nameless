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
                        <h1 class="m-0 text-dark">{$TEMPLATES}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$LAYOUT}</li>
                            <li class="breadcrumb-item active">{$TEMPLATES}</li>
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

                        <h5 style="display:inline">{$EDITING_TEMPLATE}</h5>
                        <div class="float-md-right">
                            {if $PERMISSIONS_LINK}<a class="btn btn-info" href="{$PERMISSIONS_LINK}">{$PERMISSIONS}</a>{/if}
                            <a href="{$BACK_LINK}" class="btn btn-primary">{$BACK}</a>
                        </div>
                        <hr />

                        {include file='includes/success.tpl'}

                        {include file='includes/errors.tpl'}

                        {if isset($DEFAULT_TEMPLATE_WARNING)}
                            <div class="alert alert-warning">{$DEFAULT_TEMPLATE_WARNING}</div>
                        {/if}

                        <div class="table-responsive">
                            <table class="table table-striped">
                                {if count($TEMPLATE_DIRS)}
                                    {foreach from=$TEMPLATE_DIRS item=dir}
                                        <tr>
                                            <td>
                                                <i class="fa fa-folder"></i> {$dir.name}
                                                <div class="float-right">
                                                    <a href="{$dir.link}" class="btn btn-primary btn-sm"><i class="fas fa-search fa-fw"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    {/foreach}
                                {/if}
                                {if count($TEMPLATE_FILES)}
                                    {foreach from=$TEMPLATE_FILES item=file}
                                        <tr>
                                            <td>
                                                <i class="fa fa-file"></i> {$file.name}
                                                <div class="float-right">
                                                    <a href="{$file.link}" class="btn btn-primary btn-sm"><i class="fas fa-edit fa-fw"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    {/foreach}
                                {/if}
                            </table>
                        </div>

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