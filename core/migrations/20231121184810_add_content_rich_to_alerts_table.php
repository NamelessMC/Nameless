<?php

declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class AddContentRichToAlertsTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('nl2_alerts')
            ->addColumn('content_rich', 'text', ['default' => null, 'limit' => MysqlAdapter::TEXT_MEDIUM, 'null' => true])
            ->update();
    }
}
