{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="container">
    <div class="card">
        <div class="card-body">
            <h2 style="display:inline">{$SEARCH_RESULTS}</h2>
            <span class="float-md-right"><a href="{$NEW_SEARCH_URL}" class="btn btn-primary">{$NEW_SEARCH}</a></span>
            <br /><br />

            {if isset($RESULTS)}
                {foreach from=$RESULTS item=result}
                    <div class="card">
                        <div class="card-header">
                            <a href="{$result.post_url}">{$result.topic_title}</a>
                            <span class="float-md-right" data-toggle="tooltip" title="{$result.post_date_full}">{$result.post_date_friendly}</span>
                        </div>
                        <div class="card-body">
                            {$result.content}
                            <hr />
                            <a href="{$result.post_author_profile}"><img class="rounded-circle" src="{$result.post_author_avatar}" style="max-height:40px; max-width:40px;"/></a> <a href="{$result.post_author_profile}" style="{$result.post_author_style}" data-poload="{$USER_INFO_URL}{$result.post_author_id}" data-html="true" data-placement="top">{$result.post_author}</a>
                            <span class="float-md-right"><a href="{$result.post_url}" class="btn btn-primary btn-sm">{$READ_FULL_POST} &raquo;</a></span>
                        </div>
                    </div>
					<br />
                {/foreach}
            {else}
                <div class="alert alert-info">
                    {$NO_RESULTS}
                </div>
            {/if}

            {if isset($PAGINATION)}
                <br />
                {$PAGINATION}
            {/if}
        </div>
    </div>
</div>

{include file='footer.tpl'}