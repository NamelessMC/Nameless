<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddOrderColumnToReactionsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_reactions');

        $table->addColumn('order', 'integer', ['length' => 11, 'default' => 1]);

        $table->update();
    }
}
