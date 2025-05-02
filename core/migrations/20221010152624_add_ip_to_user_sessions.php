<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddIpToUserSessions extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_users_session');
        $table->addColumn('ip', 'string', ['length' => 128, 'null' => true]);
        $table->update();
    }
}
