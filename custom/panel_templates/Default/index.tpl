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
                        <h1 class="m-0 text-dark">{$DASHBOARD}</h1>
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

                {if isset($DIRECTORY_WARNING)}
                    <div class="alert alert-warning">
                        {$DIRECTORY_WARNING}
                    </div>
                {/if}

                {if count($DASHBOARD_STATS)}
                    {assign var="i" value=0}
                    <div class="row">
                    {foreach from=$DASHBOARD_STATS item=stat}
                        {if $i % 4 eq 0}
                            </div>
                            <div class="row">
                        {/if}
                        <div class="col">{$stat->getContent()}</div>
                        {assign var="i" value=$i+1}
                    {/foreach}
                    </div>
                {/if}

                <div class="row">
                    <div class="col-md-9">
                        {if count($GRAPHS)}
                            <div class="card">
                                <div class="card-header">
                                    <i class="far fa-chart-bar"></i> {$STATISTICS}

                                    <div class="card-tools">
                                        <div class="dropdown float-md-right">
                                            <a style="color: #000;" class="btn btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                {$GRAPHS|@key}
                                            </a>

                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            {assign var="i" value=0}
                                            {foreach from=$GRAPHS key=key item=graph}
                                                <a class="dropdown-item" href="#" onclick="drawChart({$i})">{$key}</a>
                                                {assign var="i" value=$i+1}
                                            {/foreach}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <canvas id="graphDiv" style="height: 400px;"></canvas>
                                </div>
                            </div>
                        {/if}

                        {if count($MAIN_ITEMS)}
                            {assign var="i" value=0}
                            {assign var="counter" value=0}
                            <div class="row justify-content-md-center">
                            {foreach from=$MAIN_ITEMS item=item}
                                {assign var="width" value=(12*$item->getWidth())|round:1}
                                {assign var="counter" value=($counter+$width)}
                                {if $counter > 12}
                                    {assign var="counter" value=0}
                                    </div><br />
                                    <div class="row justify-content-md-center">
                                {/if}
                                <div class="col-md-{$width}">{$item->getContent()}</div>
                                {assign var="i" value=$i+1}
                            {/foreach}
                            </div>
                        {/if}
                    </div>

                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header">
                                <i class="far fa-newspaper"></i> {$NAMELESS_NEWS}
                            </div>
                            <div class="card-body">
                                {if isset($NO_NEWS)}
                                    <div class="alert alert-warning">{$NO_NEWS}</div>
                                {else}
                                    {foreach from=$NEWS item=item name=newsarray}
                                        <a href="#" onclick="confirmLeaveSite('{$item.url}')">{$item.title}</a>
                                        <br /><small>{$item.author} | <span data-toggle="tooltip" data-title="{$item.date}">{$item.date_friendly}</span></small>
                                        {if not $smarty.foreach.newsarray.last}<hr />{/if}
                                    {/foreach}
                                {/if}
                            </div>
                        </div>

                        {if isset($SERVER_COMPATIBILITY)}
                            <div class="card">
                                <div class="card-header">
                                    <i class="fas fa-wrench"></i>
                                    {$SERVER_COMPATIBILITY}
                                </div>
                                <div class="card-body">
                                    {$NAMELESS_VERSION}
                                    <hr />
                                    {foreach from=$COMPAT_SUCCESS item=item}
                                        {$item} <i class="fas fa-check-circle text-success"></i><br />
                                    {/foreach}
                                    {if count($COMPAT_ERRORS)}
                                        <hr />
                                        {foreach from=$COMPAT_ERRORS item=item}
                                            {$item} <i class="fas fa-times-circle text-danger"></i><br />
                                        {/foreach}
                                    {/if}
                                </div>
                            </div>
                        {/if}
                    </div>
                </div>

                <!-- Spacing -->
                <div style="height:1rem;"></div>

            </div>
        </section>
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

    {include file='footer.tpl'}

</div>
<!-- ./wrapper -->

{include file='scripts.tpl'}

{if count($GRAPHS)}
    <script type="text/javascript">
        let graphs = [
        {foreach from=$GRAPHS item=graph}
            {
                type: 'line',
                data: {
                    labels: [{foreach from=$graph.keys key=key item=item}'{$item}',{/foreach}],
                    datasets: [
                        {foreach from=$graph.datasets item=dataset}
                        {
                            fill: false,
                            borderColor: '{$dataset.colour}',
                            label: '{$dataset.label}',
                            yAxisID: '{$dataset.axis}',
                            data: [ {foreach from=$dataset.data item=data name=ds} {$data}{if not $smarty.foreach.ds.last}, {/if}{/foreach} ]
                        },
                        {/foreach}
                    ]
                },
                options: {
                    scales: {
                        xAxes: [{
                            type: 'time',
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
                    }
                }
            },
        {/foreach}
        ];

        function drawChart(i){
            let canvas = document.getElementById('graphDiv');

            let chart = new Chart(canvas, graphs[i]);
        }

        $(function(){
            drawChart(0);
        });
    </script>
{/if}

<script type="text/javascript">
    function confirmLeaveSite(url){
        $('#leaveSiteURL').html(url);
        $('#leaveSiteA').attr('href', url);
        $('#confirmLeaveSiteModal').modal().show();
    }
</script>

</body>
</html>
