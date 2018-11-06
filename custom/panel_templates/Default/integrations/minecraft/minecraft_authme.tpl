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
                        <h1 class="m-0 text-dark">{$AUTHME}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$INTEGRATIONS}</li>
                            <li class="breadcrumb-item"><a href="{$MINECRAFT_LINK}">{$MINECRAFT}</a></li>
                            <li class="breadcrumb-item active">{$AUTHME}</li>
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

                            <div class="callout callout-info">
                                <h5><i class="icon fa fa-info-circle"></i> {$INFO}</h5>
                                {$AUTHME_INFO}
                            </div>

                            <form id="enableAuthMe" action="" method="post">
                                <label for="inputEnableAuthme">{$ENABLE_AUTHME}</label>
                                <input type="hidden" name="enable_authme" value="0">
                                <input id="inputEnableAuthme" name="enable_authme" type="checkbox" class="js-switch js-check-change"{if $ENABLE_AUTHME_VALUE} checked{/if} value="1"/>
                                <input type="hidden" name="token" value="{$TOKEN}">
                            </form>

                            {if isset($AUTHME_DB_DETAILS)}
                                <hr />
                                <form action="" method="post">
                                    <div class="form-group">
                                        <label for="inputHashingAlgorithm">{$AUTHME_HASH_ALGORITHM}</label>
                                        <select id="inputHashingAlgorithm" class="form-control" name="hashing_algorithm">
                                            <option value="bcrypt"{if isset($AUTHME_DB_DETAILS->hash) && $AUTHME_DB_DETAILS->hash eq 'bcrypt'} selected{/if}>bcrypt</option>
                                            <option value="sha1"{if isset($AUTHME_DB_DETAILS->hash) && $AUTHME_DB_DETAILS->hash eq 'sha1'} selected{/if}>SHA1</option>
                                            <option value="sha256"{if isset($AUTHME_DB_DETAILS->hash) && $AUTHME_DB_DETAILS->hash eq 'sha256'} selected{/if}>SHA256</option>
                                            <option value="pbkdf2"{if isset($AUTHME_DB_DETAILS->hash) && $AUTHME_DB_DETAILS->hash eq 'pbkdf2'} selected{/if}>PBKDF2</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputDBAddress">{$AUTHME_DB_ADDRESS}</label>
                                        <input type="text" class="form-control" name="db_address" value="{if isset($AUTHME_DB_DETAILS->address)}{$AUTHME_DB_DETAILS->address|escape}{/if}">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputDBPort">{$AUTHME_DB_PORT}</label>
                                        <input type="text" class="form-control" name="db_port" value="{if isset($AUTHME_DB_DETAILS->port)}{$AUTHME_DB_DETAILS->port|escape}{else}3306{/if}">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputDBName">{$AUTHME_DB_NAME}</label>
                                        <input type="text" class="form-control" name="db_name" value="{if isset($AUTHME_DB_DETAILS->db)}{$AUTHME_DB_DETAILS->db|escape}{/if}">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputDBUsername">{$AUTHME_DB_USER}</label>
                                        <input type="text" class="form-control" name="db_username" value="{if isset($AUTHME_DB_DETAILS->user)}{$AUTHME_DB_DETAILS->user|escape}{/if}">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputDBPassword">{$AUTHME_DB_PASSWORD}</label> <span class="badge badge-info"><i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-placement="top" title="{$INFO}" data-content="{$AUTHME_DB_PASSWORD_HIDDEN}"></i></span>
                                        <input type="password" class="form-control" name="db_password">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputDBTable">{$AUTHME_DB_TABLE}</label>
                                        <input type="text" class="form-control" name="db_table" value="{if isset($AUTHME_DB_DETAILS->table)}{$AUTHME_DB_DETAILS->table|escape}{else}authme{/if}">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputAuthmeSync">{$AUTHME_PASSWORD_SYNC}</label> <span class="badge badge-info"><i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-placement="top" title="{$INFO}" data-content="{$AUTHME_PASSWORD_SYNC_HELP}"></i></span>
                                        <input type="hidden" name="authme_sync" value="0">
                                        <input id="inputAuthmeSync" name="authme_sync" type="checkbox" class="js-switch"{if isset($AUTHME_DB_DETAILS->sync) && $AUTHME_DB_DETAILS->sync} checked{/if} value="1"/>
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="token" value="{$TOKEN}">
                                        <input type="submit" value="{$SUBMIT}" class="btn btn-primary">
                                    </div>
                                </form>
                            {/if}

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