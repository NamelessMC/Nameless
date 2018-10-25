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
                        <h1 class="m-0 text-dark">{$PROFILE_FIELDS}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                            <li class="breadcrumb-item active">{$PROFILE_FIELDS}</li>
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

                            <h5 style="display:inline">{$EDITING_PROFILE_FIELD}</h5>

                            <div class="float-md-right">
                                <a class="btn btn-warning" onclick="showCancelModal()">{$CANCEL}</a>
                                <a class="btn btn-danger" style="color:#fff!important" onclick="showDeleteModal()">{$DELETE}</a>
                            </div>

                            <hr />

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

                            <form action="" method="post">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="inputFieldName">{$FIELD_NAME}</label>
                                            <input type="text" class="form-control" id="inputFieldName" name="name" placeholder="{$FIELD_NAME}" value="{$FIELD_NAME_VALUE}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="inputFieldType">{$TYPE}</label>
                                            <select class="form-control" name="type" id="inputFieldType">
                                                {foreach from=$TYPES key=key item=item}
                                                    <option value="{$key}"{if $key eq $TYPE_VALUE} selected{/if}>{$item}</option>
                                                {/foreach}
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputDescription">{$DESCRIPTION}</label>
                                    <textarea class="form-control" id="inputDescription" name="description">{$DESCRIPTION_VALUE}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="inputRequired">{$REQUIRED}</label>
                                    <span class="badge badge-info" style="margin-right:10px"><i class="fas fa-question-circle" data-container="body" data-toggle="popover" title="{$INFO}" data-content="{$REQUIRED_HELP}"></i></span>
                                    <input type="checkbox" id="inputRequired" name="required"
                                           class="js-switch"{if $REQUIRED_VALUE eq 1} checked{/if} />
                                </div>

                                <div class="form-group">
                                    <label for="inputEditable">{$EDITABLE}</label>
                                    <span class="badge badge-info" style="margin-right:10px"><i class="fas fa-question-circle" data-container="body" data-toggle="popover" title="{$INFO}" data-content="{$EDITABLE_HELP}"></i></span>
                                    <input type="checkbox" id="inputEditable" name="editable"
                                           class="js-switch"{if $EDITABLE_VALUE eq 1} checked{/if} />
                                </div>

                                <div class="form-group">
                                    <label for="inputPublic">{$PUBLIC}</label>
                                    <span class="badge badge-info" style="margin-right:10px"><i class="fas fa-question-circle" data-container="body" data-toggle="popover" title="{$INFO}" data-content="{$PUBLIC_HELP}"></i></span>
                                    <input type="checkbox" id="inputPublic" name="public" class="js-switch"{if $PUBLIC_VALUE eq 1} checked{/if} />
                                </div>

                                <div class="form-group">
                                    <label for="inputForum">{$DISPLAY_FIELD_ON_FORUM}</label>
                                    <span class="badge badge-info"><i class="fas fa-question-circle" data-container="body" data-toggle="popover" title="{$INFO}" data-content="{$DISPLAY_FIELD_ON_FORUM_HELP}"></i></span>
                                    <input type="checkbox" id="inputForum" name="forum" class="js-switch"{if $DISPLAY_FIELD_ON_FORUM_VALUE eq 1} checked{/if} />
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

    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{$ARE_YOU_SURE}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {$CONFIRM_CANCEL}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{$NO}</button>
                    <a href="{$CANCEL_LINK}" class="btn btn-primary">{$YES}</a>
                </div>
            </div>
        </div>
    </div>

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
                    {$CONFIRM_DELETE}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{$NO}</button>
                    <a href="{$DELETE_LINK}" class="btn btn-primary">{$YES}</a>
                </div>
            </div>
        </div>
    </div>

    {include file='footer.tpl'}

</div>
<!-- ./wrapper -->

{include file='scripts.tpl'}

<script type="text/javascript">
    function showCancelModal(){
        $('#cancelModal').modal().show();
    }
    function showDeleteModal(){
        $('#deleteModal').modal().show();
    }
</script>

</body>
</html>