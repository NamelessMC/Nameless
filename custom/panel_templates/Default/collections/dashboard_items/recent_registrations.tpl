<div class="card shadow mb-4">
    <div class="card-header bg-primary py-3">
        <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-user fa-fw"></i> {$RECENT_REGISTRATIONS}</h6>
    </div>
    <div class="card-body">
        {if count($REGISTRATIONS)}
        <div id="accordion">
            {assign var=i value=1} {foreach from=$REGISTRATIONS item=registration}
            <div class="card" style="margin-bottom: 10px; border-radius: 10px">
                <div class="card-header" style="border-radius: 10px" id="headingRegistration{$i}">
                    <h5 class="mb-0">
                        <button class="btn btn-link btn-block btn-accordion" data-toggle="collapse" data-target="#collapseRegistration{$i}" aria-expanded="true" aria-controls="collapseRegistration{$i}">
                                    <span style="{$registration.style}"><img src="{$registration.avatar}" style="max-width:20px;max-height:20px;" class="rounded" alt="{$registration.username}" /> {$registration.nickname}</span>
                                    {$registration.groups[0]}
                        </button>
                    </h5>
                    <div id="collapseRegistration{$i}" class="collapse text-center" aria-labelledby="headingRegistration{$i}" data-parent="#accordion">
                        {$REGISTERED} <span data-toggle="tooltip" data-title="{$registration.time_full}">{$registration.time}</span><br />
                        <hr />
                        <a class="btn btn-primary btn-block text-white" href="{$registration.url}">{$VIEW}</a>
                    </div>
                </div>
            </div>
            {assign var=i value=($i+1)} {/foreach}
        </div>
        {/if}
    </div>
</div>