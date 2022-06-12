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

        $trusted_proxies = Config::get('core/trustedProxies');
        // If trusted proxies is an empty array
        if ($trusted_proxies !== null &&
            is_countable($trusted_proxies) &&
            count($trusted_proxies) == 0) {

            // The core/trustedProxies option was mistakenly set during the installer for pr13. If we decide to
            // allow not configuring it, at least it must be in an "unconfigured" state by default so StaffCP
            // can display a warning until it is explicitly set to an empty or non-empty array by the user.
            Config::set('core/trustedProxies', null);
        }

        $this->setVersion('2.0.0');
    }
}
