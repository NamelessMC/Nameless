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

require('core/includes/paginate.php'); // Get number of items to display on a page
require('core/includes/htmlpurifier/HTMLPurifier.standalone.php'); // HTML Purifier

if($user->isLoggedIn()){ // User must be logged in to search
	if(Input::exists()){
		if(Token::check(Input::get('token'))){
			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'forum_search' => array(
					'required' => true,
					'min' => 2,
					'max' => 32
				)
			));
			if($validation->passed()){
				$search = str_replace(' ', '+', htmlspecialchars(Input::get('forum_search')));
				$search = preg_replace("/[^a-zA-Z0-9 +]+/", "", $search); // alphanumeric only
				echo '<script type="text/javascript">window.location.replace(\'/forum/search/?s=' . $search . '&p=1\');</script>';
				die();
				
			} else {
				Session::flash('forum_search', '<div class="alert alert-danger">' . $forum_language['search_error'] . '</div>');
			}
		} else {
			Session::flash('forum_search', '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>');
		}
	} else {
		if(isset($_GET['s'])){
			$search = htmlspecialchars(str_replace('+', ' ', $_GET['s']));
			$search = preg_replace("/[^a-zA-Z0-9 +]+/", "", $search); // alphanumeric only
			
			// Get page
			if(isset($_GET['p'])){
				if(!is_numeric($_GET['p'])){
					Redirect::to("/forum");
					die();
				} else {
					$p = $_GET['p'];
					
					// Execute search
					$search_topics = $queries->getLike('topics', 'topic_title', '%' . $search . '%');
					$search_posts = $queries->getLike('posts', 'post_content', '%' . $search . '%');
					
					$search_results = array_merge((array)$search_topics, (array)$search_posts);
					
					$input = true;
				}
			} else {
				echo '<script type="text/javascript">window.location.replace(\'/forum/search/?p=1&s=' . $search . '\');</script>';
				die();
			}
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="<?php echo $sitename; ?>">
	<meta name="robots" content="noindex">
    <?php if(isset($custom_meta)){ echo $custom_meta; } ?>
	
	<?php
	// Generate header and navbar content
	// Page title
	$title = $navbar_language['forum'];
	
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
	?>
	<br />
	<div class="container">
      <h2><?php echo $general_language['search']; ?></h2>
	  <?php
	if($user->isLoggedIn()){
	  if(Session::exists('forum_search')){
		echo Session::flash('forum_search');
	  }
	  
	  if(isset($input)){
		// Merge topics with their posts
		$merged_results = array();
		foreach($search_results as $result){
			if(!isset($result->topic_title)){
				// Posts
				if($result->deleted == 1) continue;
				
				$merged_results[]['post'] = array(
					'topic_id' => $result->topic_id,
					'post_creator' => $result->post_creator,
					'post_content' => $result->post_content,
					'post_date' => $result->post_date,
					'post_id' => $result->id,
					'forum_id' => $result->forum_id
				);
			} else {
				// Topics
				$merged_results[]['topic'] = array(
					'topic_id' => $result->id,
					'topic_title' => $result->topic_title,
					'label' => $result->label,
					'forum_id' => $result->forum_id
				);
			}
		}
		
		// Loop through merged items and see if they're just topics, just posts, or both
		foreach($merged_results as $key => $result){
			// Check type; topic or post?
			if(isset($result['post']['post_content'])) $type = 'post';
			else $type = 'topic';
			
			// Check permissions
			$permissions = $queries->getWhere('forums_permissions', array('forum_id', '=', $result[$type]['forum_id']));
			foreach($permissions as $permission){
				if($permission->group_id == $user->data()->group_id){
					if($permission->view == 0){
						unset($merged_results[$key]);
						continue 2;
					} else break;
				}
			}
			
			// Attach corresponding post to topic
			if(isset($result['topic']['topic_title'])){
				$result_post = $queries->orderWhere('posts', 'topic_id = ' . $result['topic']['topic_id'], 'id ASC LIMIT 1');
				$merged_results[$key]['post'] = array(
					'topic_id' => $result_post[0]->topic_id,
					'post_id' => $result_post[0]->id,
					'post_creator' => $result_post[0]->post_creator,
					'post_content' => $result_post[0]->post_content,
					'post_date' => $result_post[0]->post_date
				);
				$result_post = null; // clear out variable
			}
			
			// Attach corresponding topic to post
			if(isset($result['post']['post_content'])){
				$result_topic = $queries->getWhere('topics', array('id', '=', $result['post']['topic_id']));
				$merged_results[$key]['topic'] = array(
					'topic_id' => $result_topic[0]->id,
					'topic_title' => $result_topic[0]->topic_title,
					'label' => $result_topic[0]->label
				);
				$result_topic = null; // clear out variable
			}
		}
		
		// Reset array keys (for pagination)
		$merged_results = array_values($merged_results);
		
		if(!count($merged_results)){
			// No results
			echo '<div class="alert alert-danger">' . $forum_language['no_search_results'] . '</div>';
		} else {
			// Generate form token
			$token = Token::generate();
			
			// Pagination
			$pagination = new Pagination();
			$pagination->setCurrent($p);
			$pagination->setTotal(count($merged_results));
			$pagination->alwaysShowPagination();

			// Get number of users we should display on the page
			$paginate = PaginateArray($p);

			$n = $paginate[0];
			$f = $paginate[1];
			
			// Get the number we need to finish on ($d)
			if(count($merged_results) > $f){
				$d = $p * 10;
			} else {
				$d = count($merged_results) - $n;
				$d = $d + $n;
			}

			// Initialise HTMLPurifier
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
			$purifier = new HTMLPurifier($config);
			
			// Display the specific number of results
			while($n < $d){
			?>
	  <div class="panel panel-primary">
	    <div class="panel-heading">
		  <a href="/forum/view_topic/?tid=<?php echo $merged_results[$n]['topic']['topic_id']; ?>&amp;pid=<?php echo $merged_results[$n]['post']['post_id']; ?>" class="white-text"><?php echo htmlspecialchars($merged_results[$n]['topic']['topic_title']); ?></a>
		  <span class="pull-right">
		    <a class="white-text" href="/profile/<?php echo htmlspecialchars($user->idToMCName($merged_results[$n]['post']['post_creator'])); ?>"><?php echo htmlspecialchars($user->idToName($merged_results[$n]['post']['post_creator'])); ?></a> <img class="img-rounded" style="height:25px; width:25px;" src="<?php echo $user->getAvatar($merged_results[$n]['post']['post_creator'], '../', 25); ?>" />
		  </span>
		</div>
		<div class="panel-body">
		  <?php echo $purifier->purify(htmlspecialchars_decode($merged_results[$n]['post']['post_content'])); ?>
		  <hr />
		  <?php if(!($merged_results[$n]['topic']['topic_title']) && $user->canViewMCP($user->data()->id)){ ?>
		  <form class="form-inline" action="/forum/delete_post/" method="post" id="form<?php echo $n; ?>">
		    <input type="hidden" name="token" value="<?php echo $token; ?>">
			<input type="hidden" name="pid" value="<?php echo $merged_results[$n]['post']['post_id']; ?>">
			<?php
			// Search input
			$search = str_replace(' ', '+', htmlspecialchars($_GET['s']));
			$search = preg_replace("/[^a-zA-Z0-9 +]+/", "", $search); // alphanumeric only
			?>
			<input type="hidden" name="search_string" value="<?php echo $search; ?>">
		  </form>
		  <a href="#" onclick="document.getElementById('form<?php echo $n; ?>').submit()">
			<span class="label label-danger">Delete</span>
		  </a>
		  <?php } ?>
		  <span class="pull-right">
		    <span class="label label-info"><?php echo date('d M Y, H:i', strtotime($merged_results[$n]['post']['post_date'])); ?></span>
		  </span>
		</div>
	  </div>
			<?php
				$n++;
			}
		
			echo $pagination->parse(); // Print pagination
		}
	  } else {
	  ?>
	  <form class="form-horizontal" role="form" method="post" action="">
	    <div class="input-group">
	      <input type="text" class="form-control" name="forum_search" placeholder="Search">
		  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
	      <span class="input-group-btn">
		    <button type="submit" class="btn btn-default">
              <i class="fa fa-search"></i>
            </button>
	      </span>
	    </div>
	  </form>
	  <?php
	  }
	} else {
		// user is not logged in
		echo '<div class="alert alert-danger">' . $user_language['not_logged_in'] . '</div>';
	}
	?>
	</div>
	<?php 
	// Footer
	require('core/includes/template/footer.php');
	$smarty->display('styles/templates/' . $template . '/footer.tpl');
	  
	// Scripts 
	require('core/includes/template/scripts.php');
	?>
  </body>
</html>
