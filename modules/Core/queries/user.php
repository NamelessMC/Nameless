<?php
// Check user ID is specified
if(!isset($_GET['id'])){
	die(json_encode(array('html' => 'Error: Invalid ID')));
}

define('PAGE', 'user_query');
$page_title = 'user_query';
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

if(!is_numeric($_GET['id'])){
	// Username
	$username = Output::getClean($_GET['id']);
	$nickname = $username;
	$profile = URL::build('/profile/' . $username);
	$avatar = (isset($_GET['uuid']) ? Util::getAvatarFromUUID(Output::getClean($_GET['uuid']), 128) : Util::getAvatarFromUUID($username, 128));
	$style = '';
	$groups = array();
	$id = 0;

} else {
	$user_query = $queries->getWhere('users', array('id', '=', $_GET['id']));
	if(!count($user_query)){
		die(json_encode(array('html' => 'User not found')));
	} else {
		$user_query = $user_query[0];
	}

	$username = Output::getClean($user_query->username);
	$nickname = Output::getClean($user_query->nickname);
	$profile = URL::build('/profile/' . $username);
	$avatar = $user->getAvatar($user_query->id, '../', 128);
	$style = $user->getGroupClass($user_query->id);
	$groups = $user->getAllGroups($user_query->id, true);
	$id = Output::getClean($user_query->id);
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