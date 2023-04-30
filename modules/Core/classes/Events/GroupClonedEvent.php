<?php

class GroupClonedEvent extends AbstractEvent {

    public Group $group;
    public Group $cloned_group;

    public function __construct(int $group_id, int $cloned_group_id) {
        $this->group = Group::find($group_id);
        $this->cloned_group = Group::find($cloned_group_id);
    }

    public static function name(): string {
        return 'cloneGroup';
    }

    public static function description(): string {
        return (new Language())->get('admin', 'clone_group');
    }

    public static function internal(): bool {
        return true;
    }
}
