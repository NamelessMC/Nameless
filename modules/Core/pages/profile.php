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
$timeago = new Timeago(TIMEZONE);

require('core/includes/emojione/autoload.php'); // Emojione
$emojione = new Emojione\Client(new Emojione\Ruleset());

require('core/includes/paginate.php'); // Get number of wall posts on a page
?>
<!DOCTYPE html>
<html lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>">
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
						
						case 'new_post':
							if(Token::check(Input::get('token'))){
								// Valid token
								$validate = new Validate();
								
								$validation = $validate->check($_POST, array(
									'post' => array(
										'required' => true,
										'min' => 1,
										'max' => 10000
									)
								));
								
								if($validation->passed()){
									// Validation successful
									// Input into database
									$queries->create('user_profile_wall_posts', array(
										'user_id' => $query->id,
										'author_id' => $user->data()->id,
										'time' => date('U'),
										'content' => Output::getClean(Input::get('post'))
									));
									
									if($query->id !== $user->data()->id){
										// Alert user
										Alert::create($query->id, 'profile_post', str_replace('{x}', Output::getClean($user->data()->nickname), $language->get('user', 'new_wall_post')), str_replace('{x}', Output::getClean($user->data()->nickname), $language->get('user', 'new_wall_post')), URL::build('/profile/' . Output::getClean($query->username)));
									}
									
									// Redirect to clear input
									Redirect::to(URL::build('/profile/' . Output::getClean($query->username)));
									die();
									
								} else {
									// Validation failed
									$error = $language->get('user', 'invalid_wall_post');
								}
								
							} else {
								$error = $language->get('general', 'invalid_token');
							}
						break;
						
						case 'reply':
							if(Token::check(Input::get('token'))){
								// Valid token
								$validate = new Validate();
								
								$validation = $validate->check($_POST, array(
									'reply' => array(
										'required' => true,
										'min' => 1,
										'max' => 10000
									),
									'post' => array(
										'required' => true
									)
								));
								
								if($validation->passed()){
									// Validation successful
									
									// Ensure post exists
									$post = $queries->getWhere('user_profile_wall_posts', array('id', '=', $_POST['post']));
									if(!count($post)){
										Redirect::to(URL::build('/profile/' . Output::getClean($query->username)));
										die();
									}
									
									// Input into database
									$queries->create('user_profile_wall_posts_replies', array(
										'post_id' => $_POST['post'],
										'author_id' => $user->data()->id,
										'time' => date('U'),
										'content' => Output::getClean(Input::get('reply'))
									));
									
									if($query->id !== $user->data()->id){
										// Alert user
										Alert::create($query->id, 'profile_post', str_replace('{x}', Output::getClean($user->data()->nickname), $language->get('user', 'new_wall_post')), str_replace('{x}', Output::getClean($user->data()->nickname), $language->get('user', 'new_wall_post')), URL::build('/profile/' . Output::getClean($query->username)));
									}
									
									// Redirect to clear input
									Redirect::to(URL::build('/profile/' . Output::getClean($query->username)));
									die();
									
								} else {
									// Validation failed
									$error = $language->get('user', 'invalid_wall_post');
								}
								
							} else {
								$error = $language->get('general', 'invalid_token');
							}
						break;
						
					}
				}
			}
		}
		
		if($user->isLoggedIn()){
			if(isset($_GET['action'])){
				switch($_GET['action']){
					case 'react':
						if(!isset($_GET['post']) || !is_numeric($_GET['post'])){
							// Post ID required
							Redirect::to(URL::build('/profile/' . Output::getClean($query->username)));
							die();
						}
						
						// Does the post exist?
						$post = $queries->getWhere('user_profile_wall_posts', array('id', '=', $_GET['post']));
						if(!count($post)){
							Redirect::to(URL::build('/profile/' . Output::getClean($query->username)));
							die();
						}
						
						// Can't like our own post
						if($post[0]->author_id == $user->data()->id){
							Redirect::to(URL::build('/profile/' . Output::getClean($query->username)));
							die();
						}
						
						// Liking or unliking?
						$post_likes = $queries->getWhere('user_profile_wall_posts_reactions', array('post_id', '=', $_GET['post']));
						if(count($post_likes)){
							foreach($post_likes as $like){
								if($like->user_id == $user->data()->id){
									$has_liked = $like->id;
									break;
								}
							}
						}
						
						if(isset($has_liked)){
							// Unlike
							$queries->delete('user_profile_wall_posts_reactions', array('id', '=', $has_liked));
						} else {
							// Like
							$queries->create('user_profile_wall_posts_reactions', array(
								'user_id' => $user->data()->id,
								'post_id' => $_GET['post'],
								'reaction_id' => 1,
								'time' => date('U')
							));
						}
						
						// Redirect
						Redirect::to(URL::build('/profile/' . Output::getClean($query->username)));
						die();
						
					break;
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
				'TOKEN' => Token::get(),
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
				$smarty->assign('MESSAGE_LINK', URL::build('/user/messaging/', 'action=new&amp;uid=' . $query->id));
				$smarty->assign('FOLLOW_LINK', URL::build('/user/follow/', 'user=' . $query->id));
			}
		}
		
		// Get user's group
		$group = $queries->getWhere('groups', array('id', '=', $query->group_id));
		$group = $group[0]->group_html_lg;
		
		// Get list of reactions
		//$reactions = $queries->getWhere('reactions', array('enabled', '=', 1));
		
		$smarty->assign(array(
			'NICKNAME' => Output::getClean($query->nickname),
			'USERNAME' => Output::getClean($query->username),
			'GROUP' => Output::getPurified($group),
			'USERNAME_COLOUR' => $user->getGroupClass($query->id),
			'USER_TITLE' => Output::getClean($query->user_title),
			'FOLLOW' => $language->get('user', 'follow'),
			'AVATAR' => $user->getAvatar($query->id, '../', 500),
			'BANNER' => ((defined('CONFIG_PATH')) ? CONFIG_PATH : '/') . 'uploads/profile_images/' . Output::getClean($query->banner),
			'POST_ON_WALL' => str_replace('{x}', Output::getClean($query->nickname), $language->get('user', 'post_on_wall')),
			'FEED' => $language->get('user', 'feed'),
			'ABOUT' => $language->get('user', 'about'),
			'REACTIONS_TITLE' => $language->get('user', 'likes'),
			//'REACTIONS' => $reactions,
			'CLOSE' => $language->get('general', 'close'),
			'REPLIES_TITLE' => $language->get('user', 'replies'),
			'NO_REPLIES' => $language->get('user', 'no_replies_yet'),
			'NEW_REPLY' => $language->get('user', 'new_reply')
		));
		
		// Wall posts
		$wall_posts = array();
		$wall_posts_query = $queries->orderWhere('user_profile_wall_posts', 'user_id = ' . $query->id, 'time', 'DESC');
		
		if(count($wall_posts_query)){
			// Pagination
			$results = $paginator->getLimited($wall_posts_query, 10, $p, count($wall_posts_query));
			$pagination = $paginator->generate(7, URL::build('/profile/' . Output::getClean($query->username) . '/', true));
			
			$smarty->assign('PAGINATION', $pagination);
			
			// Display the correct number of posts	
			for($n = 0; $n < count($results->data); $n++){
				$post_user = $queries->getWhere('users', array('id', '=', $results->data[$n]->author_id));
				
				if(!count($post_user)) continue;
				
				// Get reactions/replies
				$reactions = array();
				$replies = array();
				
				$reactions_query = $queries->getWhere('user_profile_wall_posts_reactions', array('post_id', '=', $results->data[$n]->id));
				if(count($reactions_query)){
					if(count($reactions_query) == 1)
						$reactions['count'] = $language->get('user', '1_like');
					else 
						$reactions['count'] = str_replace('{x}', count($reactions_query), $language->get('user', 'x_likes'));
					
					foreach($reactions_query as $reaction){
						// Get reaction name and icon
						// TODO
						/*
						$reaction_name = $queries->getWhere('reactions', array('id', '=', $reaction->reaction_id));
						
						if(!count($reaction_name) || $reaction_name[0]->enabled == 0) continue;
						$reaction_html = $reaction_name[0]->html;
						$reaction_name = Output::getClean($reaction_name[0]->name);
						*/
						
						$reactions['reactions'][] = array(
							'username' => Output::getClean($user->idToName($reaction->user_id)),
							'nickname' => Output::getClean($user->idToNickname($reaction->user_id)),
							'style' => $user->getGroupClass($reaction->user_id),
							'profile' => URL::build('/profile/' . Output::getClean($user->idToName($reaction->user_id))),
							'avatar' => $user->getAvatar($reaction->user_id, '../', 500),
							//'reaction_name' => $reaction_name,
							//'reaction_html' => $reaction_html
						);
					}
				} else $reactions['count'] = str_replace('{x}', 0, $language->get('user', 'x_likes'));
				$reactions_query = null;
				
				$replies_query = $queries->orderWhere('user_profile_wall_posts_replies', 'post_id = ' . $results->data[$n]->id, 'time', 'ASC');
				if(count($replies_query)){
					if(count($replies_query) == 1)
						$replies['count'] = $language->get('user', '1_reply');
					else 
						$replies['count'] = str_replace('{x}', count($replies_query), $language->get('user', 'x_replies'));
					
					foreach($replies_query as $reply){
						$replies['replies'][] = array(
							'username' => Output::getClean($user->idToName($reply->author_id)),
							'nickname' => Output::getClean($user->idToNickname($reply->author_id)),
							'style' => $user->getGroupClass($reply->author_id),
							'profile' => URL::build('/profile/' . Output::getClean($user->idToName($reply->author_id))),
							'avatar' => $user->getAvatar($reply->author_id, '../', 500),
							'time_friendly' => $timeago->inWords(date('d M Y, H:i', $reply->time), $language->getTimeLanguage()),
							'time_full' => date('d M Y, H:i', $reply->time),
							'content' => Output::getPurified($reply->content)
						);
					}
				} else $replies['count'] = str_replace('{x}', 0, $language->get('user', 'x_replies'));
				$replies_query = null;
				
				$wall_posts[] = array(
					'id' => $results->data[$n]->id,
					'username' => Output::getClean($post_user[0]->username),
					'nickname' => Output::getClean($post_user[0]->nickname),
					'profile' => URL::build('/profile/' . Output::getClean($post_user[0]->username)),
					'user_style' => $user->getGroupClass($post_user[0]->id),
					'avatar' => $user->getAvatar($results->data[$n]->author_id, '../', 500),
					'content' => Output::getPurified(htmlspecialchars_decode($results->data[$n]->content)),
					'date_rough' => $timeago->inWords(date('d M Y, H:i', $results->data[$n]->time), $language->getTimeLanguage()),
					'date' => date('d M Y, H:i', $results->data[$n]->time),
					'reactions' => $reactions,
					'replies' => $replies,
					'reactions_link' => ($user->isLoggedIn() && ($post_user[0]->id != $user->data()->id) ? URL::build('/profile/' . Output::getClean($query->username) . '/', 'action=react&amp;post=' . $results->data[$n]->id) : '#')
				);
			}
			
		} else $smarty->assign('NO_WALL_POSTS', $language->get('user', 'no_wall_posts'));
		
		$smarty->assign('WALL_POSTS', $wall_posts);
		
		if(isset($error)) $smarty->assign('ERROR', $error);
		
		// About tab
		$fields = array();
		
		// Get profile fields
		$profile_fields = $queries->getWhere('users_profile_fields', array('user_id', '=', $query->id));
		if(count($profile_fields)){
			foreach($profile_fields as $field){
				// Get field
				$profile_field = $queries->getWhere('profile_fields', array('id', '=', $field->field_id));
				if(!count($profile_field)) continue;
				else $profile_field = $profile_field[0];
				
				if($profile_field->public == 0) continue;
				
				// Get field type
				switch($profile_field->type){
					case 1:
						$type = 'text';
					break;
					case 2:
						$type = 'textarea';
					break;
					case 3:
						$type = 'date';
					break;
				}
				
				$fields[] = array(
					'title' => Output::getClean($profile_field->name),
					'type' => $type,
					'value' => Output::getPurified(Util::urlToAnchorTag(htmlspecialchars_decode($field->value)))
				);
			}
		}
		
		// Minecraft?
		$minecraft_integration = $queries->getWhere('settings', array('name', '=', 'mc_integration'));
		$minecraft_integration = $minecraft_integration[0];
		
		if($minecraft_integration->value == '1'){
			$fields['minecraft'] = array(
				'title' => $language->get('user', 'ign'),
				'type' => 'text',
				'value' => Output::getClean($query->username),
				'image' => 'https://crafatar.com/renders/body/' . $query->uuid . '?overlay'
			);
		}
		
		// Add date registered and last seen
		$fields['registered'] = array(
			'title' => $language->get('user', 'registered'),
			'type' => 'text',
			'value' => $timeago->inWords(date('d M Y, H:i', $query->joined), $language->getTimeLanguage()),
			'tooltip' => date('d M Y, H:i', $query->joined)
		);
		$fields['last_seen'] = array(
			'title' => $language->get('user', 'last_seen'),
			'type' => 'text',
			'value' => $timeago->inWords(date('d M Y, H:i', $query->last_online), $language->getTimeLanguage()),
			'tooltip' => date('d M Y, H:i', $query->last_online)
		);
		
		$smarty->assign('ABOUT_FIELDS', $fields);
		
		// Custom tabs
		$tabs = array();
		if(isset($profile_tabs) && count($profile_tabs)){
			foreach($profile_tabs as $key => $tab){
				$tabs[$key] = array('title' => $tab['title'], 'include' => $tab['smarty_template']);
				if(is_file($tab['require'])) require($tab['require']);
			}
		}
		
		// Assign profile tabs
		$smarty->assign('TABS', $tabs);
		
		// Template
		$smarty->display('custom/templates/' . TEMPLATE . '/profile.tpl');
		
	} else {
		if(isset($_GET['error'])){
			// Error
			echo 'Couldn\'t find that user.';
		}
		// Search for user
		// TODO
	}
	
	// Footer and scripts
	require('core/templates/footer.php');
	require('core/templates/scripts.php'); 
	
	if(isset($directories[1]) && !empty($directories[1]) && !isset($_GET['error']) && $user->isLoggedIn()){
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
