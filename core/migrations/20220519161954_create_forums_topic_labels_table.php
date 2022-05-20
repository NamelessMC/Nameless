<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateForumsTopicLabelsTable extends AbstractMigration
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
        $table = $this->table('nl2_forums_topic_labels');

        $table
            ->addColumn('fids', 'string', ['length' => 128])
            ->addColumn('name', 'string', ['length' => 32])
            ->addColumn('label', 'string', ['length' => 20])
            ->addColumn('gids', 'string', ['length' => 256, 'null' => true, 'default' => null]);

        $table->create();
    }
}
