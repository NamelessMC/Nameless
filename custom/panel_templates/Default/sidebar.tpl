<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{$PANEL_INDEX}" class="brand-link">
        <span class="brand-text font-weight-light">{$SITE_NAME}</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{$LOGGED_IN_USER.avatar}" class="img-circle elevation-2" alt="{$LOGGED_IN_USER.username}">
            </div>
            <div class="info">
                <a href="{$LOGGED_IN_USER.panel_profile}" class="d-block">{$LOGGED_IN_USER.nickname}</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                {foreach from=$NAV_LINKS key=name item=item}
                    {if isset($item.link) && $item.link eq "divider"}
                        <li class="nav-header">{$item.title}</li>
                    {else}
                        {if isset($item.items)}
                            {* Dropdown *}
                            <li class="nav-item has-treeview{if isset($PARENT_PAGE) && $PARENT_PAGE eq $name} menu-open{/if}">
                                <a class="nav-link{if isset($PARENT_PAGE) && $PARENT_PAGE eq $name} active{/if}" style="cursor:pointer;">
                                    {$item.icon}
                                    <p>
                                        {$item.title}
                                        <i class="right fa fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    {if count($item.items)}
                                        {foreach from=$item.items key=subKey item=subItem}
                                            <li class="nav-item">
                                                <a href="{$subItem.link}" style="margin-left:10px;" class="nav-link{if $PAGE eq $subKey} active{/if}">
                                                    {$subItem.icon}
                                                    <p>{$subItem.title}</p>
                                                </a>
                                            </li>
                                        {/foreach}
                                    {/if}
                                </ul>
                            </li>
                        {else}
                            {* Normal link *}
                            <li class="nav-item">
                                <a class="nav-link{if $PAGE eq $name} active{/if}" href="{$item.link}" target="{$item.target}">
                                    {$item.icon}
                                    <p>
                                        {$item.title}
                                    </p>
                                </a>
                            </li>
                        {/if}
                    {/if}
                {/foreach}
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
