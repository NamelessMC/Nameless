<?php

const ROOT_PATH = __DIR__ . '/../..';
require ROOT_PATH . '/vendor/autoload.php';

$checksums = IntegrityChecker::generate_checksums();

IntegrityChecker::save_checksums($checksums);
