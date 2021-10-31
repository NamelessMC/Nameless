<?php

class MinecraftGroupSyncInjector implements GroupSyncInjector
{

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

    public function getNotEnabledMessage(Language $language): string {
        return $language->get('admin', 'group_sync_plugin_not_set_up');
    }

    public function getSelectionOptions(): array {
        $groups_query = json_decode(
            DB::getInstance()->selectQuery('SELECT `groups` FROM `nl2_query_results` ORDER BY `id` DESC LIMIT 1')->first()->groups,
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
