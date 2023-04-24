<?php
/*
 * there is NO SUPPORT offered for this script.
 * this script is provided AS IS and without any warranty.
 * this script was made with the primary goal of making the install process automatic for hosting providers + our API test suite.
 */

function getEnvVar(string $name, string $fallback = null, array $valid_values = null) {
    $value = getenv($name);
    $required = $fallback === null;

    if ($value === false && $required) {
        print("⚠️  Required environment variable '$name' is not set!" . PHP_EOL);
        exit(1);
    }

    if (!$value && $fallback !== null) {
        $value = $fallback;
        print("ℹ️  Environment variable '$name' is not set, using fallback '$fallback'" . PHP_EOL);
    }

    if ($valid_values != null && !in_array($value, $valid_values)) {
        print("⚠️  Environment variable '$name' has invalid value");
        exit(1);
    }

    return $value;
}

if (PHP_SAPI !== 'cli') {
    die('This script must be run from the command line.');
}

if (!isset($argv[1]) || $argv[1] !== '--iSwearIKnowWhatImDoing') {
    print("🚫 You don't know what you're doing." . PHP_EOL);
    exit(1);
}

print(PHP_EOL);

$reinstall = false;
if (isset($argv[2]) && $argv[2] == '--reinstall') {
    $reinstall = true;
    print('🧨 Reinstall mode enabled! ' . PHP_EOL . PHP_EOL);
}

if (!file_exists('./vendor/autoload.php')) {
    print('⚠️  You need to run "composer install" first!' . PHP_EOL);
    exit(1);
}

if (!$reinstall && file_exists('./core/config.php')) {
    print('⚠️  NamelessMC is already installed! ' . PHP_EOL);
    print("🧨 If you want to reinstall, run this script with the '--reinstall' flag." . PHP_EOL);
    exit(1);
}

// check all the required environment variables are set
foreach (['NAMELESS_SITE_NAME', 'NAMELESS_SITE_CONTACT_EMAIL', 'NAMELESS_SITE_OUTGOING_EMAIL', 'NAMELESS_ADMIN_EMAIL'] as $var) {
    getEnvVar($var);
}

$start = microtime(true);

