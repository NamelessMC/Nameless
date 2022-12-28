<?php
declare(strict_types=1);
/**
 *  Made by Unknown
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  TODO: Add description
 *
 * @var User $user
 * @var Language $language
 * @var Announcements $announcements
 * @var Smarty $smarty
 * @var Pages $pages
 * @var Cache $cache
 * @var Navigation $navigation
 * @var array $cc_nav
 * @var array $staffcp_nav
 * @var Widgets $widgets
 * @var TemplateBase $template
 * @var Language $forum_language
 * @var User $target_user
 */

// Check user ID is specified
use GuzzleHttp\Exception\GuzzleException;

if (!isset($_GET['id'])) {
    die(json_encode(['html' => 'Error: Invalid ID']));
}

const PAGE = 'user_query';
$page_title = 'user_query';
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

if (!is_numeric($_GET['id'])) {
    // Username
    $username = Output::getClean($_GET['id']);
    $nickname = $username;
    $profile = URL::build('/profile/' . $username);
    $avatar = (isset($_GET['uuid']) ? AvatarSource::getAvatarFromUUID(Output::getClean($_GET['uuid'])) : AvatarSource::getAvatarFromUUID($username));
    $style = '';
    $groups = [];
    $id = 0;
} else {
    $cache->setCacheName('user_query');

    if ($cache->hasCashedData($_GET['id'])) {
        [$username, $nickname, $profile, $avatar, $style, $groups, $id] = $cache->retrieve($_GET['id']);

    } else {
        try {
            $target_user = new User($_GET['id']);
        } catch (GuzzleException $ignored) {
        }
        if (!$target_user->exists()) {
            die(json_encode(['html' => 'User not found']));
        }

        $username = $target_user->getDisplayName(true);
        $nickname = $target_user->getDisplayName();
        $profile = $target_user->getProfileURL();
        try {
            $avatar = $target_user->getAvatar();
        } catch (GuzzleException $ignored) {
        }
        $style = $target_user->getGroupStyle();
        $groups = $target_user->getAllGroupHtml();
        $id = Output::getClean((string)$target_user->data()->id);

        $cache->store($_GET['id'], [$username, $nickname, $profile, $avatar, $style, $groups, $id], 60);
    }
}

$smarty->assign([
    'PROFILE' => $profile,
    'USERNAME' => $username,
    'NICKNAME' => $nickname,
    'AVATAR' => $avatar,
    'STYLE' => $style,
    'GROUPS' => $groups,
    'USER_ID' => $id
]);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

$template->onPageLoad();

try {
    echo json_encode([
        'id' => $id,
        'profile' => $profile,
        'username' => $username,
        'nickname' => $nickname,
        'avatar' => $avatar,
        'style' => $style,
        'groups' => $groups,
        'html' => $template->getTemplate('user_popover.tpl', $smarty)
    ], JSON_PRETTY_PRINT);
} catch (SmartyException $ignored) {
}
