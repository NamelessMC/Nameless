<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class DropPanelTemplatesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_panel_templates');
        $table->drop();
        
        $table->update();
    }
}
