<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTopicsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_topics');

        $table
            ->addColumn('forum_id', 'integer', ['length' => 11])
            ->addColumn('topic_title', 'string', ['length' => 150])
            ->addColumn('topic_creator', 'integer', ['length' => 11])
            ->addColumn('topic_last_user', 'integer', ['length' => 11])
            ->addColumn('topic_date', 'integer', ['length' => 11])
            ->addColumn('topic_reply_date', 'integer', ['length' => 11])
            ->addColumn('topic_views', 'integer', ['length' => 11, 'default' => 0])
            ->addColumn('locked', 'boolean', ['default' => false])
            ->addColumn('sticky', 'boolean', ['default' => false])
            ->addColumn('label', 'integer', ['length' => 11, 'null' => true, 'default' => null])
            ->addColumn('deleted', 'boolean', ['default' => false])
            ->addColumn('labels', 'string', ['length' => 128, 'null' => true, 'default' => null]);

        $table
            ->addForeignKey('forum_id', 'nl2_forums', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('topic_creator', 'nl2_users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('topic_last_user', 'nl2_users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);

        $table->create();

        // avoid circular dependency
        $forums_table = $this->table('nl2_forums');
        $forums_table->addForeignKey('last_topic_posted', 'nl2_topics', 'id', ['delete' => 'SET_NULL', 'update' => 'NO_ACTION']);
        $forums_table->update();
    }
}
