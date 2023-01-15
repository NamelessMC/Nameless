<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UsersNightModeNullable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_users');
        $table->changeColumn('night_mode', 'boolean', ['length' => 1, 'null' => true, 'default' => null]);
        $table->update();
        $this->execute('UPDATE nl2_users SET night_mode = NULL WHERE night_mode = 0');
    }
}
