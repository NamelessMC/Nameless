<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr11
 *
 *  License: MIT
 *
 *  Panel profile fields page
 */

if (!$user->handlePanelPageLoad('admincp.core.fields')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'core_configuration';
const PANEL_PAGE = 'custom_profile_fields';
$page_title = $language->get('admin', 'custom_fields');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'new') {
        // New field
        if (Input::exists()) {
            $errors = [];

            if (Token::check()) {
                // Validate input
                $validation = Validate::check($_POST, [
                    'name' => [
                        Validate::REQUIRED => true,
                        Validate::MIN => 2,
                        Validate::MAX => 16
                    ],
                    'type' => [
                        Validate::REQUIRED => true
                    ]
                ])->message($language->get('admin', 'profile_field_error'));

                if ($validation->passed()) {
                    // Input into database
                    try {
                        // Get whether required/public/editable/forum post options are enabled or not
                        if (isset($_POST['required']) && $_POST['required'] == 'on') {
                            $required = 1;
                        } else {
                            $required = 0;
                        }

                        if (isset($_POST['public']) && $_POST['public'] == 'on') {
                            $public = 1;
                        } else {
                            $public = 0;
                        }

                        if (isset($_POST['forum']) && $_POST['forum'] == 'on') {
                            $forum_posts = 1;
                        } else {
                            $forum_posts = 0;
                        }

                        if (isset($_POST['editable']) && $_POST['editable'] == 'on') {
                            $editable = 1;
                        } else {
                            $editable = 0;
                        }

                        // Insert into database
                        DB::getInstance()->insert('profile_fields', [
                            'name' => Input::get('name'),
                            'type' => Input::get('type'),
                            'public' => $public,
                            'required' => $required,
                            'description' => Input::get('description'),
                            'forum_posts' => $forum_posts,
                            'editable' => $editable
                        ]);

                        //Log::getInstance()->log(Log::Action('admin/core/profile/new'), Output::getClean(Input::get('name')));

                        // Redirect
                        Session::flash('profile_field_success', $language->get('admin', 'profile_field_created_successfully'));
                        Redirect::to(URL::build('/panel/core/profile_fields'));
                    } catch (Exception $e) {
                        $errors[] = $e->getMessage();
                    }
                } else {
                    // Display errors
                    $errors = $validation->errors();
                }
            } else {
                // Invalid token
                $errors[] = $language->get('admin', 'invalid_token');
            }
        }

        $smarty->assign([
            'CREATING_PROFILE_FIELD' => $language->get('admin', 'creating_profile_field'),
            'CANCEL' => $language->get('general', 'cancel'),
            'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
            'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
            'YES' => $language->get('general', 'yes'),
            'NO' => $language->get('general', 'no'),
            'CANCEL_LINK' => URL::build('/panel/core/profile_fields'),
            'TOKEN' => Token::get(),
            'SUBMIT' => $language->get('general', 'submit'),
            'FIELD_NAME' => $language->get('admin', 'field_name'),
            'TYPE' => $language->get('admin', 'type'),
            'TYPES' => [1 => $language->get('admin', 'text'), 2 => $language->get('admin', 'textarea'), 3 => $language->get('admin', 'date')],
            'DESCRIPTION' => $language->get('admin', 'description'),
            'REQUIRED' => $language->get('admin', 'required'),
            'EDITABLE' => $language->get('admin', 'editable'),
            'PUBLIC' => $language->get('admin', 'public'),
            'DISPLAY_FIELD_ON_FORUM' => $language->get('admin', 'display_field_on_forum'),
            'INFO' => $language->get('general', 'info'),
            'EDITABLE_HELP' => $language->get('admin', 'profile_field_editable_help'),
            'REQUIRED_HELP' => $language->get('admin', 'profile_field_required_help'),
            'PUBLIC_HELP' => $language->get('admin', 'profile_field_public_help'),
            'DISPLAY_FIELD_ON_FORUM_HELP' => $language->get('admin', 'profile_field_forum_help')
        ]);

        $template_file = 'core/profile_fields_create.tpl';

    } else {
        if ($_GET['action'] == 'edit') {
            if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
                Redirect::to(URL::build('/panel/core/groups'));
            }
            $id = (int)$_GET['id'];

            $field = ProfileField::find($id);

            if (!$field) {
                Redirect::to(URL::build('/panel/core/profile_fields'));
            }

            if (Input::exists()) {
                $errors = [];

                if (Token::check()) {
                    if (Input::get('action') == 'update') {
                        // Validate input
                        $validation = Validate::check($_POST, [
                            'name' => [
                                Validate::REQUIRED => true,
                                Validate::MIN => 2,
                                Validate::MAX => 16
                            ],
                            'type' => [
                                Validate::REQUIRED => true
                            ]
                        ])->message($language->get('admin', 'profile_field_error'));

                        if ($validation->passed()) {
                            // Update database
                            try {
                                // Get whether required/public/editable/forum post options are enabled or not
                                if (isset($_POST['required']) && $_POST['required'] == 'on') {
                                    $required = 1;
                                } else {
                                    $required = 0;
                                }

                                if (isset($_POST['public']) && $_POST['public'] == 'on') {
                                    $public = 1;
                                } else {
                                    $public = 0;
                                }

                                if (isset($_POST['forum']) && $_POST['forum'] == 'on') {
                                    $forum_posts = 1;
                                } else {
                                    $forum_posts = 0;
                                }

                                if (isset($_POST['editable']) && $_POST['editable'] == 'on') {
                                    $editable = 1;
                                } else {
                                    $editable = 0;
                                }

                                // Update database
                                DB::getInstance()->update('profile_fields', $field->id, [
                                    'name' => Output::getClean(Input::get('name')),
                                    'type' => Input::get('type'),
                                    'public' => $public,
                                    'required' => $required,
                                    'description' => Output::getClean(Input::get('description')),
                                    'forum_posts' => $forum_posts,
                                    'editable' => $editable
                                ]);

                                //Log::getInstance()->log(Log::Action('admin/core/profile/update'), Output::getClean(Input::get('name')));

                                // Redirect
                                Session::flash('profile_field_success', $language->get('admin', 'profile_field_updated_successfully'));
                                Redirect::to(URL::build('/panel/core/profile_fields/', 'action=edit&id=' . urlencode($field->id)));
                            } catch (Exception $e) {
                                $errors[] = $e->getMessage();
                            }
                        } else {
                            // Error
                            $errors = $validation->errors();
                        }

                    } else {
                        if (Input::get('action') == 'delete') {
                            // Delete field
                            DB::getInstance()->delete('profile_fields', ['id', (int)$_POST['id']]);

                            Session::flash('profile_field_success', $language->get('admin', 'profile_field_deleted_successfully'));
                            Redirect::to(URL::build('/panel/core/profile_fields'));
                        }
                    }
                } else {
                    $errors[] = $language->get('general', 'invalid_token');
                }
            }

            $smarty->assign([
                'EDITING_PROFILE_FIELD' => $language->get('admin', 'editing_profile_field'),
                'CANCEL' => $language->get('general', 'cancel'),
                'DELETE' => $language->get('general', 'delete'),
                'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
                'CONFIRM_CANCEL' => $language->get('general', 'confirm_cancel'),
                'CONFIRM_DELETE' => $language->get('general', 'confirm_deletion'),
                'YES' => $language->get('general', 'yes'),
                'NO' => $language->get('general', 'no'),
                'CANCEL_LINK' => URL::build('/panel/core/profile_fields'),
                'DELETE_LINK' => URL::build('/panel/core/profile_fields/'),
                'TOKEN' => Token::get(),
                'SUBMIT' => $language->get('general', 'submit'),
                'FIELD_ID' => Output::getClean($field->id),
                'FIELD_NAME' => $language->get('admin', 'field_name'),
                'FIELD_NAME_VALUE' => Output::getClean($field->name),
                'TYPE' => $language->get('admin', 'type'),
                'TYPES' => [1 => $language->get('admin', 'text'), 2 => $language->get('admin', 'textarea'), 3 => $language->get('admin', 'date')],
                'TYPE_VALUE' => $field->type,
                'DESCRIPTION' => $language->get('admin', 'description'),
                'DESCRIPTION_VALUE' => Output::getPurified($field->description),
                'REQUIRED' => $language->get('admin', 'required'),
                'REQUIRED_VALUE' => $field->required,
                'EDITABLE' => $language->get('admin', 'editable'),
                'EDITABLE_VALUE' => $field->editable,
                'PUBLIC' => $language->get('admin', 'public'),
                'PUBLIC_VALUE' => $field->public,
                'DISPLAY_FIELD_ON_FORUM' => $language->get('admin', 'display_field_on_forum'),
                'DISPLAY_FIELD_ON_FORUM_VALUE' => $field->forum_posts,
                'INFO' => $language->get('general', 'info'),
                'EDITABLE_HELP' => $language->get('admin', 'profile_field_editable_help'),
                'REQUIRED_HELP' => $language->get('admin', 'profile_field_required_help'),
                'PUBLIC_HELP' => $language->get('admin', 'profile_field_public_help'),
                'DISPLAY_FIELD_ON_FORUM_HELP' => $language->get('admin', 'profile_field_forum_help')
            ]);

            $template_file = 'core/profile_fields_edit.tpl';
        } else {
            Redirect::to(URL::build('/panel/core/profile_fields'));
        }
    }
} else {
    $template_fields = [];

    foreach (ProfileField::all() as $field) {
        switch ($field->type) {
            case Fields::TEXT:
                $type = $language->get('admin', 'text');
                break;

            case Fields::TEXTAREA:
                $type = $language->get('admin', 'textarea');
                break;

            case Fields::DATE:
                $type = $language->get('admin', 'date');
                break;
        }

        $template_fields[] = [
            'edit_link' => URL::build('/panel/core/profile_fields/', 'action=edit&id=' . urlencode($field->id)),
            'name' => Output::getClean($field->name),
            'type' => $type,
            'required' => $field->required,
            'editable' => $field->editable,
            'public' => $field->public,
            'forum_posts' => $field->forum_posts
        ];
    }

    $smarty->assign([
        'FIELDS' => $template_fields,
        'NO_FIELDS' => $language->get('admin', 'no_custom_fields'),
        'NEW_FIELD' => $language->get('admin', 'new_field'),
        'NEW_FIELD_LINK' => URL::build('/panel/core/profile_fields/', 'action=new'),
        'FIELD_NAME' => $language->get('admin', 'field_name'),
        'TYPE' => $language->get('admin', 'type'),
        'REQUIRED' => $language->get('admin', 'required'),
        'EDITABLE' => $language->get('admin', 'editable'),
        'PUBLIC' => $language->get('admin', 'public'),
        'FORUM_POSTS' => $language->get('admin', 'forum_posts')
    ]);

    $template_file = 'core/profile_fields.tpl';
}

if (Session::exists('profile_field_success')) {
    $success = Session::flash('profile_field_success');
}

if (isset($success)) {
    $smarty->assign([
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success')
    ]);
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
    'PROFILE_FIELDS' => $language->get('admin', 'custom_fields'),
    'PAGE' => PANEL_PAGE
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
