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
                {include file='includes/update.tpl'}

                <div class="card">
                    <div class="card-body">

                        {include file='includes/success.tpl'}

                        {include file='includes/errors.tpl'}

                        <form action="" method="post">
                            <div class="form-group">
                                <label for="inputNewForum">{$MOVE_TOPICS_AND_POSTS_TO}</label>
                                <select id="inputNewForum" class="form-control" name="move_forum">
                                    <option value="none" selected>{$DELETE_TOPICS_AND_POSTS}</option>
                                    {foreach from=$OTHER_FORUMS item=item}
                                        <option value="{$item.id}">{$item.name}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <input type="hidden" name="token" value="{$TOKEN}">
                            <input type="hidden" name="confirm" value="true">
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

</body>
</html>