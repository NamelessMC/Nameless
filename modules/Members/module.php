<?php
/*
 *  Made by Aberdeener
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.1.0
 *
 *  License: MIT
 *
 *  Members module file
 */

class Members_Module extends Module {

    private Language $_language;
    private Language $_member_language;

    public function __construct(Language $language, Language $member_language, Pages $pages) {
        $this->_language = $language;
        $this->_member_language = $member_language;

        $name = 'Members';
        $author = '<a href="https://tadhg.sh" target="_blank" rel="nofollow noopener">Aberdeener</a>';
        $module_version = '2.1.0';
        $nameless_version = '2.1.0';

        parent::__construct($this, $name, $author, $module_version, $nameless_version);

        // Define URLs which belong to this module
        $pages->add('Members', '/members', 'pages/members.php');
        $pages->add('Members', '/queries/member_list', 'queries/member_list.php');
        $pages->add('Members', '/panel/core/member_lists', 'pages/panel/member_lists.php');
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
            'admincp.members' => $this->_member_language->get('members', 'member_lists')
        ]);

        if (defined('FRONT_END')) {
            if (count(MemberList::getInstance()->allEnabledLists()) > 0) {
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
                $navs[0]->add('members', $this->_member_language->get('members', 'members'), URL::build('/members'), 'top', null, $members_order, $members_icon);
            }
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

                if (!$cache->isCached('members_icon')) {
                    $icon = '<i class="nav-icon fas fa-list"></i>';
                    $cache->store('members_icon', $icon);
                } else {
                    $icon = $cache->retrieve('members_icon');
                }

                $navs[2]->add('members_divider', mb_strtoupper($this->_member_language->get('members', 'members'), 'UTF-8'), 'divider', 'top', null, $order);
                $navs[2]->add('member_lists_settings', $this->_member_language->get('members', 'member_lists'), URL::build('/panel/core/member_lists'), 'top', null, $order + 0.1, $icon);
            }
        }
    }

    public function getDebugInfo(): array {
        return [];
    }
}
