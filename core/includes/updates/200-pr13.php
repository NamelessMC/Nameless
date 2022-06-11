<?php

class Nameless200 extends UpgradeScript {

    public function run(): void {
        $db = DB::getInstance();

        try {
            $db->query('ALTER TABLE nl2_groups DROP COLUMN group_html_lg;');
        } catch (PDOException $e) {
            if (!str_contains($e->getMessage(), 'check that column/key exists')) {
                $this->log($e->getMessage());
            }
        }

        $this->setVersion('2.0.0');
    }
}
