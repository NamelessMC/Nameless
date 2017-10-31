<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
            <form role="form" action="" method="post">
                <h2>{$SIGNIN}</h2>
                {$SESSION_FLASH}
                <hr class="colorgraph">
                {$FORM_CONTENT}
                <hr class="colorgraph">
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <a class="btn btn-primary btn-block btn-lg" href="/register">{$REGISTER}</a>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        {$FORM_SUBMIT}
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>