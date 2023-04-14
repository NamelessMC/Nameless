<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.3
 *
 *  License: MIT
 *
 *  Frontend template initialisation
 */

const FRONT_END = true;

// Set current page URL in session, provided it's not the login page
if (defined('PAGE') && PAGE != 'login' && PAGE != 'register' && PAGE != 404 && PAGE != 'maintenance' && PAGE != 'oauth' && (!isset($_GET['route']) || !str_contains($_GET['route'], '/queries'))) {
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
if ($user->isLoggedIn() && defined('PAGE') && PAGE != 'cc_connections') {
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
    if (!str_contains($_GET['route'], '/queries/') && (isset($_SESSION['remember']) || isset($_SESSION['username']) || isset($_SESSION['email']) || isset($_SESSION['password'])) && !isset($_POST['tfa_code'])) {
        unset($_SESSION['remember'], $_SESSION['username'], $_SESSION['email'], $_SESSION['password']);
    }
}

$smarty->setCompileDir(ROOT_PATH . '/cache/templates_c');

if (file_exists(ROOT_PATH . '/custom/templates/' . TEMPLATE . '/template.php')) {
    $smarty->setTemplateDir(ROOT_PATH . '/custom/templates/' . TEMPLATE);

    require(ROOT_PATH . '/custom/templates/' . TEMPLATE . '/template.php');
} else {
    $smarty->setTemplateDir(ROOT_PATH . '/custom/templates/DefaultRevamp');

    require(ROOT_PATH . '/custom/templates/DefaultRevamp/template.php');
}

// User related actions
if ($user->isLoggedIn()) {
    // Warnings
    $warnings = DB::getInstance()->get('infractions', ['punished', $user->data()->id])->results();
    if (count($warnings)) {
        foreach ($warnings as $warning) {
            if ($warning->revoked == 0 && $warning->acknowledged == 0) {
                $smarty->assign([
                    'GLOBAL_WARNING_TITLE' => $language->get('user', 'you_have_received_a_warning'),
                    'GLOBAL_WARNING_REASON' => Output::getClean($warning->reason),
                    'GLOBAL_WARNING_ACKNOWLEDGE' => $language->get('user', 'acknowledge'),
                    'GLOBAL_WARNING_ACKNOWLEDGE_LINK' => URL::build('/user/acknowledge/' . urlencode($warning->id))
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
        $smarty->assign([
            'PAGE_DESCRIPTION' => str_replace('{site}', Output::getClean(SITE_NAME), Output::getPurified($page_metadata->description)),
            'PAGE_KEYWORDS' => Output::getPurified($page_metadata->tags),
        ]);

        $og_image = $page_metadata->image;
        if ($og_image) {
            $smarty->assign('OG_IMAGE', rtrim(URL::getSelfURL(), '/') . $og_image);
        }
    }
} else {
    $smarty->assign([
        'PAGE_DESCRIPTION' => str_replace('{site}', Output::getClean(SITE_NAME), Output::getPurified(PAGE_DESCRIPTION)),
        'PAGE_KEYWORDS' => (defined('PAGE_KEYWORDS') ? Output::getPurified(PAGE_KEYWORDS) : '')
    ]);
}

$smarty->assign('TITLE', $page_title);

$cache->setCache('backgroundcache');

$banner_image = $cache->retrieve('banner_image');

if (!empty($banner_image)) {
    $smarty->assign('BANNER_IMAGE', Output::getClean($banner_image));
}

$logo_image = $cache->retrieve('logo_image');

if (!empty($logo_image)) {
    $smarty->assign('LOGO_IMAGE', Output::getClean($logo_image));
}

$favicon_image = $cache->retrieve('favicon_image');

if (!empty($favicon_image)) {
    $smarty->assign('FAVICON', Output::getClean($favicon_image));
}

$analytics_id = Util::getSetting('ga_script');
if ($analytics_id) {
    $smarty->assign('ANALYTICS_ID', Output::getClean($analytics_id));
}

$smarty->assign([
    'FOOTER_LINKS_TITLE' => $language->get('general', 'links'),
    'FOOTER_SOCIAL_TITLE' => $language->get('general', 'social'),
    'AUTO_LANGUAGE_TEXT' => $language->get('general', 'auto_language'),
    'ENABLED' => $language->get('user', 'enabled'),
    'DISABLED' => $language->get('user', 'disabled'),
    'DARK_LIGHT_MODE_ACTION' => URL::build('/queries/dark_light_mode'),
    'DARK_LIGHT_MODE_TOKEN' => $user->isLoggedIn() ? Token::get() : null
]);
