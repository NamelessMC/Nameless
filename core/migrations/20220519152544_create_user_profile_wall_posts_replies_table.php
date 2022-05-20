<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUserProfileWallPostsRepliesTable extends AbstractMigration
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
        $table = $this->table('nl2_user_profile_wall_posts_replies');

        $table
            ->addColumn('post_id', 'integer', ['length' => 11])
            ->addColumn('author_id', 'integer', ['length' => 11])
            ->addColumn('time', 'integer', ['length' => 11])
            ->addColumn('content', 'text');

        $table
            ->addForeignKey('post_id', 'nl2_user_profile_wall_posts', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('author_id', 'nl2_users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);

        $table->create();
    }
}
