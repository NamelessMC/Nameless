<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUserProfileWallPostsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_user_profile_wall_posts');

        $table
            ->addColumn('user_id', 'integer', ['length' => 11])
            ->addColumn('author_id', 'integer', ['length' => 11])
            ->addColumn('time', 'integer', ['length' => 11])
            ->addColumn('content', 'text');

        $table
            ->addForeignKey('user_id', 'nl2_users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('author_id', 'nl2_users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);

        $table->create();
    }
}
