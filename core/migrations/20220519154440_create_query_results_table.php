<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateQueryResultsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_query_results');

        $table
            ->addColumn('server_id', 'integer', ['length' => 11])
            ->addColumn('queried_at', 'integer', ['length' => 11])
            ->addColumn('players_online', 'integer', ['length' => 11])
            ->addColumn('groups', 'text', ['null' => true, 'default' => null]);

        $table
            ->addForeignKey('server_id', 'nl2_mc_servers', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);

        $table->create();
    }
}
