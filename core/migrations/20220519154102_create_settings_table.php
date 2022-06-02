<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateSettingsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_settings');

        $table
            ->addColumn('name', 'string', ['length' => 64])
            ->addColumn('value', 'string', ['length' => 2048, 'null' => true])
            ->addIndex('name', ['unique' => true]);

        $table->create();
    }
}
