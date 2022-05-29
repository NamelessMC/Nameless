<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUsersIntegrationsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_users_integrations');

        $table
            ->addColumn('user_id', 'integer', ['length' => 11])
            ->addColumn('integration_id', 'integer', ['length' => 11])
            ->addColumn('identifier', 'string', ['length' => 64, 'null' => true, 'default' => null])
            ->addColumn('username', 'string', ['length' => 32])
            ->addColumn('verified', 'boolean', ['default' => false])
            ->addColumn('date', 'integer', ['length' => 11])
            ->addColumn('code', 'string', ['length' => 64, 'null' => true, 'default' => null])
            ->addColumn('show_publicly', 'boolean', ['default' => true])
            ->addColumn('last_sync', 'integer', ['length' => 11, 'default' => 0]);

        $table
            ->addForeignKey('user_id', 'nl2_users', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('integration_id', 'nl2_integrations', 'id', ['delete' => 'CASCADE']);

        $table
            ->addIndex(['user_id', 'integration_id'], ['unique' => true]);

        $table->create();
    }
}
