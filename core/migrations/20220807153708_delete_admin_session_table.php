<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class DeleteAdminSessionTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_users_admin_session');
        $table->drop();
        $table->update();
    }
}
