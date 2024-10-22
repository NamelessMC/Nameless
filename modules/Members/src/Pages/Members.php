<?php

namespace NamelessMC\Members\Pages;

use NamelessMC\Framework\Pages\FrontendPage;
use NamelessMC\Members\MemberListManager;
use NamelessMC\Members\MemberListProvider;
use \Smarty;
use \Language;
use \Cache;
use \User;
use \Group;
use \Redirect;
use \URL;
use \Output;
use \Settings;
use \Paginator;

class Members extends FrontendPage {

    private \User $user;
    private \Smarty $smarty;
    private \Language $coreLanguage;
    private \Language $membersLanguage;
    private \Cache $cache;
    private MemberListManager $memberListManager;
    private array $templatePagination;

    public function __construct(
        \User $user,
        \Smarty $smarty,
        \Language $coreLanguage,
        \Language $membersLanguage,
        \Cache $cache,
        MemberListManager $memberListManager,
        $templatePagination,
    ) {
        $this->user = $user;
        $this->smarty = $smarty;
        $this->coreLanguage = $coreLanguage;
        $this->membersLanguage = $membersLanguage;
        $this->cache = $cache;
        $this->memberListManager = $memberListManager;
        $this->templatePagination = $templatePagination;
        $this->templatePagination['div'] = $this->templatePagination['div'] .= ' centered';

        $this->cache->setCache('member_lists');
    }

    public function pageName(): string {
        return 'members';
    }

    public function viewFile(): string {
        return 'members/members.tpl';
    }

    public function render() {
        if (isset($_GET['group'])) {
            if (!in_array($_GET['group'], json_decode(\Settings::get('member_list_viewable_groups', '{}', 'Members'), true))) {
                \Redirect::to(\URL::build('/members'));
            }

            $viewing_list = 'group';
            $viewing_group = \Group::find($_GET['group']);
            $this->smarty->assign([
                'VIEWING_GROUP' => [
                    'id' => $viewing_group->id,
                    'name' => \Output::getClean($viewing_group->name),
                ],
            ]);

            $lists_viewing = [];
        } else {
            $viewing_list = $_GET['list'] ?? 'overview';
            if ($viewing_list !== 'overview'
                && (!$this->memberListManager->listExists($viewing_list) || !$this->memberListManager->getList($viewing_list)->isEnabled())
            ) {
                \Redirect::to(\URL::build('/members'));
            }

            $lists_viewing = $viewing_list === 'overview'
                ? array_filter($this->memberListManager->allEnabledLists(), static fn (MemberListProvider $list) => $list->displayOnOverview())
                : [$this->memberListManager->getList($viewing_list)];
        }

        $new_members = [];
        if (\Settings::get('member_list_hide_banned', false, 'Members')) {
            $cacheKey = 'new_members_banned';
        } else {
            $cacheKey = 'new_members';
        }
        foreach ($this->cache->retrieve($cacheKey) as $new_member_id) {
            $new_members[] = new \User($new_member_id);
        }

        if (isset($error)) {
            $this->smarty->assign([
                'ERROR_TITLE' => $this->coreLanguage->get('general', 'error'),
                'ERROR' => $error,
            ]);
        }

        $groups = [];
        foreach (json_decode(\Settings::get('member_list_viewable_groups', '{}', 'Members'), true) as $group_id) {
            $group = \Group::find($group_id);
            if (!$group) {
                continue;
            }
            $groups[] = [
                'id' => $group->id,
                'name' => \Output::getClean($group->name),
            ];
        }

        if ($viewing_list !== 'overview') {
            $member_count = isset($_GET['group'])
                ? $this->memberListManager->getList($_GET['group'], true)->getMemberCount()
                : $this->memberListManager->getList($viewing_list)->getMemberCount();
            $url_param = isset($_GET['group'])
                ? 'group=' . $_GET['group']
                : 'list=' . $viewing_list;

            $paginator = new \Paginator(
                $this->templatePagination,
            );
            $paginator->setValues($member_count, 20, $_GET['p'] ?? 1);
            $this->smarty->assign([
                'PAGINATION' => $paginator->generate(6, \URL::build('/members/', $url_param)),
            ]);
        }

        // Sort sidebar lists to have displayOnOverview lists first
        $sidebar_lists = $this->memberListManager->allEnabledLists();
        usort($sidebar_lists, static function (MemberListProvider $a, MemberListProvider $b) {
            return $b->displayOnOverview() - $a->displayOnOverview();
        });

        $this->smarty->assign([
            'MEMBERS' => $this->membersLanguage->get('members', 'members'),
            'SIDEBAR_MEMBER_LISTS' => $sidebar_lists,
            'MEMBER_LISTS_VIEWING' => $lists_viewing,
            'VIEWING_LIST' => $viewing_list,
            'MEMBER_LIST_URL' => \URL::build('/members'),
            'QUERIES_URL' => \URL::build('/queries/members/member_list', 'list={{list}}&page={{page}}' . ($viewing_list === 'overview' ? '&overview=true' : '')),
            'OVERVIEW' => $this->coreLanguage->get('user', 'overview'),
            'VIEW_ALL' => $this->membersLanguage->get('members', 'view_all'),
            'GROUPS' => $groups,
            'VIEW_GROUP_URL' => \URL::build('/members', 'group='),
            'NEW_MEMBERS' => $this->membersLanguage->get('members', 'new_members'),
            'NEW_MEMBERS_VALUE' => $new_members,
            'FIND_MEMBER' => $this->membersLanguage->get('members', 'find_member'),
            'NAME' => $this->membersLanguage->get('members', 'name'),
            'SEARCH_URL' => \URL::build('/queries/users'),
            'NO_RESULTS_HEADER' => $this->membersLanguage->get('members', 'no_results_header'),
            'NO_RESULTS_TEXT' => $this->membersLanguage->get('members', 'no_results_text'),
            'VIEW_GROUP' => $this->membersLanguage->get('members', 'view_group'),
            'GROUP' => $this->membersLanguage->get('members', 'group'),
            'NO_MEMBERS_FOUND' => $this->membersLanguage->get('members', 'no_members'),
            'NO_OVERVIEW_LISTS_ENABLED' => $this->membersLanguage->get('members', 'no_overview_lists_enabled'),
        ]);
    }
}
