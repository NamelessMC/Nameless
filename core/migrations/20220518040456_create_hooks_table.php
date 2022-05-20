<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateHooksTable extends AbstractMigration
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
        $table = $this->table('nl2_hooks');

        $table
            ->addColumn('name', 'string', ['length' => 128])
            ->addColumn('action', 'integer', ['length' => 11])
            ->addColumn('url', 'string', ['length' => 2048])
            ->addColumn('events', 'text');

        $table->create();
    }
}
