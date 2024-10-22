{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
    {$MEMBERS}
</h2>

<br />

{if isset($ERROR)}
<div class="ui error icon message">
    <i class="x icon"></i>
    <div class="content">
        <div class="header">{$ERROR_TITLE}</div>
        {$ERROR}
    </div>
</div>
{/if}

<div class="ui stackable equal width grid">
    <div class="ui centered row">
        <div class="ui four wide column">
            <div class="ui fluid vertical menu pointing">
                <a class="item {if $VIEWING_LIST eq "overview"}active{/if}" href="{$MEMBER_LIST_URL}">
                    <i class="ellipsis horizontal icon"></i>{$OVERVIEW}
                </a>
                {foreach from=$SIDEBAR_MEMBER_LISTS item=list}
                    <a class="item {if $VIEWING_LIST eq $list->getName()}active{/if}" href="{$list->url()}">
                        <i class="{if $list->getIcon()}{$list->getIcon()}{else}dot circle icon{/if}"></i> {$list->getFriendlyName()}
                    </a>
                {/foreach}
            </div>
            <div class="ui fluid card">
                <div class="content">
                    <h4 class="ui header">{$FIND_MEMBER}</h4>
                    <div class="description">
                        <div class="ui search">
                            <div class="ui icon fluid input">
                                <input class="prompt" type="text" minlength="2" required placeholder="{$NAME}" autocomplete="off">
                                <i class="search icon"></i>
                            </div>
                            <div class="results"></div>
                        </div>
                    </div>
                </div>
            </div>
            {if $GROUPS|count}
                <div class="ui fluid card">
                    <div class="content">
                        <h4 class="ui header">{$VIEW_GROUP}</h4>
                        <div class="description">
                            <select class="ui selection fluid dropdown" onchange="viewGroup(this)">
                                <option value="">{$GROUP}</option>
                                {foreach from=$GROUPS item=group}
                                    <option value="{$group.id}" {if $VIEWING_GROUP.id == $group.id} selected {/if}>{$group.name}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                </div>
            {/if}
            <div class="ui fluid card">
                <div class="content">
                    <h4 class="ui header">{$NEW_MEMBERS}</h4>
                    <div class="description">
                        <div class="ui four column grid" id="new-members-grid">
                            {foreach from=$NEW_MEMBERS_VALUE item=member}
                                <div class="column">
                                    <a href="{$member->getProfileUrl()}" data-toggle="popup" data-poload="{$USER_INFO_URL}{$member->data()->id}">
                                        <img class="ui circular image" src="{$member->getAvatar()}" alt="{$member->getDisplayname()}">
                                    </a>
                                </div>
                            {/foreach}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ui column">
            {if $VIEWING_LIST == "group" || $MEMBER_LISTS_VIEWING|count}
                <div class="ui stackable equal width left aligned three column grid segment" style="margin-top: 0">
                    {if $VIEWING_LIST == "group"}
                        <div class="ui column">
                            <h3>{$VIEWING_GROUP.name}</h3>
                            <div>
                                <ul id="member_list_group_{$VIEWING_GROUP.id}" class="ui list large selection" style="margin-left: -10px;">
                                </ul>
                                {$PAGINATION}
                            </div>
                        </div>
                    {else}
                        {foreach from=$MEMBER_LISTS_VIEWING item=list}
                            <div class="ui column">
                                <h3>{$list->getFriendlyName()}</h3>
                                <div>
                                    <ul id="member_list_{$list->getName()}" class="ui list large selection" style="margin-left: -10px;">
                                    </ul>
                                    {if $VIEWING_LIST == "overview"}
                                        <a class="fluid ui grey basic button" href="{$list->url()}">{$VIEW_ALL}</a>
                                    {else}
                                        {$PAGINATION}
                                    {/if}
                                </div>
                            </div>
                        {/foreach}
                    {/if}
                </div>
            {else}
                <div class="ui orange message">{$NO_OVERVIEW_LISTS_ENABLED}</div>
            {/if}
        </div>
    </div>
</div>

<script type="text/javascript">
    const viewGroup = (e) => {
        window.location.href = '{$VIEW_GROUP_URL}' + e.value;
    }

    const renderList = (name) => {
        const list = document.getElementById('member_list_' + name);
        list.innerHTML = '<div class="ui active centered inline loader"></div>';

        fetch(
            '{$QUERIES_URL}'
                .replace({literal}'{{list}}'{/literal}, name)
                .replace({literal}'{{page}}'{/literal}, new URLSearchParams(window.location.search).get('p') ?? 1)
        )
            .then(async response => {
                const data = await response.json();
                if (data.length === 0) {
                    list.parentElement.innerHTML = '<div class="ui orange message">{$NO_MEMBERS_FOUND}</div>';
                    return;
                }

                list.innerHTML = '';

                for (const member of data) {
                    const mainDiv = document.createElement('div');
                    mainDiv.classList.add('item');
                    mainDiv.onclick = () => window.location.href = member.profile_url;

                    const countDiv = document.createElement('div');
                    countDiv.classList.add('right', 'floated', 'content');

                    if (member.count !== null) {
                        const countHeader = document.createElement('h3');
                        countHeader.classList.add('ui', 'header');
                        countHeader.innerText = member.count;
                        countDiv.appendChild(countHeader);
                        mainDiv.appendChild(countDiv);
                    }

                    const contentDiv = document.createElement('div');
                    contentDiv.classList.add('middle', 'aligned', 'content');
                    contentDiv.style.whiteSpace = 'nowrap';
                    contentDiv.style.overflow = 'hidden';
                    contentDiv.style.textOverflow = 'ellipsis';

                    const avatarDiv = document.createElement('img');
                    avatarDiv.classList.add('ui', 'avatar', 'image');
                    avatarDiv.setAttribute('src', member.avatar_url);
                    {if $VIEWING_LIST == "overview"}
                        contentDiv.appendChild(avatarDiv);
                    {else}
                        mainDiv.appendChild(avatarDiv);
                    {/if}

                    const nameDiv = document.createElement('span');
                    nameDiv.style = member.group_style?.replace('&#039;', "'")?.replace('&quot;', '"');
                    {if $VIEWING_LIST != "overview"}
                        nameDiv.innerHTML = member.username + '&nbsp;' + member.group_html.join('');
                    {else}
                        nameDiv.innerText = member.username;
                    {/if}
                    contentDiv.appendChild(nameDiv);

                    {if $VIEWING_LIST != "overview"}
                        const metaDiv = document.createElement('div');
                        metaDiv.classList.add('description');

                        const metaSpan = document.createElement('span');
                        metaSpan.classList.add('ui', 'text', 'small');
                        const memberMeta = member.metadata;
                        metaSpan.innerHTML = Object.keys(memberMeta).map(key => key + ': ' + memberMeta[key]).join(' &middot; ');

                        metaDiv.appendChild(metaSpan);
                        contentDiv.appendChild(metaDiv);
                    {/if}
                    mainDiv.appendChild(contentDiv);
                    list.appendChild(mainDiv)
                }
        });
    }

    window.onload = () => {
        {if $VIEWING_LIST == "group"}
            renderList('group_{$VIEWING_GROUP.id}');
        {else}
            {foreach from=$MEMBER_LISTS_VIEWING item=list}
                renderList('{$list->getName()}');
            {/foreach}
        {/if}

        $('.ui.search')
            .search({
                minCharacters: 2,
                maxResults: 5,
                selectFirstResult: true,
                fields: {
                    title: 'username',
                    description: 'nickname',
                    image: 'avatar_url',
                    url: 'profile_url',
                },
                apiSettings: {
                    url: '{$SEARCH_URL}&search={literal}{query}{/literal}&limit=5'
                },
                error: {
                    noResultsHeader: "{$NO_RESULTS_HEADER}",
                    noResults: "{$NO_RESULTS_TEXT}",
                }
            })
        ;
    }
</script>

{include file='footer.tpl'}
