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
                    <h1 class="h3 mb-0 text-gray-800">{$REPORTS}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                        <li class="breadcrumb-item active">{$USER_MANAGEMENT}</li>
                        <li class="breadcrumb-item active">{$REPORTS}</li>
                    </ol>
                </div>

                <!-- Update Notification -->
                {include file='includes/update.tpl'}

                <!-- Success and Error Alerts -->
                {include file='includes/alerts.tpl'}

                <div class="card shadow mb-4">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-9">
                                <h5 style="margin-top: 7px; margin-bottom: 7px;">{$VIEWING_REPORT} &raquo; <a
                                            target="_blank" href="{$REPORTED_USER_PROFILE}"
                                            style="{$REPORTED_USER_STYLE}">{$REPORTED_USER}</a> {if ($TYPE == 0)}|
                                        <small><a href="{$CONTENT_LINK}" target="_blank">{$VIEW_CONTENT}</a>
                                        </small>{/if}</h5>
                            </div>
                            <div class="col-md-3">
                                <span class="float-md-right"><a href="{$REPORTS_LINK}"
                                                                class="btn btn-primary">{$BACK}</a></span>
                            </div>
                        </div>
                        <hr />

                        <div class="card shadow">
                            <div class="card-header">
                                <a href="{$REPORTER_USER_PROFILE}" style="{$REPORTER_USER_STYLE}" target="_blank"><img
                                            src="{$REPORTER_USER_AVATAR}" class="rounded"
                                            style="max-width:25px;max-height:25px;"
                                            alt="{$REPORTER_USER}" /> {$REPORTER_USER}</a>:
                                <span class="pull-right" data-toggle="tooltip"
                                      data-original-title="{$REPORT_DATE}">{$REPORT_DATE_FRIENDLY}</span>
                            </div>
                            <div class="card-body">
                                {$REPORT_CONTENT}
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h5>{$COMMENTS_TEXT}</h5>
                        <hr />
                        {if count($COMMENTS)}
                            {foreach from=$COMMENTS item=comment}
                                <div class="card shadow">
                                    <div class="card-header">
                                        <a href="{$comment.profile}" style="{$comment.style}" target="_blank"><img
                                                    src="{$comment.avatar}" class="rounded"
                                                    style="max-height:25px;max-width:25px;"
                                                    alt="{$comment.username}" /> {$comment.username}</a>:
                                        <span class="pull-right" data-toggle="tooltip"
                                              data-original-title="{$comment.date}">{$comment.date_friendly}</span>
                                    </div>
                                    <div class="card-body">
                                        {$comment.content}
                                    </div>
                                </div>
                                <br />
                            {/foreach}
                            <hr />
                        {else}
                            {$NO_COMMENTS}
                            <br />
                            <br />
                        {/if}

                        <form action="" method="post">
                            <div class="form-group">
                                <textarea class="form-control" name="content" rows="5"
                                          placeholder="{$NEW_COMMENT}"></textarea>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <input type="submit" value="{$SUBMIT}" class="btn btn-primary">
                                <div class="float-md-right">
                                    {if isset($CLOSE_REPORT)}
                                        <a href="{$CLOSE_LINK}" class="btn btn-danger">{$CLOSE_REPORT}</a>
                                    {else}
                                        <a href="{$REOPEN_LINK}" class="btn btn-danger">{$REOPEN_REPORT}</a>
                                    {/if}
                                </div>
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