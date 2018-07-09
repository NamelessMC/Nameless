<?php
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

if(isset($profile)){
	require_once('core/integration/uuid.php'); // For UUID stuff
	require('core/includes/htmlpurifier/HTMLPurifier.standalone.php'); // HTMLPurifier

	// Is UUID linking enabled?
	$uuid_linking = $queries->getWhere('settings', array('name', '=', 'uuid_linking'));
	$uuid_linking = $uuid_linking[0]->value;

	$profile_user = $queries->getWhere("users", array("username", "=", $profile)); // Is it their username?
	if(!count($profile_user)){ // No..
		$profile_user = $queries->getWhere("users", array("mcname", "=", $profile)); // Is it their Minecraft username?
		if(!count($profile_user)){ // No..
			$exists = false;
			$uuid = $queries->getWhere("uuid_cache", array("mcname", "=", $profile)); // Get the UUID, maybe they haven't registered yet
			if(!count($uuid)){
				if($uuid_linking == '1'){ // is UUID linking enabled?
					$profile_utils = ProfileUtils::getProfile($profile);

					if($profile_utils == null){ // Not a Minecraft user, end the page
						Redirect::to('/404');
						die();
					}

					// Get results as array
					$result = $profile_utils->getProfileAsArray();

					if(empty($result['uuid'])){ // Not a Minecraft user, end the page
						Redirect::to('/404');
						die();

					}

					$uuid = $result["uuid"];
					$mcname = htmlspecialchars($profile, ENT_QUOTES);
					// Cache the UUID so we don't have to keep looking it up via Mojang's servers
					try {
						$queries->create("uuid_cache", array(
							'mcname' => $mcname,
							'uuid' => $uuid
						));
					} catch(Exception $e){
						die($e->getMessage());
					}
				} else {
					$mcname = htmlspecialchars($profile, ENT_QUOTES);
				}
			} else {
				$uuid = $uuid[0]->uuid;
				$mcname = htmlspecialchars($profile, ENT_QUOTES);
			}
		} else {
			$exists = true;
			$uuid = htmlspecialchars($profile_user[0]->uuid);
			$mcname = htmlspecialchars($profile_user[0]->mcname);
		}
	} else {
		$exists = true;
		$uuid = htmlspecialchars($profile_user[0]->uuid);
		$mcname = htmlspecialchars($profile_user[0]->mcname);
	}

	// Redirect to fix pagination if URL does not end in /
	if(substr($_SERVER['REQUEST_URI'], -1) !== '/' && !strpos($_SERVER['REQUEST_URI'], '?')){
		echo '<script data-cfasync="false">window.location.replace(\'/profile/' . $mcname . '/\');</script>';
		die();
	}

	if($user->isLoggedIn()){
		if(!isset($_POST['action'])){
			if(isset($_POST['AddFriend'])) {
				if(Token::check(Input::get('token'))){
					$user->addfriend($user->data()->id, $profile_user[0]->id);
				}
			}
			if(isset($_POST['RemoveFriend'])){
				if(Token::check(Input::get('token'))){
					$user->removefriend($user->data()->id, $profile_user[0]->id);
				}
			}
		} else {
			if($_POST['action'] == 'reply'){
				// Reply to profile post
				if(Token::check(Input::get('token'))){
					// Validate input
					$validate = new Validate();

					$validation = $validate->check($_POST, array(
						'post_reply' => array(
							'required' => true,
							'min' => 2,
							'max' => 2048
						)
					));

					if($validation->passed()) {
						// Validation successful
						// Input into database
						$queries->create('user_profile_wall_posts_replies', array(
							'post_id' => Input::get('pid'),
							'author_id' => $user->data()->id,
							'time' => date('U'),
							'content' => htmlspecialchars(Input::get('post_reply'))
						));

						// Alert original post user
						$original_post_id = $queries->getWhere('user_profile_wall_posts', array('id', '=', Input::get('pid')));
						$original_post_id = $original_post_id[0]->author_id;

						if ($profile_user[0]->id !== $original_post_id && $profile_user[0]->id !== $user->data()->id) {
							// Alert profile user
							$queries->create('alerts', array(
								'user_id' => $profile_user[0]->id,
								'type' => 'Profile Post',
								'url' => '/profile/' . $mcname,
								'content' => htmlspecialchars($user->data()->username) . ' has replied to a post on your profile.',
								'created' => date('U')
							));

							$queries->create('alerts', array(
								'user_id' => $original_post_id,
								'type' => 'Profile Post',
								'url' => '/profile/' . $mcname,
								'content' => htmlspecialchars($user->data()->username) . ' has replied to your post on ' . htmlspecialchars($mcname) . '\'s profile.',
								'created' => date('U')
							));
						}

						// Redirect to clear input
						echo '<script data-cfasync="false">window.location.replace("/profile/' . $mcname . '");</script>';
						die();

					} else {
						// Validation failed
						Session::flash('user_wall', '<div class="alert alert-danger">' . $user_language['invalid_wall_post'] . '</div>');
					}

				} else {
					// Invalid token
					Session::flash('user_wall', '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>');

				}
			} else {
				// Profile post
				if(Token::check(Input::get('token'))){
					// Validate input
					$validate = new Validate();

					$validation = $validate->check($_POST, array(
						'wall_post' => array(
							'required' => true,
							'min' => 2,
							'max' => 2048
						)
					));

					if($validation->passed()) {
						// Validation successful
						// Input into database
						$queries->create('user_profile_wall_posts', array(
							'user_id' => $profile_user[0]->id,
							'author_id' => $user->data()->id,
							'time' => date('U'),
							'content' => htmlspecialchars(Input::get('wall_post'))
						));

						if ($profile_user[0]->id !== $user->data()->id) {
							// Alert user
							$queries->create('alerts', array(
								'user_id' => $profile_user[0]->id,
								'type' => 'Profile Post',
								'url' => '/profile/' . $mcname,
								'content' => htmlspecialchars($user->data()->username) . ' has posted on your profile.',
								'created' => date('U')
							));
						}

						// Redirect to clear input
						echo '<script data-cfasync="false">window.location.replace("/profile/' . $mcname . '");</script>';
						die();

					} else {
						// Validation failed
						Session::flash('user_wall', '<div class="alert alert-danger">' . $user_language['invalid_wall_post'] . '</div>');
					}

				} else {
					// Invalid token
					Session::flash('user_wall', '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>');
				}
			}
		}

		if(isset($_GET['action'])){
			if($_GET['action'] == 'like' && isset($_GET['post']) && is_numeric($_GET['post'])){
				// Liking or unliking?
				$post_likes = $queries->getWhere('user_profile_wall_posts_likes', array('post_id', '=', $_GET['post']));

				foreach($post_likes as $post_like){
					if($post_like->user_id == $user->data()->id){
						$post_like_id = $post_like->id;
						$liked = true;
						break;
					}
				}

				if(isset($liked)){
					// Unliking
					$queries->delete('user_profile_wall_posts_likes', array('id', '=', $post_like_id));

					Session::flash('user_wall', '<div class="alert alert-info">' . $user_language['post_unliked'] . '</div>');
					echo '<script data-cfasync="false">window.location.replace("/profile/' . $mcname . '");</script>';
					die();
				} else {
					// Liking
					$queries->create('user_profile_wall_posts_likes', array(
						'post_id' => $_GET['post'],
						'user_id' => $user->data()->id
					));

					Session::flash('user_wall', '<div class="alert alert-info">' . $user_language['post_liked'] . '</div>');
					echo '<script data-cfasync="false">window.location.replace("/profile/' . $mcname . '");</script>';
					die();
				}
			} else if($_GET['action'] == 'delete'){
				// Ensure user is moderator
				if($user->canViewMCP($user->data()->id)){
					if(isset($_GET['pid']) && is_numeric($_GET['pid'])){
						// Delete post
						$queries->delete('user_profile_wall_posts_likes', array('post_id', '=', $_GET['pid']));
						$queries->delete('user_profile_wall_posts_replies', array('post_id', '=', $_GET['pid']));
						$queries->delete('user_profile_wall_posts', array('id', '=', $_GET['pid']));

						echo '<script data-cfasync="false">window.location.replace("/profile/' . $mcname . '");</script>';
						die();

					} else if(isset($_GET['r']) && is_numeric($_GET['r'])){
						// Delete post reply
						$queries->delete('user_profile_wall_posts_replies', array('id', '=', $_GET['r']));

						echo '<script data-cfasync="false">window.location.replace("/profile/' . $mcname . '");</script>';
						die();

					}
				}
			}
		}

		$token = Token::generate();
	}

	// Is the user online?
	if($exists == true && strtotime("-10 minutes") < $profile_user[0]->last_online) $is_online = true;

	// Pagination
	require('core/includes/paginate.php');
	$pagination = new Pagination();

	// Infractions
	if(isset($infractions_language)){
		require('addons/Infractions/config.php');
		require('addons/Infractions/Infractions.php');

		$infractions = new Infractions($inf_db, $infractions_language);
		$timeago = new Timeago();

		// Get current plugin in use
		$inf_plugin = $queries->getWhere('infractions_settings', array('id', '=', 1));
		$inf_plugin = $inf_plugin[0]->value;

		$longuuid = ProfileUtils::formatUUID($profile_user[0]->uuid);
	}

	// Get page
	if(isset($_GET['p'])){
		if(!is_numeric($_GET['p'])){
			Redirect::to('/profile/' . $mcname);
			die();
		} else {
			if($_GET['p'] == 1){
				// Avoid bug in pagination class
				Redirect::to('/profile/' . $mcname);
				die();
			}
			$p = $_GET['p'];
		}
	} else {
		$p = 1;
	}

	// HTMLPurifier
	$config = HTMLPurifier_Config::createDefault();
	$config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
	$config->set('URI.DisableExternalResources', false);
	$config->set('URI.DisableResources', false);
	$config->set('HTML.Allowed', 'u,p,b,i,a,small,blockquote,span[style],span[class],p,strong,em,li,ul,ol,div[align],br,img');
	$config->set('CSS.AllowedProperties', array('text-align', 'float', 'color','background-color', 'background', 'font-size', 'font-family', 'text-decoration', 'font-weight', 'font-style', 'font-size'));
	$config->set('HTML.AllowedAttributes', 'target, href, src, height, width, alt, class, *.style');
	$config->set('Attr.AllowedFrameTargets', array('_blank', '_self', '_parent', '_top'));
	$config->set('HTML.SafeIframe', true);
	$config->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%');
	$config->set('Core.EscapeInvalidTags', true);
	$purifier = new HTMLPurifier($config);

	// Enable username history?
	$name_history = $queries->getWhere('settings', array('name', '=', 'enable_name_history'));
	if(count($name_history))
		$name_history = $name_history[0]->value;
	else
		$name_history = 1;
}
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="User profile page &bull; <?php echo $sitename; ?>">
      <meta name="author" content="<?php echo $sitename; ?>">
      <meta name="theme-color" content="#454545" />
      <?php if(isset($custom_meta)){ echo $custom_meta; } ?>

	  <?php
	  // Generate header and navbar content
	  // Page title
	  $title = $user_language['profile'] . (isset($profile) ? ' - ' . $profile : '');

	  require('core/includes/template/generate.php');

	  ?>
      <!-- Custom style -->
      <style>
        html {
            overflow-y: scroll;
        }
        .jumbotron {
            margin-bottom: 0px;
            background-image: url(/core/assets/img/profile.jpg);
            background-position: 0% 25%;
            background-size: cover;
            background-repeat: no-repeat;
            color: white;
        }
		.image_container {
			/* width: 262.5px;
			height: 262.5px;*/
			text-align: center;
			/*line-height: 262.5px;*/
		}
      </style>
   </head>
   <body>
      <?php
         // Load navbar
         $smarty->display('styles/templates/' . $template . '/navbar.tpl');
         ?>
      <br />
      <div class="container">
         <?php if(isset($profile)){ ?>
         <div class="row">
            <div class="col-md-3">
			  <div class="image_container">
			  <?php
			  // User avatar
			  if(!($exists)){
				// Get the avatar type in use
				$avatar_type = $queries->getWhere('settings', array('name', '=', 'avatar_type'));
				$avatar_type = $avatar_type[0]->value;

				echo '<img class="img-rounded" src="https://cravatar.eu/' . htmlspecialchars($avatar_type) . '/' . $mcname . '/300.png" />';
			  }
			  else echo '<img class="img-rounded" style="vertical-align: middle;" src="' . $user->getAvatar($profile_user[0]->id, "../", 300) . '" />';
			  ?>
			  </div>
			  <br />
			  <?php
			    // Follower system or friend system?
				$use_followers = $queries->getWhere('settings', array('name', '=', 'followers'));
				$use_followers = $use_followers[0]->value;
				if($use_followers == '1'){
					// Followers
					if($exists == true) $followers = $user->listFollowers($profile_user[0]->id);
				?>
              <div class="panel panel-info">
                <div class="panel-heading">
                  <h4 style="display: inline-block;"><?php echo $user_language['followers']; ?></h4>
                  <h4 style="display: inline-block; float: right; text-align: right;">(<?php if(!$exists || $followers == false) echo '0'; else echo count($followers); ?>)</h4>
                </div>
                <div class="panel-body">
                     <?php
                        if($exists == true){
                           if($followers !== false){
                              foreach($followers as $follower){
                                  echo '<span rel="tooltip" title="' . htmlspecialchars($user->IdToName($follower->user_id)) . '"><a href="/profile/' . htmlspecialchars($user->IdToMCName($follower->user_id)) . '">';
								  echo '<img class="img-rounded" style="padding-bottom:2.5px; height: 40px; width: 40px;" src="' . $user->getAvatar($follower->user_id, "../", 40) . '" />';
								  echo '</a></span>&nbsp;';
                              }
                           } else {
                              echo $user_language['user_no_followers'];
                           }
                        } else {
                           echo $user_language['user_no_followers'];
                        }
                        ?>
                </div>
              </div>
			  <?php
				if($exists == true) $following = $user->listFriends($profile_user[0]->id); // Same method as listing friends
			  ?>
              <div class="panel panel-info">
                  <div class="panel-heading">
                     <h4 style="display: inline-block;"><?php echo $user_language['following']; ?></h4>
                     <h4 style="display: inline-block; float: right; text-align: right;">(<?php if(!$exists || $following == false) echo '0'; else echo count($following); ?>)</h4>
                  </div>
                  <div class="panel-body">
                     <?php
                        if($exists == true){
                           if($following !== false){
                              foreach($following as $item){
                                  echo '<span rel="tooltip" title="' . htmlspecialchars($user->IdToName($item->friend_id)) . '"><a href="/profile/' . htmlspecialchars($user->IdToMCName($item->friend_id)) . '">';
								  echo '<img class="img-rounded" style="padding-bottom:2.5px; height: 40px; width: 40px;" src="' . $user->getAvatar($item->friend_id, "../", 40) . '" />';
								  echo '</a></span>&nbsp;';
                              }
                           } else {
                              echo $user_language['user_not_following'];
                           }
                        } else {
                           echo $user_language['user_not_following'];
                        }
                        ?>
                  </div>
              </div>
				<?php
				} else {
					// Friends
					if($exists == true) $friends = $user->listFriends($profile_user[0]->id); else $friends = false;
					?>
              <div class="panel panel-info">
                  <div class="panel-heading">
                     <h4 style="display: inline-block;"><?php echo $user_language['friends']; ?></h4>
                     <h4 style="display: inline-block; float: right; text-align: right;">(<?php if(!$exists || $friends == false) echo '0'; else echo count($friends); ?>)</h4>
                  </div>
                  <div class="panel-body">
					<?php
					if($exists == true){
						$friends = $user->listFriends($profile_user[0]->id);
						if($friends !== false){
							foreach($friends as $friend){
								echo '<span rel="tooltip" title="' . htmlspecialchars($user->IdToName($friend->friend_id)) . '"><a href="/profile/' . htmlspecialchars($user->IdToMCName($friend->friend_id)) . '">';
								echo '<img class="img-rounded" style="padding-bottom:2.5px; height: 40px; width: 40px;" src="' . $user->getAvatar($friend->friend_id, "../", 40) . '" />';
								echo '</a></span>&nbsp;';
							}
						} else {
							echo $user_language['user_no_friends'];
						}
					} else {
						echo $user_language['user_no_friends'];
					}
				?>
                  </div>
              </div>
				<?php
				}
			  ?>
            </div>
            <div class="col-md-9">
               <div class="jumbotron">
                  <h2 style="display: inline-block;">
                     <?php echo $mcname; ?>
                     <?php
                        if($exists == true){
                           echo $user->getGroup($profile_user[0]->id, null, "true");
                           echo "   ";
                           if($user->getGroup2($profile_user[0]->id, null, null)) {
                               echo $user->getGroup2($profile_user[0]->id, null, "true");
                           }
                        } else {
                           echo '<span class="label label-default">' . $user_language['player'] . '</span>';
                        }
                        ?>
                  </h2>
                  <h2 style="display: inline-block; float: right;">
                     <span class="label label-<?php
                        if(!isset($is_online)){
                           echo 'danger">' . $user_language['offline'];
                        } else {
                           echo 'success">' . $user_language['online'];
                        }
                        ?>
                     </span>
                  </h2>
                  <?php if($exists == true && ($profile_user[0]->user_title)) echo '<h5 style="padding-bottom: 10px;"><b>' . htmlspecialchars($profile_user[0]->user_title) . '</b></h5>'; ?>
                  <hr />
                  <?php if($exists == true){ ?>
				  <h5 style="float: left; display: inline-block;">
				    <?php
					if($profile_user[0]->display_age == 1 && ($profile_user[0]->birthday) && ($profile_user[0]->location)){
						echo str_replace(array('{x}', '{y}'), array((date_diff(date_create($profile_user[0]->birthday), date_create('today'))->y), htmlspecialchars($profile_user[0]->location)), $user_language['display_age_and_location']);
					} else if(($profile_user[0]->location)){
						echo str_replace('{x}', htmlspecialchars($profile_user[0]->location), $user_language['display_location']);
					}
					?>
				  </h5><?php } ?>
                  <h5 style="float: right; display: inline-block;"><?php if($user->isLoggedIn() && $exists == true){
                     if($user->isfriend($user->data()->id, $profile_user[0]->id) === 0){
                        if($user->data()->id === $profile_user[0]->id){

                        } else {
                           echo '
                           <form style="display: inline"; method="post">
                           <input type="hidden" name="token" value="' . $token . '">
                           <input style="margin-top: -5px;" type="submit" class="btn btn-success" name="AddFriend" value="' . ($use_followers == '1' ? $user_language['follow'] : $user_language['add_friend']) . '">
                           </form>
                           <a style="margin-top: -5px;" href="/user/messaging/?action=new&uid=' . $profile_user[0]->id . '" class="btn btn-primary">' . $user_language['send_message'] . '</a>
                           ';
                        }
                     } else {
                        if($user->data()->id === $profile_user[0]->id){

                        } else {
                           echo '
                           <form style="display: inline"; method="post">
                           <input type="hidden" name="token" value="' . $token . '">
                           <input style="margin-top: -5px;" type="submit" class="btn btn-danger" name="RemoveFriend" value="' . ($use_followers == '1' ? $user_language['unfollow'] : $user_language['remove_friend']) . '">
                           </form>
                           <a style="margin-top: -5px;" href="/user/messaging/?action=new&uid=' . $profile_user[0]->id . '" class="btn btn-primary">' . $user_language['send_message'] . '</a>
                           ';
                        }
                     }
                     } ?>
                  </h5>
                  <br />
               </div>
               <br />
			    <?php if($exists == true){ ?>
                <div role="tabpanel">
                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs" role="tablist">
					<li class="active"><a href="#profile-posts" role="tab" data-toggle="tab"><?php echo $user_language['profile_posts']; ?></a></li>
                    <li><a href="#forum" role="tab" data-toggle="tab"><?php echo $user_language['about']; ?></a></li>
                    <li><a href="#topics-and-comments" role="tab" data-toggle="tab"><?php echo ucfirst($forum_language['posts']); ?></a></li>
                    <?php if($name_history == '1'){ ?>
					<li><a href="#name_history" role="tab" data-toggle="tab"><?php echo ucfirst($user_language['name_history']); ?></a></li>
					<?php
					}
					if(isset($infractions_language)){
					?>
					<li><a href="#infractions" role="tab" data-toggle="tab"><?php echo $infractions_language['infractions']; ?></a></li>
					<?php } ?>
				  </ul>
                  <!-- Tab panes -->
                  <div class="tab-content">
                     <div role="tabpanel" class="tab-pane active" id="profile-posts">
                        <br />
						<?php
						if(Session::exists('user_wall')){
							echo Session::flash('user_wall');
						}
						if($user->isLoggedIn()){
						?>
                        <div class="row" style="overflow:hidden; width:100%;">
						  <form action="" method="post">
                            <label style="float:left; width:6%;">
						    <?php
						    // User avatar
							echo '<img class="img-rounded" style="padding-bottom:2.5px; height: 50px; width: 50px;" src="' . $user->getAvatar($user->data()->id, "../", 50) . '" />';
						    ?>
						    </label>&nbsp;
                            <textarea name="wall_post" class="form-control input-lg" style="float:right; width:93%;" type="text" placeholder="<?php if($user->data()->id !== $profile_user[0]->id) echo str_replace('{x}', $mcname, $user_language['write_on_user_profile']); else echo $user_language['write_on_own_profile']; ?>"><?php echo Input::get('wall_post'); ?></textarea>
                            <input type="hidden" name="action" value="post">
							<input type="hidden" name="token" value="<?php echo $token; ?>">
							<input type="submit" style="float: right; margin-top: 10px;" class="btn btn-info btn-md" value="<?php echo $general_language['submit']; ?>">
                          </form>
						</div>
                        <hr />
						<?php
						}

						// Get all profile posts
						$profile_posts = $queries->orderWhere('user_profile_wall_posts', 'user_id = ' . $profile_user[0]->id, 'time', 'DESC');
						if(count($profile_posts)){
							// Pagination stuff
							$pagination->setCurrent($p);
							$pagination->setTotal(count($profile_posts));
							$pagination->alwaysShowPagination();

							// Get number of users we should display on the page
							$paginate = PaginateArray($p);

							$n = $paginate[0];
							$f = $paginate[1];

							// Get the number we need to finish on ($d)
							if(count($profile_posts) > $f){
								$d = $p * 10;
							} else {
								$d = count($profile_posts) - $n;
								$d = $d + $n;
							}

							while($n < $d){
								// Get info about the user who's posted
								$post_user = $queries->getWhere('users', array('id', '=', $profile_posts[$n]->author_id));

								// Any replies?


								// How many likes?
								$likes = $queries->getWhere('user_profile_wall_posts_likes', array('post_id', '=', $profile_posts[$n]->id));
								$likes_count = count($likes);
						?>
						<div class="well">
                        <div class="row">
                           <label style="float:left; width:6%;">
						    <?php
						    // User avatar
							echo '<img class="img-rounded" style="padding-bottom:2.5px; height: 50px; width: 50px;" src="' . $user->getAvatar($post_user[0]->id, "../", 50) . '" />';
						    ?>
						   </label>&nbsp;
                           <p style="float:right; width:93%;" type="text">
						     <a href="/profile/<?php echo htmlspecialchars($user->idToMCName($profile_posts[$n]->author_id)); ?>"><strong><?php echo htmlspecialchars($user->idToName($profile_posts[$n]->author_id)); ?></strong></a> | <?php echo date('M j, Y', $profile_posts[$n]->time); ?>
							 <br /><br />
							 <?php echo $purifier->purify(htmlspecialchars_decode($profile_posts[$n]->content)); ?>
						   </p>
                           <p style="float:right; width:93%;" type="text">
						     <?php if($user->isLoggedIn()){ ?><a href="#" data-toggle="modal" data-target="#replyModal<?php echo $n; ?>"><?php echo $user_language['reply']; ?></a> | <?php } ?><a class="pop" href="<?php if($user->isLoggedIn() && $user->data()->id != $profile_posts[$n]->author_id){ ?>/profile/<?php echo $mcname; ?>/?action=like&amp;post=<?php echo $profile_posts[$n]->id; } else echo '#'; ?>" title="<?php echo $user_language['likes']; ?>" data-content="<?php if($likes_count){ $i = 1; foreach($likes as $like){ echo '<a href=\'/profile/' . htmlspecialchars($user->idToMCName($like->user_id)) . '\'>' . htmlspecialchars($user->idToName($like->user_id)); if($i != $likes_count) echo ', '; echo '</a>'; $i++; } } else { echo $user_language['no_likes']; } ?>"><i class="fa fa-thumbs-o-up"></i> <?php echo str_replace('{x}', $likes_count, $user_language['x_likes']); ?></a><?php if($user->isLoggedIn() && $user->canViewMCP($user->data()->id)){ ?> | <a onclick="return confirm('<?php echo $forum_language['confirm_post_deletion']; ?>');" href="/profile/<?php echo $mcname; ?>/?action=delete&amp;pid=<?php echo $profile_posts[$n]->id; ?>"><?php echo $user_language['delete']; ?></a><?php } ?>
						   </p>
						   <?php
						   // Replies
						   $replies = $queries->getWhere('user_profile_wall_posts_replies', array('post_id', '=', $profile_posts[$n]->id));
						   if(count($replies)){
							   foreach($replies as $reply){
								   $reply_user = $queries->getWhere('users', array('id', '=', $reply->author_id));
							   ?>
                           <p style="float:right; width:93%;" type="text">
						     <div class="row">
							   <div class="col-md-1 col-md-offset-1">
								<?php
								// User avatar
								echo '<img class="img-rounded" style="padding-bottom:2.5px; height: 50px; width: 50px;" src="' . $user->getAvatar($reply->author_id, "../", 50) . '" />';
								?>
							   </div>
							   <div class="col-md-10">
								 <a href="/profile/<?php echo htmlspecialchars($user->idToMCName($reply->author_id)); ?>"><strong><?php echo htmlspecialchars($user->idToName($reply->author_id)); ?></strong></a> | <?php echo date('M j, Y', $reply->time); ?>
								 <br /><br />
								 <?php echo $purifier->purify($reply->content); ?>
								 <?php if($user->isLoggedIn() && $user->canViewMCP($user->data()->id)){ ?><br /><br /><a onclick="return confirm('<?php echo $forum_language['confirm_post_deletion']; ?>');" href="/profile/<?php echo $mcname; ?>/?action=delete&amp;r=<?php echo $reply->id; ?>"><?php echo $user_language['delete']; ?></a><?php } ?>
							   </div>
							 </div>
						   </p>
						       <?php
							   }
						   }
						   ?>
                        </div>
						</div>
						<hr />
						<?php
								// Reply modal
								if($user->isLoggedIn()){
								?>
						<!-- Modal -->
						<div class="modal fade" id="replyModal<?php echo $n; ?>" tabindex="-1" role="dialog" aria-labelledby="replyModal<?php echo $n; ?>Label">
						  <div class="modal-dialog" role="document">
							<div class="modal-content">
							  <div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="replyModal<?php echo $n; ?>Label"><?php echo $user_language['reply']; ?></h4>
							  </div>
							  <div class="modal-body">
							    <form action="" method="post" id="reply<?php echo $n; ?>">
								  <textarea name="post_reply" class="form-control"><?php echo Input::get('post_reply'); ?></textarea>
								  <input type="hidden" name="pid" value="<?php echo $profile_posts[$n]->id; ?>">
								  <input type="hidden" name="action" value="reply">
								  <input type="hidden" name="token" value="<?php echo $token; ?>">
								</form>
							  </div>
							  <div class="modal-footer">
								<button type="button" class="btn btn-primary" onclick="document.getElementById('reply<?php echo $n; ?>').submit();"><?php echo $general_language['submit']; ?></button>
							    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $general_language['cancel']; ?></button>
							  </div>
							</div>
						  </div>
						</div>
								<?php
								}

								$n++;
							}
							echo $pagination->parse(); // Print pagination
						} else {
							echo $user_language['no_profile_posts'];
						}
						?>
                     </div>
                     <div role="tabpanel" class="tab-pane" id="forum">
                        <br />
				    <div class="well">
                        <?php
                           // Check if the user has registered on the website
                           if($exists == true){
                           ?>
                        <strong><?php echo $user_language['pf_registered']; ?></strong> <?php echo date("d M Y, G:i", $profile_user[0]->joined); ?><br />
                        <strong><?php echo $user_language['last_online']; ?></strong> <?php if($profile_user[0]->last_online){ echo date("d M Y, G:i", $profile_user[0]->last_online); } else { echo 'n/a'; } ?><br />
                        <strong><?php echo $user_language['pf_posts']; ?></strong> <?php echo count($queries->getWhere("posts", array("post_creator", "=", $profile_user[0]->id))); ?><br />
                        <strong><?php echo $user_language['pf_reputation']; ?></strong> <?php echo count($queries->getWhere("reputation", array("user_received", "=", $profile_user[0]->id))); ?><br />
                        <?php
                           } else {
                              echo $user_language['user_hasnt_registered'];
                           }
                           ?>
				     </div>
                     </div>
                     <div role="tabpanel" class="tab-pane" id="topics-and-comments">
					    <?php
						// Get latest posts
						$latest_posts = $queries->orderWhere('posts', 'post_creator = ' . $profile_user[0]->id, 'post_date', 'DESC LIMIT 15');

						if(!count($latest_posts)) echo '<br /><p>' . $user_language['no_posts'] . '</p>';

						else {
							echo '<h3>' . $user_language['last_5_posts'] . '</h3>';
							$n = 0;

							if(!$user->isLoggedIn()) $group_id = 0;
							else $group_id = $user->data()->group_id;

							foreach($latest_posts as $latest_post){
								if($n == 5) break;

								// Is the post somewhere the user can view?
								$permission = false;
								$forum_permissions = $queries->getWhere('forums_permissions', array('forum_id', '=', $latest_post->forum_id));
								foreach($forum_permissions as $forum_permission){
									if($forum_permission->group_id == $group_id){
										if($forum_permission->view == 1){
											$permission = true;
											break;
										}
									}
								}

								if($permission != true) continue;

								// Check the post isn't deleted
								if($latest_post->deleted == 1) continue;

								// Get topic title
								$topic_title = $queries->getWhere('topics', array('id', '=', $latest_post->topic_id));
								$topic_title = htmlspecialchars($topic_title[0]->topic_title);
							?>
						<div class="panel panel-primary">
						  <div class="panel-heading">
							<a href="/forum/view_topic/?tid=<?php echo $latest_post->topic_id; ?>&amp;pid=<?php echo $latest_post->id; ?>" class="white-text"><?php echo $topic_title; ?></a>
						  </div>
						  <div class="panel-body">
						    <div class="forum_post">
						    <?php echo $purifier->purify(htmlspecialchars_decode($latest_post->post_content)); ?>
							</div>
						    <span class="pull-right">
							  <span class="label label-info"><?php echo date('d M Y, H:i', strtotime($latest_post->post_date)); ?></span>
						    </span>
						  </div>
						</div>
							<?php
								$n++;
							}
						}
						?>
                     </div>
					 <?php if($name_history == '1'){ ?>
                     <div role="tabpanel" class="tab-pane" id="name_history">
					   <br />
					   <div class="well">
						<?php
							// Name history
							// Check database
							$user_history = $queries->getWhere('users_username_history', array('user_id', '=', $profile_user[0]->id));
							if(!count($user_history)){
								// Not stored yet, get username history now
								$name = file_get_contents('https://api.mojang.com/user/profiles/' . htmlspecialchars($uuid) . '/names');
								if($name){
									$namehistory = json_decode($name, true);

									for($i = 0; $i < count($namehistory); $i++){
										if(array_key_exists("changedToAt", $namehistory[$i])){
											// Not the original username
											$queries->create('users_username_history', array(
												'user_id' => $profile_user[0]->id,
												'changed_to' => htmlspecialchars($namehistory[$i]["name"]),
												'changed_at' => ($namehistory[$i]["changedToAt"] / 1000)
											));
										} else {
											// Original username
											$queries->create('users_username_history', array(
												'user_id' => $profile_user[0]->id,
												'changed_to' => htmlspecialchars($namehistory[$i]["name"]),
												'changed_at' => 0,
												'original' => 1
											));
										}
									}

									// Refresh user history query
									$user_history = $queries->getWhere('users_username_history', array('user_id', '=', $profile_user[0]->id));

								} else {
									// Unable to retrieve list of past names
									$history_error = true;
								}

								// Cache for 6 hours
								$c->setCache('user_history_' . htmlspecialchars($uuid));
								$c->store('cached', 'true', 21600);

							} else {
								// Already stored, see if it needs updating by checking cache
								$c->setCache('user_history_' . htmlspecialchars($uuid));

								if(!$c->isCached('cached')){
									// Needs updating
									$name = file_get_contents('https://api.mojang.com/user/profiles/' . htmlspecialchars($uuid) . '/names');
									if($name){
										$namehistory = json_decode($name, true);

										$queries->delete('users_username_history', array('user_id', '=', $profile_user[0]->id));

										for($i = 0; $i < count($namehistory); $i++){
											if(array_key_exists("changedToAt", $namehistory[$i])){
												// Not the original username
												$queries->create('users_username_history', array(
													'user_id' => $profile_user[0]->id,
													'changed_to' => htmlspecialchars($namehistory[$i]["name"]),
													'changed_at' => ($namehistory[$i]["changedToAt"] / 1000)
												));
											} else {
												// Original username
												$queries->create('users_username_history', array(
													'user_id' => $profile_user[0]->id,
													'changed_to' => htmlspecialchars($namehistory[$i]["name"]),
													'changed_at' => 0,
													'original' => 1
												));
											}
										}

										// Refresh user history query
										$user_history = $queries->orderWhere('users_username_history', 'user_id = ' . $profile_user[0]->id, 'changed_at', 'ASC');

									} else {
										// Unable to retrieve list of past names
										$history_error = true;
									}

									// Cache for 6 hours
									$c->store('cached', 'true', 21600);
								}
							}

							// Display username history
							echo '<h3>' . $user_language['name_history'] . '</h3>';

							if(isset($history_error)){
								// Error querying Mojang API
								echo $user_language['name_history_error'];
							} else {
								if(count($user_history)){
									echo '<ul>';

									foreach($user_history as $history){
										if($history->original == 1){
											// Original username
											echo '<li class="name">' . $user_language['original_name'] . ' <b>' . htmlspecialchars($history->changed_to) . '</b>';

										} else {
											echo '<li class="name">' . str_replace(array('{x}', '{y}'), array(htmlspecialchars($history->changed_to), date('dS M Y', $history->changed_at)), $user_language['changed_name_to']) . '</li>';
										}
									}

									echo '</ul>';
								} else {
									// Nothing stored in database
									echo $user_language['name_history_error'];
								}
							}
						?>
				       </div>
                     </div>
					 <?php
					 }
					 if(isset($infractions_language)){
					 ?>
					 <div role="tabpanel" class="tab-pane" id="infractions">
					   <br />
						<div class="well">
						<h3><?php echo $infractions_language['infractions']; ?></h3>
						<?php
						if(!isset($_GET['type']) && !isset($_GET['id'])){
							// Get all infractions, depending on plugin
							switch($inf_plugin){
								case 'bat':
									$all_infractions = $infractions->bat_getAllInfractions($longuuid);
								break;
								case 'bm':
									$all_infractions = $infractions->bm_getAllInfractions($user->data()->mcname);
								break;
								case 'lb':
									$all_infractions = $infractions->lb_getAllInfractions($longuuid);
								break;
								case 'bam':
									$all_infractions = $infractions->bam_getAllInfractions($longuuid);
								break;
								case 'bu':
									$all_infractions = $infractions->bu_getAllInfractions($longuuid);
								break;
								case 'ab':
									$all_infractions = $infractions->ab_getAllInfractions($longuuid);
								break;
							}

							// Pagination
							$paginate = PaginateArray($p);

							$n = $paginate[0];
							$f = $paginate[1];

							if(count($all_infractions) > $f){
								$d = $p * 10;
							} else {
								$d = count($all_infractions) - $n;
								$d = $d + $n;
							}
						?>
						<div class="table-responsive">
						  <table class="table table-bordered">
							<colgroup>
							  <col span="1" style="width: 15%;">
							  <col span="1" style="width: 15%;">
							  <col span="1" style="width: 15%">
							  <?php if($inf_plugin != 'bu'){ ?>
							  <col span="1" style="width: 30%">
							  <col span="1" style="width: 15%">
							  <?php } else { ?>
							  <col span="1" style="width: 45%">
							  <?php } ?>
							  <col span="1" style="width: 10%">
							</colgroup>
							<thead>
							  <tr>
								<td><?php echo $user_language['username']; ?></td>
								<td><?php echo $infractions_language['staff_member']; ?></td>
								<td><?php echo $infractions_language['action']; ?></td>
								<td><?php echo $infractions_language['reason']; ?></td>
								<?php if($inf_plugin != 'bu'){ ?>
								<td><?php echo $infractions_language['created']; ?></td>
								<?php } ?>
								<td><?php echo $infractions_language['actions']; ?></td>
							  </tr>
							</thead>
							<tbody>
							<?php
							while($n < $d){
								if(isset($mcname)) unset($mcname);

								$infraction = $all_infractions[$n];
								if($inf_plugin == "mb"){
									$exploded = explode('.', $infraction["id"]);
									$mcname = $exploded[0];
									$time = $exploded[1];
								} else if($inf_plugin == "lb"){
									$mcname = $infraction["username"];
								} else {
									$infractions_query = $queries->getWhere('users', array('uuid', '=', str_replace('-', '', $infraction["uuid"])));
									if(empty($infractions_query)){

										if($inf_plugin == 'bat') $mcname = $infractions->bat_getUsernameFromUUID($infraction['uuid']);

										if($inf_plugin != 'bat' || !count($mcname)){
											if($inf_plugin == 'bm') $mcname = $infractions->bm_getUsernameFromID(pack("H*", str_replace('-', '', $infraction['uuid'])));

											else {
												$infractions_query = $queries->getWhere('uuid_cache', array('uuid', '=', str_replace('-', '', $infraction["uuid"])));

												if(empty($infractions_query)){
													// Query Minecraft API to retrieve username
													$profile = ProfileUtils::getProfile(str_replace('-', '', $infraction["uuid"]));
													if(empty($profile)){
														// Couldn't find player

													} else {
														$result = $profile->getProfileAsArray();
															if(isset($result['username'])){
															$mcname = htmlspecialchars($result["username"]);
															$uuid = htmlspecialchars(str_replace('-', '', $infraction["uuid"]));
															try {
																$queries->create("uuid_cache", array(
																	'mcname' => $mcname,
																	'uuid' => $uuid
																));
															} catch(Exception $e){
																die($e->getMessage());
															}
														}
													}
												}
												$mcname = $queries->getWhere('uuid_cache', array('uuid', '=', str_replace('-', '', $infraction["uuid"])));
												if(count($mcname))
													$mcname = $mcname[0]->mcname;
												else
													$mcname = 'Unknown';
											}

										} else {
											$mcname = $mcname[0]->BAT_player;
										}
									} else {
										$mcname = $infractions_query[0]->mcname;
									}
								}
							?>
							  <tr>
								<td><a href="/profile/<?php echo htmlspecialchars($mcname); ?>"><?php echo htmlspecialchars($mcname); ?></a></td>
								<td><?php if(strtolower($infraction["staff"]) !== "console"){?><a href="/profile/<?php echo htmlspecialchars($infraction["staff"]); ?>"><?php if($inf_plugin !== "mb"){ echo htmlspecialchars($infraction["staff"]); } else { echo htmlspecialchars($infractions->mb_getUsernameFromName($infraction["staff"])); }?></a><?php } else { echo 'Console'; } ?></td>
								<td><?php echo $infraction["type_human"]; ?> <?php echo $infraction["expires_human"]; ?></td>
								<td><?php echo htmlspecialchars($infraction["reason"]); ?></td>
								<?php if($inf_plugin != 'bu') { ?><td><?php if(isset($infraction['issued'])){ ?><span rel="tooltip" data-placement="top" title="<?php echo $infraction["issued_human"]; ?>"><?php echo $timeago->inWords(date('d M Y, H:i', $infraction["issued"]), $time_language); ?></span><?php } else echo '-'; ?></td><?php } ?>
								<td><a class="btn btn-primary btn-sm" href="/infractions/?type=<?php echo $infraction["type"]; ?>&amp;id=<?php echo $infraction["id"]; if(isset($infraction['past'])){ ?>&amp;past=true<?php } ?>"><?php echo $infractions_language['view']; ?></a></td>
							  </tr>
							<?php
								$n++;
							}
							?>
							</tbody>
						  </table>
						</div>
							<?php
							$pagination->setCurrent($p);
							$pagination->setTotal(count($all_infractions));
							$pagination->alwaysShowPagination();

							echo $pagination->parse();
						}
						?>
						</div>
					 </div>
					 <?php } ?>
                  </div>
                </div>
				<?php } else { echo $user_language['user_hasnt_registered']; } ?>
            </div>
         </div>
         <?php } else {
            if(Input::exists()){
				if(Token::check(Input::get('token'))){
				    echo '<script data-cfasync="false">window.location.replace("/profile/' . htmlspecialchars(Input::get('username'), ENT_QUOTES) . '");</script>';
				    die();
				} else {
					$error = true;
				}
            }
             ?>
         <h2><?php echo $user_language['find_a_user']; ?></h2>
		 <?php if(isset($error)) echo '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>'; ?>
         <form role="form" action="" method="post">
            <input type="text" name="username" id="username" autocomplete="off" value="<?php echo htmlspecialchars(Input::get('username')); ?>" class="form-control input-lg" placeholder="<?php echo $user_language['username']; ?>" tabindex="1">
            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
            <br />
            <input type="submit" value="<?php echo $general_language['submit']; ?>" class="btn btn-primary btn-lg" tabindex="2">
         </form>
         <?php } ?>
      </div>
      <?php
         // Footer
         require('core/includes/template/footer.php');
         $smarty->display('styles/templates/' . $template . '/footer.tpl');

         // Scripts
         require('core/includes/template/scripts.php');

         ?>

		 <script>
		 $(".pop").popover({ trigger: "manual" , html: true, animation:false, placement: "top" })
			.on("mouseenter", function () {
				var _this = this;
				$(this).popover("show");
				$(".popover").on("mouseleave", function () {
					$(_this).popover('hide');
				});
			}).on("mouseleave", function () {
				var _this = this;
				setTimeout(function () {
					if (!$(".popover:hover").length) {
						$(_this).popover("hide");
					}
				}, 300);
		 });
		 </script>
   </body>
</html>
