{include file='header.tpl'}
{include file='navbar.tpl'}

<h1 class="font-display text-2xl sm:text-3xl font-bold tracking-tight mb-6 flex items-center gap-3">
  <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-accent-subtle text-accent">{include file='components/icon.tpl' name='users' size=18}</span>
  {$MEMBERS}
</h1>

{if isset($ERROR)}
  <div class="nx-alert nx-alert--danger mb-4">
    {include file='components/icon.tpl' name='alert' size=18 class="nx-alert__icon"}
    <div><div class="nx-alert__title">{$ERROR_TITLE}</div><div class="nx-alert__body">{$ERROR}</div></div>
  </div>
{/if}

<div class="grid lg:grid-cols-[18rem_minmax(0,1fr)] gap-6">
  <aside class="space-y-4">
    {* List navigation *}
    <nav class="nx-surface p-2 space-y-0.5">
      <a class="flex items-center gap-2 px-3 py-2 rounded-md text-sm transition
                {if $VIEWING_LIST eq "overview"}bg-accent-subtle text-text-primary{else}text-text-secondary hover:text-text-primary hover:bg-bg-elevated{/if}"
         href="{$MEMBER_LIST_URL}">
        {include file='components/icon.tpl' name='grid' size=14}{$OVERVIEW}
      </a>
      {foreach from=$SIDEBAR_MEMBER_LISTS item=list}
        <a class="flex items-center gap-2 px-3 py-2 rounded-md text-sm transition
                  {if $VIEWING_LIST eq $list->getName()}bg-accent-subtle text-text-primary{else}text-text-secondary hover:text-text-primary hover:bg-bg-elevated{/if}"
           href="{$list->url()}">
          <i class="{if $list->getIcon()}{$list->getIcon()}{else}icon{/if}"></i> {$list->getFriendlyName()}
        </a>
      {/foreach}
    </nav>

    {* Find member *}
    <div class="nx-surface p-4">
      <h3 class="font-medium text-text-primary mb-3 text-sm">{$FIND_MEMBER}</h3>
      <div class="ui search">
        <div class="nx-input-group">
          <span class="nx-input-group__icon">{include file='components/icon.tpl' name='search' size=14}</span>
          <input class="prompt nx-input" type="text" minlength="2" required placeholder="{$NAME}" autocomplete="off">
        </div>
        <div class="results"></div>
      </div>
    </div>

    {if $GROUPS|count}
      <div class="nx-surface p-4">
        <h3 class="font-medium text-text-primary mb-3 text-sm">{$VIEW_GROUP}</h3>
        <select class="nx-select" onchange="viewGroup(this)">
          <option value="">{$GROUP}</option>
          {foreach from=$GROUPS item=group}
            <option value="{$group.id}" {if $VIEWING_GROUP.id == $group.id}selected{/if}>{$group.name}</option>
          {/foreach}
        </select>
      </div>
    {/if}

    <div class="nx-surface p-4">
      <h3 class="font-medium text-text-primary mb-3 text-sm">{$NEW_MEMBERS}</h3>
      <div class="grid grid-cols-4 gap-2" id="new-members-grid">
        {foreach from=$NEW_MEMBERS_VALUE item=member}
          <a href="{$member->getProfileUrl()}" data-poload="{$USER_INFO_URL}{$member->data()->id}"
             class="nx-avatar block">
            <img src="{$member->getAvatar()}" alt="{$member->getDisplayname()}" loading="lazy" />
          </a>
        {/foreach}
      </div>
    </div>
  </aside>

  <main class="min-w-0">
    {if $VIEWING_LIST == "group" || $MEMBER_LISTS_VIEWING|count}
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        {if $VIEWING_LIST == "group"}
          <div class="nx-surface p-5 col-span-full">
            <h3 class="font-display text-lg font-semibold mb-3">{$VIEWING_GROUP.name}</h3>
            <ul id="member_list_group_{$VIEWING_GROUP.id}" class="space-y-1"></ul>
            <div class="mt-3">{$PAGINATION}</div>
          </div>
        {else}
          {foreach from=$MEMBER_LISTS_VIEWING item=list}
            <div class="nx-surface p-5">
              <h3 class="font-display text-lg font-semibold mb-3">{$list->getFriendlyName()}</h3>
              <ul id="member_list_{$list->getName()}" class="space-y-1"></ul>
              {if $VIEWING_LIST == "overview"}
                <a class="nx-btn nx-btn--outline nx-btn--block mt-3" href="{$list->url()}">{$VIEW_ALL}</a>
              {else}
                <div class="mt-3">{$PAGINATION}</div>
              {/if}
            </div>
          {/foreach}
        {/if}
      </div>
    {else}
      <div class="nx-alert nx-alert--warning">
        {include file='components/icon.tpl' name='info' size=18 class="nx-alert__icon"}
        <div class="nx-alert__body">{$NO_OVERVIEW_LISTS_ENABLED}</div>
      </div>
    {/if}
  </main>
