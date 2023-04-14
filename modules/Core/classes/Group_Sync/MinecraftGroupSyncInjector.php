<?php
/**
 * Minecraft group sync injector implementation.
 *
 * @package Modules\Core\GroupSync
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
class MinecraftGroupSyncInjector implements GroupSyncInjector {

    public function getModule(): string {
        return 'Core';
    }

    public function getName(): string {
        return 'Minecraft rank';
    }

    public function getColumnName(): string {
        return 'ingame_rank_name';
    }

    public function getColumnType(): string {
        return 'VARCHAR(64)';
    }

    public function shouldEnable(): bool {
        return count($this->getSelectionOptions()) > 0;
    }

    public function getSelectionOptions(): array {
        $row = DB::getInstance()->query(
            'SELECT `groups` FROM `nl2_query_results` WHERE `server_id` = ? ORDER BY `id` DESC LIMIT 1',
            [Util::getSetting('group_sync_mc_server')]
        )->first();

        if ($row === null) {
            // Plugin is not set up and/or they did not select a server to source groups from/default server
            return [];
        }

        $groups = json_decode($row->groups, true);

        $cleaned_groups = [];

        foreach ($groups as $group) {
            $cleaned_groups[] = [
                'id' => Output::getClean($group),
                'name' => Output::getClean($group),
            ];
        }

        return $cleaned_groups;
    }

    public function getNotEnabledMessage(Language $language): string {
        return $language->get('admin', 'group_sync_plugin_not_set_up');
    }

    public function getValidationRules(): array {
        return [
            Validate::MIN => 2,
            Validate::MAX => 64,
        ];
    }

    public function getValidationMessages(Language $language): array {
        return [
            Validate::MIN => $language->get('admin', 'group_name_minimum'),
            Validate::MAX => $language->get('admin', 'ingame_group_maximum')
        ];
    }

    public function addGroup(User $user, $group_id): bool {
        // Nothing to do here, changes will get picked up by plugin
        return true;
    }

    public function removeGroup(User $user, $group_id): bool {
        // Nothing to do here, changes will get picked up by plugin
        return true;
    }
}
