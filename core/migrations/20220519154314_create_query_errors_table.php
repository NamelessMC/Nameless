<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateQueryErrorsTable extends AbstractMigration
{
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
