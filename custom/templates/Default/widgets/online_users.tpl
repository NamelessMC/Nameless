<div class="card">
    <div class="card-body">
        <h2>{$ONLINE_USERS}</h2>
        {if isset($ONLINE_USERS_LIST)}
            {foreach from=$ONLINE_USERS_LIST name=online_users_arr item=user}
<<<<<<< HEAD
                <a style="{$user.style}" href="{$user.profile}"><img src="{$user.avatar}" alt="{$user.nickname}" class="rounded" style="max-height:20px;max-width:20px;"> {$user.username}</a>
=======
                <a style="{$user.style}" href="{$user.profile}" data-poload="{$USER_INFO_URL}{$user.id}" data-html="true" data-placement="top"><img src="{$user.avatar}" alt="{$user.nickname}" class="rounded" style="max-height:20px;max-width:20px;"> {$user.username}</a>
>>>>>>> upstream/v2
                {if not $smarty.foreach.online_users_arr.last}, {/if}
            {/foreach}
        {else}
            {$NO_USERS_ONLINE}
        {/if}
    </div>
</div>