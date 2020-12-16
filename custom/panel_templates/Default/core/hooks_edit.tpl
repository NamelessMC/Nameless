{include file='header.tpl'}
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    {include file='navbar.tpl'}
    {include file='sidebar.tpl'}

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">{$HOOKS}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                            <li class="breadcrumb-item active">{$HOOKS}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                {include file='includes/update.tpl'}

                <div class="card">
                    <div class="card-body">
                        <h5 style="display:inline">{$EDITING_HOOK}</h5>
                        <div class="float-md-right">
                        <a href="{$BACK_LINK}" class="btn btn-warning">{$BACK}</a>
                        </div>
                        <hr>
                    
                        {if isset($SUCCESS)}
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h5><i class="icon fa fa-check"></i> {$SUCCESS_TITLE}</h5>
                                {$SUCCESS}
                            </div>
                        {/if}

                        {include file='includes/errors.tpl'}

                        <form role="form" action="" method="post">
                            <div class="form-group">
                            <label for="InputName">{$HOOK_NAME}</label>
                            <input type="text" name="hook_name" class="form-control" id="InputName" value="{$HOOK_NAME_VALUE}" placeholder="{$HOOK_NAME_VALUE}">
                            </div>
                            <div class="form-group">
                            <label for="InputURL">{$HOOK_URL}</label>
                            <input type="text" name="hook_url" class="form-control" id="InputURL" value="{$HOOK_URL_VALUE}" placeholder="https://example.com/examplelistener">
                            </div>
                            <div class="form-group">
                            <label for="link_location">{$HOOK_TYPE}</label>
                            <select class="form-control" id="hook_type" name="hook_type">
                                <option value="2" {if $HOOK_TYPE_VALUE eq 2} selected{/if}>Discord</option>
                            </select>
                            </div>
                            <label for="InputName">{$HOOK_EVENTS}</label>
                            {foreach from=$ALL_HOOKS key=key item=item}
                            <div class="form-group">
                            <input type="checkbox" name="events[{$key|escape}]" class="js-switch" value="1" {if in_array($key|escape, $ENABLED_HOOKS)} checked{/if}> {$item|escape}
                            </br>
                            </div>
                            {/foreach}
                            <div class="form-group">
                            <input type="hidden" name="token" value="{$TOKEN}">
                            <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
                            </div>
                        </form>

                    </div>
                </div>

                <!-- Spacing -->
                <div style="height:1rem;"></div>

            </div>
        </section>
    </div>

    {include file='footer.tpl'}

</div>
<!-- ./wrapper -->

{include file='scripts.tpl'}

</body>
</html>