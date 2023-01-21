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
                        <h1 class="h3 mb-0 text-gray-800">{$AUTHME}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$INTEGRATIONS}</li>
                            <li class="breadcrumb-item"><a href="{$MINECRAFT_LINK}">{$MINECRAFT}</a></li>
                            <li class="breadcrumb-item active">{$AUTHME}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='includes/update.tpl'}

                    <div class="card shadow mb-4">
                        <div class="card-body">

                            <!-- Success and Error Alerts -->
                            {include file='includes/alerts.tpl'}

                            <div class="card shadow border-left-primary">
                                <div class="card-body">
                                    <h5><i class="icon fa fa-info-circle"></i> {$INFO}</h5>
                                    {$AUTHME_INFO}
                                </div>
                            </div>
                            <br />

                            <form id="enableAuthMe" action="" method="post">
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <div class="form-group custom-control custom-switch">
                                    <input type="hidden" name="enable_authme" value="0">
                                    <input id="inputEnableAuthme" name="enable_authme" type="checkbox" class="custom-control-input js-check-change" value="1" {if $ENABLE_AUTHME_VALUE} checked{/if} />
                                    <label for="inputEnableAuthme" class="custom-control-label">
                                        {$ENABLE_AUTHME}
                                    </label>
                                </div>
                            </form>

                            {if isset($AUTHME_DB_DETAILS)}
                            <hr />
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="inputHashingAlgorithm">{$AUTHME_HASH_ALGORITHM}</label>
                                    <select id="inputHashingAlgorithm" class="form-control" name="hashing_algorithm">
                                        <option value="bcrypt" {if isset($AUTHME_DB_DETAILS['hash']) && $AUTHME_DB_DETAILS['hash'] eq 'bcrypt'} selected{/if}>
                                            bcrypt
                                        </option>
                                        <option value="sha1" {if isset($AUTHME_DB_DETAILS['hash']) && $AUTHME_DB_DETAILS['hash'] eq 'sha1'} selected{/if}>
                                            SHA1
                                        </option>
                                        <option value="sha256" {if isset($AUTHME_DB_DETAILS['hash']) && $AUTHME_DB_DETAILS['hash'] eq 'sha256'} selected{/if}>
                                            SHA256
                                        </option>
                                        <option value="pbkdf2" {if isset($AUTHME_DB_DETAILS['hash']) && $AUTHME_DB_DETAILS['hash'] eq 'pbkdf2'} selected{/if}>
                                            PBKDF2
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="inputDBAddress">{$AUTHME_DB_ADDRESS}</label>
                                    <input type="text" class="form-control" name="db_address" value="{if isset($AUTHME_DB_DETAILS['address'])}{$AUTHME_DB_DETAILS['address']|escape}{/if}">
                                </div>
                                <div class="form-group">
                                    <label for="inputDBPort">{$AUTHME_DB_PORT}</label>
                                    <input type="text" class="form-control" name="db_port" value="{if isset($AUTHME_DB_DETAILS['port'])}{$AUTHME_DB_DETAILS['port']|escape}{else}3306{/if}">
                                </div>
                                <div class="form-group">
                                    <label for="inputDBName">{$AUTHME_DB_NAME}</label>
                                    <input type="text" class="form-control" name="db_name" value="{if isset($AUTHME_DB_DETAILS['db'])}{$AUTHME_DB_DETAILS['db']|escape}{/if}">
                                </div>
                                <div class="form-group">
                                    <label for="inputDBUsername">{$AUTHME_DB_USER}</label>
                                    <input type="text" class="form-control" name="db_username" value="{if isset($AUTHME_DB_DETAILS['user'])}{$AUTHME_DB_DETAILS['user']|escape}{/if}">
                                </div>
                                <div class="form-group">
                                    <label for="inputDBPassword">{$AUTHME_DB_PASSWORD}</label> <span class="badge badge-info"><i class="fa fa-question-circle" data-container="body" data-toggle="popover" data-placement="top" title="{$INFO}" data-content="{$AUTHME_DB_PASSWORD_HIDDEN}"></i></span>
                                    <input type="password" class="form-control" name="db_password">
                                </div>
                                <div class="form-group">
                                    <label for="inputDBTable">{$AUTHME_DB_TABLE}</label>
                                    <input type="text" class="form-control" name="db_table" value="{if isset($AUTHME_DB_DETAILS['table'])}{$AUTHME_DB_DETAILS['table']|escape}{else}authme{/if}">
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="token" value="{$TOKEN}">
                                    <input type="submit" value="{$SUBMIT}" class="btn btn-primary">
                                    <button class="btn btn-info" id="testConnection">{$TEST_CONNECTION}</button>
                                    <span id="connectionTestResult"></span>
                                </div>
                            </form>
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

        <!-- End Wrapper -->
    </div>

    {include file='scripts.tpl'}

    <script>
        document.getElementById('testConnection').addEventListener('click', (e) => {
            e.preventDefault();

            e.target.classList.add('disabled');

            $.post('{$AUTHME_DB_CONNECTION_TEST_URL}', {
                token: '{$TOKEN}',
                db_address: $('input[name="db_address"]').val(),
                db_port: $('input[name="db_port"]').val(),
                db_name: $('input[name="db_name"]').val(),
                db_username: $('input[name="db_username"]').val(),
                db_password: $('input[name="db_password"]').val(),
                db_table: $('input[name="db_table"]').val(),
            }).done(function (resp) {
                const success = resp === 'OK';
                const colour = success ? 'success' : 'danger';
                const text = success ? '{$CONNECTION_SUCCESS}' : '{$CONNECTION_FAILED}';
                const icon = success ? 'check' : 'times';

                document.getElementById('connectionTestResult').innerHTML = '<span class="badge badge-' + colour + '" style="font-size: 0.9rem;">' + text + '&nbsp;<i class="fas fa-' + icon + '-circle"></i></span>';
            });

            e.target.classList.remove('disabled');
        });
    </script>

</body>

</html>
