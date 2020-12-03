<div class="card shadow mb-4">
    <div class="card-header bg-primary py-3">
        <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-gavel fa-fw"></i> {$RECENT_PUNISHMENTS}</h6>
    </div>
    <div class="card-body">
        {if count($PUNISHMENTS)}
            <div id="accordion">
                {assign var=i value=1} {foreach from=$PUNISHMENTS item=punishment}
                    <div class="card" style="margin-bottom: 10px; border-radius: 10px">
                        <div class="card-header" style="border-radius: 10px" id="heading{$i}}">
                            <h5 class="mb-0">
                                <button class="btn btn-link btn-block btn-accordion" data-toggle="collapse" data-target="#collapse{$i}" aria-expanded="true" aria-controls="collapse{$i}">
                                    <span style="{$punishment.punished_style}"><img src="{$punishment.punished_avatar}" style="max-width:20px;max-height:20px;" class="rounded" alt="{$punishment.punished_username}" /> {$punishment.punished_nickname}</span>
                                    {if $punishment.type eq 1}
                                        <span class="badge badge-danger">{$BAN}</span>
                                    {elseif $punishment.type eq 2}
                                        <span class="badge badge-warning">{$WARNING}</span>
                                    {elseif $punishment.type eq 3}
                                        <span class="badge badge-danger">{$IP_BAN}</span>
                                    {/if}
                                    {if $punishment.revoked eq 1}
                                        <span class="badge badge-success">{$REVOKED}</span>
                                    {/if}
                                </button>
                            </h5>
                            <div id="collapse{$i}" class="collapse text-center" aria-labelledby="heading{$i}" data-parent="#accordion">
                                {$CREATED} <span data-toggle="tooltip" data-title="{$punishment.time_full}">{$punishment.time}</span><br />
                                {$STAFF} <span style="{$punishment.staff_style}">{$punishment.staff_nickname} <img src="{$punishment.staff_avatar}" style="max-width:20px;max-height:20px;" class="rounded" alt="{$punishment.staff_username}" /></span><br />
                                {$REASON} {$punishment.reason}
                                <hr />
                                <a class="btn btn-primary btn-block text-white" href="{$punishment.url}">{$VIEW}</a>
                            </div>
                        </div>
                    </div>
                {assign var=i value=($i+1)} {/foreach}
            </div>
        {else} {$NO_PUNISHMENTS}
        {/if}
    </div>
</div>