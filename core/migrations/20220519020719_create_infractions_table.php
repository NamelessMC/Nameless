<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateInfractionsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('nl2_infractions');

        $table
            ->addColumn('type', 'integer', ['length' => 11])
            ->addColumn('punished', 'integer', ['length' => 11])
            ->addColumn('staff', 'integer', ['length' => 11])
            ->addColumn('reason', 'text')
            ->addColumn('infraction_date', 'datetime')
            ->addColumn('created', 'integer', ['length' => 11])
            ->addColumn('acknowledged', 'boolean', ['default' => false])
            ->addColumn('revoked', 'boolean', ['default' => false])
            ->addColumn('revoked_by', 'integer', ['length' => 11, 'null' => true, 'default' => null])
            ->addColumn('revoked_at', 'integer', ['length' => 11, 'null' => true, 'default' => null]);

        $table
            ->addForeignKey('punished', 'nl2_users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('staff', 'nl2_users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('revoked_by', 'nl2_users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);

        $table->create();
    }
}
