{include file='header.tpl'}

<body id="page-top">

    <!-- Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        {include file='sidebar.tpl'}

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main content -->
            <div id="content">

                <!-- Topbar -->
                {include file='navbar.tpl'}

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">{$NICKNAME}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$NICKNAME}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='includes/update.tpl'}

                    <!-- Success and Error Alerts -->
                    {include file='includes/alerts.tpl'}

                    <div class="row">
                        <div class="col-md-3">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="text-center">
                                        <img class="profile-user-img rounded-circle" src="{$AVATAR}" alt="{$USERNAME}">
                                    </div>

                                    <h4 class="text-center" style="{$USER_STYLE}">{$NICKNAME}</h4>

                                    <p class="text-muted text-center">{foreach from=$USER_GROUPS item=item}{$item}
                                        {/foreach}</p>

                                    <ul class="list-group list-group-unbordered mb-3">
                                        <li class="list-group-item">
                                            <b>{$REGISTERED}</b><br />{$REGISTERED_VALUE}
                                        </li>
                                        <li class="list-group-item">
                                            <b>{$LAST_SEEN}</b><br /><span data-toggle="tooltip"
                                                data-title="{$LAST_SEEN_FULL_VALUE}">{$LAST_SEEN_SHORT_VALUE}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item">
                                            <a class="nav-link active">{$DETAILS}</a>
                                        </li>
                                        {foreach from=$LINKS item=item}
                                        <li class="nav-item">
                                            <a class="nav-link"
                                                href="{($item.link|replace:'{id}':$USER_ID)|replace:'{username}':$USERNAME}">{$item.title}</a>
                                        </li>
                                        {/foreach}
                                    </ul>
                                    <hr />
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="username">{$USERNAME_LABEL}</label>
                                                <input id="username" type="text" class="form-control" value="{$USERNAME}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nickname">{$NICKNAME_LABEL}</label>
                                                <input id="nickname" type="text" class="form-control" value="{$NICKNAME}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="title">{$USER_TITLE_LABEL}</label>
                                                <input id="title" type="text" class="form-control" value="{$USER_TITLE}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="language">{$LANGUAGE_LABEL}</label>
                                                <input id="language" type="text" class="form-control" value="{$LANGUAGE}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="timezone">{$TIMEZONE_LABEL}</label>
                                                <input id="timezone" type="text" class="form-control" value="{$TIMEZONE}" readonly>
                                            </div>
                                        </div>
                                        {if isset($EMAIL_ADDRESS)}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">{$EMAIL_ADDRESS_LABEL}</label>
                                                <input id="email" type="email" class="form-control" value="{$EMAIL_ADDRESS}" readonly>
                                            </div>
                                        </div>
                                        {/if}
                                        {if isset($LAST_IP)}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="last_ip">{$LAST_IP_LABEL}</label>
                                                <input id="last_ip" type="text" class="form-control" value="{$LAST_IP}"
                                                    readonly>
                                            </div>
                                        </div>
                                        {/if}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="template">{$TEMPLATE_LABEL}</label>
                                                <input id="template" type="text" class="form-control" value="{$TEMPLATE}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <h5>{$PROFILE_FIELDS_LABEL}</h5>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>{$NAME}</th>
                                                    <th>{$CONTENT}</th>
                                                    <th>{$UPDATED}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {foreach from=$ALL_PROFILE_FIELDS item=field}
                                                <tr>
                                                    <td>
                                                        {$field->name}
                                                    </td>
                                                    <td>
                                                        {if $USER_PROFILE_FIELDS[$field->id]->value}
                                                        {$USER_PROFILE_FIELDS[$field->id]->value}
                                                        {else}
                                                        <i>{$NOT_SET}</i>
                                                        {/if}
                                                    </td>
                                                    <td>
                                                        {if $USER_PROFILE_FIELDS[$field->id]->updated}
                                                        {$USER_PROFILE_FIELDS[$field->id]->updated()}
                                                        {else}
                                                        <i>{$NOT_SET}</i>
                                                        {/if}
                                                    </td>
                                                </tr>
                                                {foreachelse}
                                                <tr>
                                                    <td colspan="3" style="text-align:center;">{$NO_PROFILE_FIELDS}</td>
                                                </tr>
                                                {/foreach}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Spacing -->
                    <div style="height:1rem;"></div>

                    <!-- End Page Content -->
                </div>

                <!-- End Main Content -->
            </div>

            {include file='footer.tpl'}

            <!-- End Content Wrapper -->
        </div>

        <!-- End Wrapper -->
    </div>

    {include file='scripts.tpl'}

</body>

</html>
