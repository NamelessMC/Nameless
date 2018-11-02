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
                        <h1 class="m-0 text-dark">{$REPORTS}{if isset($VIEWING_USER)} | {$VIEWING_USER}{/if}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$USER_MANAGEMENT}</li>
                            <li class="breadcrumb-item active">{$REPORTS}</li>
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
                            <a href="{$CHANGE_VIEW_LINK}" class="btn btn-info">{$CHANGE_VIEW}</a>
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
                                    <h5><i class="icon fas fa-exclamation-triangle"></i> {$ERRORS_TITLE}</h5>
                                    <ul>
                                        {foreach from=$ERRORS item=error}
                                            <li>{$error}</li>
                                        {/foreach}
                                    </ul>
                                </div>
                            {/if}

                            {if count($ALL_REPORTS)}
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>{$USER_REPORTED}</th>
                                            <th>{$UPDATED_BY}</th>
                                            <th>{$COMMENTS}</th>
                                            <th>{$ACTIONS}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        {foreach from=$ALL_REPORTS item=report}
                                            <tr>
                                                <td><a href="{$report.user_profile}" style="{$report.user_reported_style}"><img src="{$report.user_reported_avatar}" style="max-height:25px;max-width:25px;" alt="{$report.user_reported}" class="rounded"> {$report.user_reported}</a><br /><span data-toggle="tooltip" data-original-title="{$report.reported_at_full}">{$report.reported_at}</span></td>
                                                <td><a href="{$report.updated_by_profile}" style="{$report.updated_by_style}"><img src="{$report.updated_by_avatar}" style="max-height:25px;max-width:25px;" alt="{$report.updated_by}" class="rounded"> {$report.updated_by}</a><br /><span data-toggle="tooltip" data-original-title="{$report.updated_at_full}">{$report.updated_at}</span></td>
                                                <td><i class="fa fa-comments" aria-hidden="true"></i> {$report.comments}</td>
                                                <td><a href="{$report.link}" class="btn btn-primary">{$VIEW} &raquo;</a></td>
                                            </tr>
                                        {/foreach}
                                        </tbody>
                                    </table>
                                </div>

                                {$PAGINATION}

                            {elseif isset($NO_REPORTS)}
                                {$NO_REPORTS}
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