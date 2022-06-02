<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateReportsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_reports');

        $table
            ->addColumn('type', 'integer', ['length' => 1])
            ->addColumn('reporter_id', 'integer', ['length' => 11, 'null' => true, 'default' => null])
            ->addColumn('reported_id', 'integer', ['length' => 11, 'null' => true, 'default' => null])
            ->addColumn('status', 'integer', ['length' => 1, 'default' => 0])
            ->addColumn('date_reported', 'datetime')
            ->addColumn('date_updated', 'datetime')
            ->addColumn('reported', 'integer', ['length' => 11])
            ->addColumn('updated', 'integer', ['length' => 11])
            ->addColumn('report_reason', 'text')
            ->addColumn('updated_by', 'integer', ['length' => 11])
            ->addColumn('reported_post', 'integer', ['length' => 11, 'null' => true, 'default' => null])
            ->addColumn('link', 'string', ['length' => 128, 'null' => true, 'default' => null])
            ->addColumn('reported_mcname', 'string', ['length' => 64, 'null' => true, 'default' => null])
            ->addColumn('reported_uuid', 'string', ['length' => 64, 'null' => true, 'default' => null]);

        $table
            ->addForeignKey('reporter_id', 'nl2_users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('reported_id', 'nl2_users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('updated_by', 'nl2_users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('reported_post', 'nl2_posts', 'id', ['delete' => 'SET_NULL', 'update' => 'CASCADE']);

        $table->create();
    }
}
