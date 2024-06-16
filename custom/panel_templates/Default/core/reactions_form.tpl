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
                        <h1 class="h3 mb-0 text-gray-800">{$REACTIONS}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                            <li class="breadcrumb-item active">{$REACTIONS}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='includes/update.tpl'}

                    <div class="card shadow mb-4">
                        <div class="card-body">

                            <div class="row" style="margin-bottom: 10px;">
                                <div class="col-md-9">
                                    <h5 style="margin-top: 7px; margin-bottom: 7px;">{($EDITING) ? $EDITING_REACTION : $CREATING_REACTION}</h5>
                                </div>
                                <div class="col-md-3">
                                    <span class="float-md-right">
                                        <a href="#" class="btn btn-warning" onclick="showCancelModal()">{$CANCEL}</a>
                                        {if $EDITING}
                                            <a href="#" class="btn btn-danger" onclick="showDeleteModal()">{$DELETE}</a>
                                        {/if}
                                    </span>
                                </div>
                            </div>
                            <hr />

                            <!-- Success and Error Alerts -->
                            {include file='includes/alerts.tpl'}

                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="InputReactionName">{$NAME}</label>
                                    <input type="text" class="form-control" name="name" id="InputReactionName"
                                        placeholder="{$NAME}" value="{$NAME_VALUE}">
                                </div>

                                <div class="form-group">
                                    <label for="InputReactionHTML">{$HTML}</label>
                                    <input type="text" class="form-control" name="html" id="InputReactionHTML"
                                        placeholder="{$HTML}" value="{$HTML_VALUE}">
                                </div>

                                <div class="form-group row">
                                    <div class="col">
                                        <label for="InputReactionType">{$TYPE}</label>
                                        <select name="type" class="form-control" id="InputReactionType">
                                            <option value="2" {if $TYPE_VALUE eq 2} selected{/if}>{$POSITIVE}</option>
                                            <option value="1" {if $TYPE_VALUE eq 1} selected{/if}>{$NEUTRAL}</option>
                                            <option value="0" {if $TYPE_VALUE eq 0} selected{/if}>{$NEGATIVE}</option>
                                            <option value="3" {if $TYPE_VALUE eq 3} selected{/if}>{$CUSTOM_SCORE}</option>
                                        </select>
                                    </div>
                                    <div class="col" id="custom-score">
                                        <label for="InputReactionCustomScore">{$CUSTOM_SCORE}</label>
                                        <input type="number" class="form-control" name="custom_score" id="InputReactionCustomScore"
                                               placeholder="0" value="{$CUSTOM_SCORE_VALUE}">
                                    </div>
                                </div>

                                <div class="form-group custom-control custom-switch">
                                    <input id="InputEnabled" type="checkbox" name="enabled" class="custom-control-input"
                                        {if $ENABLED_VALUE eq 1} checked{/if} />
                                    <label class="custom-control-label" for="InputEnabled">
                                        {$ENABLED}
                                    </label>
                                </div>

                                <div class="form-group">
                                    <input type="hidden" name="token" value="{$TOKEN}">
                                    <input type="submit" value="{$SUBMIT}" class="btn btn-primary">
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

        <!-- Cancel modal -->
        <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{$ARE_YOU_SURE}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {$CONFIRM_CANCEL}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{$NO}</button>
                        <a href="{$CANCEL_LINK}" class="btn btn-primary">{$YES}</a>
                    </div>
                </div>
            </div>
        </div>

        {if $EDITING}
            <!-- Delete modal -->
            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{$ARE_YOU_SURE}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            {$CONFIRM_DELETE}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{$NO}</button>
                            <form action="{$DELETE_LINK}" method="post" style="display: inline">
                                <input type="hidden" name="token" value="{$TOKEN}" />
                                <input type="submit" class="btn btn-primary" value="{$YES}" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        {/if}

        <!-- End Wrapper -->
    </div>

    {include file='scripts.tpl'}


    <script type="text/javascript">
        function showCancelModal() {
            $('#cancelModal').modal().show();
        }

        {if $EDITING}
            function showDeleteModal() {
                $('#deleteModal').modal().show();
            }
        {/if}

        document.getElementById('InputReactionType').addEventListener('change', (e) => {
            toggleCustomScoreField(e.target.value);
        });

        const toggleCustomScoreField = (type) => {
            const enabled = type == '3';
            const div = document.getElementById('custom-score');
            if (enabled) {
                div.style.display = 'block';
            } else {
                div.style.display = 'none';
            }
            document.getElementsByName('custom_score')[0].required = enabled;
        }

        toggleCustomScoreField({$TYPE_VALUE});
    </script>

</body>

</html>
