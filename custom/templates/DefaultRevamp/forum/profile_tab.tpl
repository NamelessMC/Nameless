<h3 class="ui header">
    {$PF_LATEST_POSTS_TITLE}
</h3>

{if isset($NO_POSTS)}
<div class="ui info message">
    <div class="content">
        {$NO_POSTS}
    </div>
</div>
{else}
{foreach from=$PF_LATEST_POSTS item=post}
<h4 class="ui dividing header">
    <div class="sub header right floated" data-toggle="tooltip" data-content="{$post.date_full}">{$post.date_friendly}
    </div>
    <a href="{$post.link}" data-toggle="popup">{$post.title}</a>
    <div class="ui wide popup">
        <h4 class="ui header">{$post.title}</h4>
        {$post.date_full}
    </div>
</h4>
<div class="forum_post">{$post.content}</div>
{/foreach}
{/if}