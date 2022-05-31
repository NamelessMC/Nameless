<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUserProfileWallPostsReactionsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_user_profile_wall_posts_reactions');

        $table
            ->addColumn('user_id', 'integer', ['length' => 11])
            ->addColumn('post_id', 'integer', ['length' => 11])
            ->addColumn('reaction_id', 'integer', ['length' => 11])
            ->addColumn('time', 'integer', ['length' => 11]);

        $table
            ->addForeignKey('user_id', 'nl2_users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('post_id', 'nl2_user_profile_wall_posts', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('reaction_id', 'nl2_reactions', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);

        $table->create();
    }
}
