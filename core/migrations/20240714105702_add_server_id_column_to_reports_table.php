<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddServerIdColumnToReportsTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('nl2_reports')
            ->addColumn('server_id', 'integer', ['null' => true, 'default' => null])
            ->addForeignKey('server_id', 'nl2_mc_servers', 'id', ['delete' => 'SET_NULL'])
            ->update();
    }
}
