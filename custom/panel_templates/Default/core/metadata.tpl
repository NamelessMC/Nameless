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
                        <h1 class="m-0 text-dark">{$PAGE_METADATA}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$PAGES}</li>
                            <li class="breadcrumb-item active">{$PAGE_METADATA}</li>
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

                        <div class="table-responsive">
                            <table class="table table-striped dataTables-pages">
                                <thead>
                                    <tr>
                                        <th>{$PAGE_TITLE}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                {foreach from=$PAGE_LIST key=key item=item}
                                    <tr>
                                        <td><a href="{$EDIT_LINK|replace:'{x}':$item.id}">{$key|escape}</a></td>
                                    </tr>
                                {/foreach}
                                </tbody>
                            </table>
                        </div>

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