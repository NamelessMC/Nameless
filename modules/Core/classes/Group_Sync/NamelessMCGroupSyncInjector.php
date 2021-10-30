<?php

class NamelessMCGroupSyncInjector implements GroupSyncInjector
{

    public function getModule(): string {
        return 'Core';
    }

    public function getName(): string {
        return 'Website group';
    }

    public function getColumnName(): string {
        return 'website_group_id';
    }

    public function getColumnType(): string {
        return 'INT';
    }

    public function shouldEnable(): bool {
        return true;
    }

    public function getNotEnabledMessage(Language $language): string {
        throw new Exception(self::class . ' should always be enabled.');
    }

    public function getSelectionOptions(): array {
        $groups_query = DB::getInstance()->get('groups', ['id', '<>', 0])->results();
        $groups = [];

        foreach ($groups_query as $group) {
            $groups[] = [
                'id' => Output::getClean($group->id),
                'name' => Output::getClean($group->name)
            ];
        }

        return $groups;
    }

    public function getValidationRules(): array {
        return [
            Validate::REQUIRED => true,
        ];
    }

    public function getValidationMessages(Language $language): array {
        return [
            Validate::REQUIRED => $language->get('general', 'Error')
        ];
    }

    public function addGroup(User $user, $group_id): bool {
        return $user->addGroup($group_id);
    }

    public function removeGroup(User $user, $group_id): bool {
        return $user->removeGroup($group_id);
    }
}
