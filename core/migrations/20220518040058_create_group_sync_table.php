<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateGroupSyncTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_group_sync');

        $table
            ->addColumn('website_group_id', 'integer', ['length' => 11])
            ->addColumn('discord_role_id', 'biginteger', ['null' => true, 'default' => null])
            ->addColumn('ingame_rank_name', 'string', ['length' => 64, 'null' => true, 'default' => null]);

        $table
            ->addForeignKey('website_group_id', 'nl2_groups', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);

        $table->create();
    }
}
