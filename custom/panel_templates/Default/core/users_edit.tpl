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
                        <h1 class="m-0 text-dark">{$USERS}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$USER_MANAGEMENT}</li>
                            <li class="breadcrumb-item active">{$USERS}</li>
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
                            <h5 style="display:inline">{$EDITING_USER}</h5>

                            <div class="float-md-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{$ACTIONS}</button>
                                    <div class="dropdown-menu">
                                        {if isset($DELETE_USER)}<a class="dropdown-item" href="#" onclick="showDeleteModal()">{$DELETE_USER}</a>{/if}
                                        {if isset($UPDATE_MINECRAFT_USERNAME)}<a class="dropdown-item" href="{$UPDATE_MINECRAFT_USERNAME_LINK}">{$UPDATE_MINECRAFT_USERNAME}</a>{/if}
                                        {if isset($UPDATE_UUID)}<a class="dropdown-item" href="{$UPDATE_UUID_LINK}">{$UPDATE_UUID}</a>{/if}
                                        {if isset($VALIDATE_USER)}<a class="dropdown-item" href="{$VALIDATE_USER_LINK}">{$VALIDATE_USER}</a>{/if}
                                    </div>
                                </div>
                                <a href="{$BACK_LINK}" class="btn btn-warning">{$BACK}</a>
                            </div>

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
                                <div class="form-group">
                                    <label for="InputMCUsername">{$USERNAME}</label>
                                    <input type="text" name="username" class="form-control" id="InputMCUsername" placeholder="{$USERNAME}" value="{$USERNAME_VALUE}">
                                </div>
                                {if $DISPLAYNAMES eq true}
                                    <div class="form-group">
                                        <label for="InputUsername">{$NICKNAME}</label>
                                        <input type="text" name="nickname" class="form-control" id="InputUsername" placeholder="{$NICKNAME}" value="{$NICKNAME_VALUE}">
                                    </div>
                                {else}
                                    <input type="hidden" name="nickname" value="{$NICKNAME_VALUE}">
                                {/if}
                                <div class="form-group">
                                    <label for="InputEmail">{$EMAIL_ADDRESS}</label>
                                    <input type="email" name="email" class="form-control" id="InputEmail" placeholder="{$EMAIL_ADDRESS}" value="{$EMAIL_ADDRESS_VALUE}">
                                </div>
                                {if $UUID_LINKING eq true}
                                    <div class="form-group">
                                        <label for="InputUUID">{$UUID}</label>
                                        <input type="text" name="UUID" class="form-control" id="InputUUID" placeholder="{$UUID}" value="{$UUID_VALUE}">
                                    </div>
                                {/if}
                                <div class="form-group">
                                    <label for="InputTitle">{$USER_TITLE}</label>
                                    <input type="text" name="title" class="form-control" id="InputTitle" placeholder="{$USER_TITLE}" value="{$USER_TITLE_VALUE}">
                                </div>
                                {if $PRIVATE_PROFILE_ENABLED eq true}
                                    <div class="form-group">
                                        <label for="inputPrivateProfile">{$PRIVATE_PROFILE}</label>
                                        <select name="privateProfile" class="form-control" id="inputPrivateProfile">
                                            <option value="1"{if $PRIVATE_PROFILE_VALUE eq 1} selected{/if}>{$ENABLED}</option>
                                            <option value="0"{if $PRIVATE_PROFILE_VALUE eq 0} selected{/if}>{$DISABLED}</option>
                                        </select>
                                    </div>
                                {else}
                                    <input type="hidden" name="privateProfile" value="0">
                                {/if}
                                <div class="form-group">
                                    <label for="inputTemplate">{$ACTIVE_TEMPLATE}</label>
                                    <select name="template" class="form-control" id="inputTemplate">
                                        {foreach from=$TEMPLATES item=template}
                                            <option value="{$template.id}"{if $template.active eq true} selected{/if}>{$template.name}</option>
                                        {/foreach}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="InputSignature">{$SIGNATURE}</label>
                                    <textarea style="width:100%" rows="10" name="signature" id="InputSignature">{$SIGNATURE_VALUE}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="InputGroup">{$GROUP}</label>
                                    <select class="form-control" id="InputGroup" name="group"{if isset($CANT_EDIT_GROUP)} disabled{/if}>
                                        {foreach from=$ALL_GROUPS item=item}
                                            <option value="{$item->id}"{if $item->id eq $GROUP_ID} selected{/if}>{$item->name|escape}</option>
                                        {/foreach}
                                    </select>
                                </div>
                                {if isset($CANT_EDIT_GROUP)}
                                    <div class="alert alert-warning">
                                        {$CANT_EDIT_GROUP}
                                    </div>
                                {/if}
                                <div class="form-group">
                                    <label for="inputSecondaryGroups">{$SECONDARY_GROUPS}</label>
                                    <div class="callout callout-info">
                                        <h5><i class="icon fa fa-info-circle"></i> {$INFO}</h5>
                                        {$SECONDARY_GROUPS_INFO}
                                    </div>
                                    <select class="form-control" name="secondary_groups[]" id="inputSecondaryGroups" multiple>
                                        {foreach from=$ALL_GROUPS item=item}
                                            {if $item->id neq $GROUP_ID}
                                                <option value="{$item->id}"{if in_array($item->id, $SECONDARY_GROUPS_VALUE)} selected{/if}>{$item->name|escape}</option>
                                            {/if}
                                        {/foreach}
                                    </select>
                                </div>
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <input type="hidden" name="action" value="update">
                                <input type="submit" value="{$SUBMIT}" class="btn btn-primary">
                            </form>

                        </div>
                    </div>

                    <!-- Spacing -->
                    <div style="height:1rem;"></div>

                </div>
        </section>
    </div>

    {if isset($DELETE_USER)}
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{$ARE_YOU_SURE}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {$CONFIRM_DELETE_USER}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{$NO}</button>
                        <form action="" method="post">
                            <input type="hidden" name="token" value="{$TOKEN}">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="{$USER_ID}">
                            <input type="submit" class="btn btn-primary" value="{$YES}">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    {/if}

    {include file='footer.tpl'}

</div>
<!-- ./wrapper -->

{include file='scripts.tpl'}

<script type="text/javascript">
    {if isset($DELETE_USER)}
    function showDeleteModal(){
        $('#deleteModal').modal().show();
    }
    {/if}
</script>

</body>
</html>