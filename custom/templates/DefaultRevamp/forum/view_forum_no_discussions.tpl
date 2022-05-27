{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="ui breadcrumb" style="margin-bottom:10px;">
    {assign i 1}
    {foreach from=$BREADCRUMBS item=breadcrumb}
    {if $i != 1}<i class="right angle icon divider"></i>{/if}
    <a class="section{if isset($breadcrumb.active)} active{/if}" href="{$breadcrumb.link}">{$breadcrumb.forum_title}</a>
    {assign i $i+1}
    {/foreach}
</div>

<div class="ui stackable padded grid" id="forum-view">
    <div class="ui centered row">
        <div class="ui eleven wide tablet twelve wide computer column">
            <h2 class="ui header">
                {$FORUM_TITLE}

                {if $NEW_TOPIC_BUTTON}
                <div class="res right floated">
                    <a class="ui small primary button" href="{$NEW_TOPIC_BUTTON}">{$NEW_TOPIC}</a>
                </div>
                {/if}
            </h2>
        </div>
        <div class="ui five wide tablet four wide computer column">
            <form class="ui form" method="post" action="{$SEARCH_URL}" name="searchForm">
                <input type="hidden" name="token" value="{$TOKEN}">
                <div class="ui fluid action input">
                    <input type="text" name="forum_search" placeholder="{$SEARCH}">
                    <button type="submit" class="ui primary icon button"><i class="search icon"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="ui stackable padded grid" id="forum-view">
    <div class="ui centered row">
        {if count($WIDGETS_LEFT)}
        <div class="ui five wide tablet four wide computer column">
            {foreach from=$WIDGETS_LEFT item=widget}
            {$widget}
            {/foreach}
        </div>
        {/if}
        <div
            class="ui {if count($WIDGETS_LEFT) && count($WIDGETS_RIGHT) }four wide tablet eight wide computer{elseif count($WIDGETS_LEFT) || count($WIDGETS_RIGHT)}ten wide tablet twelve wide computer{else}sixteen wide{/if} column">
            {if count($SUBFORUMS)}
            <table class="ui fixed single line selectable unstackable small padded res table" id="subforums-table">
                <thead>
                    <tr>
                        <th class="nine wide">
                            <h4>{$SUBFORUM_LANGUAGE}</h4>
                        </th>
                        <th class="seven wide"></th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$SUBFORUMS item=subforum}
                    <tr>
                        <td>
                            <h5 class="ui header">
                                {if empty($subforum.icon)}<i class="comment icon"></i>{else}{$subforum.icon}{/if}
                                <div class="content">
                                    <a href="{$subforum.link}" data-toggle="popup">{$subforum.title}</a>
                                    <div class="ui wide popup">
                                        <h4 class="ui header">{$subforum.title}</h4>
                                        {$TOPICS|capitalize}: <b>{$subforum.topics}</b>
                                    </div>
                                    <div class="sub header">
                                        {if !$subforum.redirect}{$TOPICS|capitalize}: <b>{$subforum.topics}</b>{/if}
                                    </div>
                                </div>
                            </h5>
                        </td>
                        <td>
                            {if !$subforum.redirect}
                            {if !empty($subforum.latest_post)}
                            <h5 class="ui header">
                                <img class="ui mini circular image" src="{$subforum.latest_post.last_user_avatar}">
                                <div class="content">
                                    <a href="{$subforum.latest_post.link}"
                                        data-toggle="popup">{$subforum.latest_post.title}</a>
                                    <div class="ui wide popup">
                                        <h4 class="ui header">{$subforum.latest_post.title}</h4>
                                        <br />{$BY|capitalize} <a style="{$subforum.latest_post.last_user_style}"
                                            href="{$subforum.latest_post.last_user_link}">{$subforum.latest_post.last_user}</a>
                                        | {$subforum.latest_post.time}
                                    </div>
                                    <div class="sub header">
                                        <a href="{$subforum.latest_post.last_user_link}"
                                            data-poload="{$USER_INFO_URL}{$subforum.latest_post.last_user_id}"
                                            style="{$subforum.latest_post.last_user_style}">{$subforum.latest_post.last_user}</a>
                                        &middot;
                                        <span data-toggle="tooltip"
                                            data-content="{$subforum.latest_post.time}">{$subforum.latest_post.timeago}</span>
                                    </div>
                                </div>
                            </h5>
                            {else}
                            {$NO_TOPICS}
                            {/if}
                            {/if}
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
            {/if}
            <table class="ui single line selectable unstackable small padded res table" id="normal-threads">
                <thead>
                    <tr>
                        <th class="sixteen wide">
                            <h4>{$TOPICS|capitalize}</h4>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            {$NO_TOPICS_FULL}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        {if count($WIDGETS_RIGHT)}
        <div class="ui five wide tablet four wide computer column">
            {foreach from=$WIDGETS_RIGHT item=widget}
            {$widget}
            {/foreach}
        </div>
        {/if}
    </div>
</div>

{include file='footer.tpl'}