<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateGroupsTable extends AbstractMigration
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
        $table = $this->table('nl2_groups');

        $table
            ->addColumn('name', 'string', ['length' => 20])
            ->addColumn('group_html', 'string', ['length' => 1024])
            ->addColumn('group_username_color', 'string', ['length' => 256, 'null' => true, 'default' => null])
            ->addColumn('group_username_css', 'string', ['length' => 256, 'null' => true, 'default' => null])
            ->addColumn('admin_cp', 'boolean', ['default' => false])
            ->addColumn('staff', 'boolean', ['default' => false])
            ->addColumn('permissions', 'text', ['default' => '[]'])
            ->addColumn('default_group', 'boolean', ['default' => false])
            ->addColumn('order', 'integer', ['length' => 11, 'default' => 1])
            ->addColumn('force_tfa', 'boolean', ['default' => false])
            ->addColumn('deleted', 'boolean', ['default' => false]);

        $table->create();
    }
}
