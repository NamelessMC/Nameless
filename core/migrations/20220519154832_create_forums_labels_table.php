<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateForumsLabelsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_forums_labels');

        $table
            ->addColumn('name', 'string', ['length' => 32])
            ->addColumn('html', 'string', ['length' => 1024]);

        $table->create();
    }
}
