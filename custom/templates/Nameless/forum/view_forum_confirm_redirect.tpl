{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="ui padded segment center aligned" id="forum-redirect">
    <div class="ui stackable grid">
        <div class="ui centered row">
            <div class="ui sixteen wide tablet twelve wide computer column" style="text-align: center;">
                <h4 class="ui header">{$CONFIRM_REDIRECT}</h4>
                <div class="ui divider"></div>
                <div class="ui buttons">
                    <a class="ui primary button" href="{$FORUM_INDEX}">{$NO}</a>
                    <div class="or"></div>
                    <a class="ui positive button" href="{$REDIRECT_URL}" target="_blank"
                        rel="noopener nofollow">{$YES}</a>
                </div>
            </div>
        </div>
    </div>
</div>

{include file='footer.tpl'}