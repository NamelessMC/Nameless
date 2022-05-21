<div class="ui fluid card">
    <div class="content">
        <h4 class="ui header">{$COOKIE_NOTICE_HEADER}</h4>
        <div class="description">
            <p>{$COOKIE_NOTICE_BODY}</p>
            {if $COOKIE_DECISION_MADE}
            <a class="ui fluid blue button" href="{$COOKIE_URL}">{$COOKIE_NOTICE_CONFIGURE}</a>
            {/if}
        </div>
    </div>
</div>