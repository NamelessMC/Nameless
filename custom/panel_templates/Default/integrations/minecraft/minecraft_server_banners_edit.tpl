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
                        <h1 class="m-0 text-dark">{$SERVER_BANNERS}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$INTEGRATIONS}</li>
                            <li class="breadcrumb-item"><a href="{$MINECRAFT_LINK}">{$MINECRAFT}</a></li>
                            <li class="breadcrumb-item active">{$SERVER_BANNERS}</li>
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
                        <h5 style="display:inline">{$SERVER_NAME}</h5>
                        <div class="float-md-right">
                            <a href="{$BACK_LINK}" class="btn btn-primary">{$BACK}</a>
                        </div>

                        <hr />

                        {include file='includes/success.tpl'}

                        {include file='includes/errors.tpl'}

                        <form action="" method="post">
                            <label for="inputBanner">{$BANNER_BACKGROUND}</label>
                            <select name="banner" id="inputBanner" class="image-picker show-html">
                                {foreach from=$IMAGES item=image}
                                    <option data-img-src="{$image.src}" value="{$image.value}"{if $image.selected} selected{/if}>{$image.n}</option>
                                {/foreach}
                            </select>
                            <input type="hidden" name="token" value="{$TOKEN}">
                            <input type="submit" class="btn btn-primary" value="{$SUBMIT}">
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