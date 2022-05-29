<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateMcServersTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_mc_servers');

        $table
            ->addColumn('ip', 'string', ['length' => 64])
            ->addColumn('query_ip', 'string', ['length' => 64])
            ->addColumn('name', 'string', ['length' => 128])
            ->addColumn('is_default', 'boolean', ['default' => false])
            ->addColumn('display', 'boolean', ['default' => true])
            ->addColumn('pre', 'boolean', ['default' => false])
            ->addColumn('player_list', 'boolean', ['default' => true])
            ->addColumn('parent_server', 'integer', ['length' => 11, 'null' => true, 'default' => 0]) // No foreign key because we use 0 for no parent server
            ->addColumn('bungee', 'boolean', ['default' => false])
            ->addColumn('bedrock', 'boolean', ['default' => false])
            ->addColumn('port', 'integer', ['length' => 11, 'null' => true, 'default' => null])
            ->addColumn('query_port', 'integer', ['length' => 11, 'default' => 25565, 'null' => true])
            ->addColumn('banner_background', 'string', ['length' => 32, 'null' => true, 'default' => 'background.png'])
            ->addColumn('show_ip', 'boolean', ['default' => true])
            ->addColumn('order', 'integer', ['length' => 11, 'default' => 1]);

        $table->create();
    }
}
