{include file='header.tpl'}
{include file='navbar.tpl'}

<h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight mb-6 flex items-center gap-3">
  <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-accent-subtle text-accent">
    {include file='components/icon.tpl' name='trophy' size=18}
  </span>
  {$LEADERBOARDS}
</h1>

<div class="grid lg:grid-cols-[18rem_minmax(0,1fr)] gap-6">
  <nav class="nx-surface p-2 space-y-0.5 h-fit lg:sticky lg:top-20">
    {foreach from=$LEADERBOARD_PLACEHOLDERS item=placeholder}
      <a class="leaderboard_tab flex items-center gap-2 px-3 py-2 rounded-md text-sm text-text-secondary hover:text-text-primary hover:bg-bg-elevated transition cursor-pointer"
         name="{$placeholder->safe_name}"
         server_id="{$placeholder->server_id}"
         id="tab-{$placeholder->safe_name}-server-{$placeholder->server_id}"
         onclick="showTable('{$placeholder->safe_name}', '{$placeholder->server_id}');">
        {include file='components/icon.tpl' name='star' size=14}
        {$placeholder->leaderboard_title}
      </a>
    {/foreach}
  </nav>

  <div class="min-w-0">
    {foreach from=$LEADERBOARD_PLACEHOLDERS item=placeholder}
      <div class="leaderboard_table nx-surface p-6" style="display:none" id="table-{$placeholder->safe_name}-server-{$placeholder->server_id}">
        <h3 class="font-display text-lg font-semibold mb-4">{$placeholder->leaderboard_title}</h3>
        <ol class="space-y-1">
          {assign var=rank value=1}
          {foreach from=$LEADERBOARD_PLACEHOLDERS_DATA item=data}
            {if $data->name eq $placeholder->name and $data->server_id eq $placeholder->server_id}
              <li>
                <a href="{$data->profile}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-md hover:bg-bg-elevated transition">
                  <span class="font-mono text-text-muted w-8 text-sm">#{$rank}</span>
                  <span class="nx-avatar nx-avatar--sm"><img src="{$data->avatar}" alt="{$data->username}" loading="lazy" /></span>
                  <span class="flex-1 min-w-0">
                    <span class="block text-text-primary font-medium truncate" style="{$data->style}">{$data->username}</span>
                    <span class="block text-xs text-text-muted truncate" title="{$data->last_updated_full}">{$data->last_updated_string}</span>
                  </span>
                  <span class="font-display text-lg font-semibold text-text-primary tabular-nums">{$data->value}</span>
                </a>
              </li>
              {assign var=rank value=$rank+1}
            {/if}
          {/foreach}
        </ol>
      </div>
    {/foreach}
  </div>
</div>

{include file='footer.tpl'}
