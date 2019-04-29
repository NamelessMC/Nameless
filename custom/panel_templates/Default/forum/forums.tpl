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
                        <h1 class="m-0 text-dark">{$FORUM}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$FORUM}</li>
                            <li class="breadcrumb-item active">{$FORUMS}</li>
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
                            <a href="{$NEW_FORUM_LINK}" class="btn btn-primary">{$NEW_FORUM}</a>
                            <hr />

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

                            {if count($FORUMS_ARRAY)}
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody id="sortable">
                                        {foreach from=$FORUMS_ARRAY item=item name=forum_array}
                                            <tr data-id="{$item.id}">
                                                <td{if $item.parent_forum} style="padding-left:{math equation="x * y" x=25 y=$item.parent_forum_count}px"{/if}>
                                                    <a href="{$item.edit_link}">{$item.title}</a>{if $item.parent_forum} <small>| {$item.parent_forum}</small>{/if}<br />{$item.description}
                                                </td>
                                                <td>
                                                    <div class="float-md-right">
                                                        {if $item.up_link}
                                                            <a href="{$item.up_link}" class="btn btn-success btn-sm"><i class="fas fa-chevron-up"></i></a>
                                                        {/if}
                                                        {if $item.down_link}
                                                            <a href="{$item.down_link}" class="btn btn-warning btn-sm"><i class="fas fa-chevron-down"></i></a>
                                                        {/if}
                                                        <a href="{$item.delete_link}" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        {/foreach}
                                        </tbody>
                                    </table>
                                </div>
                            {else}
                                <p>{$NO_FORUMS}</p>
                            {/if}

                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="InputEnabled">{$USE_REACTIONS}</label>
                                    <input type="checkbox" name="enabled" id="InputEnabled" class="js-switch"{if $USE_REACTIONS_VALUE} checked{/if} />
                                </div>
                                <input type="hidden" name="token" value="{$TOKEN}">
                                <input type="submit" class="btn btn-primary" value="{$SUBMIT}" />
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

<script type="text/javascript">
    $(document).ready(function(){
        $("#sortable").sortable({
            start: function(event, ui) {
                let start_pos = ui.item.index();
                ui.item.data('startPos', start_pos);
            },
            update: function(event, ui){
                let forums = $("#sortable").children();
                let toSubmit = [];
                forums.each(function(){
                    toSubmit.push($(this).data().id);
                });

                $.ajax({
                    url: "{$REORDER_DRAG_URL}",
                    type: "GET",
                    data: {
                        action: "order",
                        dir: "drag",
                        {literal}forums: JSON.stringify({"forums": toSubmit}){/literal}
                    },
                    success: function(response) {
                        // Success
                    },
                    error: function(xhr) {
                        // Error
                        console.log(xhr);
                    }
                });
            }
        });
    });
</script>

</body>
</html>