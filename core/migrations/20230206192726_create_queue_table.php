<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateQueueTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_queue');

        $table
            ->addColumn('module_id', 'integer', ['length' => 11])
            ->addColumn('task', 'string', ['length' => 64])
            ->addColumn('name', 'string', ['length' => 64, 'null' => true, 'default' => null])
            ->addColumn('data', 'text', ['null' => true, 'default' => null])
            ->addColumn('output', 'text', ['null' => true, 'default' => null])
            ->addColumn('scheduled_at', 'integer', ['length' => 11])
            ->addColumn('scheduled_for', 'integer', ['length' => 11])
            ->addColumn('executed_at', 'integer', ['length' => 11, 'null' => true, 'default' => null])
            ->addColumn('status', 'string', ['length' => 12, 'default' => 'ready'])
            ->addColumn('fragment', 'boolean', ['default' => false])
            ->addColumn('fragment_total', 'integer', ['length' => 11, 'null' => true, 'default' => null])
            ->addColumn('fragment_next', 'integer', ['length' => 11, 'null' => true, 'default' => null])
            ->addColumn('attempts', 'integer', ['length' => 11, 'default' => 0])
            ->addColumn('user_id', 'integer', ['length' => 11, 'null' => true, 'default' => null])
            ->addColumn('entity', 'string', ['length' => 64, 'null' => true, 'default' => null])
            ->addColumn('entity_id', 'integer', ['length' => 11, 'null' => true, 'default' => null]);

        $table
            ->addForeignKey('module_id', 'nl2_modules', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('user_id', 'nl2_users', 'id', ['delete' => 'CASCADE']);

        $table->create();
    }
}
