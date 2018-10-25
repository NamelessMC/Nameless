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
                        <h1 class="m-0 text-dark">{$AVATARS}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                            <li class="breadcrumb-item active">{$AVATARS}</li>
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

                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="inputCustomAvatars">{$CUSTOM_AVATARS}</label>
                                    <input type="hidden" name="custom_avatars" value="0">
                                    <input id="inputCustomAvatars" name="custom_avatars" type="checkbox"
                                           class="js-switch"
                                           value="1"{if $CUSTOM_AVATARS_VALUE eq 1} checked{/if} />
                                </div>
                                <div class="form-group">
                                    <label for="inputDefaultAvatar">{$DEFAULT_AVATAR}</label>
                                    <select class="form-control" name="default_avatar" id="inputDefaultAvatar">
                                        <option value="minecraft"{if $DEFAULT_AVATAR_VALUE eq "minecraft"} selected{/if}>{$MINECRAFT_AVATAR}</option>
                                        <option value="custom"{if $DEFAULT_AVATAR_VALUE eq "custom"} selected{/if}>{$CUSTOM_AVATAR}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="inputMinecraftAvatarSource">{$MINECRAFT_AVATAR_SOURCE}</label>
                                    <select class="form-control" name="avatar_source"
                                            id="inputMinecraftAvatarSource">
                                        {foreach from=$MINECRAFT_AVATAR_VALUES key=key item=item}
                                            <option value="{$key}"{if $key eq $MINECRAFT_AVATAR_VALUE} selected{/if}>{$item}</option>
                                        {/foreach}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="inputAvatarPerspective">{$MINECRAFT_AVATAR_PERSPECTIVE}</label>
                                    <select class="form-control" name="avatar_perspective"
                                            id="inputAvatarPerspective">
                                        <option value="face"{if $MINECRAFT_AVATAR_PERSPECTIVE_VALUE eq "avatar" OR $MINECRAFT_AVATAR_PERSPECTIVE_VALUE eq "helmavatar" OR $MINECRAFT_AVATAR_PERSPECTIVE_VALUE eq "face"} selected{/if}>{$FACE}</option>
                                        <option value="bust"{if $MINECRAFT_AVATAR_PERSPECTIVE_VALUE eq "bust"} selected{/if}>{$BUST}</option>
                                        <option value="head"{if $MINECRAFT_AVATAR_PERSPECTIVE_VALUE eq "head"} selected{/if}>{$HEAD}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="token" value="{$TOKEN}">
                                    <input type="submit" class="btn btn-primary"
                                           value="{$SUBMIT}">
                                </div>
                            </form>

                            <hr />

                            <strong>{$DEFAULT_AVATAR}</strong>

                            <br /><br />

                            <button class="btn btn-primary" data-toggle="modal" data-target="#uploadModal">{$UPLOAD_NEW_IMAGE}</button>

                            <br /><br />

                            {if count($IMAGES)}
                                <form action="" method="post">
                                    <div class="form-group">
                                        <label for="selectDefaultAvatar">{$SELECT_DEFAULT_AVATAR}</label>
                                        <select class="image-picker show-html" id="selectDefaultAvatar" name="avatar">
                                            {foreach from=$IMAGES key=key item=item}
                                                <option data-img-src="{$key}" value="{$item}" {if $DEFAULT_AVATAR_IMAGE eq $item} selected{/if}>{$item}</option>
                                            {/foreach}
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="token" value="{$TOKEN}">
                                        <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
                                    </div>
                                </form>
                            {else}
                                {$NO_AVATARS}
                            {/if}

                        </div>
                    </div>

                    <!-- Spacing -->
                    <div style="height:1rem;"></div>

                </div>
        </section>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog"
         aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="uploadModalLabel">{$UPLOAD_NEW_IMAGE}</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="{$CLOSE}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Upload modal -->
                    <form action="{$UPLOAD_FORM_ACTION}"
                          class="dropzone" id="upload_avatar_dropzone">
                        <div class="dz-message" data-dz-message>
                            <span>{$DRAG_FILES_HERE}</span>
                        </div>
                        <input type="hidden" name="token"
                               value="{$TOKEN}">
                        <input type="hidden" name="type" value="default_avatar">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary"
                            onclick="location.reload();"
                            data-dismiss="modal">{$CLOSE}</button>
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