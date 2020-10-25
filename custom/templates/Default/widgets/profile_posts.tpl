<div class="card">
    <div class="card-body">
        <h2>{$LATEST_PROFILE_POSTS}</h2>
            {if isset($PROFILE_POSTS_ARRAY)}
                {foreach from=$PROFILE_POSTS_ARRAY name=profile_posts item=post}
                    <div class="row">
                        <div class="col-md-3">
                            <div class="frame">
                                <a href="{$post.user_profile_link}"><img class="img-centre rounded" style="max-height:30px;max-width:30px;" src="{$post.avatar}" alt="{$post.username}"/></a>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <a href="{$post.link}">{$post.content}</a><br />
                            {$BY} <a href="{$post.user_profile_link}" style="{$post.username_style}" data-poload="{$USER_INFO_URL}{$post.user_id}" data-html="true" data-placement="top">{$post.username}</a><br />
                            <span data-toggle="tooltip" data-trigger="hover" data-original-title="{$post.date_ago}">{$post.ago}</span>
                        </div>
                    </div>
                    {if not $smarty.foreach.latest_posts.last}<br />{/if}
                {/foreach}
            {else}
                {$NO_PROFILE_POSTS}
            {/if}
    </div>
</div>