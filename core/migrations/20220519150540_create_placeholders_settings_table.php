<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreatePlaceholdersSettingsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_placeholders_settings', ['id' => false, 'primary_key' => ['server_id', 'name']]);

        $table
            ->addColumn('server_id', 'integer', ['length' => 11])
            ->addColumn('name', 'string', ['length' => 186])
            ->addColumn('friendly_name', 'string', ['length' => 256, 'null' => true, 'default' => null])
            ->addColumn('show_on_profile', 'boolean', ['default' => true])
            ->addColumn('show_on_forum', 'boolean', ['default' => true])
            ->addColumn('leaderboard', 'boolean', ['default' => false])
            ->addColumn('leaderboard_title', 'string', ['length' => 36, 'null' => true, 'default' => null])
            ->addColumn('leaderboard_sort', 'string', ['length' => 4, 'default' => 'DESC']);

        $table
            ->addForeignKey('server_id', 'nl2_mc_servers', 'id', ['delete' => 'CASCADE']);

        $table->create();
    }
}
