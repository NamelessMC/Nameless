<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UpdateForumTopicKeyCascades extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_topics');
        $table->dropForeignKey('topic_last_user');

        $table->changeColumn('topic_last_user', 'integer', ['length' => 11, 'null' => true, 'default' => null]);
        $table->addForeignKey('topic_last_user', 'nl2_users', 'id', ['delete' => 'SET_NULL', 'update' => 'CASCADE']);
        $table->update();
    }
}
