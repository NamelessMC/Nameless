<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateLogsTable extends AbstractMigration
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
        $table = $this->table('nl2_logs');

        $table
            ->addColumn('time', 'integer', ['length' => 11])
            ->addColumn('action', 'text')
            ->addColumn('ip', 'string', ['length' => 128, 'null' => true, 'default' => null])
            ->addColumn('user_id', 'integer', ['length' => 11]) // No foreign key because we default to 0 if not logged in
            ->addColumn('info', 'text', ['null' => true, 'default' => null]);

        $table->create();
    }
}
