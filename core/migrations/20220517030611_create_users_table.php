<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUsersTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_users');

        $table
            ->addColumn('username', 'string', ['length' => 20])
            ->addColumn('nickname', 'string', ['length' => 255])
            ->addColumn('password', 'string', ['length' => 255])
            ->addColumn('pass_method', 'string', ['length' => 12, 'default' => 'default'])
            ->addColumn('joined', 'integer', ['length' => 11])
            ->addColumn('email', 'string', ['length' => 64])
            ->addColumn('isbanned', 'boolean', ['default' => false])
            ->addColumn('lastip', 'string', ['length' => 128, 'null' => true])
            ->addColumn('active', 'boolean', ['default' => false])
            ->addColumn('signature', 'text', ['null' => true, 'default' => null])
            ->addColumn('profile_views', 'integer', ['length' => 11, 'default' => 0])
            ->addColumn('reputation', 'integer', ['length' => 11, 'default' => 0])
            ->addColumn('reset_code', 'string', ['length' => 64, 'null' => true])
            ->addColumn('has_avatar', 'boolean', ['default' => false])
            ->addColumn('gravatar', 'boolean', ['default' => false])
            ->addColumn('topic_updates', 'boolean', ['default' => true])
            ->addColumn('private_profile', 'boolean', ['default' => false])
            ->addColumn('last_online', 'integer', ['length' => 11, 'null' => true])
            ->addColumn('user_title', 'string', ['length' => 64, 'null' => true])
            ->addColumn('theme_id', 'integer', ['length' => 11, 'null' => true])
            ->addColumn('language_id', 'integer', ['length' => 11, 'null' => true])
            ->addColumn('warning_points', 'integer', ['length' => 11, 'default' => 0])
            ->addColumn('night_mode', 'boolean', ['length' => 1, 'default' => false])
            ->addColumn('tfa_enabled', 'boolean', ['default' => false])
            ->addColumn('tfa_type', 'integer', ['length' => 10, 'default' => 0])
            ->addColumn('tfa_secret', 'string', ['length' => 256, 'null' => true])
            ->addColumn('tfa_complete', 'boolean', ['default' => false])
            ->addColumn('banner', 'string', ['length' => 64, 'null' => true])
            ->addColumn('timezone', 'string', ['length' => 32, 'default' => 'Europe/London'])
            ->addColumn('avatar_updated', 'integer', ['length' => 11, 'null' => true]);

        $table
            ->addIndex(['username', 'email'], ['unique' => true])
            ->addIndex(['id', 'last_online']);

        $table
            ->addForeignKey('theme_id', 'nl2_templates', 'id', ['delete' => 'SET_NULL'])
            ->addForeignKey('language_id', 'nl2_languages', 'id', ['delete' => 'SET_NULL']);

        $table->create();
    }
}
