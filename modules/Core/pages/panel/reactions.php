<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Panel reactions page
 */

if (!$user->handlePanelPageLoad('admincp.core.reactions')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'core_configuration';
const PANEL_PAGE = 'reactions';
$page_title = $language->get('user', 'reactions');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

$template->assets()->include(
    AssetTree::JQUERY_UI,
);

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (Session::exists('api_reactions')) {
    $smarty->assign([
        'SUCCESS' => Session::flash('api_reactions'),
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);
}

if (Session::exists('api_reactions_error')) {
    $smarty->assign([
        'ERRORS' => [Session::flash('api_reactions_error')],
        'ERRORS_TITLE' => $language->get('general', 'error')
    ]);
}

if (!isset($_GET['id']) && !isset($_GET['action'])) {
    // Get all reactions
    $template_reactions = [];
    foreach (Reaction::all() as $reaction) {
        switch ($reaction->type) {
            case Reaction::TYPE_NEUTRAL:
                $type = $language->get('admin', 'neutral');
                break;

            case Reaction::TYPE_POSITIVE:
                $type = $language->get('admin', 'positive');
                break;

            default:
                $type = $language->get('admin', 'negative');
                break;
        }

        $template_reactions[] = [
            'id' => $reaction->id,
            'edit_link' => URL::build('/panel/core/reactions/', 'id=' . urlencode($reaction->id)),
            'name' => Output::getClean($reaction->name),
            'html' => Text::renderEmojis($reaction->html),
            'type_id' => $reaction->type,
            'type' => $type,
            'enabled' => $reaction->enabled
        ];
    }

    $smarty->assign([
        'NEW_REACTION' => $language->get('admin', 'new_reaction'),
        'NEW_REACTION_LINK' => URL::build('/panel/core/reactions/', 'action=new'),
        'NAME' => $language->get('admin', 'name'),
        'ICON' => $language->get('admin', 'icon'),
        'TYPE' => $language->get('admin', 'type'),
        'ENABLED' => $language->get('admin', 'enabled'),
        'REACTIONS_LIST' => $template_reactions,
        'NO_REACTIONS' => $language->get('admin', 'no_reactions'),
        'REORDER_DRAG_URL' => URL::build('/panel/core/reactions', 'action=order'),
    ]);

    $template_file = 'core/reactions.tpl';
} else {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'new':
                if (Input::exists()) {
                    $errors = [];
                    if (Token::check()) {
                        // Validate input
                        $validation = Validate::check($_POST, [
                            'name' => [
                                Validate::REQUIRED => true,
                                Validate::MIN => 1,
                                Validate::MAX => 16
                            ],
                            'html' => [
                                Validate::REQUIRED => true,
                                Validate::MIN => 1,
                                Validate::MAX => 255
                            ],
                            'type' => [
                                Validate::REQUIRED => true
                            ]
                        ])->messages([
                            'name' => [
                                Validate::REQUIRED => $language->get('admin', 'name_required'),
                                Validate::MAX => $language->get('admin', 'name_maximum_16')
                            ],
                            'html' => [
                                Validate::REQUIRED => $language->get('admin', 'html_required'),
                                Validate::MAX => $language->get('admin', 'html_maximum_255')
                            ],
                            'type' => $language->get('admin', 'type_required')
                        ]);

                        if ($validation->passed()) {
                            // Check enabled status
                            if (isset($_POST['enabled']) && $_POST['enabled'] == 'on') {
                                $enabled = 1;
                            } else {
                                $enabled = 0;
                            }

                            switch (Input::get('type')) {
                                case Reaction::TYPE_NEUTRAL:
                                    $type = Reaction::TYPE_NEUTRAL;
                                    break;
                                case Reaction::TYPE_POSITIVE:
                                    $type = Reaction::TYPE_POSITIVE;
                                    break;
                                default:
                                    $type = Reaction::TYPE_NEGATIVE;
                                    break;
                            }

                            // Update database
                            DB::getInstance()->insert('reactions', [
                                'name' => Input::get('name'),
                                'html' => Input::get('html'),
                                'type' => $type,
                                'enabled' => $enabled
                            ]);

                            Session::flash('api_reactions', $language->get('admin', 'reaction_created_successfully'));
                            Redirect::to(URL::build('/panel/core/reactions'));
                        }

                        // Validation error
                        $errors = $validation->errors();
                    } else {
                        // Invalid token
                        $errors[] = $language->get('general', 'token');
                    }
                }

                $smarty->assign([
                    'CANCEL' => $language->get('general', 'cancel'),
                    'CANCEL_LINK' => URL::build('/panel/core/reactions'),
                    'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
                    'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
                    'YES' => $language->get('general', 'yes'),
                    'NO' => $language->get('general', 'no'),
                    'CREATING_REACTION' => $language->get('admin', 'creating_reaction'),
                    'NAME' => $language->get('admin', 'name'),
                    'NAME_VALUE' => Output::getClean(Input::get('name')),
                    'HTML' => $language->get('admin', 'html'),
                    'HTML_VALUE' => Output::getClean(Input::get('html')),
                    'TYPE' => $language->get('admin', 'type'),
                    'POSITIVE' => $language->get('admin', 'positive'),
                    'NEGATIVE' => $language->get('admin', 'negative'),
                    'NEUTRAL' => $language->get('admin', 'neutral'),
                    'ENABLED' => $language->get('admin', 'enabled')
                ]);

                $template_file = 'core/reactions_new.tpl';

                break;

            case 'delete':
                // Check reaction is specified
                if (!isset($_GET['reaction']) || !is_numeric($_GET['reaction'])) {
                    Redirect::to(URL::build('/panel/core/reactions'));
                }

                if (Token::check($_POST['token'])) {
                    // Delete reaction
                    DB::getInstance()->delete('reactions', ['id', $_GET['reaction']]);
                    Session::flash('api_reactions', $language->get('admin', 'reaction_deleted_successfully'));

                } else {
                    Session::flash('api_reactions_error', $language->get('general', 'invalid_token'));
                }

                // Redirect
                Redirect::to(URL::build('/panel/core/reactions'));

            case 'order':
                if (isset($_GET['reactions'])) {
                    if (!Token::check()) {
                        die('Invalid Token');
                    }

                    $reactions_list = json_decode($_GET['reactions'])->reactions;
                    $i = 1;
                    foreach ($reactions_list as $item) {
                        DB::getInstance()->update('reactions', $item, [
                            'order' => $i
                        ]);
                        $i++;
                    }
                }
                die('Complete');

            default:
                Redirect::to(URL::build('/panel/core/reactions'));
        }
    } else {
        // Get reaction
        $reaction = Reaction::find($_GET['id']);
        if (!$reaction) {
            // Reaction doesn't exist
            Redirect::to(URL::build('/panel/core/reactions'));
        }

        // Deal with input
        if (Input::exists()) {
            $errors = [];

            if (Token::check()) {
                // Validate input
                $validation = Validate::check($_POST, [
                    'name' => [
                        Validate::REQUIRED => true,
                        Validate::MIN => 1,
                        Validate::MAX => 16
                    ],
                    'html' => [
                        Validate::REQUIRED => true,
                        Validate::MIN => 1,
                        Validate::MAX => 255
                    ],
                    'type' => [
                        Validate::REQUIRED => true
                    ]
                ])->messages([
                    'name' => [
                        Validate::REQUIRED => $language->get('admin', 'name_required'),
                        Validate::MAX => $language->get('admin', 'name_maximum_16')
                    ],
                    'html' => [
                        Validate::REQUIRED => $language->get('admin', 'html_required'),
                        Validate::MAX => $language->get('admin', 'html_maximum_255')
                    ],
                    'type' => $language->get('admin', 'type_required')
                ]);

                if ($validation->passed()) {
                    // Check enabled status
                    if (isset($_POST['enabled']) && $_POST['enabled'] == 'on') {
                        $enabled = 1;
                    } else {
                        $enabled = 0;
                    }

                    switch (Input::get('type')) {
                        case Reaction::TYPE_NEUTRAL:
                            $type = Reaction::TYPE_NEUTRAL;
                            break;
                        case Reaction::TYPE_POSITIVE:
                            $type = Reaction::TYPE_POSITIVE;
                            break;
                        default:
                            $type = Reaction::TYPE_NEGATIVE;
                            break;
                    }

                    // Update database
                    DB::getInstance()->update('reactions', $_GET['id'], [
                        'name' => Output::getClean(Input::get('name')),
                        'html' => Output::getPurified(Input::get('html')),
                        'type' => $type,
                        'enabled' => $enabled
                    ]);

                    Session::flash('api_reactions', $language->get('admin', 'reaction_edited_successfully'));
                    Redirect::to(URL::build('/panel/core/reactions'));
                }

                // Validation error
                $errors = $validation->errors();
            } else {
                // Invalid token
                $errors[] = $language->get('general', 'invalid_token');
            }
        }

        $smarty->assign([
            'CANCEL' => $language->get('general', 'cancel'),
            'CANCEL_LINK' => URL::build('/panel/core/reactions'),
            'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
            'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
            'YES' => $language->get('general', 'yes'),
            'NO' => $language->get('general', 'no'),
            'DELETE' => $language->get('general', 'delete'),
            'DELETE_LINK' => URL::build('/panel/core/reactions/', 'action=delete&reaction=' . urlencode($reaction->id)),
            'CONFIRM_DELETE' => $language->get('admin', 'confirm_delete_reaction'),
            'EDITING_REACTION' => $language->get('admin', 'editing_reaction'),
            'NAME' => $language->get('admin', 'name'),
            'NAME_VALUE' => Output::getClean($reaction->name),
            'HTML' => $language->get('admin', 'html'),
            'HTML_VALUE' => Output::getClean($reaction->html),
            'TYPE' => $language->get('admin', 'type'),
            'POSITIVE' => $language->get('admin', 'positive'),
            'NEUTRAL' => $language->get('admin', 'neutral'),
            'NEGATIVE' => $language->get('admin', 'negative'),
            'TYPE_VALUE' => $reaction->type,
            'ENABLED' => $language->get('admin', 'enabled'),
            'ENABLED_VALUE' => $reaction->enabled
        ]);

        $template_file = 'core/reactions_edit.tpl';
    }
}

if (isset($errors) && count($errors)) {
    $smarty->assign([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ]);
}

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'CONFIGURATION' => $language->get('admin', 'configuration'),
    'REACTIONS' => $language->get('user', 'reactions'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit')
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
