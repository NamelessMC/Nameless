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
                        <h1 class="m-0 text-dark">{$MODULES}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$MODULES}</li>
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
                            <a class="btn btn-primary" href="{$INSTALL_MODULE_LINK}">{$INSTALL_MODULE}</a>

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
                                        <col width="75%">
                                        <col width="25%">
                                    </colgroup>
                                    {foreach from=$MODULE_LIST item=module}
                                        <tr>
                                            <td>
                                                <strong>{$module.name}</strong> <small>{$module.version}</small>
                                                {if $module.version_mismatch}
                                                    &nbsp;<button role="button" class="btn btn-sm btn-warning" data-toggle="popover" data-title="{$WARNING}" data-content="{$module.version_mismatch}"><i class="fa fa-exclamation-triangle"></i></button>
                                                {/if}
                                                <br />
                                                <small>{$module.author_x}</small>
                                            </td>
                                            <td>
                                                <div class="float-md-right">
                                                    {if $module.enabled}
                                                        {if $module.disable_link}
                                                            <a class="btn btn-danger btn-sm" href="{$module.disable_link}">{$DISABLE}</a>
                                                        {else}
                                                            <a class="btn btn-warning btn-sm disabled"><i class="fa fa-lock"></i></a>
                                                        {/if}
                                                    {else}
                                                        <a class="btn btn-primary btn-sm" href="{$module.enable_link}">{$ENABLE}</a>
                                                    {/if}
                                                </div>
                                            </td>
                                        </tr>
                                    {/foreach}
                                </table>
                            </div>

                            <hr />

                            <h5>{$FIND_MODULES}</h5>

                            {if isset($WEBSITE_MODULES_ERROR)}
                                <div class="alert alert-warning">{$WEBSITE_MODULES_ERROR}</div>
                            {/if}

                            {if count($WEBSITE_MODULES)}
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <colgroup>
                                            <col width="70%">
                                            <col width="20%">
                                            <col width="10%">
                                        </colgroup>
                                        <thead>
                                        <tr>
                                            <th>{$MODULE}</th>
                                            <th>{$STATS}</th>
                                            <th>{$ACTIONS}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        {foreach from=$WEBSITE_MODULES item=item}
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
                                <div class="alert alert-warning">{$UNABLE_TO_RETRIEVE_MODULES}</div>
                            {/if}

                            <a href="{$VIEW_ALL_MODULES_LINK}" class="btn btn-primary" target="_blank">{$VIEW_ALL_MODULES} &raquo;</a>

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