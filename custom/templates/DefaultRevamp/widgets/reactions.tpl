<div class="ui fluid card">
    <div class="content">
        <h4 class="ui header">{$REACTIONS_TEXT}</h4>
        <table class="ui table center aligned">
            <thead>
                <tr>
                    <th></th>
                    <th>{$RECEIVED}</th>
                    <th>{$GIVEN}</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$ALL_REACTIONS item=reaction}
                    <tr>
                        <td data-toggle="tooltip" data-content="{$reaction.name}">{$reaction.html}</td>
                        <td>{$reaction.received}</td>
                        <td>{$reaction.given}</td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
    <div class="extra content">
        {$REACTION_SCORE}: <span class="ui {if $REACTION_SCORE_AGGREGATE < 0}red{elseif $REACTION_SCORE_AGGREGATE eq 0}orange{else}green{/if} text">{$REACTION_SCORE_AGGREGATE}</span>
    </div>
    {if count($CONTEXT_REACTION_SCORES)}
        <div class="extra content">
            {foreach $CONTEXT_REACTION_SCORES as $context => $score}
                {$context}: <span class="ui {if $score < 0}red{elseif $score eq 0}orange{else}green{/if} text">{$score}</span>
                <br>
            {/foreach}
        </div>
    {/if}
</div>
