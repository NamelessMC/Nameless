<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateReportsCommentsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_reports_comments');

        $table
            ->addColumn('report_id', 'integer', ['length' => 11])
            ->addColumn('commenter_id', 'integer', ['length' => 11])
            ->addColumn('comment_date', 'datetime')
            ->addColumn('date', 'integer', ['length' => 11])
            ->addColumn('comment_content', 'text');

        $table
            ->addForeignKey('report_id', 'nl2_reports', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('commenter_id', 'nl2_users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);

        $table->create();
    }
}
