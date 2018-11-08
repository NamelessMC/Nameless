<div class="card h-100">
    <div class="card-header">
        <i class="fas fa-user fa-fw"></i> {$RECENT_REGISTRATIONS}
    </div>
    <div class="card-body">
        {if count($REGISTRATIONS)}
            <div id="accordion">
                {assign var=i value=1}
                {foreach from=$REGISTRATIONS item=registration}
                    <div class="card" style="margin-bottom:0!important">
                        <div class="card-header" id="headingRegistration{$i}">
                            <h5 class="mb-0">
                                <button class="btn btn-link btn-block" data-toggle="collapse" data-target="#collapseRegistration{$i}" aria-expanded="true" aria-controls="collapseRegistration{$i}">
                                    <span style="{$registration.style}"><img src="{$registration.avatar}" style="max-width:20px;max-height:20px;" class="rounded" alt="{$registration.username}" /> {$registration.nickname}</span>
                                    {$registration.groups[0]}
                                </button>
                            </h5>
                        </div>

                        <div id="collapseRegistration{$i}" class="collapse" aria-labelledby="headingRegistration{$i}" data-parent="#accordion">
                            <div class="card-body">
                                {$REGISTERED} <span data-toggle="tooltip" data-title="{$registration.time_full}">{$registration.time}</span><br />
                                <hr />
                                <a class="btn btn-primary btn-block text-white" href="{$registration.url}">{$VIEW}</a>
                            </div>
                        </div>
                    </div>
                    {assign var=i value=($i+1)}
                {/foreach}
            </div>
        {/if}
    </div>
</div>