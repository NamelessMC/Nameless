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
                        <h1 class="m-0 text-dark">{$NAVIGATION}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$CONFIGURATION}</li>
                            <li class="breadcrumb-item active">{$NAVIGATION}</li>
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
                                <div class="callout callout-info">
                                    <h5><i class="icon fa fa-info-circle"></i> {$INFO}</h5>
                                    <p>{$NAVBAR_ORDER_INSTRUCTIONS}</p>
                                    <p>{$NAVBAR_ICON_INSTRUCTIONS}</p>
                                </div>
                                {foreach from=$NAV_ITEMS key=key item=item}
                                    <h4>{$item.title|escape}</h4>

                                    <div class="form-group">
										<div class="row">
											<div class="col-md-6">
												<label for="input{$item.title|escape}">{$NAVBAR_ORDER}</label>
												<input type="number" min="1" class="form-control"
													   id="input{$item.title|escape}"
													   name="inputOrder[{if isset($item.custom) && is_numeric($item.custom)}{$item.custom}{else}{$key}{/if}]"
													   value="{$item.order|escape}">
											</div>
											<div class="col-md-6">
												<label for="input{$item.title|escape}Icon">{$NAVBAR_ICON}</label>
												<input type="text" class="form-control"
													   id="input{$item.title|escape}Icon"
													   name="inputIcon[{if isset($item.custom) && is_numeric($item.custom)}{$item.custom}{else}{$key}{/if}]"
													   value="{$item.icon|escape}">
											</div>
										</div>
                                    </div>

                                    {if isset($item.items) && count($item.items)}
                                    <br>
                                        <strong>{$item.title|escape} &raquo; {$DROPDOWN_ITEMS}</strong><br />

                                        {foreach from=$item.items key=dropdown_key item=dropdown_item}
                                            <strong>{$dropdown_item.title|escape}</strong>

                                            <div class="form-group">
												<div class="row">
													<div class="col-md-6">
														<label for="input{$dropdown_item.title|escape}">{$NAVBAR_ORDER}</label>
														<input type="number" min="1" class="form-control" id="input{$dropdown_item.title|escape}" name="inputOrder[{if isset($dropdown_item.custom) && is_numeric($dropdown_item.custom)}{$dropdown_item.custom}{else}{$dropdown_key}{/if}]" value="{$dropdown_item.order|escape}">
													</div>
													<div class="col-md-6">
														<label for="input{$dropdown_item.title|escape}Icon">{$NAVBAR_ICON}</label>
														<input type="text" class="form-control"
															   id="input{$dropdown_item.title|escape}Icon"
															   name="inputIcon[{if isset($dropdown_item.custom) && is_numeric($dropdown_item.custom)}{$dropdown_item.custom}{else}{$dropdown_key}{/if}]"
															   value="{$dropdown_item.icon|escape}">
													</div>
												</div>
                                            </div>

                                        {/foreach}
                                    {/if}

                                    <hr>

                                {/foreach}

                                <div class="form-group">
                                    <label for="dropdown_name">{$DROPDOWN_NAME}</label>
                                    <input type="text" class="form-control" id="dropdown_name" name="dropdown_name" value="{$DROPDOWN_NAME_VALUE}">
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="token" value="{$TOKEN}">
                                    <input type="submit" value="{$SUBMIT}" class="btn btn-primary">
                                </div>
                            </form>

                        </div>
                    </div>

                    <!-- Spacing -->
                    <div style="height:1rem;"></div>

                </div>
            </div>
        </section>
    </div>

    {include file='footer.tpl'}

</div>
<!-- ./wrapper -->

{include file='scripts.tpl'}

</body>
</html>
