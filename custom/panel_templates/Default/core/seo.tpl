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
                        <h1 class="h3 mb-0 text-gray-800">{$SEO}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                            <li class="breadcrumb-item active">{$SEO}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='includes/update.tpl'}

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <!-- Success and Error Alerts -->
                            {include file='includes/alerts.tpl'}

                            <h4>{$GOOGLE_ANALYTICS}</h4>
                            <p>{$GOOGLE_ANALYTICS_HELP}</p>

                            <form action="" method="post">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="analyticsid" id="inputAnalyticsId"
                                        placeholder="Google Analytics ID" value="{$GOOGLE_ANALYTICS_VALUE}">
                                </div>
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <input type="hidden" name="type" value="google_analytics">
                                <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
                            </form>

                            <hr />

                            <h4>{$SITEMAP}</h4>
                            {if isset($SITEMAP_LAST_GENERATED)}
                            <p>{$SITEMAP_LAST_GENERATED}</p>
                            <p>{$LINK}<br /><code>{$SITEMAP_FULL_LINK}</code></p>
                            <form action="" method="post">
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <input type="hidden" name="type" value="sitemap">
                                <input type="submit" class="btn btn-primary" value="{$GENERATE}">
                                <a href="{$SITEMAP_LINK}" class="btn btn-primary" download
                                    style="color:#fff;text-decoration:none">{$DOWNLOAD_SITEMAP}</a>
                            </form>
                            {else}
                            <p>{$SITEMAP_NOT_GENERATED}</p>
                            <form action="" method="post">
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <input type="hidden" name="type" value="sitemap">
                                <input type="submit" class="btn btn-primary" value="{$GENERATE}">
                            </form>
                            {/if}

                            <hr />

                            <h4 style="display:inline;">{$PAGE_METADATA}</h4>
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
