<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateBlockedUsersTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_blocked_users', ['id' => false, 'primary_key' => ['user_id', 'user_blocked_id']]);

        $table
            ->addColumn('user_id', 'integer', ['length' => 11])
            ->addColumn('user_blocked_id', 'integer', ['length' => 11]);

        $table
            ->addForeignKey('user_id', 'nl2_users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('user_blocked_id', 'nl2_users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);;

        $table
            ->addIndex(['user_id', 'user_blocked_id'], ['unique' => true]);

        $table->create();
    }
}
