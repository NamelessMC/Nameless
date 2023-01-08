<?php

require __DIR__ . '/../../core/classes/Misc/IntegrityChecker.php';
const ROOT_PATH = __DIR__ . '/../..';

$errors = IntegrityChecker::verify_checksums();

if (count($errors) === 0) {
    echo "No errors found!\n";
    return;
}

foreach ($errors as $error) {
    echo $error . "\n";
}

exit(1);
