<?php

class Discord_Module extends Module {
    
    private $_language;
    private $_discord_integration_language;

    public function __construct(Language $language, Pages $pages, Queries $queries, Endpoints $endpoints) {
        $this->_language = $language;

        $name = 'Discord Integration';
        $author = '<a href="https://tadhg.sh" target="_blank" rel="nofollow noopener">Aberdeener</a>';
        $module_version = '2.0.0-pr12';
        $nameless_version = '2.0.0-pr12';

        parent::__construct($this, $name, $author, $module_version, $nameless_version);

        $this->_discord_integration_language = new Language(ROOT_PATH . "/modules/{$this->getName()}/language", LANGUAGE);

        $bot_url = $queries->getWhere('settings', array('name', '=', 'discord_bot_url'));
        $bot_url = $bot_url[0]->value;
        if ($bot_url == null) {
            $bot_url = '';
        }
        define('BOT_URL', $bot_url);

        $bot_username = $queries->getWhere('settings', array('name', '=', 'discord_bot_username'));
        $bot_username = $bot_username[0]->value;
        if ($bot_username == null) {
            $bot_username = '';
        }
        define('BOT_USERNAME', $bot_username);

        $pages->add($this->getName(), '/panel/discord', 'pages/panel/discord.php');

        require_once(ROOT_PATH . "/modules/{$this->getName()}/hooks/DiscordHook.php");

        Util::loadEndpoints(ROOT_PATH . "/modules/{$this->getName()}/includes/endpoints", $endpoints);
    }

    public function onInstall() {
    }

    public function onUninstall() {
    }

    public function onDisable() {
    }

    public function onEnable() {
    }

    public function onPageLoad(User $user, Pages $pages, Cache $cache, Smarty $smarty, $navs, Widgets $widgets, $template)
    {
        PermissionHandler::registerPermissions($this->getName(), [
            'admincp.discord' => $this->_language->get('admin', 'integrations') . ' &raquo; ' . $this->_discord_integration_language->get('discord_integration', 'discord'),
        ]);

        require_once(ROOT_PATH . "/modules/{$this->getName()}/widgets/DiscordWidget.php");
        $discord = $cache->retrieve('discord');
        $module_pages = $widgets->getPages('Discord');
        $widgets->add(new DiscordWidget($module_pages, $this->_language, $cache, $discord));

        if (!defined('FRONT_END')) {
            if ($user->hasPermission('admincp.discord')) {
                if (!$cache->isCached('discord_icon')) {
                    $icon = '<i class="nav-icon fab fa-discord"></i>';
                    $cache->store('discord_icon', $icon);
                } else {
                    $icon = $cache->retrieve('discord_icon');
                }

                $navs[2]->addItemToDropdown('integrations', 'discord', $this->_discord_integration_language->get('discord_integration', 'discord'), URL::build('/panel/discord'), 'top', null, $icon, $order);
            }
        }
    }
}