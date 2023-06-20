<?php

if (PHP_SAPI !== 'cli') {
    die('This script must be run from the command line.');
}

const ROOT_PATH = __DIR__ . '/../..';
require ROOT_PATH . '/vendor/autoload.php';

$errors = IntegrityChecker::verifyChecksums();

if (count($errors) === 0) {
    echo "No errors found!\n";
    return;
}

foreach ($errors as $error) {
    echo $error . "\n";
}

exit(1);
