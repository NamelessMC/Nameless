{include file='header.tpl'}
{include file='navbar.tpl'}

<h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight mb-6">{$TITLE}</h1>

<div class="grid gap-6
            {if count($WIDGETS_LEFT) && count($WIDGETS_RIGHT)}lg:grid-cols-[18rem_minmax(0,1fr)_18rem]
            {elseif count($WIDGETS_LEFT) || count($WIDGETS_RIGHT)}lg:grid-cols-[18rem_minmax(0,1fr)]
            {else}grid-cols-1{/if}">

  {if count($WIDGETS_LEFT)}
    <aside class="space-y-4 order-2 lg:order-1">{foreach from=$WIDGETS_LEFT item=widget}{$widget}{/foreach}</aside>
  {/if}

  <main class="order-1 lg:order-2 min-w-0">
    <div class="nx-surface p-6 sm:p-8 forum_post">{$CONTENT}</div>
  </main>

  {if count($WIDGETS_RIGHT)}
    <aside class="space-y-4 order-3">{foreach from=$WIDGETS_RIGHT item=widget}{$widget}{/foreach}</aside>
  {/if}
</div>

{include file='footer.tpl'}
