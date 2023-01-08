<?php

const ROOT_PATH = __DIR__ . '/../..';
require ROOT_PATH . '/vendor/autoload.php';

$checksums = IntegrityChecker::generateChecksums();
IntegrityChecker::saveChecksums($checksums);
