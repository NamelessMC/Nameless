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
                        <h1 class="m-0 text-dark">{$PUNISHMENTS}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$USER_MANAGEMENT}</li>
                            <li class="breadcrumb-item active">{$PUNISHMENTS}</li>
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
                            <button onclick="showSearchModal()" class="btn btn-primary"><i class="fa fa-search"></i> {$SEARCH}</button>

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

                            {if isset($RESULTS)}
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <colgroup>
                                            <col span="1" style="width: 20%;">
                                            <col span="1" style="width: 20%;">
                                            <col span="1" style="width: 20%;">
                                            <col span="1" style="width: 20%;">
                                            <col span="1" style="width: 20%;">
                                        </colgroup>
                                        <thead>
                                        <tr>
                                            <th>{$USERNAME}</th>
                                            <th>{$STAFF}</th>
                                            <th>{$TYPE}</th>
                                            <th>{$WHEN}</th>
                                            <th>{$ACTIONS}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        {foreach from=$RESULTS item=result}
                                            <tr>
                                                <td><a href="{$result.profile}" style="{$result.style}"><img src="{$result.avatar}" class="rounded" style="max-width:25px;max-height:25px;"> {$result.nickname}</a></td>
                                                <td><a href="{$result.staff_profile}" style="{$result.staff_style}"><img src="{$result.staff_avatar}" class="rounded" style="max-width:25px;max-height:25px;"> {$result.staff_nickname}</a></td>
                                                <td>
                                                    {if $result.type_numeric == 1}
                                                        <span class="badge badge-danger">{$result.type}</span>
                                                    {elseif $result.type_numeric == 2}
                                                        <span class="badge badge-warning">{$result.type}</span>
                                                    {elseif $result.type_numeric == 3}
                                                        <span class="badge badge-danger">{$result.type}</span>
                                                    {/if}
                                                    {if $result.revoked == 1}
                                                        <span class="badge badge-info">{$REVOKED}</span>
                                                    {/if}
                                                    {if $result.acknowledged == 1}
                                                        <span class="badge badge-success">{$ACKNOWLEDGED}</span>
                                                    {/if}
                                                </td>
                                                <td><span data-toggle="tooltip" data-original-title="{$result.time_full}">{$result.time}</span></td>
                                                <td><a href="{$result.link}" class="btn btn-info">{$VIEW_USER}</a></td>
                                            </tr>
                                        {/foreach}
                                        </tbody>
                                    </table>
                                </div>
                                <br />
                                {$PAGINATION}
                            {else}
                                {$NO_PUNISHMENTS}
                            {/if}

                        </div>
                    </div>

                    <!-- Spacing -->
                    <div style="height:1rem;"></div>

                </div>
        </section>
    </div>

    <div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="searchModalLabel">{$SEARCH}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="InputUsername">{$USERNAME}</label>
                            <input type="text" placeholder="{$USERNAME}" class="form-control" id="InputUsername" name="username">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">{$CANCEL}</button>
                        <input type="hidden" name="token" value="{$TOKEN}">
                        <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
                    </div>
                </form>
            </div>
        </div>
    </div>

    {include file='footer.tpl'}

</div>
<!-- ./wrapper -->

{include file='scripts.tpl'}

<script type="text/javascript">
    function showSearchModal(){
        $('#searchModal').modal().show();
    }
</script>

</body>
</html>