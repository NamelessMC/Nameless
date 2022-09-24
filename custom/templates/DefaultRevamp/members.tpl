{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
    {$MEMBERS}
</h2>

<br />

<div class="ui stackable equal width grid">
    <div class="ui centered row">
        <div class="ui three wide column">
            <div class="ui vertical menu">
                <a class="item {if $VIEWING_LIST eq "overview"}active{/if}" href="{$MEMBER_LIST_URL}">
                    <i class="ellipsis horizontal icon"></i>{$OVERVIEW}
                </a>
                {foreach from=$MEMBER_LISTS item=list}
                    <a class="item {if $VIEWING_LIST eq $list->getName()}active{/if}" href="{$list->url()}">
                        <i class="{if $list->getIcon()}{$list->getIcon()}{else}dot circle icon{/if}"></i> {$list->getFriendlyName()}
                    </a>
                {/foreach}
            </div>
        </div>
        <div class="ui thirteen wide column">
            <div class="ui stackable equal width left aligned grid segment" style="margin-top: 0">
                {foreach from=$MEMBER_LISTS_VIEWING item=list}
                    <div class="ui column">
                        <h3>{$list->getFriendlyName()}</h3>
                        <ul id="member_list_{$list->getName()}" class="ui list large selection" style="margin-left: -10px;">
                        </ul>
                        {if $VIEWING_LIST eq "overview"}
                            <a class="fluid ui grey basic button" href="{$list->url()}">View all</a>
                        {/if}
                    </div>
                {/foreach}
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    {foreach from=$MEMBER_LISTS item=list}
    (function () {
        const xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        xhr.open('GET', '{$QUERIES_URL|replace:'{{list}}':$list->getName()}');

        xhr.onload = function() {
            const list = document.getElementById('member_list_{$list->getName()}');

            const data = JSON.parse(xhr.responseText);
            if (data.length < 0) {
                return;
            }

            for (const member of data) {
                const mainDiv = document.createElement('div');
                mainDiv.classList.add('item');

                const countDiv = document.createElement('div');
                countDiv.classList.add('right', 'floated', 'content');

                if (member.count !== null) {
                    const countHeader = document.createElement('h3');
                    countHeader.classList.add('ui', 'header');
                    countHeader.innerText = member.count;
                    countDiv.appendChild(countHeader);
                    mainDiv.appendChild(countDiv);
                }

                const avatarDiv = document.createElement('img');
                avatarDiv.classList.add('ui', 'avatar', 'image');
                avatarDiv.setAttribute('src', member.avatar_url);
                mainDiv.appendChild(avatarDiv);

                const contentDiv = document.createElement('div');
                contentDiv.classList.add('middle', 'aligned', 'content');

                const nameDiv = document.createElement('a');
                nameDiv.classList.add('header');
                nameDiv.setAttribute('href', member.profile_url);
                nameDiv.innerHTML = member.username;
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
