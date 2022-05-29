<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUsersGroupsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_users_groups');

        $table
            ->addColumn('user_id', 'integer', ['length' => 11])
            ->addColumn('group_id', 'integer', ['length' => 11])
            ->addColumn('received', 'integer', ['length' => 11, 'default' => 0])
            ->addColumn('expire', 'integer', ['length' => 11, 'default' => 0]);

        $table
            ->addForeignKey('user_id', 'nl2_users', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE',])
            ->addForeignKey('group_id', 'nl2_groups', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE',]);

        $table
            ->addIndex(['user_id', 'group_id'], ['unique' => true]);

        $table->create();
    }
}
