<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateReactionsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_reactions');

        $table
            ->addColumn('name', 'string', ['length' => 16])
            ->addColumn('html', 'string', ['length' => 255])
            ->addColumn('enabled', 'boolean', ['default' => true])
            ->addColumn('type', 'integer', ['length' => 1, 'default' => 2]);

        $table->create();
    }
}
