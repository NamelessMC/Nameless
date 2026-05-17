<div class="nx-surface overflow-hidden">
  <header class="nx-card__header"><h3 class="nx-card__title">{$REACTIONS_TEXT}</h3></header>
  <div class="p-4">
    <table class="nx-table text-center">
      <thead>
        <tr><th></th><th>{$RECEIVED}</th><th>{$GIVEN}</th></tr>
      </thead>
      <tbody>
        {foreach from=$ALL_REACTIONS item=reaction}
          <tr>
            <td title="{$reaction.name}">{$reaction.html}</td>
            <td class="text-text-primary font-medium tabular-nums">{$reaction.received}</td>
            <td class="text-text-primary font-medium tabular-nums">{$reaction.given}</td>
          </tr>
        {/foreach}
      </tbody>
    </table>
  </div>
  <footer class="nx-card__footer">
    <span class="text-sm">{$REACTION_SCORE}</span>
    <span class="font-display font-semibold text-lg
      {if $REACTION_SCORE_AGGREGATE < 0}text-danger
      {elseif $REACTION_SCORE_AGGREGATE eq 0}text-warning
      {else}text-success{/if}">{$REACTION_SCORE_AGGREGATE}</span>
  </footer>
  {if count($CONTEXT_REACTION_SCORES)}
    <div class="px-4 py-3 border-t border-border-subtle text-xs space-y-1">
      {foreach $CONTEXT_REACTION_SCORES as $context => $score}
        <div class="flex items-center justify-between">
          <span class="text-text-muted">{$context}</span>
          <span class="font-medium {if $score < 0}text-danger{elseif $score eq 0}text-warning{else}text-success{/if}">{$score}</span>
        </div>
      {/foreach}
    </div>
  {/if}
</div>
