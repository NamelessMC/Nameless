<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class MoveEmailSettingsToConfig extends AbstractMigration {

    public function change(): void {
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

        try {
            unlink(ROOT_PATH . '/core/email.php');
        } catch (Exception $ignored) {
            // Not a big problem if we can't delete the file, for example if it's not writable. It's not worth crashing the upgrader.
        }
    }

}
