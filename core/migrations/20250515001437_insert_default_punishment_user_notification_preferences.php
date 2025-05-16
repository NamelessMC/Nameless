<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class InsertDefaultPunishmentUserNotificationPreferences extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        DB::getInstance()->query('INSERT IGNORE INTO nl2_users_notification_preferences (user_id, `type`, email, alert) SELECT id, \'punishment\', 1, 1 FROM nl2_users WHERE id NOT IN (SELECT user_id FROM nl2_users_notification_preferences WHERE `type` = \'punishment\')');
    }
}
