<?php

class MemberList extends Instanceable {

    private array $_lists = [];

    /**
     * @var Closure[]
     */
    private array $_metadata_providers = [];

    public function registerListProvider(MemberListProvider $provider): void {
        $this->_lists[$provider->getName()] = $provider;
    }

    public function registerMemberMetadataProvider(Closure $provider): void {
        $this->_metadata_providers[] = $provider;
    }

    /**
     * @return MemberListProvider[]
     */
    public function allLists(): array {
        return $this->_lists;
    }

    /**
     * @return MemberListProvider[]
     */
    public function allEnabledLists(): array {
        $lists = [];
        foreach ($this->allLists() as $list) {
            if ($list->isEnabled()) {
                $lists[] = $list;
            }
        }

        return $lists;
    }

    public function listExists(string $name): bool {
        return isset($this->_lists[$name]);
    }

    public function getList(string $name): MemberListProvider {
        if (!$this->listExists($name)) {
            throw new RuntimeException("Provider '$name' does not exist");
        }

        return $this->_lists[$name];
    }

    public function getMembersInGroup(int $group_id): array {
        return MemberListProvider::parseMembers(
            array_map(
                static fn ($row) => new User($row->id),
                DB::getInstance()->query(
                    'SELECT DISTINCT(nl2_users.id) AS id FROM nl2_users LEFT JOIN nl2_users_groups ON nl2_users.id = nl2_users_groups.user_id WHERE group_id = ? ',
                    [$group_id]
                )->results()
            ),
        );
    }

    public function getMemberMetadata(User $user): array {
        $metadata = [];
        foreach ($this->_metadata_providers as $provider) {
            $metadata += $provider($user);
        }

        return $metadata;
    }
}
