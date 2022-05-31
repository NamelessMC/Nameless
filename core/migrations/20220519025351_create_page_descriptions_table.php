<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreatePageDescriptionsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_page_descriptions');

        $table
            ->addColumn('page', 'string', ['length' => 64])
            ->addColumn('description', 'text', ['null' => true, 'default' => null])
            ->addColumn('tags', 'text');

        $table->create();
    }
}
