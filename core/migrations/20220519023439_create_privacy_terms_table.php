<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreatePrivacyTermsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_privacy_terms');

        $table
            ->addColumn('name', 'string', ['length' => 8])
            ->addColumn('value', 'text');

        $table->create();
    }
}
