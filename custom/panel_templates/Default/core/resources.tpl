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
                        <h1 class="h3 mb-0 text-gray-800">{$RESOURCES}</h1>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{$PANEL_INDEX}">{$DASHBOARD}</a></li>
                            <li class="breadcrumb-item active">{$RESOURCES}</li>
                        </ol>
                    </div>

                    <!-- Update Notification -->
                    {include file='includes/update.tpl'}

                    <!-- Success and Error Alerts -->
                    {include file='includes/alerts.tpl'}

                    <!-- Resources Notice -->
                    <div class="alert alert-info shadow-sm mb-4" role="alert">
                        <div class="d-flex">
                            <div class="alert-icon">
                                <i class="fas fa-info-circle fa-2x text-info"></i>
                            </div>
                            <div class="ml-3">
                                <h5 class="alert-heading mb-2">
                                    Browse All Resources
                                </h5>
                                <p class="mb-2">
                                    The resources shown here may not include all available modules and templates.
                                    For the complete collection and latest updates, visit our resources website.
                                </p>
                                <a href="https://namelessmc.com/resources" target="_blank" class="btn btn-info btn-sm">
                                    <i class="fas fa-external-link-alt"></i> Visit NamelessMC Resources
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Modules Section -->
                    {if isset($MODULES) && count($MODULES)}
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-puzzle-piece"></i> {$MODULES_TITLE}
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                {foreach from=$MODULES item=module}
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card h-100 shadow-sm">
                                        {if $module.image_url}
                                        <img src="{$module.image_url}" class="card-img-top" alt="{$module.name}" style="height: 200px; object-fit: cover;">
                                        {else}
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                            <i class="fas fa-puzzle-piece fa-3x text-muted"></i>
                                        </div>
                                        {/if}
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">{$module.name}</h5>
                                            <p class="card-text text-muted small mb-2">
                                                <i class="fas fa-user"></i> {$module.author.username}
                                                {if $module.latest_version}
                                                <br><i class="fas fa-tag"></i> {$module.latest_version}
                                                {/if}
                                            </p>
                                            <p class="card-text flex-grow-1">{$module.short_description}</p>
                                            <div class="mt-auto">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <small class="text-muted">
                                                        <i class="fas fa-download"></i> {$module.downloads}
                                                        <i class="fas fa-eye ml-2"></i> {$module.views}
                                                    </small>
                                                    <div class="star-rating-small">
                                                        {for $i=1 to 5}
                                                        <i class="fa{if $i <= ($module.rating)}s{else}r{/if} fa-star text-warning"></i>
                                                        {/for}
                                                    </div>
                                                </div>
                                                <div class="btn-group btn-group-sm w-100" role="group">
                                                    <a href="{$module.url}" target="_blank" class="btn btn-outline-primary">
                                                        <i class="fas fa-external-link-alt"></i> {$VIEW}
                                                    </a>
                                                    {if $module.downloadable_via_api}
                                                    <button class="btn btn-success" onclick="installResource('{$module.id}', 'module', '{$module.name}')">
                                                        <i class="fas fa-download"></i> {$INSTALL}
                                                    </button>
                                                    {/if}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {/foreach}
                            </div>
                        </div>
                    </div>
                    {/if}

                    <!-- Templates Section -->
                    {if isset($TEMPLATES) && count($TEMPLATES)}
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-paintbrush"></i> {$TEMPLATES_TITLE}
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                {foreach from=$TEMPLATES item=template}
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card h-100 shadow-sm">
                                        {if $template.image_url}
                                        <img src="{$template.image_url}" class="card-img-top" alt="{$template.name}" style="height: 200px; object-fit: cover;">
                                        {else}
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                            <i class="fas fa-paintbrush fa-3x text-muted"></i>
                                        </div>
                                        {/if}
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">{$template.name}</h5>
                                            <p class="card-text text-muted small mb-2">
                                                <i class="fas fa-user"></i> {$template.author.username}
                                                {if $template.latest_version}
                                                <br><i class="fas fa-tag"></i> {$template.latest_version}
                                                {/if}
                                            </p>
                                            <p class="card-text flex-grow-1">{$template.short_description}</p>
                                            <div class="mt-auto">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <small class="text-muted">
                                                        <i class="fas fa-download"></i> {$template.downloads}
                                                        <i class="fas fa-eye ml-2"></i> {$template.views}
                                                    </small>
                                                    <div class="star-rating-small">
                                                        {for $i=1 to 5}
                                                        <i class="fa{if $i <= ($template.rating)}s{else}r{/if} fa-star text-warning"></i>
                                                        {/for}
                                                    </div>
                                                </div>
                                                <div class="btn-group btn-group-sm w-100" role="group">
                                                    <a href="{$template.url}" target="_blank" class="btn btn-outline-primary">
                                                        <i class="fas fa-external-link-alt"></i> {$VIEW}
                                                    </a>
                                                    {if $template.downloadable_via_api}
                                                    <button class="btn btn-success" onclick="installResource('{$template.id}', 'template', '{$template.name}')">
                                                        <i class="fas fa-download"></i> {$INSTALL}
                                                    </button>
                                                    {/if}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {/foreach}
                            </div>
                        </div>
                    </div>
                    {/if}

                    <!-- No Resources Found -->
                    {if !count($MODULES) && !count($TEMPLATES)}
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="text-center py-5">
                                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">{$NO_RESOURCES_FOUND}</h5>
                                <p class="text-muted">{$NO_RESOURCES_DESCRIPTION}</p>
                            </div>
                        </div>
                    </div>
                    {/if}

                    <!-- Spacing -->
                    <div style="height:1rem;"></div>

                    <!-- End Page Content -->
                </div>

                <!-- End Main Content -->
            </div>

            {include file='footer.tpl'}

            <!-- End Content Wrapper -->
        </div>

        <!-- Installation Confirmation Modal -->
        <div class="modal fade" id="installResourceModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-download"></i> {$INSTALL_RESOURCE}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{$INSTALL_RESOURCE_API_LINK}" method="post" id="installResourceForm">
                        <div class="modal-body">
                            <div class="text-center mb-3">
                                <i class="fas fa-download fa-3x text-primary mb-3"></i>
                                <h5 id="installResourceName"></h5>
                                <p class="text-muted" id="installResourceDescription"></p>
                            </div>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> {$INSTALL_RESOURCE_WARNING}
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="token" value="{$TOKEN}">
                            <input type="hidden" name="resource_id" id="resourceId">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times"></i> {$CANCEL}
                            </button>
                            <button type="submit" class="btn btn-success" id="confirmInstallBtn">
                                <i class="fas fa-download"></i> {$INSTALL}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- End Wrapper -->
    </div>

    {include file='scripts.tpl'}

    <script type="text/javascript">
        // Resource installation functionality
        function installResource(resourceId, resourceType, resourceName) {
            document.getElementById('resourceId').value = resourceId;
            document.getElementById('installResourceName').textContent = resourceName;

            const description = resourceType === 'module' ?
                '{$INSTALL_MODULE_DESCRIPTION}' :
                '{$INSTALL_TEMPLATE_DESCRIPTION}';
            document.getElementById('installResourceDescription').textContent = description;

            $('#installResourceModal').modal().show();
        }

        // Handle form submission with vanilla JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('installResourceForm');
            const confirmBtn = document.getElementById('confirmInstallBtn');

            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                // Show loading state
                confirmBtn.disabled = true;
                confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {$INSTALLING}...';

                // Get form data
                const formData = new FormData(form);

                // Make fetch request
                fetch('{$INSTALL_RESOURCE_API_LINK}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                {literal}
                .then(response => response.json().then(data => ({ok: response.ok, body: data})))
                {/literal}
                .then(data => {
                    $('#installResourceModal').modal().hide();

                    if (data.ok) {
                        showAlert('success', data.body.message);
                    } else {
                        showAlert('danger', data.body.error);
                    }

                    // Reset button state
                    resetButton();
                });
            });

            function resetButton() {
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = '<i class="fas fa-download"></i> {$INSTALL}';
            }

            {literal}
                function showAlert(type, message) {
                    const iconClass = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle';
                    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';

                    const alertHtml = `
                        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                            <i class="${iconClass}"></i> ${message}
                        </div>
                    `;

                    // Insert alert after the page heading
                    const pageHeading = document.querySelector('.container-fluid .d-sm-flex');
                    pageHeading.insertAdjacentHTML('afterend', alertHtml);

                    // Scroll to top to show the message
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            {/literal}
        });
    </script>

</body>

</html>
