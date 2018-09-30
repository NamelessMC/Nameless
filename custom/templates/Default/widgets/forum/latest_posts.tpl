<div class="card">
    <div class="card-body">
        <h2>{$LATEST_POSTS}</h2>
        {foreach from=$LATEST_POSTS_ARRAY item=post name=latest_posts}
            <div class="row">
                <div class="col-md-3">
                    <div class="frame">
                        <a href="{$post.last_reply_profile_link}"><img class="img-centre rounded" style="max-height:30px;max-width:30px;" src="{$post.last_reply_avatar}" alt="{$post.last_reply_username}"/></a>
                    </div>
                </div>
                <div class="col-md-9">
                    <a href="{$post.last_reply_link}">{$post.topic_title}</a><br />
                    {$BY} <a href="{$post.last_reply_profile_link}" style="{$post.last_reply_style}" data-poload="{$USER_INFO_URL}{$post.last_reply_user_id}" data-html="true" data-placement="top">{$post.last_reply_username}</a><br />
                    <span data-toggle="tooltip" data-trigger="hover" data-original-title="{$post.last_reply}">{$post.last_reply_rough}</span>
                </div>
            </div>
            {if not $smarty.foreach.latest_posts.last}<br />{/if}
        {/foreach}
    </div>
</div>