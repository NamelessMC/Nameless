<form action="" method="post">
    <div class="form-group custom-control custom-switch">
        <input id="inputPremiumAccounts" name="premium_account" type="checkbox" class="custom-control-input js-check-change" {if $PREMIUM_ACCOUNTS_VALUE} checked{/if}/>
        <label for="inputPremiumAccounts" class="custom-control-label">
            {$PREMIUM_ACCOUNTS}
        </label>
    </div>
    <div class="form-group custom-control custom-switch">
        <input id="inputUsernameRegistration" name="username_registration" type="checkbox" class="custom-control-input js-check-change" {if $REQUIRE_USERNAME_REGISTRATION_VALUE} checked{/if}/>
        <label for="inputUsernameRegistration" class="custom-control-label">
            {$REQUIRE_USERNAME_REGISTRATION}
        </label>
    </div>
    <div class="form-group">
        <input type="hidden" name="token" value="{$TOKEN}">
        <input type="hidden" name="action" value="integration_settings">
        <input type="submit" value="{$SUBMIT}" class="btn btn-primary">
    </div>
</form>