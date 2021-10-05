<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  New topic page
 */

// Always define page name
define('PAGE', 'forum');
$page_title = $forum_language->get('forum', 'new_topic');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// User must be logged in to proceed
if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/forum'));
    die();
}

require_once(ROOT_PATH . '/modules/Forum/classes/Forum.php');
$forum = new Forum();
$mentionsParser = new MentionsParser();

require(ROOT_PATH . '/core/includes/markdown/tohtml/Markdown.inc.php'); // Markdown to HTML

if (!isset($_GET['fid']) || !is_numeric($_GET['fid'])) {
    Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
    die();
}

$fid = (int) $_GET['fid'];

// Get user group ID
$user_groups = $user->getAllGroupIds();

// Does the forum exist, and can the user view it?
$list = $forum->forumExist($fid, $user_groups);
if (!$list) {
    Redirect::to(URL::build('/forum/error/', 'error=not_exist'));
    die();
}

// Can the user post a topic in this forum?
$can_reply = $forum->canPostTopic($fid, $user_groups);
if (!$can_reply) {
    Redirect::to(URL::build('/forum/view/' . $fid));
    die();
}

$current_forum = DB::getInstance()->query('SELECT * FROM nl2_forums WHERE id = ?', array($fid))->first();
$forum_title = Output::getClean(Output::getDecoded($current_forum->forum_title));

// Topic labels
$smarty->assign('LABELS_TEXT', $forum_language->get('forum', 'label'));
$labels = array();

$default_labels = $current_forum->default_labels ? explode(',', $current_forum->default_labels) : array();

$forum_labels = $queries->getWhere('forums_topic_labels', array('id', '<>', 0));
if (count($forum_labels)) {
    foreach ($forum_labels as $label) {
        $forum_ids = explode(',', $label->fids);

        if (in_array($fid, $forum_ids)) {
            // Check permissions
            $lgroups = explode(',', $label->gids);

            $hasperm = false;
            foreach ($user_groups as $group_id) {
                if (in_array($group_id, $lgroups)) {
                    $hasperm = true;
                    break;
                }
            }

            if (!$hasperm)
                continue;

            // Get label HTML
            $label_html = $queries->getWhere('forums_labels', array('id', '=', $label->label));
            if (!count($label_html)) continue;
            else $label_html = str_replace('{x}', Output::getClean($label->name), Output::getPurified($label_html[0]->html));

            $labels[] = array(
                'id' => $label->id,
                'html' => $label_html,
                'checked' => in_array($label->id, $default_labels)
            );
        }
    }
}

// Deal with any inputted data
if (Input::exists()) {
    if (Token::check()) {
        // Check post limits
        $last_post = $queries->orderWhere('posts', 'post_creator = ' . $user->data()->id, 'post_date', 'DESC LIMIT 1');
        if (count($last_post)) {
            if ($last_post[0]->created > strtotime("-30 seconds")) {
                $spam_check = true;
            }
        }

        if (!isset($spam_check)) {
            // Spam check passed
            $validate = new Validate();

            $validate->check($_POST, [
                'title' => [
                    Validate::REQUIRED => true,
                    Validate::MIN => 2,
                    Validate::MAX => 64
                ],
                'content' => [
                    Validate::REQUIRED => true,
                    Validate::MIN => 2,
                    Validate::MAX => 50000
                ]
            ])->messages([
                'title' => [
                    Validate::REQUIRED => $forum_language->get('forum', 'title_required'),
                    Validate::MIN => $forum_language->get('forum', 'title_min_2'),
                    Validate::MAX => $forum_language->get('forum', 'title_max_64')
                ],
                'content' => [
                    Validate::REQUIRED => $forum_language->get('forum', 'content_required'),
                    Validate::MIN => $forum_language->get('forum', 'content_min_2'),
                    Validate::MAX => $forum_language->get('forum', 'content_max_50000')
                ]
            ]);

            if ($validate->passed()) {
                try {
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
                    } else if (count($default_labels)) {
                        $post_labels = $default_labels;
                    }

                    $queries->create("topics", array(
                        'forum_id' => $fid,
                        'topic_title' => Input::get('title'),
                        'topic_creator' => $user->data()->id,
                        'topic_last_user' => $user->data()->id,
                        'topic_date' => date('U'),
                        'topic_reply_date' => date('U'),
                        'labels' => implode(',', $post_labels)
                    ));
                    $topic_id = $queries->getLastId();

                    // Parse markdown
                    $cache->setCache('post_formatting');
                    $formatting = $cache->retrieve('formatting');

                    if ($formatting == 'markdown') {
                        $content = Michelf\Markdown::defaultTransform(Input::get('content'));
                        $content = Output::getClean($content);
                    } else $content = Output::getClean(Input::get('content'));

                    $queries->create("posts", array(
                        'forum_id' => $fid,
                        'topic_id' => $topic_id,
                        'post_creator' => $user->data()->id,
                        'post_content' => $content,
                        'post_date' => date('Y-m-d H:i:s'),
                        'created' => date('U')
                    ));

                    // Get last post ID
                    $last_post_id = $queries->getLastId();
                    $content = $mentionsParser->parse($user->data()->id, $content, URL::build('/forum/topic/' . $topic_id, 'pid=' . $last_post_id), array('path' => ROOT_PATH . '/modules/Forum/language', 'file' => 'forum', 'term' => 'user_tag'), array('path' => ROOT_PATH . '/modules/Forum/language', 'file' => 'forum', 'term' => 'user_tag_info', 'replace' => '{x}', 'replace_with' => Output::getClean($user->data()->nickname)));

                    $queries->update("posts", $last_post_id, array(
                        'post_content' => $content
                    ));

                    $queries->update("forums", $fid, array(
                        'last_post_date' => date('U'),
                        'last_user_posted' => $user->data()->id,
                        'last_topic_posted' => $topic_id
                    ));

                    Log::getInstance()->log(Log::Action('forums/topic/create'), Output::getClean(Input::get('title')));

                    // Execute hooks and pass $available_hooks
                    $available_hooks = $queries->getWhere('forums', array('id', '=', $fid));
                    $available_hooks = json_decode($available_hooks[0]->hooks);
                    HookHandler::executeEvent('newTopic', array(
                        'event' => 'newTopic',
                        'uuid' => Output::getClean($user->data()->uuid),
                        'username' => $user->getDisplayname(true),
                        'nickname' => $user->getDisplayname(),
                        'content' => str_replace(array('{x}', '{y}'), array($forum_title, $user->getDisplayname()), $forum_language->get('forum', 'new_topic_text')),
                        'content_full' => strip_tags(str_ireplace(array('<br />', '<br>', '<br/>'), "\r\n", Input::get('content'))),
                        'avatar_url' => $user->getAvatar(128, true),
                        'title' => Input::get('title'),
                        'url' => Util::getSelfURL() . ltrim(URL::build('/forum/topic/' . $topic_id . '-' . $forum->titleToURL(Input::get('title'))), '/'),
                        'available_hooks' => $available_hooks == null ? [] : $available_hooks
                    ));

                    Session::flash('success_post', $forum_language->get('forum', 'post_successful'));

                    Redirect::to(URL::build('/forum/topic/' . $topic_id . '-' . $forum->titleToURL(Input::get('title'))));
                    die();
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $error = $validate->errors();
            }
        } else {
            $error = array(str_replace('{x}', (strtotime($last_post[0]->post_date) - strtotime("-30 seconds")), $forum_language->get('forum', 'spam_wait')));
        }
    } else {
        $error = array($language->get('general', 'invalid_token'));
    }
}

