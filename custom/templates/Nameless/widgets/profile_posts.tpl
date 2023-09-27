<div class="ui fluid card" id="widget-latest-posts">
    <div class="content">
        <h4 class="ui header">{$LATEST_PROFILE_POSTS}</h4>
        <div class="description">
            {if isset($PROFILE_POSTS_ARRAY)}
            {foreach from=$PROFILE_POSTS_ARRAY name=profile_posts item=post}
            <div class="ui relaxed list">
                <div class="item">
                    <img class="ui mini circular image" src="{$post.avatar}" alt="{$post.username}">
                    <div class="content">
                        <a class="header" href="{$post.link}">{$post.content}</a>
                        <div class="ui wide popup">
                            {$BY|capitalize} <a href="{$post.user_profile_link}"
                                style="{$post.username_style}">{$post.username}</a> | {$post.last_reply}
                        </div>
                        <a href="{$post.user_profile_link}" style="{$post.username_style}}"
                            data-poload="{$USER_INFO_URL}{$post.user_id}">{$post.username}</a>
                        &middot; <span data-toggle="tooltip" data-content="{$post.date_ago}">{$post.ago}</span>
                    </div>
                </div>
            </div>
            {/foreach}
            {else}
            {$NO_PROFILE_POSTS}
            {/if}
        </div>
    </div>
</div>