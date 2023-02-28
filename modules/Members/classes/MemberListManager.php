<?php

/**
 * Member list providers manager. Provides a way to register and retrieve member list providers.
 * @see MemberListProvider
 *
 * @package Modules\Members
 * @author Aberdener
 * @version 2.1.0
 * @license MIT
 */
class MemberListManager extends Instanceable {

    private array $_lists = [];

    /**
     * @var Closure[]
     */
    private array $_metadata_providers = [];

    /**
     * Register a member list provider.
     *
     * @param MemberListProvider $provider The member list provider to register
     */
    public function registerListProvider(MemberListProvider $provider): void {
        $this->_lists[$provider->getName()] = $provider;
    }

    /**
     * Register a member metadata provider.
     * Member metadata providers are used to add additional information to member lists under each member's name.
     *
     * @param Closure $provider The member metadata provider to register
     */
    public function registerMemberMetadataProvider(Closure $provider): void {
        $this->_metadata_providers[] = $provider;
    }

    /**
     * Return all lists, including disabled ones.
     *
     * @return MemberListProvider[] All lists
     */
    public function allLists(): array {
        return $this->_lists;
    }

    /**
     * Return all enabled lists.
     *
     * @return MemberListProvider[] All enabled lists
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

    /**
     * Determine whether a list with the given name exists.
     *
     * @param string $name The name of the list
     * @return bool Whether a list with the given name exists
     */
    public function listExists(string $name): bool {
        return isset($this->_lists[$name]);
    }

    /**
     * Get a member list provider with the given name, or create an instance of GroupMemberListProvider if the name is a group ID.
     *
     * @see GroupMemberListProvider
     * @param string $name The name of the list, or the group ID if <code>$for_group</code> is true
     * @param bool $for_group Whether the name is a group ID
     * @return MemberListProvider The member list provider with the given name
     */
    public function getList(string $name, bool $for_group = false): MemberListProvider {
        if (!$this->listExists($name)) {
            if (!$for_group) {
                throw new RuntimeException("Member list '$name' does not exist");
            }

            $group_id = (int) $name;
            return new GroupMemberListProvider($group_id);
        }

        return $this->_lists[$name];
    }

    /**
     * Get the metadata for a given user. Pipes the user through all registered metadata providers.
     *
     * @param User $user The user to get the metadata for
     * @return array The metadata for the given user
     */
    public function getMemberMetadata(User $user): array {
        $metadata = [];
        foreach ($this->_metadata_providers as $provider) {
            $metadata += $provider($user);
        }

        return $metadata;
    }
}
