<?php
// Check user ID is specified
if (!isset($_GET['id'])) {
    die(json_encode(array('html' => 'Error: Invalid ID')));
}

define('PAGE', 'user_query');
$page_title = 'user_query';
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

if (!is_numeric($_GET['id'])) {
    // Username
    $username = Output::getClean($_GET['id']);
    $nickname = $username;
    $profile = URL::build('/profile/' . $username);
    $avatar = (isset($_GET['uuid']) ? Util::getAvatarFromUUID(Output::getClean($_GET['uuid']), 128) : Util::getAvatarFromUUID($username, 128));
    $style = '';
    $groups = array();
    $id = 0;
} else {
    $target_user = new User($_GET['id']);
    if (!$target_user->data()) {
        die(json_encode(array('html' => 'User not found')));
    } else {
        $user_query = $user_query[0];
    }

    $username = $target_user->getDisplayname(true);
    $nickname = $target_user->getDisplayname();
    $profile = $target_user->getProfileURL();
    $avatar = $target_user->getAvatar();
    $style = $target_user->getGroupClass();
    $groups = $target_user->getAllGroups(true);
    $id = Output::getClean($target_user->data()->id);
}

$smarty->assign(array(
    'PROFILE' => $profile,
    'USERNAME' => $username,
    'NICKNAME' => $nickname,
    'AVATAR' => $avatar,
    'STYLE' => $style,
    'GROUPS' => $groups,
    'USER_ID' => $id
));

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

$template->onPageLoad();

echo json_encode(array(
    'id' => $id,
    'profile' => $profile,
    'username' => $username,
    'nickname' => $nickname,
    'avatar' => $avatar,
    'style' => $style,
    'groups' => $groups,
    'html' => $template->getTemplate('user_popover.tpl', $smarty)
), JSON_PRETTY_PRINT);
