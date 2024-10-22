<?php

namespace NamelessMC\Members\Pages\Panel;

use NamelessMC\Members\MemberListManager;
use NamelessMC\Framework\Pages\PanelPage;

class Index extends PanelPage {

    private \Smarty $smarty;
    private \Language $coreLanguage;
    private \Language $membersLanguage;
    private MemberListManager $memberListManager;

    public function __construct(
        \Smarty $smarty,
        \Language $coreLanguage,
        \Language $membersLanguage,
        MemberListManager $memberListManager,
    ) {
        $this->smarty = $smarty;
        $this->coreLanguage = $coreLanguage;
        $this->membersLanguage = $membersLanguage;
        $this->memberListManager = $memberListManager;
    }

    public function permission(): string {
        return 'admincp.members';
    }

    public function viewFile(): string {
        return 'members/index.tpl';
    }

    public function pageName(): string {
        return 'member_lists';
    }

    public function render() {
        if (\Input::exists()) {
            if (\Token::check()) {
                $list = $this->memberListManager->getList($_POST['list']);
                $enabled = \DB::getInstance()->get('member_lists', ['name', $list->getName()])->first()->enabled;
                \DB::getInstance()->update('member_lists', ['name', $list->getName()], [
                    'enabled' => !$enabled
                ]);

                \Session::flash('admin_member_lists_success', $this->membersLanguage->get('members', !$enabled ? 'member_list_toggled_enabled' : 'member_list_toggled_disabled', [
                    'list' => $list->getFriendlyName(),
                ]));

                \Redirect::to(\URL::build('/panel/members'));
            } else {
                \Session::flash('admin_member_lists_error', $this->coreLanguage->get('general', 'invalid_token'));
            }
        }

        if (\Session::exists('admin_member_lists_error')) {
            $this->smarty->assign([
                'ERRORS' => [\Session::flash('admin_member_lists_error')],
                'ERRORS_TITLE' => $this->coreLanguage->get('general', 'error'),
            ]);
        }

        if (\Session::exists('admin_member_lists_success')) {
            $this->smarty->assign([
                'SUCCESS' => \Session::flash('admin_member_lists_success'),
                'SUCCESS_TITLE' => $this->coreLanguage->get('general', 'success'),
            ]);
        }

        $this->smarty->assign([
            'DASHBOARD' => $this->coreLanguage->get('admin', 'dashboard'),
            'MEMBERS' => $this->membersLanguage->get('members', 'members'),
            'MEMBER_LISTS' => $this->membersLanguage->get('members', 'member_lists'),
            'MEMBER_LISTS_VALUES' => $this->memberListManager->allLists(),
            'NAME' => $this->coreLanguage->get('admin', 'name'),
            'ENABLED' => $this->coreLanguage->get('admin', 'enabled'),
            'MODULE' => $this->coreLanguage->get('admin', 'module'),
            'EDIT' => $this->coreLanguage->get('general', 'edit'),
            'ENABLE' => $this->coreLanguage->get('admin', 'enable'),
            'DISABLE' => $this->coreLanguage->get('admin', 'disable'),
            'TOKEN' => \Token::get(),
        ]);
    }
}
