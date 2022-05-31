<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUsersAdminSessionTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_users_admin_session');

        $table
            ->addColumn('user_id', 'integer', ['length' => 11])
            ->addColumn('hash', 'string', ['length' => 64]);

        $table
            ->addForeignKey('user_id', 'nl2_users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);

        $table->create();
    }
}
