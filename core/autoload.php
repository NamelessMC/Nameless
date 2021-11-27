<?php

require_once ROOT_PATH . '/core/includes/constants/autoload.php';
require_once ROOT_PATH . '/core/includes/smarty/Smarty.class.php';

spl_autoload_register(function ($class) {

    $path = join(DIRECTORY_SEPARATOR, [ROOT_PATH, 'core', 'classes', getFolder($class), $class . '.php']);

    if (file_exists($path)) {
        require_once($path);
    }
});

const CLASS_FOLDERS = [
    'Avatars' => [
        AvatarSource::class,
        AvatarSourceBase::class,
    ],
    'Collections' => [
        Collection::class,
        CollectionItemBase::class,
        CollectionManager::class,
    ],
    'Core' => [
        Alert::class,
        Announcements::class,
        Cache::class,
        Config::class,
        Configuration::class,
        Cookie::class,
        Email::class,
        Hash::class,
        HttpClient::class,
        Input::class,
        Instanceable::class,
        Language::class,
        Log::class,
        Module::class,
        Navigation::class,
        Output::class,
        Pages::class,
        Paginator::class,
        PermissionHandler::class,
        Redirect::class,
        Report::class,
        Session::class,
        TimeAgo::class,
        Token::class,
        URL::class,
        User::class,
        Util::class,
        Validate::class,
    ],
    'Database' => [
        DB_Custom::class,
        DB::class,
        Queries::class,
        QueryRecorder::class,
    ],
    'Endpoints' => [
        EndpointBase::class,
        Endpoints::class,
    ],
    'Events' => [
        EventHandler::class,
    ],
    'Group_Sync' => [
        GroupSyncInjector::class,
        GroupSyncManager::class,
    ],
    'Minecraft' => [
        ExternalMCQuery::class,
        MCAssoc::class,
        MCQuery::class,
        MentionsParser::class,
        MinecraftBanner::class,
        MinecraftPing::class,
        ServerBanner::class,
    ],
    'Misc' => [
        CaptchaBase::class,
        ErrorHandler::class,
        Placeholders::class,
        TemplateBase::class,
        UpgradeScript::class,
    ],
    'Widgets' => [
        WidgetBase::class,
        Widgets::class,
    ],
];

function getFolder(string $class): string {
    foreach (CLASS_FOLDERS as $folder => $classes) {
        if (in_array($class, $classes)) {
            return $folder;
        }
    }

    return '';
}
