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
                        <h1 class="h3 mb-0 text-gray-800">{$EMAILS_MASS_MESSAGE}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$COMMUNICATIONS}</li>
                            <li class="breadcrumb-item active">{$MASS_MESSAGE}</li>
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
                                    <label for="inputMessageType">{$MESSAGE_TYPE}</label>
                                    <select class="form-control" name="type[]" id="inputMessageType" multiple>
                                        <option value="alert" selected>{$MESSAGE_TYPE_ALERT}</option>
                                        <option value="email" selected>{$MESSAGE_TYPE_EMAIL}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="inputSubject">{$SUBJECT}</label>
                                    <input class="form-control" type="text" name="subject" id="inputSubject">
                                </div>
                                <div class="form-group">
                                    <label for="inputContent">{$CONTENT} <span class="badge badge-info"><i
                                                class="fa fa-question-circle" data-container="body" data-toggle="popover"
                                                title="{$INFO}" data-content="{$REPLACEMENT_INFO}"></i></span></label>
                                    <div class="field">
                                        <textarea name="content" id="message"></textarea>
                                    </div>
                                </div>
                                <div class="form-group custom-control custom-switch">
                                    <input id="inputUnsafeHTML" name="unsafe_html" type="checkbox" class="custom-control-input" />
                                    <label class="custom-control-label" for="inputUnsafeHTML">{$UNSAFE_HTML}</label>
                                    <span data-toggle="popover" data-content="{$UNSAFE_HTML_WARNING}"
                                          class="badge badge-warning"><i class="fas fa-exclamation-triangle"></i></span>
                                </div>
                                <div class="form-group custom-control custom-switch">
                                    <input id="inputIgnoreOptIn" name="ignore_opt_in" type="checkbox" class="custom-control-input" />
                                    <label class="custom-control-label" for="inputIgnoreOptIn">{$IGNORE_OPT_IN}</label>
                                    <span data-toggle="popover" data-content="{$IGNORE_OPT_IN_INFO}"
                                          class="badge badge-info"><i class="fas fa-question-circle"></i></span>
                                </div>
                                <hr />
                                <div class="card shadow border-left-info" style="margin-bottom: 10px;">
                                    <div class="card-body">
                                        {$EXCLUSION_INCLUSION_INFO}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputExcludeGroups">{$EXCLUDED_GROUPS}</label>
                                    <select class="form-control" name="exclude_groups[]" id="inputExcludeGroups" multiple>
                                        {foreach from=$ALL_GROUPS item=item}
                                            <option value="{$item->id}">{$item->name|escape}</option>
                                        {/foreach}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="inputExcludeUsers">{$EXCLUDED_USERS}</label>
                                    <select class="form-control" name="exclude_users[]" id="inputExcludeUsers" multiple></select>
                                </div>
                                <div class="form-group">
                                    <label for="inputIncludeGroups">{$INCLUDED_GROUPS}</label>
                                    <select class="form-control" name="include_groups[]" id="inputIncludeGroups" multiple>
                                        {foreach from=$ALL_GROUPS item=item}
                                            <option value="{$item->id}">{$item->name|escape}</option>
                                        {/foreach}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="inputIncludeUsers">{$INCLUDED_USERS}</label>
                                    <select class="form-control" name="include_users[]" id="inputIncludeUsers" multiple></select>
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

    <script type="text/javascript">
      function queryData(params) {
        return {
          search: params.term,
          limit: 10
        };
      };

      function processResults(data) {
        return {
          results: data.results.map(({ id, username }) => ({ id, text: username }))
        };
      }

      const ajaxOptions = {
        processResults,
        data: queryData,
        dataType: 'json',
        delay: 350,
        url: '{$USERS_QUERY_URL}'
      };

      $(document).ready(() => {
        $('#inputMessageType').select2({ placeholder: "{$NO_ITEM_SELECTED}" });
        $('#inputExcludeGroups').select2({ placeholder: "{$NO_ITEM_SELECTED}" });
        $('#inputExcludeUsers').select2({
          placeholder: "{$NO_ITEM_SELECTED}",
          ajax: ajaxOptions,
          minimumInputLength: 1
        });
        $('#inputIncludeGroups').select2({ placeholder: "{$NO_ITEM_SELECTED}" });
        $('#inputIncludeUsers').select2({
          placeholder: "{$NO_ITEM_SELECTED}",
          ajax: ajaxOptions,
          minimumInputLength: 1
        });
      });
    </script>

</body>

</html>
