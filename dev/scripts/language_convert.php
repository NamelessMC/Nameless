<?php

if (PHP_SAPI !== 'cli') {
    die('This script must be run from the command line.');
}

if (!isset($argv[1])) {
    echo 'Usage: php language_convert.php --in <path> --out <path>' . PHP_EOL;
    exit(1);
}

$in = null;
if (isset($argv[1], $argv[2]) && $argv[1] == '--in') {
    $in = $argv[2];

    if (!is_dir($in)) {
        echo '⚠️  Input directory does not exist: ' . $in . PHP_EOL;
        exit(1);
    }

    echo '📂 Input folder set to: ' . $in . PHP_EOL;
} else {
    echo '🚫 Please specify an input folder with --in <path>' . PHP_EOL;
    exit(1);
}

$out = null;
if (isset($argv[3], $argv[4]) && $argv[3] == '--out') {
    $out = $argv[4];

    if (!is_dir($out)) {
        echo '⚠️  Output directory does not exist: ' . $out . PHP_EOL;
        exit(1);
    }

    echo '📂 Output folder set to: ' . $out . PHP_EOL;
} else {
    echo '🚫 Please specify an output folder with --out <path>' . PHP_EOL;
    exit(1);
}

echo PHP_EOL;

$files = scandir($in);
$parts = explode('/', $in);
$output_file_name = $parts[count($parts) - 1];
$data = [];

foreach ($files as $file) {
    if (substr($file, -4) !== '.php') {
        continue;
    }

    $filePath = $in . '/' . $file;
    if (!is_file($filePath)) {
        continue;
    }

    require_once($filePath);
    $file_name = explode('.', $file)[0];
    foreach ($language as $term => $value) {
        $data[$file_name . '/' . $term] = $value;
    }
}

ksort($data);
$json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
file_put_contents($out . '/' . $output_file_name . '.json', $json);

echo '☑️  Converted files in: ' . $in . ' to ' . $out . '/' . $output_file_name . '.json'  . PHP_EOL;

echo PHP_EOL;

echo '🎉  Done!' . PHP_EOL;
