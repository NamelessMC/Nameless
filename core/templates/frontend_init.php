<?php
/**
 * Frontend initialisation.
 *
 * @author Samerton
 * @license MIT
 * @version 2.2.0
 *
 * @var Cache    $cache
 * @var Language $language
 * @var string   $page_title
 * @var User     $user
 */
const FRONT_END = true;

// Set current page URL in session, provided it's not the login page
if (
    defined('PAGE') &&
    PAGE != 'login' && PAGE != 'register' &&
    PAGE != 404 &&
    PAGE != 'maintenance' &&
    PAGE != 'oauth' &&
    (!isset($_GET['route']) || !str_contains($_GET['route'], '/queries'))
) {
    if (FRIENDLY_URLS === true) {
        $split = explode('?', $_SERVER['REQUEST_URI']);

        if (count($split) > 1) {
            $_SESSION['last_page'] = URL::build($split[0], $split[1]);
        } else {
            $_SESSION['last_page'] = URL::build($split[0]);
        }

        if (defined('CONFIG_PATH')) {
            $_SESSION['last_page'] = substr($_SESSION['last_page'], strlen(CONFIG_PATH));
        }
    } else {
        $_SESSION['last_page'] = URL::build($_GET['route'] ?? '/');
    }
}

// Check if any integrations is required before user can continue
if ($user->isLoggedIn() && defined('PAGE') && PAGE != 'cc_connections' && PAGE != 'oauth') {
    foreach (Integrations::getInstance()->getEnabledIntegrations() as $integration) {
        if ($integration->data()->required && $integration->allowLinking()) {
            $integrationUser = $user->getIntegration($integration->getName());
            if ($integrationUser === null || !$integrationUser->isVerified()) {
                Session::flash('connections_error', $language->get('user', 'integration_required_to_continue'));
                Redirect::to(URL::build('/user/connections'));
            }
        }
    }
}

if (defined('PAGE') && PAGE != 404) {
    // Auto unset signin tfa variables if set
    if (
        !str_contains($_GET['route'], '/queries/') &&
        (isset($_SESSION['remember']) || isset($_SESSION['username']) || isset($_SESSION['email']) || isset($_SESSION['password'])) &&
        !isset($_POST['tfa_code'])
    ) {
        unset($_SESSION['remember'], $_SESSION['username'], $_SESSION['email'], $_SESSION['password']);
    }
}

if (file_exists(ROOT_PATH . '/custom/templates/' . TEMPLATE . '/template.php')) {
    /** @var TemplateBase $template */
    require(ROOT_PATH . '/custom/templates/' . TEMPLATE . '/template.php');
} else {
    /** @var TemplateBase $template */
    require(ROOT_PATH . '/custom/templates/DefaultRevamp/template.php');
}

// Basic template variables
$template->getEngine()->addVariables([
    'CONFIG_PATH' => defined('CONFIG_PATH') ? CONFIG_PATH . '/' : '/',
    'OG_URL' => Output::getClean(rtrim(URL::getSelfURL(), '/') . $_SERVER['REQUEST_URI']),
    'SITE_NAME' => Output::getClean(SITE_NAME),
    'SITE_HOME' => URL::build('/'),
    'USER_INFO_URL' => URL::build('/queries/user/', 'id='),
    'GUEST' => $language->get('user', 'guest'),
]);

$cache->setCache('backgroundcache');
if ($cache->isCached('og_image')) {
    // Assign the image value now, some pages may override it (via Page Metadata config)
    $template->getEngine()->addVariable('OG_IMAGE', rtrim(URL::getSelfURL(), '/') . $cache->retrieve('og_image'));
}

// User related actions
if ($user->isLoggedIn()) {
    // Warnings
    $warnings = DB::getInstance()->get('infractions', ['punished', $user->data()->id])->results();
    if (count($warnings)) {
        foreach ($warnings as $warning) {
            if ($warning->revoked == 0 && $warning->acknowledged == 0) {
                $template->getEngine()->addVariables([
                    'GLOBAL_WARNING_TITLE' => $language->get('user', 'you_have_received_a_warning'),
                    'GLOBAL_WARNING_REASON' => Output::getClean($warning->reason),
                    'GLOBAL_WARNING_ACKNOWLEDGE' => $language->get('user', 'acknowledge'),
                    'GLOBAL_WARNING_ACKNOWLEDGE_LINK' => URL::build('/user/acknowledge/' . urlencode($warning->id)),
                ]);
                break;
            }
        }
    }

    // Does the account need verifying?
    // Get default group ID
    $cache->setCache('default_group');
    if ($cache->isCached('default_group')) {
        $default_group = $cache->retrieve('default_group');
    } else {
        try {
            $default_group = Group::find(1, 'default_group')->id;
        } catch (Exception $e) {
            $default_group = 1;
        }

        $cache->store('default_group', $default_group);
    }
}

