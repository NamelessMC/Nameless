<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCustomPagesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_custom_pages');

        $table
            ->addColumn('url', 'string', ['length' => 255])
            ->addColumn('title', 'string', ['length' => 255])
            ->addColumn('content', 'text')
            ->addColumn('link_location', 'integer', ['length' => 1, 'default' => 1])
            ->addColumn('redirect', 'boolean', ['default' => false])
            ->addColumn('link', 'string', ['length' => 512, 'null' => true, 'default' => null])
            ->addColumn('target', 'boolean', ['default' => false])
            ->addColumn('icon', 'string', ['length' => 64, 'null' => true, 'default' => null])
            ->addColumn('all_html', 'boolean', ['default' => false])
            ->addColumn('sitemap', 'boolean', ['default' => false])
            ->addColumn('basic', 'boolean', ['default' => false]);

        $table->create();
    }
}
