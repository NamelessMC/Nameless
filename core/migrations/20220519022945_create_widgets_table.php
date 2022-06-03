<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateWidgetsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_widgets');

        $table
            ->addColumn('name', 'string', ['length' => 20])
            ->addColumn('enabled', 'boolean', ['default' => false])
            ->addColumn('pages', 'text')
            ->addColumn('order', 'integer', ['length' => 11, 'default' => 10])
            ->addColumn('location', 'string', ['length' => 5, 'default' => 'right']);

        $table->create();
    }
}
