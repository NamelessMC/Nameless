{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="container">
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-4">
                    <div class="d-flex justify-content-center">
                        {$CONFIRM_REDIRECT}
                    </div>
                    <hr />
                    <div class="d-flex justify-content-center">
                        <div class="btn-group btn-group-lg" role="group" aria-label="...">
                            <a href="{$FORUM_INDEX}" class="btn btn-secondary">{$NO}</a>
                            <a href="{$REDIRECT_URL}" target="_blank" rel="noopener nofollow" class="btn btn-primary">{$YES}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{include file='footer.tpl'}