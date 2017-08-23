<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Edit post page
 */

// Always define page name
define('PAGE', 'forum');

// User must be logged in to proceed
if(!$user->isLoggedIn()){
	Redirect::to(URL::build('/forum'));
	die();
}

// Initialise
require_once('modules/Forum/classes/Forum.php');
$forum = new Forum();
$mentionsParser = new MentionsParser();

require('core/includes/markdown/tohtml/Markdown.inc.php'); // Markdown to HTML

if(isset($_GET['pid']) && isset($_GET['tid'])){
	if(is_numeric($_GET['pid']) && is_numeric($_GET['tid'])){
		$post_id = $_GET['pid'];
		$topic_id = $_GET['tid'];
	} else {
		Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
		die();
	}
} else {
	Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
	die();
}

/*
 *  Is the post the first in the topic? If so, allow the title to be edited.
 */
 
$post_editing = $queries->orderWhere("posts", "topic_id = " . $topic_id, "id", "ASC LIMIT 1");

// Check topic exists
if(!count($post_editing)){
	Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
	die();
}

if($post_editing[0]->id == $post_id){
	$edit_title = true;
	
	/*
	 *  Get the title of the topic
	 */
	 
	$post_title = $queries->getWhere("topics", array("id", "=", $topic_id));
	$post_label = $post_title[0]->label;
	$post_title = Output::getClean($post_title[0]->topic_title);
	
}

/*
 *  Get the post we're editing
 */

$post_editing = $queries->getWhere("posts", array("id", "=", $post_id));

// Check post exists
if(!count($post_editing)){
	Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
	die();
}

$forum_id = $post_editing[0]->forum_id;

// Check permissions before proceeding
$can_reply = $forum->canPostReply($forum_id, $user->data()->group_id, $user->data()->secondary_groups);
if(!$can_reply){
	Redirect::to(URL::build('/forum/view/' . $forum_id));
	die();
}


if($user->data()->id !== $post_editing[0]->post_creator && !($forum->canModerateForum($user->data()->group_id, $forum_id, $user->data()->secondary_groups))){
	Redirect::to(URL::build('/forum/view/' . $forum_id));
	die();
}

// Deal with input
if(Input::exists()){
	// Check token
	if(Token::check(Input::get('token'))){
		// Valid token, check input
		$validate = new Validate();
		$validation = array(
			'content' => array(
				'required' => true,
				'min' => 2,
				'max' => 20480
			)
		);
		// Add title to validation if we need to
		if(isset($edit_title)){
			$validation['title'] = array(
				'required' => true,
				'min' => 2,
				'max' => 64
			);
		}
		
		$validation = $validate->check($_POST, $validation);
		
		if($validation->passed()){
			// Valid post content
			try {
				// Parse markdown
				$cache->setCache('post_formatting');
				$formatting = $cache->retrieve('formatting');
				
				if($formatting == 'markdown'){
					$content = Michelf\Markdown::defaultTransform(Input::get('content'));
					$content = Output::getClean($content);
				} else $content = Output::getClean(Input::get('content'));
				
				// Update post content
				$queries->update("posts", $post_id, array(
					'post_content' => $content,
					'last_edited' => date('U')
				));
				
				if(isset($edit_title)){
					// Update title and label
					// Check a label has been set..
					if(!isset($_POST['topic_label']) || !is_numeric($_POST['topic_label'])) $topic_label = null;
					else {
					    $topic_label = $queries->getWhere('forums_topic_labels', array('id', '=', $_POST['topic_label']));
					    if(count($topic_label)){
                            $groups = explode(',', $topic_label[0]->gids);
                            if(in_array($user->data()->group_id, $groups))
                                $topic_label = $_POST['topic_label'];
                            else {
                                if(!is_null($user->data()->secondary_groups)){
                                    $secondary_groups = json_decode($user->data()->secondary_groups, true);
                                    if(count($secondary_groups)){
                                        $topic_label = null;
                                        foreach($secondary_groups as $group){
                                            if(in_array($group, $groups)){
                                                $topic_label = $_POST['topic_label'];
                                                break;
                                            }
                                        }
                                    } else
                                        $topic_label = null;
                                } else
                                    $topic_label = null;
                            }
                        } else
                            $topic_label = null;
                    }
					
					$queries->update('topics', $topic_id, array(
						'topic_title' => htmlspecialchars_decode(Input::get('title')),
						'label' => $topic_label
					));
				}
				
				// Display success message and redirect
				Session::flash('success_post', $forum_language->get('forum', 'post_edited_successfully'));
				Redirect::to(URL::build('/forum/topic/' . $topic_id, 'pid=' . $post_id));
				die();
				
			} catch(Exception $e){
				die($e->getMessage());
			}
		} else {
			// Error handling
			$errors = array();
			
			foreach($validation->errors() as $item){
				if(strpos($item, 'is required') !== false){
					switch($item){
						case (strpos($item, 'title') !== false):
							$errors[] = $forum_language->get('forum', 'title_required');
						break;
						case (strpos($item, 'content') !== false):
							$errors[] = $forum_language->get('forum', 'content_required');
						break;
					}
				} else if(strpos($item, 'minimum') !== false){
					switch($item){
						case (strpos($item, 'title') !== false):
							$errors[] = $forum_language->get('forum', 'title_min_2');
						break;
						case (strpos($item, 'content') !== false):
							$errors[] = $forum_language->get('forum', 'content_min_2');
						break;
					}
				} else if(strpos($item, 'maximum') !== false){
					switch($item){
						case (strpos($item, 'title') !== false):
							$errors[] = $forum_language->get('forum', 'title_max_64');
						break;
						case (strpos($item, 'content') !== false):
							$errors[] = $forum_language->get('forum', 'content_max_20480');
						break;
					}
				}
			}

		}
	} else {
		// Bad token
		$errors = array($language->get('general', 'invalid_token'));
	}
}
?>

