{foreach from=$REACTIONS item=reaction}
    {$reaction.html} {$reaction.name} x <strong>{$reaction.count}:</strong>
    <div class="ui middle aligned small list">
        {foreach from=$reaction.users item=user}
            <div class="item">
                <img class="ui avatar image" src="{$user.avatar}">
                <div class="content">
                    <a class="header" href="{$user.profile}">{$user.nickname}</a>
                </div>
            </div>
        {/foreach}
    </div>
{/foreach}
