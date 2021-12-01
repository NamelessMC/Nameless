<?php
/*
 * there is NO SUPPORT offered for this script.
 * this script is provided AS IS and without any warranty.
 * this script was made with the primary goal of making the install process automatic for hosting providers + our API test suite.
 */

if (php_sapi_name() != 'cli') {
    die('This script must be run from the command line.');
}

if (!isset($argv[1]) || isset($argv[1]) && $argv[1] !== '--iSwearIKnowWhatImDoing') {
    print("You don't know what you're doing." . PHP_EOL);
    exit(0);
}

print(PHP_EOL);

$reinstall = false;
if (isset($argv[2]) && $argv[2] == '--reinstall') {
    $reinstall = true;
    print('🧨 Reinstall mode enabled! ' . PHP_EOL . PHP_EOL);
}

if (!$reinstall && file_exists('./core/config.php')) {
    print('⚠️  NamelessMC is already installed! ' . PHP_EOL);
    print('🧨 If you want to reinstall, run this script with the --reinstall flag.' . PHP_EOL);
    exit(0);
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

print('🗑  Deleting old config.php file...' . PHP_EOL);
// delete the core/config.php file
if (is_file('./core/config.php')) {
    unlink('./core/config.php');
}

define('ROOT_PATH', dirname(__FILE__));

print('✍️  Creating new config.php file...' . PHP_EOL);
require './cli_install_vars.php';
$conf = [
    'mysql' => [
        'host' => $vars['mysql']['host'],
        'port' => $vars['mysql']['port'],
        'username' => $vars['mysql']['username'],
        'password' => $vars['mysql']['password'],
        'db' => $vars['mysql']['db'],
        'prefix' => 'nl2_',
        'charset' => $vars['mysql']['charset'],
        'engine' => $vars['mysql']['engine'],
        'initialise_charset' => true,
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
        'hostname' => $vars['core']['hostname'],
        'path' => $vars['core']['path'],
        'friendly' => $vars['core']['friendly'],
        'force_https' => false,
        'force_www' => false,
        'captcha' => false,
    ],
    'allowedProxies' => '',
];

file_put_contents(
    './core/config.php',
    '<?php' . PHP_EOL . '$conf = ' . var_export($conf, true) . ';'
);
$GLOBALS['config'] = $conf;

print('♻️  Registering autoloader...' . PHP_EOL);
require './core/autoload.php';

print('🗑️  Deleting old database...' . PHP_EOL);
DB_Custom::getInstance($vars['mysql']['host'], $vars['mysql']['db'], $vars['mysql']['username'], $vars['mysql']['password'], $vars['mysql']['port'])->createQuery('DROP DATABASE IF EXISTS `' . $conf['mysql']['db'] . '`');
print('✍️  Creating new database...' . PHP_EOL);
DB_Custom::getInstance($vars['mysql']['host'], $vars['mysql']['db'], $vars['mysql']['username'], $vars['mysql']['password'], $vars['mysql']['port'])->createQuery('CREATE DATABASE `' . $conf['mysql']['db'] . '`');

print('✍️  Creating tables...' . PHP_EOL);
$queries = new Queries();
$queries->dbInitialise();

Session::put('default_language', $vars['core']['language']);

$nameless_terms = 'This website uses "Nameless" website software. The ' .
    '"Nameless" software creators will not be held responsible for any content ' .
    'that may be experienced whilst browsing this site, nor are they responsible ' .
    'for any loss of data which may come about, for example a hacking attempt. ' .
    'The website is run independently from the software creators, and any content' .
    ' is the responsibility of the website administration.';

print('✍️  Inserting default data to database...' . PHP_EOL);
require './core/installation/views/includes/site_initialize.php';
$queries->create('settings', [
    'name' => 'sitename',
    'value' => Output::getClean($vars['core']['sitename']),
]);
$queries->create('settings', [
    'name' => 'incoming_email',
    'value' => Output::getClean($vars['core']['incoming_email']),
]);
$queries->create('settings', [
    'name' => 'outgoing_email',
    'value' => Output::getClean($vars['core']['outgoing_email']),
]);

print('✍️  Creating admin account...' . PHP_EOL);
$user = new User();
$user->create([
    'username' => Output::getClean($vars['admin_account']['username']),
    'nickname' => Output::getClean($vars['admin_account']['username']),
    'password' => password_hash($vars['admin_account']['password'], PASSWORD_BCRYPT, ['cost' => 13]),
    'pass_method' => 'default',
    'uuid' => $vars['admin_account']['uuid'],
    'joined' => date('U'),
    'email' => Output::getClean($vars['admin_account']['email']),
    'lastip' => '127.0.0.1',
    'active' => 1,
    'last_online' => date('U'),
    'theme_id' => 1,
    'language_id' => $queries->getWhere('languages', ['is_default', '=', 1])[0]->id,
]);
DB::getInstance()->createQuery('INSERT INTO `nl2_users_groups` (`user_id`, `group_id`, `received`, `expire`) VALUES (?, ?, ?, ?)', [
    1,
    2,
    date('U'),
    0,
]);

print(PHP_EOL . '✅ Installation complete! (Took ' . round(microtime(true) - $start, 2) . ' seconds)' . PHP_EOL);
print(PHP_EOL . '🖥  URL: http://' . $vars['core']['hostname'] . $vars['core']['path']);
print(PHP_EOL . '🔑 Admin username: ' . Output::getClean($vars['admin_account']['username']));
print(PHP_EOL . '🔑 Admin email: ' . Output::getClean($vars['admin_account']['email']));
print(PHP_EOL . '🔑 Admin password: ' . Output::getClean($vars['admin_account']['password']));
print(PHP_EOL);
