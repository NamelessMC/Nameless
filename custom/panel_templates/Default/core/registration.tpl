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
                    <h1 class="h3 mb-0 text-gray-800">{$REGISTRATION}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                        <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                        <li class="breadcrumb-item active">{$REGISTRATION}</li>
                    </ol>
                </div>

                <!-- Update Notification -->
                {include file='includes/update.tpl'}

                <div class="card shadow mb-4">
                    <div class="card-body">

                        <!-- Success and Error Alerts -->
                        {include file='includes/alerts.tpl'}

                        <form id="enableRegistration" action="" method="post">
                            <div class="form-group">
                                {$ENABLE_REGISTRATION}
                                <input type="hidden" name="enable_registration" value="0">
                                <input name="enable_registration" type="checkbox"
                                       class="js-switch js-check-change" {if $REGISTRATION_ENABLED eq 1} checked{/if}
                                       value="1" />
                            </div>
                            <input type="hidden" name="token" value="{$TOKEN}">
                        </form>

                        <form action="" method="post">
                            <div class="form-group">
                                <label for="verification">{$EMAIL_VERIFICATION}</label>
                                <input name="verification" id="verification" type="checkbox"
                                       class="js-switch" {if $EMAIL_VERIFICATION_VALUE eq 1} checked{/if} />
                            </div>
                            <div class="form-group">
                                <label for="InputEnableRecaptcha">{$CAPTCHA_GENERAL}</label>
                                <input id="InputEnableRecaptcha" name="enable_recaptcha" type="checkbox"
                                       class="js-switch"
                                       value="1" {if $CAPTCHA_GENERAL_VALUE eq 'true' } checked{/if} />
                            </div>
                            <div class="form-group">
                                <label for="InputEnableRecaptchaLogin">{$CAPTCHA_LOGIN}</label>
                                <input id="InputEnableRecaptchaLogin" name="enable_recaptcha_login" type="checkbox"
                                       class="js-switch"
                                       value="1" {if $CAPTCHA_LOGIN_VALUE eq 'true' } checked{/if} />
                            </div>
                            <div class="form-group">
                                <label for="InputCaptchaType">{$CAPTCHA_TYPE}</label>
                                <select name="captcha_type" id="InputCaptchaType" class="form-control">
                                    {foreach from=$CAPTCHA_OPTIONS item=option}
                                        <option value="{$option.value}" {if $option.active} selected{/if}>
                                            {$option.value}
                                        </option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="InputRecaptcha">{$CAPTCHA_SITE_KEY}</label>
                                <input type="text" name="recaptcha" class="form-control" id="InputRecaptcha"
                                       placeholder="{$CAPTCHA_SITE_KEY}" value="{$CAPTCHA_SITE_KEY_VALUE}">
                            </div>
                            <div class="form-group">
                                <label for="InputRecaptchaSecret">{$CAPTCHA_SECRET_KEY}</label>
                                <input type="text" name="recaptcha_secret" class="form-control"
                                       id="InputRecaptchaSecret" placeholder="{$CAPTCHA_SECRET_KEY}"
                                       value="{$CAPTCHA_SECRET_KEY_VALUE}">
                            </div>
                            <div class="form-group">
                                <label for="InputRegistrationDisabledMessage">{$REGISTRATION_DISABLED_MESSAGE}</label>
                                <textarea style="width:100%" rows="10" name="message"
                                          id="InputRegistrationDisabledMessage">{$REGISTRATION_DISABLED_MESSAGE_VALUE}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="InputValidationPromoteGroup">{$VALIDATE_PROMOTE_GROUP}</label> <span
                                        class="badge badge-info" data-toggle="popover" data-title="{$INFO}"
                                        data-content="{$VALIDATE_PROMOTE_GROUP_INFO}"><i
                                            class="fa fa-question"></i></span>
                                <select class="form-control" id="InputValidationPromoteGroup" name="promote_group">
                                    {foreach from=$GROUPS item=group}
                                        <option value="{$group->id}" {if $group->id eq $VALIDATION_GROUP} selected{/if}>{$group->name|escape}</option>
                                    {/foreach}
                                </select>
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

    <!-- End Wrapper -->
</div>

{include file='scripts.tpl'}

</body>

</html>