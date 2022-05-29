<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTopicsFollowingTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_topics_following');

        $table
            ->addColumn('topic_id', 'integer', ['length' => 11])
            ->addColumn('user_id', 'integer', ['length' => 11])
            ->addColumn('existing_alerts', 'boolean', ['default' => false]);

        $table
            ->addForeignKey('topic_id', 'nl2_topics', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('user_id', 'nl2_users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);

        $table->create();
    }
}
