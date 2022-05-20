<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateWidgetsTable extends AbstractMigration
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
        $table = $this->table('nl2_widgets');

        $table
            ->addColumn('name', 'string', ['length' => 20])
            ->addColumn('enabled', 'boolean', ['default' => false])
            ->addColumn('pages', 'text', ['default' => '[]'])
            ->addColumn('order', 'integer', ['length' => 11, 'default' => 10])
            ->addColumn('location', 'string', ['length' => 5, 'default' => 'right']);

        $table->create();
    }
}