// Generate a token
$token = Token::get();

$template->addCSSFiles(array(
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/prism/prism.css' => array(),
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/plugins/spoiler/css/spoiler.css' => array(),
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emoji/css/emojione.min.css' => array(),
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emoji/css/emojione.sprites.css' => array(),
    (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emojionearea/css/emojionearea.min.css' => array(),
));

// Generate content for template
if (isset($error)) {
    $smarty->assign('ERROR', $error);
}

$creating_topic_in = str_replace('{x}', $forum_title, $forum_language->get('forum', 'creating_topic_in_x'));
$smarty->assign('CREATING_TOPIC_IN', $creating_topic_in);

// Get info about forum
$forum_query = $queries->getWhere('forums', array('id', '=', $fid));
$forum_query = $forum_query[0];

// Placeholder?
if ($forum_query->topic_placeholder) {
    $placeholder = Output::getPurified($forum_query->topic_placeholder);
}

// Smarty variables
$smarty->assign(array(
    'LABELS' => $labels,
    'TOPIC_TITLE' => $forum_language->get('forum', 'topic_title'),
    'LABEL' => $forum_language->get('forum', 'label'),
    'SUBMIT' => $language->get('general', 'submit'),
    'CANCEL' => $language->get('general', 'cancel'),
    'CLOSE' => $language->get('general', 'close'),
    'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
    'YES' => $language->get('general', 'yes'),
    'NO' => $language->get('general', 'no'),
    'TOKEN' => '<input type="hidden" name="token" value="' . $token . '">',
    'FORUM_LINK' => URL::build('/forum'),
    'CONTENT' => ((isset($_POST['content']) && $_POST['content']) ? Output::getPurified(Input::get('content')) : (isset($placeholder) ? $placeholder : '')),
    'FORUM_TITLE' => Output::getClean($forum_title),
    'FORUM_DESCRIPTION' => Output::getPurified($forum_query->forum_description),
    'NEWS_FORUM' => $forum_query->news
));

// Get post formatting type (HTML or Markdown)
$cache->setCache('post_formatting');
$formatting = $cache->retrieve('formatting');

if ($formatting == 'markdown') {
    // Markdown
    $smarty->assign('MARKDOWN', true);
    $smarty->assign('MARKDOWN_HELP', $language->get('general', 'markdown_help'));

    $template->addJSFiles(array(
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emoji/js/emojione.min.js' => array(),
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/emojionearea/js/emojionearea.min.js' => array()
    ));

    $template->addJSScript('
	  $(document).ready(function() {
		var el = $("#markdown").emojioneArea({
			pickerPosition: "bottom"
		});
	  });
	');
} else {
    $template->addJSFiles(array(
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/prism/prism.js' => array(),
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/plugins/spoiler/js/spoiler.js' => array(),
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/tinymce.min.js' => array()
    ));

    $template->addJSScript(Input::createTinyEditor($language, 'reply'));
}

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets, $template);

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/navbar.php');
require(ROOT_PATH . '/core/templates/footer.php');

// Display template
$template->displayTemplate('forum/new_topic.tpl', $smarty);
