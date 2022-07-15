<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class MoveEmailSettingsToConfig extends AbstractMigration {

    public function change(): void {
        // New installations won't have the old email.php config file
        if (!is_file(ROOT_PATH . '/core/email.php')) {
            return;
        }

        // Migrate settings from core/email.php to core/config.php
        // This will also convert the file to the "new" `return [...]` format instead of `$conf = [...]`
        require(ROOT_PATH . '/core/email.php');
        $email_config = $GLOBALS['email'];

        Config::set('email', [
            'email' => $email_config['email'],
            'username' => $email_config['username'],
            'password' => $email_config['password'],
            'name' => $email_config['name'],
            'host' => $email_config['host'],
            'port' => $email_config['port'],
            'secure' => $email_config['secure'],
            'smtp_auth' => $email_config['smtp_auth'],
        ]);

        try {
            unlink(ROOT_PATH . '/core/email.php');
        } catch (Exception $ignored) {
            // Not a big problem if we can't delete the file, for example if it's not writable. It's not worth crashing the upgrader.
        }
    }

}
