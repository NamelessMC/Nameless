<?php
/**
 * Staff panel resources page
 *
 * @author Aberdeener
 * @license MIT
 * @version 2.3.0
 *
 * @var Cache $cache
 * @var FakeSmarty $smarty
 * @var Language $language
 * @var Navigation $cc_nav
 * @var Navigation $navigation
 * @var Navigation $staffcp_nav
 * @var Pages $pages
 * @var TemplateBase $template
 * @var User $user
 * @var Widgets $widgets
 */

if (!$user->handlePanelPageLoad('admincp.modules')) {
    require_once ROOT_PATH . '/403.php';
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'resources';
const PANEL_PAGE = 'resources';
$page_title = $language->get('admin', 'resources');
require_once ROOT_PATH . '/core/templates/backend_init.php';

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$externalResources = $container->get(ExternalResources::class);

if (empty($_POST)) {
    // Show all resources
    $externalResources->bustCache();

    $modules = $externalResources->getAllModules();
    $templates = $externalResources->getAllTemplates();

    $template->getEngine()->addVariables([
        'MODULES' => $modules,
        'TEMPLATES' => $templates,
        'INSTALL_RESOURCE' => $language->get('admin', 'install'),
        'INSTALL_RESOURCE_LINK' => URL::build('/panel/core/resources/', 'action=install'),
        'AVAILABLE_RESOURCES' => 'Available Resources',
        'MODULES_TITLE' => 'Modules',
        'TEMPLATES_TITLE' => 'Templates',
        'VIEW' => $language->get('general', 'view'),
        'INSTALL' => $language->get('admin', 'install'),
        'NO_RESOURCES_FOUND' => 'No Resources Found',
        'NO_RESOURCES_DESCRIPTION' => 'No resources are available at this time. Please check back later.',
        'INSTALL_MODULE_DESCRIPTION' => 'This will download and install the module to your NamelessMC installation.',
        'INSTALL_TEMPLATE_DESCRIPTION' => 'This will download and install the template to your NamelessMC installation.',
        'INSTALL_RESOURCE_API_LINK' => URL::build('/panel/core/resources/', 'action=install'),
        'INSTALL_RESOURCE_WARNING' => 'Please ensure you trust the resource author before installing. Only install resources from trusted sources.',
        'CANCEL' => $language->get('general', 'cancel'),
        'INSTALLING' => 'Installing',
    ]);
} else {
    // Handle resource installation via API

    // Set JSON response header
    header('Content-Type: application/json');

    // Verify CSRF token
    if (!Token::check($_POST['token'])) {
        http_response_code(403);
        die(json_encode(['error' => 'Invalid token']));
    }

    // Check if user has permission
    if (!$user->hasPermission('admincp.modules')) {
        http_response_code(403);
        die(json_encode(['error' => 'You do not have permission to install resources']));
    }

    // Get resource ID from POST data
    if (!isset($_POST['resource_id'])) {
        http_response_code(400);
        die(json_encode(['error' => 'Resource ID is required']));
    }

    $resourceId = (int) $_POST['resource_id'];

    try {
        // TODO: Check if resource already installed

        // Simulate installation process
        // In real implementation, this would:
        // 1. Download the resource from the API
        // 2. Extract the resource files
        // 3. Move files to the appropriate directories
        // 5. Return success response and refresh client page to run module installation script

        $externalResources->installResource($resourceId);

        // Return success response
        die(json_encode([
            'success' => true,
            'message' => 'Resource installed successfully'
        ]));
    } catch (Exception $e) {
        // Return error response
        http_response_code(500);
        die(json_encode([
            'error' => $e->getMessage()
        ]));
    }
}

$template->getEngine()->addVariables([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'RESOURCES' => $language->get('admin', 'resources'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
]);

$template->onPageLoad();

require ROOT_PATH . '/core/templates/panel_navbar.php';

// Display template
$template->displayTemplate('core/resources');
