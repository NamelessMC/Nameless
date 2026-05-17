{include file='header.tpl'}
{include file='navbar.tpl'}

<h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight mb-6">{$TITLE}</h1>

<div class="grid lg:grid-cols-[18rem_minmax(0,1fr)] gap-6" id="user">
  <aside>{include file='user/navigation.tpl'}</aside>

  <main class="min-w-0 space-y-4">
    <div class="nx-surface p-6">
      <h2 class="font-display text-lg font-semibold mb-4">{$OVERVIEW}</h2>
      <dl class="space-y-3">
        {nocache}
        {foreach from=$USER_DETAILS_VALUES key=name item=value}
          <div class="flex items-start gap-3 py-2 border-b border-border-subtle last:border-0">
            <span class="text-text-muted mt-0.5">{include file='components/icon.tpl' name='chevron-right' size=14}</span>
            <div class="flex-1 min-w-0">
              <dt class="text-text-secondary text-sm font-medium">{$name}</dt>
              <dd class="text-text-primary mt-0.5">{$value}</dd>
            </div>
          </div>
        {/foreach}
        {/nocache}
      </dl>
    </div>

    {if isset($FORUM_GRAPH)}
      <div class="nx-surface p-6">
        <h2 class="font-display text-lg font-semibold mb-4">{$FORUM_GRAPH}</h2>
        <div id="chartWrapper"><canvas id="dataChart" width="100%" height="40"></canvas></div>
      </div>
    {/if}
  </main>
</div>

{include file='footer.tpl'}
