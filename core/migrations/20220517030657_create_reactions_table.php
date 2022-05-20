<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateReactionsTable extends AbstractMigration
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
        $table = $this->table('nl2_reactions');

        $table
            ->addColumn('name', 'string', ['length' => 16])
            ->addColumn('html', 'string', ['length' => 255])
            ->addColumn('enabled', 'boolean', ['default' => true])
            ->addColumn('type', 'integer', ['length' => 1, 'default' => 2]);

        $table->create();
    }
}
