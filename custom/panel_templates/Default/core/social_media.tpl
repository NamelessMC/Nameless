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
                        <h1 class="h3 mb-0 text-gray-800">{$SOCIAL_MEDIA}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                            <li class="breadcrumb-item active">{$SOCIAL_MEDIA}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='includes/update.tpl'}

                    <div class="card shadow mb-4">
                        <div class="card-body">

                            <!-- Success and Error Alerts -->
                            {include file='includes/alerts.tpl'}

                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="InputYoutube">{$YOUTUBE_URL}</label>
                                    <input type="text" name="youtubeurl" class="form-control" id="InputYoutube"
                                        placeholder="{$YOUTUBE_URL}" value="{$YOUTUBE_URL_VALUE}">
                                </div>
                                <div class="form-group">
                                    <label for="InputTwitter">{$TWITTER_URL}</label>
                                    <input type="text" name="twitterurl" class="form-control" id="InputTwitter"
                                        placeholder="{$TWITTER_URL}" value="{$TWITTER_URL_VALUE}">
                                </div>
                                <div class="form-group custom-control custom-switch">
                                    <input id="InputTwitterStyle" type="checkbox" name="twitter_dark_theme"
                                        class="custom-control-input" value="1" {if $TWITTER_STYLE_VALUE eq 'dark' }
                                        checked{/if}>
                                    <label for="InputTwitterStyle" class="custom-control-label">
                                        {$TWITTER_STYLE}
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="InputFacebook">{$FACEBOOK_URL}</label>
                                    <input type="text" name="fburl" class="form-control" id="InputFacebook"
                                        placeholder="{$FACEBOOK_URL}" value="{$FACEBOOK_URL_VALUE}">
                                </div>
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
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