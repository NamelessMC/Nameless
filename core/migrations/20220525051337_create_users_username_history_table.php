<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUsersUsernameHistoryTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_users_username_history');

        $table
            ->addColumn('user_id', 'integer', ['length' => 11])
            ->addColumn('changed_to', 'string', ['length' => 64])
            ->addColumn('changed_at', 'integer', ['length' => 11])
            ->addColumn('original', 'boolean', ['default' => false]);

        $table
            ->addForeignKey('user_id', 'nl2_users', 'id', ['delete' => 'CASCADE']);

        $table->create();
    }
}
