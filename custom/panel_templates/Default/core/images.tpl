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
                        <h1 class="m-0 text-dark">{$IMAGES}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$LAYOUT}</li>
                            <li class="breadcrumb-item active">{$IMAGES}</li>
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

                            {if isset($BACKGROUNDS_NOT_WRITABLE) || isset($TEMPLATE_BANNERS_DIRECTORY_NOT_WRITABLE)}
                                <div class="alert alert-danger alert-dismissible">
                                    <h5><i class="icon fas fa-exclamation-triangle"></i> {$ERRORS_TITLE}</h5>
                                    <ul>
                                        {if isset($BACKGROUNDS_NOT_WRITABLE)}
                                            <li>{$BACKGROUNDS_NOT_WRITABLE}</li>
                                        {/if}

                                        {if isset($TEMPLATE_BANNERS_DIRECTORY_NOT_WRITABLE)}
                                            <li>{$TEMPLATE_BANNERS_DIRECTORY_NOT_WRITABLE}</li>
                                        {/if}
                                    </ul>
                                </div>
                            {/if}

                            <p>{$BACKGROUND_IMAGE}</p>

                            <form action="" method="post" style="display:inline;" >
                                <select name="bg" class="image-picker show-html">
                                    {foreach from=$BACKGROUND_IMAGES_ARRAY item=image}
                                        <option data-img-src="{$image.src}" value="{$image.value}"{if $image.selected} selected{/if}>{$image.n}</option>
                                    {/foreach}
                                </select>
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
                            </form>

                            <a href="{$RESET_LINK}" class="btn btn-danger">{$RESET}</a>
                            <button class="btn btn-info" data-toggle="modal" data-target="#uploadModal">{$UPLOAD_NEW_IMAGE}</button>

                            <hr />

                            <p>{$BANNER_IMAGE}</p>

                            <form action="" method="post" style="display:inline;" >
                                <select name="banner" class="image-picker show-html">
                                    {foreach from=$BANNER_IMAGES_ARRAY item=image}
                                        <option data-img-src="{$image.src}" value="{$image.value}"{if $image.selected} selected{/if}>{$image.n}</option>
                                    {/foreach}
                                </select>
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
                            </form>

                            <a href="{$RESET_BANNER_LINK}" class="btn btn-danger">{$RESET_BANNER}</a>
                            <button class="btn btn-info" data-toggle="modal" data-target="#uploadBannerModal">{$UPLOAD_NEW_IMAGE}</button>

                        </div>
                    </div>

                    <!-- Spacing -->
                    <div style="height:1rem;"></div>

                </div>
        </section>
    </div>

    <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="uploadModalLabel">{$UPLOAD_NEW_IMAGE}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="location.reload();">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Upload modal -->
                    <form action="{$UPLOAD_PATH}" class="dropzone" id="uploadBackgroundDropzone">
                        <input type="hidden" name="token" value="{$TOKEN}">
                        <input type="hidden" name="type" value="background">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="location.reload();" data-dismiss="modal">{$CLOSE}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="uploadBannerModal" tabindex="-1" role="dialog" aria-labelledby="uploadBannerModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="uploadBannerModalLabel">{$UPLOAD_NEW_IMAGE}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="location.reload();">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Upload modal -->
                    <form action="{$UPLOAD_PATH}" class="dropzone" id="uploadBannerDropzone">
                        <input type="hidden" name="token" value="{$TOKEN}">
                        <input type="hidden" name="type" value="template_banner">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="location.reload();" data-dismiss="modal">{$CLOSE}</button>
                </div>
            </div>
        </div>
    </div>

    {include file='footer.tpl'}

</div>
<!-- ./wrapper -->

{include file='scripts.tpl'}

</body>
</html>