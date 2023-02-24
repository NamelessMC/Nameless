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

    public function getList(string $name, bool $for_group = false): MemberListProvider {
        if (!$this->listExists($name)) {
            if (!$for_group) {
                throw new RuntimeException("Member list '$name' does not exist");
            }

            return (new GroupMemberListProvider())->forGroup((int) $name);
        }

        return $this->_lists[$name];
    }

    public function getMemberMetadata(User $user): array {
        $metadata = [];
        foreach ($this->_metadata_providers as $provider) {
            $metadata += $provider($user);
        }

        return $metadata;
    }
}
