<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUsersIpsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_users_ips');

        $table
            ->addColumn('user_id', 'integer', ['length' => 11])
            ->addColumn('ip', 'string', ['length' => 128]);

        $table
            ->addForeignKey('user_id', 'nl2_users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);

        $table->create();
    }
}