</div>

<script>
  const viewGroup = (e) => { window.location.href = '{$VIEW_GROUP_URL}' + e.value; };

  const renderList = (name) => {
    const list = document.getElementById('member_list_' + name);
    list.innerHTML = '<div class="flex justify-center py-4"><span class="nx-spinner inline-block"></span></div>';

    fetch(
      '{$QUERIES_URL}'
        .replace({literal}'{{list}}'{/literal}, name)
        .replace({literal}'{{page}}'{/literal}, new URLSearchParams(window.location.search).get('p') ?? 1)
    ).then(async response => {
      const data = await response.json();
      if (data.length === 0) {
        list.parentElement.innerHTML = '<div class="nx-alert nx-alert--warning"><div class="nx-alert__body">{$NO_MEMBERS_FOUND}</div></div>';
        return;
      }
      list.innerHTML = '';
      for (const member of data) {
        const li = document.createElement('li');
        li.className = 'flex items-center gap-3 px-2 py-2 rounded-md hover:bg-bg-elevated cursor-pointer transition';
        li.onclick = () => window.location.href = member.profile_url;

        const avatar = document.createElement('img');
        avatar.src = member.avatar_url;
        avatar.className = 'w-8 h-8 rounded-full object-cover flex-shrink-0';
        li.appendChild(avatar);

        const nameWrap = document.createElement('div');
        nameWrap.className = 'flex-1 min-w-0';
        const nameDiv = document.createElement('span');
        nameDiv.className = 'block text-sm text-text-primary truncate';
        nameDiv.style = member.group_style?.replace('&#039;', "'")?.replace('&quot;', '"');
        {if $VIEWING_LIST != "overview"}
          nameDiv.innerHTML = member.username + '&nbsp;' + member.group_html.join('');
          const meta = document.createElement('div');
          meta.className = 'text-xs text-text-muted';
          const memberMeta = member.metadata;
          meta.innerHTML = Object.keys(memberMeta).map(key => key + ': ' + memberMeta[key]).join(' &middot; ');
          nameWrap.appendChild(nameDiv);
          nameWrap.appendChild(meta);
        {else}
          nameDiv.innerText = member.username;
          nameWrap.appendChild(nameDiv);
        {/if}
        li.appendChild(nameWrap);

        if (member.count !== null) {
          const count = document.createElement('span');
          count.className = 'font-display text-base font-semibold text-text-primary tabular-nums';
          count.innerText = member.count;
          li.appendChild(count);
        }
        list.appendChild(li);
      }
    });
  };

  window.onload = () => {
    {if $VIEWING_LIST == "group"}
      renderList('group_{$VIEWING_GROUP.id}');
    {else}
      {foreach from=$MEMBER_LISTS_VIEWING item=list}renderList('{$list->getName()}');{/foreach}
    {/if}

    $('.ui.search').search({
      minCharacters: 2,
      maxResults: 5,
      selectFirstResult: true,
      fields: { title: 'username', description: 'nickname', image: 'avatar_url', url: 'profile_url' },
      apiSettings: { url: '{$SEARCH_URL}&search={literal}{query}{/literal}&limit=5' },
      error: { noResultsHeader: "{$NO_RESULTS_HEADER}", noResults: "{$NO_RESULTS_TEXT}" }
    });
  };
</script>

{include file='footer.tpl'}
