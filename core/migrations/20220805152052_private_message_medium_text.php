<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class PrivateMessageMediumText extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_private_messages_replies');
        $table->changeColumn('content', 'text', ['limit' => MysqlAdapter::TEXT_MEDIUM]);
        $table->update();
    }
}
