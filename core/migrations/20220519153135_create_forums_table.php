<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateForumsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_forums');

        $table
            ->addColumn('forum_title', 'string', ['length' => 150])
            ->addColumn('forum_description', 'string', ['length' => 255, 'null' => true, 'default' => null])
            ->addColumn('last_post_date', 'integer', ['length' => 11, 'null' => true, 'default' => null])
            ->addColumn('last_user_posted', 'integer', ['length' => 11, 'null' => true, 'default' => null])
            ->addColumn('last_topic_posted', 'integer', ['length' => 11, 'null' => true, 'default' => null])
            ->addColumn('parent', 'integer', ['length' => 11, 'default' => 0])
            ->addColumn('forum_order', 'integer', ['length' => 11])
            ->addColumn('news', 'boolean', ['default' => false])
            ->addColumn('forum_type', 'string', ['length' => 255, 'default' => 'forum'])
            ->addColumn('redirect_forum', 'boolean', ['default' => false])
            ->addColumn('redirect_url', 'string', ['length' => 255, 'null' => true, 'default' => null])
            ->addColumn('icon', 'string', ['length' => 255, 'null' => true, 'default' => null])
            ->addColumn('topic_placeholder', 'text', ['null' => true])
            ->addColumn('hooks', 'text', ['null' => true, 'default' => null])
            ->addColumn('default_labels', 'text', ['null' => true, 'default' => null]);

        $table
            ->addForeignKey('last_user_posted', 'nl2_users', 'id', ['delete' => 'SET_NULL', 'update' => 'NO_ACTION']);

        $table->create();
    }
}
