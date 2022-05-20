<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateIpBansTable extends AbstractMigration
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
        $table = $this->table('nl2_ip_bans');

        $table
            ->addColumn('ip', 'string', ['length' => 128])
            ->addColumn('banned_by', 'integer', ['length' => 11, 'null' => true])
            ->addColumn('banned_at', 'integer', ['length' => 11])
            ->addColumn('reason', 'text', ['null' => true, 'default' => null]);

        $table
            ->addForeignKey('banned_by', 'nl2_users', 'id', ['delete' => 'SET_NULL', 'update' => 'NO_ACTION']);

        $table->create();
    }
}
