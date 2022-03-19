<form action="" method="post">
    <div class="form-group custom-control custom-switch">
        <input id="inputIncludeStaff" name="staff" type="checkbox" class="custom-control-input js-check-change" value="1" {if $INCLUDE_STAFF_VALUE eq 1} checked{/if} />
        <label for="inputIncludeStaff" class="custom-control-label">
            {$INCLUDE_STAFF}
        </label>
    </div>
    <div class="form-group custom-control custom-switch">
        <input id="inputShowNickname" name="nickname" type="checkbox" class="custom-control-input js-check-change" value="1" {if $SHOW_NICKNAME_INSTEAD_VALUE eq 1} checked{/if} />
        <label for="inputShowNickname" class="custom-control-label">
            {$SHOW_NICKNAME_INSTEAD}
        </label>
    </div>
    <div type="form-group">
        <input type="hidden" name="token" value="{$TOKEN}">
        <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
    </div>
</form>