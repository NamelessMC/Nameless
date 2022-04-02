<?php
/*
 * Structure:
 * name: Language name (required)
 * htmlCode: HTML lang code (optional, will default to en if not provided)
 * rtl: Boolean to mark as right to left language (optional)
 * pluralForm: Function to calculate which plural form to use (optional)
 */
define('LANGUAGES', [
    'en_UK' => [
        'name' => 'English UK',
    ],
    'nl_NL' => [
        'name' => 'Dutch',
        'htmlCode' => 'nl'
    ],
    'ru_RU' => [
        'name' => 'Russian',
        'htmlCode' => 'ru',
//        TODO: `define`d constants cannot contain functions...
//        'pluralForm' => static function ($count, $forms) {
//            return $count % 10 == 1 && $count % 100 != 11 ? $forms[0] : ($count % 10 >= 2 && $count % 10 <= 4 && ($count % 100 < 10 || $count % 100 >= 20) ? $forms[1] : $forms[2]);
//        },
    ],
]);
