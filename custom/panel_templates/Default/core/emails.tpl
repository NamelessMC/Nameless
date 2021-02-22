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
                    <h1 class="h3 mb-0 text-gray-800">{$EMAILS}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                        <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                        <li class="breadcrumb-item active">{$EMAILS}</li>
                    </ol>
                </div>

                <!-- Update Notification -->
                {include file='includes/update.tpl'}

                <div class="card shadow mb-4">
                    <div class="card-body">
                        {if isset($MASS_MESSAGE_LINK)}
                            <a href="{$MASS_MESSAGE_LINK}" class="btn btn-primary">{$MASS_MESSAGE}</a>
                        {/if}
                        <a href="{$EDIT_EMAIL_MESSAGES_LINK}" class="btn btn-primary">{$EDIT_EMAIL_MESSAGES}</a>
                        <a href="{$EMAIL_ERRORS_LINK}" class="btn btn-primary">{$EMAIL_ERRORS}</a>
                        <a href="{$SEND_TEST_EMAIL_LINK}" class="btn btn-info">{$SEND_TEST_EMAIL}</a>

                        <hr />

                        <!-- Success and Error Alerts -->
                        {include file='includes/alerts.tpl'}

                        <form action="" method="post">
                            <div class="form-group">
                                <label for="InputOutgoingEmail">{$OUTGOING_EMAIL}</label>
                                <span class="badge badge-info"><i class="fa fa-question-circle" data-container="body"
                                                                  data-toggle="popover" title="{$INFO}"
                                                                  data-content="{$OUTGOING_EMAIL_INFO}"></i></span>
                                <input type="text" id="InputOutgoingEmail" name="email" value="{$OUTGOING_EMAIL_VALUE}"
                                       class="form-control">
                            </div>
                            <hr />
                            <div class="card shadow border-left-primary">
                                <div class="card-body">
                                    <h5><i class="icon fa fa-info-circle"></i> {$INFO}</h5>
                                    {$MAILER_SETTINGS_INFO}
                                </div>
                            </div>
                            <br />
                            <div class="form-group">
                                <label for="inputMailer">{$ENABLE_MAILER}</label>
                                <span class="badge badge-info"><i class="fa fa-question-circle" data-container="body"
                                                                  data-toggle="popover" title="{$INFO}"
                                                                  data-content="{$ENABLE_MAILER_HELP}"></i></span>
                                <input type="hidden" name="enable_mailer" value="0">
                                <input id="inputMailer" name="enable_mailer" type="checkbox" class="js-switch"
                                       value="1" {if $ENABLE_MAILER_VALUE eq 1} checked{/if} />
                            </div>
                            <div class="form-group">
                                <label for="inputUsername">{$USERNAME}</label>
                                <input class="form-control" type="text" name="username" value="{$USERNAME_VALUE}"
                                       id="inputUsername">
                            </div>
                            <div class="form-group">
                                <label for="inputPassword">{$PASSWORD}</label>
                                <span class="badge badge-info"><i class="fa fa-question-circle" data-container="body"
                                                                  data-toggle="popover" title="{$INFO}"
                                                                  data-content="{$PASSWORD_HIDDEN}"></i></span>
                                <input class="form-control" type="password" name="password" id="inputPassword">
                            </div>
                            <div class="form-group">
                                <label for="inputName">{$NAME}</label>
                                <input class="form-control" type="text" name="name" value="{$NAME_VALUE}"
                                       id="inputName">
                            </div>
                            <div class="form-group">
                                <label for="inputHost">{$HOST}</label>
                                <input class="form-control" type="text" name="host" value="{$HOST_VALUE}"
                                       id="inputHost">
                            </div>
                            <div class="form-group">
                                <label for="inputPort">{$PORT}</label>
                                <input class="form-control" type="text" name="port" value="{$PORT_VALUE}"
                                       id="inputPort">
                            </div>
                            <hr />
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