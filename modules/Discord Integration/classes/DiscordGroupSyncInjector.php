<?php
/**
 * Discord group sync injector implementation.
 *
 * @package Modules\Discord Integration
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
class DiscordGroupSyncInjector implements GroupSyncInjector {

    public function getModule(): string {
        return 'Discord Integration';
    }

    public function getName(): string {
        return 'Discord role';
    }

    public function getColumnName(): string {
        return 'discord_role_id';
    }

    public function getColumnType(): string {
        return 'BIGINT';
    }

    public function shouldEnable(): bool {
        return Discord::isBotSetup();
    }

    public function getNotEnabledMessage(Language $language): string {
        return Discord::getLanguageTerm('discord_integration_not_setup');
    }

    public function getSelectionOptions(): array {
        $roles = [];

        foreach (Discord::getRoles() as $role) {
            $roles[] = [
                'id' => $role['id'],
                'name' => Output::getClean($role['name']),
            ];
        }

        return $roles;
    }

    public function getValidationRules(): array {
        return [
            Validate::MIN => 18,
            Validate::MAX => 18,
            Validate::NUMERIC => true
        ];
    }

    public function getValidationMessages(Language $language): array {
        return [
            Validate::MIN => Discord::getLanguageTerm('discord_role_id_length'),
            Validate::MAX => Discord::getLanguageTerm('discord_role_id_length'),
            Validate::NUMERIC => Discord::getLanguageTerm('discord_role_id_numeric'),
        ];
    }

    public function addGroup(User $user, $group_id): bool {
        return Discord::updateDiscordRoles($user, [$group_id], []) === true;
    }

    public function removeGroup(User $user, $group_id): bool {
        return Discord::updateDiscordRoles($user, [], [$group_id]) === true;
    }
}
