<?php

/*
 *  Nexus Template for NamelessMC
 *  AAA-grade UI/UX modernization — Cyberpunk-Minimalism + Apple-Polish
 *
 *  Compatible with NamelessMC 2.2.x
 *  Licence: MIT
 */

class Nexus_Template extends SmartyTemplateBase
{
    private array $_template;
    private Language $_language;
    private User $_user;
    private Pages $_pages;

    public function __construct(Cache $cache, Language $language, User $user, Pages $pages)
    {
        $template = [
            'name' => 'Nexus',
            'version' => '1.0.0',
            'nl_version' => '2.2.5',
            'author' => '<a href="https://github.com/" target="_blank">Nexus Team</a>',
        ];

        $template['path'] = (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/custom/templates/' . $template['name'] . '/';

        parent::__construct(
            $template['name'],
            $template['version'],
            $template['nl_version'],
            $template['author'],
            __DIR__
        );

        $this->_settings = ROOT_PATH . '/custom/templates/Nexus/template_settings/settings.php';

        // Only the vendor asset trees we actually depend on.
        // jQuery stays — modules (Forum, Discord, etc.) rely on it.
        // jQuery-Cookie stays — used for auto-language toggle in footer.
        // Font Awesome is loaded for icon fallback in module-provided HTML;
        // primary icons in Nexus use an inline SVG sprite.
        $this->assets()->include([
            AssetTree::JQUERY,
            AssetTree::JQUERY_COOKIE,
            AssetTree::FONT_AWESOME,
        ]);

        $this->getEngine()->addVariable('TEMPLATE', $template);
        $this->getEngine()->addVariable('FORUM_SPAM_WARNING_TITLE', $language->get('general', 'warning'));

        // Dark mode is server-driven via the DARK_MODE constant.
        // Nexus is dark-first but supports the legacy toggle.
        $cache->setCache('template_settings');
        $nexusDarkMode = true; // default
        if (defined('DARK_MODE')) {
            $nexusDarkMode = (DARK_MODE == '1');
        }

        $accent = '#7C5CFF';
        if ($cache->isCached('nexusAccent')) {
            $cachedAccent = $cache->retrieve('nexusAccent');
            if (is_string($cachedAccent) && preg_match('/^#[0-9A-Fa-f]{6}$/', $cachedAccent)) {
                $accent = $cachedAccent;
            }
        }

        $this->getEngine()->addVariables([
            'NEXUS_DARK_MODE' => $nexusDarkMode,
            'NEXUS_ACCENT' => $accent,
            'NEXUS_THEME_NAME' => $template['name'],
            'NEXUS_THEME_VERSION' => $template['version'],
        ]);

        if (defined('AUTO_LANGUAGE_VALUE')) {
            $this->getEngine()->addVariable('AUTO_LANGUAGE_VALUE', AUTO_LANGUAGE_VALUE);
        }

        $this->_template = $template;
        $this->_language = $language;
        $this->_user = $user;
        $this->_pages = $pages;
    }

    public function onPageLoad()
    {
        $page_load = microtime(true) - PAGE_START_TIME;
        define('PAGE_LOAD_TIME', $this->_language->get('general', 'page_loaded_in', ['time' => round($page_load, 3)]));

        // Nexus compiled stylesheet. Version bump invalidates browser cache.
        $this->addCSSFiles([
            $this->_template['path'] . 'assets/css/nexus.css?v=' . $this->_template['version'] => [],
        ]);

        $route = (isset($_GET['route']) ? rtrim($_GET['route'], '/') : '/');

        // JS variables consumed by Nexus + legacy module scripts.
        // The names mirror DefaultRevamp's contract so module scripts keep working.
        $JSVariables = [
            'siteName' => Output::getClean(SITE_NAME),
            'siteURL' => URL::build('/'),
            'fullSiteURL' => URL::getSelfURL() . ltrim(URL::build('/'), '/'),
            'page' => PAGE,
            'avatarSource' => AvatarSource::getUrlToFormat(),
            'copied' => $this->_language->get('general', 'copied'),
            'cookieNotice' => $this->_language->get('general', 'cookie_notice'),
            'noMessages' => $this->_language->get('user', 'no_messages'),
            'newMessage1' => $this->_language->get('user', '1_new_message'),
            'newMessagesX' => $this->_language->get('user', 'x_new_messages'),
            'noAlerts' => $this->_language->get('user', 'no_alerts'),
            'newAlert1' => $this->_language->get('user', '1_new_alert'),
            'newAlertsX' => $this->_language->get('user', 'x_new_alerts'),
            'bungeeInstance' => $this->_language->get('general', 'bungee_instance'),
            'andMoreX' => $this->_language->get('general', 'and_x_more'),
            'onePlayerOnline' => $this->_language->get('general', 'currently_1_player_online'),
            'xPlayersOnline' => $this->_language->get('general', 'currently_x_players_online'),
            'noPlayersOnline' => $this->_language->get('general', 'no_players_online'),
            'offline' => $this->_language->get('general', 'offline'),
            'confirmDelete' => $this->_language->get('general', 'confirm_deletion'),
            'debugging' => (defined('DEBUGGING') && DEBUGGING == 1) ? '1' : '0',
            'loggedIn' => $this->_user->isLoggedIn() ? '1' : '0',
            'cookie' => defined('COOKIE_NOTICE') ? '1' : '0',
            'loadingTime' => Settings::get('page_loading') === '1' ? PAGE_LOAD_TIME : '',
            'route' => $route,
            'csrfToken' => Token::get(),
        ];

        $cache = new Cache(['name' => 'nameless', 'extension' => '.cache', 'path' => ROOT_PATH . '/cache/']);
        $cache->setCache('backgroundcache');
        $logo_image = $cache->retrieve('logo_image');
        $JSVariables['logoImage'] = !empty($logo_image) ? $logo_image : null;

        // jQuery-UI only on heavy interactive pages (matches DefaultRevamp behaviour)
        if (str_contains($route, '/forum/topic/') || PAGE === 'profile') {
            $this->assets()->include([AssetTree::JQUERY_UI]);
        }

        $JSVars = '';
        $i = 0;
        foreach ($JSVariables as $var => $value) {
            $JSVars .= ($i == 0 ? 'const ' : ', ') . $var . ' = ' . json_encode($value);
            $i++;
        }

        $this->addJSScript($JSVars);

        // Nexus bundle (Alpine + helpers + legacy bridges).
        // Loaded with defer attribute by the framework's asset resolver.
        $this->addJSFiles([
            $this->_template['path'] . 'assets/js/nexus.js?v=' . $this->_template['version'] => [],
        ]);

        // Preserve module ajax hook contract
        foreach ($this->_pages->getAjaxScripts() as $script) {
            $this->addJSScript('$.getJSON(\'' . $script . '\', function(data) {});');
        }
    }
}

/**
 * @var Cache    $cache
 * @var Language $language
 * @var User     $user
 * @var Pages    $pages
 */
$template = new Nexus_Template($cache, $language, $user, $pages);

// Pagination markup hook used by core list rendering
$template_pagination = [
    'div' => 'nx-pagination',
    'a' => 'nx-pagination__link',
];
