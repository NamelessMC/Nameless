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
                        <h1 class="h3 mb-0 text-gray-800">{$USERS}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$USER_MANAGEMENT}</li>
                            <li class="breadcrumb-item active">{$USERS}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='includes/update.tpl'}

                    <div class="card shadow mb-4">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-9">
                                    <h5 style="margin-top: 7px; margin-bottom: 7px;">{$EDITING_USER}</h5>
                                </div>
                                <div class="col-md-3">
                                    <span class="float-md-right">
                                        {if isset($DELETE_USER) || isset($RESEND_ACTIVATION_EMAIL) ||
                                        isset($VALIDATE_USER)}
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary dropdown-toggle"
                                                data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">{$ACTIONS}</button>
                                            <div class="dropdown-menu">
                                                {if isset($DELETE_USER)}<a class="dropdown-item" href="#"
                                                    onclick="showDeleteModal()">{$DELETE_USER}</a>{/if}
                                                {if isset($RESEND_ACTIVATION_EMAIL)}<a class="dropdown-item"
                                                    href="{$RESEND_ACTIVATION_EMAIL_LINK}">{$RESEND_ACTIVATION_EMAIL}</a>{/if}
                                                {if isset($VALIDATE_USER)}<a class="dropdown-item" href="#"
                                                    onclick="validateUser()">{$VALIDATE_USER}</a>{/if}
                                                {if isset($CHANGE_PASSWORD)}<a class="dropdown-item" href="#"
                                                    onclick="changePassword()">{$CHANGE_PASSWORD}</a> {/if}
                                            </div>
                                        </div>
                                        {/if}
                                        <a href="{$BACK_LINK}" class="btn btn-warning">{$BACK}</a>
                                    </span>
                                </div>
                            </div>
                            <hr />

                            <!-- Success and Error Alerts -->
                            {include file='includes/alerts.tpl'}

                            {if isset($WARNINGS) && count($WARNINGS)}
                            <div class="alert bg-warning text-white alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h5><i class="icon fas fa-exclamation-triangle"></i> {$WARNINGS_TITLE}</h5>
                                <ul>
                                    {foreach from=$WARNINGS item=warning}
                                    <li>{$warning}</li>
                                    {/foreach}
                                </ul>
                            </div>
                            {/if}

                            <form role="form" action="" method="post">
                                <div class="form-group">
                                    <label for="InputMCUsername">{$USERNAME}</label>
                                    <input type="text" name="username" class="form-control" id="InputMCUsername"
                                        placeholder="{$USERNAME}" value="{$USERNAME_VALUE}">
                                </div>
                                {if $DISPLAYNAMES eq true}
                                    <div class="form-group">
                                        <label for="InputUsername">{$NICKNAME}</label>
                                        <input type="text" name="nickname" class="form-control" id="InputUsername"
                                            placeholder="{$NICKNAME}" value="{$NICKNAME_VALUE}">
                                    </div>
                                {else}
                                    <input type="hidden" name="nickname" value="{$NICKNAME_VALUE}">
                                {/if}
                                <div class="form-group">
                                    <label for="InputEmail">{$EMAIL_ADDRESS}</label>
                                    <input type="email" name="email" class="form-control" id="InputEmail"
                                        placeholder="{$EMAIL_ADDRESS}" value="{$EMAIL_ADDRESS_VALUE}">
                                </div>
                                <div class="form-group">
                                    <label for="InputTitle">{$USER_TITLE}</label>
                                    <input type="text" name="title" class="form-control" id="InputTitle"
                                        placeholder="{$USER_TITLE}" value="{$USER_TITLE_VALUE}">
                                </div>
                                {if $PRIVATE_PROFILE_ENABLED eq true}
                                    <div class="form-group">
                                        <label for="inputPrivateProfile">{$PRIVATE_PROFILE}</label>
                                        <select name="privateProfile" class="form-control" id="inputPrivateProfile">
                                            <option value="1" {if $PRIVATE_PROFILE_VALUE eq 1} selected{/if}>{$ENABLED}
                                            </option>
                                            <option value="0" {if $PRIVATE_PROFILE_VALUE eq 0} selected{/if}>{$DISABLED}
                                            </option>
                                        </select>
                                    </div>
                                {else}
                                    <input type="hidden" name="privateProfile" value="0">
                                {/if}
                                <div class="form-group">
                                    <label for="inputLanguage">{$LANGUAGE}</label>
                                    <select name="language" class="form-control" id="inputLanguage">
                                        {foreach from=$LANGUAGES item=language}
                                            <option value="{$language.id}" {if $language.active} selected{/if}>
                                                {$language.name}
                                            </option>
                                        {/foreach}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="inputTimezone">{$TIMEZONE}</label>
                                    <select name="timezone" class="form-control" id="inputTimezone">
                                        {foreach from=$TIMEZONES key=KEY item=ITEM}
                                            <option value="{$KEY}" {if $TIMEZONE_VALUE eq $KEY} selected{/if}>
                                                ({$ITEM.offset}) {$ITEM.name} &middot; ({$ITEM.time})
                                            </option>
                                        {/foreach}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="inputTemplate">{$ACTIVE_TEMPLATE}</label>
                                    <select name="template" class="form-control" id="inputTemplate">
                                        {foreach from=$TEMPLATES item=template}
                                        <option value="{$template.id}" {if $template.active eq true} selected{/if}>
                                            {$template.name}</option>
                                        {/foreach}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="InputSignature">{$SIGNATURE}</label>
                                    <textarea style="width:100%" rows="10" name="signature"
                                        id="InputSignature">{$SIGNATURE_VALUE}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="inputGroups">{$GROUPS}</label>
                                    <div
                                        class="card shadow {if !isset($CANT_EDIT_GROUP)}border-left-info{else}border-left-warning{/if}">
                                        <div class="card-body">
                                            <strong>{$MAIN_GROUP_INFO}: </strong>{$MAIN_GROUP->name} {if
                                            isset($CANT_EDIT_GROUP)}<i>({$CANT_EDIT_GROUP})</i>{/if}
                                        </div>
                                    </div>
                                    <br />
                                    <select class="form-control" name="groups[]" id="inputGroups" multiple>
                                        {foreach from=$ALL_GROUPS item=item}
                                        <option value="{$item->id}" {if in_array($item->id, $GROUPS_VALUE)}
                                            selected{/if}>{$item->name|escape}</option>
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

                    <!-- End Page Content -->
                </div>

                <!-- End Main Content -->
            </div>

            {include file='footer.tpl'}

            <!-- End Content Wrapper -->
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
        {if isset($CHANGE_PASSWORD)}
            <div class="modal fade" id="passwordModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{$CHANGE_PASSWORD}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="" method="post">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="inputPassword">{$NEW_PASSWORD}</label>
                                    <input type="password" name="password" id="inputPassword" placeholder="{$PASSWORD}"
                                           autocomplete="off" tabindex="2" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="inputPasswordAgain">{$CONFIRM_NEW_PASSWORD}</label>
                                    <input type="password" name="password_again" id="inputPasswordAgain"
                                           placeholder="{$CONFIRM_PASSWORD}" autocomplete="off" tabindex="3" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <input type="hidden" name="action" value="change_password">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{$BACK}</button>
                                <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        {/if}

        <!-- End Wrapper -->
    </div>

    <form style="display:none" action="{$VALIDATE_USER_LINK}" method="post" id="validateUserForm">
        <input type="hidden" name="token" value="{$TOKEN}" />
    </form>

    {include file='scripts.tpl'}

    <script type="text/javascript">
        {if isset($DELETE_USER)}
            function showDeleteModal() {
                $('#deleteModal').modal().show();
            }
        {/if}

        {if isset($VALIDATE_USER)}
            function validateUser() {
                $('#validateUserForm').submit();
            }
        {/if}

        {if isset($CHANGE_PASSWORD)}
            function changePassword() {
                $('#passwordModal').modal().show();
            }
        {/if}

        $(document).ready(() => {
            $('#inputGroups').select2({ placeholder: "{$NO_ITEM_SELECTED}" });
        })
    </script>

</body>

</html>
