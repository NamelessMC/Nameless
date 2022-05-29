<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUuidCacheTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_uuid_cache');

        $table
            ->addColumn('mcname', 'string', ['limit' => 20])
            ->addColumn('uuid', 'string', ['limit' => 64]);

        $table->create();
    }
}
