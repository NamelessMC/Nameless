<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ReportsReportedIdNullable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_reports');
        $table->changeColumn('reported_id', 'integer', ['length' => 11, 'null' => true, 'default' => null]);
        $table->update();
    }
}
