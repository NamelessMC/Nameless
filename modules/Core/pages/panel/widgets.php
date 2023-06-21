<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  Panel widgets page
 */

if (!$user->handlePanelPageLoad('admincp.widgets')) {
    require_once(ROOT_PATH . '/403.php');
    die();
}

const PAGE = 'panel';
const PARENT_PAGE = 'layout';
const PANEL_PAGE = 'widgets';
$page_title = $language->get('admin', 'widgets');
require_once(ROOT_PATH . '/core/templates/backend_init.php');

// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

if (!isset($_GET['action'])) {
    $widgets_list = [];
    $profile_widgets_list = [];

    // List widgets
    /** @var AbstractWidget $widget */
    foreach ($widgets->getAll() as $widget) {
        $widget_id = DB::getInstance()->get('widgets', ['name', $widget->getName()])->first()->id;

        $entry = [
            'name' => Output::getClean($widget->getName()),
            'module' => $language->get('admin', 'module_x', ['module' => Output::getClean($widget->getModule())]),
            'description' => Output::getClean($widget->getDescription()),
            'order' => $widget->getOrder(),
            'enabled' => $widgets->isEnabled($widget),
            'disable_link' => (($widgets->isEnabled($widget)) ? URL::build('/panel/core/widgets/', 'action=disable&w=' . urlencode($widget_id)) : null),
            'enable_link' => ((!$widgets->isEnabled($widget)) ? URL::build('/panel/core/widgets/', 'action=enable&w=' . urlencode($widget_id)) : null),
            'settings_link' => (($widgets->isEnabled($widget)) ? URL::build('/panel/core/widgets/', 'action=edit&w=' . urlencode($widget_id)) : null),
        ];

        if ($widget instanceof ProfileWidgetBase) {
            $profile_widgets_list[] = $entry;
        } else {
            $widgets_list[] = $entry;
        }
    }

    // Sort widgets by order
    usort($widgets_list, static function ($a, $b) {
        return $a['order'] - $b['order'];
    });
    usort($profile_widgets_list, static function ($a, $b) {
        return $a['order'] - $b['order'];
    });

    $smarty->assign([
        'ENABLE' => $language->get('admin', 'enable'),
        'DISABLE' => $language->get('admin', 'disable'),
        'EDIT' => $language->get('general', 'edit'),
        'SITE_WIDGETS' => $language->get('admin', 'site_widgets'),
        'PROFILE_WIDGETS' => $language->get('admin', 'profile_widgets'),
        'WIDGETS_LIST' => $widgets_list,
        'PROFILE_WIDGETS_LIST' => $profile_widgets_list,
    ]);

    $template_file = 'core/widgets.tpl';
} else {
    if ($_GET['action'] == 'enable') {
        // Enable a widget
        if (!isset($_GET['w']) || !is_numeric($_GET['w'])) {
            die('Invalid widget!');
        }

        if (Token::check($_POST['token'])) {
            // Get widget name
            $name = DB::getInstance()->get('widgets', ['id', $_GET['w']])->results();

            if (count($name)) {
                $name = Output::getClean($name[0]->name);
                /** @var AbstractWidget|null $widget */
                $widget = $widgets->getWidget($name);

                if (!is_null($widget)) {
                    $widgets->enable($widget);

                    Session::flash('admin_widgets', $language->get('admin', 'widget_enabled'));
                }
            }

        } else {
            Session::flash('admin_widgets_error', $language->get('general', 'invalid_token'));
        }

        Redirect::to(URL::build('/panel/core/widgets'));
    }

    if ($_GET['action'] == 'disable') {
        // Disable a widget
        if (!isset($_GET['w']) || !is_numeric($_GET['w'])) {
            die('Invalid widget!');
        }

        if (Token::check($_POST['token'])) {
            // Get widget name
            $name = DB::getInstance()->get('widgets', ['id', $_GET['w']])->results();
            if (count($name)) {
                $name = Output::getClean($name[0]->name);
                $widget = $widgets->getWidget($name);

                if (!is_null($widget)) {
                    $widgets->disable($widget);

                    Session::flash('admin_widgets', $language->get('admin', 'widget_disabled'));
                }
            }

        } else {
            Session::flash('admin_widgets_error', $language->get('general', 'invalid_token'));
        }

        Redirect::to(URL::build('/panel/core/widgets'));
    }

    if ($_GET['action'] == 'edit') {
        // Ensure widget exists
        if (!isset($_GET['w']) || !is_numeric($_GET['w'])) {
            Redirect::to(URL::build('/panel/core/widgets'));
        }

        $widget = DB::getInstance()->get('widgets', ['id', $_GET['w']])->results();
        if (!count($widget)) {
            Redirect::to(URL::build('/panel/core/widgets'));
        }
        $widget = $widget[0];
        /** @var AbstractWidget $widget_instance */
        $widget_instance = $widgets->getWidget($widget->name);
        $is_profile_widget = $widget_instance instanceof ProfileWidgetBase;

        // Editing widget
        $active_pages = $widget_instance->getPages();
        $order = $widget_instance->getOrder();

        if (Input::exists()) {
            if (Token::check()) {
                try {
                    // Profile widgets cannot be on specific pages
                    if (!$is_profile_widget) {
                        // Updated pages list
                        if (isset($_POST['pages']) && count($_POST['pages'])) {
                            $active_pages = $_POST['pages'];
                        } else {
                            $active_pages = [];
                        }
                    }

                    $active_pages_string = json_encode($active_pages);
                    $order = ($_POST['order'] ?? 10);

                    $location = Input::get('location');
                    if (!in_array($location, ['left', 'right'])) {
                        $location = 'right';
                    }

                    DB::getInstance()->update('widgets', $widget->id, [
                        'pages' => $active_pages_string,
                        'location' => $location,
                        'order' => $order,
                    ]);
                    $widget_instance->clearCache();

                    Session::flash('admin_widgets', $language->get('admin', 'widget_updated'));
                    Redirect::to(URL::build('/panel/core/widgets/', 'action=edit&w=' . urlencode($widget->id)));
                } catch (Exception $e) {
                    $errors = [$e->getMessage()];
                }
            } else {
                $errors = [$language->get('general', 'invalid_token')];
            }
        }

        if ($widget_instance->getSettings() !== null) {
            $smarty->assign([
                'SETTINGS' => $language->get('admin', 'settings'),
                'SETTINGS_LINK' => URL::build('/panel/core/widgets/', 'action=settings&w=' . urlencode($widget->id)),
            ]);
        }

        $location = Output::getClean($widget_instance->getLocation());
        if (!in_array($location, ['left', 'right'])) {
            $location = 'right';
        }

        $smarty->assign([
            'EDITING_WIDGET' => $language->get('admin', 'editing_widget_x', [
                'widget' => Text::bold(Output::getClean($widget->name)),
            ]),
            'BACK' => $language->get('general', 'back'),
            'BACK_LINK' => URL::build('/panel/core/widgets'),
            'IS_PROFILE_WIDGET' => $is_profile_widget,
            'ORDER' => $order,
            'WIDGET_ORDER' => $language->get('admin', 'widget_order'),
            'LOCATION' => $location,
            'WIDGET_LOCATION' => $language->get('admin', 'widget_location'),
            'LEFT' => $language->get('admin', 'left'),
            'RIGHT' => $language->get('admin', 'right'),
            'ACTIVE_PAGES' => $active_pages,
            'POSSIBLE_PAGES' => $pages->returnWidgetPages(),
            'MODULE' => $language->get('admin', 'module'),
            'MODULE_SEPERATOR' => '&raquo',
        ]);

        $template_file = 'core/widgets_edit.tpl';
    } else {
        if ($_GET['action'] === 'settings') {
            // Ensure widget exists
            if (!isset($_GET['w']) || !is_numeric($_GET['w'])) {
                Redirect::to(URL::build('/panel/core/widgets'));
            }

            $widget = DB::getInstance()->get('widgets', ['id', $_GET['w']])->results();
            if (!count($widget)) {
                Redirect::to(URL::build('/panel/core/widgets'));
            }
            $widget = $widget[0];
            $widget_instance = $widgets->getWidget($widget->name);

            if ($widget_instance->getSettings() === null || !file_exists($widget_instance->getSettings())) {
                Redirect::to(URL::build('/admin/widgets'));
            }

            require_once($widget_instance->getSettings());

            $smarty->assign([
                'EDITING_WIDGET' => $language->get('admin', 'editing_widget_x', [
                    'widget' => Text::bold(Output::getClean($widget->name)),
                ]),
                'BACK' => $language->get('general', 'back'),
                'BACK_LINK' => URL::build('/panel/core/widgets/', 'action=edit&w=' . urlencode($widget->id)),
            ]);

            $template_file = 'core/widget_settings.tpl';
        } else {
            Redirect::to('/panel/core/widgets');
        }
    }
}

if (Session::exists('admin_widgets')) {
    $success = Session::flash('admin_widgets');
}

if (Session::exists('admin_widgets_error')) {
    $errors = [Session::flash('admin_widgets_error')];
}

if (isset($success)) {
    $smarty->assign([
        'SUCCESS' => $success,
        'SUCCESS_TITLE' => $language->get('general', 'success'),
    ]);
}

if (isset($errors) && count($errors)) {
    $smarty->assign([
        'ERRORS' => $errors,
        'ERRORS_TITLE' => $language->get('general', 'error'),
    ]);
}

$smarty->assign([
    'PARENT_PAGE' => PARENT_PAGE,
    'DASHBOARD' => $language->get('admin', 'dashboard'),
    'LAYOUT' => $language->get('admin', 'layout'),
    'WIDGETS' => $language->get('admin', 'widgets'),
    'PAGE' => PANEL_PAGE,
    'TOKEN' => Token::get(),
    'SUBMIT' => $language->get('general', 'submit'),
]);

$template->onPageLoad();

require(ROOT_PATH . '/core/templates/panel_navbar.php');

// Display template
$template->displayTemplate($template_file, $smarty);
