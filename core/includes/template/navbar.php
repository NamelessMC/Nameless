<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */
 
/* 
 *  Generate HTML for navbar
 */
 
// First, set Smarty variable to see if the navbar is inverse or not
if($inverse_navbar == 1){
	$smarty->assign('NAVBAR_INVERSE', ' navbar-inverse');
} else {
	$smarty->assign('NAVBAR_INVERSE', '');
}
 
/*
 *  Any messages or alerts?
 */

 
// Main navbar
$main_navbar = array(
	'/' => array(1 => $navbar_language['home'], 2 => 'index'),
	'/play' => array(1 => $navbar_language['play'], 2 => 'play'),
	'/forum' => array(1 => $navbar_language['forum'], 2 => 'forum')
);


$navbar_links = '<ul class="nav navbar-nav">';

foreach($main_navbar as $key => $item){
	$navbar_links .= '<li';
	if(isset($page) && $page == $item[2]){
		$navbar_links .= ' class="active"';
	}
	$navbar_links .= '><a href="' . htmlspecialchars($key) . '">' . $item[1] . '</a></li>';
}

// Addons
foreach($navbar_array as $item){
	foreach($item as $key => $text){
		$navbar_links .= '<li';
		if(isset($page) && $page == $text){
			$navbar_links .= ' class="active"';
		}
		$navbar_links .= '><a href="/' . htmlspecialchars($key) . '">' . htmlspecialchars($text) . '</a></li>';
	}
}

// Custom pages
$custom_pages = $queries->getWhere('custom_pages', array('url', '<>', ''));
foreach($custom_pages as $item){
	if($item->link_location == 1){
		$navbar_links .= '<li';
		if(isset($page) && $page == $item->title){
			$navbar_links .= ' class="active"';
		}
		$navbar_links .= '><a href="' . htmlspecialchars($item->url) . '">' . htmlspecialchars($item->title) . '</a></li>';
	}
}

$navbar_links .= '</ul>';
 
// User area
if($user->isLoggedIn()){
	$user_area = '
	<li><a href="/user/messaging"><span style="margin: -10px 0px; font-size: 16px;"><i class="fa fa-envelope"></i> <div style="display: inline;" id="pms"></div></span></a></li>
	<li><a href="/user/alerts"><span style="margin: -10px 0px; font-size: 16px;"><i class="fa fa-flag"></i> <div style="display: inline;" id="alerts"></div></span></a></li>
	<li class="dropdown">
	  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><img class="img-rounded" style="margin: -10px 0px;" src="https://cravatar.eu/avatar/' . $user->data()->mcname . '/25.png">&nbsp;&nbsp;' . $user->data()->username . ' <span class="caret"></span></a>
	  <ul class="dropdown-menu" role="menu">
		<li><a href="/profile/' . $user->data()->mcname . '">'. $user_language['profile'] .'</a></li>
		<li class="divider"></li>
		<li><a href="/user">' . $user_language['user_cp'] . '</a></li>';
		if($user->canViewMCP($user->data()->id)){
			$user_area .= '<li><a href="/mod">' . $mod_language['mod_cp'] . '</a></li>';
		}
		if($user->canViewACP($user->data()->id)){
			$user_area .= '<li><a href="/admin">' . $admin_language['admin_cp'] . '</a></li>';
		}
		$user_area .= '<li class="divider"></li>
		<li><a href="/signout">' . $user_language['sign_out'] . '</a></li>
	  </ul>
	</li>';
} else {
	// Guest
	$user_area = '
	<li class="dropdown">
	  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">' . $general_language['guest'] . ' <span class="caret"></span></a>
	  <ul class="dropdown-menu" role="menu">
		<li><a href="/signin">' . $user_language['sign_in'] . '</a></li>
		<li><a href="/register">' . $user_language['register'] . '</a></li>
	  </ul>
	</li>';
}
 
/*
 *  Global messages
 */
 
$global_messages = '';
if(Session::exists('global')){
	$global_messages = '<br /><div class="container">' . Session::flash('global') . '</div>';
}
 
/*
 *  Assign to Smarty variables
 */
 
$smarty->assign('NAVBAR_LINKS', $navbar_links);
$smarty->assign('USER_AREA', $user_area);
$smarty->assign('GLOBAL_MESSAGES', $global_messages);