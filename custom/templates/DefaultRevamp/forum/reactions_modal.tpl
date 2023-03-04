<div class="ui pointing menu">
    {foreach from=$REACTIONS item=reaction}
        <a class="{if $ACTIVE_TAB == $reaction.id}active{/if} item" data-tab="{$reaction.id}">
            {$reaction.html} &nbsp; {$reaction.name} ({$reaction.count})
        </a>
    {/foreach}
</div>

{foreach from=$REACTIONS item=reaction}
    <div class="ui bottom attached tab {if $ACTIVE_TAB == $reaction.id}active{/if}" data-tab="{$reaction.id}">
        <div class="ui large relaxed selection celled list">
            {foreach from=$reaction.users item=user}
                <div class="item">
                    {if $reaction.id == 0}
                        <div class="right floated content center aligned">
                            <span class="ui text large">
                                {$user.reaction_html}
                            </span>
                            <br>
                            <span class="ui text small">
                                {$user.reacted_time}
                            </span>
                        </div>
                    {else}
                        <div class="right floated content">
                            {$user.reacted_time}
                        </div>
                    {/if}
                    <div class="content">
                        <img class="ui avatar image" src="{$user.avatar}" alt="{$user.nickname}">
                        <a href="{$user.profile}">{$user.nickname}</a>
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
