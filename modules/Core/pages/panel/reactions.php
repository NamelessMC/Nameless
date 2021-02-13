<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Panel reactions page
 */

$user->handlePanelPageLoad('admincp.core.reactions');

define('PAGE', 'panel');
define('PARENT_PAGE', 'core_configuration');
define('PANEL_PAGE', 'reactions');
$page_title = $language->get('user', 'reactions');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets);

if (Session::exists('api_reactions'))
    $smarty->assign(array(
        'SUCCESS' => Session::flash('api_reactions'),
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ));

if (!isset($_GET['id']) && !isset($_GET['action'])) {
    // Get all reactions
    $reactions = $queries->getWhere('reactions', array('id', '<>', 0));

    $template_reactions = array();
    if (count($reactions)) {
        foreach ($reactions as $reaction) {
            switch ($reaction->type) {
                case 1:
                    $type = $language->get('admin', 'neutral');
                    break;

                case 2:
                    $type = $language->get('admin', 'positive');
                    break;

                default:
                    $type = $language->get('admin', 'negative');
                    break;
            }

            $template_reactions[] = array(
                'edit_link' => URL::build('/panel/core/reactions/', 'id=' . Output::getClean($reaction->id)),
                'name' => Output::getClean($reaction->name),
                'html' => $reaction->html,
                'type_id' => $reaction->type,
                'type' => $type,
                'enabled' => $reaction->enabled
            );
        }
    }

    $smarty->assign(array(
        'NEW_REACTION' => $language->get('admin', 'new_reaction'),
        'NEW_REACTION_LINK' => URL::build('/panel/core/reactions/', 'action=new'),
        'NAME' => $language->get('admin', 'name'),
        'ICON' => $language->get('admin', 'icon'),
        'TYPE' => $language->get('admin', 'type'),
        'ENABLED' => $language->get('admin', 'enabled'),
        'REACTIONS_LIST' => $template_reactions,
        'NO_REACTIONS' => $language->get('admin', 'no_reactions')
    ));

    $template_file = 'core/reactions.tpl';
} else {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'new':
                if (Input::exists()) {
                    $errors = array();
                    if (Token::check()) {
                        // Validate input
                        $validate = new Validate();
                        $validation = $validate->check($_POST, array(
                            'name' => array(
                                'required' => true,
                                'min' => 1,
                                'max' => 16
                            ),
                            'html' => array(
                                'required' => true,
                                'min' => 1,
                                'max' => 255
                            ),
                            'type' => array(
                                'required' => true
                            )
                        ));

                        if ($validation->passed()) {
                            // Check enabled status
                            if (isset($_POST['enabled']) && $_POST['enabled'] == 'on') $enabled = 1;
                            else $enabled = 0;

                            switch (Input::get('type')) {
                                case 1:
                                    $type = 1;
                                    break;
                                case 2:
                                    $type = 2;
                                    break;
                                default:
                                    $type = 0;
                                    break;
                            }

                            // Update database
                            $queries->create('reactions', array(
                                'name' => Output::getClean(Input::get('name')),
                                'html' => Output::getPurified(htmlspecialchars_decode(Input::get('html'))),
                                'type' => $type,
                                'enabled' => $enabled
                            ));

                            Session::flash('api_reactions', $language->get('admin', 'reaction_created_successfully'));
                            Redirect::to(URL::build('/panel/core/reactions'));
                            die();
                        } else {
                            // Validation error
                            foreach ($validation->errors() as $error) {
                                if (strpos($error, 'required') !== false) {
                                    // Required
                                    if (strpos($error, 'name') !== false) {
                                        // Name
                                        $errors[] = $language->get('admin', 'name_required');
                                    } else if (strpos($error, 'html') !== false) {
                                        // HTML
                                        $errors[] = $language->get('admin', 'html_required');
                                    } else {
                                        // Type
                                        $errors[] = $language->get('admin', 'type_required');
                                    }
                                } else if (strpos($error, 'maximum') !== false) {
                                    // Maximum
                                    if (strpos($error, 'name') !== false) {
                                        // Name
                                        $errors[] = $language->get('admin', 'name_maximum_16');
                                    } else {
                                        // HTML
                                        $errors[] = $language->get('admin', 'html_maximum_255');
                                    }
                                }
                            }
                        }
                    } else {
                        // Invalid token
                        $errors[] = $language->get('general', 'token');
                    }
                }

                $smarty->assign(array(
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
                ));

                $template_file = 'core/reactions_new.tpl';

                break;

            case 'delete':
                // Check reaction is specified
                if (!isset($_GET['reaction']) || !is_numeric($_GET['reaction'])) {
                    Redirect::to(URL::build('/panel/core/reactions'));
                    die();
                }

                // Delete reaction
                $queries->delete('reactions', array('id', '=', $_GET['reaction']));

                // Redirect
                Session::flash('api_reactions', $language->get('admin', 'reaction_deleted_successfully'));
                Redirect::to(URL::build('/panel/core/reactions'));
                die();

                break;

            default:
                Redirect::to(URL::build('/panel/core/reactions'));
                die();

                break;
        }
    } else {
        // Get reaction
        $reaction = $queries->getWhere('reactions', array('id', '=', $_GET['id']));
        if (!count($reaction)) {
            // Reaction doesn't exist
            Redirect::to(URL::build('/panel/core/reactions'));
            die();
        } else $reaction = $reaction[0];

        // Deal with input
        if (Input::exists()) {
            $errors = array();

            if (Token::check()) {
                // Validate input
                $validate = new Validate();
                $validation = $validate->check($_POST, array(
                    'name' => array(
                        'required' => true,
                        'min' => 1,
                        'max' => 16
                    ),
                    'html' => array(
                        'required' => true,
                        'min' => 1,
                        'max' => 255
                    ),
                    'type' => array(
                        'required' => true
                    )
                ));

                if ($validation->passed()) {
                    // Check enabled status
                    if (isset($_POST['enabled']) && $_POST['enabled'] == 'on') $enabled = 1;
                    else $enabled = 0;

                    switch (Input::get('type')) {
                        case 1:
                            $type = 1;
                            break;
                        case 2:
                            $type = 2;
                            break;
                        default:
                            $type = 0;
                            break;
                    }

                    // Update database
                    $queries->update('reactions', $_GET['id'], array(
                        'name' => Output::getClean(Input::get('name')),
                        'html' => Output::getPurified(Output::getDecoded(Input::get('html'))),
                        'type' => $type,
                        'enabled' => $enabled
                    ));

                    Session::flash('api_reactions', $language->get('admin', 'reaction_edited_successfully'));
                    Redirect::to(URL::build('/panel/core/reactions'));
                    die();
                } else {
                    // Validation error
                    foreach ($validation->errors() as $error) {
                        if (strpos($error, 'required') !== false) {
                            // Required
                            if (strpos($error, 'name') !== false) {
                                // Name
                                $errors[] = $language->get('admin', 'name_required');
                            } else if (strpos($error, 'html') !== false) {
                                // HTML
                                $errors[] = $language->get('admin', 'html_required');
                            } else {
                                // Type
                                $errors[] = $language->get('admin', 'type_required');
                            }
                        } else if (strpos($error, 'maximum') !== false) {
                            // Maximum
                            if (strpos($error, 'name') !== false) {
                                // Name
                                $errors[] = $language->get('admin', 'name_maximum_16');
                            } else {
                                // HTML
                                $errors[] = $language->get('admin', 'html_maximum_255');
                            }
                        }
                    }
                }
            } else {
                // Invalid token
                $errors[] = $language->get('general', 'invalid_token');
            }
        }

        $smarty->assign(array(
            'CANCEL' => $language->get('general', 'cancel'),
            'CANCEL_LINK' => URL::build('/panel/core/reactions'),
            'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
            'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
            'YES' => $language->get('general', 'yes'),
            'NO' => $language->get('general', 'no'),
            'DELETE' => $language->get('general', 'delete'),
            'DELETE_LINK' => URL::build('/panel/core/reactions/', 'action=delete&reaction=' . $reaction->id),
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
        ));

        $template_file = 'core/reactions_edit.tpl';
    }
}

if (isset($errors) && count($errors))
    $smarty->assign(array(
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error')
    ));

$smarty->assign(array(
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'CONFIGURATION' => $language->get('admin', 'configuration'),
    'REACTIONS' => $language->get('user', 'reactions'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit')
));

$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
