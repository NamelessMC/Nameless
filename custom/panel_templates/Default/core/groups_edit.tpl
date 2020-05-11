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
                        <h1 class="m-0 text-dark">{$GROUPS}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$GROUPS}</li>
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
                            <h5 style="display:inline">{$GROUP_NAME}</h5>
                            <div class="float-md-right">
                                {if isset($PERMISSIONS)}
                                    <a href="{$PERMISSIONS_LINK}" class="btn btn-primary">{$PERMISSIONS}</a>
                                {/if}
                                <button role="button" class="btn btn-warning" onclick="showCancelModal()">{$CANCEL}</button>
                                {if isset($DELETE_GROUP)}
                                    <button role="button" class="btn btn-danger" onclick="showDeleteModal()">{$DELETE}</button>
                                {/if}
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

                            {if isset($OWN_GROUP)}
                                <div class="callout callout-info">
                                    <h5><i class="icon fa fa-info-circle"></i> {$INFO}</h5>
                                    {$OWN_GROUP}
                                </div>
                            {/if}

                            <form role="form" action="" method="post">
                                <div class="form-group">
                                    <label for="InputGroupname">{$NAME}</label>
                                    <input type="text" name="groupname" class="form-control" id="InputGroupname"
                                           placeholder="{$NAME}"
                                           value="{$GROUP_NAME}">
                                </div>
                                <div class="form-group">
                                    <label for="InputHTML">{$GROUP_HTML}</label>
                                    <input type="text" name="html" class="form-control" id="InputHTML"
                                           placeholder="{$GROUP_HTML}"
                                           value="{$GROUP_HTML_VALUE}">
                                </div>
                                <div class="form-group">
                                    <label for="InputColour">{$GROUP_USERNAME_COLOUR}</label>
                                    <div class="input-group">
                                        <input type="text" name="username_style" class="form-control" id="InputColour"
                                               value="{$GROUP_USERNAME_COLOUR_VALUE}">
                                        <span class="input-group-append groupColour">
                                            <span class="input-group-text colorpicker-input-addon"><i></i></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="InputOrder">{$GROUP_ORDER}</label>
                                    <input type="number" min="1" class="form-control" id="InputOrder" name="order" value="{$GROUP_ORDER_VALUE}">
                                </div>
                                <div class="form-group">
                                    <label for="InputStaff">{$STAFF_GROUP}</label>
                                    <input type="hidden" name="staff" value="0">
                                    <input type="checkbox" name="staff" class="js-switch" id="InputStaff"
                                           value="1"{if $STAFF_GROUP_VALUE eq 1} checked{/if} >
                                </div>
                                {if !isset($OWN_GROUP)}
                                <div class="form-group">
                                    <label for="InputStaffCP">{$STAFF_CP}</label>
                                    <input type="hidden" name="staffcp" value="0">
                                    <input type="checkbox" name="staffcp" class="js-switch" id="InputStaffCP"
                                           value="1"{if $STAFF_CP_VALUE eq 1} checked{/if} >
                                </div>
                                {/if}
                                <div class="form-group">
                                    <label for="InputDefault">{$DEFAULT_GROUP}</label>
                                    <input type="hidden" name="default" value="0">
                                    <input type="checkbox" name="default" class="js-switch" id="InputDefault"
                                           value="1"{if $DEFAULT_GROUP_VALUE eq 1} checked{/if} >
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="token" value="{$TOKEN}">
                                    <input type="hidden" name="action" value="update">
                                    <input type="submit" value="{$SUBMIT}"
                                           class="btn btn-primary">
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

    {if isset($DELETE_GROUP)}
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
                    <form action="" method="post">
                        <input type="hidden" name="token" value="{$TOKEN}">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="{$GROUP_ID}">
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
    function showCancelModal(){
        $('#cancelModal').modal().show();
    }
    {if isset($DELETE_GROUP)}
    function showDeleteModal(){
        $('#deleteModal').modal().show();
    }
    {/if}
    $(function(){
        $('.groupColour').colorpicker({
            format: 'hex',
            'color': {if $GROUP_USERNAME_COLOUR_VALUE}'{$GROUP_USERNAME_COLOUR_VALUE}'{else}false{/if}
        });

        $('.groupColour').on('colorpickerChange', function(event) {
            $('#InputColour').val(event.color.toString());
        });

        $('#InputColour').change(function() {
            $('.groupColour').colorpicker('setValue', $(this).val());
        });
    });
</script>

</body>
</html>