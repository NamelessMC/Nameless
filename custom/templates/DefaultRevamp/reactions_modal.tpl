<div class="ui menu">
    {foreach from=$REACTIONS item=reaction}
        <a class="{if $ACTIVE_TAB == $reaction.id}active {/if}item" data-tab="{$reaction.id}">
            {if $reaction.id != 0}{$reaction.html} &nbsp; {/if}{$reaction.name} ({$reaction.count})
        </a>
    {/foreach}
</div>

{foreach from=$REACTIONS item=reaction}
    <div class="ui bottom attached tab {if $ACTIVE_TAB == $reaction.id}active{/if}" data-tab="{$reaction.id}">
        <div class="ui large selection divided list middle aligned">
            {foreach from=$reaction.users item=user}
                <div class="item">
                    <div class="right floated content center aligned">
                        {if $reaction.id == 0}
                            <span class="ui text">
                                {$user.reaction_html}
                            </span>
                            <br>
                        {/if}
                        <span class="ui text small">
                            {$user.reacted_time}
                        </span>
                    </div>
                    <div class="content" onclick="window.location.href = '{$user.profile}'">
                        <img class="ui avatar image" src="{$user.avatar}" alt="{$user.nickname}">
                        <span style="{$user.group_style}">
                            {$user.nickname} {foreach from=$user.group_html item=$group_html}{$group_html}{/foreach}
                        </span>
                    </div>
                </div>
            {/foreach}
        </div>
    </div>
{/foreach}

<script>
    $('.menu .item')
        .tab()
    ;
</script>
