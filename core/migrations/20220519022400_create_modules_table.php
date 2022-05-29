<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateModulesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_modules');

        $table
            ->addColumn('name', 'string', ['length' => 64])
            ->addColumn('enabled', 'boolean', ['default' => false]);

        $table
            ->addIndex(['name'], ['unique' => true]);

        $table->create();
    }
}
