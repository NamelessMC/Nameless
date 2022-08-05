<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class ForumPostMediumText extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_posts');
        $table->changeColumn('post_content', 'text', ['limit' => MysqlAdapter::TEXT_MEDIUM]);
        $table->update();
    }
}
