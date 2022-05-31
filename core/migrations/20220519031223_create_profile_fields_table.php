<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateProfileFieldsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_profile_fields');

        $table
            ->addColumn('name', 'string', ['length' => 255])
            ->addColumn('type', 'integer', ['length' => 11, 'default' => 1])
            ->addColumn('public', 'boolean', ['default' => true])
            ->addColumn('required', 'boolean', ['default' => false])
            ->addColumn('forum_posts', 'boolean', ['default' => false])
            ->addColumn('editable', 'boolean', ['default' => true])
            ->addColumn('description', 'text', ['null' => true, 'default' => null])
            ->addColumn('length', 'integer', ['length' => 11, 'null' => true, 'default' => null]);

        $table->create();
    }
}
