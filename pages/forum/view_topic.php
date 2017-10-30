<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Maintenance mode?
// Todo: cache this
$maintenance_mode = $queries->getWhere('settings', array('name', '=', 'maintenance'));
if($maintenance_mode[0]->value == 'true'){
	// Maintenance mode is enabled, only admins can view
	if(!$user->isLoggedIn() || !$user->canViewACP($user->data()->id)){
		require('pages/forum/maintenance.php');
		die();
	}
}
 
// Set the page name for the active link in navbar
$page = "forum";

$forum = new Forum();
$timeago = new Timeago();
$pagination = new Pagination();
$mentionsParser = new MentionsParser();

require('core/includes/paginate.php'); // Get number of replies on a page
require('core/includes/htmlpurifier/HTMLPurifier.standalone.php'); // HTML Purifier
require('core/includes/getSelfURL.php'); // getSelfURL function

if(!isset($_GET['tid']) || !is_numeric($_GET['tid'])){
	Redirect::to('/forum/error/?error=not_exist');
	die();
}

$tid = (int) $_GET['tid'];

// Does the topic exist, and can the user view it?
$list = $forum->topicExist($tid, $user->data()->group_id);
if(!$list){
	Redirect::to('/forum/error/?error=not_exist');
	die();
}

// Get page
if(isset($_GET['p'])){
	if(!is_numeric($_GET['p'])){
		Redirect::to("/forum");
		die();
	} else {
		if($_GET['p'] == 1){ 
			// Avoid bug in pagination class
			Redirect::to('/forum/view_topic/?tid=' . $tid);
			die();
		}
		$p = $_GET['p'];
	}
} else {
	$p = 1;
}

// Is the URL pointing to a specific post?
if(isset($_GET['pid'])){
	$posts = $queries->getWhere("posts", array("topic_id", "=", $tid));
	if(count($posts)){
		$i = 0;
		while($i < count($posts)){
			if($posts[$i]->id == $_GET['pid']){
				$output = $i + 1;
				break;
			}
			$i++;
		}
		if(ceil($output / 10) != $p){
			Redirect::to('/forum/view_topic/?tid=' . $tid . '&p=' . ceil($output / 10) . '#post-' . $_GET['pid']);
			die();
		} else {
			Redirect::to('/forum/view_topic/?tid=' . $tid . '#post-' . $_GET['pid']);
			die();
		}
		
	} else {
		Redirect::to('/forum/error/?error=not_exist');
		die();
	}
}

// Get the topic information
$topic = $queries->getWhere("topics", array("id", "=", $tid));
$topic = $topic[0];

// Assign author + title to Smarty variables
$smarty->assign(array(
	'TOPIC_TITLE' => htmlspecialchars($topic->topic_title),
	'TOPIC_AUTHOR_USERNAME' => htmlspecialchars($user->idToName($topic->topic_creator)),
	'TOPIC_AUTHOR_MCNAME' => htmlspecialchars($user->idToMCName($topic->topic_creator))
));

// Get all posts in the topic
$posts = $forum->getPosts($tid);

// Can the user post a reply in this topic?
$can_reply = $forum->canPostReply($topic->forum_id, $user->data()->group_id);

// Quick reply
if(Input::exists()) {
	if(!$user->isLoggedIn() && !$can_reply){ 
		Redirect::to('/forum');
		die();
	}
	if(Token::check(Input::get('token'))){
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'content' => array(
				'required' => true,
				'min' => 2,
				'max' => 20480
			)
		));
		if($validation->passed()){
			try {
				$queries->create("posts", array(
					'forum_id' => $topic->forum_id,
					'topic_id' => $tid,
					'post_creator' => $user->data()->id,
					'post_content' => htmlspecialchars(Input::get('content')),
					'post_date' => date('Y-m-d H:i:s')
				));
				
				// Get last post ID
				$last_post_id = $queries->getLastId();
				$content = $mentionsParser->parse(Input::get('content'), $tid, $last_post_id, $user_language);
				
				$queries->update("posts", $last_post_id, array(
					'post_content' => $content
				));
				
				$queries->update("forums", $topic->forum_id, array(
					'last_topic_posted' => $tid,
					'last_user_posted' => $user->data()->id,
					'last_post_date' => date('Y-m-d H:i:s')
				));
				$queries->update("topics", $tid, array(
					'topic_last_user' => $user->data()->id,
					'topic_reply_date' => date('U')
				));
				Session::flash('success_post', '<div class="alert alert-info alert-dismissable"> <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>Post submitted.</div>');
				Redirect::to('/forum/view_topic/?tid=' . $tid);
				die();
			} catch(Exception $e){
				die($e->getMessage());
			}
		} else {
			$error_string = "";
			foreach($validation->errors() as $error) {
				$error_string .= ucfirst($error) . '<br />';
			}
			Session::flash('failure_post', '<div class="alert alert-danger alert-dismissable"> <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>' . $error_string . '</div>');
		}
	} else {
		// Invalid token - TODO: improve
		//echo 'Invalid token';

	}
}

