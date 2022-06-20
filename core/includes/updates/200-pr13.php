<?php

return new class extends UpgradeScript {

    public function run(): void {
        $trusted_proxies = Config::get('core.trustedProxies');
        // If trusted proxies is an empty array
        if (is_countable($trusted_proxies) && count($trusted_proxies) === 0) {
            // The core/trustedProxies option was mistakenly set during the installer for pr13. If we decide to
            // allow not configuring it, at least it must be in an "unconfigured" state by default so StaffCP
            // can display a warning until it is explicitly set to an empty or non-empty array by the user.
            Config::set('core/trustedProxies', null);
        }

        $trusted_proxies = Config::get('core.trustedProxies');
        // If trusted proxies is an empty array
        if (is_countable($trusted_proxies) && count($trusted_proxies) === 0) {
            // The core/trustedProxies option was mistakenly set during the installer for pr13. If we decide to
            // allow not configuring it, at least it must be in an "unconfigured" state by default so StaffCP
            // can display a warning until it is explicitly set to an empty or non-empty array by the user.
            Config::set('core/trustedProxies', null);
        }

        // TODO Move to phinx migration
        // Migrate settings from core/email.php to core/config.php
        require(ROOT_PATH . '/core/email.php');
        Config::set('email.email', $GLOBALS['email']['email']);
        Config::set('email.username', $GLOBALS['email']['username']);
        Config::set('email.password', $GLOBALS['email']['password']);
        Config::set('email.name', $GLOBALS['email']['name']);
        Config::set('email.host', $GLOBALS['email']['host']);
        Config::set('email.port', $GLOBALS['email']['port']);
        Config::set('email.secure', $GLOBALS['email']['secure']);
        Config::set('email.smtp_auth', $GLOBALS['email']['smtp_auth']);

        $this->runMigrations();

        $this->setVersion('2.0.0');
    }
};