// Page metadata
if (isset($_GET['route']) && $_GET['route'] != '/') {
    $route = rtrim($_GET['route'], '/');
} else {
    $route = '/';
}

if (!defined('PAGE_DESCRIPTION')) {
    $page_metadata = DB::getInstance()->get('page_descriptions', ['page', $route]);
    if ($page_metadata->count()) {
        $page_metadata = $page_metadata->first();
        $template->getEngine()->addVariables([
            'PAGE_DESCRIPTION' => str_replace('{site}', Output::getClean(SITE_NAME), addslashes(strip_tags($page_metadata->description))),
            'PAGE_KEYWORDS' => addslashes(strip_tags($page_metadata->tags)),
        ]);

        $og_image = $page_metadata->image;
        if ($og_image) {
            $template->getEngine()->addVariable('OG_IMAGE', rtrim(URL::getSelfURL(), '/') . $og_image);
        }
    } else {
        $template->getEngine()->addVariables([
            'PAGE_DESCRIPTION' => str_replace('{site}', Output::getClean(SITE_NAME), addslashes(strip_tags(Settings::get('default_meta_description', '')))),
            'PAGE_KEYWORDS' => addslashes(strip_tags(Settings::get('default_meta_keywords', ''))),
        ]);
    }
} else {
    $template->getEngine()->addVariables([
        'PAGE_DESCRIPTION' => str_replace('{site}', Output::getClean(SITE_NAME), addslashes(strip_tags(PAGE_DESCRIPTION))),
        'PAGE_KEYWORDS' => (defined('PAGE_KEYWORDS') ? addslashes(strip_tags(PAGE_KEYWORDS)) : ''),
    ]);
}

$template->getEngine()->addVariable('TITLE', $page_title);

$cache->setCache('backgroundcache');

$banner_image = $cache->retrieve('banner_image');

if (!empty($banner_image)) {
    $template->getEngine()->addVariable('BANNER_IMAGE', Output::getClean($banner_image));
}

$logo_image = $cache->retrieve('logo_image');

if (!empty($logo_image)) {
    $template->getEngine()->addVariable('LOGO_IMAGE', Output::getClean($logo_image));
}

$favicon_image = $cache->retrieve('favicon_image');

if (!empty($favicon_image)) {
    $template->getEngine()->addVariable('FAVICON', Output::getClean($favicon_image));
}

$analytics_id = Settings::get('ga_script');
if ($analytics_id) {
    $template->getEngine()->addVariable('ANALYTICS_ID', Output::getClean($analytics_id));
}

$template->getEngine()->addVariables([
    'FOOTER_LINKS_TITLE' => $language->get('general', 'links'),
    'FOOTER_SOCIAL_TITLE' => $language->get('general', 'social'),
    'AUTO_LANGUAGE_TEXT' => $language->get('general', 'auto_language'),
    'ENABLED' => $language->get('user', 'enabled'),
    'DISABLED' => $language->get('user', 'disabled'),
    'DARK_LIGHT_MODE_ACTION' => URL::build('/queries/dark_light_mode'),
    'DARK_LIGHT_MODE_TOKEN' => $user->isLoggedIn() ? Token::get() : null,
]);

if (defined('BYPASS_MAINTENANCE')) {
    $template->getEngine()->addVariable('MAINTENANCE_ENABLED', $language->get('admin', 'maintenance_enabled'));
}

if (defined('AUTO_LANGUAGE')) {
    $template->getEngine()->addVariable('AUTO_LANGUAGE', AUTO_LANGUAGE);
}

if ($user->isLoggedIn()) {
    // Basic user variables
    $template->getEngine()->addVariable('LOGGED_IN_USER', [
        'username' => $user->getDisplayname(true),
        'nickname' => $user->getDisplayname(),
        'profile' => $user->getProfileURL(),
        'panel_profile' => URL::build('/panel/user/' . urlencode($user->data()->id) . '-' . urlencode($user->data()->username)),
        'username_style' => $user->getGroupStyle(),
        'user_title' => Output::getClean($user->data()->user_title),
        'avatar' => $user->getAvatar(),
        'integrations' => $user_integrations ?? [],
    ]);

    // Panel access?
    if ($user->canViewStaffCP()) {
        $template->getEngine()->addVariables([
            'PANEL_LINK' => URL::build('/panel'),
            'PANEL' => $language->get('moderator', 'staff_cp'),
        ]);
    }
}

// Initialise widgets
$widgets = new Widgets($cache, $language, $template);

// TODO: remove in 2.3.0
$smarty = new FakeSmarty($template->getEngine());
