<div class="card shadow border-left-primary">
    <div class="card-body">
        <h5><i class="icon fa fa-info-circle"></i> {$INFO}</h5>
        {$WIDGET_CACHED}
    </div>
</div>

</br>

<form action="" method="post">
    <div class="form-group">
        <label for="inputPostLimit">{$LATEST_POSTS_LIMIT}</label>
        <input id="inputPostLimit" name="limit" type="number" min="1" class="form-control" placeholder="{$LATEST_POSTS_LIMIT}" value="{$LATEST_POSTS_LIMIT_VALUE}">
    </div>
    <div type="form-group">
        <input type="hidden" name="token" value="{$TOKEN}">
        <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
    </div>
</form>