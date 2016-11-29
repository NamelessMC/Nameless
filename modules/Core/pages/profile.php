<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  User profile page
 */

// Always define page name
define('PAGE', 'profile');

$paginator = new Paginator();
$timeago = new Timeago();

require('core/includes/paginate.php'); // Get number of wall posts on a page
?>
<!DOCTYPE html>
<html>
  <head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <!-- Site Properties -->
	<?php 
	$title = $language->get('user', 'profile');
	require('core/templates/header.php'); 
	?>
	
	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/image-picker/image-picker.css">
	<style type="text/css">
		.thumbnails li img{
			width: 200px;
		}
	</style>
  
  </head>
  <body>
    <?php
	require('core/templates/navbar.php'); 
	require('core/templates/footer.php'); 
	?>
	
	<?php
	if(isset($directories[1]) && !empty($directories[1]) && !isset($_GET['error'])){
		// User specified
		$profile = $directories[1];
		$query = $queries->getWhere('users', array('username', '=', $profile));
		
		if(!count($query)) Redirect::to(URL::build('/profile/', 'error=not_exist'));
		$query = $query[0];
		
		// Deal with input
		if(Input::exists()){
			if($user->isLoggedIn()){
				if(isset($_POST['action'])){
					switch ($_POST['action']){
						case 'banner':
							if($user->data()->username == $profile){
								if(Token::check(Input::get('token'))){
									// Update banner
									if(isset($_POST['banner'])){
										// Check image specified actually exists
										if(is_file(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'uploads', 'profile_images', $_POST['banner'])))){
											// Exists
											// Is it an image file?
											if(in_array(pathinfo(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'uploads', 'profile_images', $_POST['banner'])), PATHINFO_EXTENSION), array('gif', 'png', 'jpg', 'jpeg'))){
												// Yes, update settings
												$user->update(array(
													'banner' => Output::getClean($_POST['banner'])
												));
												
												// Requery to update banner
												$user = new User();
												$query = $queries->getWhere('users', array('username', '=', $profile));
												$query = $query[0];
											}
										}
									}
								}
							}
						break;
						
					}
				}
			}
		}
		
		// Get page
		if(isset($_GET['p'])){
			if(!is_numeric($_GET['p'])){
				Redirect::to(URL::build('/profile/' . Output::getClean($query->username)));
				die();
			} else {
				if($_GET['p'] == 1){ 
					// Avoid bug in pagination class
					Redirect::to(URL::build('/profile/' . Output::getClean($query->username)));
					die();
				}
				$p = $_GET['p'];
			}
		} else {
			$p = 1;
		}
		
		// Generate Smarty variables to pass to template
		if($user->isLoggedIn()){
			// Form token
			$smarty->assign(array(
				'TOKEN' => Token::generate(),
				'LOGGED_IN' => true,
				'SUBMIT' => $language->get('general', 'submit'),
				'CANCEL' => $language->get('general', 'cancel')
			));
			
			if($user->data()->username == $profile){
				// Custom profile banners
				$banners = array();

				$image_path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'uploads', 'profile_images'));
				$images = scandir($image_path);

				// Only display jpeg, png, jpg, gif
				$allowed_exts = array('gif', 'png', 'jpg', 'jpeg');
				  
				foreach($images as $image){
					$ext = pathinfo($image, PATHINFO_EXTENSION);
					if(!in_array($ext, $allowed_exts)){
						continue;
					}
					
					$banners[] = array(
						'src' => ((defined('CONFIG_PATH')) ? CONFIG_PATH : '/') . 'uploads/profile_images/' . Output::getClean($image),
						'name' => Output::getClean($image),
						'active' => ($user->data()->banner == $image) ? true : false
					);
				}
				
				$smarty->assign(array(
					'SELF' => true,
					'SETTINGS_LINK' => URL::build('/user/settings'),
					'CHANGE_BANNER' => $language->get('user', 'change_banner'),
					'BANNERS' => $banners
				));
			} else {
				$smarty->assign('MESSAGE_LINK', URL::build('/user/message/', 'user=' . $query->id));
				$smarty->assign('FOLLOW_LINK', URL::build('/user/follow/', 'user=' . $query->id));
			}
		}
		
		// Get user's group
		$group = $queries->getWhere('groups', array('id', '=', $query->group_id));
		if($group[0]->group_username_css) $username_colour = $group[0]->group_username_css; else $username_colour = false;
		$group = $group[0]->group_html;
		
		$smarty->assign(array(
			'NICKNAME' => Output::getClean($query->nickname),
			'USERNAME' => Output::getClean($query->username),
			'GROUP' => Output::getPurified($group),
			'USERNAME_COLOUR' => $username_colour,
			'FOLLOW' => $language->get('user', 'follow'),
			'AVATAR' => $user->getAvatar($query->id, '../', 500),
			'BANNER' => ((defined('CONFIG_PATH')) ? CONFIG_PATH : '/') . 'uploads/profile_images/' . Output::getClean($query->banner)
		));
		
		// Wall posts
		$wall_posts = array();
		$wall_posts_query = $queries->orderWhere('user_profile_wall_posts', 'user_id = ' . $query->id, 'time', 'DESC');
		
		if(count($wall_posts_query)){
			// Pagination
			$results = $paginator->getLimited($wall_posts_query, 10, $p, count($wall_posts_query));
			$pagination = $paginator->generate(7, URL::build('/profile/' . Output::getClean($query->username), ''));
			
			$smarty->assign('PAGINATION', $pagination);
			
			// Wall posts
			$replies = array();
			
			// Display the correct number of posts	
			for($n = 0; $n < count($results->data); $n++){
				$post_user = $queries->getWhere('users', array('id', '=', $results->data[$n]->author_id));
				
				if(!count($post_user)) continue;
				
				$wall_posts[] = array(
					'username' => Output::getClean($post_user[0]->username),
					'nickname' => Output::getClean($post_user[0]->nickname),
					'profile' => URL::build('/profile/' . Output::getClean($post_user[0]->username)),
					'user_style' => $user->getGroupClass($post_user[0]->id),
					'avatar' => $user->getAvatar($results->data[$n]->author_id, '../', 500),
					'content' => Output::getClean($results->data[$n]->content),
					'date_rough' => $timeago->inWords(date('d M Y, H:i', $results->data[$n]->time), $language->getTimeLanguage()),
					'date' => date('d M Y, H:i', $results->data[$n]->time)
				);
			}
			
		} else $smarty->assign('NO_WALL_POSTS', $language->get('user', 'no_wall_posts'));
			
		$smarty->assign('WALL_POSTS', $wall_posts);
		
		// Template
		$smarty->display('custom/templates/' . TEMPLATE . '/profile.tpl');
		
	} else {
		if(isset($_GET['error'])){
			// Error
		}
		// Search for user
		
	}
	
	// Footer and scripts
	require('core/templates/footer.php');
	require('core/templates/scripts.php'); 
	
	
	if($user->isLoggedIn()){
		if($user->data()->username == $profile){
			// Script for banner selector
			?>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/image-picker/image-picker.min.js"></script>
	
	<script>
	  $('#imageModal').on('show.bs.modal', function (e) {
		$("select").imagepicker();
	  })
	</script>
			<?php
		}
	}
	?>
	
  </body>
</html>