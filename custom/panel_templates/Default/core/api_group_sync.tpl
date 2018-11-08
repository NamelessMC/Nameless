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
                        <h1 class="m-0 text-dark">{$API}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                            <li class="breadcrumb-item active">{$API}</li>
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
                            <a class="btn btn-primary" href="{$BACK_LINK}">{$BACK}</a>
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

                            <div class="callout callout-info">
                                <h5><i class="icon fa fa-info-circle"></i> {$INFO}</h5>
                                {$GROUP_SYNC_INFO}
                            </div>

                            {if count($GROUP_SYNC_VALUES)}
                                <h5>{$EXISTING_RULES}</h5>
                                <form action="" method="post">
                                    {foreach from=$GROUP_SYNC_VALUES item=group_sync}
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="inputIngame">{$INGAME_GROUP_NAME}</label>
                                                    <input class="form-control" name="ingame_group[{$group_sync.id}]" type="text" id="inputIngame" placeholder="{$INGAME_GROUP_NAME}" value="{$group_sync.ingame}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="inputWebsite">{$WEBSITE_GROUP}</label>
                                                    <select name="website_group[{$group_sync.id}]" class="form-control" id="inputWebsite">
                                                        {foreach from=$GROUPS item=group}
                                                            <option value="{$group.id}"{if $group_sync.website == $group.id} selected{/if}>{$group.name}</option>
                                                        {/foreach}
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="inputSetAsPrimary">{$SET_AS_PRIMARY_GROUP}</label> <span class="badge badge-info" data-toggle="popover" data-title="{$INFO}" data-content="{$SET_AS_PRIMARY_GROUP_INFO}"><i class="fas fa-question-circle"></i></span>
                                                    <select name="primary_group[{$group_sync.id}]" class="form-control" id="inputSetAsPrimary">
                                                        <option value="0"{if $group_sync.primary == 0} selected{/if}>{$NO}</option>
                                                        <option value="1"{if $group_sync.primary == 1} selected{/if}>{$YES}</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <div style="height:32px"></div>
                                                    <a href="{$group_sync.delete_link}" class="btn btn-danger">{$DELETE}</a>
                                                </div>
                                            </div>
                                        </div>
                                    {/foreach}
                                    <div class="form-group">
                                        <input type="hidden" name="token" value="{$TOKEN}">
                                        <input type="hidden" name="action" value="update">
                                        <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
                                    </div>
                                </form>
                                <hr />
                            {/if}

                            <h5>{$NEW_RULE}</h5>
                            <form action="" method="post">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="inputIngame">{$INGAME_GROUP_NAME}</label>
                                            <input class="form-control" name="ingame_rank_name" type="text" id="inputIngame" placeholder="{$INGAME_GROUP_NAME}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="inputWebsite">{$WEBSITE_GROUP}</label>
                                            <select name="website_group" class="form-control" id="inputWebsite">
                                                {foreach from=$GROUPS item=group}
                                                    <option value="{$group.id}">{$group.name}</option>
                                                {/foreach}
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="inputSetAsPrimary">{$SET_AS_PRIMARY_GROUP}</label> <span class="badge badge-info" data-toggle="popover" data-title="{$INFO}" data-content="{$SET_AS_PRIMARY_GROUP_INFO}"><i class="fas fa-question-circle"></i></span>
                                            <select name="primary_group" class="form-control" id="inputSetAsPrimary">
                                                <option value="0">{$NO}</option>
                                                <option value="1">{$YES}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="token" value="{$TOKEN}">
                                    <input type="hidden" name="action" value="create">
                                    <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
                                </div>
                            </form>

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