{include file='header.tpl'}

<body id="page-top">

    <!-- Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        {include file='sidebar.tpl'}

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main content -->
            <div id="content">

                <!-- Topbar -->
                {include file='navbar.tpl'}

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">{$AVATARS}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                            <li class="breadcrumb-item active">{$AVATARS}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='includes/update.tpl'}

                    <div class="card shadow mb-4">
                        <div class="card-body">

                            <!-- Success and Error Alerts -->
                            {include file='includes/alerts.tpl'}

                            <form action="" method="post">
                                <div class="form-group custom-control custom-switch">
                                    <input type="hidden" name="custom_avatars" value="0">
                                    <input id="inputCustomAvatars" name="custom_avatars" type="checkbox"
                                        class="custom-control-input" value="1" {if $CUSTOM_AVATARS_VALUE eq 1}
                                        checked{/if} />
                                    <label class="custom-control-label"
                                        for="inputCustomAvatars">{$CUSTOM_AVATARS}</label>
                                </div>
                                <div class="form-group">
                                    <label for="inputDefaultAvatar">{$DEFAULT_AVATAR}</label>
                                    <select class="form-control" name="default_avatar" id="inputDefaultAvatar">
                                        <option value="minecraft" {if $DEFAULT_AVATAR_VALUE eq "minecraft" }
                                            selected{/if}>{$MINECRAFT_AVATAR}</option>
                                        {if $CUSTOM_AVATARS_VALUE eq 1}
                                        <option value="custom" {if $DEFAULT_AVATAR_VALUE eq "custom" } selected{/if}>
                                            {$CUSTOM_AVATAR}</option>
                                        {/if}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="inputMinecraftAvatarSource">{$MINECRAFT_AVATAR_SOURCE}</label>
                                    <select class="form-control" name="avatar_source" id="inputMinecraftAvatarSource">
                                        {foreach from=$MINECRAFT_AVATAR_VALUES key=name item=url}
                                        <option value="{$name}" {if $name eq $MINECRAFT_AVATAR_VALUE} selected{/if}>
                                            {$url}</option>
                                        {/foreach}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="inputAvatarPerspective">{$MINECRAFT_AVATAR_PERSPECTIVE}</label>
                                    <select class="form-control" name="avatar_perspective" id="inputAvatarPerspective">
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="token" value="{$TOKEN}">
                                    <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
                                </div>
                            </form>

                            <hr />

                            <strong>{$DEFAULT_AVATAR}</strong>

                            <br /><br />

                            <button class="btn btn-primary" data-toggle="modal"
                                data-target="#uploadModal">{$UPLOAD_NEW_IMAGE}</button>

                            <br /><br />

                            {if count($IMAGES)}
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="selectDefaultAvatar">{$SELECT_DEFAULT_AVATAR}</label>
                                    <select class="image-picker show-html" id="selectDefaultAvatar" name="avatar">
                                        {foreach from=$IMAGES key=key item=item}
                                        <option data-img-src="{$key}" value="{$item}" {if $DEFAULT_AVATAR_IMAGE eq
                                            $item} selected{/if}>{$item}</option>
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

                    <!-- End Page Content -->
                </div>

                <!-- End Main Content -->
            </div>

            {include file='footer.tpl'}

            <!-- End Content Wrapper -->
        </div>

        <!-- Modal -->
        <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadModalLabel">{$UPLOAD_NEW_IMAGE}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="{$CLOSE}">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Upload modal -->
                        <form action="{$UPLOAD_FORM_ACTION}" class="dropzone" id="upload_avatar_dropzone">
                            <div class="dz-message" data-dz-message>
                                <span>{$DRAG_FILES_HERE}</span>
                            </div>
                            <input type="hidden" name="token" value="{$TOKEN}">
                            <input type="hidden" name="type" value="default_avatar">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="location.reload();"
                            data-dismiss="modal">{$CLOSE}</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- End Wrapper -->
    </div>

    <script>

        const perspective_selector = document.getElementById('inputAvatarPerspective');
        const source_selector = document.getElementById('inputMinecraftAvatarSource');
        source_selector.addEventListener('change', () => reloadPerspectives(source_selector.value));

        document.onLoad = reloadPerspectives(source_selector.value, true);

        function reloadPerspectives(source, firstLoad = false) {
            removeOptions(perspective_selector);
            {foreach $MINECRAFT_AVATAR_PERSPECTIVE_VALUES key=source item=perspectives}
                if ('{$source}' == source) {
                    {foreach $perspectives item=$perspective}
                        if (firstLoad) {
                            {if $perspective|strtolower eq $MINECRAFT_AVATAR_PERSPECTIVE_VALUE|strtolower}
                                option = new Option('{$perspective|ucfirst}', '{$perspective|ucfirst}', true, true);
                                perspective_selector.add(option, undefined);
                            {else}
                                option = new Option('{$perspective|ucfirst}', '{$perspective|ucfirst}');
                                perspective_selector.add(option, undefined);
                            {/if}
                        } else {
                            option = new Option('{$perspective|ucfirst}', '{$perspective|ucfirst}');
                            perspective_selector.add(option, undefined);
                        }
                    {/foreach}
                }
            {/foreach}
        }

        function removeOptions(selectElement) {
            var i, L = selectElement.options.length - 1;
            for (i = L; i >= 0; i--) {
                selectElement.remove(i);
            }
        }

    </script>

    {include file='scripts.tpl'}

</body>

</html>