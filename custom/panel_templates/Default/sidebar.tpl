<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a href="{$PANEL_INDEX}" class="sidebar-brand d-flex align-items-center justify-content-center">
        <div class="sidebar-brand-icon">
            <img src="{if isset($PANEL_LOGO_IMAGE)}{$PANEL_LOGO_IMAGE}{else}{$NAMELESS_LOGO}{/if}"
                style="height: 35px;">
        </div>
        <div class="sidebar-brand-text mx-3" style="margin-top: 4px">{$SITE_NAME}</div>
    </a>

    <!-- Sidebar - Links -->
    {foreach from=$NAV_LINKS key=name item=item}
    {if isset($item.link) && $item.link eq "divider"}
    <hr class="sidebar-divider">
    <div class="sidebar-heading">{$item.title}</div>
    {else}
    {if isset($item.items)}
    <li class="nav-item{if ($PAGE eq $name) || ($PARENT_PAGE eq $name)} active{/if}">
        <a class="nav-link {if isset($PARENT_PAGE) && $PARENT_PAGE eq $name}{else}collapsed{/if}{if isset($PARENT_PAGE) && $PARENT_PAGE eq $name} active{/if}"
            href="#" data-toggle="collapse" data-target="#{$item.title|strip:'&nbsp;'}" aria-expanded="true"
            aria-controls="{$item.title|strip:'&nbsp;'}">
            {$item.icon}<span>{$item.title}</span>
        </a>
        <div id="{$item.title|strip:'&nbsp;'}"
            class="collapse{if isset($PARENT_PAGE) && $PARENT_PAGE eq $name} show{/if}"
            aria-labelledby="{$item.title|strip:'&nbsp;'}" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                {if count($item.items)}
                {foreach from=$item.items key=subKey item=subItem}
                <a class="collapse-item {if $PAGE eq $subKey} active{/if}" href="{$subItem.link}"
                    target="{$subItem.target}">{$subItem.icon} {$subItem.title}</a>
                {/foreach}
                {/if}
            </div>
        </div>
    </li>
    {else}
    <li class="nav-item{if $PAGE eq $name} active{/if}">
        <a class="nav-link" href="{$item.link}" target="{$item.target}">
            {$item.icon}<span>{$item.title}</span>
        </a>
    </li>
    {/if}
    {/if}
    {/foreach}

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->