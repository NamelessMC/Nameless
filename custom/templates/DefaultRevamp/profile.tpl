{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="ui segment center aligned" id="profile-header" style="background-image:url('{$BANNER}');background-size:cover;">
    <div class="actions">
        {if isset($LOGGED_IN)}
            {if isset($SELF)}
                <a class="ui tiny primary icon button" href="{$SETTINGS_LINK}"><i class="cogs icon"></i></a>
                <button type="button" class="ui tiny teal icon button" onclick="showBannerSelect()">
                    <i class="picture icon"></i>
                </button>
            {else}
                {if ($MOD_OR_ADMIN != true)}
                <a class="ui tiny negative icon button" data-toggle="modal" data-target="#modal-block">
                    <i class="ban icon"></i>
                </a>
                {/if}
                <a class="ui tiny default icon button" href="{$MESSAGE_LINK}"><i class="envelope icon"></i></a>
                {if isset($RESET_PROFILE_BANNER)}
                    <form action="{$RESET_PROFILE_BANNER_LINK}" method="post" style="display: inline">
                        <input type="hidden" name="token" value="{$TOKEN}" />
                        <button class="ui tiny negative icon button" data-toggle="tooltip" data-content="{$RESET_PROFILE_BANNER}">
                            <i class="picture icon"></i>
                        </button>
                    </form>
                {/if}
            {/if}
        {/if}
    </div>
    <center>
        <img class="ui tiny circular image" src="{$AVATAR}">
        <h2 class="ui header">
            <span{if $USERNAME_COLOUR != false} style="{$USERNAME_COLOUR}" {/if}>{$NICKNAME}</span>
            {if isset($USER_TITLE)}
                <div class="sub header">{$USER_TITLE}</div>
            {/if}
        </h2>
        {foreach from=$GROUPS item=group}
            {$group}
        {/foreach}
    </center>
</div>

<div class="ui stackable grid" id="profile">
    <div class="ui centered row">
        {if count($WIDGETS_LEFT)}
            <div class="ui six wide tablet four wide computer column">
                {foreach from=$WIDGETS_LEFT item=widget}
                    {$widget}
                {/foreach}
            </div>
        {/if}
        <div class="ui {if count($WIDGETS_LEFT) && count($WIDGETS_RIGHT) }four wide tablet eight wide computer{elseif count($WIDGETS_LEFT) || count($WIDGETS_RIGHT)}ten wide tablet twelve wide computer{else}sixteen wide{/if} column">
            {if isset($SUCCESS)}
                <div class="ui success icon message">
                    <i class="check icon"></i>
                    <div class="content">
                        <div class="header">{$SUCCESS_TITLE}</div>
                        {$SUCCESS}
                    </div>
                </div>
            {/if}
            {if isset($ERROR)}
                <div class="ui negative icon message">
                    <i class="x icon"></i>
                    <div class="content">
                        <div class="header">{$ERROR_TITLE}</div>
                        {$ERROR}
                    </div>
                </div>
            {/if}
            {if $CAN_VIEW}
                <div class="ui top attached tabular menu">
                    <a class="item active" data-tab="feed">{$FEED}</a>
                    <a class="item" data-tab="about">{$ABOUT}</a>
                    {foreach from=$TABS key=key item=tab}
                        <a class="item" data-tab="{$key}">{$tab.title}</a>
                    {/foreach}
                </div>
                <div class="ui bottom attached tab segment active" data-tab="feed" id="profile-feed">
                    <h3 class="ui header">{$FEED}</h3>
                    {if isset($LOGGED_IN)}
                        <form class="ui reply form" action="" method="post" id="form-profile-post">
                            <div class="field">
                                <textarea name="post" placeholder="{$POST_ON_WALL}"></textarea>
                            </div>
                            <input type="hidden" name="action" value="new_post">
                            <input type="hidden" name="token" value="{$TOKEN}">
                            <input type="submit" class="ui primary button" value="{$SUBMIT}">
                        </form>
                    {/if}
                    {if count($WALL_POSTS)}
                        <div class="ui threaded comments" id="profile-posts">
                            {foreach from=$WALL_POSTS item=post}
                                <div class="comment" id="post-{$post.id}">
                                    <a class="ui circular image avatar">
                                        <img src="{$post.avatar}" alt="{$post.nickname}">
                                    </a>
                                    <div class="content">
                                        <a class="author" href="{$post.profile}" data-poload="{$USER_INFO_URL}{$post.user_id}" style="{$post.user_style}">
                                            {$post.nickname}
                                        </a>
                                        <div class="metadata">
                                            <span class="date" data-toggle="tooltip" data-content="{$post.date}">
                                                {$post.date_rough}
                                            </span>
                                        </div>
                                        <div class="text forum_post">
                                            {$post.content}
                                        </div>
                                        <div class="actions">
                                            {if isset($LOGGED_IN_USER)}
                                                {if $post.user_id ne $VIEWER_ID}
                                                    <a href="{if $post.reactions_link !== " #"}{$post.reactions_link}{else}#{/if}"
                                                        data-toggle="popup">{$LIKE}
                                                        {if ($post.reactions.count|regex_replace:'/[^0-9]+/':'' !=
                                                        0)}({$post.reactions.count|regex_replace:'/[^0-9]+/':''}){/if}</a>
                                                {/if}
                                                <a data-toggle="modal" data-target="#modal-reply-{$post.id}">{$REPLY} {if
                                                    ($post.replies.count|regex_replace:'/[^0-9]+/':'' !=
                                                    0)}({$post.replies.count|regex_replace:'/[^0-9]+/':''}){/if}</a>
                                            {/if}
                                            {if (isset($CAN_MODERATE) && $CAN_MODERATE == 1) || $post.self == 1}
                                                <a data-toggle="modal" data-target="#modal-edit-{$post.id}">{$EDIT}</a>
                                                <a onclick="{literal}if(confirm(confirmDelete)){$('#form-delete-post-{/literal}{$post.id}{literal}').submit();}{/literal}">
                                                    {$DELETE}
                                                </a>
                                                <form action="" method="post" id="form-delete-post-{$post.id}">
                                                    <input type="hidden" name="post_id" value="{$post.id}">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="token" value="{$TOKEN}">
                                                </form>
                                            {/if}
                                        </div>
                                    </div>
                                    {if isset($post.replies.replies)}
                                        <div class="comments">
                                            {foreach from=$post.replies.replies item=item}
                                                <div class="comment">
                                                    <a class="ui circular image avatar">
                                                        <img src="{$item.avatar}" alt="{$item.nickname}">
                                                    </a>
                                                    <div class="content">
                                                        <a class="author" href="{$item.profile}" style="{$item.style}">{$item.nickname}</a>
                                                        <div class="metadata">
                                                            <span class="date" data-toggle="tooltip"
                                                                data-content="{$item.time_full}">{$item.time_friendly}</span>
                                                        </div>
                                                        <div class="text forum_post">
                                                            {$item.content}
                                                        </div>
                                                        <div class="actions">
                                                            {if (isset($CAN_MODERATE) && $CAN_MODERATE eq 1) || $post.self eq 1}
                                                                <form class="ui form" action="" method="post" id="form-delete-{$item.id}">
                                                                    <input type="hidden" name="action" value="deleteReply">
                                                                    <input type="hidden" name="token" value="{$TOKEN}">
                                                                    <input type="hidden" name="post_id" value="{$item.id}">
                                                                </form>
                                                                <a onclick="{literal}if(confirm(confirmDelete)){$('#form-delete-{/literal}{$item.id}{literal}').submit();}{/literal}">
                                                                    {$DELETE}
                                                                </a>
                                                            {/if}
                                                        </div>
                                                    </div>
                                                </div>
                                            {/foreach}
                                        </div>
                                    {/if}
                                </div>
                            {/foreach}
                        </div>
                        {$PAGINATION}
                    {else}
                        <div class="ui info message">
                            <div class="content">
                                {$NO_WALL_POSTS}
                            </div>
                        </div>
                    {/if}
                </div>
                <div class="ui bottom attached tab segment" data-tab="about" id="profile-about">
                    <h3 class="ui header">{$ABOUT}</h3>
                    <div class="ui relaxed list">
                        <div class="item">
                            <i class="middle aligned user add icon"></i>
                            <div class="middle aligned content" data-toggle="popup">
                                <div class="header">{$ABOUT_FIELDS.registered.title}</div>
                                <div class="description">{$ABOUT_FIELDS.registered.value}</div>
                            </div>
                            <div class="ui wide popup">
                                <h4 class="ui header">{$ABOUT_FIELDS.registered.title|replace:':':''}</h4>
                                <br />
                                {$ABOUT_FIELDS.registered.tooltip}
                            </div>
                        </div>
                        <div class="item">
                            <i class="middle aligned clock icon"></i>
                            <div class="middle aligned content" data-toggle="popup">
                                <div class="header">{$ABOUT_FIELDS.last_seen.title}</div>
                                <div class="description">{$ABOUT_FIELDS.last_seen.value}</div>
                            </div>
                            <div class="ui wide popup">
                                <h4 class="ui header">{$ABOUT_FIELDS.last_seen.title|replace:':':''}</h4>
                                <br />
                                {$ABOUT_FIELDS.last_seen.tooltip}
                            </div>
                        </div>
                        <div class="item">
                            <i class="middle aligned eye icon"></i>
                            <div class="middle aligned content">
                                <div class="header">{$ABOUT_FIELDS.profile_views.title}</div>
                                <div class="description">{$ABOUT_FIELDS.profile_views.value}</div>
                            </div>
                        </div>
                    </div>
                    {if count($ABOUT_FIELDS)}
                        <div class="ui relaxed list">
                            {foreach from=$ABOUT_FIELDS key=key item=field}
                                {if is_numeric($key)}
                                    <div class="item">
                                        <i class="middle aligned {if $field.type eq 'date'}calendar alternate{else}dot circle{/if} icon"></i>
                                        <div class="middle aligned content" {if $field.tooltip} data-toggle="popup" {/if}">
                                            <div class="header">{$field.title}</div>
                                            <div class="description">{$field.value}</div>
                                        </div>
                                        {if $field.tooltip}
                                            <div class="ui wide popup">
                                                <h4 class="ui header">{$field.title}</h4>
                                                <br />
                                                {$field.tooltip}
                                            </div>
                                        {/if}
                                    </div>
                                {/if}
                            {/foreach}
                        </div>
                    {else}
                    <div class="ui info message">
                        <div class="content">
                            {$NO_ABOUT_FIELDS}
                        </div>
                    </div>
                    {/if}
                </div>
                {foreach from=$TABS key=key item=tab}
                    <div class="ui bottom attached tab segment" data-tab="{$key}" id="profile-{$key}">
                        {include file=$tab.include}
                    </div>
                {/foreach}
            {else}
                <div class="ui error message">
                    <div class="content">
                        {$PRIVATE_PROFILE}
                    </div>
                </div>
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

{if count($WALL_POSTS)}
    {foreach from=$WALL_POSTS item=post}
        {if (isset($CAN_MODERATE) && $CAN_MODERATE eq 1) || $post.self eq 1}
            <div class="ui small modal" id="modal-edit-{$post.id}">
                <div class="header">{$EDIT_POST}</div>
                <div class="content">
                    <form class="ui form" action="" method="post" id="form-edit-{$post.id}">
                        <div class="field">
                            <textarea name="content">{$post.content}</textarea>
                        </div>
                        <input type="hidden" name="token" value="{$TOKEN}">
                        <input type="hidden" name="post_id" value="{$post.id}">
                        <input type="hidden" name="action" value="edit">
                    </form>
                </div>
                <div class="actions">
                    <a class="ui negative button">{$CANCEL}</a>
                    <a class="ui positive button" onclick="$('#form-edit-{$post.id}').submit();">{$SUBMIT}</a>
                </div>
            </div>
        {/if}
        {if isset($LOGGED_IN_USER)}
            <div class="ui small modal" id="modal-reply-{$post.id}">
                <div class="header">
                    {$REPLY}
                </div>
                <div class="content">
                    <form class="ui form" action="" method="post" id="form-reply-{$post.id}">
                        <div class="field">
                            <textarea name="reply" placeholder="{$NEW_REPLY}"></textarea>
                        </div>
                        <input type="hidden" name="token" value="{$TOKEN}">
                        <input type="hidden" name="post" value="{$post.id}">
                        <input type="hidden" name="action" value="reply">
                    </form>
                </div>
                <div class="actions">
                    <a class="ui negative button">{$CANCEL}</a>
                    <a class="ui positive button" onclick="$('#form-reply-{$post.id}').submit();">{$SUBMIT}</a>
                </div>
            </div>
        {/if}
    {/foreach}
{/if}

{if isset($LOGGED_IN_USER)}
    {if !isset($SELF)}
        {if $MOD_OR_ADMIN != true}
            <div class="ui small modal" id="modal-block">
                <div class="header">
                    {if isset($BLOCK_USER)}{$BLOCK_USER}{else}{$UNBLOCK_USER}{/if}
                </div>
                <div class="content">
                    {if isset($CONFIRM_BLOCK_USER)}{$CONFIRM_BLOCK_USER}{else}{$CONFIRM_UNBLOCK_USER}{/if}
                    <form class="ui form" action="" method="post" id="form-block">
                        <input type="hidden" name="token" value="{$TOKEN}">
                        <input type="hidden" name="action" value="block">
                    </form>
                </div>
                <div class="actions">
                    <a class="ui negative button">{$CANCEL}</a>
                    <a class="ui positive button" onclick="$('#form-block').submit();">{$SUBMIT}</a>
                </div>
            </div>
        {/if}
    {else}
        <!-- Change background image modal -->
        <div class="ui small modal" id="imageModal">
            <div class="header">
                {$CHANGE_BANNER}
            </div>
            <div class="content">
                <form action="" class="ui form" name="updateBanner" method="post" style="display:inline;">
                    <select name="banner" class="image-picker show-html">
                        {foreach from=$BANNERS item=banner}
                            <option data-img-src="{$banner.src}" value="{$banner.name}" {if $banner.active==true} selected{/if}>
                                {$banner.name}
                            </option>
                        {/foreach}
                    </select>
                    <input type="hidden" name="token" value="{$TOKEN}">
                    <input type="hidden" name="action" value="banner">
                </form>
                {if isset($PROFILE_BANNER)}
                    <div class="ui horizontal divider">Or {$UPLOAD_PROFILE_BANNER}</div>
                    <center>
                        <form class="ui form" action="{$UPLOAD_BANNER_URL}" method="post" enctype="multipart/form-data"
                            id="form-banner">
                            <input type="file" class="inputFile" name="file" id="uploadBannerInput" hidden />
                            <label class="ui icon labeled default button" for="uploadBannerInput">
                                <i class="ui cloud upload icon"></i> {$BROWSE}
                            </label>
                            <input type="hidden" name="token" value="{$TOKEN}">
                            <input type="hidden" name="type" value="profile_banner">
                            <button type="submit" class="ui icon labeled primary button" type="submit">
                                <i class="ui upload icon"></i> {$UPLOAD}
                            </button>
                        </form>
                    </center>
                {/if}
            </div>
            <div class="actions">
                <button class="ui negative button">{$CANCEL}</button>
                <button class="ui positive button" onclick="document.updateBanner.submit()">{$SUBMIT}</button>
            </div>
        </div>
    {/if}
{/if}

{include file='footer.tpl'}
