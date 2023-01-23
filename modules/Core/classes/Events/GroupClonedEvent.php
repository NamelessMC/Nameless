<?php

class GroupClonedEvent extends AbstractEvent {

    public static function description(): array {
        return ['admin', 'clone_group'];
    }

    public static function name(): string {
        return 'cloneGroup';
    }

    public Group $group;
    public int $group_id;
    public Group $cloned_group;
    public int $cloned_group_id;

    public function __construct(int $group_id, int $cloned_group_id) {
        $this->group = Group::find($group_id);
        $this->group_id = $group_id;
        $this->cloned_group = Group::find($cloned_group_id);
        $this->cloned_group_id = $cloned_group_id;
    }
}
