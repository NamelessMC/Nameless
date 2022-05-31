{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="ui breadcrumb" style="margin-bottom:10px;">
    <a class="section active" href="{$BREADCRUMB_URL}">{$BREADCRUMB_TEXT}</a>
</div>

<div class="ui stackable padded grid" id="forum-index">
    <div class="ui centered row">
        <div class="ui eleven wide tablet twelve wide computer column">
            <h2 class="ui header">
                {$TITLE}
            </h2>
        </div>

        <div class="ui five wide tablet four wide computer column">
            <form class="ui form" method="post" action="{$SEARCH_URL}" name="searchForm">
                <input type="hidden" name="token" value="{$TOKEN}">
                <div class="ui fluid action input">
                    <input type="text" name="forum_search" placeholder="{$SEARCH}" minlength="3" maxlength="128">
                    <button type="submit" class="ui primary icon button"><i class="search icon"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="ui stackable padded grid" id="forum-index">
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
            {if isset($SPAM_INFO)}
            <div class="ui warning icon message">
                <i class="exclamation icon"></i>
                <div class="content">
                    <div class="header">{$FORUM_SPAM_WARNING_TITLE}</div>
                    {$SPAM_INFO}
                </div>
            </div>
            {/if}

            {foreach from=$FORUMS key=category item=forum}
            {if !empty($forum.subforums)}
            <div class="ui padded segment" id="forum-node">
                <h3 class="ui header"><a href="{$forum.link}">{$forum.title}</a></h3>
                <div class="ui divider"></div>
                <div class="ui middle aligned stackable grid">
                    {foreach from=$forum.subforums item=subforum}
                    {if $subforum->redirect_forum neq 1}
                    <div class="centered row">
                        <div class="one wide column mobile hidden">{if empty($subforum->icon)}
                            <i class="ui large comment icon middle aligned"></i>
                            {else}{$subforum->icon}{/if}
                        </div>
                        <div class="nine wide column">
                            <a class="header" href="{$subforum->link}" data-toggle="popup">{$subforum->forum_title}</a>
                            <div class="ui wide popup">
                                <h4 class="ui header">{$subforum->forum_title}</h4>
                                {if !empty($subforum->forum_description)}
                                <br />
                                {$subforum->forum_description}
                                {/if}
                                <br />{$TOPICS|capitalize} <b>{$subforum->topics}</b>
                                &middot; {$POSTS|capitalize} <b>{$subforum->posts}</b>
                                {if isset($subforum->subforums)}
                                <br />
                                {$SUBFORUMS}:
                                {assign i 1}
                                {foreach from=$subforum->subforums item=sub_subforum}
                                {if $i != 1}&middot; {/if}<a href="{$sub_subforum->link}">{$sub_subforum->title}</a>
                                {assign i $i+1}
                                {/foreach}
                                {/if}
                            </div>
                            <div class="description">
                                {$TOPICS|capitalize}: <b>{$subforum->topics}</b>
                                &middot; {$POSTS|capitalize}: <b>{$subforum->posts}</b>
                                {if isset($subforum->subforums)}
                                <div class="ui top right pointing inline dropdown">
                                    &middot; {$SUBFORUMS} <i class="dropdown icon"></i>
                                    <div class="menu">
                                        <div class="header">{$SUBFORUMS}</div>
                                        {foreach from=$subforum->subforums item=sub_subforum}
                                        <a class="item" href="{$sub_subforum->link}">
                                            {if empty($sub_subforum->icon)}
                                            <i class="comment icon"></i>
                                            {else}{$sub_subforum->icon}{/if} {$sub_subforum->title}
                                        </a>
                                        {/foreach}
                                    </div>
                                </div>
                                {/if}
                            </div>
                        </div>
                        <div class="six wide column mobile hidden">
                            {if isset($subforum->last_post)}
                            <img class="ui avatar image left floated" src="{$subforum->last_post->avatar}"
                                alt="{$subforum->last_post->username}">
                            <a class="header" href="{$subforum->last_post->link}"
                                data-toggle="popup">{$subforum->last_post->title}</a>
                            <div class="ui wide popup">
                                <h4 class="ui header">{$subforum->last_post->title}</h4>
                                <br />{$BY|capitalize} <a style="{$subforum->last_post->user_style}"
                                    href="{$subforum->last_post->profile}">{$subforum->last_post->username}</a>
                                | {$subforum->last_post->post_date}
                            </div>
                            <div class="description">
                                <a style="{$subforum->last_post->user_style}" href="{$subforum->last_post->profile}"
                                    data-poload="{$USER_INFO_URL}{$subforum->last_post->post_creator}">{$subforum->last_post->username}</a>
                                &middot; <span data-toggle="tooltip"
                                    data-content="{$subforum->last_post->post_date}">{$subforum->last_post->date_friendly}</span>
                            </div>
                            {else}
                            <div class="description" style="padding: 8px 0">{$NO_TOPICS}</div>
                            {/if}
                        </div>
                    </div>
                    {else}
                    <div class="centered row">
                        <div class="one wide column mobile hidden">{if empty($subforum->icon)}<i
                                class="ui large comment icon middle aligned"></i>{else}{$subforum->icon}{/if}</div>
                        <div class="fifteen wide column">
                            <a class="header" data-toggle="modal" {if isset($subforum->redirect_confirm)}
                                href="#"
                                data-target="#modal-redirect-{$subforum->id}"
                                {else}
                                href="{$subforum->redirect_url}"
                                {/if}>{$subforum->forum_title}</a>
                        </div>
                    </div>
                    <div class="ui mini modal" id="modal-redirect-{$subforum->id}">
                        <div class="content">
                            {$subforum->redirect_confirm}
                        </div>
                        <div class="actions">
                            <a class="ui negative button">{$NO}</a>
                            <a class="ui positive button" href="{$subforum->redirect_url}" target="_blank"
                                rel="noopener nofollow">{$YES}</a>
                        </div>
                    </div>
                    {/if}
                    {/foreach}
                </div>
            </div>
            {/if}
            {/foreach}
        </div>
        <div class="ui five wide tablet four wide computer column">
            {if count($WIDGETS_RIGHT)}
            {foreach from=$WIDGETS_RIGHT item=widget}
            {$widget}
            {/foreach}
            {/if}
        </div>
    </div>
</div>

{include file='footer.tpl'}