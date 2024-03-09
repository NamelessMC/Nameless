<?php

// TODO: Alert notifications for reactions? We should add some sort of debounce to prevent spamming notifications

// Validate form input
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['reactable_id']) || !is_numeric($_GET['reactable_id'])) {
        http_response_code(\Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST);
        die('Invalid input');
    }
    $reactable_id = $_GET['reactable_id'];
    $context = $_GET['context'];
} else {
    // User must be logged in to proceed
    if (!$user->isLoggedIn()) {
        http_response_code(\Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED);
        die('Not logged in');
    }

    if (!isset($_POST['reactable_id'], $_POST['reaction_id']) || !is_numeric($_POST['reactable_id']) || !is_numeric($_POST['reaction_id'])) {
        http_response_code(\Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST);
        die('Invalid input');
    }
    $reactable_id = $_POST['reactable_id'];
    $context = $_POST['context'];
}

$reaction_context = ReactionContextsManager::getInstance()->getContext($context);

if (!$reaction_context->isEnabled()) {
    http_response_code(\Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST);
    die('Reactions disabled in this context');
}

// Ensure exists
$reactable = $reaction_context->validateReactable($reactable_id, $user);
if (!$reactable) {
    http_response_code(\Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST);
    die('Invalid reactable');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    define('PAGE', 'reactions_modal');
    $page_title = 'reactions_modal';
    require_once(ROOT_PATH . '/core/templates/frontend_init.php');

    // Load modules + template
    Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

    $template->onPageLoad();

    $all_reactions = Reaction::find(true, 'enabled');
    $formatted_reactions[0] = [
        'id' => 0,
        'name' => 'All',
        'html' => '',
        'order' => 0,
        'count' => 0,
        'users' => [],
    ];

    $reactions = $reaction_context->getAllReactions($reactable_id);

    foreach ($reactions as $reaction) {
        $reaction_user = new User($reaction->{$reaction_context->reactionUserIdColumn()});

        if (isset($formatted_reactions[$reaction->reaction_id])) {
            $formatted_reactions[$reaction->reaction_id]['count']++;
            $formatted_reactions[$reaction->reaction_id]['users'][] = [
                'id' => $reaction_user->data()->id,
                'nickname' => Output::getClean($reaction_user->getDisplayname()),
                'avatar' => $reaction_user->getAvatar(),
                'profile' => $reaction_user->getProfileURL(),
                'group_style' => $reaction_user->getGroupStyle(),
                'group_html' => $reaction_user->getAllGroupHtml(),
                'reacted_time' => date(DATE_FORMAT, $reaction->time),
                'reaction_html' => $all_reactions[$reaction->reaction_id]->html,
            ];
            continue;
        }

        $formatted_reactions[$reaction->reaction_id] = [
            'id' => $reaction->reaction_id,
            'name' => $all_reactions[$reaction->reaction_id]->name,
            'html' => $all_reactions[$reaction->reaction_id]->html,
            'order' => $all_reactions[$reaction->reaction_id]->order,
            'count' => 1,
            'users' => [
                [
                    'id' => $reaction_user->data()->id,
                    'nickname' => Output::getClean($reaction_user->getDisplayname()),
                    'avatar' => $reaction_user->getAvatar(),
                    'profile' => $reaction_user->getProfileURL(),
                    'group_style' => $reaction_user->getGroupStyle(),
                    'group_html' => $reaction_user->getAllGroupHtml(),
                    'reacted_time' => date(DATE_FORMAT, $reaction->time),
                    'reaction_html' => $all_reactions[$reaction->reaction_id]->html,
                ],
            ],
        ];
    }

    $formatted_reactions[0]['count'] = count($reactions);
    foreach ($formatted_reactions as $reaction) {
        if ($reaction['id'] === 0) {
            continue;
        }
        foreach ($reaction['users'] as $user) {
            $formatted_reactions[0]['users'][] = $user;
        }
    }

    foreach (array_keys($formatted_reactions) as $key) {
        uasort($formatted_reactions[$key]['users'], static function ($a, $b) {
            return strtotime($a['reacted_time']) < strtotime($b['reacted_time']);
        });
    }

    usort($formatted_reactions, static function($a, $b) {
        return $a['order'] - $b['order'];
    });

    $smarty->assign([
        'ACTIVE_TAB' => $_GET['tab'],
        'REACTIONS' => $formatted_reactions,
    ]);

    // modal
    die($template->getTemplate('reactions_modal.tpl', $smarty));
}

// add reaction
if (!Token::check()) {
    http_response_code(\Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST);
    die('Invalid token');
}

$reaction = Reaction::find($_POST['reaction_id']);

if ($reaction_id = $reaction_context->hasReacted($user, $reaction, $reactable_id)) {
    $reaction_context->deleteReaction($reaction_id);
    EventHandler::executeEvent(new UserReactionDeletedEvent(
        $user,
        $reaction,
        $reaction_context->name(),
    ));

    http_response_code(\Symfony\Component\HttpFoundation\Response::HTTP_OK);
    die('Reaction deleted');
}

$receiver = $reaction_context->determineReceiver($reactable);
$reaction_context->giveReaction($user, $receiver, $reaction, $reactable_id);
EventHandler::executeEvent(new UserReactionAddedEvent(
    $user,
    $receiver,
    $reaction,
    $reaction_context->name(),
));

http_response_code(\Symfony\Component\HttpFoundation\Response::HTTP_OK);
die('Reaction added');
