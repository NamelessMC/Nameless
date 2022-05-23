{if isset($SUCCESS)}
    <div class="alert bg-success text-white alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h5><i class="icon fa fa-check"></i> {$SUCCESS_TITLE}</h5>
        {$SUCCESS}
    </div>
    <!-- TODO: reference string from file (e.g. test_email_query) -->
    <div class ="email-suggest">
        Not reaching your inbox? Try the following:
        <ul>
            <li>Wait a few minutes and check your Spam folder.</li>
            <li>Update your site's DNS records (check SPF & DKIM).</li>
            <li>Set up a SMTP server. <a href="https://docs.namelessmc.com/en/smtp">Read more »</a></li>
        </ul>
    </div>
{/if}
{if isset($ERRORS) && count($ERRORS)}
    <div class="alert bg-danger text-white alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h5><i class="icon fas fa-exclamation-triangle"></i> {$ERRORS_TITLE}</h5>
        <ul>
            {foreach from=$ERRORS item=error}
                <li>{$error}</li>
            {/foreach}
        </ul>
    </div>
{/if}