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
                                    <h5><i class="icon fas fa-exclamation-triangle"></i> {$ERRORS_TITLE}</h5>
                                    <ul>
                                        {foreach from=$ERRORS item=error}
                                            <li>{$error}</li>
                                        {/foreach}
                                    </ul>
                                </div>
                            {/if}

                            <form role="form" action="" method="post">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <colgroup>
                                            <col span="1" style="width:70%">
                                            <col span="1" style="width:30%">
                                        </colgroup>
                                        <thead>
                                        <tr>
                                            <th>{$GROUP}</th>
                                            <th><a href="#" onclick="selectAllPerms(); return false;">{$SELECT_ALL}</a> | <a href="#" onclick="deselectAllPerms(); return false;">{$DESELECT_ALL}</a></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>{$GUEST}</td>
                                            <td><input type="hidden" name="perm-use-0" value="0" /><input onclick="colourUpdate(this);" class="js-switch" name="perm-use-0" id="Input-use-0" value="1" type="checkbox"{if isset($GUEST_PERMISSIONS->can_use_template) && $GUEST_PERMISSIONS->can_use_template eq 1} checked{/if} /></td>
                                        </tr>
                                        {foreach from=$GROUP_PERMISSIONS item=group}
                                            <tr>
                                                <td>{$group->name|escape}</td>
                                                <td><input type="hidden" name="perm-use-{$group->id|escape}" value="0" /> <input onclick="colourUpdate(this);" class="js-switch" name="perm-use-{$group->id|escape}" id="Input-use-{$group->id|escape}" value="1" type="checkbox"{if isset($group->can_use_template) && $group->can_use_template eq 1} checked{/if} /></td>
                                            </tr>
                                        {/foreach}
                                        </tbody>
                                    </table>
                                </div>

                                <div class="form-group">
                                    <input type="hidden" name="token" value="{$TOKEN}">
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

<script type="text/javascript">
    function selectAllPerms(){
        var table= $('table');
        table.find('tbody tr td .js-switch').each(function () {
            $(this).prop('checked', true);
            onChange(this);
        });
        return false;
    }
    function deselectAllPerms(){
        var table= $('table');
        table.find('tbody tr td .js-switch').each(function () {
            $(this).prop('checked', false);
            onChange(this);
        });
        return false;
    }
    function onChange(el) {
        if (typeof Event === 'function' || !document.fireEvent) {
            var event = document.createEvent('HTMLEvents');
            event.initEvent('change', true, true);
            el.dispatchEvent(event);
        } else {
            el.fireEvent('onchange');
        }
    }
</script>

</body>
</html>