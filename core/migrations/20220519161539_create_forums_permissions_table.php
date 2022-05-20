<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateForumsPermissionsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
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
