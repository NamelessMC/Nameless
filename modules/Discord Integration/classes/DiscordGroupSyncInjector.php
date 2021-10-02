<?php

class DiscordGroupSyncInjector implements GroupSyncInjector
{

    public function getModule()
    {
        return 'Discord Integration';
    }

    public function getName()
    {
        return 'Discord role';
    }

    public function getColumnName()
    {
        return 'discord_role_id';
    }

    public function getColumnType()
    {
        return 'BIGINT';
    }

    public function shouldEnable()
    {
        return Discord::isBotSetup();
    }

    public function getNotEnabledMessage(Language $language)
    {
        return Discord::getLanguageTerm('discord_integration_not_setup');
    }

    public function getSelectionOptions()
    {
        $roles = [];

        foreach (Discord::getRoles() as $role) {
            $roles[] = [
                'id' => $role->id,
                'name' => Output::getClean($role->name),
            ];
        }

        return $roles;
    }

    public function getValidationRules()
    {
        return [
            Validate::MIN => 18,
            Validate::MAX => 18,
            Validate::NUMERIC => true
        ];
    }

    public function getValidationMessages(Language $language)
    {
        return [
            Validate::MIN => Discord::getLanguageTerm('discord_role_id_length'),
            Validate::MAX => Discord::getLanguageTerm('discord_role_id_length'),
            Validate::NUMERIC => Discord::getLanguageTerm('discord_role_id_numeric'),
        ];
    }

    public function addGroup(User $user, $group_id)
    {
        return Discord::updateDiscordRoles($user, [$group_id], [], false);
    }

    public function removeGroup(User $user, $group_id)
    {
        return Discord::updateDiscordRoles($user, [], [$group_id], false);
    }
}