<!DOCTYPE html>
<html lang="<?php echo (defined('HTML_LANG') ? HTML_LANG : 'en'); ?>">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo SITE_NAME; ?> - editing post">

	<meta name="robots" content="noindex">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>
	
	<?php
	// Generate header and navbar content
	// Page title
	$title = $forum_language->get('forum', 'edit_post');
	
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
	
	if(isset($errors)) $smarty->assign('ERRORS', $errors);
	
	$smarty->assign('EDITING_POST', $forum_language->get('forum', 'edit_post'));
	
	if(isset($edit_title)){
		$smarty->assign('EDITING_TOPIC', true);
		
		$smarty->assign('TOPIC_TITLE', $post_title);
		
		// Topic labels
		$smarty->assign('LABELS_TEXT', $forum_language->get('forum', 'label'));
		$labels = array();
		
		$forum_labels = $queries->getWhere('forums_topic_labels', array('id', '<>', 0));
		if(count($forum_labels)){
			$labels[] = array(
				'id' => 0,
				'active' => (($post_label == 0 || is_null($post_label)) ? true : false),
				'html' => $forum_language->get('forum', 'no_label')
			);
			
			foreach($forum_labels as $label){
				$forum_ids = explode(',', $label->fids);
				
				if(in_array($forum_id, $forum_ids)){
                    // Check permissions
                    $groups = explode(',', $label->gids);
                    if(!in_array($user->data()->group_id, $groups)){
                        $perms = false;
                        if(!is_null($user->data()->secondary_groups)) {
                            $secondary_groups = json_decode($user->data()->secondary_groups, true);
                            if(count($secondary_groups)){
                                foreach($secondary_groups as $group){
                                    if(in_array($group, $groups))
                                        $perms = true;
                                }
                            }
                        }
                        if($perms == false) continue;
                    }

                    // Get label HTML
                    $label_html = $queries->getWhere('forums_labels', array('id', '=', $label->label));
                    if (!count($label_html)) continue;
                    else $label_html = str_replace('{x}', Output::getClean($label->name), $label_html[0]->html);

                    $labels[] = array(
                        'id' => $label->id,
                        'active' => (($post_label == $label->id) ? true : false),
                        'html' => $label_html
                    );
                }
			}
		}
		
		$smarty->assign('LABELS', $labels);

	}
	
	$smarty->assign(array(
		'TOKEN' => Token::get(),
		'SUBMIT' => $language->get('general', 'submit'),
		'CANCEL' => $language->get('general', 'cancel'),
		'CANCEL_LINK' => URL::build('/forum/topic/' . $topic_id, 'pid=' . $post_id),
		'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel')
	));
	
	// Get post formatting type (HTML or Markdown)
	$cache->setCache('post_formatting');
	$formatting = $cache->retrieve('formatting');
	
	if($formatting == 'markdown'){
		// Markdown
		$smarty->assign('MARKDOWN', true);
		$smarty->assign('MARKDOWN_HELP', $language->get('general', 'markdown_help'));
	}
	
	// Display template
	$smarty->display('custom/templates/' . TEMPLATE . '/forum/forum_edit_post.tpl'); 

	require('core/templates/scripts.php');

	// Get clean post content
	if($formatting == 'markdown'){
		// Markdown
		require('core/includes/markdown/tomarkdown/autoload.php');
		$converter = new League\HTMLToMarkdown\HtmlConverter(array('strip_tags' => true));

		$clean = $converter->convert(htmlspecialchars_decode($post_editing[0]->post_content));
		$clean = Output::getPurified($clean);
		?>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emoji/js/emojione.min.js"></script>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emojionearea/js/emojionearea.min.js"></script>
	
	<script type="text/javascript">
	  $(document).ready(function() {
	    var el = $("#markdown").emojioneArea({
			pickerPosition: "bottom"
		});
		
		el[0].emojioneArea.setText('<?php echo str_replace(array("'", "&gt;", "&amp;"), array("&#39;", ">", "&"), str_replace(array("\r", "\n"), array("\\r", "\\n"), $clean)); ?>');
	  });
	</script>
		<?php
	} else {
		$clean = htmlspecialchars_decode($post_editing[0]->post_content);
		$clean = Output::getPurified($clean);
	?>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/emoji/js/emojione.min.js"></script>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/ckeditor/plugins/spoiler/js/spoiler.js"></script>
	<script src="<?php if(defined('CONFIG_PATH')) echo CONFIG_PATH . '/'; else echo '/'; ?>core/assets/plugins/ckeditor/ckeditor.js"></script>

	<script type="text/javascript">
		<?php 
		echo Input::createEditor('editor');

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
	
	<?php
	}
	?>

  </body>
</html>
