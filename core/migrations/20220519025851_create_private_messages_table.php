<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreatePrivateMessagesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_private_messages');

        $table
            ->addColumn('author_id', 'integer', ['length' => 11])
            ->addColumn('title', 'string', ['length' => 128])
            ->addColumn('created', 'integer', ['length' => 11])
            ->addColumn('last_reply_user', 'integer', ['length' => 11])
            ->addColumn('last_reply_date', 'integer', ['length' => 11]);

        $table
            ->addForeignKey('author_id', 'nl2_users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('last_reply_user', 'nl2_users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);

        $table->create();
    }
}
