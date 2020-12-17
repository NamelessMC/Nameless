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
                        <h1 class="m-0 text-dark">{$SECURITY}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$SECURITY}</li>
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
                        <h5 style="display:inline">{$LOG_TITLE}</h5>
                        <div class="float-md-right">
                            <a href="{$BACK_LINK}" class="btn btn-primary">{$BACK}</a>
                        </div>
                        <hr />

                        {include file='includes/success.tpl'}

                        {include file='includes/errors.tpl'}

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover dataTable">
                                <thead>
                                <tr>
                                    {foreach from=$COL_TITLES item=col_title}
                                        <td>{$col_title}</td>
                                    {/foreach}
                                </tr>
                                </thead>
                                <tbody>
                                {foreach from=$ROWS item=row}
                                    <tr>
                                        {assign var=i value=0}
                                        {while $i < count($row)}
                                            <td{if isset($row.$i.order)} data-order="{$row.$i.order}"{/if}>
                                                {$row.$i.content}
                                            </td>
                                            {assign var=i value=$i+1}
                                        {/while}
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