<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddAuthMeColumnsToUserTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('nl2_users')
            ->addColumn('register_method', 'string', ['default' => null])
            ->addColumn('authme_sync_password', 'boolean', ['default' => false])
            ->update();
    }
}
