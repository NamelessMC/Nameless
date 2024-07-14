<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class RenameDeviceNameColumnUsersSessionTable extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('nl2_users_session');
        $table->renameColumn('device_name', 'user_agent')->save();
    }

    public function down(): void
    {
        $table = $this->table('nl2_users_session');
        $table->renameColumn('user_agent', 'device_name')->save();
    }
}
