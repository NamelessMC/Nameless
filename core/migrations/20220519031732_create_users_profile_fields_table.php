<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUsersProfileFieldsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_users_profile_fields');

        $table
            ->addColumn('user_id', 'integer', ['length' => 11])
            ->addColumn('field_id', 'integer', ['length' => 11])
            ->addColumn('value', 'text')
            ->addColumn('updated', 'integer', ['length' => 11]);

        $table
            ->addForeignKey('user_id', 'nl2_users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('field_id', 'nl2_profile_fields', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);

        $table->create();
    }
}
