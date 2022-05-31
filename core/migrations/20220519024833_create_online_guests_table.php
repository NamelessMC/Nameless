<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateOnlineGuestsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_online_guests');

        $table
            ->addColumn('ip', 'string', ['length' => 128])
            ->addColumn('last_seen', 'integer', ['length' => 11]);

        $table->create();
    }
}
