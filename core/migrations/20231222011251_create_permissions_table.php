<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreatePermissionsTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('nl2_permissions', ['id' => false, 'primary_key' => ['permissible', 'permissible_id', 'permission']])
            ->addColumn('permissible', 'string', ['limit' => 255])
            ->addColumn('permissible_id', 'integer')
            ->addColumn('permission', 'string', ['limit' => 255])
            ->addColumn('value', 'integer')
            ->addIndex(['permissible', 'permissible_id', 'permission'], ['unique' => true])
            ->create();

        foreach (Group::all() as $group) {
            NamelessContainer::getInstance()
            ->get(PermissionCache::class)
            ->upsert(Group::class, $group->id, json_decode($group->permissions, true));
        }

        $this->table('nl2_groups')
            ->removeColumn('permissions')
            ->update();
    }
}
