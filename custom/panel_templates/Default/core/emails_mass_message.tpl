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
                    <h1 class="h3 mb-0 text-gray-800">{$EMAILS}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                        <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                        <li class="breadcrumb-item"><a href="{$EMAILS_LINK}">{$EMAILS}</a></li>
                        <li class="breadcrumb-item active">{$EMAILS_MASS_MESSAGE}</li>
                    </ol>
                </div>

                <!-- Update Notification -->
                {include file='includes/update.tpl'}

                <div class="card shadow mb-4">
                    <div class="card-body">
                        <a class="btn btn-primary" href="{$BACK_LINK}">{$BACK}</a>
                        <hr />

                        <!-- Success and Error Alerts -->
                        {include file='includes/alerts.tpl'}

                        <form action="" method="post">
                            <div class="form-group">
                                <label for="inputSubject">{$SUBJECT}</label>
                                <input class="form-control" type="text" name="subject" id="inputSubject">
                            </div>
                            <div class="form-group">
                                <label for="inputContent">{$CONTENT} <span class="badge badge-info"><i
                                                class="fa fa-lightbulb" data-container="body" data-toggle="popover"
                                                title="{$INFO}" data-content="{$REPLACEMENT_INFO}"></i></span></label>
                                {if isset($MARKDOWN)}
                                    <div class="field">
                                        <textarea name="content" id="markdown"></textarea>
                                    </div>
                                {else}
                                    <div class="field">
                                        <textarea name="content" id="reply"></textarea>
                                    </div>
                                {/if}
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <input type="submit" class="btn btn-primary" value="{$SUBMIT}"
                                       onclick="$('#loading').css('visibility', 'visible');">
                                <strong style="visibility:hidden; color:orange;" id="loading">{$LOADING}</strong>
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

</body>

</html>