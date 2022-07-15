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
                        <h1 class="h3 mb-0 text-gray-800">{$DASHBOARD}</h1>
                    </div>

                    <!-- Update Notification -->
                    {include file='includes/update.tpl'}

                    {if isset($DIRECTORY_WARNING)}
                    <div class="alert alert-warning">
                        {$DIRECTORY_WARNING}
                    </div>
                    {/if} {if count($DASHBOARD_STATS)} {assign var="i" value=0}
                    <div class="row">
                        {foreach from=$DASHBOARD_STATS item=stat} {if $i % 4 eq 0}
                    </div>
                    <div class="row">
                        {/if} {$stat->getContent()} {assign var="i" value=$i+1} {/foreach}
                    </div>
                    {/if}

                    <div class="row">
                        <div class="col-md-9">
                            {if count($GRAPHS)}
                            <!-- Area Chart -->
                            <div class="card shadow mb-4 statistics">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header bg-primary py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-white">
                                        <i class="far fa-chart-bar"></i> {$STATISTICS}
                                    </h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area" style="height: 100%">
                                        <canvas id="graphDiv"></canvas>
                                    </div>
                                </div>
                            </div>
                            {/if}
                            {if count($MAIN_ITEMS)} {assign var="i" value=0} {assign var="counter" value=0}
                                <div class="row justify-content-md-center">
                                    {foreach from=$MAIN_ITEMS item=item}
                                        {assign var="width" value=(12*$item->getWidth())|round:1}
                                        {assign var="counter" value=($counter+$width)}

                                        {if $counter > 12} {assign var="counter" value=0}
                                            </div>
                                            <br />
                                            <div class="row justify-content-md-center">
                                        {/if}
                                        <div class="col-md-6 col-lg-{$width}">
                                            {$item->getContent()}
                                        </div>
                                        {assign var="i" value=$i+1}
                                    {/foreach}
                                </div>
                            {/if}
                        </div>

                        <div class="col-md-3">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header bg-primary py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-white"><i class="far fa-newspaper"></i>
                                        {$NAMELESS_NEWS}</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    {if isset($NO_NEWS)}
                                    <div class="alert alert-warning">{$NO_NEWS}</div>
                                    {else} {foreach from=$NEWS item=item name=newsarray}
                                    <a href="#" onclick="confirmLeaveSite('{$item.url}')">{$item.title}</a>
                                    <br /><small>{$item.author} | <span data-toggle="tooltip"
                                            data-title="{$item.date}">{$item.date_friendly}</span></small> {if not
                                    $smarty.foreach.newsarray.last}
                                    <hr />{/if} {/foreach} {/if}
                                </div>
                            </div>

                            {if isset($SERVER_COMPATIBILITY)}
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header bg-primary py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-wrench"></i>
                                        {$SERVER_COMPATIBILITY}</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    {$NAMELESS_VERSION}
                                    <hr />
                                    {foreach from=$COMPAT_SUCCESS item=item}
                                    <i class="fas fa-check-circle text-success"></i> {$item}
                                    <br />
                                    {/foreach}
                                    {if count($COMPAT_WARNINGS)}
                                    <hr />
                                    {foreach from=$COMPAT_WARNINGS item=item}
                                    <i class="fas fa-check-circle text-warning"></i> {$item}
                                    <br />
                                    {/foreach}
                                    {/if}
                                    {if count($COMPAT_ERRORS)}
                                    <hr />
                                    {foreach from=$COMPAT_ERRORS item=item}
                                    <i class="fas fa-times-circle text-danger"></i> {$item}
                                    <br />
                                    {/foreach}
                                    {/if}
                                </div>
                            </div>
                            {/if}

                        </div>

                        <!-- End Row -->
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

        <!-- Confirm leave site modal -->
        <div class="modal fade" id="confirmLeaveSiteModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        {$CONFIRM_LEAVE_SITE}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{$NO}</button>
                        <a href="#" id="leaveSiteA" class="btn btn-primary" target="_blank">{$YES}</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- End Wrapper -->
    </div>

    {include file='scripts.tpl'}

    {if count($GRAPHS)}
    <script type="text/javascript">
        Chart.defaults.global.defaultFontFamily = 'Nunito,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';

        if (currentPanelTheme === "dark") {
            Chart.defaults.global.defaultFontColor = 'rgb(189,189,189)';
        } else {
            Chart.defaults.global.defaultFontColor = '#858796';
        }

        graphs = [
            {foreach from=$GRAPHS item=graph} {
                type: 'line',
                data: {
                    labels: [
                        {foreach from=$graph.keys key=key item=item}
                            '{$item}',
                        {/foreach}
                    ],
                    datasets: [
                        {foreach from=$graph.datasets item=dataset} {
                            fill: false,
                            borderColor: '{$dataset.colour}',
                            label: '{$dataset.label}',
                            yAxisID: '{$dataset.axis}',
                            lineTension: 0.3,
                            backgroundColor: "rgba(78, 115, 223, 0.05)",
                            pointRadius: 3,
                            pointBackgroundColor: "{$dataset.colour}",
                            pointBorderColor: "{$dataset.colour}",
                            pointHoverRadius: 3,
                            pointHoverBackgroundColor: "{$dataset.colour}",
                            pointHoverBorderColor: "{$dataset.colour}",
                            pointHitRadius: 10,
                            pointBorderWidth: 2,
                            data: [
                                {foreach from=$dataset.data item=data name=ds}
                                    {$data}{if not $smarty.foreach.ds.last}, {/if}
                                {/foreach}
                            ]
                        },
                        {/foreach}
                    ]
                },
                options: {
                    scales: {
                        xAxes: [{
                            type: 'time',
                            gridLines: {
                                display: false,
                                drawBorder: false
                            },
                            time: {
                                tooltipFormat: 'MMM D',
                                unit: 'day'
                            }
                        }],
                        yAxes: [
                            {foreach from=$graph.axes key=key item=axis}
                                {
                                    id: '{$key}',
                                    type: 'linear',
                                    position: '{$axis}'
                                },
                            {/foreach}
                        ]
                    },
                    tooltips: {
                        backgroundColor: currentPanelTheme === "dark" ? "#161c25" : "#f8f9fc",
                        bodyFontColor: currentPanelTheme === "dark" ? "rgb(189,189,189)" : "#858796",
                        titleFontColor: currentPanelTheme === "dark" ? "rgb(189,189,189)" : "#6e707e",
                        borderColor: currentPanelTheme !== "dark" ? "#dddfeb" : "#161c25",
                        titleMarginBottom: 10,
                        titleFontSize: 14,
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10
                    }
                }
            },
            {/foreach}
        ];

        function drawChart(i) {
            let canvas = document.getElementById('graphDiv');
            let chart = new Chart(canvas, graphs[i]);
        }

        $(function() {
            drawChart(0);
        });
    </script>
    {/if}

    <script type="text/javascript">
        function confirmLeaveSite(url) {
            $('#leaveSiteURL').html(url);
            $('#leaveSiteA').attr('href', url);
            $('#confirmLeaveSiteModal').modal().show();
        }
    </script>

</body>

</html>
