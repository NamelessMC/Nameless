<form action="" method="post">
    <div class="form-group">
        <label for="inputIncludeStaff">{$INCLUDE_STAFF}</label>
        <input class="js-switch" type="checkbox" name="staff"
               id="inputIncludeStaff"
               value="1"{if $INCLUDE_STAFF_VALUE eq 1} checked{/if} >
    </div>
    <div type="form-group">
        <input type="hidden" name="token" value="{$TOKEN}">
        <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
    </div>
</form>