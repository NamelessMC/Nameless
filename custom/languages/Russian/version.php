<?php
/*
 *  Made by Samerton
 *  Translated by Oniel, Я научу тебя шить XIMI
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Russian Language - Language version
 */

/*
 *  Russian translation by Oniel
 *  https://github.com/0niel
 */
// Which version of NamelessMC is this language file updated to?
$language_version = '2.0.0-pr9';
$language_html = 'ru';

// Plural function
if (!function_exists('pluralForm')) {
    function pluralForm($n, $forms) {
        return $n % 10 == 1 && $n % 100 != 11 ? $forms[0] : ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20) ? $forms[1] : $forms[2]);
    }
}
