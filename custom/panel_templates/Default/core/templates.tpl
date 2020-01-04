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

                            <a class="btn btn-primary" href="{$INSTALL_TEMPLATE_LINK}">{$INSTALL_TEMPLATE}</a>

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

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <colgroup>
                                        <col width="60%">
                                        <col width="40%">
                                    </colgroup>
                                    {foreach from=$TEMPLATE_LIST item=template}
                                        <tr>
                                            <td>
                                                <strong>{$template.name}</strong> <small>{$template.version}</small>
                                                {if $template.version_mismatch}
                                                    &nbsp;<button role="button" class="btn btn-sm btn-warning" data-toggle="popover" data-title="{$WARNING}" data-content="{$template.version_mismatch}"><i class="fa fa-exclamation-triangle"></i></button>
                                                {/if}
                                                <br />
                                                <small>{$template.author_x}</small>
                                            </td>
                                            <td>
                                                <div class="float-md-right">
                                                    {if $template.enabled}
                                                        {if $template.deactivate_link}
                                                            <a class="btn btn-danger btn-sm" href="{$template.deactivate_link}">{$DEACTIVATE}</a>
                                                        {else}
                                                            <button role="button" class="btn btn-success btn-sm" disabled>{$ACTIVE}</button>
                                                        {/if}

                                                        {if $template.default}
                                                            <button role="button" class="btn btn-success btn-sm" disabled>{$DEFAULT}</button>
                                                        {else}
                                                            <a class="btn btn-primary btn-sm" href="{$template.default_link}">{$MAKE_DEFAULT}</a>
                                                        {/if}
                                                    {else}
                                                        <a class="btn btn-primary btn-sm" href="{$template.activate_link}">{$ACTIVATE}</a>
                                                        <button role="button" onclick="showDeleteModal('{$template.delete_link}')" class="btn btn-danger btn-sm">{$DELETE}</button>
                                                    {/if}

                                                    {if $template.edit_link}
                                                        <a class="btn btn-primary btn-sm" href="{$template.edit_link}">{$EDIT}</a>
                                                    {/if}

                                                    {if $template.settings_link}
                                                        <a class="btn btn-info btn-sm" href="{$template.settings_link}">{$SETTINGS}</a>
                                                    {/if}
                                                </div>
                                            </td>
                                        </tr>
                                    {/foreach}
                                </table>
                            </div>

                            <hr />

                            <h5>{$FIND_TEMPLATES}</h5>

                            {if isset($WEBSITE_TEMPLATES_ERROR)}
                                <div class="alert alert-warning">{$WEBSITE_TEMPLATES_ERROR}</div>
                            {/if}

                            {if count($WEBSITE_TEMPLATES)}
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <colgroup>
                                            <col width="70%">
                                            <col width="20%">
                                            <col width="10%">
                                        </colgroup>
                                        <thead>
                                            <tr>
                                                <th>{$TEMPLATE}</th>
                                                <th>{$STATS}</th>
                                                <th>{$ACTIONS}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {foreach from=$WEBSITE_TEMPLATES item=item}
                                                <tr>
                                                    <td>
                                                        <strong>{$item.name}</strong> <small>{$item.latest_version}</small>
                                                        <br />
                                                        <small>{$item.author_x}</small>
                                                    </td>
                                                    <td>
                                                        <div class="star-rating view">
                                                            <span class="far fa-star" data-rating="1" style="color:gold;"></span>
                                                            <span class="far fa-star" data-rating="2" style="color:gold"></span>
                                                            <span class="far fa-star" data-rating="3" style="color:gold;"></span>
                                                            <span class="far fa-star" data-rating="4" style="color:gold;"></span>
                                                            <span class="far fa-star" data-rating="5" style="color:gold;"></span>
                                                            <input type="hidden" name="rating" class="rating-value" value="{($item.rating/10)|round}">
                                                        </div>
                                                        {$item.downloads_full}<br />
                                                        {$item.views_full}
                                                    </td>
                                                    <td><a href="{$item.url}" target="_blank" class="btn btn-primary btn-sm">{$VIEW} &raquo;</a></td>
                                                </tr>
                                            {/foreach}
                                        </tbody>
                                    </table>
                                </div>

                            {else}
                                <div class="alert alert-warning">{$UNABLE_TO_RETRIEVE_TEMPLATES}</div>
                            {/if}

                            <a href="{$VIEW_ALL_TEMPLATES_LINK}" class="btn btn-primary" target="_blank">{$VIEW_ALL_TEMPLATES} &raquo;</a>

                        </div>
                    </div>

                    <!-- Spacing -->
                    <div style="height:1rem;"></div>

                </div>
        </section>
    </div>

    <!-- Delete error modal -->
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
                    {$CONFIRM_DELETE_TEMPLATE}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{$NO}</button>
                    <a href="#" id="deleteLink" class="btn btn-primary">{$YES}</a>
                </div>
            </div>
        </div>
    </div>

    {include file='footer.tpl'}

</div>
<!-- ./wrapper -->

{include file='scripts.tpl'}

<script type="text/javascript">
    function showDeleteModal(id){
        $('#deleteLink').attr('href', id);
        $('#deleteModal').modal().show();
    }

    var $star_rating = $('.star-rating.view .fa-star');

    var SetRatingStar = function(type = 0) {
        if(type === 0) {
            return $star_rating.each(function () {
                if (parseInt($(this).parent().children('input.rating-value').val()) >= parseInt($(this).data('rating'))) {
                    return $(this).removeClass('far').addClass('fas');
                } else {
                    return $(this).removeClass('fas').addClass('far');
                }
            });
        }
    };

    SetRatingStar();
</script>

</body>
</html>