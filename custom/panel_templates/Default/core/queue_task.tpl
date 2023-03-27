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
                        <h1 class="h3 mb-0 text-gray-800">{$TASK}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                            <li class="breadcrumb-item"><a href="{$QUEUE_LINK}">{$QUEUE}</a></li>
                            <li class="breadcrumb-item"><a href="{$QUEUE_STATUS_LINK}">{$QUEUE_STATUS}</a></li>
                            <li class="breadcrumb-item active">{$TASK}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='includes/update.tpl'}

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <a class="btn btn-primary" href="{$QUEUE_STATUS_LINK}">{$BACK}</a>

                            <div class="float-right">
                                {if isset($CANCEL_TASK) || isset($REQUEUE_TASK)}
                                    <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">{$ACTIONS}</button>
                                    <div class="dropdown-menu">
                                        {if isset($CANCEL_TASK)}<a class="dropdown-item" href="#"
                                                                   onclick="showCancelModal()">{$CANCEL_TASK}</a>{/if}
                                        {if isset($REQUEUE_TASK)}<a class="dropdown-item" href="#"
                                                                   onclick="showRequeueModal()">{$REQUEUE_TASK}</a>{/if}
                                    </div>
                                </div>
                                {/if}
                            </div>

                            <hr />

                            <!-- Success and Error Alerts -->
                            {include file='includes/alerts.tpl'}

                            <form action="" method="post">
                                <div class="form-row">
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="task">{$TASK}</label>
                                            <input id="task" type="text" readonly="readonly" value="{$TASK_VALUE}"  class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="name">{$TASK_NAME}</label>
                                            <input id="name" type="text" readonly="readonly" value="{$TASK_NAME_VALUE}"  class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-4">
                                        <div class="form-group">
                                            <label for="module">{$TASK_MODULE}</label>
                                            <input id="module" type="text" readonly="readonly" value="{$TASK_MODULE_VALUE}"  class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="scheduled-at">{$TASK_SCHEDULED_AT}</label>
                                            <input id="scheduled-at" type="text" readonly="readonly" value="{$TASK_SCHEDULED_AT_VALUE}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="scheduled-for">{$TASK_SCHEDULED_FOR}</label>
                                            <input id="scheduled-for" type="text" readonly="readonly" value="{$TASK_SCHEDULED_FOR_VALUE}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="executed-at">{$TASK_EXECUTED_AT}</label>
                                            <input id="executed-at" type="text" readonly="readonly" value="{$TASK_EXECUTED_AT_VALUE}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">{$TASK_STATUS}</label>
                                            <input id="status" type="text" readonly="readonly" value="{$TASK_STATUS_VALUE}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="data">{$TASK_DATA}</label>
                                    <textarea id="data" readonly="readonly" class="form-control" rows="5">{$TASK_DATA_VALUE}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="output">{$TASK_OUTPUT}</label>
                                    <textarea id="output" readonly="readonly" class="form-control" rows="5">{$TASK_OUTPUT_VALUE}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="attempts">{$TASK_ATTEMPTS}</label>
                                    <input id="attempts" type="text" readonly="readonly" value="{$TASK_ATTEMPTS_VALUE}" class="form-control">
                                </div>
                                {if isset($TASK_TRIGGERED_BY)}
                                    <div class="form-group">
                                        <label for="user">{$TASK_TRIGGERED_BY}</label>
                                        <div class="input-group mb-3">
                                            <input id="user" type="text" readonly="readonly" value="{$TASK_USERNAME}" class="form-control">
                                            <div class="input-group-append">
                                                <a class="btn btn-primary" type="button" href="{$TASK_PROFILE}">{$PROFILE}</a>
                                            </div>
                                        </div>
                                    </div>
                                {/if}

                                <div class="form-row">
                                    <div class="col-md">
                                        <div class="form-group">
                                            <label for="entity">{$TASK_ENTITY}</label>
                                            <input id="entity" type="text" readonly="readonly" value="{$TASK_ENTITY_VALUE}"  class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div class="form-group">
                                            <label for="entity-id">{$TASK_ENTITY_ID}</label>
                                            <input id="entity-id" type="text" readonly="readonly" value="{$TASK_ENTITY_ID_VALUE}"  class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="fragment">{$TASK_FRAGMENT}</label>
                                            <input id="fragment" type="text" readonly="readonly" value="{$TASK_FRAGMENT_VALUE}"  class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="fragment-total">{$TASK_FRAGMENT_TOTAL}</label>
                                            <input id="fragment-total" type="text" readonly="readonly" value="{$TASK_FRAGMENT_TOTAL_VALUE}"  class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="fragment-next">{$TASK_FRAGMENT_NEXT}</label>
                                            <input id="fragment-next" type="text" readonly="readonly" value="{$TASK_FRAGMENT_NEXT_VALUE}"  class="form-control">
                                        </div>
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

        {if isset($CANCEL_TASK)}
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
                            {$CONFIRM_CANCEL_TASK}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{$NO}</button>
                            <form action="" method="post">
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <input type="hidden" name="task_action" value="cancel">
                                <input type="submit" class="btn btn-primary" value="{$YES}">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        {/if}

        {if isset($REQUEUE_TASK)}
            <div class="modal fade" id="requeueModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{$ARE_YOU_SURE}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            {$CONFIRM_REQUEUE_TASK}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{$NO}</button>
                            <form action="" method="post">
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <input type="hidden" name="task_action" value="requeue">
                                <input type="submit" class="btn btn-primary" value="{$YES}">
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
        {if isset($CANCEL_TASK)}
        function showCancelModal() {
          $('#cancelModal').modal().show();
        }
        {/if}
        {if isset($REQUEUE_TASK)}
        function showRequeueModal() {
          $('#requeueModal').modal().show();
        }
        {/if}
    </script>

</body>

</html>