<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class DropUuidCacheTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('nl2_uuid_cache')->drop()->update();
    }
}
