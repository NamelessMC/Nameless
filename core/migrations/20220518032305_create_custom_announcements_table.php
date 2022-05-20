<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCustomAnnouncementsTable extends AbstractMigration
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
        $table = $this->table('nl2_custom_announcements');

        $table
            ->addColumn('pages', 'text')
            ->addColumn('groups', 'text')
            ->addColumn('order', 'integer', ['length' => 11])
            ->addColumn('text_colour', 'string', ['length' => 7])
            ->addColumn('background_colour', 'string', ['length' => 7])
            ->addColumn('icon', 'string', ['length' => 64])
            ->addColumn('closable', 'boolean', ['default' => false])
            ->addColumn('header', 'string', ['length' => 64])
            ->addColumn('message', 'text');

        $table->create();
    }
}
