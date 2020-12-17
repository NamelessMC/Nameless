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
                        <h1 class="m-0 text-dark">{$SEND_TEST_EMAIL}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                            <li class="breadcrumb-item"><a href="{$BACK_LINK}">{$EMAILS}</a></li>
                            <li class="breadcrumb-item active">{$SEND_TEST_EMAIL}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                {include file='includes/update.tpl'}

                <div class="card">
                    <div class="card-body">

                        {include file='includes/success.tpl'}

                        {include file='includes/errors.tpl'}

                        <a class="btn btn-primary" href="{$BACK_LINK}">{$BACK}</a>

                        {if isset($SEND_TEST_EMAIL_INFO)}
                            <hr />

                            <div class="callout callout-info">
                                <h5><i class="icon fa fa-info-circle"></i> {$INFO}</h5>
                                {$SEND_TEST_EMAIL_INFO}
                            </div>

                            <a href="{$SEND_LINK}" class="btn btn-primary">{$SEND}</a>
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