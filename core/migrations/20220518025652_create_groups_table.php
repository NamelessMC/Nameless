<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateGroupsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_groups');

        $table
            ->addColumn('name', 'string', ['length' => 20])
            ->addColumn('group_html', 'string', ['length' => 1024])
            ->addColumn('group_username_color', 'string', ['length' => 256, 'null' => true, 'default' => null])
            ->addColumn('group_username_css', 'string', ['length' => 256, 'null' => true, 'default' => null])
            ->addColumn('admin_cp', 'boolean', ['default' => false])
            ->addColumn('staff', 'boolean', ['default' => false])
            ->addColumn('permissions', 'text')
            ->addColumn('default_group', 'boolean', ['default' => false])
            ->addColumn('order', 'integer', ['length' => 11, 'default' => 1])
            ->addColumn('force_tfa', 'boolean', ['default' => false])
            ->addColumn('deleted', 'boolean', ['default' => false]);

        $table->create();
    }
}
