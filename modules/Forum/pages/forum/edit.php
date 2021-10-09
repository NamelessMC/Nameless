<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Edit post page
 */

// Always define page name
define('PAGE', 'forum');
$page_title = $forum_language->get('forum', 'edit_post');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

$template->addCSSFiles(array(
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/prism/prism.css' => array(),
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/plugins/spoiler/css/spoiler.css' => array(),
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emoji/css/emojione.min.css' => array(),
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emoji/css/emojione.sprites.css' => array(),
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emojionearea/css/emojionearea.min.css' => array(),
));

// User must be logged in to proceed
if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/forum'));
    die();
}

// Initialise
require_once(ROOT_PATH . '/modules/Forum/classes/Forum.php');
$forum = new Forum();
$mentionsParser = new MentionsParser();

require(ROOT_PATH . '/core/includes/markdown/tohtml/Markdown.inc.php'); // Markdown to HTML

if (isset($_GET['pid']) && isset($_GET['tid'])) {
    if (is_numeric($_GET['pid']) && is_numeric($_GET['tid'])) {
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

$post_editing = DB::getInstance()->query('SELECT * FROM nl2_posts WHERE topic_id = ? ORDER BY id ASC LIMIT 1', array($topic_id))->results();

// Check topic exists
if (!count($post_editing)) {
    Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
    die();
}

if ($post_editing[0]->id == $post_id) {
    $edit_title = true;

    /*
	 *  Get the title of the topic
	 */

    $post_title = $queries->getWhere("topics", array("id", "=", $topic_id));
    $post_labels = $post_title[0]->labels ? explode(',', $post_title[0]->labels) : array();
    $post_title = Output::getClean($post_title[0]->topic_title);
}

/*
 *  Get the post we're editing
 */

$post_editing = $queries->getWhere("posts", array("id", "=", $post_id));

// Check post exists
if (!count($post_editing)) {
    Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
    die();
}

$forum_id = $post_editing[0]->forum_id;

// Get user group IDs
$user_groups = $user->getAllGroupIds();

// Check permissions before proceeding
if ($user->data()->id === $post_editing[0]->post_creator && !$forum->canEditTopic($forum_id, $user_groups) && !$forum->canModerateForum($forum_id, $user_groups)) {
    Redirect::to(URL::build('/forum/topic/' . $post_id));
    die();
}

if ($user->data()->id !== $post_editing[0]->post_creator && !($forum->canModerateForum($forum_id, $user_groups))) {
    Redirect::to(URL::build('/forum/topic/' . $post_id));
    die();
}

// Deal with input
if (Input::exists()) {
    // Check token
    if (Token::check()) {
        // Valid token, check input
        $validate = new Validate();
        $validation = [
            'content' => [
                Validate::REQUIRED => true,
                Validate::MIN => 2,
                Validate::MAX => 50000
            ]
        ];
        // Add title to validation if we need to
        if (isset($edit_title)) {
            $validation['title'] = array(
                Validate::REQUIRED => true,
                Validate::MIN => 2,
                Validate::MAX => 64
            );
        }

        $validation = $validate->check($_POST, $validation)->messages([
            'content' => [
                Validate::REQUIRED => $forum_language->get('forum', 'content_required'),
                Validate::MIN => $forum_language->get('forum', 'content_min_2'),
                Validate::MAX => $forum_language->get('forum', 'content_max_50000')
            ],
            'title' => [
                Validate::REQUIRED => $forum_language->get('forum', 'title_required'),
                Validate::MIN => $forum_language->get('forum', 'title_min_2'),
                Validate::MAX => $forum_language->get('forum', 'title_max_64')
            ]
        ]);

        if ($validation->passed()) {
            // Valid post content

            // Parse markdown
            $cache->setCache('post_formatting');
            $formatting = $cache->retrieve('formatting');

            if ($formatting == 'markdown') {
                $content = Michelf\Markdown::defaultTransform(Input::get('content'));
                $content = Output::getClean($content);
            } else $content = Output::getClean(Input::get('content'));

            // Update post content
            $queries->update("posts", $post_id, array(
                'post_content' => $content,
                'last_edited' => date('U')
            ));

            Log::getInstance()->log(Log::Action('forums/post/edit'), $post_id);

            if (isset($edit_title)) {
                // Update title and labels
                $post_labels = array();

                if (isset($_POST['topic_label']) && !empty($_POST['topic_label']) && is_array($_POST['topic_label']) && count($_POST['topic_label'])) {
                    foreach ($_POST['topic_label'] as $topic_label) {
                        $label = $queries->getWhere('forums_topic_labels', array('id', '=', $topic_label));
                        if (count($label)) {
                            $lgroups = explode(',', $label[0]->gids);

                            $hasperm = false;
                            foreach ($user_groups as $group_id) {
                                if (in_array($group_id, $lgroups)) {
                                    $hasperm = true;
                                    break;
                                }
                            }

                            if ($hasperm) $post_labels[] = $label[0]->id;
                        }
                    }
                }

                $queries->update('topics', $topic_id, array(
                    'topic_title' => Output::getDecoded(Input::get('title')),
                    'labels' => implode(',', $post_labels)
                ));

                Log::getInstance()->log(Log::Action('forums/topic/edit'), Output::getDecoded(Input::get('title')));
            }

            // Display success message and redirect
            Session::flash('success_post', $forum_language->get('forum', 'post_edited_successfully'));
            Redirect::to(URL::build('/forum/topic/' . $topic_id, 'pid=' . $post_id));
            die();

        } else {
            // Error handling
            $errors = $validation->errors();
        }
    } else {
        // Bad token
        $errors = array($language->get('general', 'invalid_token'));
    }
}

if (isset($errors))
    $smarty->assign(array(
        'ERROR_TITLE' => $language->get('general', 'error'),
        'ERRORS' => $errors
    ));

$smarty->assign('EDITING_POST', $forum_language->get('forum', 'edit_post'));

if (isset($edit_title) && isset($post_labels)) {
    $smarty->assign('EDITING_TOPIC', true);

    $smarty->assign('TOPIC_TITLE', $post_title);

    // Topic labels
    $smarty->assign('LABELS_TEXT', $forum_language->get('forum', 'label'));
    $labels = array();

    $forum_labels = $queries->getWhere('forums_topic_labels', array('id', '<>', 0));
    if (count($forum_labels)) {
        foreach ($forum_labels as $label) {
            $forum_ids = explode(',', $label->fids);

            if (in_array($forum_id, $forum_ids)) {
                // Check permissions
                $lgroups = explode(',', $label->gids);
                $perms = false;

                foreach ($user_groups as $group) {
                    if (in_array($group, $lgroups))
                        $perms = true;
                }

                if ($perms == false) continue;

                // Get label HTML
                $label_html = $queries->getWhere('forums_labels', array('id', '=', $label->label));
                if (!count($label_html)) continue;
                else $label_html = str_replace('{x}', Output::getClean($label->name), Output::getPurified($label_html[0]->html));

                $labels[] = array(
                    'id' => $label->id,
                    'active' => in_array($label->id, $post_labels),
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
    'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
    'CONTENT' => Output::getPurified(Output::getDecoded($post_editing[0]->post_content))
));

// Get post formatting type (HTML or Markdown)
$cache->setCache('post_formatting');
$formatting = $cache->retrieve('formatting');

if ($formatting == 'markdown') {
    // Markdown
    $smarty->assign('MARKDOWN', true);
    $smarty->assign('MARKDOWN_HELP', $language->get('general', 'markdown_help'));

    require(ROOT_PATH . '/core/includes/markdown/tomarkdown/autoload.php');
    $converter = new League\HTMLToMarkdown\HtmlConverter(array('strip_tags' => true));

    $clean = $converter->convert(Output::getDecoded($post_editing[0]->post_content));
    $clean = Output::getPurified($clean);

    $template->addJSFiles(array(
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emoji/js/emojione.min.js' => array(),
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emojionearea/js/emojionearea.min.js' => array()
    ));

    $template->addJSScript('
	  $(document).ready(function() {
		var el = $("#markdown").emojioneArea({
			pickerPosition: "bottom"
		});

		el[0].emojioneArea.setText(\'' . str_replace(array("'", "&gt;", "&amp;"), array("&#39;", ">", "&"), str_replace(array("\r", "\n"), array("\\r", "\\n"), $clean)) . '\');
 	 });
	');
} else {
    $clean = Output::getDecoded($post_editing[0]->post_content);
    $clean = Output::getPurified($clean);

    $template->addJSFiles(array(
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/prism/prism.js' => array(),
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/plugins/spoiler/js/spoiler.js' => array(),
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/tinymce.min.js' => array()
    ));

    $template->addJSScript(Input::createTinyEditor($language, 'editor'));
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets, $template);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('forum/forum_edit_post.tpl', $smarty);
