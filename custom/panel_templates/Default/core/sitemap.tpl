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
                        <h1 class="h3 mb-0 text-gray-800">{$SITEMAP}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$LAYOUT}</li>
                            <li class="breadcrumb-item active">{$SITEMAP}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='update.tpl'}

                    <div class="card shadow mb-4">
                        <div class="card-body">

                            <!-- Success and Error Alerts -->
                            {include file='alerts.tpl'}

                            {if isset($SITEMAP_LAST_GENERATED)}
                                    <p>{$SITEMAP_LAST_GENERATED}</p>
                                    <p>{$LINK}<br /><code>{$SITEMAP_FULL_LINK}</code></p>
                                    <p><a href="{$SITEMAP_LINK}" class="btn btn-primary" download style="color:#fff;text-decoration:none">{$DOWNLOAD_SITEMAP}</a></p>
                            {else}
                                    <p>{$SITEMAP_NOT_GENERATED}</p>
                            {/if}
                            <hr />
                            <form action="" method="post">
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <input type="submit" class="btn btn-primary" value="{$GENERATE}">
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