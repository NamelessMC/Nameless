<?php

class NamelessMCGroupSyncInjector implements GroupSyncInjector
{

    public function getModule()
    {
        return 'Core';
    }

    public function getName()
    {
        return 'Website group';
    }

    public function getColumnName()
    {
        return 'website_group_id';
    }

    public function getColumnType()
    {
        return 'INT';
    }

    public function shouldEnable()
    {
        return true;
    }

    public function getNotEnabledMessage(Language $language)
    {
        throw new Exception(self::class . ' should always be enabled.');
    }

    public function getSelectionOptions()
    {
        $groups_query = DB::getInstance()->get('groups', array('id', '<>', 0))->results();
        $groups = [];

        foreach ($groups_query as $group) {
            $groups[] = [
                'id' => Output::getClean($group->id),
                'name' => Output::getClean($group->name)
            ];
        }

        return $groups;
    }

    public function getValidationRules()
    {
        return [
            Validate::REQUIRED => true,
        ];
    }

    public function getValidationMessages(Language $language)
    {
        return [
            Validate::REQUIRED => $language->get('general', 'Error')
        ];
    }

    public function addGroup(User $user, $group_id)
    {
        return $user->addGroup($group_id);
    }

    public function removeGroup(User $user, $group_id)
    {
        return $user->removeGroup($group_id);
    }
}
