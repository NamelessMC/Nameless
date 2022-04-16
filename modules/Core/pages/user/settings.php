<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr12
 *
 *  License: MIT
 *
 *  UserCP settings
 */

// Must be logged in
if (!$user->isLoggedIn()) {
    Redirect::to(URL::build('/'));
}

// Always define page name for navbar
const PAGE = 'cc_settings';
$page_title = $language->get('user', 'user_cp');
require_once(ROOT_PATH . '/core/templates/frontend_init.php');

// Forum enabled?
$forum_enabled = Util::isModuleEnabled('Forum');

// Two factor auth?
if (isset($_GET['do'])) {
    if ($_GET['do'] == 'enable_tfa') {

        // Ensure TFA is currently disabled
        if ($user->data()->tfa_enabled == 1) {
            Redirect::to(URL::build('/user/settings'));
        }

        $tfa = new \RobThree\Auth\TwoFactorAuth(SITE_NAME);

        if (!isset($_GET['s'])) {
            // Generate secret
            $secret = $tfa->createSecret();

            $user->update([
                'tfa_secret' => $secret
            ]);

            if (Session::exists('force_tfa_alert')) {
                $errors[] = Session::get('force_tfa_alert');
            }

            // Assign Smarty variables
            $smarty->assign([
                'TWO_FACTOR_AUTH' => $language->get('user', 'two_factor_auth'),
                'TFA_SCAN_CODE_TEXT' => $language->get('user', 'tfa_scan_code'),
                'IMG_SRC' => $tfa->getQRCodeImageAsDataUri(SITE_NAME . ':' . Output::getClean($user->data()->username), $secret),
                'TFA_CODE_TEXT' => $language->get('user', 'tfa_code'),
                'TFA_CODE' => chunk_split($secret, 4, ' '),
                'NEXT' => $language->get('general', 'next'),
                'LINK' => URL::build('/user/settings/', 'do=enable_tfa&amp;s=2'),
                'CANCEL' => $language->get('general', 'cancel'),
                'CANCEL_LINK' => URL::build('/user/settings/', 'do=disable_tfa'),
                'ERROR_TITLE' => $language->get('general', 'error')
            ]);

            if (isset($errors) && count($errors)) {
                $smarty->assign([
                    'ERRORS' => $errors
                ]);
            }

            // Load modules + template

            // Display template

        } else {
            // Validate code to see if it matches the secret
            if (Input::exists()) {
                if (Token::check()) {
                    if (isset($_POST['tfa_code'])) {
                        if ($tfa->verifyCode($user->data()->tfa_secret, $_POST['tfa_code']) === true) {
                            $user->update([
                                'tfa_complete' => 1,
                                'tfa_enabled' => 1,
                                'tfa_type' => 1
                            ]);
                            Session::delete('force_tfa_alert');
                            Session::flash('tfa_success', $language->get('user', 'tfa_successful'));
                            Redirect::to(URL::build('/user/settings'));
                        }

                        $error = $language->get('user', 'invalid_tfa');
                    } else {
                        $error = $language->get('user', 'invalid_tfa');
                    }
                } else {
                    $error = $language->get('general', 'invalid_token');
                }
            }

            if (isset($error)) {
                $smarty->assign('ERROR', $error);
            }

            $smarty->assign([
                'TWO_FACTOR_AUTH' => $language->get('user', 'two_factor_auth'),
                'TFA_ENTER_CODE' => $language->get('user', 'tfa_enter_code'),
                'SUBMIT' => $language->get('general', 'submit'),
                'TOKEN' => Token::get(),
                'CANCEL' => $language->get('general', 'cancel'),
                'CANCEL_LINK' => URL::build('/user/settings/', 'do=disable_tfa'),
                'ERROR_TITLE' => $language->get('general', 'error')
            ]);

            // Load modules + template

            // Display template

        }
        Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);
        require(ROOT_PATH . '/core/templates/cc_navbar.php');
        $template->onPageLoad();
        require(ROOT_PATH . '/core/templates/navbar.php');
        require(ROOT_PATH . '/core/templates/footer.php');
        $template->displayTemplate('user/tfa.tpl', $smarty);

    } else {
        if ($_GET['do'] == 'disable_tfa') {
            // Disable TFA
            $user->update([
                'tfa_enabled' => 0,
                'tfa_type' => 0,
                'tfa_secret' => null,
                'tfa_complete' => 0
            ]);

            Redirect::to(URL::build('/user/settings'));
        }
    }

} else {
    // Handle input
    if (Input::exists()) {
        if (Token::check()) {
            if (Input::get('action') == 'settings') {
                $to_validate = [
                    'signature' => [
                        'max' => 900
                    ],
                    'timezone' => [
                        'timezone' => true,
                    ]
                ];

                // Permission to use nickname?
                if ($user->hasPermission('usercp.nickname')) {
                    $to_validate['nickname'] = [
                        Validate::REQUIRED => true,
                        Validate::MIN => 3,
                        Validate::MAX => 20
                    ];

                    $displayname = Output::getClean(Input::get('nickname'));
                } else {
                    $displayname = $user->data()->username;
                }

                // Get a list of required profile fields
                $profile_fields = $queries->getWhere('profile_fields', ['required', '=', 1]);

                if (count($profile_fields)) {
                    foreach ($profile_fields as $field) {
                        if ($field->required == '1') {
                            $to_validate[$field->id] = [
                                'required' => true,
                                'max' => (is_null($field->length) ? 1024 : $field->length)
                            ];
                        } else {
                            $to_validate[$field->id] = [
                                'max' => (is_null($field->length) ? 1024 : $field->length)
                            ];
                        }
                    }
                }

                $validation = Validate::check(
                    $_POST, $to_validate
                )->messages([
                    'signature' => $language->get('user', 'signature_max_900'),
                    'nickname' => [
                        Validate::REQUIRED => $language->get('user', 'nickname_required'),
                        Validate::MIN => $language->get('user', 'nickname_minimum_3'),
                        Validate::MAX => $language->get('user', 'nickname_maximum_20')
                    ],
                    'timezone' => $language->get('general', 'invalid_timezone'),
                    // fallback message for required profile fields
                    '*' => static function ($field) use ($language, $queries) {
                        [$id] = explode(' ', $field);

                        $field = $queries->getWhere('profile_fields', ['id', '=', $id]);
                        if (!count($field)) {
                            return null;
                        }

                        return str_replace('{x}', Output::getClean($field[0]->name), $language->get('user', 'field_is_required'));
                    },
                ]);

                if ($validation->passed()) {
                    // Check nickname is unique
                    if ($user->hasPermission('usercp.nickname')) {
                        $unique_nickname = $queries->getWhere('users', ['nickname', '=', Output::getClean(Input::get('nickname'))]);
                        if (count($unique_nickname)) {
                            $unique_nickname = $unique_nickname[0];
                            if ($unique_nickname->id != $user->data()->id) {
                                // Not unique
                                $nickname_error = true;
                                $error = $language->get('user', 'nickname_already_exists');
                            }
                        }
                    }

                    // Update profile fields
                    if (!isset($nickname_error)) {
                        try {
                            // Update language, template and timezone
                            $new_language = $queries->getWhere('languages', ['name', '=', Input::get('language')]);

                            if (count($new_language)) {
                                $new_language = $new_language[0]->id;
                            } else {
                                $new_language = $user->data()->language_id;
                            }

                            $new_template = $queries->getWhere('templates', ['id', '=', Input::get('template')]);

                            if (count($new_template)) {
                                $new_template = $new_template[0]->id;
                            } else {
                                $new_template = $user->data()->theme_id;
                            }

                            // Check permissions
                            $available_templates = $user->getUserTemplates();

                            foreach ($available_templates as $available_template) {
                                if ($available_template->id == $new_template) {
                                    $can_update = true;
                                    break;
                                }
                            }

                            if (!isset($can_update)) {
                                $new_template = $user->data()->theme_id;
                            }

                            $timezone = Output::getClean(Input::get('timezone'));

                            if ($user->hasPermission('usercp.signature')) {
                                $signature = Input::get('signature');
                            } else {
                                $signature = '';
                            }

                            // Private profiles enabled?
                            $private_profiles = $queries->getWhere('settings', ['name', '=', 'private_profile']);
                            if ($private_profiles[0]->value == 1) {
                                if ($user->canPrivateProfile() && $_POST['privateProfile'] == 1) {
                                    $privateProfile = 1;
                                } else {
                                    $privateProfile = 0;
                                }
                            } else {
                                $privateProfile = $user->data()->private_profile;
                            }

                            $gravatar = $_POST['gravatar'] == '1' ? 1 : 0;

                            $data = [
                                'language_id' => $new_language,
                                'timezone' => $timezone,
                                'signature' => $signature,
                                'nickname' => $displayname,
                                'private_profile' => $privateProfile,
                                'theme_id' => $new_template,
                                'gravatar' => $gravatar
                            ];

                            // Is forum enabled? Update topic Updates
                            if ($forum_enabled) {
                                $topicUpdates = Input::get('topicUpdates');

                                $data['topic_updates'] = $topicUpdates;
                            }

                            $user->update($data);

                            Log::getInstance()->log(Log::Action('user/ucp/update'));

                            foreach ($_POST as $key => $item) {
                                if (!str_contains($key, 'action')&& !str_contains($key, 'token')) {
                                    // Check field exists
                                    $field_exists = $queries->getWhere('profile_fields', ['id', '=', $key]);
                                    if (!count($field_exists)) {
                                        continue;
                                    }

                                    $user_profile_fields = $user->getProfileFields(false);
                                    if (array_key_exists($key, $user_profile_fields)) {
                                        // Update field value
                                        $queries->update('users_profile_fields', $user_profile_fields[$key]['row_id'], [
                                            'value' => $item
                                        ]);
                                    } else {
                                        // Create new field value
                                        $queries->create('users_profile_fields', [
                                            'user_id' => $user->data()->id,
                                            'field_id' => $key,
                                            'value' => $item
                                        ]);
                                    }
                                }
                            }

                            Session::flash('settings_success', $language->get('user', 'settings_updated_successfully'));
                            Redirect::to(URL::build('/user/settings'));

                        } catch (Exception $e) {
                            Session::flash('settings_error', $e->getMessage());
                        }
                    }

                } else {
                    $errors = $validation->errors();
                }
            } else {
                if (Input::get('action') == 'password') {
                    // Change password
                    $validation = Validate::check($_POST, [
                        'old_password' => [
                            Validate::REQUIRED => true
                        ],
                        'new_password' => [
                            Validate::REQUIRED => true,
                            Validate::MIN => 6
                        ],
                        'new_password_again' => [
                            Validate::REQUIRED => true,
                            Validate::MATCHES => 'new_password'
                        ]
                    ])->messages([
                        'old_password' => $language->get('user', 'password_required') . '<br />',
                        'new_password' => [
                            Validate::REQUIRED => $language->get('user', 'password_required') . '<br />',
                            Validate::MIN => $language->get('user', 'password_minimum_6') . '<br />'
                        ],
                        'new_password_again' => [
                            Validate::REQUIRED => $language->get('user', 'password_required') . '<br />',
                            Validate::MATCHES => $language->get('user', 'passwords_dont_match') . '<br />'
                        ]
                    ]);

                    if ($validation->passed()) {
                        // Update password
                        // Check old password matches
                        $old_password = Input::get('old_password');
                        if ($user->checkCredentials($user->data()->username, $old_password, 'username')) {

                            // Hash new password
                            $new_password = password_hash(Input::get('new_password'), PASSWORD_BCRYPT, ['cost' => 13]);

                            // Update password
                            $user->update([
                                'password' => $new_password,
                                'pass_method' => 'default'
                            ]);

                            $success = $language->get('user', 'password_changed_successfully');

                        } else {
                            // Invalid current password
                            Session::flash('settings_error', $language->get('user', 'incorrect_password'));
                        }
                    } else {
                        $errors = $validation->errors();
                    }
                } else {
                    if (Input::get('action') == 'email') {
                        // Change password
                        $validation = Validate::check($_POST, [
                            'password' => [
                                Validate::REQUIRED => true
                            ],
                            'email' => [
                                Validate::REQUIRED => true,
                                Validate::EMAIL => true,
                            ]
                        ])->messages([
                            'password' => [
                                Validate::REQUIRED => $language->get('user', 'password_required') . '<br />'
                            ],
                            'email' => [
                                Validate::REQUIRED => $language->get('user', 'email_required') . '<br />',
                                Validate::EMAIL => $language->get('general', 'contact_message_email') . '<br />'
                            ]
                        ]);

                        if ($validation->passed()) {
                            // Check email doesn't exist
                            $email_query = $queries->getWhere('users', ['email', '=', $_POST['email']]);
                            if (count($email_query)) {
                                if ($email_query[0]->id != $user->data()->id) {
                                    $error = $language->get('user', 'email_already_exists');
                                }
                            }

                            if (!isset($error)) {
                                // Check password matches
                                $password = Input::get('password');
                                if ($user->checkCredentials($user->data()->username, $password, 'username')) {

                                    // Update email
                                    $user->update([
                                        'email' => Output::getClean($_POST['email'])
                                    ]);

                                    Session::flash('settings_success', $language->get('user', 'email_changed_successfully'));
                                    Redirect::to(URL::build('/user/settings'));
                                }

                                // Invalid password
                                Session::flash('settings_error', $language->get('user', 'incorrect_password'));
                            }
                        } else {
                            $errors = $validation->errors();
                        }
                    }
                }
            }
        } else {
            // Invalid form token
            Session::flash('settings_error', $language->get('general', 'invalid_token'));
        }
    }

    $template->addCSSFiles([
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.standalone.min.css' => [],
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/prism/prism.css' => [],
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/plugins/spoiler/css/spoiler.css' => [],
    ]);

    $template->addJSFiles([
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js' => []
    ]);

    $template->addJSScript('$(\'.datepicker\').datepicker();');
    $template->addJSFiles([
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/prism/prism.js' => [],
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/plugins/spoiler/js/spoiler.js' => [],
        (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/plugins/tinymce/tinymce.min.js' => []
    ]);

    $template->addJSScript(Input::createTinyEditor($language, 'inputSignature'));

    // Error/success message?
    if (Session::exists('settings_error')) {
        $error = Session::flash('settings_error');
    }
    if (Session::exists('settings_success')) {
        $success = Session::flash('settings_success');
    }

    // Get languages
    $languages = [];
    $language_query = $queries->getWhere('languages', ['id', '<>', 0]);

    foreach ($language_query as $item) {
        $languages[] = [
            'name' => Output::getClean($item->name),
            'active' => $user->data()->language_id == $item->id
        ];
    }

    // Get templates
    $templates = [];
    $templates_query = $user->getUserTemplates();

    foreach ($templates_query as $item) {
        $templates[] = [
            'id' => Output::getClean($item->id),
            'active' => $item->id === $user->data()->theme_id,
            'name' => Output::getClean($item->name)
        ];
    }

    // Get custom fields
    $custom_fields = $queries->getWhere('profile_fields', ['id', '<>', 0]);

    if ($user->hasPermission('usercp.nickname')) {
        $custom_fields_template['nickname'] = [
            'name' => $language->get('user', 'nickname'),
            'value' => Output::getClean($user->data()->nickname),
            'id' => 'nickname',
            'type' => 'text'
        ];
    } else {
        $custom_fields_template['nickname'] = [
            'nickname' => [
                'disabled' => true
            ]
        ];
    }

    foreach ($custom_fields as $field) {
        // Skip this field if it's not editable, and it is already set.
        // This fixes when a field is made after someone registers, the next time they edit their profile,
        // they will have to set it.
        if (!$field->editable  && $field->value !== null) {
            continue;
        }

        // Get field value for user
        $value = '';
        foreach ($user->getProfileFields(false) as $id => $item) {
            if ($id == $field->id) {
                // TODO: support HTML fields
                $value = $item['value'];
                break;
            }
        }

        // Get custom field type
        if ($field->type == 1) {
            $type = 'text';
        } else if ($field->type == 2) {
            $type = 'textarea';
        } else if ($field->type == 3) {
            $type = 'date';
        }

        $custom_fields_template[$field->name] = [
            'name' => Output::getClean($field->name),
            'value' => $value,
            'id' => $field->id,
            'type' => $type,
            'required' => $field->required,
            'description' => $field->description ?: $field->name
        ];
    }

    if (Session::exists('tfa_success')) {
        $success = Session::flash('tfa_success');
    }

    if (isset($errors) && count($errors)) {
        $smarty->assign([
            'ERRORS' => $errors,
            'ERRORS_TITLE' => $language->get('general', 'error')
        ]);
    }

    if ($user->hasPermission('usercp.signature')) {
        $signature = Output::getPurified($user->data()->signature);

        $smarty->assign([
            'SIGNATURE' => $language->get('user', 'signature'),
            'SIGNATURE_VALUE' => $signature
        ]);
    }

    if ($forum_enabled) {
        $smarty->assign([
            'TOPIC_UPDATES' => $language->get('user', 'topic_updates'),
            'TOPIC_UPDATES_ENABLED' => DB::getInstance()->get('users', ['id', '=', $user->data()->id])->first()->topic_updates
        ]);
    }

    if ($user->canPrivateProfile()) {
        $smarty->assign([
            'PRIVATE_PROFILE' => $language->get('user', 'private_profile'),
            'PRIVATE_PROFILE_ENABLED' => $user->isPrivateProfile()
        ]);
    }

    // Language values
    $smarty->assign([
        'SETTINGS' => $language->get('user', 'profile_settings'),
        'ACTIVE_LANGUAGE' => $language->get('user', 'active_language'),
        'LANGUAGES' => $languages,
        'ACTIVE_TEMPLATE' => $language->get('user', 'active_template'),
        'TEMPLATES' => $templates,
        'PROFILE_FIELDS' => $custom_fields_template,
        'SUBMIT' => $language->get('general', 'submit'),
        'TOKEN' => Token::get(),
        'ERROR' => ($error ?? false),
        'SUCCESS' => ($success ?? false),
        'CHANGE_PASSWORD' => $language->get('user', 'change_password'),
        'CURRENT_PASSWORD' => $language->get('user', 'current_password'),
        'NEW_PASSWORD' => $language->get('user', 'new_password'),
        'CONFIRM_NEW_PASSWORD' => $language->get('user', 'confirm_new_password'),
        'TWO_FACTOR_AUTH' => $language->get('user', 'two_factor_auth'),
        'TIMEZONE' => $language->get('user', 'timezone'),
        'TIMEZONES' => Util::listTimezones(),
        'SELECTED_TIMEZONE' => $user->data()->timezone,
        'CURRENT_EMAIL' => Output::getClean($user->data()->email),
        'CHANGE_EMAIL_ADDRESS' => $language->get('user', 'change_email_address'),
        'EMAIL_ADDRESS' => $language->get('user', 'email_address'),
        'SUCCESS_TITLE' => $language->get('general', 'success'),
        'ERROR_TITLE' => $language->get('general', 'error'),
        'HELP' => $language->get('general', 'help'),
        'INFO' => $language->get('general', 'info'),
        'ENABLED' => $language->get('user', 'enabled'),
        'DISABLED' => $language->get('user', 'disabled'),
        'GRAVATAR' => $language->get('user', 'gravatar'),
        'GRAVATAR_VALUE' => $user->data()->gravatar == '1' ? '1' : '0'
    ]);

    if (defined('CUSTOM_AVATARS')) {
        $smarty->assign([
            'CUSTOM_AVATARS' => true,
            'CUSTOM_AVATARS_SCRIPT' => ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'core/includes/image_upload.php',
            'BROWSE' => $language->get('general', 'browse'),
            'UPLOAD_NEW_PROFILE_IMAGE' => $language->get('user', 'upload_new_avatar')
        ]);
    }

    if ($user->data()->tfa_enabled == 1) {
        $smarty->assign('DISABLE', $language->get('user', 'disable'));
        foreach ($user->getGroups() as $group) {
            if ($group->force_tfa) {
                $forced = true;
                break;
            }
        }

        if (isset($forced) && $forced) {
            $smarty->assign('FORCED', true);
        } else {
            $smarty->assign('DISABLE_LINK', URL::build('/user/settings/', 'do=disable_tfa'));
        }
    } else {
        // Enable
        $smarty->assign('ENABLE', $language->get('user', 'enable'));
        $smarty->assign('ENABLE_LINK', URL::build('/user/settings/', 'do=enable_tfa'));
    }

    // Load modules + template
    Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

    require(ROOT_PATH . '/core/templates/cc_navbar.php');

    $template->onPageLoad();

    require(ROOT_PATH . '/core/templates/navbar.php');
    require(ROOT_PATH . '/core/templates/footer.php');

    // Display template
    $template->displayTemplate('user/settings.tpl', $smarty);
}
