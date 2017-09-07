<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  UserCP messaging page
 */

// Must be logged in
if(!$user->isLoggedIn()){
	Redirect::to(URL::build('/'));
	die();
}
 
// Always define page name for navbar
define('PAGE', 'cc_messaging');

$timeago = new Timeago(TIMEZONE);

require('core/includes/paginate.php'); // Get number of topics on a page
require('core/includes/emojione/autoload.php'); // Emojione
require('core/includes/markdown/tohtml/Markdown.inc.php'); // Markdown to HTML
$emojione = new Emojione\Client(new Emojione\Ruleset());

// Get page
if(isset($_GET['p'])){
	if(!is_numeric($_GET['p'])){
		Redirect::to(URL::build('/user/messaging'));
		die();
	} else {
		if($_GET['p'] == 1){ 
			// Avoid bug in pagination class
			Redirect::to(URL::build('/user/messaging'));
			die();
		}
		$p = $_GET['p'];
	}
} else {
	$p = 1;
}

require('core/templates/cc_navbar.php');
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
	$title = $language->get('user', 'user_cp');
	require('core/templates/header.php'); 
	?>
	
	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/ckeditor/plugins/spoiler/css/spoiler.css">
    <link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emoji/css/emojione.min.css"/>
	<link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emoji/css/emojione.sprites.css"/>
    <link rel="stylesheet" href="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emojionearea/css/emojionearea.min.css"/>
  
  </head>
  <body>
    <?php
	require('core/templates/navbar.php');
	require('core/templates/footer.php');

	if(!isset($_GET['action'])){
		// Get private messages
		$messages = $user->listPMs($user->data()->id);
		
		// Pagination
		$paginator = new Paginator((isset($template_pagination) ? $template_pagination : array()));
		$results = $paginator->getLimited($messages, 10, $p, count($messages));
		$pagination = $paginator->generate(7, URL::build('/user/messaging/', true));
		
		$smarty->assign('PAGINATION', $pagination);

		// Array to pass to template
		$template_array = array();
		
		// Display the correct number of messages
		for($n = 0; $n < count($results->data); $n++){
			// Get participants list
			$participants = '';
			
			foreach($results->data[$n]['users'] as $item){
				$participants .= '<a href="' . URL::build('/profile/' . Output::getClean($user->idToName($item))) . '">' . Output::getClean($user->idToNickname($item)) . '</a>, ';
			}
			$participants = rtrim($participants, ', ');
			
			$template_array[] = array(
				'id' => $results->data[$n]['id'],
				'title' => Output::getClean($results->data[$n]['title']),
				'participants' => $participants,
				'link' => URL::build('/user/messaging/', 'action=view&amp;message=' . $results->data[$n]['id']),
				'last_message_user' => Output::getClean($user->idToNickname($results->data[$n]['user_updated'])),
				'last_message_user_profile' => URL::build('/profile/' . Output::getClean($user->idToName($results->data[$n]['user_updated']))),
				'last_message_user_avatar' => $user->getAvatar($results->data[$n]['user_updated'], "../", 30),
				'last_message_user_style' => $user->getGroupClass($results->data[$n]['user_updated']),
				'last_message_date' => $timeago->inWords(date('d M Y, H:i', $results->data[$n]['updated']), $language->getTimeLanguage()),
				'last_message_date_full' => date('d M Y, H:i', $results->data[$n]['updated'])
			);
		}
		
		// Assign Smarty variables
		$smarty->assign(array(
			'USER_CP' => $language->get('user', 'user_cp'),
			'MESSAGING' => $language->get('user', 'messaging'),
			'MESSAGES' => $template_array,
			'NO_MESSAGES' => $language->get('user', 'no_messages_full'),
			'NEW_MESSAGE' => $language->get('user', 'new_message'),
			'NEW_MESSAGE_LINK' => URL::build('/user/messaging/', 'action=new'),
			'MESSAGE_TITLE' => $language->get('user', 'message_title'),
			'PARTICIPANTS' => $language->get('user', 'participants'),
			'LAST_MESSAGE' => $language->get('user', 'last_message'),
			'BY' => $language->get('user', 'by')
		));
		
		$smarty->display('custom/templates/' . TEMPLATE . '/user/messaging.tpl');
	
	} else {
		if($_GET['action'] == 'new'){
			// New PM
			if(Input::exists()){
				if(Token::check(Input::get('token'))){
					// Validate input
					$validate = new Validate();
					
					$validation = $validate->check($_POST, array(
						'title' => array(
							'required' => true,
							'min' => 2,
							'max' => 64
						),
						'content' => array(
							'required' => true,
							'min' => 2,
							'max' => 20480
						),
						'to' => array(
							'required' => true
						)
					));
					
					if($validation->passed()){
						// Validation passed, validate recipients
						$users = Input::get('to');
						$users = explode(',', $users);
						$n = 0;
						
						// Replace white space at start of username, also limit to 10 users
						foreach($users as $item){
							if($item[0] === ' '){
								$users[$n] = substr($item, 1);
								$username = $users[$n];
							} else $username = $item;
							
							if($username == $user->data()->nickname || $username == $user->data()->username){
								unset($users[$n]);
								continue;
							}

                            $user_id = $user->NameToId($item);
							if($user_id){
                                if($user->isBlocked($user_id, $user->data()->id) && !$user->canViewMCP() && !$user->canViewACP()){
                                    $blocked = true;
                                    unset($users[$n]);
                                    continue;
                                }
							}
							
							if($n == 10){
								$max_users = true;
								break;
							}
							$n++;
						}

						if(isset($blocked)){
						    $error = $language->get('user', 'one_or_more_users_blocked');

                        } else if(!count($users)){
							$error = $language->get('user', 'cant_send_to_self');
							
						} else {
							// Ensure people haven't been added twice
							$users = array_unique($users);
							
							if(!isset($max_users)){
								try {
									// Input the content
									$queries->create('private_messages', array(
										'author_id' => $user->data()->id,
										'title' => Output::getClean(Input::get('title')),
										'created' => date('U'),
										'last_reply_user' => $user->data()->id,
										'last_reply_date' => date('U')
									));
									
									// Get the PM ID
									$last_id = $queries->getLastId();
									
									// Parse markdown
									$cache->setCache('post_formatting');
									$formatting = $cache->retrieve('formatting');
									
									if($formatting == 'markdown'){
										$content = Michelf\Markdown::defaultTransform(Input::get('content'));
										$content = Output::getClean($content);
									} else $content = Output::getClean(Input::get('content'));
									
									// Insert post content into database
									$queries->create('private_messages_replies', array(
										'pm_id' => $last_id,
										'author_id' => $user->data()->id,
										'created' => date('U'),
										'content' => $content
									));
									
									// Add users to conversation
									foreach($users as $item){
										// Get ID
										$user_id = $user->NameToId($item);
										
										if($user_id){
											// Not the author
											$queries->create('private_messages_users', array(
												'pm_id' => $last_id,
												'user_id' => $user_id
											));
										}
									}
									
									// Add the author to the list of users
									$queries->create('private_messages_users', array(
										'pm_id' => $last_id,
										'user_id' => $user->data()->id,
										'read' => 1
									));
									
									// Sent successfully
									Session::flash('user_messaging_success', $language->get('user', 'message_sent_successfully'));
									Redirect::to(URL::build('/user/messaging'));
									die();
									
								} catch(Exception $e){
									// Exception
									die($e->getMessage());
								}
								
							} else {
								// Over 10 users added
								$error = $language->get('user', 'max_pm_10_users');
							}
						}
					} else {
						// Errors
						$errors = array();
						
						foreach($validation->errors() as $item){
							if(strpos($item, 'is required') !== false){
								switch($item){
									case (strpos($item, 'title') !== false):
										$errors[] = $language->get('user', 'title_required');
									break;
									case (strpos($item, 'content') !== false):
										$errors[] = $language->get('user', 'content_required');
									break;
									case (strpos($item, 'to') !== false):
										$errors[] = $language->get('user', 'users_to_required');
									break;
								}
							} else if(strpos($item, 'minimum') !== false){
								switch($item){
									case (strpos($item, 'title') !== false):
										$errors[] = $language->get('user', 'title_min_2');
									break;
									case (strpos($item, 'content') !== false):
										$errors[] = $language->get('user', 'content_min_2');
									break;
								}
							} else if(strpos($item, 'maximum') !== false){
								switch($item){
									case (strpos($item, 'title') !== false):
										$errors[] = $language->get('user', 'title_max_64');
									break;
									case (strpos($item, 'content') !== false):
										$errors[] = $language->get('user', 'content_max_20480');
									break;
								}
							}
						}
						
						$error = implode('<br />', $errors);
					}
					
				} else {
					// Invalid token
					$error = $language->get('general', 'invalid_token');
				}
			}
			
			if(isset($error)) $smarty->assign('ERROR', $error);
			
			// Markdown or HTML?
			$cache->setCache('post_formatting');
			$formatting = $cache->retrieve('formatting');

			if($formatting == 'markdown'){
				// Markdown
				$smarty->assign('MARKDOWN', true);
				$smarty->assign('MARKDOWN_HELP', $language->get('general', 'markdown_help'));
			}
			
			if(isset($_GET['uid'])){
				// Messaging a specific user
				$user_messaging = $queries->getWhere('users', array('id', '=', $_GET['uid']));
				
				if(count($user_messaging)){
					$smarty->assign('TO_USER', Output::getClean($user_messaging[0]->nickname));
				}
			}
			
			// Assign Smarty variables
			$smarty->assign(array(
				'NEW_MESSAGE' => $language->get('user', 'new_message'),
				'CANCEL' => $language->get('general', 'cancel'),
				'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
				'CANCEL_LINK' => URL::build('/user/messaging'),
				'SUBMIT' => $language->get('general', 'submit'),
				'TOKEN' => Token::get(),
				'MESSAGE_TITLE' => $language->get('user', 'message_title'),
				'MESSAGE_TITLE_VALUE' => (isset($_POST['title']) ? Output::getPurified($_POST['title']) : ''),
				'CONTENT' => (isset($_POST['content']) ? Output::getPurified($_POST['content']) : ''),
				'TO' => $language->get('user', 'to'),
				'SEPARATE_USERS_WITH_COMMAS' => $language->get('user', 'separate_users_with_commas'),
				'ALL_USERS' => $user->listAllUsers()
			));
			
			// Display template
			$smarty->display('custom/templates/' . TEMPLATE . '/user/new_message.tpl');
			
		} else if($_GET['action'] == 'view'){
			// Ensure message is specified
			if(!isset($_GET['message']) || !is_numeric($_GET['message'])){
				Redirect::to(URL::build('/user/messaging'));
				die();
			}
			
			// Ensure message exists
			$pm = $user->getPM($_GET['message'], $user->data()->id); // Get the PM - this also handles setting it as "read" 
			
			if($pm == false){ // Either PM doesn't exist, or the user doesn't have permission to view it
				Redirect::to(URL::build('/user/messaging'));
				die();
			}

			// Deal with input
			if(Input::exists()){
				// Check token
				if(Token::check(Input::get('token'))){
					// Valid token
					// Validate input
					$validate = new Validate();
					
					$validation = $validate->check($_POST, array(
						'content' => array(
							'required' => true,
							'min' => 2,
							'max' => 20480
						)
					));
					
					if($validation->passed()){
						// Parse markdown
						$cache->setCache('post_formatting');
						$formatting = $cache->retrieve('formatting');
						
						if($formatting == 'markdown'){
							$content = Michelf\Markdown::defaultTransform(Input::get('content'));
							$content = Output::getClean($content);
						} else $content = Output::getClean(Input::get('content'));
						
						// Insert post content into database
						$queries->create('private_messages_replies', array(
							'pm_id' => $pm[0]->id,
							'author_id' => $user->data()->id,
							'created' => date('U'),
							'content' => $content
						));
						
						// Update last reply PM information
						$queries->update('private_messages', $pm[0]->id, array(
							'last_reply_user' => $user->data()->id,
							'last_reply_date' => date('U')
						));
						
						// Update PM as unread for all users
						$users = $queries->getWhere('private_messages_users', array('pm_id', '=', $pm[0]->id));
						
						foreach($users as $item){
							if($item->user_id != $user->data()->id){
								$queries->update('private_messages_users', $item->id, array(
									'`read`' => 0
								));
							}
						}
						
						// Display success message
						$smarty->assign('MESSAGE_SENT', $language->get('user', 'message_sent_successfully'));
						unset($_POST['content']);
						
					} else {
						// Errors
						foreach($validation->errors() as $item){
							if(strpos($item, 'is required') !== false){
								$error = $language->get('user', 'content_required');
								
							} else if(strpos($item, 'minimum') !== false){
								$error = $language->get('user', 'content_min_2');

							} else if(strpos($item, 'maximum') !== false){
								$error = $language->get('user', 'content_max_20480');

							}
						}
					}
					
				} else {
					// Invalid token
					$error = $language->get('general', 'invalid_token');
				}
			}
			
			if(isset($error)) $smarty->assign('ERROR', $error);
			
			// Get all PM replies
			$pm_replies = $queries->getWhere('private_messages_replies', array('pm_id', '=', $_GET['message']));
			
			// Pagination
			$paginator = new Paginator((isset($template_pagination) ? $template_pagination : array()));
			$results = $paginator->getLimited($pm_replies, 10, $p, count($pm_replies));
			$pagination = $paginator->generate(7, URL::build('/user/messaging/', 'action=view&amp;message=' . $pm[0]->id . '&amp;'));
			
			$smarty->assign('PAGINATION', $pagination);

			// Array to pass to template
			$template_array = array();
			
			// Display the correct number of messages
			for($n = 0; $n < count($results->data); $n++){
				$template_array[] = array(
					'id' => $results->data[$n]->id,
					'author_username' => Output::getClean($user->idToNickname($results->data[$n]->author_id)),
					'author_profile' => URL::build('/profile/' . Output::getClean($user->idToName($results->data[$n]->author_id))),
					'author_avatar' => $user->getAvatar($results->data[$n]->author_id, "../", 100),
					'author_style' => $user->getGroupClass($results->data[$n]->author_id),
					'author_groups' => $user->getAllGroups($results->data[$n]->author_id, 'true'),
					'message_date' => $timeago->inWords(date('d M Y, H:i', $results->data[$n]->created), $language->getTimeLanguage()),
					'message_date_full' => date('d M Y, H:i', $results->data[$n]->created),
					'content' => Output::getPurified($emojione->unicodeToImage(htmlspecialchars_decode($results->data[$n]->content)))
				);
			}
			
			// Get participants list
			$participants = '';
			
			foreach($pm[1] as $item){
				$participants .= '<a href="' . URL::build('/profile/' . Output::getClean($user->idToName($item))) . '">' . Output::getClean($user->idToNickname($item)) . '</a>, ';
			}
			$participants = rtrim($participants, ', ');
			
			// Smarty variables
			$smarty->assign(array(
				'MESSAGE_TITLE' => Output::getClean($pm[0]->title),
				'BACK' => $language->get('general', 'back'),
				'BACK_LINK' => URL::build('/user/messaging'),
				'LEAVE_CONVERSATION' => $language->get('user', 'leave_conversation'),
				'CONFIRM_LEAVE' => $language->get('user', 'confirm_leave'),
				'LEAVE_CONVERSATION_LINK' => URL::build('/user/messaging/', 'action=leave&amp;message=' . $pm[0]->id),
				'PAGINATION' => $pagination,
				'PARTICIPANTS_TEXT' => $language->get('user', 'participants'),
				'PARTICIPANTS' => $participants,
				'MESSAGES' => $template_array,
				'NEW_REPLY' => $language->get('user', 'new_reply'),
				'TOKEN' => Token::get(),
				'SUBMIT' => $language->get('general', 'submit')
			));
			
			// Markdown or HTML?
			$cache->setCache('post_formatting');
			$formatting = $cache->retrieve('formatting');

			if($formatting == 'markdown'){
				// Markdown
				$smarty->assign('MARKDOWN', true);
				$smarty->assign('MARKDOWN_HELP', $language->get('general', 'markdown_help'));
			}
			
			if(isset($_POST['content']))
				$smarty->assign('CONTENT', Output::getClean($_POST['content']));
			else $smarty->assign('CONTENT', '');
			
			// Display Smarty template
			$smarty->display('custom/templates/' . TEMPLATE . '/user/view_message.tpl');
			
		} else if($_GET['action'] == 'leave'){
			// Try to remove the user from the conversation
			if(!isset($_GET['message']) || !is_numeric($_GET['message'])){
				Redirect::to(URL::build('/user/messaging'));
				die();
			}
			
			$message = $queries->getWhere('private_messages_users', array('pm_id', '=', $_GET['message']));
			
			if(count($message)){
				foreach($message as $item){
					if($item->user_id == $user->data()->id){
						$queries->delete('private_messages_users', array('id', '=', $item->id));
						break;
					}
				}
			}
			
			// Done, redirect
			Redirect::to(URL::build('/user/messaging'));
			die();
		}
	}

    require('core/templates/scripts.php');
	
	// Display either Markdown or HTML editor
	if(!isset($formatting)){
		$cache->setCache('post_formatting');
		$formatting = $cache->retrieve('formatting');
	}
	if($formatting == 'markdown'){
	?>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emoji/js/emojione.min.js"></script>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emojionearea/js/emojionearea.min.js"></script>
	
	<script type="text/javascript">
	  $(document).ready(function() {
	    var el = $("#markdown").emojioneArea({
			pickerPosition: "bottom"
		});
	  });
	</script>
	<?php } else { ?>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emoji/js/emojione.min.js"></script>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/ckeditor/plugins/spoiler/js/spoiler.js"></script>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/ckeditor/ckeditor.js"></script>
	
	<script type="text/javascript">
		<?php echo Input::createEditor('reply'); ?>
	</script>
	<?php } ?>
	
  </body>
</html>