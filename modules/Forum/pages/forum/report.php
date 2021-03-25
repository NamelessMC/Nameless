<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Report a post
 */

if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/forum'));
    die();
}

require_once(ROOT_PATH . '/modules/Forum/classes/Forum.php');

// Always define page name
define('PAGE', 'forum');

// Initialise
$forum = new Forum();

// Get the post
if (!isset($_POST['post']) || !is_numeric($_POST['post'])) {
    Redirect::to(URL::build('/forum'));
    die();
}

$post = $queries->getWhere('posts', array('id', '=', $_POST['post']));
if (!count($post)) {
    // Doesn't exist
    Redirect::to(URL::build('/forum'));
    die();
}
$post = $post[0];

// Check token
if (Token::check()) {
    // Valid token
    // Ensure user hasn't already submitted a report for this post
    $reports = $queries->getWhere('reports', array('reported_post', '=', $_POST['post']));

    if (count($reports)) {
        foreach ($reports as $report) {
            if ($report->reporter_id == $user->data()->id && $report->status == 0) {
                // User already has an open report
                Session::flash('failure_post', $forum_language->get('forum', 'post_already_reported'));
                Redirect::to(URL::build('/forum/topic/' . Output::getClean($_POST['topic'])));
                die();
            }
        }
    }

    $validate = new Validate();

    $validation = $validate->check($_POST, [
        'reason' => [
            Validate::REQUIRED => true,
            Validate::MIN => 2,
            Validate::MAX => 1024
        ]
    ]);

    if ($validation->passed()) {
        try {
            $report = new Report();

            // Create report
            $report->create(array(
                'type' => 0,
                'reporter_id' => $user->data()->id,
                'reported_id' => $post->post_creator,
                'date_reported' => date('Y-m-d H:i:s'),
                'date_updated' => date('Y-m-d H:i:s'),
                'reported' => date('U'),
                'updated' => date('U'),
                'report_reason' => Output::getClean($_POST['reason']),
                'updated_by' => $user->data()->id,
                'reported_post' => $post->id,
                'link' => URL::build('/forum/topic/' . Output::getClean($_POST['topic']), 'pid=' . Output::getClean($_POST['post']))
            ));
            Log::getInstance()->log(Log::Action('misc/report'), $post->post_creator);
        } catch (Exception $e) {
            // Exception creating report
            Session::flash('failure_post', $e->getMessage());
            Redirect::to(URL::build('/forum/topic/' . Output::getClean($_POST['topic'])));
            die();
        }

        Session::flash('success_post', $language->get('user', 'report_created'));
        Redirect::to(URL::build('/forum/topic/' . Output::getClean($_POST['topic'])));
        die();
    } else {
        // Invalid report content
        Session::flash('failure_post', $language->get('user', 'invalid_report_content'));
        Redirect::to(URL::build('/forum/topic/' . Output::getClean($_POST['topic'])));
        die();
    }
} else {
    // Invalid token
    Session::flash('failure_post', $language->get('general', 'invalid_token'));
    Redirect::to(URL::build('/forum/topic/' . Output::getClean($_POST['topic'])));
    die();
}
