<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddModuleSettingsColumn extends AbstractMigration {

    public function change(): void {
        $table = $this->table('nl2_settings');
        $table->addColumn('module', 'string', ['length' => 32, 'null' => true, 'default' => null]);
        $table->removeIndex(['name']);
        $table->addIndex(['name', 'module'], ['unique' => true]);
        $table->update();
    }

}