// Generate a post token
if($user->isLoggedIn()){
	$token = Token::generate();
}

// View count
if($user->isLoggedIn() || Cookie::exists('alert-box')){
	if(!Cookie::exists('nl-topic-' . $tid)) {
		$queries->increment("topics", $tid, "topic_views");
		Cookie::put("nl-topic-" . $tid, "true", 3600);
	}
}

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $sitename; ?> Forum - Topic: <?php echo htmlspecialchars($topic->topic_title); ?>">
    <meta name="author" content="<?php echo $sitename; ?>">
    <?php if(isset($custom_meta)){ echo $custom_meta; } ?>
	
	<?php
	// Generate header and navbar content
	// Page title
	$title = htmlspecialchars($topic->topic_title);
	
	require('core/includes/template/generate.php');
	?>
	
	<!-- Custom style -->
	<style>
	html {
		overflow-y: scroll;
	}
	</style>
	
  </head>

  <body>

	<?php 
	// Load navbar
	$smarty->display('styles/templates/' . $template . '/navbar.tpl'); 
	
	// Assign Smarty variables to pass to template
	// Generate breadcrumbs
	$stop = 0;
	$forum_parent = $queries->getWhere('forums', array('id', '=', $topic->forum_id));
	
	while($stop == 0){
		$forum_parents[] = array('id' => htmlspecialchars($forum_parent[0]->id), 'name' => $purifier->purify(htmlspecialchars_decode($forum_parent[0]->forum_title)));
		if($forum_parent[0]->parent == 0){
			$stop = 1;
		} else {
			$forum_parent = $queries->getWhere('forums', array('id', '=', $forum_parent[0]->parent));
		}
	}
	
	$breadcrumbs = '
	<ol class="breadcrumb">
	  <li><a href="/forum">' . $forum_language['home'] . '</a></li>';
	
	foreach(array_reverse($forum_parents) as $parent){
		$breadcrumbs .= '<li><a href="/forum/view_forum/?fid=' . $parent['id'] . '">' . $parent['name'] . '</a></li>';
	}
	
	$breadcrumbs .= '<li class="active">' . htmlspecialchars($topic->topic_title) . '</li>
	</ol>';
	
	$smarty->assign('BREADCRUMBS', $breadcrumbs);
	
	// Display session messages
	if(Session::exists('success_post')){
		//echo '<br /><div class="container"><center>' . Session::flash('success_post') . '</center></div>';
		$smarty->assign('SESSION_SUCCESS_POST', Session::flash('success_post'));
	} else {
		$smarty->assign('SESSION_SUCCESS_POST', '');
	}
	if(Session::exists('failure_post')){
		//echo '<br /><div class="container"><center>' . Session::flash('failure_post') . '</center></div>';
		$smarty->assign('SESSION_FAILURE_POST', Session::flash('failure_post'));
	} else {
		$smarty->assign('SESSION_FAILURE_POST', '');
	}
	
	// TODO: remove
	$smarty->assign('COOKIE_MESSAGE', '');
	
	// Display "new reply" button and "mod actions" if the user has access to them
	$buttons = '';
	
	// Can the user post a reply?
	if($user->isLoggedIn() && $can_reply){
		// Is the topic locked?
		if($topic->locked != 1){ // Not locked
			$buttons .= '<a href="/forum/create_post/?tid=' . $tid . '&amp;fid=' .  $topic->forum_id . '" class="btn btn-primary">' . $forum_language['new_reply'] . '</a>';
		} else { // Locked
			if($user->canViewMCP($user->data()->id)){
				// can post anyway
				// TODO: change IF statement so it's permission based, not group based
				$buttons .= '<a href="/forum/create_post/?tid=' . $tid . '&amp;fid=' .  $topic->forum_id . '" class="btn btn-primary">' . $forum_language['new_reply'] . '</a>';
			} else {
				// can't post
				$buttons .= '<a class="btn btn-primary" disabled="disabled">' . $forum_language['topic_locked'] . '</a>';
			}
		}
	}
	
	// TODO: Change this so this is permission based instead of group based
	// Is the user a moderator?
	$buttons .= '<span class="pull-right">';
	if($user->isLoggedIn() && $user->canViewMCP($user->data()->id)){
	  $buttons .= '
		<div class="btn-group">
		  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
			' . $forum_language['mod_actions'] . ' <span class="caret"></span>
		  </button>
		  <ul class="dropdown-menu" role="menu">
			<li><a href="/forum/lock_thread/?tid=' . $tid . '">'; if($topic->locked == 1){ $buttons .= $forum_language['unlock_thread']; } else { $buttons .= $forum_language['lock_thread']; } $buttons .= '</a></li>
			<li><a href="/forum/merge_thread/?tid=' . $tid . '">' . $forum_language['merge_thread'] . '</a></li>
			<li><a href="/forum/delete_thread/?tid=' . $tid . '" onclick="return confirm(\'' . $forum_language['confirm_thread_deletion'] . '\')">' . $forum_language['delete_thread'] . '</a></li>
			<li><a href="/forum/move_thread/?tid=' . $tid . '">' . $forum_language['move_thread'] . '</a></li>
			<li><a href="/forum/sticky_thread/?tid=' . $tid . '">' . $forum_language['sticky_thread'] . '</a></li>
		  </ul>
		</div>';
	}
	$buttons .= '
	<div class="btn-group">
	  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
	    ' . $forum_language["sm-share"] . ' <span class="caret"></span>
	  </button>
	  <ul class="dropdown-menu" role="menu">
		<li><a target="_blank" href="https://twitter.com/intent/tweet?text=' . getSelfURL() . 'forum/view_topic/?tid='.$tid.'">'.$forum_language["sm-share-twitter"].'</a></li>
		<li><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u='.getSelfURL().'forum/view_topic/?tid='.$tid.'">'.$forum_language["sm-share-facebook"].'</a></li>
	  </ul>
	</div>
	</span>';
	
	$smarty->assign('BUTTONS', $buttons);
	
	// Pagination
	$pagination->setCurrent($p);
	$pagination->setTotal(count($posts));
	$pagination->alwaysShowPagination();

	// Get number of users we should display on the page
	$paginate = PaginateArray($p);

	$n = $paginate[0];
	$f = $paginate[1];
	
	// Get the number we need to finish on ($d)
	if(count($posts) > $f){
		$d = $p * 10;
	} else {
		$d = count($posts) - $n;
		$d = $d + $n;
    }

	$pagination = $pagination->parse(); // Print pagination
	
	$smarty->assign('PAGINATION', $pagination);
	
	// Initialise HTMLPurifier
	$config = HTMLPurifier_Config::createDefault();
	$config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
	$config->set('URI.DisableExternalResources', false);
	$config->set('URI.DisableResources', false);
	$config->set('CSS.Trusted', true);
	$config->set('HTML.Allowed', 'u,p,b,i,a,small,blockquote,span[style],span[class],p,strong,em,li,ul,ol,div[align],br,img');
	$config->set('CSS.AllowedProperties', array('position', 'padding-bottom', 'padding-top', 'top', 'left', 'height', 'width', 'overflow', 'text-align', 'float', 'color','background-color', 'background', 'font-size', 'font-family', 'text-decoration', 'font-weight', 'font-style', 'font-size'));
	$config->set('HTML.AllowedAttributes', 'target, href, src, height, width, alt, class, *.style');
	$config->set('Attr.AllowedFrameTargets', array('_blank', '_self', '_parent', '_top'));
	$config->set('HTML.SafeIframe', true);
	$config->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%');
	$config->set('Core.EscapeInvalidTags', true);
	$purifier = new HTMLPurifier($config);
	
	// Replies
	$replies = array();
	// Display the correct number of posts
	while($n < $d){
	  	// Get user's group HTML formatting and their signature
	  	$user_group = $user->getGroup($posts[$n]->post_creator, "true");
	  	$user_group2 = $user->getGroup2($posts[$n]->post_creator, "true");
		$signature = $user->getSignature($posts[$n]->post_creator);
	
		// Panel heading content
		$heading = '<a href="/forum/view_topic/?tid=' . $tid . '&amp;pid=' . $posts[$n]->id . '" class="white-text">'; if($n != 0){ $heading .= $forum_language['re'] . ' '; } else { if($topic->locked == 1){ $heading .= ' <span class="fa fa-lock"></span> '; } } $heading .= htmlspecialchars($topic->topic_title) . '</a>';
		
		// Avatar
		$post_user = $queries->getWhere('users', array('id', '=', $posts[$n]->post_creator));
		$avatar = '<img class="img-rounded" style="width:100px; height:100px;" src="' . $user->getAvatar($posts[$n]->post_creator, '../', 100) . '" />';
		
		// Which buttons do we need to display?
		$buttons = '';
		if($user->isLoggedIn()) { 
			$buttons .= '<span class="pull-right">';
			// Edit button
			if($user->canViewMCP($user->data()->id)){ // Admins and moderators
				$buttons .= '<a rel="tooltip" title="' . $forum_language['edit_post'] . '" href="/forum/edit_post/?pid=' . $posts[$n]->id . '&amp;tid=' . $tid . '" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span></a>';
			} else if($user->data()->id == $posts[$n]->post_creator) { 
				if($topic->locked != 1){ // Can't edit if topic is locked
					$buttons .= '<a rel="tooltip" title="' . $forum_language['edit_post'] . '" href="/forum/edit_post/?pid=' . $posts[$n]->id . '&amp;tid=' . $tid . '" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span></a>';
				}
			} 

			// Delete button
			if($user->canViewMCP($user->data()->id)){ // Mods/admins only
				$buttons .= '
				<form onsubmit="return confirm(\'' . $forum_language['confirm_post_deletion'] . '\');" style="display: inline;" action="/forum/delete_post/" method="post">
					<input type="hidden" name="pid" value="' . $posts[$n]->id . '" />
					<input type="hidden" name="tid" value="' . $tid . '" />
					<input type="hidden" name="number" value="' . $n . '" />
					<input type="hidden" name="token" value="' . $token . '">
					<button rel="tooltip" title="' . $forum_language['delete_post'] . '" type="submit" class="btn btn-danger btn-xs">
					  <span class="glyphicon glyphicon-trash"></span>
					</button>
				</form>
				';
			}

			// Report button
			$buttons .= '<a rel="tooltip" title="' . $forum_language['report_post'] . '" href="/forum/report_post/?pid=' . $posts[$n]->id . '&amp;tid=' . $tid . '" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-exclamation-sign"></span></a>';

			// Quote button
			if($can_reply){
				if($user->canViewMCP($user->data()->id)){ 
					$buttons .= ' <a rel="tooltip" title="' . $forum_language['quote_post'] . '" href="/forum/create_post/?tid=' . $tid . '&amp;qid=' . $posts[$n]->id . '&amp;fid=' . $topic->forum_id . '" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-share"></span></a>';
				} else { 
					if($topic->locked != 1){ 
						$buttons .= ' <a rel="tooltip" title="' . $forum_language['quote_post'] . '" href="/forum/create_post/?tid=' . $tid . '&amp;qid=' . $posts[$n]->id . '&amp;fid=' . $topic->forum_id . '" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-share"></span></a>';
					}
				}
			}

			$buttons .= '</span>';
		}
		
		// Reputation
		$reputation = $forum->getReputation(htmlspecialchars($posts[$n]->id));
		$post_reputation = '<span class="pull-right">';
		
		// Guests and the post author can't upvote the post
		if($user->isLoggedIn() && $user->data()->id !== $posts[$n]->post_creator){
		    if(count($reputation)){
				foreach($reputation as $rep){
				  // Has the user already given reputation to this post?
				  if($user->data()->id == $rep->user_given){
					$user_has_given = true;
					break;
				  } else {
					$user_has_given = false;
				  }
				}
				if($user_has_given === false){
					$post_reputation .= '
					<form style="display: inline;" action="/forum/reputation/" method="post">
						<input type="hidden" name="token" value="' . $token . '">
						<input type="hidden" name="pid" value="' . $posts[$n]->id. '" />
						<input type="hidden" name="tid" value="' . $tid . '" />
						<input type="hidden" name="uid" value="' . $posts[$n]->post_creator . '" />
						<input type="hidden" name="type" value="positive" />
						<button rel="tooltip" title="' . $forum_language['give_reputation'] . '" type="submit" class="btn btn-success btn-sm give-rep"><span class="glyphicon glyphicon-thumbs-up"></span></button>
					</form>';
				} else {
					$post_reputation .= '
					<form style="display: inline;" action="/forum/reputation/" method="post">
						<input type="hidden" name="token" value="' . $token . '">
						<input type="hidden" name="pid" value="' . $posts[$n]->id . '" />
						<input type="hidden" name="tid" value="' . $tid . '" />
						<input type="hidden" name="uid" value="' . $posts[$n]->post_creator . '" />
						<input type="hidden" name="type" value="negative" />
						<button rel="tooltip" title="' . $forum_language['remove_reputation'] . '" type="submit" class="btn btn-danger btn-sm give-rep"><span class="glyphicon glyphicon-thumbs-down"></span></button>
					</form>';
				}
			  } else { // No reputation for this post yet
				$post_reputation = '
				<form style="display: inline;" action="/forum/reputation/" method="post">
					<input type="hidden" name="token" value="' . $token . '">
					<input type="hidden" name="pid" value="' . $posts[$n]->id. '" />
					<input type="hidden" name="tid" value="' . $tid . '" />
					<input type="hidden" name="uid" value="' . $posts[$n]->post_creator . '" />
					<input type="hidden" name="type" value="positive" />
					<button rel="tooltip" title="' . $forum_language['give_reputation'] . '" type="submit" class="btn btn-success btn-sm give-rep"><span class="glyphicon glyphicon-thumbs-up"></span></button>
				</form>';
			  }
			}
			// Display the reputation count
				$post_reputation .= '
			<button class="btn btn-'; if(count($reputation)){ $post_reputation .= 'success'; } else { $post_reputation .= 'default'; } $post_reputation .= ' btn-sm count-rep" data-toggle="modal" data-target="#repModal' . $posts[$n]->id . '"><strong>' . count($reputation) . '</strong></button>
		  </span>';
		  
		  // Modals
		  $post_reputation .= '
		  <!-- Reputation modal --> 
		  <div class="modal fade" id="repModal' . $posts[$n]->id . '" tabindex="-1" role="dialog" aria-labelledby="repModalLabel' . $posts[$n]->id . '" aria-hidden="true">
			<div class="modal-dialog modal-sm">
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">' . $general_language['close'] . '</span></button>
				  <h4 class="modal-title" id="repModalLabel' . $posts[$n]->id . '">' . $forum_language['post_reputation'] . '</h4>
				</div>
				<div class="modal-body">';
				if(count($reputation)){
					$post_reputation .= '<table>';
					foreach($reputation as $rep){
						$post_reputation .= '
						  <tr>
							<td style="width:40px"><a href="/profile/' . htmlspecialchars($user->IdToMCName($rep->user_given)) . '"><img class="img-rounded" style="height:30px; width:30px;" src="' . $user->getAvatar($rep->user_given, '../', 30) . '" /></a></td>
							<td style="width:100px"><a href="/profile/' . htmlspecialchars($user->IdToMCName($rep->user_given)) . '">' . htmlspecialchars($user->IdToName($rep->user_given)) . '</a></td>
						  </tr>';
				    }
					$post_reputation .= '</table>';
				} else {
					$post_reputation .= $forum_language['no_reputation'];
				}
				$post_reputation .= '
				</div>
			  </div>
			</div>
		  </div>';
	
		$replies[] = array(
			'heading' => $heading,
			'post_id' => 'post-' . $posts[$n]->id,
			'avatar' => $avatar,
			'username' => htmlspecialchars($post_user[0]->username),
			'mcname' => htmlspecialchars($post_user[0]->mcname),
			'user_group' => $user_group,
			'user_group2' => $user_group2,
			'user_title' => htmlspecialchars($post_user[0]->user_title),
			'user_posts_count' => count($queries->getWhere('posts', array('post_creator', '=', $posts[$n]->post_creator))),
			'user_reputation' => $post_user[0]->reputation,
			'post_date_rough' => $timeago->inWords($posts[$n]->post_date, $time_language),
			'post_date' => date('d M Y, H:i', strtotime($posts[$n]->post_date)),
			'buttons' => $buttons,
			'content' => $purifier->purify(htmlspecialchars_decode($posts[$n]->post_content)),
			'reputation' => $post_reputation,
			'signature' => $purifier->purify(htmlspecialchars_decode($signature))
		);
		
		$n++;
	}
	$smarty->assign('REPLIES', $replies);
	
	// Quick reply
	if($user->isLoggedIn() && $can_reply){
		if($topic->locked != 1){
			$quick_reply = '
		    <h3>' . $forum_language['new_reply'] . '</h3>
		    <form action="" method="post">
			  <textarea name="content" id="quickreply" rows="3">' . htmlspecialchars(Input::get('content')) . '</textarea>
			  <br /><input type="hidden" name="token" value="' .  $token . '">
			  <button type="submit" class="btn btn-primary">' . $general_language['submit'] . '</button>
		    </form>';
			$smarty->assign('QUICK_REPLY', $quick_reply);
		} else {
			$smarty->assign('QUICK_REPLY', '');
		}
	} else {
		$smarty->assign('QUICK_REPLY', '');
	}
	
	// Assign Smarty language variables
	$smarty->assign('POSTS', $forum_language['posts']);
	$smarty->assign('REPUTATION', $forum_language['reputation']);
	$smarty->assign('BY', ucfirst($forum_language['by']));
	$smarty->assign('AGO', ''); // to be removed
	
	// Display page template
	$smarty->display('styles/templates/' . $template . '/view_topic.tpl'); 
	
	// Footer
	require('core/includes/template/footer.php');
	$smarty->display('styles/templates/' . $template . '/footer.tpl');
	  
	// Scripts 
	require('core/includes/template/scripts.php');
	?>
	<script src="/core/assets/js/ckeditor.js"></script>
	<script src="/core/assets/js/jquery-ui.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("[rel=tooltip]").tooltip({ placement: 'top'});
			var hash = window.location.hash.substring(1);
			$("#" + hash).effect("highlight", {}, 2000);
			(function() {
			    if (document.location.hash) {
			        setTimeout(function() {
			            window.scrollTo(window.scrollX, window.scrollY - 70);
			        }, 10);
			    }
			})();
		});
		
		CKEDITOR.replace( 'quickreply', {
			// Define the toolbar groups as it is a more accessible solution.
			toolbarGroups: [
				{"name":"basicstyles","groups":["basicstyles"]},
				{"name":"paragraph","groups":["list","align"]},
				{"name":"styles","groups":["styles"]},
				{"name":"colors","groups":["colors"]},
				{"name":"links","groups":["links"]},
				{"name":"insert","groups":["insert"]}
			],
			// Remove the redundant buttons from toolbar groups defined above.
			removeButtons: 'Anchor,Styles,Specialchar,Font,About,Flash,Iframe'
		} );
		CKEDITOR.timestamp = '2';
		CKEDITOR.config.disableNativeSpellChecker = false;
		CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
	</script>
  </body>
</html>
