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
                        <h1 class="m-0 text-dark">{$REPORTS}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$USER_MANAGEMENT}</li>
                            <li class="breadcrumb-item active">{$REPORTS}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                {if isset($NEW_UPDATE)}
                {if $NEW_UPDATE_URGENT eq true}
                <div class="alert alert-danger">
                    {else}
                    <div class="alert alert-primary alert-dismissible" id="updateAlert">
                        <button type="button" class="close" id="closeUpdate" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {/if}
                        {$NEW_UPDATE}
                        <br />
                        <a href="{$UPDATE_LINK}" class="btn btn-primary" style="text-decoration:none">{$UPDATE}</a>
                        <hr />
                        {$CURRENT_VERSION}<br />
                        {$NEW_VERSION}
                    </div>
                    {/if}

                    <div class="card">
                        <div class="card-body">
                            <h4 style="display:inline;">{$VIEWING_REPORT} &raquo; <a target="_blank" href="{$REPORTED_USER_PROFILE}" style="{$REPORTED_USER_STYLE}">{$REPORTED_USER}</a> {if ($TYPE == 0)}| <small><a href="{$CONTENT_LINK}" target="_blank">{$VIEW_CONTENT}</a></small>{/if}</h4>
                            <div class="float-md-right">
                                <a href="{$REPORTS_LINK}" class="btn btn-info">{$BACK}</a>
                            </div>

                            <hr />

                            {if isset($SUCCESS)}
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h5><i class="icon fa fa-check"></i> {$SUCCESS_TITLE}</h5>
                                    {$SUCCESS}
                                </div>
                            {/if}

                            {if isset($ERRORS) && count($ERRORS)}
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h5><i class="icon fas fa-exclamation-triangle"></i> {$ERRORS_TITLE}</h5>
                                    <ul>
                                        {foreach from=$ERRORS item=error}
                                            <li>{$error}</li>
                                        {/foreach}
                                    </ul>
                                </div>
                            {/if}

                            <div class="card">
                                <div class="card-header">
                                    <a href="{$REPORTER_USER_PROFILE}" style="{$REPORTER_USER_STYLE}" target="_blank"><img src="{$REPORTER_USER_AVATAR}" class="rounded" style="max-width:25px;max-height:25px;" alt="{$REPORTER_USER}" /> {$REPORTER_USER}</a>:
                                    <span class="pull-right" data-toggle="tooltip" data-original-title="{$REPORT_DATE}">{$REPORT_DATE_FRIENDLY}</span>
                                </div>
                                <div class="card-body">
                                    {$REPORT_CONTENT}
                                </div>
                            </div>

                            <h5>{$COMMENTS_TEXT}</h5>
                            {if count($COMMENTS)}
                                {foreach from=$COMMENTS item=comment}
                                    <div class="card">
                                        <div class="card-header">
                                            <a href="{$comment.profile}" style="{$comment.style}" target="_blank"><img src="{$comment.avatar}" class="rounded" style="max-height:25px;max-width:25px;" alt="{$comment.username}" /> {$comment.username}</a>:
                                            <span class="pull-right" data-toggle="tooltip" data-original-title="{$comment.date}">{$comment.date_friendly}</span>
                                        </div>
                                        <div class="card-body">
                                            {$comment.content}
                                        </div>
                                    </div>
                                {/foreach}
                            {else}
                                {$NO_COMMENTS}
                            {/if}

                            <hr />

                            <form action="" method="post">
                                <div class="form-group">
                                    <textarea class="form-control" name="content" rows="5" placeholder="{$NEW_COMMENT}"></textarea>
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

                </div>
        </section>
    </div>

    {include file='footer.tpl'}

</div>
<!-- ./wrapper -->

{include file='scripts.tpl'}

</body>
</html>