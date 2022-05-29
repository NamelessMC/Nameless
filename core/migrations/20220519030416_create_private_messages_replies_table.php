<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreatePrivateMessagesRepliesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_private_messages_replies');

        $table
            ->addColumn('pm_id', 'integer', ['length' => 11])
            ->addColumn('author_id', 'integer', ['length' => 11])
            ->addColumn('created', 'integer', ['length' => 11])
            ->addColumn('content', 'text');

        $table
            ->addForeignKey('pm_id', 'nl2_private_messages', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('author_id', 'nl2_users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);

        $table->create();
    }
}
