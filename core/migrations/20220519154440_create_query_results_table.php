<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateQueryResultsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
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
