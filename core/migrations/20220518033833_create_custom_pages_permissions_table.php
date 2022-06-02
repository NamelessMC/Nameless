<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCustomPagesPermissionsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_custom_pages_permissions');

        $table
            ->addColumn('page_id', 'integer', ['length' => 11])
            ->addColumn('group_id', 'integer', ['length' => 11])
            ->addColumn('view', 'boolean', ['default' => false]);

        $table
            ->addForeignKey('page_id', 'nl2_custom_pages', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);

        $table
            ->addIndex(['page_id', 'group_id'], ['unique' => true]);

        $table->create();
    }
}
