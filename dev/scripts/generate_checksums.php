<?php

require __DIR__ . '/../../core/classes/Misc/IntegrityChecker.php';
const ROOT_PATH = __DIR__ . '/../..';

$checksums = IntegrityChecker::generate_checksums();

IntegrityChecker::save_checksums($checksums);
