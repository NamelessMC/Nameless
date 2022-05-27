{include file='header.tpl'}
{include file='navbar.tpl'}

{if isset($HOME_SESSION_FLASH)}
<div class="ui success icon message">
    <i class="check icon"></i>
    <div class="content">
        <div class="header">{$SUCCESS_TITLE}</div>
        {$HOME_SESSION_FLASH}
    </div>
</div>
{/if}

{if isset($HOME_SESSION_ERROR_FLASH)}
<div class="ui error icon message">
    <i class="x icon"></i>
    <div class="content">
        <div class="header">{$ERROR_TITLE}</div>
        {$HOME_SESSION_ERROR_FLASH}
    </div>
</div>
{/if}

<div class="ui stackable grid">
    <div class="ui centered row">
        {if count($WIDGETS_LEFT)}
        <div class="ui six wide tablet four wide computer column">
            {foreach from=$WIDGETS_LEFT item=widget}
            {$widget}
            {/foreach}
        </div>
        {/if}
        <div
            class="ui {if count($WIDGETS_LEFT) && count($WIDGETS_RIGHT) }four wide tablet eight wide computer{elseif count($WIDGETS_LEFT) || count($WIDGETS_RIGHT)}ten wide tablet twelve wide computer{else}sixteen wide{/if} column">
            {if $HOME_TYPE === 'news'}
            {foreach from=$NEWS item=item}
            <div class="ui fluid card" id="news-post">
                <div class="content">
                    <div class="header">
                        {if isset($item.label)}{$item.label}{/if}
                        <a href="{$item.url}" data-toggle="popup">{$item.title}</a>
                        <div class="ui popup wide transition hidden">
                            <h4 class="ui header">{$item.title}</h4>
                            {$BY|capitalize} <a style="{$item.author_style}"
                                href="{$item.author_url}">{$item.author_name}</a> |
                            {$item.date}
                        </div>
                    </div>
                    <div class="meta" data-toggle="tooltip" data-content="{$item.date}">
                        {$item.time_ago}
                    </div>
                    <div class="description forum_post">
                        {$item.content}
                    </div>
                </div>
                <div class="extra content">
                    <div class="right floated author">
                        <img class="ui avatar image" src="{$item.author_avatar}" alt="{$item.author_name}"> <a
                            style="{$item.author_style}" href="{$item.author_url}"
                            data-poload="{$USER_INFO_URL}{$item.author_id}">{$item.author_name}</a>
                    </div>
                    <a class="ui mini primary button" href="{$item.url}">
                        {$READ_FULL_POST}
                    </a>
                </div>
            </div>
            {foreachelse}
            <div class="ui segment red">
                {$NO_NEWS}
            </div>
            {/foreach}
            {elseif $HOME_TYPE === 'custom'}
            {$CUSTOM_HOME_CONTENT}
            {/if}
        </div>
        {if count($WIDGETS_RIGHT)}
        <div class="ui six wide tablet four wide computer column">
            {foreach from=$WIDGETS_RIGHT item=widget}
            {$widget}
            {/foreach}
        </div>
        {/if}
    </div>
</div>

{include file='footer.tpl'}