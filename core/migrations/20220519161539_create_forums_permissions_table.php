<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateForumsPermissionsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_forums_permissions');

        $table
            ->addColumn('group_id', 'integer', ['length' => 11]) // No foreign key because we use 0 for guests
            ->addColumn('forum_id', 'integer', ['length' => 11])
            ->addColumn('view', 'boolean', ['default' => false])
            ->addColumn('create_topic', 'boolean', ['default' => false])
            ->addColumn('edit_topic', 'boolean', ['default' => false])
            ->addColumn('create_post', 'boolean', ['default' => false])
            ->addColumn('view_other_topics', 'boolean', ['default' => false])
            ->addColumn('moderate', 'boolean', ['default' => false]);

        $table
            ->addForeignKey('forum_id', 'nl2_forums', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);

        $table->create();
    }
}
