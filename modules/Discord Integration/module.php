<?php

class Discord_Module extends Module {

    private Language $_language;

    public function __construct(Language $language, Pages $pages, Endpoints $endpoints) {
        $this->_language = $language;

        $name = 'Discord Integration';
        $author = '<a href="https://tadhg.sh" target="_blank" rel="nofollow noopener">Aberdeener</a>';
        $module_version = '2.1.2';
        $nameless_version = '2.1.2';

        parent::__construct($this, $name, $author, $module_version, $nameless_version);

        $bot_url = Util::getSetting('discord_bot_url');
        if ($bot_url === null) {
            $bot_url = '';
        }
        define('BOT_URL', $bot_url);

        $bot_username = Util::getSetting('discord_bot_username');
        if ($bot_username === null) {
            $bot_username = '';
        }
        define('BOT_USERNAME', $bot_username);

        $pages->add($this->getName(), '/panel/discord', 'pages/panel/discord.php');

        $endpoints->loadEndpoints(ROOT_PATH . '/modules/Discord Integration/includes/endpoints');

        // -- Events
        EventHandler::registerEvent(DiscordWebhookFormatterEvent::class);

        GroupSyncManager::getInstance()->registerInjector(new DiscordGroupSyncInjector);

        Integrations::getInstance()->registerIntegration(new DiscordIntegration($language));
    }

    public function onInstall() {
    }

    public function onUninstall() {
    }

    public function onDisable() {
    }

    public function onEnable() {
    }

    public function onPageLoad(User $user, Pages $pages, Cache $cache, Smarty $smarty, $navs, Widgets $widgets, ?TemplateBase $template) {
        PermissionHandler::registerPermissions($this->getName(), [
            'admincp.discord' => $this->_language->get('admin', 'integrations') . ' &raquo; ' . Discord::getLanguageTerm('discord'),
        ]);

        if ($pages->getActivePage()['widgets'] || (defined('PANEL_PAGE') && str_contains(PANEL_PAGE, 'widget'))) {
            $widgets->add(new DiscordWidget($cache, $smarty));
        }

        if (!defined('FRONT_END')) {
            $cache->setCache('panel_sidebar');

            if ($user->hasPermission('admincp.discord')) {
                if (!$cache->isCached('discord_icon')) {
                    $icon = '<i class="nav-icon fab fa-discord"></i>';
                    $cache->store('discord_icon', $icon);
                } else {
                    $icon = $cache->retrieve('discord_icon');
                }

                $navs[2]->addItemToDropdown('integrations', 'discord', Discord::getLanguageTerm('discord'), URL::build('/panel/discord'), 'top', null, $icon, 1);
            }
        }
    }

    public function getDebugInfo(): array {
        return [
            'guild_id' => Discord::getGuildId(),
            'roles' => Discord::getRoles(),
            'bot_setup' => Discord::isBotSetup(),
            'bot_url' => BOT_URL,
        ];
    }
}
