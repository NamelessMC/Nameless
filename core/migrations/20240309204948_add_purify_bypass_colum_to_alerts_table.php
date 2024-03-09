<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddPurifyBypassColumToAlertsTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('nl2_alerts')
            ->addColumn('bypass_purify', 'boolean', ['default' => false])
            ->update();
    }
}
