<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateLanguagesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_languages');

        $table
            ->addColumn('name', 'string', ['length' => 64])
            ->addColumn('short_code', 'string', ['length' => 64])
            ->addColumn('is_default', 'boolean', ['default' => false]);

        $table->create();
    }
}
