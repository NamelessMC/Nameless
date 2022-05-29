<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateHooksTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_hooks');

        $table
            ->addColumn('name', 'string', ['length' => 128])
            ->addColumn('action', 'integer', ['length' => 11])
            ->addColumn('url', 'string', ['length' => 2048])
            ->addColumn('events', 'text');

        $table->create();
    }
}
