{include file='header.tpl'}
<div class="ui container" id="error-404">
    <div class="ui segment">
        <h2 class="ui header">{$404_TITLE}</h2>
        <div class="ui divider"></div>
        <p>{$CONTENT}</p>
        <div class="ui buttons">
            <button class="ui primary button" onclick="javascript:history.go(-1)">{$BACK}</button>
            <div class="or"></div>
            <a class="ui positive button" href="{$SITE_HOME}">{$HOME}</a>
        </div>
        <div class="ui divider"></div>
        <p>{$ERROR}</p>
    </div>
</div>
</body>

</html>