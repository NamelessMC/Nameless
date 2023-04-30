<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateMemberListsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_member_lists', ['id' => false, 'primary_key' => 'name']);

        $table->addColumn('name', 'string', ['limit' => 64])
            ->addColumn('friendly_name', 'string', ['limit' => 64])
            ->addColumn('module', 'string', ['limit' => 64])
            ->addColumn('enabled', 'boolean', ['default' => true]);

        $table->addIndex(['name', 'friendly_name'], ['unique' => true]);

        $table->create();
    }
}
