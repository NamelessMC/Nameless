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

                                <p class="text-muted text-center">{foreach from=$USER_GROUPS item=item}{$item} {/foreach}</p>

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
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="username">{$USERNAME_LABEL}</label>
                                            <input id="username" type="text" class="form-control" value="{$USERNAME}"
                                                   readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="nickname">{$NICKNAME_LABEL}</label>
                                            <input id="nickname" type="text" class="form-control" value="{$NICKNAME}"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="title">{$USER_TITLE_LABEL}</label>
                                            <input id="title" type="text" class="form-control" value="{$USER_TITLE}"
                                                   readonly>
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
                                            <input id="language" type="text" class="form-control" value="{$LANGUAGE}"
                                                   readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="timezone">{$TIMEZONE_LABEL}</label>
                                            <input id="timezone" type="text" class="form-control" value="{$TIMEZONE}"
                                                   readonly>
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
                                                    <input id="email" type="email" class="form-control"
                                                           value="{$EMAIL_ADDRESS}" readonly>
                                                {/if}
                                                {if isset($EMAIL_ADDRESS) && isset($LAST_IP)}
                                            </div>
                                            <div class="col-md-6">
                                                {/if}
                                                {if isset($LAST_IP)}
                                                    <label for="last_ip">{$LAST_IP_LABEL}</label>
                                                    <input id="last_ip" type="text" class="form-control"
                                                           value="{$LAST_IP}" readonly>
                                                {/if}
                                            </div>
                                        </div>
                                    </div>
                                    {/if}
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