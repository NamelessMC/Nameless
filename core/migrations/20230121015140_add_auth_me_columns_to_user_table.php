<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddAuthMeColumnsToUserTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('nl2_users')
            ->addColumn('authme_registered', 'boolean', ['default' => false])
            ->addColumn('authme_sync_password', 'boolean', ['default' => false])
            ->update();
    }
}
