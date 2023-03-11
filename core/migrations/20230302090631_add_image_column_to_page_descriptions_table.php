<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddImageColumnToPageDescriptionsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_page_descriptions');
        $table->addColumn('image', 'string', ['limit' => 512, 'null' => true, 'default' => null])->update();
    }
}
