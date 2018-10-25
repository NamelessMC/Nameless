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
                        <h1 class="m-0 text-dark">{$NICKNAME}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$NICKNAME}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                {if isset($NEW_UPDATE)}
                {if $NEW_UPDATE_URGENT eq true}
                <div class="alert alert-danger">
                    {else}
                    <div class="alert alert-primary alert-dismissible" id="updateAlert">
                        <button type="button" class="close" id="closeUpdate" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {/if}
                        {$NEW_UPDATE}
                        <br />
                        <a href="{$UPDATE_LINK}" class="btn btn-primary" style="text-decoration:none">{$UPDATE}</a>
                        <hr />
                        {$CURRENT_VERSION}<br />
                        {$NEW_VERSION}
                    </div>
                    {/if}

                    <div class="card">
                        <div class="card-body">
                            {if isset($SUCCESS)}
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h5><i class="icon fa fa-check"></i> {$SUCCESS_TITLE}</h5>
                                    {$SUCCESS}
                                </div>
                            {/if}

                            {if isset($ERRORS) && count($ERRORS)}
                                <div class="alert alert-danger alert-dismissible">
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

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card card-primary card-outline">
                                        <div class="card-body box-profile">
                                            <div class="text-center">
                                                <img class="profile-user-img img-fluid img-circle" src="{$AVATAR}" alt="{$USERNAME}">
                                            </div>

                                            <h3 class="profile-username text-center" style="{$USER_STYLE}">{$NICKNAME}</h3>

                                            <p class="text-muted text-center">{foreach from=$USER_GROUPS item=item}{$item} {/foreach}</p>

                                            <ul class="list-group list-group-unbordered mb-3">
                                                <li class="list-group-item">
                                                    <b>{$REGISTERED}</b> <a class="float-right">{$REGISTERED_VALUE}</a>
                                                </li>
                                                <li class="list-group-item">
                                                    <b>{$LAST_SEEN}</b> <a class="float-right" data-toggle="tooltip" data-title="{$LAST_SEEN_FULL_VALUE}">{$LAST_SEEN_SHORT_VALUE}</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="card">
                                        <div class="card-header p-2">
                                            <ul class="nav nav-pills">
                                                <li class="nav-item">
                                                    <a class="nav-link active">{$DETAILS}</a>
                                                </li>
                                                {foreach from=$LINKS item=item}
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="{($item.link|replace:'{id}':$USER_ID)|replace:'{username}':$USERNAME}">{$item.title}</a>
                                                    </li>
                                                {/foreach}
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="username">{$USERNAME_LABEL}</label>
                                                        <input id="username" type="text" class="form-control" value="{$USERNAME}" readonly>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="nickname">{$NICKNAME_LABEL}</label>
                                                        <input id="nickname" type="text" class="form-control" value="{$NICKNAME}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="title">{$USER_TITLE_LABEL}</label>
                                                        <input id="title" type="text" class="form-control" value="{$USER_TITLE}" readonly>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="uuid">{$UUID_LABEL}</label>
                                                        <input id="uuid" type="text" class="form-control" value="{$UUID}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="language">{$LANGUAGE_LABEL}</label>
                                                        <input id="language" type="text" class="form-control" value="{$LANGUAGE}" readonly>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="timezone">{$TIMEZONE_LABEL}</label>
                                                        <input id="timezone" type="text" class="form-control" value="{$TIMEZONE}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            {if isset($EMAIL_ADDRESS) || isset($LAST_IP)}
                                                <div class="form-group">
                                                    <div class="row">
                                                        {if isset($EMAIL_ADDRESS) && isset($LAST_IP)}
                                                            <div class="col-md-6">
                                                        {else}
                                                            <div class="col-md-12">
                                                        {/if}
                                                        {if isset($EMAIL_ADDRESS)}
                                                            <label for="email">{$EMAIL_ADDRESS_LABEL}</label>
                                                            <input id="email" type="email" class="form-control" value="{$EMAIL_ADDRESS}" readonly>
                                                        {/if}
                                                        {if isset($EMAIL_ADDRESS) && isset($LAST_IP)}
                                                            </div>
                                                            <div class="col-md-6">
                                                        {/if}
                                                        {if isset($LAST_IP)}
                                                            <label for="last_ip">{$LAST_IP_LABEL}</label>
                                                            <input id="last_ip" type="text" class="form-control" value="{$LAST_IP}" readonly>
                                                        {/if}
                                                        </div>
                                                    </div>
                                                </div>
                                            {/if}
                                        </div>
                                    </div>
                                </div>
                            </div>

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