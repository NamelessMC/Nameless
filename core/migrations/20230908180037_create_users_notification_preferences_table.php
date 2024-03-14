<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUsersNotificationPreferencesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_users_notification_preferences');

        $table
            ->addColumn('user_id', 'integer', ['length' => 11])
            ->addColumn('type', 'string', ['length' => 64])
            ->addColumn('alert', 'boolean', ['default' => false])
            ->addColumn('email', 'boolean', ['default' => false]);

        $table->addForeignKey('user_id', 'nl2_users', 'id', ['delete' => 'CASCADE']);

        $table->create();
    }
}
