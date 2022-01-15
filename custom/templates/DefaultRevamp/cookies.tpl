{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="ui header">
    {$COOKIE_NOTICE_HEADER}
</h2>

<div class="ui padded segment" id="cookies">
    {$COOKIE_NOTICE}

    <div class="ui divider"></div>
    <div class="ui blue button" onclick="configureCookies()">{$UPDATE_SETTINGS}</div>
</div>

{include file='footer.tpl'}