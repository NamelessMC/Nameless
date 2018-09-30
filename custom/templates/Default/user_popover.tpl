<div class="container">
    <div class="row align-items-center h-100">
        <div class="col-md-4">
            <a href="{$PROFILE}"><img style="max-width:100%" src="{$AVATAR}" alt="{$USERNAME}" class="rounded-circle" /></a>
        </div>
        <div class="col-md-8">
            <span style="font-size:1.2rem;">
                <a href="{$PROFILE}" style="{$STYLE}">{$NICKNAME}</a>
                <hr style="margin-top:0;margin-bottom:0.5rem" />
                {if count($GROUPS)}
                    {foreach from=$GROUPS item=group}
                        {$group}
                    {/foreach}
                    </span>
                    <br /><br />
                    <div style="text-center">
                        {if isset($REGISTERED)}<small>{$REGISTERED}</small><br />{/if}
                        {if isset($TOPICS) && isset($POSTS)}
                            <div class="row">
                                <div class="col-md-6">
                                    <small>{$TOPICS}</small>
                                </div>
                                <div class="col-md-6">
                                    <small>{$POSTS}</small>
                                </div>
                            </div>
                        {/if}
                    </div>
                {else}
                    <div class="badge badge-secondary">{$GUEST}</div>
                    </span>
                {/if}
        </div>
    </div>
</div>