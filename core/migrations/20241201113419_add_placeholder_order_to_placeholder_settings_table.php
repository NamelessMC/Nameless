<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddPlaceholderOrderToPlaceholderSettingsTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('nl2_placeholders_settings')
            ->addColumn('order', 'integer', ['length' => 11, 'null' => true, 'default' => null])
            ->update();
    }
}
