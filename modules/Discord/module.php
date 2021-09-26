<?php

class Discord_Module extends Module {
    
    private $_language;

    public function __construct(Language $language, Pages $pages, Queries $queries, Endpoints $endpoints) {
        $this->_language = $language;

        $name = 'Discord Integration';
        $author = '<a href="https://tadhg.sh" target="_blank" rel="nofollow noopener">Aberdeener</a>';
        $module_version = '2.0.0-pr12';
        $nameless_version = '2.0.0-pr12';

        parent::__construct($this, $name, $author, $module_version, $nameless_version);

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

        $pages->add('Core', '/panel/discord', 'pages/panel/discord.php');

        require_once(ROOT_PATH . '/modules/Discord/hooks/DiscordHook.php');

        Util::loadEndpoints(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'modules', 'Discord', 'includes', 'endpoints')), $endpoints);
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
        PermissionHandler::registerPermissions($this->_language->get('moderator', 'staff_cp'), [
            'admincp.discord' => $this->_language->get('admin', 'integrations') . ' &raquo; ' . $this->_language->get('admin', 'discord'),
        ]);

        require_once(ROOT_PATH . '/modules/Core/widgets/DiscordWidget.php');
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

                $navs[2]->addItemToDropdown('integrations', 'discord', $this->_language->get('admin', 'discord'), URL::build('/panel/discord'), 'top', null, $icon, $order);
            }
        }
    }
}