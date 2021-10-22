<?php

require_once ROOT_PATH . '/core/includes/smarty/Smarty.class.php';

spl_autoload_register(function(string $class) {

    $sections = explode('\\', $class);
    $section_count = count($sections);

    if ($section_count == 1 || $section_count == 3) {

        $path = ROOT_PATH . "/core/classes/{$class}.php";

    } else {

        switch ($section_count) {
            case 4: {
                // \NamelessMC\Core\<folder>\<class>
                $folder = str_replace('_', '', $sections[2]);
                $path = ROOT_PATH . "/core/classes/{$folder}/{$sections[3]}.php";
                break;
            }
        }
    }

    if (file_exists($path)) {
        require_once($path);
    }
});

$aliases = [
    \NamelessMC\Core\Support\Alert::class => 'Alert',
    \NamelessMC\Core\Avatars\AvatarSource::class => 'AvatarSource',
    \NamelessMC\Core\Avatars\AvatarSourceBase::class => 'AvatarSourceBase',
    \NamelessMC\Core\Support\Cache::class => 'Cache',
    \NamelessMC\Core\Support\CaptchaBase::class => 'CaptchaBase',
    \NamelessMC\Core\Collections\CollectionItemBase::class => 'CollectionItemBase',
    \NamelessMC\Core\Collections\CollectionManager::class => 'CollectionManager',
    \NamelessMC\Core\Support\Config::class => 'Config',
    \NamelessMC\Core\Support\Configuration::class => 'Configuration',
    \NamelessMC\Core\Support\Cookie::class => 'Cookie',
    \NamelessMC\Core\Database\DB_Custom::class => 'DB_Custom',
    \NamelessMC\Core\Database\DB::class => 'DB',
    \NamelessMC\Core\Support\Email::class => 'Email',
    \NamelessMC\Core\Endpoints\EndpointBase::class => 'EndpointBase',
    \NamelessMC\Core\Endpoints\Endpoints::class => 'Endpoints',
    \NamelessMC\Core\Support\ErrorHandler::class => 'ErrorHandler',
    \NamelessMC\Core\Minecraft\ExternalMCQuery::class => 'ExternalMCQuery',
    \NamelessMC\Core\Group_Sync\GroupSyncInjector::class => 'GroupSyncInjector',
    \NamelessMC\Core\Group_Sync\GroupSyncManager::class => 'GroupSyncManager',
    \NamelessMC\Core\Support\Hash::class => 'Hash',
    \NamelessMC\Core\Events\Hook::class => 'Hook',
    \NamelessMC\Core\Events\HookHandler::class => 'HookHandler',
    \NamelessMC\Core\Support\Input::class => 'Input',
    \NamelessMC\Core\Support\Instanceable::class => 'Instanceable',
    \NamelessMC\Core\Support\Language::class => 'Language',
    \NamelessMC\Core\Support\Log::class => 'Log',
    \NamelessMC\Core\Minecraft\MCAssoc::class => 'MCAssoc',
    \NamelessMC\Core\Minecraft\MCQuery::class => 'MCQuery',
    \NamelessMC\Core\Support\MentionsParser::class => 'MentionsParser',
    \NamelessMC\Core\Minecraft\MinecraftBanner::class => 'MinecraftBanner',
    \NamelessMC\Core\Minecraft\MinecraftPing::class => 'MinecraftPing',
    \NamelessMC\Core\Support\Module::class => 'Module',
    \NamelessMC\Core\Support\Navigation::class => 'Navigation',
    \NamelessMC\Core\Support\Output::class => 'Output',
    \NamelessMC\Core\Support\Pages::class => 'Pages',
    \NamelessMC\Core\Support\Paginator::class => 'Paginator',
    \NamelessMC\Core\Support\PermissionHandler::class => 'PermissionHandler',
    \NamelessMC\Core\Database\Queries::class => 'Queries',
    \NamelessMC\Core\Support\Redirect::class => 'Redirect',
    \NamelessMC\Core\Support\Report::class => 'Report',
    \NamelessMC\Core\Minecraft\ServerBanner::class => 'ServerBanner',
    \NamelessMC\Core\Support\Session::class => 'Session',
    \NamelessMC\Core\Support\TemplateBase::class => 'TemplateBase',
    \NamelessMC\Core\Support\TimeAgo::class => 'TimeAgo',
    \NamelessMC\Core\Support\Token::class => 'Token',
    \NamelessMC\Core\Support\URL::class => 'URL',
    \NamelessMC\Core\Support\User::class => 'User',
    \NamelessMC\Core\Support\Util::class => 'Util',
    \NamelessMC\Core\Support\Validate::class => 'Validate',
    \NamelessMC\Core\Widgets\WidgetBase::class => 'WidgetBase',
    \NamelessMC\Core\Widgets\Widgets::class => 'Widgets',
];

// we alias classes so modules will still work if they dont import the class
foreach ($aliases as $class => $alias) {
    class_alias($class, $alias);
}
