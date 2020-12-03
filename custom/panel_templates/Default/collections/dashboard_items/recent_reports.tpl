<div class="card shadow mb-4">
    <div class="card-header bg-primary py-3">
        <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-exclamation-triangle fa-fw"></i> {$RECENT_REPORTS}</h6>
    </div>
    <div class="card-body">
        {if count($REPORTS)}
            <div id="accordion">
                {assign var=i value=1} {foreach from=$REPORTS item=report}
                    <div class="card" style="margin-bottom: 10px; border-radius: 10px">
                        <div class="card-header" style="border-radius: 10px" id="headingReport{$i}">
                            <h5 class="mb-0">
                                <button class="btn btn-link btn-block btn-accordion" data-toggle="collapse" data-target="#collapseReport{$i}" aria-expanded="true" aria-controls="collapseReport{$i}">
                                    <span style="{$report.reported_style}"><img src="{$report.reported_avatar}" style="max-width:20px;max-height:20px;" class="rounded" alt="{$report.reported_username}" /> {$report.reported_nickname}</span>
                                    {if $report.type eq 0}
                                        <span class="badge badge-info">{$WEBSITE}</span>
                                    {else}
                                        <span class="badge badge-info">{$INGAME}</span>
                                    {/if}
                                </button>
                            </h5>
                            <div id="collapseReport{$i}" class="collapse text-center" aria-labelledby="collapseReport{$i}" data-parent="#accordion">
                                {$CREATED} <span data-toggle="tooltip" data-title="{$report.time_full}">{$report.time}</span><br />
                                {$REPORTED_BY} <span style="{$report.reporter_style}">{$report.reporter_nickname}  <img src="{$report.reporter_avatar}" style="max-width:20px;max-height:20px;" class="rounded" alt="{$report.reporter_username}" /></span><br />
                                {$REASON} {$report.reason}
                                <hr />
                                <a class="btn btn-primary btn-block text-white" href="{$report.url}">{$VIEW}</a>
                            </div>
                        </div>
                    </div>
                {assign var=i value=($i+1)} {/foreach}
            </div>
        {else} {$NO_REPORTS} 
        {/if}
    </div>
</div>