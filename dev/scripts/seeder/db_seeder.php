<?php
if (PHP_SAPI !== 'cli') {
    die('This script must be run from the command line.');
}

define('ROOT_PATH', __DIR__ . '/../..');
require 'vendor/autoload.php';

$classes = [
    'Seeder.php',
    'UserSeeder.php',
    'UserProfilePostSeeder.php',
    'MinecraftServerSeeder.php',
    'MinecraftPlaceholderSeeder.php',
    'MinecraftPlaceholderDataSeeder.php',
    'ProfileFieldsSeeder.php',
    'ProfileFieldsDataSeeder.php',
    'ForumCategorySeeder.php',
    'ForumSubforumSeeder.php',
    'ForumTopicSeeder.php',
    'ForumPostSeeder.php',
];

foreach ($classes as $class) {
    require $class;
}

/** @var Seeder[] $seeders */
$seeders = array_map(static function (string $class) {
    $class = explode('.', $class)[0];
    return new $class();
}, array_slice($classes, 1));

$faker = Faker\Factory::create();

$db = DB::getInstance();

$wipe = false;
if (isset($argv[1]) && $argv[1] === 'wipe') {
    $wipe = true;
    print('🧨 Wipe mode enabled!' . PHP_EOL);
}

if (!$wipe && $db->get('users', ['id', '>', 0])->count() > 0) {
    print '🛑 Database is not empty and wipe mode is not enabled!' . PHP_EOL;
    exit(1);
}

if ($wipe) {
    $db->query('SET FOREIGN_KEY_CHECKS = 0');
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
