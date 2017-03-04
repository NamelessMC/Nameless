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

// User must be logged in to proceed
if(!$user->isLoggedIn()){
	Redirect::to('/forum');
	die();
}

// Initialise
$forum = new Forum();

require('core/includes/htmlpurifier/HTMLPurifier.standalone.php'); // HTML Purifier


if(isset($_GET["pid"]) && isset($_GET["tid"])){
	if(is_numeric($_GET["pid"]) && is_numeric($_GET["tid"])){
		$post_id = $_GET["pid"];
		$topic_id = $_GET["tid"];
	} else {
		Redirect::to('/forum/error/?error=not_exist');
		die();
	}
} else {
	Redirect::to('/forum/error/?error=not_exist');
	die();
}

/*
 *  Is the post the first in the topic? If so, allow the title to be edited.
 */
 
$post_editing = $queries->orderWhere("posts", "topic_id = " . $topic_id, "id", "ASC LIMIT 1");

if($post_editing[0]->id == $post_id){
	$edit_title = true;
	
	/*
	 *  Get the title of the topic
	 */
	 
	$post_title = $queries->getWhere("topics", array("id", "=", $topic_id));
	$post_label = $post_title[0]->label;
	$post_title = htmlspecialchars($post_title[0]->topic_title);
	
}

/*
 *  Get the post we're editing
 */

$post_editing = $queries->getWhere("posts", array("id", "=", $post_id));


