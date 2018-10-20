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

                            <h5 style="display:inline">{$EDITING_TEMPLATE}</h5>
                            <div class="float-md-right"><a href="{$BACK_LINK}" class="btn btn-primary">{$BACK}</a></div>
                            <hr />

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
                                    <h5><i class="icon fa fa-warning"></i> {$ERRORS_TITLE}</h5>
                                    <ul>
                                        {foreach from=$ERRORS item=error}
                                            <li>{$error}</li>
                                        {/foreach}
                                    </ul>
                                </div>
                            {/if}

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