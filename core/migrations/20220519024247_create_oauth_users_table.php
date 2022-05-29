<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateOauthUsersTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_oauth_users', ['id' => false, 'primary_key' => ['user_id', 'provider', 'provider_id']]);

        $table
            ->addColumn('user_id', 'integer', ['length' => 11])
            ->addColumn('provider', 'string', ['length' => 256])
            ->addColumn('provider_id', 'string', ['length' => 256]);

        $table
            ->addForeignKey('user_id', 'nl2_users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('provider', 'nl2_oauth', 'provider', ['delete' => 'CASCADE', 'update' => 'CASCADE']);

        $table->create();
    }
}
