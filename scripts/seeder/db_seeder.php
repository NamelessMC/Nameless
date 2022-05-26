<?php
if (PHP_SAPI !== 'cli') {
    die('This script must be run from the command line.');
}

require 'vendor/autoload.php';
require 'core/config.php';

$classes = [
    'Seeder.php',
    'UserSeeder.php',
    'UserProfilePostSeeder.php',
    'MinecraftServerSeeder.php',
    'MinecraftPlaceholderSeeder.php',
    'MinecraftPlaceholderDataSeeder.php',
];

foreach ($classes as $class) {
    require $class;
}

/** @var Seeder[] $seeders */
$seeders = [
    new UserSeeder,
    new UserProfilePostSeeder,
    new MinecraftServerSeeder,
    new MinecraftPlaceholderSeeder,
    new MinecraftPlaceholderDataSeeder,
];

$faker = Faker\Factory::create();

$db = DB_Custom::getInstance(
    $conf['mysql']['host'],
    $conf['mysql']['db'],
    $conf['mysql']['username'],
    $conf['mysql']['password'],
    3306,
    'nl2_'
);

$wipe = false;
if (isset($argv[1]) && $argv[1] === '--wipe') {
    $wipe = true;
    print('🧨 Wipe mode enabled!' . PHP_EOL);
}

if (!$wipe && $db->get('users', ['id', '>', 0])->count() > 0) {
    print '🛑 Database is not empty and wipe mode is not enabled!' . PHP_EOL;
    exit(1);
}

if ($wipe) {
    foreach ($seeders as $seeder) {
        foreach ($seeder->tables as $table) {
            $db->query("TRUNCATE {$table}");
        }
    }
    print '🧨 Deleted existing data!' . PHP_EOL;
}

print PHP_EOL;

$start = microtime(true);
foreach ($seeders as $seeder) {
    $seeder->seed($db, $faker);
}

print PHP_EOL;

print '🪄  Seeding complete! (' . round((microtime(true) - $start), 2) . 's)' . PHP_EOL;
