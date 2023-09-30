<?php
/*
 *  Made by Aberdeener
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.1.2
 *
 *  License: MIT
 *
 *  Members module file
 */

class Members_Module extends Module {

    private Language $_language;
    private Language $_members_language;

    public function __construct(Language $language, Language $members_language, Pages $pages) {
        $this->_language = $language;
        $this->_members_language = $members_language;

        $name = 'Members';
        $author = '<a href="https://tadhg.sh" target="_blank" rel="nofollow noopener">Aberdeener</a>';
        $module_version = '2.1.2';
        $nameless_version = '2.1.2';

        parent::__construct($this, $name, $author, $module_version, $nameless_version);

        // Define URLs which belong to this module
        $pages->add('Members', '/members', 'pages/members.php');
        $pages->add('Members', '/queries/member_list', 'queries/member_list.php');
        $pages->add('Members', '/panel/members/member_lists', 'pages/panel/member_lists.php');
        $pages->add('Members', '/panel/members/settings', 'pages/panel/settings.php');
    }

    public function onInstall() {
        // Not necessary for CookieConsent
    }

    public function onUninstall() {
        // Not necessary for CookieConsent
    }

    public function onEnable() {
        // Not necessary for CookieConsent
    }

    public function onDisable() {
        // Not necessary for CookieConsent
    }

    public function onPageLoad(User $user, Pages $pages, Cache $cache, Smarty $smarty, $navs, Widgets $widgets, ?TemplateBase $template) {
        $language = $this->_language;

        // AdminCP
        PermissionHandler::registerPermissions($language->get('moderator', 'staff_cp'), [
            'admincp.members' => $this->_members_language->get('members', 'member_lists')
        ]);

        $cache->setCache('navbar_order');
        if (!$cache->isCached('members_order')) {
            $members_order = 5;
            $cache->store('members_order', 5);
        } else {
            $members_order = $cache->retrieve('members_order');
        }

        $cache->setCache('navbar_icons');
        if (!$cache->isCached('members_icon')) {
            $members_icon = '';
        } else {
            $members_icon = $cache->retrieve('members_icon');
        }

        $cache->setCache('nav_location');
        if (!$cache->isCached('members_location')) {
            $link_location = 1;
            $cache->store('members_location', 1);
        } else {
            $link_location = $cache->retrieve('members_location');
        }

        switch ($link_location) {
            case 1:
                // Navbar
                $navs[0]->add('members', $this->_members_language->get('members', 'members'), URL::build('/members'), 'top', null, $members_order, $members_icon);
                break;
            case 2:
                // "More" dropdown
                $navs[0]->addItemToDropdown('more_dropdown', 'members', $this->_members_language->get('members', 'members'), URL::build('/members'), 'top', null, $members_icon, $members_order);
                break;
            case 3:
                // Footer
                $navs[0]->add('members', $this->_members_language->get('members', 'members'), URL::build('/members'), 'footer', null, $members_order, $members_icon);
                break;
        }

        if (defined('BACK_END')) {
            $cache->setCache('panel_sidebar');

            // StaffCP link
            if ($user->hasPermission('admincp.members')) {
                if (!$cache->isCached('members_order')) {
                    $order = 13;
                    $cache->store('members_order', 13);
                } else {
                    $order = $cache->retrieve('members_order');
                }

                if (!$cache->isCached('members_settings_icon')) {
                    $members_settings_icon = '<i class="nav-icon fas fa-cogs"></i>';
                    $cache->store('members_settings_icon', $members_settings_icon);
                } else {
                    $members_settings_icon = $cache->retrieve('members_settings_icon');
                }

                if (!$cache->isCached('member_lists_icon')) {
                    $member_lists_icon = '<i class="nav-icon fas fa-list"></i>';
                    $cache->store('member_lists_icon', $member_lists_icon);
                } else {
                    $member_lists_icon = $cache->retrieve('member_lists_icon');
                }

                $navs[2]->add('members_divider', mb_strtoupper($this->_members_language->get('members', 'members'), 'UTF-8'), 'divider', 'top', null, $order);
                $navs[2]->add('members_settings', $this->_language->get('admin', 'settings'), URL::build('/panel/members/settings'), 'top', null, $order + 0.1, $members_settings_icon);
                $navs[2]->add('member_lists_settings', $this->_members_language->get('members', 'member_lists'), URL::build('/panel/members/member_lists'), 'top', null, $order + 0.2, $member_lists_icon);
            }
        }
    }

    public function getDebugInfo(): array {
        return [];
    }
}
