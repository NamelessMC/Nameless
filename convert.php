<?php
$languages = [
    'Chinese' => 'zh',
    'Chinese(Simplified)' => 'zh_CN',
    'Czech' => 'cs_CZ',
    'Danish' => 'da_DK',
    'EnglishUK' => 'en_UK',
    'EnglishUS' => 'en_US',
    'French' => 'fr_FR',
    'German' => 'de_DE',
    'Greek' => 'el_GR',
    'Italian' => 'it_IT',
    'Japanese' => 'ja_JP',
    'Lithuanian' => 'lt_LT',
    'Norwegian' => 'no_NO',
    'Polish' => 'pl_PL',
    'Portuguese' => 'pt_PT',
    'Romanian' => 'ro_RO',
    'Russian' => 'ru_RU',
    'Slovak' => 'sk_SK',
    'Spanish' => 'es_ES',
    'SpanishSE' => 'es_ES',
    'SwedishSE' => 'sv_SE',
    'Thai' => 'th_TH',
    'Turkish' => 'tr_TR',
];

$paths = [
    '/custom/languages/',
    '/modules/Cookie Consent/language/',
    '/modules/Discord Integration/language/',
    '/modules/Forum/language/',
];

foreach ($languages as $lang => $code) {
    foreach ($paths as $path) {

        // check if directory exists
        if (!file_exists(__DIR__ . $path . $lang . '/')) {
            continue;
        }

        $files = scandir(__DIR__ . $path . $lang . '/');

        $json = [];

        foreach ($files as $file) {
            if (in_array($file, ['.', '..', 'version.php'])) {
                continue;
            }

            if (substr($file, -4) !== '.php') {
                continue;
            }

            require_once(__DIR__ . $path . $lang . '/' . $file);

            $file_name = explode('.', $file)[0];

            foreach ($language as $key => $value) {
                $json[$file_name . '/' . $key] = $value;
            }
        }

        if (empty($json)) {
            continue;
        }

        ksort($json);
        file_put_contents(__DIR__ . $path . $code . '.json', json_encode($json, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
    }
}
