<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ChangeWidgetsLocationLength extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_widgets');
        $table->changeColumn('location', 'string', ['length' => 16, 'default' => 'right']);
        $table->update();
    }
}
