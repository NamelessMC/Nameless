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
                        <h1 class="m-0 text-dark">{$EMAILS}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                            <li class="breadcrumb-item active">{$EMAILS}</li>
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

                            <a href="{$EMAIL_ERRORS_LINK}" class="btn btn-primary">{$EMAIL_ERRORS}</a>
                            <a href="{$SEND_TEST_EMAIL_LINK}" class="btn btn-primary">{$SEND_TEST_EMAIL}</a>

                            <hr />

                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="inputMailer">{$ENABLE_MAILER}</label>
                                    <span class="badge badge-info"><i class="fa fa-question-circle"
                                                                      data-container="body"
                                                                      data-toggle="popover"
                                                                      title="{$INFO}"
                                                                      data-content="{$ENABLE_MAILER_HELP}"></i></span>
                                    <input type="hidden" name="enable_mailer" value="0">
                                    <input id="inputMailer" name="enable_mailer" type="checkbox"
                                           class="js-switch"
                                           value="1"{if $ENABLE_MAILER_VALUE eq 1} checked{/if} />
                                </div>
                                <div class="form-group">
                                    <label for="InputOutgoingEmail">{$OUTGOING_EMAIL}</label>
                                    <span class="badge badge-info"><i class="fa fa-question-circle"
                                                                      data-container="body"
                                                                      data-toggle="popover"
                                                                      title="{$INFO}"
                                                                      data-content="{$OUTGOING_EMAIL_INFO}"></i></span>
                                    <input type="text" id="InputOutgoingEmail" name="email"
                                           value="{$OUTGOING_EMAIL_VALUE}"
                                           class="form-control">
                                </div>
                                <hr/>
                                <div class="callout callout-info">
                                    <h5><i class="icon fa fa-info-circle"></i> {$INFO}</h5>
                                    {$MAILER_SETTINGS_INFO}
                                </div>
                                <div class="form-group">
                                    <label for="inputUsername">{$USERNAME}</label>
                                    <input class="form-control" type="text" name="username"
                                           value="{$USERNAME_VALUE}"
                                           id="inputUsername">
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword">{$PASSWORD}</label>
                                    <span class="badge badge-info"><i class="fa fa-question-circle"
                                                                      data-container="body"
                                                                      data-toggle="popover"
                                                                      title="{$INFO}"
                                                                      data-content="{$PASSWORD_HIDDEN}"></i></span>
                                    <input class="form-control" type="password" name="password"
                                           id="inputPassword">
                                </div>
                                <div class="form-group">
                                    <label for="inputName">{$NAME}</label>
                                    <input class="form-control" type="text" name="name"
                                           value="{$NAME_VALUE}"
                                           id="inputName">
                                </div>
                                <div class="form-group">
                                    <label for="inputHost">{$HOST}</label>
                                    <input class="form-control" type="text" name="host"
                                           value="{$HOST_VALUE}"
                                           id="inputHost">
                                </div>
                                <div class="form-group">
                                    <label for="inputPort">{$PORT}</label>
                                    <input class="form-control" type="text" name="port"
                                           value="{$PORT_VALUE}"
                                           id="inputPort">
                                </div>
                                <hr/>
                                <div class="form-group">
                                    <input type="hidden" name="token" value="{$TOKEN}">
                                    <input type="submit" class="btn btn-primary"
                                           value="{$SUBMIT}">
                                </div>
                            </form>

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

</body>
</html>