<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreatePostsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_posts');

        $table
            ->addColumn('forum_id', 'integer', ['length' => 11])
            ->addColumn('topic_id', 'integer', ['length' => 11])
            ->addColumn('post_creator', 'integer', ['length' => 11])
            ->addColumn('post_content', 'text')
            ->addColumn('post_date', 'datetime')
            ->addColumn('last_edited', 'integer', ['length' => 11, 'null' => true, 'default' => null])
            ->addColumn('ip_address', 'string', ['length' => 128, 'null' => true, 'default' => null])
            ->addColumn('deleted', 'boolean', ['default' => false])
            ->addColumn('created', 'integer', ['length' => 11]);

        $table
            ->addForeignKey('forum_id', 'nl2_forums', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('topic_id', 'nl2_topics', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('post_creator', 'nl2_users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);

        $table
            ->addIndex('topic_id');

        $table->create();
    }
}
