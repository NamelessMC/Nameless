<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Navbar generation
 */

// User area - DEPRECATED, will be removed at some point
$user_area = array();
$user_area_left = array();

if ($user->isLoggedIn()) {
    /*
	 *  Start deprecated variable generation
	 */
    $user_area['usercp'] = array(
        'target' => '',
        'link' => URL::build('/user'),
        'title' => $language->get('user', 'user_cp_icon')
    );
    if (defined('PAGE') && PAGE == 'usercp') {
        $user_area['usercp']['active'] = true;
    }

    $user_area_left['account'] = array(
        'target' => '',
        'link' => '',
        'title' => $user->getDisplayname(),
        'items' => array(
            'profile' => array(
                'link' => $user->getProfileURL(),
                'target' => '',
                'title' => $language->get('user', 'profile')
            ),
            'separator1' => array(
                'separator' => true
            ),
            'user' => array(
                'link' => URL::build('/user'),
                'target' => '',
                'title' => $language->get('user', 'user_cp')
            )
        )
    );

    if ($user->canViewStaffCP()) {
        $user_area_left['account']['items']['panel'] = array(
            'link' => URL::build('/panel'),
            'target' => '',
            'title' => $language->get('moderator', 'staff_cp')
        );
    }

    $user_area_left['account']['items']['separator2'] = array(
        'separator' => true
    );

    $user_area_left['account']['items']['logout'] = array(
        'link' => URL::build('/logout'),
        'target' => '',
        'title' => $language->get('general', 'log_out')
    );

    /*
	 *  End deprecated variable generation
	 */

    $user_section = array(
        'pms' => array(
            'title' => $language->get('user', 'messages'),
            'icon' => '<i class="fas fa-envelope icon"></i>',
            'link' =>  URL::build('/user/messaging'),
            'meta' => $language->get('user', 'view_messages'),
            'target' => '',
            'items' => array(
                'loading' => array(
                    'title' => $language->get('general', 'loading'),
                    'link' => '',
                    'target' => '',
                ),
            ),
        ),
        'alerts' => array(
            'title' => $language->get('user', 'alerts'),
            'icon' => '<i class="fas fa-flag icon"></i>',
            'link' =>  URL::build('/user/alerts'),
            'meta' => $language->get('user', 'view_alerts'),
            'target' => '',
            'items' => array(
                'loading' => array(
                    'title' => $language->get('general', 'loading'),
                    'link' => '',
                    'target' => '',
                ),
            ),
        ),
    );

    if ($user->canViewStaffCP()) {
        $user_section['panel'] = array(
            'title' => $language->get('moderator', 'staff_cp'),
            'icon' => '<i class="fas fa-cogs icon"></i>',
            'link' => URL::build('/panel'),
            'meta' => '',
            'target' => '',
        );
    }

    $user_section['account'] = array(
        'title' => $user->getDisplayname(),
        'icon' => '<img src="' . $user->getAvatar() . '">',
        'link' => '',
        'meta' => '',
        'target' => '',
        'items' => array(
            'profile' => array(
                'title' => $language->get('user', 'profile'),
                'link' => $user->getProfileURL(),
                'target' => '',
            ),
            'user' => array(
                'title' => $language->get('user', 'user_cp'),
                'link' => URL::build('/user'),
                'target' => '',
            ),
            'separator_1' => array(
                'separator' => true
            ),
            'logout' => array(
                'title' => $language->get('general', 'log_out'),
                'link' => URL::build('/logout'),
                'target' => '',
            ),
        ),
    );
} else {
    $user_area_left['account'] = array(
        'target' => '',
        'link' => '',
        'title' => $language->get('user', 'guest'),
        'items' => array(
            'login' => array(
                'link' => URL::build('/login'),
                'target' => '',
                'title' => $language->get('general', 'sign_in')
            ),
            'register' => array(
                'link' => URL::build('/register'),
                'target' => '',
                'title' => $language->get('general', 'register')
            )
        )
    );

    $user_section = array(
        'login' => array(
            'title' => $language->get('general', 'sign_in'),
            'icon' => '<i class="fas fa-key icon"></i>',
            'link' => URL::build('/login'),
            'meta' => '',
            'target' => '',
        ),
        'register' => array(
            'title' => $language->get('general', 'register'),
            'icon' => '<i class="fas fa-clipboard icon"></i>',
            'link' => URL::build('/register'),
            'meta' => '',
            'target' => '',
        )
    );
}

// Assign to Smarty variables
$smarty->assign(array(
    'NAVBAR_INVERSE' => '',
    'SITE_NAME' => SITE_NAME,
    'NAV_LINKS' => $navigation->returnNav('top'),
    'USER_AREA' => $user_area,
    'USER_DROPDOWN' => $user_area_left,
    'USER_SECTION' => $user_section,
    'ANNOUNCEMENTS' => $announcements->getAvailable(PAGE, defined('CUSTOM_PAGE') ? CUSTOM_PAGE : null, !$user->isLoggedIn() ? array(0) : $user->getAllGroupIds())
));

if ($user->isLoggedIn()) {
    // Get unread alerts and messages
    $smarty->assign(array(
        'ALERTS_LINK' => URL::build('/user/alerts'),
        'VIEW_ALERTS' => $language->get('user', 'view_alerts'),
        'MESSAGING_LINK' => URL::build('/user/messaging'),
        'VIEW_MESSAGES' => $language->get('user', 'view_messages'),
        'LOADING' => $language->get('general', 'loading'),
        'MESSAGING' => $language->get('user', 'messaging'),
        'ALERTS' => $language->get('user', 'alerts'),
    ));
}