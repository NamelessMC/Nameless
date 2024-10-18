<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddSessionExpiryColumnToUsersSessionTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('nl2_users_session')
            ->addColumn('expires_at', 'integer', ['length' => 11, 'null' => true, 'default' => null])
            ->update();
    }
}
