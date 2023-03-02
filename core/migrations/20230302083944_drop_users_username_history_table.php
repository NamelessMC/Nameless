<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class DropUsersUsernameHistoryTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('nl2_users_username_history')->drop()->update();
    }
}
