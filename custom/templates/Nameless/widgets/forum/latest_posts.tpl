<div class="ui fluid card" id="widget-latest-posts">
    <div class="content">
        <h4 class="ui header">{$LATEST_POSTS}</h4>
        <div class="description">
            {foreach from=$LATEST_POSTS_ARRAY name=latest_posts item=post}
                <div class="ui relaxed list">
                    <div class="item">
                        <img class="ui mini circular image" src="{$post.last_reply_avatar}"
                            alt="{$post.last_reply_username}">
                        <div class="content">
                            <a class="header" href="{$post.last_reply_link}" data-toggle="popup"
                                data-position="top left">{$post.topic_title}</a>
                            <div class="ui wide popup">
                                <h4 class="ui header">{$post.topic_title}</h4>
                                {$BY|capitalize} <a href="{$post.last_reply_profile_link}"
                                    style="{$post.last_reply_style}">{$post.last_reply_username}</a> | {$post.last_reply}
                            </div>
                            <a href="{$post.last_reply_profile_link}" style="{$post.last_reply_style}"
                                data-poload="{$USER_INFO_URL}{$post.last_reply_user_id}">{$post.last_reply_username}</a>
                            &middot; <span data-toggle="tooltip"
                                data-content="{$post.last_reply}">{$post.last_reply_rough}</span>
                        </div>
                    </div>
                </div>
            {foreachelse}
                {$NO_POSTS_FOUND}
            {/foreach}
        </div>
    </div>
</div>
