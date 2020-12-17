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
                        <h1 class="m-0 text-dark">{$IP_LOOKUP}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$USER_MANAGEMENT}</li>
                            <li class="breadcrumb-item active">{$IP_LOOKUP}</li>
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
                        <h5 style="display:inline">{$COUNT_ACCOUNTS}</h5>

                        <div class="float-md-right">
                            <a class="btn btn-primary" href="{$BACK_LINK}">{$BACK}</a>
                        </div>

                        <hr />

                        {include file='includes/success.tpl'}

                        {include file='includes/errors.tpl'}

                        <ul>
                            {if isset($IP_SEARCH)}
                                {foreach from=$ACCOUNTS item=account}
                                    <li><a href="{$account.account_ips}" style="{$account.style}">{$account.username}</a></li>
                                {/foreach}
                            {else}
                                {foreach from=$ACCOUNTS item=account}
                                    <li><a href="{$account.link}">{$account.ip}</a></li>
                                {/foreach}
                            {/if}
                        </ul>

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