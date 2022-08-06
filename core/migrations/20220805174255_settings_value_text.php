<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class SettingsValueText extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_settings');
        $table->changeColumn('value', 'text', ['null' => true]);
        $table->update();
    }
}
