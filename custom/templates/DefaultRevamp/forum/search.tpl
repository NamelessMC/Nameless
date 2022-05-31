{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
    {$FORUM_SEARCH}
</h2>

{if isset($ERROR)}
<div class="ui error icon message">
    <i class="x icon"></i>
    <div class="content">
        <div class="header">{$ERROR_TITLE}</div>
        {$ERROR}
    </div>
</div>
{/if}

<div class="ui padded segment" id="forum-search">
    <div class="ui stackable grid">
        <div class="ui centered row">
            <div class="ui sixteen wide tablet ten wide computer column">
                <form class="ui form" action="" method="post" id="form-forum-search">
                    <div class="field">
                        <label>{$SEARCH}</label>
                        <div class="ui fluid action input">
                            <input type="hidden" name="token" value="{$TOKEN}">
                            <input type="text" name="forum_search" placeholder="{$SEARCH}">
                            <button type="submit" class="ui primary icon button"><i class="search icon"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{include file='footer.tpl'}
