<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Moderator reports page
 */

// Can the user view the ModCP?
if($user->isLoggedIn()){
	if(!$user->canViewMCP()){
		// No
		Redirect::to(URL::build('/'));
		die();
	} else if(!$user->hasPermission('modcp.reports')){
        // Can't view this page
        require(ROOT_PATH . '/404.php');
        die();
    }
} else {
	// Not logged in
	Redirect::to(URL::build('/login'));
	die();
}
 
define('PAGE', 'mod_reports');
$page_title = $language->get('moderator', 'mod_cp');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Initialise timeago class
$timeago = new Timeago(TIMEZONE);

require(ROOT_PATH . '/core/templates/mod_navbar.php');

if(!isset($_GET['report'])){
	$reports = array();

	if(!isset($_GET['view'])){
		// Get open reports
		$report_query = $queries->orderWhere('reports', 'status = 0', 'date_updated', 'DESC');

		$smarty->assign(array(
			'CHANGE_VIEW' => $language->get('moderator', 'view_closed'),
			'CHANGE_VIEW_LINK' => URL::build('/mod/reports/', 'view=closed')
		));
	} else {
		// Get closed reports
		$report_query = $queries->getWhere('reports', array('status', '=', 1));

		$smarty->assign(array(
			'CHANGE_VIEW' => $language->get('moderator', 'view_open'),
			'CHANGE_VIEW_LINK' => URL::build('/mod/reports/')
		));
	}

	if(count($report_query)){
		foreach($report_query as $report){
			// Get comments count
			$comments = $queries->getWhere('reports_comments', array('report_id', '=', $report->id));
			$comments = count($comments);

			if($report->type == 0){
				// Site report
				$user_reported = Output::getClean($user->idToNickname($report->reported_id));
				$user_profile = URL::build('/profile/' . Output::getClean($user->idToName($report->reported_id)));
				$user_style = $user->getGroupClass($report->reported_id);
			} else {
				// Ingame report
				$user_reported = Output::getClean($report->reported_mcname);
				$user_profile = URL::build('/profile/' . Output::getClean($report->reported_mcname));
				$user_style = '';
			}

			$reports[] = array(
				'id' => $report->id,
				'user_reported' => $user_reported,
				'user_profile' => $user_profile,
				'user_reported_style' => $user_style,
				'link' => URL::build('/mod/reports/', 'report=' . $report->id),
				'updated_by' => Output::getClean($user->idToNickname($report->updated_by)),
				'updated_by_profile' => URL::build('/profile/' . Output::getClean($user->idToName($report->updated_by))),
				'updated_by_style' => $user->getGroupClass($report->updated_by),
				'comments' => $comments
			);
		}
	} else {
		if(!isset($_GET['view'])){
			$smarty->assign('NO_REPORTS', $language->get('moderator', 'no_open_reports'));
		} else {
			$smarty->assign('NO_REPORTS', $language->get('moderator', 'no_closed_reports'));
		}
	}

	// Smarty variables
	$smarty->assign(array(
		'MOD_CP' => $language->get('moderator', 'mod_cp'),
		'REPORTS' => $language->get('moderator', 'reports'),
		'ALL_REPORTS' => $reports,
		'VIEW' => $language->get('general', 'view'),
		'USER_REPORTED' => $language->get('moderator', 'user_reported'),
		'COMMENTS' => $language->get('moderator', 'comments'),
		'UPDATED_BY' => $language->get('moderator', 'updated_by'),
		'ACTIONS' => $language->get('moderator', 'actions')
	));

	// Load modules + template
	Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

	$page_load = microtime(true) - $start;
	define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

	$template->onPageLoad();

	require(ROOT_PATH . '/core/templates/navbar.php');
	require(ROOT_PATH . '/core/templates/footer.php');

	// Display template
	$template->displayTemplate('mod/reports.tpl', $smarty);

} else {
	if(!isset($_GET['action'])){
		// Get report
		$report = $queries->getWhere('reports', array('id', '=', $_GET['report']));
		if(!count($report)){
			Redirect::to(URL::build('/mod/reports'));
			die();
		}
		$report = $report[0];

		// Check input
		if(Input::exists()){
			// Check token
			if(Token::check(Input::get('token'))){
				// Valid token
				$validate = new Validate();

				$validation = $validate->check($_POST, array(
					'content' => array(
						'required' => true,
						'min' => 1,
						'max' => 10000
					)
				));

				if($validation->passed()){
					$queries->create('reports_comments', array(
						'report_id' => $_GET['report'],
						'commenter_id' => $user->data()->id,
						'comment_date' => date('Y-m-d H:i:s'),
						'comment_content' => Output::getClean(Input::get('content'))
					));
					Log::getInstance()->log(Log::Action('mod/report/comment'));
				} else {
					// Display error
					$error = $language->get('moderator', 'report_comment_invalid');
				}
			} else {
				// Invalid token
				$error = $language->get('general', 'invalid_token');
			}
		}

		// Get comments
		$comments = $queries->getWhere('reports_comments', array('report_id', '=', $_GET['report']));
		$smarty_comments = array();
		foreach($comments as $comment){
			$smarty_comments[] = array(
				'username' => Output::getClean($user->idToNickname($comment->commenter_id)),
				'profile' => URL::build('/profile/' . Output::getClean($user->idToName($comment->commenter_id))),
				'style' => $user->getGroupClass($comment->commenter_id),
				'avatar' => $user->getAvatar($comment->commenter_id),
				'content' => Output::getPurified(htmlspecialchars_decode($comment->comment_content)),
				'date' => date('d M Y, H:i', strtotime($comment->comment_date)),
				'date_friendly' => $timeago->inWords($comment->comment_date, $language->getTimeLanguage())
			);
		}

		if(!$report->reported_id){
			$reported_user = Output::getClean($report->reported_mcname);
			$reported_user_profile = URL::build('/profile/' . Output::getClean($report->reported_mcname));
			$reported_user_style = '';
			$reported_user_avatar = '';
		} else {
			$reported_user = Output::getClean($user->idToNickname($report->reported_id));
			$reported_user_profile = URL::build('/profile/' . Output::getClean($user->idToName($report->reported_id)));
			$reported_user_style = $user->getGroupClass($report->reported_id);
			$reported_user_avatar = $user->getAvatar($report->reported_id);
		}

		// Smarty variables
		$smarty->assign(array(
			'MOD_CP' => $language->get('moderator', 'mod_cp'),
			'REPORTS' => $language->get('moderator', 'reports'),
			'REPORTS_LINK' => URL::build('/mod/reports'),
			'VIEWING_REPORT' => $language->get('moderator', 'viewing_report'),
			'BACK' => $language->get('general', 'back'),
			'REPORTED_USER' => $reported_user,
			'REPORTED_USER_PROFILE' => $reported_user_profile,
			'REPORTED_USER_STYLE' => $reported_user_style,
			'REPORTED_USER_AVATAR' => $reported_user_avatar,
			'REPORT_DATE' => date('d M Y, H:i', strtotime($report->date_reported)),
			'REPORT_DATE_FRIENDLY' => $timeago->inWords($report->date_reported, $language->getTimeLanguage()),
			'CONTENT_LINK' => $report->link,
			'VIEW_CONTENT' => $language->get('moderator', 'view_content'),
			'REPORT_CONTENT' => Output::getPurified(htmlspecialchars_decode($report->report_reason)),
			'REPORTER_USER' => Output::getClean($user->idToNickname($report->reporter_id)),
			'REPORTER_USER_PROFILE' => URL::build('/profile/' . Output::getClean($user->idToName($report->reporter_id))),
			'REPORTER_USER_STYLE' => $user->getGroupClass($report->reporter_id),
			'REPORTER_USER_AVATAR' => $user->getAvatar($report->reporter_id),
			'COMMENTS' => $smarty_comments,
			'COMMENTS_TEXT' => $language->get('moderator', 'comments'),
			'NO_COMMENTS' => $language->get('moderator', 'no_comments'),
			'NEW_COMMENT' => $language->get('moderator', 'new_comment'),
			'SUBMIT' => $language->get('general', 'submit'),
			'TOKEN' => Token::get(),
			'ERROR' => (isset($error) ? $error : false),
			'TYPE' => $report->type
		));

		// Close/reopen link
		if($report->status == 0){
			$smarty->assign(array(
				'CLOSE_LINK' => URL::build('/mod/reports/', 'action=close&amp;report=' . $report->id),
				'CLOSE_REPORT' => $language->get('moderator', 'close_report')
			));
		} else {
			$smarty->assign(array(
				'REOPEN_LINK' => URL::build('/mod/reports/', 'action=open&amp;report=' . $report->id),
				'REOPEN_REPORT' => $language->get('moderator', 'reopen_report')
			));
		}

		// Load modules + template
		Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

		$page_load = microtime(true) - $start;
		define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

		$template->onPageLoad();

		require(ROOT_PATH . '/core/templates/navbar.php');
		require(ROOT_PATH . '/core/templates/footer.php');

		// Display template
		$template->displayTemplate('mod/view_report.tpl', $smarty);

	} else {
		if($_GET['action'] == 'close'){
			// Close report
			if(is_numeric($_GET['report'])){
				// Get report
				$report = $queries->getWhere('reports', array('id', '=', $_GET['report']));
				if(count($report)){
					$queries->update('reports', $report[0]->id, array(
						'status' => 1
					));
				}

				Log::getInstance()->log(Log::Action('mod/report/close'), Output::getClean($_GET['id']));

				Redirect::to(URL::build('/mod/reports/', 'report=' . $report[0]->id));
				die();
			}

			Redirect::to(URL::build('/mod/reports'));
			die();

		} else if($_GET['action'] == 'open'){
			// Reopen report
			if(is_numeric($_GET['report'])){
				// Get report
				$report = $queries->getWhere('reports', array('id', '=', $_GET['report']));
				if(count($report)){
					$queries->update('reports', $report[0]->id, array(
						'status' => 0
					));
				}

				Log::getInstance()->log(Log::Action('mod/report/open'), Output::getClean($_GET['report']));

				Redirect::to(URL::build('/mod/reports/', 'report=' . $report[0]->id));
				die();
			}

			Redirect::to(URL::build('/mod/reports'));
			die();

		} else {
			Redirect::to(URL::build('/mod/reports'));
			die();
		}
	}
}
