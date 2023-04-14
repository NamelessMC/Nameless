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
                        <h1 class="h3 mb-0 text-gray-800">{$PAGE_METADATA}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                            <li class="breadcrumb-item">{$SEO}</li>
                            <li class="breadcrumb-item active">{$PAGE_METADATA}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='includes/update.tpl'}

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <!-- Success and Error Alerts -->
                            {include file='includes/alerts.tpl'}

                            <h5 style="display:inline">{$EDITING_PAGE}</h5>

                            <div class="float-md-right">
                                <a href="{$BACK_LINK}" class="btn btn-primary">{$BACK}</a>
                            </div>

                            <hr />

                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="inputDescription">{$DESCRIPTION}</label>
                                    <textarea class="form-control" name="description"
                                        id="inputDescription">{$DESCRIPTION_VALUE}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="inputKeywords">{$KEYWORDS}</label>
                                    <input type="text" class="form-control" name="keywords" id="inputKeywords"
                                        value="{$KEYWORDS_VALUE}" placeholder="{$KEYWORDS}">
                                </div>
                                {if $OG_IMAGES_ARRAY|count}
                                    <div class="form-group">
                                        <label for="inputImage">{$IMAGE}</label>
                                        <select name="inputImage" class="image-picker show-html">
                                            {foreach from=$OG_IMAGES_ARRAY item=image}
                                                <option data-img-src="{$image.src}" value="{$image.value}" {if $image.selected}selected{/if}>
                                                    {$image.n}
                                                </option>
                                            {/foreach}
                                        </select>
                                    </div>
                                {/if}
                                <div class="form-group">
                                    <input type="hidden" name="token" value="{$TOKEN}">
                                    <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
                                </div>
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

    <script>
        $(".image-picker").imagepicker();
    </script>

</body>

</html>
