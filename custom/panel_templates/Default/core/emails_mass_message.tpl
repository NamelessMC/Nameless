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
                            <h1 class="m-0 text-dark">{$EMAILS}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                                <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                                <li class="breadcrumb-item active">{$EMAILS}</li>
                                <li class="breadcrumb-item active">{$EMAILS_MASS_MESSAGE}</li>
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
                            <h5 style="display:inline;">{$SENDING_MASS_MESSAGE}</h5>
                            
                            {include file='includes/success.tpl'}

                            {include file='includes/errors.tpl'}

                            <div class="float-md-right">
                                <a href="{$BACK_LINK}" class="btn btn-warning">{$BACK}</a>
                            </div>
                            <hr />

                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="inputSubject">{$SUBJECT}</label>
                                    <input class="form-control" type="text" name="subject" id="inputSubject">
                                </div>
                                <div class="form-group">
                                <label for="inputContent">{$CONTENT} <span class="badge badge-info"><i class="fa fa-lightbulb" data-container="body" data-toggle="popover" title="{$INFO}" data-content="{$REPLACEMENT_INFO}"></i></span></label>
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
                                    <input type="submit" class="btn btn-primary" value="{$SUBMIT}" onclick="$('#loading').css('visibility', 'visible');">
                                    <strong style="visibility:hidden; color:orange;" id="loading">{$LOADING}</strong>
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