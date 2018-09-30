<div class="card">
    <div class="card-body">
        <h2>{$ONLINE_STAFF}</h2>
        {if isset($ONLINE_STAFF_LIST)}
            {foreach from=$ONLINE_STAFF_LIST name=online_staff_arr item=user}
                <a style="{$user.style}" href="{$user.profile}" data-poload="{$USER_INFO_URL}{$user.id}" data-html="true" data-placement="top"><img src="{$user.avatar}" alt="{$user.nickname}" class="rounded" style="max-height:20px;max-width:20px;"> {$user.username}</a>
                {if not $smarty.foreach.online_staff_arr.last}, {/if}
            {/foreach}
        {else}
            {$NO_STAFF_ONLINE}
        {/if}
    </div>
</div>