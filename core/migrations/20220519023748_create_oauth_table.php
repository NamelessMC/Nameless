<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateOauthTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_oauth', ['id' => false, 'primary_key' => ['provider']]);

        $table
            ->addColumn('provider', 'string', ['length' => 256])
            ->addColumn('enabled', 'boolean', ['default' => false])
            ->addColumn('client_id', 'string', ['length' => 256, 'null' => true, 'default' => null])
            ->addColumn('client_secret', 'string', ['length' => 256, 'null' => true, 'default' => null]);

        $table->create();
    }
}
