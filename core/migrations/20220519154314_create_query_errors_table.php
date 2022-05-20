<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateQueryErrorsTable extends AbstractMigration
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
        $table = $this->table('nl2_query_errors');

        $table
            ->addColumn('date', 'integer', ['length' => 11])
            ->addColumn('error', 'text')
            ->addColumn('ip', 'string', ['length' => 128])
            ->addColumn('port', 'integer', ['length' => 6]);

        $table->create();
    }
}
