<?php

class MinecraftGroupSyncInjector implements GroupSyncInjector
{

    public function getModule()
    {
        return 'Core';
    }

    public function getName()
    {
        return 'Minecraft rank';
    }

    public function getColumnName()
    {
        return 'ingame_rank_name';
    }

    public function getColumnType()
    {
        return 'VARCHAR(64)';
    }

    public function shouldEnable()
    {
        return count($this->getSelectionOptions()) > 0;
    }

    public function getNotEnabledMessage(Language $language)
    {
        return $language->get('admin', 'group_sync_plugin_not_set_up');
    }

    public function getSelectionOptions()
    {
        $groups_query = json_decode(
            DB::getInstance()->query("SELECT `groups` FROM `nl2_query_results` ORDER BY `id` DESC LIMIT 1")->first()->groups,
            true
        );

        $groups = [];

        if ($groups_query == null) {
            return $groups;
        }

        foreach ($groups_query as $group) {
            $groups[] = [
                'id' => Output::getClean($group),
                'name' => Output::getClean($group),
            ];
        }

        return $groups;
    }

    public function getValidationRules()
    {
        return [
            Validate::MIN => 2,
            Validate::MAX => 64,
        ];
    }

    public function getValidationMessages(Language $language)
    {
        return [
            Validate::MIN => $language->get('admin', 'group_name_minimum'),
            Validate::MAX => $language->get('admin', 'ingame_group_maximum')
        ];
    }

    public function addGroup(User $user, $group_id)
    {
        // Nothing to do here, changes will get picked up by plugin
        return true;
    }

    public function removeGroup(User $user, $group_id)
    {
        // Nothing to do here, changes will get picked up by plugin
        return true;
    }
}