print('🗑  Deleting cache directories...' . PHP_EOL);
// clear the cache directories
$folders = [
    './cache',
    './cache/templates_c'
];
foreach ($folders as $folder) {
    if (is_dir($folder)) {
        $files = glob($folder . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}

if ($reinstall) {
    print('🗑  Deleting old config.php file...' . PHP_EOL);
    // delete the core/config.php file
    if (is_file('./core/config.php')) {
        unlink('./core/config.php');
    }
}

const ROOT_PATH = __DIR__ . '/../..';

print('♻️  Registering autoloader...' . PHP_EOL);
require './vendor/autoload.php';

print('✍️  Creating new config.php file...' . PHP_EOL);
$conf = [
    'mysql' => [
        'host' => getEnvVar('NAMELESS_DATABASE_ADDRESS', '127.0.0.1'),
        'port' => getEnvVar('NAMELESS_DATABASE_PORT', '3306'),
        'username' => getEnvVar('NAMELESS_DATABASE_USERNAME', 'root'),
        'password' => getEnvVar('NAMELESS_DATABASE_PASSWORD', ''),
        'db' => getEnvVar('NAMELESS_DATABASE_NAME', 'nameless'),
        'charset' => getEnvVar('NAMELESS_DATABASE_CHARSET', 'utf8mb4'),
        'collation' => getEnvVar('NAMELESS_DATABASE_COLLATION', 'utf8mb4_unicode_ci'),
        'initialise_charset' => true,
        'initialise_collation' => true,
    ],
    'remember' => [
        'cookie_name' => 'nl2',
        'cookie_expiry' => 604800,
    ],
    'session' => [
        'session_name' => '2user',
        'admin_name' => '2admin',
        'token_name' => '2token',
    ],
    'core' => [
        'hostname' => getEnvVar('NAMELESS_HOSTNAME', 'localhost'),
        'path' => getEnvVar('NAMELESS_PATH', ''),
        'friendly' => getEnvVar('NAMELESS_FRIENDLY_URLS', 'false') === 'true',
        'force_https' => false,
        'force_www' => false,
        'captcha' => false,
        'date_format' => 'd M Y, H:i',
        'trustedProxies' => null,
    ],
];

Config::write($conf);

if ($reinstall) {
    print('🗑️  Deleting old database...' . PHP_EOL);
    $instance = DB::getCustomInstance(
        $conf['mysql']['host'],
        $conf['mysql']['db'],
        $conf['mysql']['username'],
        $conf['mysql']['password'],
        $conf['mysql']['port']
    );
    $instance->query('DROP DATABASE IF EXISTS `' . $conf['mysql']['db'] . '`');
    print('✍️  Creating new database...' . PHP_EOL);
    $instance->query('CREATE DATABASE `' . $conf['mysql']['db'] . '`');
}

print('✍️  Creating tables...' . PHP_EOL);

$message = PhinxAdapter::migrate();

if (!str_contains($message, 'All Done')) {
    print($message);
    exit(1);
}

Session::put('default_language', getEnvVar('NAMELESS_DEFAULT_LANGUAGE', 'en_UK'));

print('✍️  Inserting default data to database...' . PHP_EOL);

$_SESSION['install_timezone'] = in_array($timezone = getEnvVar('NAMELESS_TIMEZONE', 'Europe/London'), DateTimeZone::listIdentifiers())
    ? $timezone
    : 'Europe/London';

DatabaseInitialiser::runPreUser();

Util::setSetting('sitename', getEnvVar('NAMELESS_SITE_NAME'));
Util::setSetting('incoming_email', getEnvVar('NAMELESS_SITE_CONTACT_EMAIL'));
Util::setSetting('outgoing_email', getEnvVar('NAMELESS_SITE_OUTGOING_EMAIL'));
Util::setSetting('email_verification', getEnvVar('NAMELESS_EMAIL_VERIFICATION', '1', ['0', '1']));

print('👮 Creating admin account...' . PHP_EOL);

$username = getEnvVar('NAMELESS_ADMIN_USERNAME', 'admin');
$password = getEnvVar('NAMELESS_ADMIN_PASSWORD', 'password');
$email = getEnvVar('NAMELESS_ADMIN_EMAIL');

$user = new User();
$user->create([
    'username' => $username,
    'nickname' => $username,
    'password' => password_hash($password, PASSWORD_BCRYPT, ['cost' => 13]),
    'pass_method' => 'default',
    'joined' => date('U'),
    'email' => $email,
    'lastip' => '127.0.0.1',
    'active' => true,
    'last_online' => date('U'),
    'language_id' => DB::getInstance()->get('languages', ['is_default', 1])->results()[0]->id,
    'timezone' => $_SESSION['install_timezone'],
    'register_method' => 'nameless',
]);
DB::getInstance()->query('INSERT INTO `nl2_users_groups` (`user_id`, `group_id`, `received`, `expire`) VALUES (?, ?, ?, ?)', [
    1,
    2,
    date('U'),
    0,
]);

$profile = ProfileUtils::getProfile($username);
if ($profile !== null) {
    $result = $profile->getProfileAsArray();
    if (isset($result['uuid']) && !empty($result['uuid'])) {
        $uuid = $result['uuid'];

        DB::getInstance()->insert('users_integrations', [
            'integration_id' => 1,
            'user_id' => 1,
            'identifier' => $uuid,
            'username' => $username,
            'verified' => true,
            'date' => date('U'),
        ]);
    }
}

DatabaseInitialiser::runPostUser();

Config::set('core.installed', true);

print(PHP_EOL . '✅ Installation complete! (Took ' . round(microtime(true) - $start, 2) . ' seconds)' . PHP_EOL);
print(PHP_EOL . '🖥  URL: http://' . $conf['core']['hostname'] . $conf['core']['path']);
print(PHP_EOL . '🔑 Admin username: ' . $username);
print(PHP_EOL . '🔑 Admin email: ' . $email);
print(PHP_EOL . '🔑 Admin password: ' . $password);
print(PHP_EOL);
exit(0);
