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
                        <h1 class="h3 mb-0 text-gray-800">{$FORUMS}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$FORUM}</li>
                            <li class="breadcrumb-item active">{$FORUMS}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='includes/update.tpl'}

                    <div class="card shadow mb-4">
                        <div class="card-body">

                            <a href="{$NEW_CATEGORY_LINK}" class="btn btn-primary">
                                {$NEW_CATEGORY}
                            </a>
                            {if count($CATEGORIES_ARRAY)}
                                <hr>
                            {/if}

                            <!-- Success and Error Alerts -->
                            {include file='includes/alerts.tpl'}

                            {if count($CATEGORIES_ARRAY)}
                                {foreach from=$CATEGORIES_ARRAY item=category}
                                    <table class="table table-bordered table-sm">
                                        <thead>
                                            <tr style="background-color: #ededed;" class="forumEditingRow">
                                                <th style="width: 70%">
                                                    {$category.title}
                                                    {if $category.description}
                                                        <br>
                                                        <small>
                                                            {$category.description}
                                                        </small>
                                                    {/if}
                                                </th>
                                                <th class="text-right align-middle">
                                                    <a class="btn btn-primary btn-sm" href="{$category.new_forum_link}">
                                                        {$NEW_FORUM}
                                                    </a>
                                                    <a class="btn btn-secondary btn-sm" href="{$category.edit_link}">
                                                        {$EDIT}
                                                    </a>
                                                    {if count($CATEGORIES_ARRAY) > 1}
                                                        <button class="btn btn-secondary btn-sm">
                                                            <i class="fas fa-up-down-left-right"></i>
                                                        </button>
                                                    {/if}
                                                    <a class="btn btn-danger btn-sm" href="{$category.delete_link}">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {foreach from=$category.subforums item=subforum}
                                                <tr class="forumEditingRow">
                                                    <td style="width: 70%">
                                                        {$subforum.title}
                                                        {if $subforum.description}
                                                            <br>
                                                            <small>
                                                                {$subforum.description}
                                                            </small>
                                                        {/if}
                                                    </td>
                                                    <td class="text-right align-middle">
                                                        <a class="btn btn-secondary btn-sm" href="{$subforum.edit_link}">
                                                            Edit
                                                        </a>
                                                        {if count($category.subforums) > 1}
                                                            <button class="btn btn-secondary btn-sm">
                                                                <i class="fas fa-up-down-left-right"></i>
                                                            </button>
                                                        {/if}
                                                        <a class="btn btn-danger btn-sm" href="{$subforum.delete_link}">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            {/foreach}
                                        </tbody>
                                    </table>
                                {/foreach}
                            {else}
                                <p>{$NO_FORUMS}</p>
                            {/if}

{*                            {if count($FORUMS_ARRAY)}*}
{*                            <div class="table-responsive">*}
{*                                <table class="table">*}
{*                                    <tbody id="sortable">*}
{*                                        {foreach from=$FORUMS_ARRAY item=item name=forum_array}*}
{*                                        <tr data-id="{$item.id}">*}
{*                                            <td {if $item.parent_forum} style="padding-left:{math equation="x * y" x=25 y=$item.parent_forum_count}px" {/if}>*}
{*                                                <a href="{$item.edit_link}">{$item.title}</a>{if $item.parent_forum}*}
{*                                                <small>| {$item.parent_forum}</small>{/if}<br />{$item.description}*}
{*                                            </td>*}
{*                                            <td width="10%">*}
{*                                                <div class="float-md-right">*}
{*                                                    {if $item.up_link}*}
{*                                                    <form action="{$item.up_link}" method="post"*}
{*                                                        style="display: inline">*}
{*                                                        <input type="hidden" name="token" value="{$TOKEN}" />*}
{*                                                        <button class="btn btn-success btn-sm"><i*}
{*                                                                class="fas fa-chevron-up"></i></button>*}
{*                                                    </form>*}
{*                                                    {/if}*}
{*                                                    {if $item.down_link}*}
{*                                                    <form action="{$item.down_link}" method="post"*}
{*                                                        style="display: inline">*}
{*                                                        <input type="hidden" name="token" value="{$TOKEN}" />*}
{*                                                        <button class="btn btn-warning btn-sm"><i*}
{*                                                                class="fas fa-chevron-down"></i></button>*}
{*                                                    </form>*}
{*                                                    {/if}*}
{*                                                    <a href="{$item.delete_link}" class="btn btn-danger btn-sm"><i*}
{*                                                            class="fas fa-trash"></i></a>*}
{*                                                </div>*}
{*                                            </td>*}
{*                                        </tr>*}
{*                                        {/foreach}*}
{*                                    </tbody>*}
{*                                </table>*}
{*                            </div>*}
{*                            {else}*}
{*                            <p>{$NO_FORUMS}</p>*}
{*                            {/if}*}

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

    <script type="text/javascript">
        {*$(document).ready(function () {*}
        {*    $("#sortable").sortable({*}
        {*        start: function (event, ui) {*}
        {*            let start_pos = ui.item.index();*}
        {*            ui.item.data('startPos', start_pos);*}
        {*        },*}
        {*        update: function (event, ui) {*}
        {*            let forums = $("#sortable").children();*}
        {*            let toSubmit = [];*}
        {*            forums.each(function () {*}
        {*                toSubmit.push($(this).data().id);*}
        {*            });*}

        {*            $.ajax({*}
        {*                url: "{$REORDER_DRAG_URL}",*}
        {*                type: "GET",*}
        {*                data: {*}
        {*                    action: "order",*}
        {*                    dir: "drag",*}
        {*                    {literal}forums: JSON.stringify({"forums": toSubmit}){/literal}*}
        {*                },*}
        {*                success: function (response) {*}
        {*                    // Success*}
        {*                },*}
        {*                error: function (xhr) {*}
        {*                    // Error*}
        {*                    console.log(xhr);*}
        {*                }*}
        {*            });*}
        {*        }*}
        {*    });*}
        {*});*}
    </script>

</body>

</html>
