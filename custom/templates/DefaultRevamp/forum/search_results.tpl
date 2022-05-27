{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header" display="inline block">
    {$SEARCH_RESULTS}
</h2>

{if isset($PAGINATION)}
<br />
{$PAGINATION}
{/if}

<div class="res right floated">
    <a href="{$NEW_SEARCH_URL}" class="ui primary button">{$NEW_SEARCH}</a>
</div>

{if empty($RESULTS)}
<div class="ui error message">
    {$NO_RESULTS}
</div>
{else}
{foreach from=$RESULTS item=result}
<div class="ui segments" id="forum-search-result">
    <div class="ui padded attached segment">
        <h3 class="ui header">
            <a href="{$result.post_url}" data-toggle="popup">{$result.topic_title}</a>
            <div class="ui wide popup">
                <h4 class="ui header">{$result.topic_title}</h4>
                <a href="{$result.post_author_profile}" style="{$result.post_author_style}">{$result.post_author}</a> |
                {$result.post_date_full}
            </div>
            <div class="sub header">
                <a href="{$result.post_author_profile}" style="{$result.post_author_style}"
                    data-poload="{$USER_INFO_URL}{$result.post_author_id}">{$result.post_author}</a> &middot; <span
                    data-toggle="tooltip" data-content="{$result.post_date_full}">{$result.post_date_friendly}</span>
            </div>
        </h3>
        {$result.content}
    </div>
    <div class="ui bottom attached secondary segment">
        <div class="right aligned">
            <a class="ui mini primary button" href="{$result.post_url}">{$READ_FULL_POST}</a>
        </div>
    </div>
</div>
{/foreach}
{/if}

{if isset($PAGINATION)}
{$PAGINATION}
{/if}

{include file='footer.tpl'}