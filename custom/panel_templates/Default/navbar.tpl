<nav class="main-header navbar navbar-expand bg-primary navbar-light border-bottom">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{$SITE_HOME}" target="_blank" class="nav-link">{$VIEW_SITE}</a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-bell"></i>
                <span class="badge badge-danger navbar-badge">{$NOTICES|count}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                {if $NOTICES|count eq 0}
                    <span class="dropdown-item dropdown-header">{$NO_NOTICES}</span>

                {else}
                    {foreach from=$NOTICES key=url item=notice}
                        <a href="{$url}" class="dropdown-item" style="color:#6c757d!important">
                            {$notice}
                        </a>
                    {/foreach}

                {/if}
            </div>
        </li>
    </ul>
</nav>