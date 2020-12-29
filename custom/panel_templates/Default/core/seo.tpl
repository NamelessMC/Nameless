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
                        <h1 class="m-0 text-dark">{$SEO}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                            <li class="breadcrumb-item active">{$SEO}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                {include file='includes/update.tpl'}
                
                {include file='includes/success.tpl'}

                {include file='includes/errors.tpl'}
		
				<div class="card">
					<div class="card-body">
						<h4 style="display:inline;">{$GOOGLE_ANALYTICS}</h4>
						<p>{$GOOGLE_ANALYTICS_HELP}</p>
						
						<form action="" method="post">
                            <div class="form-group">
                                <input type="text" class="form-control" name="analyticsid" id="inputAnalyticsId" placeholder="Google Analytics ID" value="{$GOOGLE_ANALYTICS_VALUE}">
                            </div>
							<input type="hidden" name="token" value="{$TOKEN}">
							<input type="hidden" name="type" value="google_analytics">
                            <input type="submit" class="btn btn-primary" value="Submit">
						</form>
					</div>
				</div>
							
				<div class="card">
					<div class="card-body">
						<h4 style="display:inline;">{$SITEMAP}</h4>
						{if isset($SITEMAP_LAST_GENERATED)}
							<p>{$SITEMAP_LAST_GENERATED}</p>
							<p>{$LINK}<br /><code>{$SITEMAP_FULL_LINK}</code></p>
							
							<form action="" method="post">
								<input type="hidden" name="token" value="{$TOKEN}">
								<input type="hidden" name="type" value="sitemap">
								<input type="submit" class="btn btn-primary" value="{$GENERATE}">
								<a href="{$SITEMAP_LINK}" class="btn btn-primary" download style="color:#fff;text-decoration:none">{$DOWNLOAD_SITEMAP}</a>
							</form>
						{else}
                            <p>{$SITEMAP_NOT_GENERATED}</p>
							<form action="" method="post">
								<input type="hidden" name="token" value="{$TOKEN}">
								<input type="hidden" name="type" value="sitemap">
								<input type="submit" class="btn btn-primary" value="{$GENERATE}">
							</form>
                        {/if}
					</div>
				</div>
							
				<div class="card">
					<div class="card-body">
						<h4 style="display:inline;">{$PAGE_METADATA}</h4>
                        <div class="table-responsive">
                            <table class="table table-striped dataTables-pages">
                                <thead>
                                    <tr>
                                        <th>{$PAGE_TITLE}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                {foreach from=$PAGE_LIST key=key item=item}
                                    <tr>
                                        <td><a href="{$EDIT_LINK|replace:'{x}':$item.id}">{$key|escape}</a></td>
                                    </tr>
                                {/foreach}
                                </tbody>
                            </table>
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