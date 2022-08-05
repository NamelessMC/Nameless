<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class LogInfoMediumText extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_logs');
        $table->changeColumn('info', 'text', ['limit' => MysqlAdapter::TEXT_MEDIUM]);
        $table->update();
    }
}
