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
                        <h1 class="h3 mb-0 text-gray-800">{$GENERAL_SETTINGS}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                            <li class="breadcrumb-item active">{$GENERAL_SETTINGS}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='includes/update.tpl'}

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <!-- Success and Error Alerts -->
                            {include file='includes/alerts.tpl'}

                            <form action="" method="post">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="inputSiteName">{$SITE_NAME_LABEL}</label>
                                            <input type="text" class="form-control" id="inputSiteName" name="sitename"
                                                placeholder="{$SITE_NAME_LABEL}" value="{$SITE_NAME}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="inputContactEmail">{$CONTACT_EMAIL_ADDRESS}</label>
                                            <input type="email" class="form-control" id="inputContactEmail"
                                                name="contact_email" placeholder="{$CONTACT_EMAIL_ADDRESS}"
                                                value="{$CONTACT_EMAIL_ADDRESS_VALUE}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="inputDefaultLanguage">{$DEFAULT_LANGUAGE}</label> <span
                                                class="badge badge-info"><i class="fas fa-question-circle"
                                                    data-container="body" data-toggle="popover" data-placement="top"
                                                    title="{$INFO}" data-content="{$DEFAULT_LANGUAGE_HELP}"></i></span>
                                            <div class="input-group">
                                                <select name="language" class="form-control" id="inputDefaultLanguage">
                                                    {foreach from=$DEFAULT_LANGUAGE_VALUES item=item}
                                                    <option value="{$item->id}" {if $item->is_default eq 1}
                                                        selected{/if}>{$item->name}</option>
                                                    {/foreach}
                                                </select>
                                                <div class="input-group-append">
                                                    <a class="btn btn-default" data-toggle="tooltip"
                                                        title="{$INSTALL_LANGUAGE}" href="{$INSTALL_LANGUAGE_LINK}">
                                                        <i class="fas fa-plus-circle"></i>
                                                    </a>
                                                    <a class="btn btn-default" data-toggle="tooltip"
                                                        title="{$UPDATE_USER_LANGUAGES}" onclick="showLanguageModal()">
                                                        <i class="fas fa-upload"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="inputDefaultTimezone">{$DEFAULT_TIMEZONE}</label>
                                            <select name="timezone" class="form-control" id="inputDefaultTimezone">
                                                {foreach from=$DEFAULT_TIMEZONE_LIST key=key item=item}
                                                <option value="{$key}" {if $DEFAULT_TIMEZONE_VALUE eq $key}
                                                    selected{/if}>
                                                    ({$item.offset}) - {$item.name} ({$item.time})
                                                </option>
                                                {/foreach}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="inputHomepage">{$HOMEPAGE_TYPE}</label>
                                            <select name="homepage" class="form-control" id="inputHomepage">
                                                <option value="news" {if $HOMEPAGE_VALUE eq 'news' } selected{/if}>
                                                    {$HOMEPAGE_NEWS}</option>
                                                <option value="custom" {if $HOMEPAGE_VALUE eq 'custom' } selected{/if}>
                                                    {$HOMEPAGE_CUSTOM}</option>
                                                <option value="portal" {if $HOMEPAGE_VALUE eq 'portal' } selected{/if}>
                                                    {$HOMEPAGE_PORTAL}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="inputLoginMethod">{$LOGIN_METHOD}</label>
                                            <select name="login_method" class="form-control" id="inputLoginMethod">
                                                <option value="email" {if $LOGIN_METHOD_VALUE eq "email" }
                                                    selected{/if}>{$EMAIL}</option>
                                                <option value="username" {if $LOGIN_METHOD_VALUE eq "username" }
                                                    selected{/if}>{$USERNAME}</option>
                                                <option value="email_or_username" {if $LOGIN_METHOD_VALUE
                                                    eq "email_or_username" } selected{/if}>{$EMAIL_OR_USERNAME}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="inputFriendlyURL">{$USE_FRIENDLY_URLS}</label>
                                            <span class="badge badge-info"><i class="fas fa-question-circle"
                                                    data-container="body" data-toggle="popover" data-placement="top"
                                                    title="{$INFO}" data-content="{$USE_FRIENDLY_URLS_HELP}"></i></span>
                                            <select name="friendlyURL" class="form-control" id="inputFriendlyURL">
                                                <option value="true" {if $USE_FRIENDLY_URLS_VALUE} selected{/if}>
                                                    {$ENABLED}</option>
                                                <option value="false" {if !$USE_FRIENDLY_URLS_VALUE} selected{/if}>
                                                    {$DISABLED}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="inputPrivateProfile">{$PRIVATE_PROFILES}</label>
                                            <select name="privateProfile" class="form-control" id="inputPrivateProfile">
                                                <option value="1" {if $PRIVATE_PROFILES_VALUE} selected{/if}>{$ENABLED}
                                                </option>
                                                <option value="0" {if !$PRIVATE_PROFILES_VALUE} selected{/if}>
                                                    {$DISABLED}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="inputForceHTTPS">{$FORCE_HTTPS}</label>
                                            <span class="badge badge-info"><i class="fas fa-question-circle"
                                                    data-container="body" data-toggle="popover" data-placement="top"
                                                    title="{$INFO}" data-content="{$FORCE_HTTPS_HELP}"></i></span>
                                            <select name="forceHTTPS" class="form-control" id="inputForceHTTPS">
                                                <option value="true" {if $FORCE_HTTPS_VALUE} selected{/if}>{$ENABLED}
                                                </option>
                                                <option value="false" {if !$FORCE_HTTPS_VALUE} selected{/if}>{$DISABLED}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="inputForceWWW">{$FORCE_WWW}</label>
                                            <select name="forceWWW" class="form-control" id="inputForceWWW">
                                                <option value="true" {if $FORCE_WWW_VALUE} selected{/if}>{$ENABLED}
                                                </option>
                                                <option value="false" {if !$FORCE_WWW_VALUE} selected{/if}>{$DISABLED}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="inputEnableNicknames">{$ENABLE_NICKNAMES}</label>
                                            <select name="displaynames" class="form-control" id="inputEnableNicknames">
                                                <option value="true" {if $ENABLE_NICKNAMES_VALUE eq "1" }
                                                    selected{/if}>{$ENABLED}</option>
                                                <option value="false" {if $ENABLE_NICKNAMES_VALUE eq "0" }
                                                    selected{/if}>{$DISABLED}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="inputEmojiStyle">{$EMOJI_STYLE}</label>
                                            <span class="badge badge-info">
                                                <i class="fas fa-question-circle"
                                                   data-container="body" data-toggle="popover" data-placement="top"
                                                   title="{$INFO}" data-content='{$EMOJI_STYLE_HELP}'></i>
                                            </span>
                                            <select name="emoji_style" class="form-control" id="inputEmojiStyle">
                                                <option value="native" {if $EMOJI_STYLE_VALUE eq "native" }selected{/if}>
                                                    {$NATIVE}
                                                </option>
                                                <option value="twemoji" {if $EMOJI_STYLE_VALUE eq "twemoji" }selected{/if}>
                                                    {$TWEMOJI}
                                                </option>
                                                <option value="joypixels" {if $EMOJI_STYLE_VALUE eq "joypixels" }selected{/if}>
                                                    {$JOYPIXELS}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="inputAutoLanguage">{$ENABLE_AUTO_LANGUAGE}</label>
                                            <span class="badge badge-info"><i class="fas fa-question-circle"
                                                                              data-container="body" data-toggle="popover" data-placement="top"
                                                                              title="{$INFO}" data-content="{$AUTO_LANGUAGE_HELP}"></i></span>
                                            <select name="auto_language" class="form-control" id="inputAutoLanguage">
                                                <option value="true" {if $AUTO_LANGUAGE_VALUE} selected{/if}>
                                                    {$ENABLED}
                                                </option>
                                                <option value="false" {if !$AUTO_LANGUAGE_VALUE} selected{/if}>
                                                    {$DISABLED}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
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

                    <!-- End Page Content -->
                </div>

                <!-- End Main Content -->
            </div>

            {include file='footer.tpl'}

            <!-- End Content Wrapper -->
        </div>

        <!-- User language modal -->
        <div class="modal fade" id="languageModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{$ARE_YOU_SURE}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {$UPDATE_USER_LANGUAGES_INFO}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{$NO}</button>
                        <a href="{$UPDATE_USER_LANGUAGES_LINK}" class="btn btn-primary">{$YES}</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- End Wrapper -->
    </div>

    {include file='scripts.tpl'}

    <script type="text/javascript">
        function showLanguageModal() {
            $('#languageModal').modal().show();
        }
    </script>

</body>

</html>
