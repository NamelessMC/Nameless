<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.1.2
 *
 *  License: MIT
 *
 *  Cookie Consent module file
 */

class CookieConsent_Module extends Module {

    private Language $_language;
    private Language $_cookie_language;

    public function __construct(Language $language, Language $cookie_language, Pages $pages) {
        $this->_language = $language;
        $this->_cookie_language = $cookie_language;

        $name = 'Cookie Consent';
        $author = '<a href="https://samerton.me" target="_blank" rel="nofollow noopener">Samerton</a>';
        $module_version = '2.1.2';
        $nameless_version = '2.1.2';

        parent::__construct($this, $name, $author, $module_version, $nameless_version);

        // Define URLs which belong to this module
        $pages->add('Cookie Consent', '/cookies', 'pages/cookies.php');

        // Panel
        $pages->add('Cookie Consent', '/panel/cookies', 'pages/panel/cookies.php');

        // Cookies
        define('COOKIE_CHECK', true);
        define('COOKIES_ALLOWED', Cookie::exists('cookieconsent_status') && Cookie::get('cookieconsent_status') == 'allow');
    }

    public function onInstall() {
        // Not necessary for CookieConsent
    }

    public function onUninstall() {
        // Not necessary for CookieConsent
    }

    public function onEnable() {
        // Not necessary for CookieConsent
    }

    public function onDisable() {
        // Not necessary for CookieConsent
    }

    public function onPageLoad(User $user, Pages $pages, Cache $cache, Smarty $smarty, $navs, Widgets $widgets, ?TemplateBase $template) {
        $language = $this->_language;

        // AdminCP
        PermissionHandler::registerPermissions($language->get('moderator', 'staff_cp'), [
            'admincp.cookies' => $this->_cookie_language->get('cookie', 'cookies')
        ]);

        // Sitemap
        $pages->registerSitemapMethod([CookieConsent_Sitemap::class, 'generateSitemap']);

        if (defined('FRONT_END')) {
            // Add cookie page link
            $cache->setCache('cookie_consent_module_cache');
            if (!$cache->isCached('options')) {
                $options = ['type' => 'opt-in', 'position' => 'bottom-right'];
                $cache->store('options', $options);
            } else {
                $options = $cache->retrieve('options');
            }

            $cookie_url = URL::build('/cookies');

            // Add JS script
            if ($template) {
                $template->addCSSFiles([
                    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/modules/Cookie Consent/assets/css/cookieconsent.min.css' => [],
                ]);
                $template->addJSFiles([
                    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/modules/Cookie Consent/assets/js/cookieconsent.min.js' => [],
                ]);
                $template->addJSScript(
                    CookieConsent::generateScript(
                        array_merge($options, [
                            'cookies' => $this->_cookie_language->get('cookie', 'cookies'),
                            'message' => $this->_cookie_language->get('cookie', 'cookie_popup'),
                            'dismiss' => $this->_cookie_language->get('cookie', 'cookie_popup_disallow'),
                            'allow' => $this->_cookie_language->get('cookie', 'cookie_popup_allow'),
                            'link' => $this->_cookie_language->get('cookie', 'cookie_popup_more_info'),
                            'href' => $cookie_url,
                        ])
                    )
                );
            }

            $smarty->assign([
                'COOKIE_URL' => $cookie_url,
                'COOKIE_NOTICE_HEADER' => $this->_cookie_language->get('cookie', 'cookie_notice'),
                'COOKIE_NOTICE_BODY' => $this->_cookie_language->get('cookie', 'cookie_notice_info'),
                'COOKIE_NOTICE_CONFIGURE' => $this->_cookie_language->get('cookie', 'configure_cookies'),
                'COOKIE_DECISION_MADE' => (bool)Cookie::get('cookieconsent_status'),
            ]);

            $navs[0]->add('cookies', $this->_cookie_language->get('cookie', 'cookie_notice'), $cookie_url, 'footer');
        }

        if (defined('BACK_END')) {
            $cache->setCache('panel_sidebar');

            // StaffCP link
            if ($user->hasPermission('admincp.cookies')) {
                if (!$cache->isCached('cookie_order')) {
                    $order = 10;
                    $cache->store('cookie_order', 10);
                } else {
                    $order = $cache->retrieve('cookie_order');
                }

                if (!$cache->isCached('cookie_icon')) {
                    $icon = '<i class="nav-icon fas fa-cookie-bite"></i>';
                    $cache->store('cookie_icon', $icon);
                } else {
                    $icon = $cache->retrieve('cookie_icon');
                }

                $navs[2]->add('cookie_divider', mb_strtoupper($this->_cookie_language->get('cookie', 'cookies'), 'UTF-8'), 'divider', 'top', null, $order, '');
                $navs[2]->add('cookie_settings', $this->_cookie_language->get('cookie', 'cookies'), URL::build('/panel/cookies'), 'top', null, $order + 0.1, $icon);
            }
        }
    }

    public function getDebugInfo(): array {
        return [];
    }
}
