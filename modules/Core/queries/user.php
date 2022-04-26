<?php
// Check user ID is specified
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
    $cache->setCache('user_query');

    if ($cache->isCached($_GET['id'])) {
        [$username, $nickname, $profile, $avatar, $style, $groups, $id] = $cache->retrieve($_GET['id']);

    } else {
        $target_user = new User($_GET['id']);
        if (!$target_user->exists()) {
            die(json_encode(['html' => 'User not found']));
        }

        $username = $target_user->getDisplayname(true);
        $nickname = $target_user->getDisplayname();
        $profile = $target_user->getProfileURL();
        $avatar = $target_user->getAvatar();
        $style = $target_user->getGroupStyle();
        $groups = $target_user->getAllGroupHtml();
        $id = Output::getClean($target_user->data()->id);

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
