<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUsersPlaceholdersTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_users_placeholders', ['id' => false, 'primary_key' => ['server_id', 'uuid', 'name']]);

        $table
            ->addColumn('server_id', 'integer', ['length' => 11])
            ->addColumn('uuid', 'varbinary', ['length' => 16])
            ->addColumn('name', 'string', ['length' => 186])
            ->addColumn('value', 'text')
            ->addColumn('last_updated', 'integer', ['length' => 11]);

        $table
            ->addForeignKey('server_id', 'nl2_mc_servers', 'id', ['delete' => 'CASCADE']);

        $table->create();
    }
}
