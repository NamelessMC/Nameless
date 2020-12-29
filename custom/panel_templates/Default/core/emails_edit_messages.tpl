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
                    <h1 class="h3 mb-0 text-gray-800">{$EMAILS_MESSAGES}</h1>
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                        <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                        <li class="breadcrumb-item active">{$EMAILS}</li>
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

                        <form action="{$BACK_LINK}" method="post">
                            <h4>{$OPTIONS}</h4>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="greeting">{$GREETING}</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="{$GREETING_VALUE}"
                                               name="greeting">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="thanks">{$THANKS}</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="{$THANKS_VALUE}" name="thanks">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="inputDefaultLanguage">{$SELECT_LANGUAGE}</label>
                                    <span class="badge badge-info"><i class="fa fa-question-circle"
                                                                      data-container="body" data-toggle="popover"
                                                                      title="{$INFO}"
                                                                      data-content="{$LANGUAGE_INFO}"></i></span>
                                    <div class="input-group">
                                        <select name="editing_language" class="form-control" id="inputDefaultLanguage">
                                            {foreach from=$LANGUAGES item=item}
                                                <option value="{$item->name}" {if $item->name eq $EDITING_LANGUAGE} selected{/if}>{$item->name}</option>
                                            {/foreach}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            {foreach from=$EMAILS_LIST item=item}
                                <h4>{$item[1]}</h4>
                                <div class="row">
                                    <div class="col-md-5">
                                        <label for="{$item[1]}_message">{$SUBJECT}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" value="{$item[2]['subject']}"
                                                   name="{$item[0]}_subject" id="{$item[0]}_subject">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="{$item[1]}_message">{$MESSAGE}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" value="{$item[2]['message']}"
                                                   name="{$item[0]}_message" id="{$item[0]}_message">
                                        </div>
                                    </div>
                                    <div class="col-md-1" align="center">
                                        <button class="btn btn-success"
                                                onclick="window.open('{$BACK_LINK}&action=preview&email={$item[0]}', 'newwindow', 'width=700,height=375'); return false;"
                                                style="margin-top: 32px;"><i class="fas fa-share-square"
                                                                             data-container="body" data-toggle="popover"
                                                                             data-placement="top" title="{$PREVIEW}"
                                                                             data-content="{$PREVIEW_INFO}"></i>
                                        </button>
                                    </div>
                                </div>
                                <br>
                            {/foreach}
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

</body>

</html>