if($user->data()->id === $post_editing[0]->post_creator || $user->canViewMCP($user->data()->id)){ // TODO: Change to permission based if statement
	if(Input::exists()) {
		if(Token::check(Input::get('token'))) {
			$validate = new Validate();
			$validation = array(
				'content' => array(
					'required' => true,
					'min' => 2,
					'max' => 20480
				)
			);
			// add title to validation if we need to
			if(isset($edit_title)){
				$validation['title'] = array(
					'required' => true,
					'min' => 2,
					'max' => 64
				);
			}
			
			$validation = $validate->check($_POST, $validation);
			
			if($validation->passed()){
				try {
					// Update post content
					$queries->update("posts", $post_id, array(
						'post_content' => htmlspecialchars(Input::get('content'))
					));
					
					if(isset($edit_title)){
						// Update title and label
						// Check a label has been set..
						if(!isset($_POST['topic_label'])) $topic_label = 0;
						else $topic_label = $_POST['topic_label'];
						
						$queries->update("topics", $topic_id, array(
							'topic_title' => htmlspecialchars_decode(Input::get('title')),
							'label' => $topic_label
						));
					}
					Session::flash('success_post', '<div class="alert alert-info alert-dismissable"> <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>Post edited.</div>');
					Redirect::to('/forum/view_topic/?tid=' . $topic_id . '&pid=' . $post_id);
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
			// Bad token - TODO: improve this
		}
	}
} else {
	Redirect::to("/forum");
	die();
}

$token = Token::generate();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $sitename; ?> - editing post">
    <meta name="author" content="<?php echo $sitename; ?>">
	<meta name="robots" content="noindex">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>
	
	<?php
	// Generate header and navbar content
	// Page title
	$title = $navbar_language['forum'] . ' - ' . $forum_language['edit_post'];
	
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
	
	if(Session::exists('failure_post')){
		//echo '<br /><div class="container"><center>' . Session::flash('failure_post') . '</center></div>';
		$smarty->assign('SESSION', Session::flash('failure_post'));
	} else {
		$smarty->assign('SESSION', '');
	}
	
	$smarty->assign('EDITING_POST', $forum_language['editing_post']);
	
	if(isset($edit_title)){
		$edit_title_form = '<input type="text" class="form-control" name="title" value="' .  $post_title . '"><br />';
		
		// Topic labels
		$labels = $forum_language['label'];
		
		// Get labels available in this forum
		$forum_labels = $queries->getWhere('forums_topic_labels', array('id', '<>', 0));
		
		if(count($forum_labels)){
			$forum_id = $queries->getWhere('topics', array('id', '=', $topic_id));
			$forum_id = $forum_id[0]->forum_id;
			
			$edit_labels_form = '<div class="well well-sm"><input type="radio" name="topic_label" value="0"';
			
			if($post_label == 0 || $post_label == null){
				$edit_labels_form .= ' checked';
			}
			
			$edit_labels_form .= '>' . $general_language['none'] . '&nbsp;&nbsp;<h4 style="display:inline;">';
			$available_label_ids = array();
			
			foreach($forum_labels as $label){
				$forum_ids = explode(',', $label->fids);
				if(in_array($forum_id, $forum_ids)){
					$available_label_ids[] = $label->id;
				}
			}
			
			foreach($available_label_ids as $label){
				$query = $queries->getWhere('forums_topic_labels', array('id', '=', $label));
				$edit_labels_form .= '<input type="radio" name="topic_label" value="' . $query[0]->id . '"';
				
				if($post_label == $label){
					$edit_labels_form .= ' checked';
				}
				
				$edit_labels_form .= '> <span class="label label-' . htmlspecialchars($query[0]->label) . '">' . htmlspecialchars($query[0]->name) . '</span>&nbsp;&nbsp;';
			}
			
			$edit_labels_form .= '</h4></div>';
		}

	} else {
		$edit_title_form = '';
		$edit_labels_form = '';
	}
	
	$form_content = '<form action="" method="post">' . PHP_EOL .
					'  ' . $edit_title_form . PHP_EOL .
					'  ' . $edit_labels_form . PHP_EOL . 
					'  <textarea name="content" id="editor" rows="3"></textarea><br />' . PHP_EOL .
					'  <input type="hidden" name="token" value="' . $token . '">' . PHP_EOL .
					'  <input type="submit" class="btn btn-primary" value="' . $general_language['submit'] . '">' . PHP_EOL .
					'  <a class="btn btn-danger" href="/forum/view_topic/?tid=' . $topic_id . '" onclick="return confirm(\'' . $forum_language['confirm_cancellation'] . '\')">' . $general_language['cancel'] . '</a>' . PHP_EOL .
					'</form>';
	
	$smarty->assign('FORM_CONTENT', $form_content);
	
	$smarty->display('styles/templates/' . $template . '/forum_edit_post.tpl');
	
	// Footer
	require('core/includes/template/footer.php');
	$smarty->display('styles/templates/' . $template . '/footer.tpl');
	
	// Scripts 
	require('core/includes/template/scripts.php');
	?>
	<script src="/core/assets/js/ckeditor.js"></script>
	<?php
	// Initialise HTML Purifier
	$config = HTMLPurifier_Config::createDefault();
	$config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
	$config->set('URI.DisableExternalResources', false);
	$config->set('URI.DisableResources', false);
	$config->set('HTML.Allowed', 'u,p,b,i,small,blockquote,span[style],span[class],p,strong,em,li,ul,ol,div[align],br,img');
	$config->set('CSS.AllowedProperties', array('text-align', 'float', 'color','background-color', 'background', 'font-size', 'font-family', 'text-decoration', 'font-weight', 'font-style', 'font-size'));
	$config->set('HTML.AllowedAttributes', 'target, href, src, height, width, alt, class, *.style');
	$config->set('Attr.AllowedFrameTargets', array('_blank', '_self', '_parent', '_top'));
	$config->set('HTML.SafeIframe', true);
	$config->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%');
	$purifier = new HTMLPurifier($config);
	
	// Get clean post content
	$clean = $purifier->purify(htmlspecialchars_decode($post_editing[0]->post_content));
	?>
	<script type="text/javascript">
		CKEDITOR.replace( 'editor', {
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
		CKEDITOR.config.extraAllowedContent = 'blockquote small';
		CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
	    <?php 
	    // Insert
	    if(!Session::exists('failure_post')){
	    ?>
		CKEDITOR.on('instanceReady', function(ev) {
		     CKEDITOR.instances.editor.insertHtml('<?php echo str_replace("'", "&#39;", str_replace(array("\r", "\n"), '', $clean)); ?>');
		});
		<?php
		}
		?>
	</script>

  </body>
</html>
