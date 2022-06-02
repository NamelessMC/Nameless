<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateGroupsTemplatesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_groups_templates');

        $table
            ->addColumn('group_id', 'integer', ['length' => 11])
            ->addColumn('template_id', 'integer', ['length' => 11])
            ->addColumn('can_use_template', 'boolean', ['default' => false]);

        $table
            ->addForeignKey('template_id', 'nl2_templates', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);

        $table->create();
    }
}
