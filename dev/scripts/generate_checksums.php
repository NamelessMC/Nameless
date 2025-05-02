<?php

if (PHP_SAPI !== 'cli') {
    die('This script must be run from the command line.');
}

const ROOT_PATH = __DIR__ . '/../..';
require ROOT_PATH . '/vendor/autoload.php';

$checksums = IntegrityChecker::generateChecksums();
IntegrityChecker::saveChecksums($checksums);
