<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUsersIpsTable extends AbstractMigration
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
        $table = $this->table('nl2_users_ips');

        $table
            ->addColumn('user_id', 'integer', ['length' => 11])
            ->addColumn('ip', 'string', ['length' => 128]);

        $table
            ->addForeignKey('user_id', 'nl2_users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);

        $table->create();
    }
}