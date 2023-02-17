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
        <div class="ui three wide column">
            <div class="ui fluid vertical menu pointing">
                <a class="item {if $VIEWING_LIST eq "overview"}active{/if}" href="{$MEMBER_LIST_URL}">
                    <i class="ellipsis horizontal icon"></i>{$OVERVIEW}
                </a>
                {foreach from=$MEMBER_LISTS item=list}
                    <a class="item {if $VIEWING_LIST eq $list->getName()}active{/if}" href="{$list->url()}">
                        <i class="{if $list->getIcon()}{$list->getIcon()}{else}dot circle icon{/if}"></i> {$list->getFriendlyName()}
                    </a>
                {/foreach}
            </div>
            <div class="ui fluid card">
                <div class="content">
                    <h4 class="ui header">Find member</h4>
                    <div class="description">
                        <form action="{$MEMBER_LIST_URL}" method="post">
                            <input type="hidden" name="token" value="{$TOKEN}">
                            <div class="ui fluid icon input">
                                <i class="search icon"></i>
                                <input type="text" name="search" minlength="3" placeholder="Name..." autocomplete="off">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="ui fluid card">
                <div class="content">
                    <h4 class="ui header">New members</h4>
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
            <div class="ui grid three column segment" style="margin-top: 0">
                {foreach from=$MEMBER_LISTS_VIEWING item=list}
                    <div class="ui column">
                        <h3>{$list->getFriendlyName()}</h3>
                        <ul id="member_list_{$list->getName()}" class="ui list large selection" style="margin-left: -10px;">
                        </ul>
                        {if $VIEWING_LIST == "overview"}
                            <a class="fluid ui grey basic button" href="{$list->url()}">{$VIEW_ALL}</a>
                        {/if}
                    </div>
                {/foreach}
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    {foreach from=$MEMBER_LISTS_VIEWING item=list}
    (function () {
        const xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        xhr.open('GET', '{$QUERIES_URL|replace:'{{list}}':$list->getName()}');

        const list = document.getElementById('member_list_{$list->getName()}');
        list.innerHTML = '<div class="ui active centered inline loader"></div>';

        xhr.onload = function() {
            list.innerHTML = '';

            const data = JSON.parse(xhr.responseText);
            if (data.length < 0) {
                return;
            }

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
                nameDiv.style = member.group_style;
                nameDiv.innerText = member.username;
                contentDiv.appendChild(nameDiv);

                {if $VIEWING_LIST != "overview"}
                    const metaDiv = document.createElement('div');
                    metaDiv.classList.add('description');

                    const groupSpan = document.createElement('span');
                    groupSpan.classList.add('ui', 'text', 'small');
                    groupSpan.innerText = member.group;
                    metaDiv.appendChild(groupSpan);

                    metaDiv.appendChild(document.createElement('br'));

                    const metaSpan = document.createElement('span');
                    metaSpan.classList.add('ui', 'text', 'small');
                    const memberMeta = member.metadata;
                    const metaKeys = Object.keys(memberMeta);
                    metaSpan.innerHTML = metaKeys.map(key => key + ': ' + memberMeta[key]).join(' &middot; ');

                    metaDiv.appendChild(metaSpan);
                    contentDiv.appendChild(metaDiv);
                {/if}

                mainDiv.appendChild(contentDiv);

                list.appendChild(mainDiv)
            }
        };

        xhr.send();
    })();
    {/foreach}
</script>

{include file='footer.tpl'}
