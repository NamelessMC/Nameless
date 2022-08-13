<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddUserSessionActivityColumns extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_users_session');
        $table->addColumn('remember_me', 'boolean', ['default' => false]);
        $table->addColumn('active', 'boolean', ['default' => false]);
        $table->addColumn('device_name', 'string', ['length' => 256, 'null' => true, 'default' => null]);
        $table->addColumn('last_seen', 'integer', ['length' => 11, 'null' => true, 'default' => null]);
        $table->addColumn('login_method', 'string', ['length' => 32]);
        $table->addIndex('hash', ['unique' => true]);
        $table->update();

        // Remove old data
        $table->truncate();
    }
}
