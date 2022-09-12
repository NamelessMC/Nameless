{include file='header.tpl'}

<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-9 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">{$PLEASE_REAUTHENTICATE}</h1>
                            </div>
                            {if isset($ERROR)}
                            <div class="alert bg-danger text-white">
                                {$ERROR}
                            </div>
                            {/if}
                            <form class="user" action="" method="post">
                                <div class="form-group has-feedback">
                                    <input type="password" name="password" id="password"
                                        class="form-control form-control-user" placeholder="{$PASSWORD}">
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <input type="hidden" name="token" value="{$TOKEN}">
                                        <button type="submit"
                                            class="btn btn-primary btn-block btn-user">{$SUBMIT}</button>
                                    </div>
                                    <div class="col-6">
                                        <a href="{$SITE_HOME}" class="btn btn-danger btn-block btn-user">{$CANCEL}</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {include file='scripts.tpl'}

</body>

</html>